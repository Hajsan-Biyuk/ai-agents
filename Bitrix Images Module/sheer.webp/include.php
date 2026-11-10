<?php

use Bitrix\Main\Loader;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

Loader::registerAutoLoadClasses('sheer.webp', [
    'Sheer\\Webp\\Agent' => 'lib/agent.php',
    'Sheer\\Webp\\AjaxService' => 'lib/ajaxservice.php',
    'Sheer\\Webp\\Config' => 'lib/config.php',
    'Sheer\\Webp\\Controller\\Ajax' => 'lib/controller/ajax.php',
    'Sheer\\Webp\\Converter' => 'lib/converter.php',
    'Sheer\\Webp\\Handler' => 'lib/handler.php',
    'Sheer\\Webp\\Warmer' => 'lib/warmer.php',
    'Sheer\\Webp\\Lock' => 'lib/lock.php',
    'Sheer\\Webp\\Path' => 'lib/path.php',
    'Sheer\\Webp\\RegistryTable' => 'lib/registrytable.php',
    'Sheer\\Webp\\Rewriter' => 'lib/rewriter.php',
    'Sheer\\Webp\\Scanner' => 'lib/scanner.php',
    'Sheer\\Webp\\WebServer' => 'lib/webserver.php',
]);
