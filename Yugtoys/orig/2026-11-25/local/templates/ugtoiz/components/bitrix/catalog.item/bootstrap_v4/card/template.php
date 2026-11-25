<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $item
 * @var array $actualItem
 * @var array $minOffer
 * @var array $itemIds
 * @var array $price
 * @var array $measureRatio
 * @var bool $haveOffers
 * @var bool $showSubscribe
 * @var array $morePhoto
 * @var bool $showSlider
 * @var bool $itemHasDetailUrl
 * @var string $imgTitle
 * @var string $productTitle
 * @var string $buttonSizeClass
 * @var CatalogSectionComponent $component
 */

	if(!empty($item['PREVIEW_PICTURE']['SRC'])){
		$preview_picture = CFile::ResizeImageGet($item['PREVIEW_PICTURE']['ID'], ['width'=>400, 'height'=>400], BX_RESIZE_IMAGE_PROPORTIONAL);
		$item['PREVIEW_PICTURE']['SRC'] = $preview_picture['src'];
	}
	
	if(empty($item['PREVIEW_PICTURE']['SRC'])) $item['PREVIEW_PICTURE']['SRC'] = TMPL_URL . '/images/no_photo.jpg';
?>

<? if(!empty($item['PROPERTIES']['CH_ZN']['VALUE'])){ ?>
<div class="status-catalog ic_ch_zn" title="Честный знак">
	<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 236 236" fill="none">
		<g clip-path="url(#clip0_1438_22075)">
			<rect width="236" height="236" rx="40" fill="#F4E600"></rect>
			<path d="M48.208 114.29L100.57 166.65L186.808 80.4072" stroke="#6F6F6F" stroke-width="31"></path>
			<path d="M220.65 156.003V196.57C220.65 209.825 209.905 220.57 196.65 220.57H156.092" stroke="#6F6F6F" stroke-width="31" stroke-linejoin="round"></path>
			<path d="M220.65 79.7051V39.1472C220.65 25.8921 209.905 15.1468 196.65 15.1472L156.092 15.1485" stroke="#6F6F6F" stroke-width="31" stroke-linejoin="round"></path>
			<path d="M15.2246 79.7051L15.2259 39.147C15.2264 25.8928 25.9709 15.1482 39.2252 15.1478L79.781 15.1465" stroke="#6F6F6F" stroke-width="31"></path>
			<path d="M15.2266 156.003V196.57C15.2266 209.825 25.9717 220.57 39.2266 220.57H79.7809" stroke="#6F6F6F" stroke-width="31" stroke-linejoin="round"></path>
		</g>
		<defs>
			<clipPath id="clip0_1438_22075">
				<rect width="236" height="236" rx="40" fill="white"></rect>
			</clipPath>
		</defs>
	</svg>
</div>
<? } ?>
<? if(!empty($item['PROPERTIES']['MARKER']['VALUE'])){ ?>
<div class="status-catalog ic_<?=$item['PROPERTIES']['MARKER']['VALUE_XML_ID']?>" <?=(!empty($item['PROPERTIES']['CH_ZN']['VALUE']) ? 'style="left: 35px;"' : '')?>><?=$item['PROPERTIES']['MARKER']['VALUE']?></div>
<? } ?>
<div class="btn-like js-followers-btn <?=(is_followers($item['ID']) ? 'active' : '')?>" product_id="<?=$item['ID']?>"></div>
<a href="<?=$item['DETAIL_PAGE_URL']?>" class="item-catalog__image">
	<img alt="<?=htmlspecialchars($productTitle)?>" src="<?=$item['PREVIEW_PICTURE']['SRC']?>">
</a>
<div class="articul-catalog">Артикул: <?=$item['PROPERTIES']['ARTNUMBER']['VALUE']?></div>
<a href="<?=$item['DETAIL_PAGE_URL']?>" class="title-catalog"><?=$productTitle?></a>

<?php
$propsText = '';
if (is_array($item['PROPERTIES']['PROPS_HTML']['~VALUE']) && isset($item['PROPERTIES']['PROPS_HTML']['~VALUE']['TEXT'])) {
    $propsText = strip_tags($item['PROPERTIES']['PROPS_HTML']['~VALUE']['TEXT']);
} elseif (!empty($item['PROPERTIES']['PROPS_HTML']['~VALUE'])) {
    $propsText = strip_tags($item['PROPERTIES']['PROPS_HTML']['~VALUE']);
}
?>
<div class="articul-catalog props_html"><?=$propsText?></div>
<? if(IsAuthorized()){ ?>
<div class="prices-wrap">
	<div class="price-old" <?=($price['RATIO_PRICE'] >= $price['RATIO_BASE_PRICE'] ? 'style="display: none;"' : '')?>><?=$price['PRINT_RATIO_BASE_PRICE']?></div>
	<div class="price-main"><?=$price['PRINT_RATIO_PRICE']?></div>
</div>
	<? if($actualItem['CAN_BUY']){ ?>
		<? if($item['PROPERTIES']['IN_STOCK_M2']['VALUE'] == 'Y' && $item['PROPERTIES']['IN_STOCK_M1']['VALUE'] <> 'Y'){ ?>
	<div class="buttons-catalog">
		<a href="tel:+79892660516" nothref="#contact_manager" class="btn-main not-fancybox-contact-manager">связаться с менеджером</a>
	</div>
		<? }else{ ?>
	<div class="buttons-catalog">
		<div class="quantity">
			<div class="quantity-button quantity-down">–</div>
			<input class="quantity-inp" type="number" min="1" max="<?=$actualItem['CATALOG_QUANTITY']?>" step="1" value="1">
			<div class="quantity-button quantity-up">+</div>
		</div>
		<? if(isset($arResult['BASKE_TITEMS'][$item['ID']])){ ?>
		<a href="#" class="btn-main js-buy-product in_basked">в корзине</a>
		<? }else{ ?>
		<a href="#" class="btn-main js-buy-product">в корзину</a>
		<? } ?>
	</div>
		<? } ?>
	<? }else{ ?>
	<div class="buttons-catalog">
		<a href="#" class="btn-main not-can_buy">нет в наличии</a>
	</div>
	<? } ?>
<? }else{ ?>
<br>
<div class="buttons-catalog">
	<a href="#opt_price" class="btn-main btn-main-more fancybox-one-click">купить в 1 клик</a>
</div>
<div class="item-catalog__bottom">
	<a href="/registration/" class="btn-main btn-main_page">ЗАПРОСИТЬ ОПТОВую цену</a>
</div>
<? } ?>