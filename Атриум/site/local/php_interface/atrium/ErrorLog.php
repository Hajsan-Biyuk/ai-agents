<?php
/**
 * Журнал ошибок нашего кода (/local). Поведение сайта не меняет: пишет строку и отдаёт ошибку дальше,
 * как было. Нужен, потому что обёртки модуля ЧПУ глушат ошибки каталога (error_reporting(0)).
 * Файл вне сайта: ../logs/atriumceramic.ru/local_errors.log. Читать: tools/errors.py в репозитории.
 */

const ATRIUM_ERRLOG_TYPES = E_WARNING | E_USER_WARNING | E_RECOVERABLE_ERROR | E_USER_ERROR;
const ATRIUM_ERRLOG_FATAL = E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR;

function atriumErrorLogWrite(int $code, string $msg, string $file, int $line): void
{
	static $seen = [];
	// Бывшие notice PHP 7 (в PHP 8 стали warning): в старом шаблоне Аспро их сотни на страницу, глушат настоящие ошибки.
	if ($code === E_WARNING && preg_match('~^(Undefined (array key|variable|global variable|offset|property)|Trying to access array offset)~', $msg)) {
		return;
	}
	$key = $file . ':' . $line;
	if (isset($seen[$key]) || count($seen) >= 20) {
		return; // одна строка на место за запрос, не больше 20
	}
	$seen[$key] = true;

	$dir = dirname($_SERVER['DOCUMENT_ROOT']) . '/logs/atriumceramic.ru';
	$log = $dir . '/local_errors.log';
	if (!is_dir($dir)) {
		@mkdir($dir, 0750, true);
	}
	if (@filesize($log) > 2000000) {
		@rename($log, $log . '.1');
	}
	$root = $_SERVER['DOCUMENT_ROOT'];
	$url = substr((string)($_SERVER['REQUEST_URI'] ?? 'cli'), 0, 300);
	$row = date('Y-m-d H:i:s') . "\t" . $code . "\t" . str_replace($root, '', $file) . ':' . $line
		. "\t" . str_replace(["\n", "\t"], ' ', substr($msg, 0, 500)) . "\t" . $url . "\n";
	@file_put_contents($log, $row, FILE_APPEND | LOCK_EX);
}

function atriumErrorLogInit(): void
{
	$prev = null;
	$prevMask = (int)(\Bitrix\Main\Config\Configuration::getValue('exception_handling')['handled_errors_types'] ?? 0);

	$prev = set_error_handler(static function ($code, $msg, $file = '', $line = 0) use (&$prev, $prevMask) {
		if (($code & ATRIUM_ERRLOG_TYPES) && strpos((string)$file, '/local/') !== false) {
			atriumErrorLogWrite($code, (string)$msg, (string)$file, (int)$line);
		}
		if ($prev && ($code & $prevMask)) {
			return $prev($code, $msg, $file, $line);
		}
		return false; // дальше стандартный обработчик PHP, как до нас
	});

	register_shutdown_function(static function () {
		$e = error_get_last();
		if ($e && ($e['type'] & ATRIUM_ERRLOG_FATAL) && strpos($e['file'], '/local/') !== false) {
			atriumErrorLogWrite($e['type'], $e['message'], $e['file'], $e['line']);
		}
	});
}
