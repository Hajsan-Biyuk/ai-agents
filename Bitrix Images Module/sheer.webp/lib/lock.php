<?php

namespace Sheer\Webp;

/**
 * Межпроцессная блокировка на flock: cron-агент, цикл в админке и откат
 * не должны одновременно разбирать очередь и писать одни и те же файлы.
 */
class Lock
{
    private static $handle = null;
    private static $depth = 0;

    public static function path()
    {
        return dirname(Config::queueFilePath()) . '/sheer_webp.lock';
    }

    /**
     * @param int $waitSeconds 0 — не ждать, сразу false, если занято.
     */
    public static function acquire($waitSeconds = 0)
    {
        if (self::$handle !== null) {
            self::$depth++;
            return true;
        }

        $fp = @fopen(self::path(), 'c');
        if ($fp === false) {
            // Нет прав на tmp — работаем без блокировки, как раньше.
            self::$depth = 1;
            self::$handle = false;
            return true;
        }

        $deadline = microtime(true) + max(0, (float)$waitSeconds);
        do {
            if (flock($fp, LOCK_EX | LOCK_NB)) {
                self::$handle = $fp;
                self::$depth = 1;
                return true;
            }
            if (microtime(true) >= $deadline) {
                break;
            }
            usleep(200000);
        } while (true);

        fclose($fp);

        return false;
    }

    public static function release()
    {
        if (self::$handle === null) {
            return;
        }
        self::$depth--;
        if (self::$depth > 0) {
            return;
        }

        if (is_resource(self::$handle)) {
            flock(self::$handle, LOCK_UN);
            fclose(self::$handle);
        }
        self::$handle = null;
        self::$depth = 0;
    }
}
