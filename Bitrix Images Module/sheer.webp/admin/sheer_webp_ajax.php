<?php

use Bitrix\Main\Loader;
use Sheer\Webp\AjaxService;

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php';

global $USER, $APPLICATION;

if (!is_object($USER) || !$USER->IsAuthorized()) {
    CHTTP::SetStatus('403 Forbidden');
    echo '{"error":"auth"}';
    die();
}

if (!check_bitrix_sessid()) {
    CHTTP::SetStatus('403 Forbidden');
    header('Content-Type: application/json; charset=UTF-8');
    echo '{"error":"sessid"}';
    die();
}

if (!Loader::includeModule('sheer.webp')) {
    CHTTP::SetStatus('500 Internal Server Error');
    header('Content-Type: application/json; charset=UTF-8');
    echo '{"error":"module"}';
    die();
}

if (!AjaxService::canWrite()) {
    CHTTP::SetStatus('403 Forbidden');
    header('Content-Type: application/json; charset=UTF-8');
    echo '{"error":"denied"}';
    die();
}

header('Content-Type: application/json; charset=UTF-8');
echo \Bitrix\Main\Web\Json::encode(AjaxService::run((string)($_REQUEST['ajax_action'] ?? '')));
die();
