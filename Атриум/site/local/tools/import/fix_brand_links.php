<?php

/**
 * Одноразовое исправление брендов без DETAIL_TEXT (нет ссылки на /info/brands/).
 * POST + X-Import-Token. Тело: {"names": ["Artkera Group"]} или пусто = все бренды ИБ.
 */

define('NO_KEEP_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS', true);
define('STOP_STATISTICS', true);
define('NO_AGENT_CHECK', true);

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

header('Content-Type: application/json; charset=utf-8');

function respond($code, array $data)
{
	http_response_code($code);
	echo json_encode($data, JSON_UNESCAPED_UNICODE);
	require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php';
	die();
}

$config = require __DIR__ . '/config.php';
$token = isset($_SERVER['HTTP_X_IMPORT_TOKEN']) ? (string) $_SERVER['HTTP_X_IMPORT_TOKEN'] : '';
if ($token === '' || !hash_equals((string) $config['token'], $token)) {
	respond(403, ['error' => 'forbidden']);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	respond(405, ['error' => 'method not allowed']);
}

if (!CModule::IncludeModule('iblock')) {
	respond(500, ['error' => 'iblock module']);
}

$catalogId = (int) ($config['iblock_id'] ?? 0);
$prop = CIBlockProperty::GetList([], ['IBLOCK_ID' => $catalogId, 'CODE' => 'BRAND'])->Fetch();
$brandIblockId = $prop ? (int) $prop['LINK_IBLOCK_ID'] : 0;
if ($brandIblockId <= 0) {
	respond(500, ['error' => 'brand iblock not found']);
}

$payload = json_decode(file_get_contents('php://input'), true);
$names = is_array($payload) && !empty($payload['names']) ? (array) $payload['names'] : [];

$filter = ['IBLOCK_ID' => $brandIblockId, 'ACTIVE' => 'Y'];
if ($names) {
	$filter['NAME'] = $names;
}

$fixed = [];
$skipped = [];
$rs = CIBlockElement::GetList(['NAME' => 'ASC'], $filter, false, false, ['ID', 'NAME', 'DETAIL_TEXT']);
while ($row = $rs->Fetch()) {
	$text = trim(strip_tags((string) ($row['DETAIL_TEXT'] ?? '')));
	if ($text !== '') {
		$skipped[] = $row['NAME'];
		continue;
	}
	$el = new CIBlockElement();
	if ($el->Update((int) $row['ID'], [
		'DETAIL_TEXT' => $row['NAME'],
		'DETAIL_TEXT_TYPE' => 'text',
	])) {
		$fixed[] = $row['NAME'];
	}
}

respond(200, ['fixed' => $fixed, 'skipped' => $skipped, 'brand_iblock_id' => $brandIblockId]);
