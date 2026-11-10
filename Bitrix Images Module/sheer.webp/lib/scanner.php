<?php

namespace Sheer\Webp;

use Bitrix\Main\Application;

class Scanner
{
    public static function start()
    {
        // Ждём, пока агент допишет текущую порцию, чтобы не затереть очередь.
        $locked = Lock::acquire(15);
        try {
            Config::setDirQueue(self::scanRoots());
            Config::setPhase('scan');
            Agent::ensure();
        } finally {
            if ($locked) {
                Lock::release();
            }
        }
    }

    public static function stop()
    {
        $locked = Lock::acquire(15);
        try {
            Config::setPhase('idle');
            Config::setDirQueue([]);
            Agent::remove();
        } finally {
            if ($locked) {
                Lock::release();
            }
        }
    }

    /**
     * @param int $budget секунд на кодирование за вызов: 12 — для админки
     *                    (ответ не должен висеть), больше — для cron.
     */
    public static function processChunk($limit = 15, $budget = 12)
    {
        $budget = max(5, (int)$budget);
        @ignore_user_abort(true);
        if (function_exists('set_time_limit')) {
            @set_time_limit(max(60, $budget + 30));
        }

        $limit = max(1, (int)$limit);

        // Одна порция за раз: cron-агент и цикл в админке не разбирают
        // очередь одновременно (иначе общие файлы и потерянные каталоги).
        if (!Lock::acquire(0)) {
            return self::statsResult(0, true);
        }

        try {
            return self::processChunkLocked($limit, $budget);
        } finally {
            Lock::release();
        }
    }

    private static function processChunkLocked($limit, $budget)
    {
        RegistryTable::failStaleProcessing(120);

        $phase = Config::getPhase();
        $started = time();

        if ($phase === 'idle' && self::hasPending()) {
            Config::setPhase('convert');
            $phase = 'convert';
        }

        if ($phase === 'scan') {
            self::scanDirectories(max(30, $limit * 8), $started, $budget >= 30 ? $budget - 10 : 8);
            if (count(Config::getDirQueue()) === 0) {
                Config::setPhase('convert');
                $phase = 'convert';
            }
        }

        $converted = 0;
        if ($phase === 'convert') {
            $converted = self::convertPending($limit, $started, $budget);
            if ($converted === 0 && !self::hasPending()) {
                Config::setPhase('idle');
                Agent::remove();
                $phase = 'idle';
            }
        }

        return self::statsResult($converted, false);
    }

    private static function statsResult($converted, $busy)
    {
        $stats = RegistryTable::getStats();
        $stats['phase'] = Config::getPhase();
        $stats['converted_chunk'] = (int)$converted;
        $stats['engine'] = Converter::engine();
        $stats['queue_dirs'] = count(Config::getDirQueue());
        $stats['busy'] = (bool)$busy;

        return $stats;
    }

    /**
     * Откат порцией. Первым делом выключает модуль: иначе хиты и агент
     * сразу создают копии заново, и откат ничего не откатывает.
     * Включить модуль обратно — вручную в настройках.
     */
    public static function rollbackChunk($limit = 80)
    {
        $limit = max(1, (int)$limit);

        self::beginRollback();

        if (!Lock::acquire(15)) {
            return -1;
        }

        try {
            return self::rollbackChunkLocked($limit);
        } finally {
            Lock::release();
        }
    }

    private static function beginRollback()
    {
        $wasEnabled = Config::isEnabled();
        if ($wasEnabled) {
            Config::setEnabled(false);
        }
        Agent::remove();
        if (Config::getPhase() !== 'idle') {
            Config::setPhase('idle');
        }
        // Страницы композитного кеша ссылаются на .webp, которые сейчас удалим.
        // С выключенным модулем новые страницы кешируются уже без подмены.
        // Чистим один раз — на первой порции (при выключении в настройках
        // кеш чистится там же).
        if ($wasEnabled) {
            self::clearPageCache();
        }
    }

    private static function rollbackChunkLocked($limit)
    {
        $deleted = 0;
        $any = false;

        $rows = RegistryTable::getList([
            'select' => ['ID', 'ORIGINAL_PATH', 'WEBP_PATH', 'STATUS', 'WEBP_SIZE'],
            'limit' => $limit,
        ]);

        while ($row = $rows->fetch()) {
            $any = true;
            $abs = Path::toAbsolute($row['WEBP_PATH']);
            // Удаляем только копии, созданные модулем. Чужие *.jpg.webp остаются.
            if ($abs !== '' && Path::isInsideDocumentRoot($abs) && Converter::isOwnedFile($row, $abs)) {
                @unlink($abs);
            }
            if ($abs !== '') {
                Converter::removeTmpFiles($abs);
            }
            RegistryTable::delete((int)$row['ID']);
            $deleted++;
        }

        if (!$any) {
            self::truncateRegistry();
            Config::setPhase('idle');
            Config::setDirQueue([]);
            Agent::remove();
            self::clearPageCache();
            return 0;
        }

        return $deleted;
    }

    public static function clearPageCache()
    {
        try {
            if (class_exists('\\CHTMLPagesCache') && method_exists('CHTMLPagesCache', 'CleanAll')) {
                \CHTMLPagesCache::CleanAll();
            }
        } catch (\Throwable $e) {
        }
    }

    public static function rollbackAll()
    {
        self::beginRollback();

        $guard = 0;
        do {
            $deleted = self::rollbackChunk(200);
            $guard++;
        } while ($deleted !== 0 && $guard < 5000);

        self::truncateRegistry();
        Config::setPhase('idle');
        Config::setDirQueue([]);
        Agent::remove();

        self::clearPageCache();
        if (function_exists('BXClearCache')) {
            BXClearCache(true);
        }
    }

    public static function scanRoots()
    {
        $roots = Config::getScanRoots();
        if (Config::scanTemplates()) {
            $roots[] = '/local/templates';
            $roots[] = '/bitrix/templates';
        }
        // Галочка resize_cache сама добавляет каталог, если его нет в списке:
        // при композите превью Aspro с хитов в очередь не попадают.
        if (Config::scanResizeCache()) {
            $has = false;
            foreach ($roots as $root) {
                if (strpos(strtolower(Path::normalizeRelative($root)), '/upload/resize_cache') === 0) {
                    $has = true;
                }
            }
            if (!$has) {
                $roots[] = '/upload/resize_cache';
            }
        }

        $existing = [];
        foreach ($roots as $root) {
            $rel = Path::normalizeRelative($root);
            if ($rel === '' || Path::isMassScanSkipped($rel)) {
                continue;
            }
            $abs = Path::toAbsolute($rel);
            if (is_dir($abs)) {
                $existing[] = $rel;
            }
        }

        return array_values(array_unique($existing));
    }

    private static function scanDirectories($fileLimit, $started, $budget = 8)
    {
        $queue = Config::getDirQueue();
        $added = 0;

        while ($queue && $added < $fileLimit && (time() - $started) < $budget) {
            $dir = array_shift($queue);
            $absDir = Path::toAbsolute($dir);
            if (!is_dir($absDir)) {
                continue;
            }

            $entries = @scandir($absDir);
            if ($entries === false) {
                continue;
            }

            foreach ($entries as $entry) {
                if ($entry === '.' || $entry === '..') {
                    continue;
                }

                $abs = $absDir . '/' . $entry;
                $rel = Path::toRelative($abs);

                if (is_dir($abs)) {
                    if (!Path::isExcludedPath($rel . '/') && !Path::isMassScanSkipped($rel)) {
                        $queue[] = $rel;
                    }
                    continue;
                }

                if (!is_file($abs) || !Path::shouldConvert($rel, $abs)) {
                    continue;
                }

                // Ручное сканирование: повторяем и ошибки, и пропуски.
                Converter::enqueue($rel, 0, true);
                $added++;
            }
        }

        Config::setDirQueue($queue);

        return $added;
    }

    private static function convertPending($limit, $started, $budget = 12)
    {
        $converted = 0;
        $rows = RegistryTable::getList([
            'select' => ['ID', 'ORIGINAL_PATH', 'FILE_ID'],
            'filter' => ['=STATUS' => 'pending'],
            'limit' => $limit,
        ]);

        while ($row = $rows->fetch()) {
            if ((time() - $started) >= $budget) {
                break;
            }

            // Атомарный захват: строку, взятую другим процессом, пропускаем.
            if (!RegistryTable::claim((int)$row['ID'])) {
                continue;
            }
            Converter::convertRelative($row['ORIGINAL_PATH'], (int)$row['FILE_ID']);
            $converted++;
        }

        return $converted;
    }

    private static function hasPending()
    {
        $row = RegistryTable::getList([
            'select' => ['ID'],
            'filter' => ['@STATUS' => ['pending', 'processing']],
            'limit' => 1,
        ])->fetch();

        return (bool)$row;
    }

    public static function truncateRegistry()
    {
        try {
            Application::getConnection()->truncateTable(RegistryTable::getTableName());
        } catch (\Throwable $e) {
            $guard = 0;
            do {
                $count = 0;
                $rows = RegistryTable::getList(['select' => ['ID'], 'limit' => 500]);
                while ($row = $rows->fetch()) {
                    RegistryTable::delete((int)$row['ID']);
                    $count++;
                }
                $guard++;
            } while ($count > 0 && $guard < 200);
        }
    }
}
