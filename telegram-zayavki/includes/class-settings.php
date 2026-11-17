<?php
/**
 * Настройки плагина.
 *
 * @package Telegram_Zayavki
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Чтение и запись опции настроек.
 */
class TZ_Settings {
	const OPTION = 'telegram_zayavki_settings';
	const OFFSET = 'telegram_zayavki_offset';
	const LAST_ERROR = 'telegram_zayavki_last_error';

	/**
	 * Настройки с заполненными значениями по умолчанию.
	 *
	 * @return array<string, mixed>
	 */
	public static function get() {
		$saved = get_option(self::OPTION, array());
		if (!is_array($saved)) {
			$saved = array();
		}

		$defaults = array(
			'bot_token' => '',
			'bot_username' => '',
			'chat_id' => '',
			'chats' => array(),
			'cf7_forms' => array(),
			'woo_enabled' => 0,
		);

		$settings = array_merge($defaults, $saved);
		$settings['bot_token'] = is_string($settings['bot_token']) ? trim($settings['bot_token']) : '';
		$settings['bot_username'] = is_string($settings['bot_username']) ? $settings['bot_username'] : '';
		$settings['chat_id'] = is_scalar($settings['chat_id']) ? (string) $settings['chat_id'] : '';
		$settings['chats'] = is_array($settings['chats']) ? $settings['chats'] : array();
		$settings['cf7_forms'] = array_values(array_unique(array_map('intval', (array) $settings['cf7_forms'])));
		$settings['woo_enabled'] = empty($settings['woo_enabled']) ? 0 : 1;

		return $settings;
	}

	/**
	 * Сохраняет настройки.
	 *
	 * @param array<string, mixed> $settings Настройки.
	 */
	public static function update($settings) {
		update_option(self::OPTION, $settings);
	}

	/**
	 * Токен похож на токен Bot API.
	 *
	 * @param string $token Токен.
	 */
	public static function is_token($token) {
		return (bool) preg_match('/^\d+:[A-Za-z0-9_-]+$/', $token);
	}

	/**
	 * Можно отправлять сообщения.
	 */
	public static function can_send() {
		$settings = self::get();
		return $settings['bot_token'] !== '' && $settings['chat_id'] !== '';
	}

	/**
	 * Запоминает ошибку доставки.
	 *
	 * @param string $message Текст ошибки.
	 */
	public static function set_last_error($message) {
		update_option(
			self::LAST_ERROR,
			array(
				'time' => time(),
				'text' => $message,
			),
			false
		);
	}

	/**
	 * Сбрасывает ошибку доставки.
	 */
	public static function clear_last_error() {
		delete_option(self::LAST_ERROR);
	}

	/**
	 * Последняя ошибка доставки.
	 *
	 * @return array{time: int, text: string}|null
	 */
	public static function last_error() {
		$error = get_option(self::LAST_ERROR, null);
		if (!is_array($error) || empty($error['text'])) {
			return null;
		}

		return array(
			'time' => isset($error['time']) ? (int) $error['time'] : 0,
			'text' => (string) $error['text'],
		);
	}
}
