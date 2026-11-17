<?php
/**
 * Клиент Telegram Bot API.
 *
 * @package Telegram_Zayavki
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Запросы к Bot API без записи токена в журнал.
 */
class TZ_Telegram {
	/**
	 * Токен бота.
	 *
	 * @var string
	 */
	private $token;

	/**
	 * @param string $token Токен бота.
	 */
	public function __construct($token) {
		$this->token = trim((string) $token);
	}

	/**
	 * Проверяет токен и возвращает данные бота.
	 *
	 * @return array<string, mixed>|WP_Error
	 */
	public function get_me() {
		return $this->request('getMe');
	}

	/**
	 * Снимает webhook, чтобы getUpdates мог увидеть группы.
	 *
	 * @return array<string, mixed>|true|WP_Error
	 */
	public function delete_webhook() {
		return $this->request('deleteWebhook', array('drop_pending_updates' => false));
	}

	/**
	 * Непрочитанные обновления бота.
	 *
	 * @return array<int, array<string, mixed>>|WP_Error
	 */
	public function pull_updates() {
		$offset = (int) get_option(TZ_Settings::OFFSET, 0);
		$all = array();

		for ($page = 0; $page < 10; $page++) {
			$args = array(
				'timeout' => 0,
				'limit' => 100,
			);
			if ($offset > 0) {
				$args['offset'] = $offset;
			}

			$batch = $this->request('getUpdates', $args);
			if (is_wp_error($batch)) {
				return $batch;
			}
			if (!is_array($batch) || $batch === array()) {
				break;
			}

			foreach ($batch as $update) {
				if (!is_array($update)) {
					continue;
				}
				$all[] = $update;
				$offset = max($offset, ((int) ($update['update_id'] ?? 0)) + 1);
			}

			if (count($batch) < 100) {
				break;
			}
		}

		if ($offset > 0) {
			update_option(TZ_Settings::OFFSET, $offset, false);
		}

		return $all;
	}

	/**
	 * Группы из обновлений: новые чаты и чаты, которые бот покинул.
	 *
	 * @param array<int, array<string, mixed>> $updates Обновления.
	 * @return array{0: array<string, array<string, string>>, 1: array<int, string>}
	 */
	public function chats_from_updates($updates) {
		$chats = array();
		$left = array();

		foreach ($updates as $update) {
			if (!empty($update['my_chat_member']) && is_array($update['my_chat_member'])) {
				$member = $update['my_chat_member'];
				$chat = isset($member['chat']) && is_array($member['chat']) ? $member['chat'] : array();
				$status = '';
				if (isset($member['new_chat_member']) && is_array($member['new_chat_member'])) {
					$status = (string) ($member['new_chat_member']['status'] ?? '');
				}
				if (in_array($status, array('left', 'kicked'), true) && isset($chat['id'])) {
					$left[] = (string) $chat['id'];
					continue;
				}
				$this->remember_chat($chats, $chat);
			}

			foreach (array('message', 'edited_message', 'channel_post') as $key) {
				if (empty($update[$key]['chat']) || !is_array($update[$key]['chat'])) {
					continue;
				}
				$this->remember_chat($chats, $update[$key]['chat']);
			}
		}

		return array($chats, $left);
	}

	/**
	 * Проверяет, что бот ещё состоит в чате, и обновляет название.
	 *
	 * @param string $chat_id Идентификатор чата.
	 * @return array<string, string>|WP_Error
	 */
	public function refresh_chat($chat_id) {
		$result = $this->request('getChat', array('chat_id' => $chat_id));
		if (is_wp_error($result)) {
			return $result;
		}
		if (!is_array($result)) {
			return new WP_Error('tz_chat', 'Telegram не вернул данные группы.');
		}

		$type = (string) ($result['type'] ?? '');
		if (!in_array($type, array('group', 'supergroup'), true)) {
			return new WP_Error('tz_chat_type', 'Этот чат не является группой.');
		}

		return array(
			'id' => (string) ($result['id'] ?? $chat_id),
			'title' => (string) ($result['title'] ?? $chat_id),
			'type' => $type,
		);
	}

	/**
	 * Текстовое сообщение.
	 *
	 * @param string $chat_id Чат.
	 * @param string $text    Текст.
	 * @return array<string, mixed>|WP_Error
	 */
	public function send_message($chat_id, $text) {
		return $this->request(
			'sendMessage',
			array(
				'chat_id' => $chat_id,
				'text' => $text,
				'parse_mode' => 'HTML',
				'disable_web_page_preview' => true,
			)
		);
	}

	/**
	 * Файл к заявке.
	 *
	 * @param string $chat_id Чат.
	 * @param string $path    Путь к файлу на сервере.
	 * @param string $caption Подпись.
	 * @return array<string, mixed>|WP_Error
	 */
	public function send_document($chat_id, $path, $caption) {
		if (!is_readable($path)) {
			return new WP_Error('tz_file', 'Файл заявки недоступен.');
		}

		$boundary = '----tz' . wp_generate_password(24, false, false);
		$eol = "\r\n";
		$filename = basename($path);
		$checked = wp_check_filetype($filename);
		$mime = !empty($checked['type']) ? $checked['type'] : 'application/octet-stream';
		$contents = file_get_contents($path);
		if ($contents === false) {
			return new WP_Error('tz_file', 'Не удалось прочитать файл заявки.');
		}

		$body = '';
		$fields = array(
			'chat_id' => $chat_id,
			'caption' => function_exists('mb_substr') ? mb_substr($caption, 0, 1000) : substr($caption, 0, 1000),
		);
		foreach ($fields as $name => $value) {
			$body .= '--' . $boundary . $eol;
			$body .= 'Content-Disposition: form-data; name="' . $name . '"' . $eol . $eol;
			$body .= $value . $eol;
		}
		$body .= '--' . $boundary . $eol;
		$body .= 'Content-Disposition: form-data; name="document"; filename="' . $filename . '"' . $eol;
		$body .= 'Content-Type: ' . $mime . $eol . $eol;
		$body .= $contents . $eol;
		$body .= '--' . $boundary . '--' . $eol;

		return $this->request(
			'sendDocument',
			array(),
			array(
				'headers' => array(
					'Content-Type' => 'multipart/form-data; boundary=' . $boundary,
				),
				'body' => $body,
				'timeout' => 30,
			)
		);
	}

	/**
	 * Добавляет группу в список.
	 *
	 * @param array<string, array<string, string>> $chats Список.
	 * @param array<string, mixed>                 $chat  Чат из Telegram.
	 */
	private function remember_chat(&$chats, $chat) {
		$type = (string) ($chat['type'] ?? '');
		if (!in_array($type, array('group', 'supergroup'), true) || !isset($chat['id'])) {
			return;
		}

		$id = (string) $chat['id'];
		$chats[$id] = array(
			'id' => $id,
			'title' => (string) ($chat['title'] ?? $id),
			'type' => $type,
		);
	}

	/**
	 * Вызов метода Bot API.
	 *
	 * @param string               $method  Метод.
	 * @param array<string, mixed> $params  Параметры.
	 * @param array<string, mixed> $http    Дополнительные аргументы HTTP.
	 * @param int                  $attempt Номер попытки. С хостинга api.telegram.org иногда не отвечает с первого раза.
	 * @return mixed|WP_Error
	 */
	private function request($method, $params = array(), $http = array(), $attempt = 1) {
		if ($this->token === '') {
			return new WP_Error('tz_no_token', 'Сначала сохраните токен бота.');
		}

		$args = array(
			'timeout' => 15,
			'body' => $params,
		);
		if ($http !== array()) {
			$args = array_merge($args, $http);
			if (!isset($args['timeout'])) {
				$args['timeout'] = 15;
			}
		}

		$response = wp_remote_post(
			'https://api.telegram.org/bot' . $this->token . '/' . rawurlencode($method),
			$args
		);

		if (is_wp_error($response)) {
			if ($attempt < 3 && $method !== 'getUpdates') {
				return $this->request($method, $params, $http, $attempt + 1);
			}
			return new WP_Error('tz_http', 'Нет соединения с Telegram. ' . $response->get_error_message());
		}

		$code = (int) wp_remote_retrieve_response_code($response);
		$data = json_decode(wp_remote_retrieve_body($response), true);
		if (!is_array($data) || empty($data['ok'])) {
			$description = is_array($data) && !empty($data['description'])
				? (string) $data['description']
				: 'Telegram вернул ошибку.';
			$status = is_array($data) && isset($data['error_code']) ? (int) $data['error_code'] : $code;
			return new WP_Error('tz_telegram', $description, array('status' => $status));
		}

		return $data['result'];
	}
}
