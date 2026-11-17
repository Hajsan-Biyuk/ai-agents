<?php
/**
 * Заявки Contact Form 7.
 *
 * @package Telegram_Zayavki
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Отправка выбранных форм после успешной доставки письма.
 */
class TZ_CF7 {
	/**
	 * Экземпляр.
	 *
	 * @var self|null
	 */
	private static $instance = null;

	/**
	 * @return self
	 */
	public static function instance() {
		if (self::$instance === null) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Регистрирует хук.
	 */
	public function hooks() {
		add_action('wpcf7_mail_sent', array($this, 'send'), 20, 1);
	}

	/**
	 * Отправляет заявку, если форма отмечена в настройках.
	 *
	 * @param WPCF7_ContactForm $form Форма.
	 */
	public function send($form) {
		if (!is_object($form) || !TZ_Settings::can_send() || !class_exists('WPCF7_Submission')) {
			return;
		}

		$form_id = method_exists($form, 'id') ? (int) $form->id() : 0;
		$settings = TZ_Settings::get();
		if (!in_array($form_id, $settings['cf7_forms'], true)) {
			return;
		}

		$submission = WPCF7_Submission::get_instance();
		if (!$submission) {
			return;
		}

		$posted = $submission->get_posted_data();
		if (!is_array($posted)) {
			$posted = array();
		}

		$page_url = '';
		if (method_exists($submission, 'get_meta')) {
			$page_url = (string) $submission->get_meta('url');
		}
		if ($page_url === '' && !empty($posted['url-adress'])) {
			$page_url = (string) $posted['url-adress'];
		}

		$settings = TZ_Settings::get();
		$client = new TZ_Telegram($settings['bot_token']);
		$title = method_exists($form, 'title') ? $form->title() : 'Заявка';
		$result = $client->send_message($settings['chat_id'], TZ_Messages::cf7($form, $posted, $page_url));

		if (is_wp_error($result)) {
			TZ_Settings::set_last_error('Форма «' . $title . '»: ' . $result->get_error_message());
			return;
		}

		TZ_Settings::clear_last_error();
		$this->send_files($client, $settings['chat_id'], $submission, $title);
	}

	/**
	 * Прикреплённые файлы отдельными сообщениями.
	 *
	 * @param TZ_Telegram       $client     Клиент.
	 * @param string            $chat_id    Чат.
	 * @param WPCF7_Submission  $submission Отправка формы.
	 * @param string            $title      Название формы.
	 */
	private function send_files($client, $chat_id, $submission, $title) {
		if (!method_exists($submission, 'uploaded_files')) {
			return;
		}

		$uploaded = $submission->uploaded_files();
		if (!is_array($uploaded)) {
			return;
		}

		foreach ($uploaded as $paths) {
			if (!is_array($paths)) {
				$paths = array($paths);
			}
			foreach ($paths as $path) {
				if (!is_string($path) || $path === '') {
					continue;
				}
				$result = $client->send_document($chat_id, $path, 'Файл к заявке: ' . $title);
				if (is_wp_error($result)) {
					TZ_Settings::set_last_error('Файл формы «' . $title . '»: ' . $result->get_error_message());
				}
			}
		}
	}
}
