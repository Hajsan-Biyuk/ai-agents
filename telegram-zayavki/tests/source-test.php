<?php
// Самопроверка TZ_Source::describe/lines без WordPress: php tests/source-test.php
define('ABSPATH', __DIR__);
function wp_parse_url($u, $c = -1) { return parse_url($u, $c); }
function wp_unslash($v) { return $v; }
function wp_strip_all_tags($v) { return strip_tags($v); }
require __DIR__ . '/../includes/class-messages.php';
require __DIR__ . '/../includes/class-source.php';

$cases = array(
	array('https://yandex.ru/', 'https://s.ru/', 'Поиск (SEO) — Яндекс'),
	array('https://www.google.com/', 'https://s.ru/a/', 'Поиск (SEO) — Google'),
	array('https://yandex.ru/maps/146/', 'https://s.ru/', 'Карты — Яндекс Карты'),
	array('', 'https://s.ru/kontakty/?yclid=1&utm_source=geoadv_maps', 'Реклама — geoadv_maps'),
	array('', 'https://s.ru/?gclid=1', 'Реклама — Google Ads'),
	array('', 'https://s.ru/?utm_source=yandex&utm_medium=cpc&utm_campaign=dom', 'Реклама — yandex, кампания: dom'),
	array('', 'https://s.ru/?utm_source=newsletter&utm_medium=email', 'Метка: newsletter / email'),
	array('', 'https://s.ru/catalogs/ekspertiza/?ysclid=mwj33', 'Поиск (SEO) — Яндекс'),
	array('https://vk.com/away.php', 'https://s.ru/', 'Соцсеть / мессенджер — vk.com'),
	array('https://mail.ru/', 'https://s.ru/', 'Переход по ссылке — mail.ru'),
	array('https://go.mail.ru/search', 'https://s.ru/', 'Поиск (SEO) — Mail.ru'),
	array('https://avito.ru/item', 'https://s.ru/', 'Переход по ссылке — avito.ru'),
	array('', 'https://s.ru/', 'Прямой заход (адрес, закладка или мессенджер без ссылки)'),
);
foreach ($cases as $c) {
	$got = TZ_Source::describe(array('r' => $c[0], 'l' => $c[1]));
	assert($got === $c[2], "{$c[0]} {$c[1]}: $got");
}

$_COOKIE = array();
assert(strpos(implode('', TZ_Source::lines()), 'не определён') !== false);
$_COOKIE['tz_src'] = json_encode(array('r' => 'https://yandex.ru/', 'l' => 'https://s.ru/a/'));
$_COOKIE['tz_src_s'] = json_encode(array('r' => '', 'l' => 'https://s.ru/<b>'));
$text = implode("\n", TZ_Source::lines());
assert(strpos($text, 'Прямой заход') !== false && strpos($text, 'Первый заход:</b> Поиск (SEO) — Яндекс → https://s.ru/a/') !== false, $text);
assert(strpos($text, '&lt;b&gt;') !== false, 'cookie должны экранироваться');
$_COOKIE['tz_src_s'] = $_COOKIE['tz_src'];
assert(count(TZ_Source::lines()) === 2, 'одинаковые касания — без строки «Первый заход»');
echo "OK\n";
