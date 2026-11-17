<?php
/**
 * Источник заявки: поиск, реклама, соцсеть, ссылка или прямой заход.
 *
 * @package Telegram_Zayavki
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Читает cookie из assets/source.js и превращает их в строки сообщения.
 */
class TZ_Source {
	const FIRST = 'tz_src';
	const VISIT = 'tz_src_s';

	/**
	 * Скрипт на всех страницах сайта.
	 */
	public static function hooks() {
		add_action('wp_enqueue_scripts', array(__CLASS__, 'enqueue'));
	}

	public static function enqueue() {
		wp_enqueue_script('telegram-zayavki-source', TZ_URL . 'assets/source.js', array(), TZ_VERSION, false);
	}

	/**
	 * Строка для Telegram из cookie текущего запроса.
	 * Группа обычно общая с заказчиком, поэтому источник зашифрован: ДДЧЧММ-XY,
	 * X — этот визит, Y — первый заход. Легенда — правило студии tg-zayavki-istochnik.md.
	 *
	 * @return array<int, string>
	 */
	public static function lines() {
		$first = self::cookie(self::FIRST);
		$visit = self::cookie(self::VISIT);
		if ($visit === null) {
			$visit = $first;
		}
		if ($first === null) {
			$first = $visit;
		}
		return array('<b>Номер заявки:</b> ' . wp_date('dHi') . '-' . self::code($visit) . self::code($first));
	}

	/**
	 * Цифра источника: 1 поиск Яндекс, 2 другой поиск, 3 реклама, 4 карты, 5 соцсеть,
	 * 6 ссылка, 7 метка, 8 прямой, 0 не определён.
	 *
	 * @param array{r: string, l: string}|null $touch Касание.
	 */
	public static function code($touch) {
		if ($touch === null) {
			return 0;
		}
		$text = self::describe($touch);
		foreach (array('Поиск (SEO) — Яндекс' => 1, 'Поиск (SEO)' => 2, 'Реклама' => 3, 'Карты' => 4, 'Соцсеть' => 5, 'Переход' => 6, 'Метка' => 7, 'Прямой' => 8) as $prefix => $code) {
			if (strpos($text, $prefix) === 0) {
				return $code;
			}
		}
		return 0;
	}

	/**
	 * Человеческое описание касания.
	 *
	 * @param array{r: string, l: string} $touch Реферер и страница входа.
	 */
	public static function describe($touch) {
		$query = array();
		parse_str((string) wp_parse_url($touch['l'], PHP_URL_QUERY), $query);
		$get = static function ($key) use ($query) {
			return isset($query[$key]) && is_string($query[$key]) ? trim($query[$key]) : '';
		};

		$src = $get('utm_source');
		$medium = strtolower($get('utm_medium'));
		$campaign = $get('utm_campaign') !== '' ? ', кампания: ' . $get('utm_campaign') : '';

		if ($get('yclid') !== '' || $get('gclid') !== '' || in_array($medium, array('cpc', 'ppc', 'cpm', 'cpa', 'paid', 'ads', 'retargeting'), true)) {
			if ($src === '') {
				$src = $get('yclid') !== '' ? 'Яндекс Директ' : ($get('gclid') !== '' ? 'Google Ads' : '');
			}
			return 'Реклама' . ($src !== '' ? ' — ' . $src : '') . $campaign;
		}
		if ($src !== '') {
			return 'Метка: ' . $src . ($medium !== '' ? ' / ' . $medium : '') . $campaign;
		}

		$host = preg_replace('/^www\./', '', strtolower((string) wp_parse_url($touch['r'], PHP_URL_HOST)));
		if ($host === '') {
			// ysclid Яндекс дописывает к переходам из поиска и своих сервисов, даже когда реферер срезан.
			return $get('ysclid') !== '' ? 'Поиск (SEO) — Яндекс' : 'Прямой заход (адрес, закладка или мессенджер без ссылки)';
		}

		$social = array('vk.com', 'vk.ru', 'ok.ru', 't.me', 'telegram.org', 'max.ru', 'wa.me', 'whatsapp.com', 'dzen.ru', 'zen.yandex.ru', 'youtube.com', 'instagram.com', 'facebook.com', 'tiktok.com', 'pinterest.com');
		foreach ($social as $domain) {
			if (self::is_host($host, $domain)) {
				return 'Соцсеть / мессенджер — ' . $host;
			}
		}

		$engines = array(
			'yandex' => 'Яндекс',
			'ya' => 'Яндекс',
			'google' => 'Google',
			'bing' => 'Bing',
			'mail' => 'Mail.ru',
			'rambler' => 'Рамблер',
			'duckduckgo' => 'DuckDuckGo',
			'yahoo' => 'Yahoo',
		);
		foreach ($engines as $brand => $name) {
			// yandex.ru, yandex.com.tr, google.co.uk; mail.ru — только go.mail.ru.
			if ($brand === 'mail' ? self::is_host($host, 'go.mail.ru') : preg_match('/(^|\.)' . $brand . '\.[a-z]{2,3}(\.[a-z]{2})?$/', $host)) {
				$path = (string) wp_parse_url($touch['r'], PHP_URL_PATH);
				if (strpos($path, '/maps') === 0 || strpos($host, 'maps.') === 0) {
					return 'Карты — ' . $name . ' Карты';
				}
				return 'Поиск (SEO) — ' . $name;
			}
		}

		return 'Переход по ссылке — ' . $host;
	}

	/**
	 * @param string $name Имя cookie.
	 * @return array{r: string, l: string}|null
	 */
	private static function cookie($name) {
		if (empty($_COOKIE[$name]) || !is_string($_COOKIE[$name])) {
			return null;
		}
		$data = json_decode(wp_unslash($_COOKIE[$name]), true);
		if (!is_array($data)) {
			return null;
		}
		// Cookie приходят от клиента: только строки, с ограничением длины.
		return array(
			'r' => isset($data['r']) && is_string($data['r']) ? substr($data['r'], 0, 300) : '',
			'l' => isset($data['l']) && is_string($data['l']) ? substr($data['l'], 0, 500) : '',
		);
	}

	private static function is_host($host, $domain) {
		return $host === $domain || substr($host, -strlen('.' . $domain)) === '.' . $domain;
	}
}
