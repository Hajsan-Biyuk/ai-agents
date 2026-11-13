<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->__component->arResultCacheKeys = array_merge($this->__component->arResultCacheKeys, array('ID', 'NAME', 'LIST_PAGE_URL', 'DISPLAY_PROPERTIES'));?>
<?php
// Детальная новость по макету (lam_article.php): «другие новости» в сайдбар.
// Свойства LAM_* созданы в инфоблоке новостей 13.11.2026 (Инфоблоки → Новости → Свойства).
if (strpos($GLOBALS['APPLICATION']->GetCurPage(false), SITE_DIR . 'company/news/') === 0 && \Bitrix\Main\Loader::includeModule('iblock')) {
	$lamIblockId = (int)$arResult['IBLOCK_ID'];

	$arResult['LAM_SIDEBAR_NEWS'] = array();
	$rsNews = \CIBlockElement::GetList(
		array('ACTIVE_FROM' => 'DESC', 'ID' => 'DESC'),
		array('IBLOCK_ID' => $lamIblockId, 'ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y', '!ID' => (int)$arResult['ID']),
		false,
		array('nTopCount' => 3),
		array('ID', 'NAME', 'DETAIL_PAGE_URL', 'PREVIEW_PICTURE', 'DETAIL_PICTURE', 'ACTIVE_FROM', 'DATE_CREATE')
	);
	while ($item = $rsNews->GetNext()) {
		$pictureId = $item['PREVIEW_PICTURE'] ?: $item['DETAIL_PICTURE'];
		$picture = $pictureId ? \CFile::ResizeImageGet($pictureId, array('width' => 696, 'height' => 450), BX_RESIZE_IMAGE_EXACT) : null;
		$dateRaw = $item['ACTIVE_FROM'] ?: $item['DATE_CREATE'];
		$arResult['LAM_SIDEBAR_NEWS'][] = array(
			'NAME' => $item['NAME'],
			'URL' => $item['DETAIL_PAGE_URL'],
			'PICTURE' => $picture ? $picture['src'] : '',
			'DATE' => $dateRaw ? FormatDate('d.m.Y', MakeTimeStamp($dateRaw)) : '',
		);
	}
}
