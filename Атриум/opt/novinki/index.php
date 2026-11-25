<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Новинки");
$APPLICATION->SetPageProperty("HIDE_LEFT_BLOCK", "Y");
$APPLICATION->SetPageProperty("title", "Новинки — АТРИУМ опт");
$APPLICATION->SetPageProperty("description", "Новые поступления сантехники, плитки, керамогранита и мебели для ванной в оптовом каталоге АТРИУМ.");

// Наполнение: флажок «Новинка (опт)» у товара (см. OptNewFilter в /catalog/_catalog_param.php).
include $_SERVER["DOCUMENT_ROOT"]."/catalog/_catalog_param.php";
global $arOptNewFilter;
$arOptNewFilter = OptNewFilter();
?>
<div class="topic__heading iq-catalog-title opt-catalog-title">
	<h1 id="pagetitle"><?$APPLICATION->ShowTitle(false)?></h1>
</div>
<div class="opt-novinki catalog_block_wrapper">
	<?$APPLICATION->IncludeComponent("bitrix:catalog.section", "catalog_block", array_merge($_catalog_param, [
		"FILTER_NAME" => "arOptNewFilter",
		"PAGE_ELEMENT_COUNT" => 24,
		"LINE_ELEMENT_COUNT" => 4,
		"DISPLAY_TYPE" => "block",
		"IS_CATALOG_PAGE" => "Y",
		"PROPERTY_CODE" => ["HIT", "BRAND", "CML2_ARTICLE"],
		"ADD_PICT_PROP" => "MORE_PHOTO",
		"SHOW_GALLERY" => "Y",
		"MAX_GALLERY_ITEMS" => "5",
		"STIKERS_PROP" => "HIT",
		"SALE_STIKER" => "SALE_TEXT",
		"SHOW_ARTICLE_SKU" => "Y",
		"SHOW_QUANTITY" => "Y",
		"SHOW_MEASURE" => "Y",
		"SHOW_QUANTITY_COUNT" => "Y",
		"DISPLAY_WISH_BUTTONS" => "Y",
		"DISPLAY_COMPARE" => CMax::GetFrontParametrValue('CATALOG_COMPARE'),
		"DEFAULT_COUNT" => "1",
		"SHOW_ONE_CLICK_BUY" => "N",
		"SHOW_DISCOUNT_PERCENT_NUMBER" => "Y",
		"TYPE_SKU" => CMax::GetFrontParametrValue('TYPE_SKU'),
		"TYPE_VIEW_BASKET_BTN" => CMax::GetFrontParametrValue('TYPE_VIEW_BASKET_BTN'),
		"TYPE_VIEW_CATALOG_LIST" => CMax::GetFrontParametrValue('TYPE_VIEW_CATALOG_LIST'),
		"USE_FAST_VIEW" => CMax::GetFrontParametrValue('USE_FAST_VIEW_PAGE_DETAIL'),
		"SET_TITLE" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TEMPLATE" => "main",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TITLE" => "Товары",
	]), false, ['HIDE_ICONS' => 'Y']);?>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
