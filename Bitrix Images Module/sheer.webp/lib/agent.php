<?php

namespace Sheer\Webp;

use CAgent;

class Agent
{
    public const CALLBACK = '\\Sheer\\Webp\\Agent::convertBatch();';

    /** Секунд кодирования за один запуск агента из cron (интервал 60 с). */
    public const CRON_BUDGET = 40;

    public static function ensure()
    {
        self::remove();
        CAgent::AddAgent(
            self::CALLBACK,
            Config::MODULE_ID,
            'N',
            60,
            '',
            'Y',
            '',
            30
        );
    }

    /** Снять только агента конвертации (агент прогрева живёт отдельно). */
    public static function remove()
    {
        CAgent::RemoveAgent(self::CALLBACK, Config::MODULE_ID);
    }

    /** Все агенты модуля — при удалении модуля и откате. */
    public static function removeAll()
    {
        CAgent::RemoveModuleAgents(Config::MODULE_ID);
    }

    public static function ensureWarm()
    {
        self::removeWarm();
        CAgent::AddAgent(Warmer::CALLBACK, Config::MODULE_ID, 'N', 60, '', 'Y', '', 40);
    }

    public static function removeWarm()
    {
        CAgent::RemoveAgent(Warmer::CALLBACK, Config::MODULE_ID);
    }

    /** Агент прогрева по sitemap: порция страниц за запуск из cron. */
    public static function warmBatch()
    {
        if (!Config::isEnabled()) {
            return '';
        }
        if (!self::isBackgroundContext()) {
            return Warmer::CALLBACK;
        }

        return Warmer::step() ? Warmer::CALLBACK : '';
    }

    public static function isBackgroundContext()
    {
        if (PHP_SAPI === 'cli') {
            return true;
        }

        return defined('BX_CRONTAB') && BX_CRONTAB;
    }

    private static $woken = false;

    public static function wake()
    {
        if (self::$woken) {
            return;
        }
        self::$woken = true;

        if (Config::getPhase() === 'idle') {
            Config::setPhase('convert');
        }

        if (!class_exists('\\CAgent')) {
            return;
        }

        try {
            if (!self::isRegistered()) {
                self::ensure();
            }
        } catch (\Throwable $e) {
        }
    }

    public static function convertBatch()
    {
        if (!Config::isEnabled()) {
            Config::setPhase('idle');
            return '';
        }

        if (!self::isBackgroundContext()) {
            return self::CALLBACK;
        }

        // В cron посетителей нет: до ~40 с из минутного интервала, но не больше
        // N картинок за запуск (настройка «Картинок в минуту»), чтобы держать нагрузку.
        $result = Scanner::processChunk(Config::getCronPerMinute(), self::CRON_BUDGET);
        if (($result['phase'] ?? 'idle') === 'idle') {
            return '';
        }

        return self::CALLBACK;
    }

    public static function isRegistered()
    {
        $res = CAgent::GetList([], [
            'MODULE_ID' => Config::MODULE_ID,
            'NAME' => self::CALLBACK,
        ]);

        return (bool)$res->Fetch();
    }
}
