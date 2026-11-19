<?php

namespace Sheer\Webp;

use CAgent;

class Agent
{
    public const CALLBACK = '\\Sheer\\Webp\\Agent::convertBatch();';

    /**
     * Секунд кодирования за один запуск агента из cron, когда в эту же минуту
     * работает прогрев: агенты идут друг за другом и вместе укладываются в минуту.
     */
    public const CRON_BUDGET = 25;

    /**
     * Прогрев в эту минуту не работает (днём, завершён) — минута вся конвертации.
     * 45, а не 50: запуск + последняя картинка + INTERVAL должны успеть до следующего тика cron.
     */
    public const CRON_BUDGET_SOLO = 45;

    /**
     * Битрикс считает следующий запуск непериодического агента от КОНЦА прошлого: при 60 с
     * агент с порцией 45 с успевал только к тику cron через минуту — шёл раз в 2 минуты.
     */
    public const INTERVAL = 1;

    public static function cronBudget()
    {
        $warming = Warmer::state()['state'] === 'run' && !(Config::isWarmNightOnly() && !Config::isNight());

        return $warming ? self::CRON_BUDGET : self::CRON_BUDGET_SOLO;
    }

    public static function ensure()
    {
        self::remove();
        CAgent::AddAgent(
            self::CALLBACK,
            Config::MODULE_ID,
            'N',
            self::INTERVAL,
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
        CAgent::AddAgent(Warmer::CALLBACK, Config::MODULE_ID, 'N', self::INTERVAL, '', 'Y', '', 40);
    }

    public static function removeWarm()
    {
        CAgent::RemoveAgent(Warmer::CALLBACK, Config::MODULE_ID);
    }

    /** Агент прогрева по sitemap: порция страниц за запуск из cron. */
    public static function warmBatch()
    {
        // Выключенный модуль не убивает начатый прогрев: агент ждёт включения.
        if (!Config::isEnabled()) {
            return Warmer::state()['state'] === 'run' ? Warmer::CALLBACK : '';
        }
        if (!self::isBackgroundContext()) {
            return Warmer::CALLBACK;
        }
        // Пауза или «прогрев только ночью» днём — ждём, агент остаётся.
        if (Config::isPaused() || (Config::isWarmNightOnly() && !Config::isNight())) {
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

    /**
     * Прогрев «идёт», а агента нет (удалили вручную, выключение модуля в 1.7.x) —
     * вернуть агента; продолжит с сохранённой позиции.
     * @return bool true — агент был восстановлен
     */
    public static function restoreWarm()
    {
        if (Warmer::state()['state'] !== 'run' || self::isWarmRegistered()) {
            return false;
        }
        self::ensureWarm();

        return true;
    }

    public static function isWarmRegistered()
    {
        return (bool)CAgent::GetList([], [
            'MODULE_ID' => Config::MODULE_ID,
            'NAME' => Warmer::CALLBACK,
        ])->Fetch();
    }

    /** Агенты, созданные до 1.7.7 с интервалом 60 с, перевести на INTERVAL. */
    public static function fixIntervals()
    {
        foreach ([self::CALLBACK, Warmer::CALLBACK] as $name) {
            $row = CAgent::GetList([], ['MODULE_ID' => Config::MODULE_ID, 'NAME' => $name])->Fetch();
            if ($row && (int)$row['AGENT_INTERVAL'] !== self::INTERVAL) {
                CAgent::Update($row['ID'], ['AGENT_INTERVAL' => self::INTERVAL]);
            }
        }
    }

    public static function convertBatch()
    {
        try {
            self::restoreWarm();
            self::fixIntervals();
        } catch (\Throwable $e) {
        }

        if (!Config::isEnabled()) {
            Config::setPhase('idle');
            return '';
        }

        if (!self::isBackgroundContext()) {
            return self::CALLBACK;
        }

        // Пауза: очередь копится, но ничего не кодируем. Посетители агента не будят —
        // агент жив и просто ждёт, пока паузу снимут в админке.
        $perMinute = Config::getCronPerMinute();
        if (Config::isPaused() || $perMinute <= 0) {
            return self::CALLBACK;
        }

        // В cron посетителей нет: 25–45 с из минутного интервала, но не больше
        // N картинок за запуск (настройка «Картинок в минуту»), чтобы держать нагрузку.
        // Ночью — несколько конвертеров параллельно (настройка «Конвертеров ночью»).
        $result = Scanner::processChunk($perMinute, self::cronBudget(), Config::getWorkers());
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
