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
	 * Адрес страницы для сообщения: без ?query и #, домен и путь кириллицей.
	 *
	 * @param string $url Адрес.
	 */
	public static function page_url($url) {
		$url = (string) preg_replace('/[?#].*$/', '', (string) $url);
		if (!preg_match('~^(https?://)([^/:]+)(.*)$~i', $url, $m)) {
			return $url;
		}
		$host = implode('.', array_map(array(__CLASS__, 'idn_label'), explode('.', strtolower($m[2]))));
		$path = rawurldecode($m[3]);
		// Битый %-код в пути даёт не-UTF-8 — тогда оставляем путь как был.
		if (!preg_match('//u', $path)) {
			$path = $m[3];
		}
		return $m[1] . $host . $path;
	}

	/**
	 * Метка домена xn--… в Unicode (RFC 3492). Ошибка — метка без изменений.
	 *
	 * @param string $label Метка домена.
	 */
	public static function idn_label($label) {
		if (strpos($label, 'xn--') !== 0) {
			return $label;
		}
		if (function_exists('idn_to_utf8')) {
			$utf = idn_to_utf8($label, 0, defined('INTL_IDNA_VARIANT_UTS46') ? INTL_IDNA_VARIANT_UTS46 : 0);
			if (is_string($utf) && $utf !== '') {
				return $utf;
			}
		}
		if (!function_exists('mb_chr')) {
			return $label;
		}

		$input = substr($label, 4);
		$delim = strrpos($input, '-');
		$out = $delim === false ? array() : str_split(substr($input, 0, $delim));
		$pos = $delim === false ? 0 : $delim + 1;
		$len = strlen($input);
		$n = 128;
		$i = 0;
		$bias = 72;
		while ($pos < $len) {
			$old = $i;
			$w = 1;
			for ($k = 36; ; $k += 36) {
				if ($pos >= $len) {
					return $label;
				}
				$c = ord($input[$pos++]);
				if ($c >= 48 && $c <= 57) {
					$digit = $c - 22;
				} elseif ($c >= 97 && $c <= 122) {
					$digit = $c - 97;
				} else {
					return $label;
				}
				$i += $digit * $w;
				$t = $k <= $bias ? 1 : ($k >= $bias + 26 ? 26 : $k - $bias);
				if ($digit < $t) {
					break;
				}
				$w *= 36 - $t;
			}
			$count = count($out) + 1;
			$delta = $old === 0 ? intdiv($i - $old, 700) : intdiv($i - $old, 2);
			$delta += intdiv($delta, $count);
			for ($bias = 0; $delta > 455; $bias += 36) {
				$delta = intdiv($delta, 35);
			}
			$bias += intdiv(36 * $delta, $delta + 38);
			$n += intdiv($i, $count);
			$i %= $count;
			if ($n > 0x10FFFF) {
				return $label;
			}
			array_splice($out, $i, 0, array(mb_chr($n, 'UTF-8')));
			$i++;
		}
		return implode('', $out);
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
			$lines[] = '<b>Страница:</b> ' . self::escape(self::page_url($page_url));
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
			$line = self::escape($item->get_name()) . ' × ' . (int) $item->get_quantity();
			if (method_exists($item, 'get_formatted_meta_data')) {
				foreach ($item->get_formatted_meta_data() as $meta) {
					$line .= "\n  " . self::escape(wp_strip_all_tags($meta->display_key)) . ': ' . self::escape(trim(html_entity_decode(wp_strip_all_tags($meta->display_value), ENT_QUOTES, 'UTF-8')));
				}
			}
			$items[] = $line;
		}
		if ($items !== array()) {
			$lines[] = '<b>Состав:</b>' . "\n" . implode("\n", $items);
		}

		$total = html_entity_decode(
			wp_strip_all_tags(
				wc_price(
					$order->get_total(),
					array('currency' => $order->get_currency())
				)
			),
			ENT_QUOTES,
			'UTF-8'
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

		if ($order->get_shipping_method() !== '') {
			$lines[] = '<b>Доставка:</b> ' . self::escape($order->get_shipping_method());
		}
		$shipping = preg_replace('/<br\s*\/?>/i', "\n", (string) $order->get_formatted_shipping_address());
		$shipping = trim(wp_strip_all_tags((string) $shipping));
		if ($shipping !== '' && $shipping !== $address) {
			$lines[] = '<b>Адрес доставки:</b> ' . self::escape($shipping);
		}
		$shipping_phone = method_exists($order, 'get_shipping_phone') ? trim($order->get_shipping_phone()) : '';
		if ($shipping_phone !== '' && $shipping_phone !== $order->get_billing_phone()) {
			$lines[] = '<b>Телефон получателя:</b> ' . self::escape($shipping_phone);
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
