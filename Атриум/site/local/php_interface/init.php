<?php

// ATRIUM P0 SEO redirects (2026-09-29)
require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/seo_p0_redirects.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/seo_city_meta.php';

// REST: запись только в блог (инфоблок 56).
require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/atrium_blog_rest.php';

// Главный слайдер: после правки баннера сбросить сохранённую главную.
require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/atrium_banner_cache.php';

// В заявке по плитке: штуки и площадь.
require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/atrium_tile_area.php';
AddEventHandler('sale', 'OnOrderNewSendEmail', 'atriumOnOrderNewSendEmail');

// Заявки форм и заказов в группу Telegram. Письма не отменяются.
require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/atrium/TelegramLeads.php';
AddEventHandler('main', 'OnBeforeEventAdd', 'atriumTelegramOnBeforeEventAdd');

// reCAPTCHA v3: заявки и покупка в один клик. Ключи вне сайта.
require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/atrium/Recaptcha.php';
AddEventHandler('form', 'onBeforeResultAdd', 'atriumRecaptchaOnBeforeResultAdd');
AddEventHandler('main', 'OnEndBufferContent', 'atriumRecaptchaInject');
atriumRecaptchaGuardOneClick();
