<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

/**
 * Google reCAPTCHA v3 для заявок.
 * Секрет и ключ сайта лежат вне каталога сайта, в atrium-recaptcha.env.
 */

function atriumRecaptchaConfig()
{
	static $config = null;
	if ($config !== null) {
		return $config;
	}

	$config = array('sitekey' => '', 'secret' => '');
	$paths = array(
		dirname(dirname($_SERVER['DOCUMENT_ROOT'])) . '/atrium-recaptcha.env',
		'/var/www/atriumcerami_usr/data/atrium-recaptcha.env',
	);
	foreach ($paths as $path) {
		if (!is_file($path)) {
			continue;
		}
		foreach (file($path, FILE_IGNORE_NEW_LINES) as $line) {
			$line = trim($line);
			if ($line === '' || $line[0] === '#' || strpos($line, '=') === false) {
				continue;
			}
			list($key, $value) = explode('=', $line, 2);
			$key = trim($key);
			$value = trim($value);
			if ($key === 'ATRIUM_RECAPTCHA_SITEKEY') {
				$config['sitekey'] = $value;
			}
			if ($key === 'ATRIUM_RECAPTCHA_SECRET') {
				$config['secret'] = $value;
			}
		}
		break;
	}

	return $config;
}

function atriumRecaptchaSiteKey()
{
	$config = atriumRecaptchaConfig();
	return $config['sitekey'];
}

function atriumRecaptchaVerify($token, $action)
{
	$token = is_string($token) ? trim($token) : '';
	if ($token === '' || strlen($token) > 4000) {
		return false;
	}

	$config = atriumRecaptchaConfig();
	if ($config['secret'] === '' || $config['sitekey'] === '') {
		return false;
	}

	if (!function_exists('curl_init')) {
		return false;
	}

	$ch = curl_init('https://www.google.com/recaptcha/api/siteverify');
	curl_setopt_array($ch, array(
		CURLOPT_POST => true,
		CURLOPT_POSTFIELDS => http_build_query(array(
			'secret' => $config['secret'],
			'response' => $token,
			'remoteip' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '',
		)),
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_TIMEOUT => 6,
		CURLOPT_CONNECTTIMEOUT => 4,
		CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
	));
	$raw = curl_exec($ch);
	curl_close($ch);
	if (!is_string($raw) || $raw === '') {
		return false;
	}

	$data = json_decode($raw, true);
	if (!is_array($data) || empty($data['success'])) {
		return false;
	}
	if (!isset($data['action']) || $data['action'] !== $action) {
		return false;
	}
	if (!isset($data['score']) || (float) $data['score'] < 0.5) {
		return false;
	}

	$host = isset($data['hostname']) ? $data['hostname'] : '';
	return $host === 'atriumceramic.ru' || $host === 'www.atriumceramic.ru';
}

function atriumRecaptchaRejectMessage()
{
	return 'Не удалось подтвердить отправку. Обновите страницу и попробуйте ещё раз.';
}

function atriumRecaptchaOnBeforeResultAdd($webFormId, &$arFields, &$arrValues)
{
	if (PHP_SAPI === 'cli') {
		return;
	}
	if (defined('ADMIN_SECTION') && ADMIN_SECTION === true) {
		return;
	}

	$token = '';
	if (isset($_POST['g-recaptcha-response']) && is_string($_POST['g-recaptcha-response'])) {
		$token = $_POST['g-recaptcha-response'];
	}

	if (atriumRecaptchaVerify($token, 'webform')) {
		return;
	}

	global $APPLICATION;
	$APPLICATION->ThrowException(atriumRecaptchaRejectMessage());
}

function atriumRecaptchaGuardOneClick()
{
	if (PHP_SAPI === 'cli') {
		return;
	}
	if (empty($_SERVER['REQUEST_METHOD']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
		return;
	}

	$uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
	if (strpos($uri, 'oneclickbuy.max/script.php') === false) {
		return;
	}
	if (strpos($uri, 'uploadfiles') !== false) {
		return;
	}
	if (empty($_POST['ONE_CLICK_BUY']) || !is_array($_POST['ONE_CLICK_BUY'])) {
		return;
	}

	$token = '';
	if (isset($_POST['g-recaptcha-response']) && is_string($_POST['g-recaptcha-response'])) {
		$token = $_POST['g-recaptcha-response'];
	}
	if (atriumRecaptchaVerify($token, 'oneclick')) {
		return;
	}

	if (!headers_sent()) {
		header('Content-Type: application/json; charset=UTF-8');
	}
	echo json_encode(array(
		'result' => 'N',
		'message' => atriumRecaptchaRejectMessage(),
		'err' => '',
	), JSON_UNESCAPED_UNICODE);
	exit;
}

function atriumRecaptchaInject(&$content)
{
	if (!is_string($content) || stripos($content, '</body>') === false) {
		return;
	}
	if (defined('ADMIN_SECTION') && ADMIN_SECTION === true) {
		return;
	}
	if (strpos($content, 'ATRIUM_RECAPTCHA_SITEKEY') !== false) {
		return;
	}

	$siteKey = atriumRecaptchaSiteKey();
	if ($siteKey === '') {
		return;
	}

	$html = '<script>window.ATRIUM_RECAPTCHA_SITEKEY='
		. json_encode($siteKey, JSON_UNESCAPED_UNICODE) . ';</script>'
		. '<script src="/local/templates/aspro_max/js/recaptcha.js?v=2"></script>';
	$pos = strripos($content, '</body>');
	$content = substr($content, 0, $pos) . $html . substr($content, $pos);
}
