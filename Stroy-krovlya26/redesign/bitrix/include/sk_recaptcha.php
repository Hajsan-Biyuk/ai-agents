<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

function sk_recaptcha_site_key()
{
	return '6LfdJvstAAAAAKIMwaG09kirhY4tr1l2b6dVOdWY';
}

function sk_recaptcha_secret()
{
	return '6LfdJvstAAAAACSjRG2QZor74gV96uUQBrPKjk5S';
}

function sk_recaptcha_boot()
{
	static $done = false;
	if ($done) {
		return;
	}
	$done = true;
	AddEventHandler('iblock', 'OnBeforeIBlockElementAdd', 'sk_recaptcha_on_before_add');
	sk_recaptcha_flush_once();
}

function sk_recaptcha_flush_once()
{
	$flag = $_SERVER['DOCUMENT_ROOT'] . '/include/.sk_recaptcha_flushed';
	if (is_file($flag)) {
		return;
	}
	try {
		if (function_exists('BXClearCache')) {
			BXClearCache(true);
		}
		$composite = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/classes/general/cache_html.php';
		if (is_file($composite)) {
			require_once $composite;
			if (class_exists('CHTMLPagesCache')) {
				CHTMLPagesCache::deleteAll();
			}
		}
	} catch (Throwable $exception) {
	}
	@file_put_contents($flag, date(DATE_ATOM));
}

function sk_recaptcha_on_before_add(&$arFields)
{
	if (defined('ADMIN_SECTION') && ADMIN_SECTION === true) {
		return;
	}
	$uri = (string)(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '');
	if (strpos($uri, '/bitrix/admin/') !== false) {
		return;
	}
	if (!is_array($arFields) || empty($arFields['IBLOCK_ID'])) {
		return;
	}
	if (!CModule::IncludeModule('iblock')) {
		return;
	}

	$iblockId = (int)$arFields['IBLOCK_ID'];
	$iblock = CIBlock::GetByID($iblockId)->Fetch();
	if (!is_array($iblock)) {
		return;
	}
	$type = strtolower(trim((string)(isset($iblock['IBLOCK_TYPE_ID']) ? $iblock['IBLOCK_TYPE_ID'] : '')));
	$code = strtolower(trim((string)(isset($iblock['CODE']) ? $iblock['CODE'] : '')));
	if ($type !== 'forms' && $code !== 'forms') {
		return;
	}

	$token = isset($_POST['g-recaptcha-response']) ? (string)$_POST['g-recaptcha-response'] : '';
	$check = sk_recaptcha_verify($token);
	if (!empty($check['ok'])) {
		return;
	}

	global $APPLICATION;
	if (!empty($check['unavailable'])) {
		$APPLICATION->ThrowException('Сервис проверки временно недоступен. Позвоните нам или попробуйте ещё раз позже.');
	} else {
		$APPLICATION->ThrowException('Не удалось подтвердить отправку. Обновите страницу и попробуйте ещё раз.');
	}
	return false;
}

function sk_recaptcha_verify($token)
{
	$token = trim((string)$token);
	if ($token === '' || !function_exists('curl_init')) {
		return array('ok' => false, 'unavailable' => $token !== '' && !function_exists('curl_init'));
	}

	$payload = http_build_query(array(
		'secret' => sk_recaptcha_secret(),
		'response' => $token,
		'remoteip' => (string)(isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : ''),
	));
	$curl = curl_init('https://www.google.com/recaptcha/api/siteverify');
	if ($curl === false) {
		return array('ok' => false, 'unavailable' => true);
	}
	curl_setopt_array($curl, array(
		CURLOPT_POST => true,
		CURLOPT_POSTFIELDS => $payload,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_CONNECTTIMEOUT => 5,
		CURLOPT_TIMEOUT => 10,
	));
	$raw = curl_exec($curl);
	$errno = curl_errno($curl);
	curl_close($curl);
	if ($raw === false || $errno) {
		return array('ok' => false, 'unavailable' => true);
	}

	$data = json_decode($raw, true);
	if (!is_array($data) || empty($data['success'])) {
		return array('ok' => false);
	}
	$score = isset($data['score']) ? (float)$data['score'] : 0.0;
	$action = (string)(isset($data['action']) ? $data['action'] : '');
	$host = strtolower((string)(isset($data['hostname']) ? $data['hostname'] : ''));
	$hostOk = $host === 'stroy-krovlya26.ru' || $host === 'www.stroy-krovlya26.ru';
	if ($score < 0.5 || $action !== 'form' || !$hostOk) {
		return array('ok' => false);
	}
	return array('ok' => true);
}

function sk_recaptcha_print_assets()
{
	static $done = false;
	if ($done) {
		return;
	}
	$done = true;
	$key = sk_recaptcha_site_key();
	echo '<script>window.SK_RECAPTCHA_KEY=' . json_encode($key, JSON_UNESCAPED_SLASHES) . ';</script>' . "\n";
	echo '<script src="https://www.google.com/recaptcha/api.js?render=' . htmlspecialchars($key, ENT_QUOTES) . '" async defer></script>' . "\n";
	echo '<style>.grecaptcha-badge{display:block !important;visibility:visible !important;opacity:1 !important;z-index:2147483000 !important;bottom:72px !important;}</style>' . "\n";
}
