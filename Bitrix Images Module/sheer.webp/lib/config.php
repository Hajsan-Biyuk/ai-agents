<?php

namespace Sheer\Webp;

use Bitrix\Main\Config\Option;

class Config
{
    public const MODULE_ID = 'sheer.webp';

    public static function isEnabled()
    {
        return Option::get(self::MODULE_ID, 'enabled', 'Y') === 'Y';
    }

    public static function setEnabled($enabled)
    {
        Option::set(self::MODULE_ID, 'enabled', $enabled ? 'Y' : 'N');
    }

    public static function isRewriteEnabled()
    {
        return Option::get(self::MODULE_ID, 'rewrite_html', 'Y') === 'Y';
    }

    public static function isConvertOnUpload()
    {
        return Option::get(self::MODULE_ID, 'convert_on_upload', 'Y') === 'Y';
    }

    /** PNG, у которого WebP с потерями тяжелее, пробовать сжать без потерь. */
    public static function isPngLossless()
    {
        return Option::get(self::MODULE_ID, 'png_lossless', 'Y') === 'Y';
    }

    public static function isSkipIfLarger()
    {
        return Option::get(self::MODULE_ID, 'skip_if_larger', 'Y') === 'Y';
    }

    public static function scanTemplates()
    {
        return Option::get(self::MODULE_ID, 'scan_templates', 'N') === 'Y';
    }

    public static function scanResizeCache()
    {
        return Option::get(self::MODULE_ID, 'scan_resize_cache', 'N') === 'Y';
    }

    public static function getSkipMinPx()
    {
        $px = (int)Option::get(self::MODULE_ID, 'skip_min_px', '120');
        if ($px < 48) {
            $px = 48;
        }
        if ($px > 240) {
            $px = 240;
        }

        return $px;
    }

    /** Сколько новых картинок одна страница может поставить в очередь. */
    public static function getEnqueuePerRequest()
    {
        return 60;
    }

    public static function getScanRoots()
    {
        $raw = Option::get(
            self::MODULE_ID,
            'scan_roots',
            "/upload/iblock\n/upload/CMax\n/upload/medialibrary"
        );
        $lines = preg_split('/\R/', (string)$raw) ?: [];
        $roots = [];

        foreach ($lines as $line) {
            $line = trim((string)$line);
            if ($line === '' || strpos($line, '#') === 0) {
                continue;
            }

            $rel = Path::normalizeRelative($line);
            if ($rel === '' || strpos($rel, '..') !== false) {
                continue;
            }

            $allowed = (
                strpos($rel, '/upload') === 0
                || strpos($rel, '/local/templates') === 0
                || strpos($rel, '/bitrix/templates') === 0
            );
            if (!$allowed) {
                continue;
            }

            $roots[] = $rel;
        }

        if (!$roots) {
            $roots = ['/upload/iblock', '/upload/CMax', '/upload/medialibrary'];
        }

        return array_values(array_unique($roots));
    }

    /**
     * Кодировать сразу можно только в cron. В админке — тоже очередь:
     * синхронное кодирование внутри CFile::SaveFile тормозит импорт, а
     * нехватка памяти обрывает сохранение элемента.
     */
    public static function isImmediateConvertContext()
    {
        return defined('BX_CRONTAB') && BX_CRONTAB;
    }

    public static function getQuality()
    {
        $quality = (int)Option::get(self::MODULE_ID, 'quality', '90');
        if ($quality < 80) {
            $quality = 80;
        }
        if ($quality > 100) {
            $quality = 100;
        }

        return $quality;
    }

    /**
     * Сколько картинок агент из cron кодирует за запуск (запуск — раз в минуту).
     * В ночном окне — своё значение.
     */
    public static function getCronPerMinute($hour = null)
    {
        if (self::isNight($hour)) {
            return max(10, min(500, (int)Option::get(self::MODULE_ID, 'night_per_minute', '100')));
        }

        return max(10, min(500, (int)Option::get(self::MODULE_ID, 'cron_per_minute', '50')));
    }

    public static function isNightModeEnabled()
    {
        return Option::get(self::MODULE_ID, 'night_enabled', 'N') === 'Y';
    }

    public static function getNightFrom()
    {
        return max(0, min(23, (int)Option::get(self::MODULE_ID, 'night_from', '1')));
    }

    public static function getNightTo()
    {
        return max(0, min(23, (int)Option::get(self::MODULE_ID, 'night_to', '7')));
    }

    /**
     * Сейчас ночное окно? Часы по времени сервера; окно может переходить
     * через полночь (23 → 6). from == to — окно выключено.
     */
    public static function isNight($hour = null)
    {
        if (!self::isNightModeEnabled()) {
            return false;
        }
        $hour = $hour === null ? (int)date('G') : (int)$hour;
        $from = self::getNightFrom();
        $to = self::getNightTo();
        if ($from === $to) {
            return false;
        }

        return $from < $to ? ($hour >= $from && $hour < $to) : ($hour >= $from || $hour < $to);
    }

    /** Прогрев только ночью (если ночной режим включён). */
    public static function isWarmNightOnly()
    {
        return self::isNightModeEnabled() && Option::get(self::MODULE_ID, 'warm_night_only', 'Y') === 'Y';
    }

    /** Пауза фоновой работы: cron ничего не кодирует и не прогревает. */
    public static function isPaused()
    {
        return Option::get(self::MODULE_ID, 'paused', 'N') === 'Y';
    }

    public static function setPaused($paused)
    {
        Option::set(self::MODULE_ID, 'paused', $paused ? 'Y' : 'N');
    }

    public static function getBatchSize()
    {
        $size = (int)Option::get(self::MODULE_ID, 'batch_size', '8');
        if ($size < 3) {
            $size = 3;
        }
        if ($size > 40) {
            $size = 40;
        }

        return $size;
    }

    /**
     * Длинная сторона WebP-копии: крупнее — уменьшаем (оригинал не трогаем).
     * 0 — не уменьшать.
     */
    public static function getMaxSide()
    {
        $px = (int)Option::get(self::MODULE_ID, 'max_side', '2560');
        if ($px <= 0) {
            return 0;
        }

        return max(1000, min(8000, $px));
    }

    /** Аварийный потолок: такие файлы не обрабатываются вовсе. */
    public static function getMaxSourceBytes()
    {
        $mb = (int)Option::get(self::MODULE_ID, 'max_source_mb', '30');
        if ($mb < 1) {
            $mb = 1;
        }
        if ($mb > 60) {
            $mb = 60;
        }

        return $mb * 1024 * 1024;
    }

    public static function getMaxPixels()
    {
        $mp = (int)Option::get(self::MODULE_ID, 'max_megapixels', '80');
        if ($mp < 4) {
            $mp = 4;
        }
        if ($mp > 200) {
            $mp = 200;
        }

        return $mp * 1000000;
    }

    public static function getPhase()
    {
        return Option::get(self::MODULE_ID, 'phase', 'idle');
    }

    public static function setPhase($phase)
    {
        Option::set(self::MODULE_ID, 'phase', (string)$phase);
    }

    public static function getDirQueue()
    {
        $file = self::queueFilePath();
        if (is_file($file)) {
            $queue = json_decode((string)file_get_contents($file), true);
            return is_array($queue) ? $queue : [];
        }

        return [];
    }

    public static function setDirQueue(array $queue)
    {
        $file = self::queueFilePath();
        $dir = dirname($file);
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        self::protectTmpDir($dir);

        $json = json_encode(array_values($queue), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        @file_put_contents($file, $json !== false ? $json : '[]', LOCK_EX);
    }

    public static function queueFilePath()
    {
        $root = self::getDocumentRoot();
        $candidates = [
            $root . '/local/modules/sheer.webp/tmp',
            $root . '/bitrix/tmp',
        ];

        foreach ($candidates as $dir) {
            if ($root === '') {
                continue;
            }
            if (is_dir($dir) || @mkdir($dir, 0755, true)) {
                self::protectTmpDir($dir);
                return $dir . '/sheer_webp_dir_queue.json';
            }
        }

        return rtrim(str_replace('\\', '/', (string)sys_get_temp_dir()), '/')
            . '/sheer_webp_' . md5($root) . '_dir_queue.json';
    }

    public static function protectTmpDir($dir)
    {
        if ($dir === '' || !is_dir($dir)) {
            return;
        }

        $ht = $dir . '/.htaccess';
        if (!is_file($ht)) {
            @file_put_contents($ht, "Deny from all\n<IfModule mod_authz_core.c>\nRequire all denied\n</IfModule>\n");
        }
    }

    public static function getDocumentRoot()
    {
        $root = rtrim(str_replace('\\', '/', (string)($_SERVER['DOCUMENT_ROOT'] ?? '')), '/');
        if ($root === '') {
            $root = rtrim(str_replace('\\', '/', ApplicationRoot::fallback()), '/');
        }

        return $root;
    }
}

class ApplicationRoot
{
    public static function fallback()
    {
        if (class_exists('\\Bitrix\\Main\\Application')) {
            try {
                return \Bitrix\Main\Application::getDocumentRoot();
            } catch (\Throwable $e) {
            }
        }

        return '';
    }
}
