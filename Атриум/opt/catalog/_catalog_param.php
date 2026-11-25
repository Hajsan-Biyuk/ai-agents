<?
// Общие параметры bitrix:catalog.section для блоков вне каталога (главная опта).
// Типы цен — как в /catalog/index.php: группа 9 → OPT, группа 11 → OPT+, переключатель РРЦ (switch_price=Y) → BASE.
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$PRICE_CODE = ['BASE'];
global $USER;
if ($USER->IsAuthorized()) {
	$arGroups = CUser::GetUserGroup($USER->GetID());
	$bRetail = (isset($_SESSION['switch_price']) && $_SESSION['switch_price'] == 'Y');
	if (array_intersect([9], $arGroups)) {
		$PRICE_CODE = $bRetail ? ['BASE'] : ['OPT'];
	} elseif (array_intersect([11], $arGroups)) {
		$PRICE_CODE = $bRetail ? ['BASE'] : ['OPT+'];
	}
}

// Новинки (главная и /novinki/): товары с флажком «Новинка (опт)» (свойство OPT_NEW).
// Не раздел, как на рознице: обмен с 1С выключает чужие разделы и перезаписывает привязки товаров.
// Пока ни один товар не отмечен — все товары (сортировка по дате добавления), чтобы блок не пустел.
if (!function_exists('OptNewFilter')) {
	function OptNewFilter() {
		$arFilter = ['PROPERTY_OPT_NEW_VALUE' => 'Да'];
		$bAny = CIBlockElement::GetList([], ['IBLOCK_ID' => 80, 'ACTIVE' => 'Y'] + $arFilter, false, ['nTopCount' => 1], ['ID'])->Fetch();
		return $bAny ? $arFilter : [];
	}
}

$_catalog_param = [
	'IBLOCK_TYPE' => '1c_catalog',
	'IBLOCK_ID' => '80',
	'SECTION_ID' => '',
	'SECTION_CODE' => '',
	'INCLUDE_SUBSECTIONS' => 'Y',
	'SHOW_ALL_WO_SECTION' => 'Y',
	'PRICE_CODE' => $PRICE_CODE,
	'USE_PRICE_COUNT' => 'N',
	'SHOW_PRICE_COUNT' => '1',
	'PRICE_VAT_INCLUDE' => 'Y',
	'CONVERT_CURRENCY' => 'N',
	'SHOW_OLD_PRICE' => 'Y',
	'SHOW_DISCOUNT_PERCENT' => 'Y',
	'HIDE_NOT_AVAILABLE' => 'N',
	'HIDE_NOT_AVAILABLE_OFFERS' => 'N',
	'ELEMENT_SORT_FIELD' => 'DATE_CREATE',
	'ELEMENT_SORT_ORDER' => 'DESC',
	'ELEMENT_SORT_FIELD2' => 'ID',
	'ELEMENT_SORT_ORDER2' => 'DESC',
	'PAGE_ELEMENT_COUNT' => 12,
	'LINE_ELEMENT_COUNT' => 6,
	'PROPERTY_CODE' => ['CML2_ARTICLE', 'TOVAR_DNYA'],
	'OFFERS_LIMIT' => 0,
	'ADD_PROPERTIES_TO_BASKET' => 'Y',
	'PARTIAL_PRODUCT_PROPERTIES' => 'Y',
	'BASKET_URL' => '/basket/',
	'ACTION_VARIABLE' => 'action',
	'PRODUCT_ID_VARIABLE' => 'id',
	'PRODUCT_QUANTITY_VARIABLE' => 'quantity',
	'PRODUCT_PROPS_VARIABLE' => 'prop',
	'USE_PRODUCT_QUANTITY' => 'Y',
	'SECTION_URL' => '/catalog/#SECTION_CODE_PATH#/',
	'DETAIL_URL' => '/catalog/#SECTION_CODE_PATH#/#ELEMENT_ID#/',
	'CACHE_TYPE' => 'A',
	'CACHE_TIME' => '3600',
	'CACHE_GROUPS' => 'Y',
	'CACHE_FILTER' => 'Y',
	'SET_TITLE' => 'N',
	'SET_BROWSER_TITLE' => 'N',
	'SET_META_KEYWORDS' => 'N',
	'SET_META_DESCRIPTION' => 'N',
	'SET_LAST_MODIFIED' => 'N',
	'ADD_SECTIONS_CHAIN' => 'N',
	'DISPLAY_TOP_PAGER' => 'N',
	'DISPLAY_BOTTOM_PAGER' => 'N',
	'COMPATIBLE_MODE' => 'Y',
	'COMPOSITE_FRAME_MODE' => 'A',
	'COMPOSITE_FRAME_TYPE' => 'AUTO',
];
