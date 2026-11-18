<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

function sk_telegram_leads_token()
{
	return '8941805400:AAEJgFyZaBxx6WIDS4n0U_yLO5CDAYZEd_8';
}

function sk_telegram_leads_chat_id()
{
	return '-5382148721';
}

function sk_telegram_leads_escape($text)
{
	return htmlspecialchars((string)$text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function sk_telegram_leads_flatten($value)
{
	if (is_array($value)) {
		$parts = array();
		foreach ($value as $item) {
			$text = sk_telegram_leads_flatten($item);
			if ($text !== '') {
				$parts[] = $text;
			}
		}
		return trim(implode(', ', $parts));
	}
	return trim(html_entity_decode((string)$value, ENT_QUOTES, 'UTF-8'));
}

function sk_telegram_leads_seen($hash)
{
	if ($hash === '' || !class_exists('\\Bitrix\\Main\\Config\\Option')) {
		return false;
	}
	$raw = (string)\Bitrix\Main\Config\Option::get('sk.telegramleads', 'dedup_map', '{}');
	$map = json_decode($raw, true);
	if (!is_array($map)) {
		$map = array();
	}
	$now = time();
	foreach ($map as $key => $stamp) {
		if ($now - (int)$stamp >= 90) {
			unset($map[$key]);
		}
	}
	if (isset($map[$hash])) {
		\Bitrix\Main\Config\Option::set('sk.telegramleads', 'dedup_map', (string)json_encode($map));
		return true;
	}
	$map[$hash] = $now;
	\Bitrix\Main\Config\Option::set('sk.telegramleads', 'dedup_map', (string)json_encode($map));
	return false;
}

function sk_telegram_leads_send($title, array $fields)
{
	$token = sk_telegram_leads_token();
	$chatId = sk_telegram_leads_chat_id();
	if ($token === '' || $chatId === '' || !function_exists('curl_init')) {
		return false;
	}

	$siteName = 'СтройКровля';
	if (defined('SITE_ID') && class_exists('CSite')) {
		$row = CSite::GetByID(SITE_ID)->Fetch();
		if (is_array($row)) {
			$name = trim((string)(isset($row['SITE_NAME']) ? $row['SITE_NAME'] : ''));
			if ($name === '' && isset($row['NAME'])) {
				$name = trim((string)$row['NAME']);
			}
			if ($name !== '') {
				$siteName = $name;
			}
		}
	}

	$lines = array(
		'<b>' . sk_telegram_leads_escape($siteName) . '</b>',
		'<b>Новая заявка: ' . sk_telegram_leads_escape($title) . '</b>',
		'',
	);
	foreach ($fields as $label => $value) {
		$text = sk_telegram_leads_flatten($value);
		if ($text === '') {
			continue;
		}
		$lines[] = '<b>' . sk_telegram_leads_escape($label) . ':</b> ' . sk_telegram_leads_escape($text);
	}
	$message = trim(implode("\n", $lines));
	if ($message === '') {
		return false;
	}
	if (function_exists('mb_strlen') && mb_strlen($message) > 4000) {
		$message = mb_substr($message, 0, 3990) . '…';
	}

	$payload = json_encode(array(
		'chat_id' => $chatId,
		'text' => $message,
		'parse_mode' => 'HTML',
		'disable_web_page_preview' => true,
	));
	if ($payload === false) {
		return false;
	}

	$curl = curl_init('https://api.telegram.org/bot' . $token . '/sendMessage');
	if ($curl === false) {
		return false;
	}
	curl_setopt_array($curl, array(
		CURLOPT_POST => true,
		CURLOPT_POSTFIELDS => $payload,
		CURLOPT_HTTPHEADER => array('Content-Type: application/json; charset=utf-8'),
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_CONNECTTIMEOUT => 5,
		CURLOPT_TIMEOUT => 12,
	));
	$raw = curl_exec($curl);
	curl_close($curl);
	$data = is_string($raw) ? json_decode($raw, true) : null;
	return is_array($data) && !empty($data['ok']);
}


function sk_telegram_leads_enum_text($propertyId, $value, $fallback)
{
	$items = is_array($value) ? $value : array($value);
	$names = array();
	foreach ($items as $item) {
		$id = sk_telegram_leads_flatten($item);
		if ($id === '') {
			continue;
		}
		if (!ctype_digit($id)) {
			$names[] = $id;
			continue;
		}
		$enum = CIBlockPropertyEnum::GetByID($id);
		if (!is_array($enum) || (int)$enum['PROPERTY_ID'] !== (int)$propertyId) {
			$names[] = $id;
			continue;
		}
		$enumValue = trim((string)(isset($enum['VALUE']) ? $enum['VALUE'] : ''));
		$names[] = $enumValue !== '' ? $enumValue : $id;
	}
	$joined = trim(implode(', ', $names));
	return $joined !== '' ? $joined : (string)$fallback;
}

function sk_telegram_leads_notify_from_form($arParams)
{
	if (!is_array($arParams)) {
		return;
	}
	$iblockId = isset($arParams['IBLOCK_ID']) ? (int)$arParams['IBLOCK_ID'] : 0;
	if ($iblockId <= 0) {
		return;
	}
	if (!CModule::IncludeModule('iblock')) {
		return;
	}

	$title = 'Заявка с сайта';
	$iblock = CIBlock::GetByID($iblockId)->Fetch();
	if (is_array($iblock)) {
		$type = strtolower(trim((string)(isset($iblock['IBLOCK_TYPE_ID']) ? $iblock['IBLOCK_TYPE_ID'] : '')));
		if ($type !== 'forms') {
			return;
		}
		$name = trim((string)(isset($iblock['NAME']) ? $iblock['NAME'] : ''));
		if ($name !== '') {
			$title = $name;
		}
	}

	$request = (isset($_REQUEST['PROPERTY']) && is_array($_REQUEST['PROPERTY'])) ? $_REQUEST['PROPERTY'] : array();
	$fields = array('Форма' => $title);
	$dedup = array((string)$iblockId);
	foreach ($request as $code => $value) {
		$text = sk_telegram_leads_flatten($value);
		if ($text === '') {
			continue;
		}
		$label = (string)$code;
		if ($label === 'NAME') {
			$label = 'Имя';
		} elseif (ctype_digit($label)) {
			$prop = CIBlockProperty::GetByID($label, $iblockId)->Fetch();
			if (is_array($prop) && !empty($prop['NAME'])) {
				$label = (string)$prop['NAME'];
			}
			if (is_array($prop) && (string)(isset($prop['PROPERTY_TYPE']) ? $prop['PROPERTY_TYPE'] : '') === 'L') {
				$text = sk_telegram_leads_enum_text((int)$code, $value, $text);
			}
		}
		$fields[$label] = $text;
		$dedup[] = $text;
	}

	$page = '';
	if (!empty($_SERVER['HTTP_REFERER'])) {
		$page = (string)$_SERVER['HTTP_REFERER'];
	} elseif (!empty($_SERVER['REQUEST_URI'])) {
		$page = (string)$_SERVER['REQUEST_URI'];
	}
	if ($page !== '') {
		$fields['Страница'] = $page;
	}

	$hash = md5(implode('|', $dedup));
	if (sk_telegram_leads_seen($hash)) {
		return;
	}
	sk_telegram_leads_send($title, $fields);
}

function sk_telegram_leads_on_element_add(&$arFields)
{
	if (!is_array($arFields) || empty($arFields['ID']) || empty($arFields['IBLOCK_ID'])) {
		return;
	}
	if (isset($arFields['RESULT']) && $arFields['RESULT'] === false) {
		return;
	}
	sk_telegram_leads_notify_from_form(array('IBLOCK_ID' => $arFields['IBLOCK_ID']));
}

function sk_telegram_leads_boot()
{
	static $done = false;
	if ($done) {
		return;
	}
	$done = true;
	AddEventHandler('iblock', 'OnAfterIBlockElementAdd', 'sk_telegram_leads_on_element_add');
}
