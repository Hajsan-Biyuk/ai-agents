<?
define("HIDE_SIDEBAR", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Каталог детских товаров оптом в КраснодареЮг Тойз");
$APPLICATION->SetPageProperty('description','Яркие детские игрушки в интернет-магазине «ЮгТойз». Огромное кол-во популярных товаров. Мы работаем напрямую с производителями и предлагаем продукцию оптом');
?>

<?php
	include $_SERVER["DOCUMENT_ROOT"]."/catalog/_catalog_param.php";
	
	$_catalog_param = array_merge($_catalog_param, array());
	$APPLICATION->IncludeComponent(
		"bitrix:catalog",
		"catalog",
		$_catalog_param,
		false,
		array('HIDE_ICONS' => 'Y')
	);
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>