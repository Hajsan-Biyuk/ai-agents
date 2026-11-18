<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
// Цены и картинки для живого поиска — как у шаблона Аспро «catalog».
$lamRm = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/templates/aspro_mshop/components/bitrix/search.title/catalog/result_modifier.php';
if (is_file($lamRm)) {
	include $lamRm;
}
