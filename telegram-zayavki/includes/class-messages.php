<?php
/**
 * Текст сообщений для Telegram.
 *
 * @package Telegram_Zayavki
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Сборка HTML-сообщений.
 */
class TZ_Messages {
	/**
	 * Экранирует текст для parse_mode HTML.
	 *
	 * @param string $text Текст.
	 */
	public static function escape($text) {
		return htmlspecialchars((string) $text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
	}

	/**
	 * Обрезает сообщение до лимита Telegram.
	 *
	 * @param string $text  Текст.
	 * @param int    $limit Лимит символов.
	 */
	public static function limit($text, $limit = 4000) {
		if (function_exists('mb_strlen') && function_exists('mb_substr')) {
			if (mb_strlen($text) <= $limit) {
				return $text;
			}
			return mb_substr($text, 0, $limit - 1) . '…';
		}

		if (strlen($text) <= $limit) {
			return $text;
		}
		return substr($text, 0, $limit - 3) . '...';
	}

	/**
	 * Сообщение о заявке Contact Form 7.
	 *
	 * @param WPCF7_ContactForm $form       Форма.
	 * @param array<string, mixed> $posted  Поля.
	 * @param string            $page_url   Адрес страницы.
	 */
	public static function cf7($form, $posted, $page_url) {
		$title = method_exists($form, 'title') ? $form->title() : 'Заявка';
		$lines = array('<b>Новая заявка: ' . self::escape($title) . '</b>');
		$skip = self::cf7_skip_names($form);
		$labels = array(
			'your-name' => 'Имя',
			'your-tel' => 'Телефон',
			'your-phone' => 'Телефон',
			'phone' => 'Телефон',
			'tel' => 'Телефон',
			'your-email' => 'E-mail',
			'email' => 'E-mail',
			'your-message' => 'Сообщение',
			'your-subject' => 'Тема',
			'your-title' => 'Услуга / страница',
			'your-rezult' => 'Квиз',
			'url-adress' => 'Страница',
		);

		$used = array();
		foreach (array_keys($labels) as $key) {
			if (!isset($posted[$key]) || isset($skip[$key])) {
				continue;
			}
			$line = self::field_line($labels[$key], $posted[$key]);
			if ($line !== '') {
				$lines[] = $line;
				$used[$key] = true;
			}
		}

		$rest = array();
		foreach ($posted as $key => $value) {
			if (!is_string($key) || isset($used[$key]) || isset($skip[$key]) || strpos($key, '_') === 0) {
				continue;
			}
			$rest[$key] = $value;
		}
		ksort($rest);
		foreach ($rest as $key => $value) {
			$line = self::field_line($key, $value);
			if ($line !== '') {
				$lines[] = $line;
			}
		}

		if ($page_url !== '' && empty($used['url-adress'])) {
			// Без ?ysclid / ?yclid / utm — по ним заказчик сам поймёт источник.
			$lines[] = '<b>Страница:</b> ' . self::escape(preg_replace('/[?#].*$/', '', $page_url));
		}
		$lines = array_merge($lines, TZ_Source::lines());

		return self::limit(implode("\n", $lines));
	}

	/**
	 * Сообщение о заказе WooCommerce.
	 *
	 * @param WC_Order $order Заказ.
	 */
	public static function order($order) {
		$name = trim($order->get_formatted_billing_full_name());
		$lines = array(
			'<b>Новый заказ №' . self::escape($order->get_order_number()) . '</b>',
		);

		if ($name !== '') {
			$lines[] = '<b>Имя:</b> ' . self::escape($name);
		}
		if ($order->get_billing_phone() !== '') {
			$lines[] = '<b>Телефон:</b> ' . self::escape($order->get_billing_phone());
		}
		if ($order->get_billing_email() !== '') {
			$lines[] = '<b>E-mail:</b> ' . self::escape($order->get_billing_email());
		}

		$items = array();
		foreach ($order->get_items() as $item) {
			if (!is_object($item) || !method_exists($item, 'get_name')) {
				continue;
			}
			$items[] = self::escape($item->get_name()) . ' × ' . (int) $item->get_quantity();
		}
		if ($items !== array()) {
			$lines[] = '<b>Состав:</b>' . "\n" . implode("\n", $items);
		}

		$total = wp_strip_all_tags(
			wc_price(
				$order->get_total(),
				array('currency' => $order->get_currency())
			)
		);
		$lines[] = '<b>Сумма:</b> ' . self::escape($total);

		if ($order->get_payment_method_title() !== '') {
			$lines[] = '<b>Оплата:</b> ' . self::escape($order->get_payment_method_title());
		}

		$address = $order->get_formatted_billing_address();
		$address = preg_replace('/<br\s*\/?>/i', "\n", (string) $address);
		$address = trim(wp_strip_all_tags((string) $address));
		if ($address !== '') {
			$lines[] = '<b>Адрес:</b> ' . self::escape($address);
		}

		$note = trim($order->get_customer_note());
		if ($note !== '') {
			$lines[] = '<b>Комментарий:</b> ' . self::escape($note);
		}

		$edit = method_exists($order, 'get_edit_order_url') ? $order->get_edit_order_url() : '';
		if ($edit !== '') {
			$lines[] = '<b>Заказ:</b> ' . self::escape($edit);
		}

		$source = (string) $order->get_meta(TZ_Woo::SOURCE_META);
		$lines[] = $source !== '' ? $source : implode("\n", TZ_Source::lines());

		return self::limit(implode("\n", $lines));
	}

	/**
	 * Имена полей, которые не нужно показывать.
	 *
	 * @param WPCF7_ContactForm $form Форма.
	 * @return array<string, bool>
	 */
	private static function cf7_skip_names($form) {
		$skip = array(
			'accept' => true,
			'checkbox-mail' => true,
			'your-ip' => true,
			'alt_s' => true,
		);

		$honeypot = '';
		if (isset($GLOBALS['wpa_field_name']) && is_string($GLOBALS['wpa_field_name'])) {
			$honeypot = $GLOBALS['wpa_field_name'];
		}
		if ($honeypot === '') {
			$option = get_option('wpa_field_name');
			if (is_string($option)) {
				$honeypot = $option;
			}
		}
		if ($honeypot !== '') {
			$skip[$honeypot] = true;
		}

		if (!is_object($form) || !method_exists($form, 'scan_form_tags')) {
			return $skip;
		}

		foreach ($form->scan_form_tags() as $tag) {
			if (!is_object($tag)) {
				continue;
			}
			$base = isset($tag->basetype) ? (string) $tag->basetype : '';
			$name = isset($tag->name) ? (string) $tag->name : '';
			if ($name !== '' && in_array($base, array('acceptance', 'submit', 'captcha', 'quiz', 'file'), true)) {
				$skip[$name] = true;
			}
		}

		return $skip;
	}

	/**
	 * Строка «Подпись: значение» или пустая строка.
	 *
	 * @param string $label Подпись.
	 * @param mixed  $value Значение поля.
	 */
	private static function field_line($label, $value) {
		if (is_array($value)) {
			$value = implode(', ', array_map('strval', $value));
		}
		$value = trim(wp_strip_all_tags((string) $value));
		if ($value === '') {
			return '';
		}
		return '<b>' . self::escape($label) . ':</b> ' . self::escape($value);
	}
}
