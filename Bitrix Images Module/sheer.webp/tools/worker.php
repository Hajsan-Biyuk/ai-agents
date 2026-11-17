<?php

/**
 * Доп. конвертер ночного режима. Запускает агент из cron (CLI) рядом с собой:
 * php worker.php <DOCUMENT_ROOT> <лимит> <секунд>. Из веба не работает.
 */
if (PHP_SAPI !== 'cli' || $argc < 4 || !is_dir($argv[1])) {
    exit(1);
}
@set_time_limit((int)$argv[3] + 60);

$_SERVER['DOCUMENT_ROOT'] = rtrim($argv[1], '/');
define('NO_KEEP_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS', true);
define('NO_AGENT_CHECK', true);
define('NO_AGENT_STATISTIC', 'Y');
define('STOP_STATISTICS', true);
define('BX_NO_ACCELERATOR_RESET', true);
define('DisableEventsCheck', true);

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

if (\Bitrix\Main\Loader::includeModule('sheer.webp')) {
    \Sheer\Webp\Scanner::workerChunk((int)$argv[2], (int)$argv[3]);
}
