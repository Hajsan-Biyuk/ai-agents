<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
* @global CMain $APPLICATION
* @var array $arParams
* @var array $arResult
* @var CatalogSectionComponent $component
* @var CBitrixComponentTemplate $this
* @var string $templateName
* @var string $componentPath
* @var string $templateFolder
*/

$this->setFrameMode(true);

$arParams['ADD_TO_BASKET_ACTION'] = ['ADD'];

$templateLibrary = array('popup', 'fx');
$currencyList = '';

if (!empty($arResult['CURRENCIES']))
{
	$templateLibrary[] = 'currency';
	$currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);
}




$templateData = array(
	'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
	'TEMPLATE_LIBRARY' => $templateLibrary,
	'CURRENCIES' => $currencyList,
	'ITEM' => array(
		'ID' => $arResult['ID'],
		'IBLOCK_ID' => $arResult['IBLOCK_ID'],
		'OFFERS_SELECTED' => $arResult['OFFERS_SELECTED'],
		'JS_OFFERS' => $arResult['JS_OFFERS']
	)
);
unset($currencyList, $templateLibrary);

$mainId = $this->GetEditAreaId($arResult['ID']);
$itemIds = array(
	'ID' => $mainId,
	'DISCOUNT_PERCENT_ID' => $mainId.'_dsc_pict',
	'STICKER_ID' => $mainId.'_sticker',
	'BIG_SLIDER_ID' => $mainId.'_big_slider',
	'BIG_IMG_CONT_ID' => $mainId.'_bigimg_cont',
	'SLIDER_CONT_ID' => $mainId.'_slider_cont',
	'OLD_PRICE_ID' => $mainId.'_old_price',
	'PRICE_ID' => $mainId.'_price',
	'DISCOUNT_PRICE_ID' => $mainId.'_price_discount',
	'PRICE_TOTAL' => $mainId.'_price_total',
	'SLIDER_CONT_OF_ID' => $mainId.'_slider_cont_',
	'QUANTITY_ID' => $mainId.'_quantity',
	'QUANTITY_DOWN_ID' => $mainId.'_quant_down',
	'QUANTITY_UP_ID' => $mainId.'_quant_up',
	'QUANTITY_MEASURE' => $mainId.'_quant_measure',
	'QUANTITY_LIMIT' => $mainId.'_quant_limit',
	'BUY_LINK' => $mainId.'_buy_link',
	'ADD_BASKET_LINK' => $mainId.'_add_basket_link',
	'BASKET_ACTIONS_ID' => $mainId.'_basket_actions',
	'NOT_AVAILABLE_MESS' => $mainId.'_not_avail',
	'COMPARE_LINK' => $mainId.'_compare_link',
	'TREE_ID' => $mainId.'_skudiv',
	'DISPLAY_PROP_DIV' => $mainId.'_sku_prop',
	'DESCRIPTION_ID' => $mainId.'_description',
	'DISPLAY_MAIN_PROP_DIV' => $mainId.'_main_sku_prop',
	'OFFER_GROUP' => $mainId.'_set_group_',
	'BASKET_PROP_DIV' => $mainId.'_basket_prop',
	'SUBSCRIBE_LINK' => $mainId.'_subscribe',
	'TABS_ID' => $mainId.'_tabs',
	'TAB_CONTAINERS_ID' => $mainId.'_tab_containers',
	'SMALL_CARD_PANEL_ID' => $mainId.'_small_card_panel',
	'TABS_PANEL_ID' => $mainId.'_tabs_panel'
);
$obName = $templateData['JS_OBJ'] = 'ob'.preg_replace('/[^a-zA-Z0-9_]/', 'x', $mainId);
$name = !empty($arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'])
	? $arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']
	: $arResult['NAME'];
$title = !empty($arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE'])
	? $arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE']
	: $arResult['NAME'];
$alt = !empty($arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT'])
	? $arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT']
	: $arResult['NAME'];

$haveOffers = !empty($arResult['OFFERS']);
if ($haveOffers)
{
	$actualItem = $arResult['OFFERS'][$arResult['OFFERS_SELECTED']] ?? reset($arResult['OFFERS']);

}
else
{
	$actualItem = $arResult;
}

$price = $actualItem['ITEM_PRICES'][$actualItem['ITEM_PRICE_SELECTED']];
?>
<?
	if(empty($arResult['MORE_PHOTO'][0])){
		$arResult['MORE_PHOTO'] = [['SRC' => '/images/no_photo.png']];
	}
?>
<div class="row align-items-center" product_id="<?=$arResult['ID']?>">
	<div class="col-lg-6">
		<div class="image-modal">
		<?php
			if(!empty($arResult['MORE_PHOTO'])){
				foreach($arResult['MORE_PHOTO'] as $key => $photo){
					if(isset($photo['ID']))
						$preview_picture = CFile::ResizeImageGet($photo['ID'], ['width' => 600, 'height' => 600], BX_RESIZE_IMAGE_PROPORTIONAL);
					else
						$preview_picture = ['src' => $photo['SRC']];
					
			?>
			<img src="<?=$preview_picture['src']?>" alt="<?=$alt?>">
		<?php
					break;
				}
			}
		?>
			<div class="btn-like js-followers-btn <?=(is_followers($arResult['ID']) ? 'active' : '')?>" product_id="<?=$arResult['ID']?>"><i class="far fa-heart"></i></div>
		</div>
	</div>
	<div class="col-lg-6">
		<div class="title-middle"><?=$name?></div>
		<div class="articul">Артикул: <?=$arResult['PROPERTIES']['CML2_ARTICLE']['VALUE']?> </div>
		<div class="title-small">Описание</div>
		<div class="text-features">
		<?php
			if($arResult['DETAIL_TEXT'] != ''){
				echo $arResult['DETAIL_TEXT_TYPE'] === 'html' ? $arResult['DETAIL_TEXT'] : '<p>'.$arResult['DETAIL_TEXT'].'</p>';
			}
		?>
		</div>
		<div class="title-small">Характеристики</div>
		<div class="text-features">
			<? foreach($arResult['DISPLAY_PROPERTIES'] as $elem){ ?>
			<div class="line-feature">
				<span><?=$elem['NAME']?></span>
				<span><?=(is_array($elem['VALUE']) ? implode(', ', $elem['VALUE']): $elem['VALUE'])?></span>
			</div>
			
			<? } ?>
		</div>
		<div class="text-features">
			<div class="price-main"><?php if (atikaPriceNegotiable()) { echo 'Цена договорная'; } else { echo $price['PRINT_RATIO_PRICE']; } ?></div><br>
			<div class="quantity">
				<div class="quantity-button quantity-down">–</div>
				<input class="quantity-block__num" type="number" min="1" step="1" value="1">
				<div class="quantity-button quantity-up">+</div>
			</div>
		</div>
		<? if($price['RATIO_PRICE'] > 0){ ?>
		<a href="#" class="btn-main btn-main_page js-buy-product">в корзину</a>
		<? }else{ ?>
		<a href="#under-order" class="btn-main btn-main_page fancybox-under-order">Под заказ</a>
		<? }?>
	</div>
</div>