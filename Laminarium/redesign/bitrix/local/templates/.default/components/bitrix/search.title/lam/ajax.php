<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
// Выдача живого поиска — разметка Аспро «catalog» (стили .title-search-result уже в шаблоне сайта).
$lamAjax = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/templates/aspro_mshop/components/bitrix/search.title/catalog/ajax.php';
if (is_file($lamAjax)) {
	include $lamAjax;
}
