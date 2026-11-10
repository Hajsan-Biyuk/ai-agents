<?php

namespace Sheer\Webp;

class AjaxService
{
    public static function canWrite()
    {
        global $USER, $APPLICATION;

        if (is_object($USER) && method_exists($USER, 'IsAdmin') && $USER->IsAdmin()) {
            return true;
        }

        return is_object($APPLICATION)
            && method_exists($APPLICATION, 'GetGroupRight')
            && $APPLICATION->GetGroupRight(Config::MODULE_ID) >= 'W';
    }

    public static function run($action)
    {
        switch ((string)$action) {
            case 'start':
                Scanner::start();
                $result = Scanner::processChunk(Config::getBatchSize());
                $result['ok'] = true;
                return $result;

            case 'stop':
                Scanner::stop();
                $result = RegistryTable::getStats();
                $result['phase'] = 'idle';
                $result['ok'] = true;
                return $result;

            case 'chunk':
                $result = Scanner::processChunk(Config::getBatchSize());
                $result['ok'] = true;
                return $result;

            case 'rollback_chunk':
            case 'rollbackChunk':
                $deleted = Scanner::rollbackChunk(100);
                $result = RegistryTable::getStats();
                $result['deleted'] = $deleted;
                $result['phase'] = Config::getPhase();
                $result['ok'] = true;
                $result['busy'] = $deleted < 0;
                $result['enabled'] = Config::isEnabled();
                $result['done'] = $deleted === 0;
                return $result;

            case 'clear_queue':
            case 'clearQueue':
                $removed = Scanner::clearQueue();
                $result = RegistryTable::getStats();
                $result['removed'] = $removed;
                $result['busy'] = $removed < 0;
                $result['phase'] = Config::getPhase();
                $result['queue_dirs'] = count(Config::getDirQueue());
                $result['ok'] = $removed >= 0;
                return $result;

            case 'install_htaccess':
            case 'installHtaccess':
                return [
                    'ok' => WebServer::installApacheRules(),
                    'engine' => Converter::engine(),
                ];

            case 'status':
                $result = RegistryTable::getStats();
                $result['phase'] = Config::getPhase();
                $result['engine'] = Converter::engine();
                $result['agent'] = Agent::isRegistered();
                $result['queue_dirs'] = count(Config::getDirQueue());
                $result['ok'] = true;
                return $result;
        }

        return ['ok' => false, 'error' => 'unknown_action'];
    }
}
