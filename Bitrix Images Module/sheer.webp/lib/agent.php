<?php

namespace Sheer\Webp;

use CAgent;

class Agent
{
    public const CALLBACK = '\\Sheer\\Webp\\Agent::convertBatch();';

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

    public static function remove()
    {
        CAgent::RemoveModuleAgents(Config::MODULE_ID);
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

        $result = Scanner::processChunk(Config::getBatchSize());
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
