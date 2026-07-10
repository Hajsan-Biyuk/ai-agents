<?php

/**
 * Атриум — endpoint приёма канонического JSON.
 *
 * POST /local/tools/import/receive.php
 *   Заголовок: X-Import-Token: <token>
 *   Тело: {"dry_run": true, "with_photos": false, "products": [...]}
 *
 * Ответ: JSON-отчёт {dry_run, created, updated, skipped, errors, log}.
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

$raw = file_get_contents('php://input');
$payload = json_decode($raw, true);
if (!is_array($payload) || !isset($payload['products']) || !is_array($payload['products'])) {
	respond(400, ['error' => 'invalid payload: ожидается {"products": [...]}']);
}

$products = $payload['products'];
if (count($products) > (int) $config['max_batch']) {
	respond(400, ['error' => 'batch too large: max ' . $config['max_batch']]);
}

$dryRun = !isset($payload['dry_run']) || (bool) $payload['dry_run']; // dry-run по умолчанию!
$withPhotos = !empty($payload['with_photos']);

require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/atrium_import/AtriumImporter.php';

try {
	$importer = new AtriumImporter($config);
	$importer->setDryRun($dryRun);
	$importer->setWithPhotos($withPhotos);
	$report = $importer->run($products);
	respond(200, $report);
} catch (Throwable $e) {
	respond(500, ['error' => $e->getMessage(), 'file' => basename($e->getFile()) . ':' . $e->getLine()]);
}
