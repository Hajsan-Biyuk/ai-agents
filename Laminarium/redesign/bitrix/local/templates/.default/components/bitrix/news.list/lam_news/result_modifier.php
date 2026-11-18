<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
// DETAIL_URL инфоблока новостей содержит #YEAR# — подставляем год даты активности (как news_front Аспро).
foreach ($arResult['ITEMS'] as $key => $arItem) {
	$year = date('Y', MakeTimeStamp($arItem['ACTIVE_FROM'] ?: $arItem['DATE_CREATE']));
	$arResult['ITEMS'][$key]['DETAIL_PAGE_URL'] = str_replace('#YEAR#', $year, $arItem['DETAIL_PAGE_URL']);
}
