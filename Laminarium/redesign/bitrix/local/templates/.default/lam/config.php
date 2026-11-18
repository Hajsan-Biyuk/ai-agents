<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

// Реквизиты оператора для подвала и текстов согласия — ИП Ипполитова, решение пользователя 18.11.2026.
return array(
	'ORG' => 'ИП Ипполитова Ольга Олеговна',
	'INN' => '344811221964',
	'OGRN' => 'ОГРНИП 326010000029851',
	'EMAIL' => 'laminarium93@mail.ru',
	'PRIVACY_URL' => '/privacy-policy/',
	'CONSENT_URL' => '/soglasie/',
	'TG_URL' => 'https://t.me/laminarium_krd',
	// Блоки старой главной, которых нет в макете (inc_old_blocks.php): true — показать в старом виде Аспро.
	'SHOW_PROMO' => false,   // слайдер акций
	'SHOW_REVIEWS' => false, // отзывы
	'SHOW_STORES' => false,  // магазины
);
