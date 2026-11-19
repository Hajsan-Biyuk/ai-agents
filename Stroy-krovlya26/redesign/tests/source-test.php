<?php
// Самопроверка шифра источника в sk_telegram_leads.php (кейсы — из telegram-zayavki/tests/source-test.php).
// Запуск: php Stroy-krovlya26/redesign/tests/source-test.php  (нужна локальная копия include/sk_telegram_leads.php — она в .gitignore)
define('B_PROLOG_INCLUDED', true);
ini_set('zend.assertions', '1');
ini_set('assert.exception', '1');
require __DIR__ . '/../bitrix/include/sk_telegram_leads.php';

$t = function ($r, $l) { return sk_source_code(array('r' => $r, 'l' => $l)); };
foreach (array(
	array('https://yandex.ru/', 'https://s.ru/', 1),
	array('https://www.google.com/', 'https://s.ru/a/', 2),
	array('https://yandex.ru/maps/146/', 'https://s.ru/', 4),
	array('', 'https://s.ru/kontakty/?yclid=1&utm_source=geoadv_maps', 3),
	array('', 'https://s.ru/?gclid=1', 3),
	array('', 'https://s.ru/?utm_source=yandex&utm_medium=cpc&utm_campaign=dom', 3),
	array('', 'https://s.ru/?utm_source=newsletter&utm_medium=email', 7),
	array('', 'https://s.ru/catalogs/ekspertiza/?ysclid=mwj33', 1),
	array('https://vk.com/away.php', 'https://s.ru/', 5),
	array('https://mail.ru/', 'https://s.ru/', 6),
	array('https://go.mail.ru/search', 'https://s.ru/', 2),
	array('https://avito.ru/item', 'https://s.ru/', 6),
	array('', 'https://s.ru/', 8),
) as $c) {
	assert($t($c[0], $c[1]) === $c[2], "{$c[0]} {$c[1]}: " . $t($c[0], $c[1]));
}
assert(sk_source_code(null) === 0);

$_COOKIE = array();
assert(substr(sk_source_number(), -3) === '-00');
$_COOKIE['tz_src'] = json_encode(array('r' => 'https://yandex.ru/', 'l' => 'https://s.ru/a/'));
$_COOKIE['tz_src_s'] = json_encode(array('r' => '', 'l' => 'https://s.ru/?yclid=1'));
assert(substr(sk_source_number(), -3) === '-31', sk_source_number());
unset($_COOKIE['tz_src_s']);
assert(substr(sk_source_number(), -3) === '-11');
assert(preg_match('/^\d{6}-\d\d$/', sk_source_number()) === 1);
$_COOKIE['tz_src'] = '{"r":["x"],"l":5}'; // мусор от клиента
assert(substr(sk_source_number(), -3) === '-88');
foreach (array(
	'https://xn----7sbbumahgdz5aibf1d4b3e.xn--p1ai/?utm_source=x#f' => 'https://шархинский-карьер.рф/',
	'https://www.xn--e1afmkfd.xn--p1ai/%D0%BA%D0%B0%D1%82%D0%B0%D0%BB%D0%BE%D0%B3/' => 'https://www.пример.рф/каталог/',
	'https://xn--bcher-kva.de' => 'https://bücher.de',
	'https://s.ru/a%FF/' => 'https://s.ru/a%FF/',
	'https://xn--!!.ru/' => 'https://xn--!!.ru/',
	'https://stroy-krovlya26.ru/catalog/x/?ysclid=1' => 'https://stroy-krovlya26.ru/catalog/x/',
) as $in => $want) {
	assert(sk_source_page_url($in) === $want, sk_source_page_url($in));
}
echo "source-test: OK\n";
