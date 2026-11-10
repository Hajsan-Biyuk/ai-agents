<?php

use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Sheer\Webp\Agent;
use Sheer\Webp\Config;
use Sheer\Webp\RegistryTable;
use Sheer\Webp\Scanner;
use Sheer\Webp\WebServer;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

Loc::loadMessages(__FILE__);

class sheer_webp extends CModule
{
    public $MODULE_ID = 'sheer.webp';
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $PARTNER_NAME;
    public $PARTNER_URI;
    public $MODULE_GROUP_RIGHTS = 'Y';

    public function __construct()
    {
        $arModuleVersion = [];
        include __DIR__ . '/version.php';

        $this->MODULE_VERSION = $arModuleVersion['VERSION'] ?? '1.0.0';
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'] ?? '';
        $this->MODULE_NAME = Loc::getMessage('SHEER_WEBP_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('SHEER_WEBP_MODULE_DESC');
        $this->PARTNER_NAME = Loc::getMessage('SHEER_WEBP_PARTNER_NAME');
        $this->PARTNER_URI = Loc::getMessage('SHEER_WEBP_PARTNER_URI');
    }

    public function DoInstall()
    {
        global $APPLICATION;

        if (!$this->isAdminInstallContext()) {
            $this->showAdminOnlyError('Установка модуля доступна только администратору из админ-раздела.');
            return false;
        }

        ModuleManager::registerModule($this->MODULE_ID);
        if (!$this->InstallDB()) {
            ModuleManager::unRegisterModule($this->MODULE_ID);
            return false;
        }
        $this->InstallEvents();
        $this->InstallFiles();

        $APPLICATION->IncludeAdminFile(
            Loc::getMessage('SHEER_WEBP_INSTALL_TITLE'),
            __DIR__ . '/step.php'
        );
    }

    public function DoUninstall()
    {
        global $APPLICATION, $step;

        if (!$this->isAdminInstallContext()) {
            $this->showAdminOnlyError('Удаление модуля доступно только администратору из админ-раздела.');
            return false;
        }

        $step = (int)$step;
        if ($step < 2) {
            $APPLICATION->IncludeAdminFile(
                Loc::getMessage('SHEER_WEBP_UNINSTALL_TITLE'),
                __DIR__ . '/unstep1.php'
            );
            return;
        }

        Loader::includeModule($this->MODULE_ID);

        if (isset($_REQUEST['remove_webp']) && $_REQUEST['remove_webp'] === 'Y') {
            Scanner::rollbackAll();
        }

        $this->UnInstallEvents();
        $this->UnInstallFiles();
        $this->UnInstallDB();
        ModuleManager::unRegisterModule($this->MODULE_ID);

        $APPLICATION->IncludeAdminFile(
            Loc::getMessage('SHEER_WEBP_UNINSTALL_TITLE'),
            __DIR__ . '/unstep2.php'
        );
    }

    public function InstallDB()
    {
        global $APPLICATION;

        try {
            Loader::includeModule($this->MODULE_ID);
            $connection = Application::getConnection();
            if (!$connection->isTableExists('sheer_webp_file')) {
                RegistryTable::getEntity()->createDbTable();
            }
            $this->ensureIndexes($connection);
        } catch (\Throwable $e) {
            $APPLICATION->ThrowException($e->getMessage());
            return false;
        }

        return true;
    }

    public function UnInstallDB()
    {
        $connection = Application::getConnection();
        if ($connection->isTableExists('sheer_webp_file')) {
            $connection->dropTable('sheer_webp_file');
        }

        $names = [
            'enabled', 'quality', 'skip_if_larger', 'rewrite_html', 'convert_on_upload',
            'scan_templates', 'scan_resize_cache', 'scan_roots', 'skip_min_px',
            'batch_size', 'apache_htaccess', 'phase', 'dir_queue',
            'max_source_mb', 'max_megapixels', 'max_side',
            'warm_threads', 'cron_per_minute', 'warm_state', 'warm_pos', 'warm_total', 'warm_errors',
        ];
        foreach ($names as $name) {
            Option::delete($this->MODULE_ID, $name);
        }

        return true;
    }

    public function InstallEvents()
    {
        $eventManager = \Bitrix\Main\EventManager::getInstance();
        $handlers = $this->getEventHandlers();

        foreach ($handlers as $handler) {
            $eventManager->registerEventHandlerCompatible(
                $handler[0],
                $handler[1],
                $this->MODULE_ID,
                $handler[2],
                $handler[3]
            );
        }

        return true;
    }

    public function UnInstallEvents()
    {
        $eventManager = \Bitrix\Main\EventManager::getInstance();
        $handlers = $this->getEventHandlers();

        foreach ($handlers as $handler) {
            $eventManager->unRegisterEventHandler(
                $handler[0],
                $handler[1],
                $this->MODULE_ID,
                $handler[2],
                $handler[3]
            );
        }

        if (Loader::includeModule($this->MODULE_ID)) {
            Agent::removeAll();
        }

        return true;
    }

    public function InstallFiles()
    {
        CopyDirFiles(
            __DIR__ . '/admin',
            $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin',
            true,
            true
        );

        if (Loader::includeModule($this->MODULE_ID)) {
            Config::protectTmpDir(dirname(Config::queueFilePath()));
            if (Option::get($this->MODULE_ID, 'apache_htaccess', 'N') === 'Y') {
                WebServer::installApacheRules();
            }
        }

        return true;
    }

    public function UnInstallFiles()
    {
        DeleteDirFiles(
            __DIR__ . '/admin',
            $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin'
        );

        if (Loader::includeModule($this->MODULE_ID)) {
            WebServer::removeApacheRules();
        }

        return true;
    }

    public function GetModuleRightList()
    {
        return [
            'reference_id' => ['D', 'R', 'W'],
            'reference' => [
                Loc::getMessage('SHEER_WEBP_DENIED'),
                Loc::getMessage('SHEER_WEBP_OPEN'),
                Loc::getMessage('SHEER_WEBP_WRITE'),
            ],
        ];
    }

    private function ensureIndexes($connection)
    {
        $queries = [
            'CREATE UNIQUE INDEX ux_sheer_webp_hash ON sheer_webp_file (PATH_HASH)',
            'CREATE INDEX ix_sheer_webp_status ON sheer_webp_file (STATUS)',
            'CREATE INDEX ix_sheer_webp_file ON sheer_webp_file (FILE_ID)',
        ];
        foreach ($queries as $sql) {
            try {
                $connection->queryExecute($sql);
            } catch (\Throwable $e) {
            }
        }
    }

    private function getEventHandlers()
    {
        $class = '\\Sheer\\Webp\\Handler';

        return [
            ['main', 'OnAfterFileSave', $class, 'onAfterFileSave'],
            ['main', 'OnFileSave', $class, 'onFileSave'],
            ['main', 'OnFileDelete', $class, 'onFileDelete'],
            ['main', 'OnAfterResizeImage', $class, 'onAfterResizeImage'],
            ['main', 'OnEndBufferContent', $class, 'onEndBufferContent'],
        ];
    }

    private function isAdminInstallContext()
    {
        global $USER;

        return defined('ADMIN_SECTION')
            && ADMIN_SECTION === true
            && is_object($USER)
            && method_exists($USER, 'IsAdmin')
            && $USER->IsAdmin();
    }

    private function showAdminOnlyError($message)
    {
        global $APPLICATION;

        if (is_object($APPLICATION) && method_exists($APPLICATION, 'ThrowException')) {
            $APPLICATION->ThrowException($message);
        }

        if (class_exists('CAdminMessage')) {
            echo CAdminMessage::ShowMessage($message);
        }
    }
}
