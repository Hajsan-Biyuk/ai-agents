<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/** «Популярные предложения»: вкладки Аспро (свойство HIT: Акция / Хит / Новинка / Советуем) + карусель lam. */
global $TEMPLATE_OPTIONS;
$arParams["DISPLAY_COMPARE_BUTTONS"] = $TEMPLATE_OPTIONS["SHOW_CATALOG_COMPARE"]["CURRENT_VALUE"];
$this->setFrameMode(true);
if (empty($arResult["SHOW_SLIDER_PROP"]) || empty($arResult["TABS"])) return;
$lamImg = '/local/templates/.default/lam/img/';

// Сначала собираем товары вкладок: пустые вкладки не выводим.
$lamPanels = array();
foreach ($arResult["TABS"] as $code => $title) {
	$GLOBALS[$arParams["FILTER_NAME"]] = array("PROPERTY_HIT_VALUE" => array($title));
	if ($arParams["SECTION_ID"]) {
		$GLOBALS[$arParams["FILTER_NAME"]]["SECTION_ID"] = $arParams["SECTION_ID"];
		$GLOBALS[$arParams["FILTER_NAME"]]["INCLUDE_SUBSECTIONS"] = "Y";
	}
	ob_start();
	$APPLICATION->IncludeComponent(
								"bitrix:catalog.top",
								"lam",
								array(
									"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
									"IBLOCK_ID" => $arParams["IBLOCK_ID"],
									"FILTER_NAME" => $arParams["FILTER_NAME"],
									"SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
									"ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
									"ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],
									"ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
									"ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
									"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
									"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
									"BASKET_URL" => $arParams["BASKET_URL"],
									"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
									"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
									"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
									"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
									"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
									"DISPLAY_COMPARE" => ($arParams["DISPLAY_COMPARE_BUTTONS"] ? $arParams["DISPLAY_COMPARE_BUTTONS"] : "Y"),
									"ELEMENT_COUNT" => $arParams["PAGE_ELEMENT_COUNT"],
									"LINE_ELEMENT_COUNT" => $arParams["TOP_LINE_ELEMENT_COUNT"],
									"PROPERTY_CODE" => $arParams["PROP_CODE"],
									"PRICE_CODE" => $arParams["PRICE_CODE"],
									"SALE_STIKER" => ($arParams["SALE_STIKER"] ? $arParams["SALE_STIKER"] : "SALE_TEXT"),
									"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
									"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
									"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
									"PRICE_VAT_SHOW_VALUE" => $arParams["PRICE_VAT_SHOW_VALUE"],
									"USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
									"ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
									"PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
									"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
									"CACHE_TYPE" => $arParams["CACHE_TYPE"],
									"CACHE_TIME" => $arParams["CACHE_TIME"],
									"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
									"CACHE_FILTER" => "Y",
									"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
									"OFFERS_FIELD_CODE" => $arParams["OFFERS_FIELD_CODE"],
									"OFFERS_PROPERTY_CODE" => $arParams["OFFERS_PROPERTY_CODE"],
									"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
									"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
									"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
									"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
									"OFFERS_LIMIT" => $arParams["OFFERS_LIMIT"],
									'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
									'CURRENCY_ID' => $arParams['CURRENCY_ID'],
									'HIDE_NOT_AVAILABLE' => $arParams['HIDE_NOT_AVAILABLE'],
									'VIEW_MODE' => (isset($arParams['TOP_VIEW_MODE']) ? $arParams['TOP_VIEW_MODE'] : ''),
									'ROTATE_TIMER' => (isset($arParams['TOP_ROTATE_TIMER']) ? $arParams['TOP_ROTATE_TIMER'] : ''),
									'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
									'LABEL_PROP' => $arParams['LABEL_PROP'],
									'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
									'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],

									'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
									'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
									'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
									'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
									'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
									'MESS_BTN_BUY' => $arParams['MESS_BTN_BUY'],
									'MESS_BTN_ADD_TO_BASKET' => $arParams['MESS_BTN_ADD_TO_BASKET'],
									'MESS_BTN_SUBSCRIBE' => $arParams['MESS_BTN_SUBSCRIBE'],
									'MESS_BTN_DETAIL' => $arParams['MESS_BTN_DETAIL'],
									'MESS_NOT_AVAILABLE' => $arParams['MESS_NOT_AVAILABLE'],
									'ADD_TO_BASKET_ACTION' => '',
									'SHOW_CLOSE_POPUP' => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
									'COMPARE_PATH' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['compare'],
									'SHOW_DISCOUNT_TIME' => $arParams['SHOW_DISCOUNT_TIME'],
									'DISPLAY_WISH_BUTTONS' => $arParams['DISPLAY_WISH_BUTTONS'],
									'COMPATIBLE_MODE' => 'Y',
									'LAM_V' => isset($arParams['LAM_V']) ? $arParams['LAM_V'] : '',
								),
								false, array("HIDE_ICONS"=>"Y")
				);
	$lamHtml = trim(ob_get_clean());
	if ($lamHtml !== '') {
		$lamPanels[strtolower($code)] = array('TITLE' => $title, 'HTML' => $lamHtml);
	}
}
if (!$lamPanels) return;
?>
<section class="lam-section lam-popular" data-lam-carousel>
	<div class="lam-container">
		<div class="lam-head">
			<h2 class="lam-title">Популярные предложения</h2>
			<p class="lam-subtitle">Покрытия для дома по выгодной цене</p>
		</div>
		<div class="lam-popular__bar">
			<div class="lam-tabs" role="tablist" aria-label="Подборки товаров">
				<?php $i = 0; foreach ($lamPanels as $c => $lamPanel): ?>
					<button class="lam-tab<?=$i ? '' : ' is-active'?>" type="button" role="tab" id="lam-tab-<?=$c?>" aria-selected="<?=$i ? 'false' : 'true'?>" aria-controls="lam-panel-<?=$c?>"<?=$i ? ' tabindex="-1"' : ''?>><?=$lamPanel['TITLE']?></button>
				<?php $i++; endforeach; ?>
			</div>
			<div class="lam-arrows">
				<button class="lam-arrow lam-arrow--prev" type="button" data-lam-prev aria-label="Назад"><img src="<?=$lamImg?>ic-chevron-right.svg" alt=""></button>
				<button class="lam-arrow" type="button" data-lam-next aria-label="Вперёд"><img src="<?=$lamImg?>ic-chevron-right.svg" alt=""></button>
			</div>
		</div>
		<?php $i = 0; foreach ($lamPanels as $c => $lamPanel): ?>
			<div class="lam-tabs__panel" role="tabpanel" id="lam-panel-<?=$c?>" aria-labelledby="lam-tab-<?=$c?>"<?=$i ? ' hidden' : ''?>>
				<ul class="lam-track"><?=$lamPanel['HTML']?></ul>
			</div>
		<?php $i++; endforeach; ?>
	</div>
</section>
