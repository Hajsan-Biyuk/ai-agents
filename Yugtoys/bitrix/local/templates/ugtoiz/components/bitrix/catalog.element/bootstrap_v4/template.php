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


global $USER;

$store_amount = [];
$rsStore = CCatalogStoreProduct::GetList([], ['PRODUCT_ID' => $arResult['ID']], false, false, ['*']);
while($arStore = $rsStore->Fetch()){
	$store_amount[$arStore['STORE_ID']] = $arStore;
}



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
	$showSliderControls = false;

	foreach ($arResult['OFFERS'] as $offer)
	{
		if ($offer['MORE_PHOTO_COUNT'] > 1)
		{
			$showSliderControls = true;
			break;
		}
	}
}
else
{
	$actualItem = $arResult;
	$showSliderControls = $arResult['MORE_PHOTO_COUNT'] > 1;
}

$skuProps = array();
$price = $actualItem['ITEM_PRICES'][$actualItem['ITEM_PRICE_SELECTED']];
$measureRatio = $actualItem['ITEM_MEASURE_RATIOS'][$actualItem['ITEM_MEASURE_RATIO_SELECTED']]['RATIO'];
$showDiscount = $price['PERCENT'] > 0;


$allProductPrices = \Bitrix\Catalog\PriceTable::getList(["filter" => ["PRODUCT_ID" => $actualItem['ID']]])->fetchAll();
$actualItem['ITEM_PRICES_ALL'] = $allProductPrices;

foreach($arResult['OFFERS'] as $key => $offer){
	$allProductPrices = \Bitrix\Catalog\PriceTable::getList(["filter" => ["PRODUCT_ID" => $offer['ID']]])->fetchAll();
	foreach($allProductPrices as $i => $elem){
		$allProductPrices[$i]['PRINT_PRICE'] = CurrencyFormat($elem['PRICE'], $elem['CURRENCY']);
	}
	$arResult['JS_OFFERS'][$key]['ITEM_PRICES_ALL'] = $allProductPrices;

	$rsStoreProduct = \Bitrix\Catalog\StoreProductTable::getlist(['filter' => ["=PRODUCT_ID" => $offer['ID'], '=STORE.ACTIVE' => 'Y', '>AMOUNT'=> 0], 'select' => ['AMOUNT','STORE_ID','STORE_TITLE' => 'STORE.TITLE']]);
	$ITEM_STORE = [];
	while($arStoreProduct = $rsStoreProduct->fetch()){
		$ITEM_STORE[] = $arStoreProduct;
	}
	$arResult['JS_OFFERS'][$key]['ITEM_STORE'] = $ITEM_STORE;
}

if ($arParams['SHOW_SKU_DESCRIPTION'] === 'Y')
{
	$skuDescription = false;
	foreach ($arResult['OFFERS'] as $offer)
	{
		if ($offer['DETAIL_TEXT'] != '' || $offer['PREVIEW_TEXT'] != '')
		{
			$skuDescription = true;
			break;
		}
	}
	$showDescription = $skuDescription || !empty($arResult['PREVIEW_TEXT']) || !empty($arResult['DETAIL_TEXT']);
}
else
{
	$showDescription = !empty($arResult['PREVIEW_TEXT']) || !empty($arResult['DETAIL_TEXT']);
}
$showBuyBtn = in_array('BUY', $arParams['ADD_TO_BASKET_ACTION']);
$buyButtonClassName = in_array('BUY', $arParams['ADD_TO_BASKET_ACTION_PRIMARY']) ? 'btn-primary' : 'btn-link';
$showAddBtn = in_array('ADD', $arParams['ADD_TO_BASKET_ACTION']);
$showButtonClassName = in_array('ADD', $arParams['ADD_TO_BASKET_ACTION_PRIMARY']) ? 'btn-primary' : 'btn-link';
$showSubscribe = $arParams['PRODUCT_SUBSCRIPTION'] === 'Y' && ($arResult['PRODUCT']['SUBSCRIBE'] === 'Y' || $haveOffers);

$arParams['MESS_BTN_BUY'] = $arParams['MESS_BTN_BUY'] ?: Loc::getMessage('CT_BCE_CATALOG_BUY');
$arParams['MESS_BTN_ADD_TO_BASKET'] = $arParams['MESS_BTN_ADD_TO_BASKET'] ?: Loc::getMessage('CT_BCE_CATALOG_ADD');
$arParams['MESS_NOT_AVAILABLE'] = $arParams['MESS_NOT_AVAILABLE'] ?: Loc::getMessage('CT_BCE_CATALOG_NOT_AVAILABLE');
$arParams['MESS_BTN_COMPARE'] = $arParams['MESS_BTN_COMPARE'] ?: Loc::getMessage('CT_BCE_CATALOG_COMPARE');
$arParams['MESS_PRICE_RANGES_TITLE'] = $arParams['MESS_PRICE_RANGES_TITLE'] ?: Loc::getMessage('CT_BCE_CATALOG_PRICE_RANGES_TITLE');
$arParams['MESS_DESCRIPTION_TAB'] = $arParams['MESS_DESCRIPTION_TAB'] ?: Loc::getMessage('CT_BCE_CATALOG_DESCRIPTION_TAB');
$arParams['MESS_PROPERTIES_TAB'] = $arParams['MESS_PROPERTIES_TAB'] ?: Loc::getMessage('CT_BCE_CATALOG_PROPERTIES_TAB');
$arParams['MESS_COMMENTS_TAB'] = $arParams['MESS_COMMENTS_TAB'] ?: Loc::getMessage('CT_BCE_CATALOG_COMMENTS_TAB');
$arParams['MESS_SHOW_MAX_QUANTITY'] = $arParams['MESS_SHOW_MAX_QUANTITY'] ?: Loc::getMessage('CT_BCE_CATALOG_SHOW_MAX_QUANTITY');
$arParams['MESS_RELATIVE_QUANTITY_MANY'] = $arParams['MESS_RELATIVE_QUANTITY_MANY'] ?: Loc::getMessage('CT_BCE_CATALOG_RELATIVE_QUANTITY_MANY');
$arParams['MESS_RELATIVE_QUANTITY_FEW'] = $arParams['MESS_RELATIVE_QUANTITY_FEW'] ?: Loc::getMessage('CT_BCE_CATALOG_RELATIVE_QUANTITY_FEW');
?>
<?
	$showOffersBlock = $haveOffers && !empty($arResult['OFFERS_PROP']);
	$mainBlockProperties = array_intersect_key($arResult['DISPLAY_PROPERTIES'], $arParams['MAIN_BLOCK_PROPERTY_CODE']);
	$showPropsBlock = !empty($mainBlockProperties) || $arResult['SHOW_OFFERS_PROPS'];
	$showBlockWithOffersAndProps = $showOffersBlock || $showPropsBlock;
	
	if(empty($arResult['MORE_PHOTO'][0])){
		$arResult['MORE_PHOTO'] = [['SRC' => TMPL_URL . '/images/no_photo.jpg']];
	}
	
?><?
$today = date("Y-m-d");
$today = date("Y-m-d", strtotime("+14 days"));
// У товаров с пустыми «Характеристиками» ~VALUE — строка, а не массив: обращение ['TEXT'] в PHP 8 давало 500
$propsHtml = (isset($arResult['PROPERTIES']['PROPS_HTML']['~VALUE']) && is_array($arResult['PROPERTIES']['PROPS_HTML']['~VALUE']) && isset($arResult['PROPERTIES']['PROPS_HTML']['~VALUE']['TEXT'])) ? (string)$arResult['PROPERTIES']['PROPS_HTML']['~VALUE']['TEXT'] : '';
?>

<?
// П-3.5: разметка Product через json_encode — раньше перенос строки в description делал JSON невалидным.
// Цена в разметке — только видимая на странице: авторизованным оптовая, гостям розничная (тип цены 2); нет видимой цены — без offers.
$ldPrice = null;
if (IsAuthorized()) {
	if (isset($price['RATIO_PRICE'])) $ldPrice = $price['RATIO_PRICE'];
} else {
	$ldRetail = \Bitrix\Catalog\PriceTable::getList(["filter" => ["CATALOG_GROUP_ID" => 2, "PRODUCT_ID" => $arResult['ID']]])->fetch();
	if ($ldRetail) $ldPrice = $ldRetail['PRICE'];
}
$ld = array(
	'@context' => 'https://schema.org/',
	'@type' => 'Product',
	'name' => html_entity_decode(strip_tags($name), ENT_QUOTES, 'UTF-8'),
	'description' => trim(html_entity_decode(strip_tags($propsHtml), ENT_QUOTES, 'UTF-8')),
	'sku' => (string)$arResult['PROPERTIES']['ARTNUMBER']['VALUE'],
);
if (!empty($arResult['DETAIL_PICTURE']['SRC'])) $ld['image'] = 'https://www.yugtoys.ru' . $arResult['DETAIL_PICTURE']['SRC'];
if ($ldPrice !== null && $ldPrice > 0) {
	$ld['offers'] = array(
		'@type' => 'Offer',
		'url' => 'https://www.yugtoys.ru' . $APPLICATION->GetCurPage(false),
		'priceCurrency' => 'RUB',
		'price' => round((float)$ldPrice, 2),
		'priceValidUntil' => $today,
		'availability' => 'https://schema.org/InStock',
		'itemCondition' => 'https://schema.org/NewCondition',
	);
}
?>
<script type="application/ld+json"><?=json_encode($ld, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | (defined('JSON_INVALID_UTF8_SUBSTITUTE') ? JSON_INVALID_UTF8_SUBSTITUTE : 0))?></script>

<section class="card" id="<?=$itemIds['ID']?>"  product_id="<?=$arResult['ID']?>">
	<div class="container">
		<?$APPLICATION->IncludeComponent("bitrix:breadcrumb", "breadcrumb", ["PATH" => "", "SITE_ID" => "s1", "START_FROM" => "0"]);?>
		<div class="row">
			<div class="col-lg-2">
				<ul class="slider-nav">
				<?php
					if(!empty($arResult['MORE_PHOTO'])){
						foreach($arResult['MORE_PHOTO'] as $key => $photo){
							if(isset($photo['ID']))
								$preview_picture = CFile::ResizeImageGet($photo['ID'], ['width' => 200, 'height' => 200], BX_RESIZE_IMAGE_PROPORTIONAL);
							else
								$preview_picture = ['src' => $photo['SRC']];
					?>
					<li>
						<a href="<?=$preview_picture['src']?>" class="slider-nav__item">
							<img src="<?=$preview_picture['src']?>" alt="<?=$alt?>">
						</a>
					</li>
					<?php
						}
					}
				?>
				</ul>
			</div>
			<div class="col-lg-3 col_for">
				<ul class="slider-for">
				<?php
					if(!empty($arResult['MORE_PHOTO'])){
						foreach($arResult['MORE_PHOTO'] as $key => $photo){
							if(isset($photo['ID']))
								$preview_picture = CFile::ResizeImageGet($photo['ID'], ['width' => 930, 'height' => 930], BX_RESIZE_IMAGE_PROPORTIONAL);
							else
								$preview_picture = ['src' => $photo['SRC']];
					?>
					<li>
						<a href="<?=$preview_picture['src']?>" class="slider-for__item fancybox" rel="group1">
							<? if(!empty($arResult['PROPERTIES']['CH_ZN']['VALUE'])){ ?>
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
							<? if(!empty($arResult['PROPERTIES']['MARKER']['VALUE'])){ ?>
							<div class="status-catalog ic_<?=$arResult['PROPERTIES']['MARKER']['VALUE_XML_ID']?>" <?=(!empty($arResult['PROPERTIES']['CH_ZN']['VALUE']) ? 'style="left: 35px;"' : '')?>><?=$arResult['PROPERTIES']['MARKER']['VALUE']?></div>
							<? } ?>
							<img src="<?=$preview_picture['src']?>" alt="<?=$alt?>">
						</a>
					</li>
					<?php
						}
					}
				?>
				</ul>
			</div>
			<div class="col-lg-7">
				<div class="card-info">
					<div class="card-info__main">
						<div class="articul">Артикул: <?=$arResult['PROPERTIES']['ARTNUMBER']['VALUE']?></div>
						<h1 class="title-card"><?=$name?></h1>
						<div class="articul"><?=nl2br($propsHtml)?></div>
						<div class="articul">
						<? if($arResult['PROPERTIES']['IN_STOCK_M1']['VALUE'] == 'Y'){ ?>
							ул. Новороссийская, 98:&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-weight: bold; color: #2389fc;">В наличии</span>
						<? }else{ ?>
							ул. Новороссийская, 98:&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-weight: bold; color: #fd1e20;">Нет&nbsp;наличии</span>
						<? } ?>
						<br>
						<? if($arResult['PROPERTIES']['IN_STOCK_M2']['VALUE'] == 'Y'){ ?>
							ул. Уральская, 97/1:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-weight: bold; color: #2389fc;">В наличии</span>
						<? }else{ ?>
							ул. Уральская, 97/1:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-weight: bold; color: #fd1e20;">Нет&nbsp;наличии</span>
						<? } ?>
						</div>
						<? if(IsAuthorized()){ ?>
							<div class="prices-wrap">
								<div class="price-old" <?=($price['RATIO_PRICE'] >= $price['RATIO_BASE_PRICE'] ? 'style="display: none;"' : '')?>><?=$price['PRINT_RATIO_BASE_PRICE']?></div>
								<div class="price-main"><?=$price['PRINT_RATIO_PRICE']?></div>
							</div>
							<? if($arResult['CAN_BUY']){ ?>
								<? if($arResult['PROPERTIES']['IN_STOCK_M2']['VALUE'] == 'Y' && $arResult['PROPERTIES']['IN_STOCK_M1']['VALUE'] <> 'Y'){ ?>
							<div class="buttons-catalog">
								<a href="tel:+79892660516" nothref="#contact_manager" class="btn-main not-fancybox-contact-manager">связаться с менеджером</a>
							</div>
								<? }else{ ?>
							<div class="buttons-catalog">
								<div class="quantity">
									<div class="quantity-button quantity-down">–</div>
									<input class="quantity-inp" type="number" min="1" max="<?=$arResult['CATALOG_QUANTITY']?>" step="1" value="1">
									<div class="quantity-button quantity-up">+</div>
								</div>
								<a href="#" class="btn-main js-buy-product">в корзину</a>
							</div>
								<? } ?>
							<? }else{ ?>
							<div class="buttons-catalog">
								<a href="#" class="btn-main not-can_buy">нет в наличии</a>
							</div>
							<? } ?>
						<? }else{ ?>
						<?
							$allProductPrices = \Bitrix\Catalog\PriceTable::getList(["filter" => ["CATALOG_GROUP_ID" => 2, "PRODUCT_ID" => $arResult['ID']]])->fetchAll();
							if(!empty($allProductPrices)){
						?>
							<div class="price-card">Розничная цена: <strong><?=CurrencyFormat($allProductPrices[0]['PRICE'], $allProductPrices[0]['CURRENCY'])?></strong></div>
							<? } ?>
							<div class="card-info__authorization">
								<p>Оптовые цены предоставляются после авторизации на сайте</p>
								<a href="/personal/private/" class="btn-main">Пройти авторизацию</a>
							</div>
							<? if(!empty($allProductPrices) && $arResult['CAN_BUY']){ ?>
								<? if($arResult['PROPERTIES']['IN_STOCK_M2']['VALUE'] == 'Y' && $arResult['PROPERTIES']['IN_STOCK_M1']['VALUE'] <> 'Y'){ ?>
								<? }else{ ?>
								<a href="#" class="btn-main btn-main_page js-buy-product">добавить в корзину</a>
								<? } ?>
							<? }else{ ?>
							<? } ?>
						<? } ?>
					</div>
					<div class="card-info__delivery">
						<p>Доставка по городу Краснодару при покупке на сумму свыше <strong>10 000 Р</strong> <span>осуществляется бесплатно</span></p>
						<p>Минимальная оптовая закупка <br>от <strong>10 000 Р</strong> работаем мы строго по документам <span>с ИП и организациями</span></p>
						<p>Доставка по Краснодарскому краю при покупке на сумму свыше <strong>20 000 Р</strong> <span>осуществляется бесплатно</span></p>
						<p>Доставка в населенные пункты Краснодарского края осуществляется <br><span>в определенные дни недели</span></p>
					</div>
				</div>
			</div>
		</div>
		<ul class="tabs-card">
			<?php
				if($arResult['DETAIL_TEXT'] != ''){
				   ?>
				   <li class="active"><a href="#tab_card1">описание</a></li>
				   <?php
				}
			?>
				<?php
				if($propsHtml != ''){
				    ?>
				   	<li><a href="#tab_card2">характеристики</a></li>
					<?php
				}
			?>
		    
		 <?php
        				if(!empty($arResult['DISPLAY_PROPERTIES'])){
        					foreach($arResult['DISPLAY_PROPERTIES'] as $key => $prop){
        						if($key == 'ARTNUMBER') continue;
        				?>
        				
        		<li><a href="#tab_card3">Свойства</a></li>
        				<?php
        					break;
        				}
        				}
        			?>
	
		</ul>
		<div class="tab-container-card">
		    <?php
				if($arResult['DETAIL_TEXT'] != ''){
				   ?>
				   <div class="tab-pane-card" id="tab_card1">
        			<?php
        				if($arResult['DETAIL_TEXT'] != ''){
        					echo $arResult['DETAIL_TEXT_TYPE'] === 'html' ? $arResult['DETAIL_TEXT'] : '<p>'.$arResult['DETAIL_TEXT'].'</p>';
        				}
        			?>
        			</div>
				   <?php
				}
			?>
			<?php
				if($propsHtml != ''){
				    ?>
				   	<div class="tab-pane-card" id="tab_card2">
        			<?php
        				if($propsHtml != ''){
        					echo nl2br($propsHtml);
        				}
        			?>
        			</div>
					<?php
				}
			?>
			 <?php
        				if(!empty($arResult['DISPLAY_PROPERTIES'])){
        					foreach($arResult['DISPLAY_PROPERTIES'] as $key => $prop){
        						if($key == 'ARTNUMBER') continue;
        				?>
        				
        	<div class="tab-pane-card" id="tab_card3">
        			<?php
        				if(!empty($arResult['DISPLAY_PROPERTIES'])){
        					foreach($arResult['DISPLAY_PROPERTIES'] as $key => $prop){
        						if($key == 'ARTNUMBER') continue;
        				?>
        				<p><strong><?=$prop['NAME']?>:</strong> <?=$prop['VALUE']?></p>
        				<?php
        					}
        				}
        			?>
        			</div>
        				<?php
        					break;
        				}
        				}
        			?>
			
			
		</div>
	</div>
</section>

<meta itemprop="name" content="<?=$name?>" />
<meta itemprop="category" content="<?=$arResult['CATEGORY_PATH']?>" />
<?php
if ($haveOffers)
{
	$offerIds = array();
	$offerCodes = array();

	$useRatio = $arParams['USE_RATIO_IN_RANGES'] === 'Y';

	foreach ($arResult['JS_OFFERS'] as $ind => &$jsOffer)
	{
		$offerIds[] = (int)$jsOffer['ID'];
		$offerCodes[] = $jsOffer['CODE'];

		$fullOffer = $arResult['OFFERS'][$ind];
		$measureName = $fullOffer['ITEM_MEASURE']['TITLE'];

		$strAllProps = '';
		$strMainProps = '';
		$strPriceRangesRatio = '';
		$strPriceRanges = '';

		if ($arResult['SHOW_OFFERS_PROPS'])
		{
			if (!empty($jsOffer['DISPLAY_PROPERTIES']))
			{
				foreach ($jsOffer['DISPLAY_PROPERTIES'] as $property)
				{
					$current = '<dt>'.$property['NAME'].'</dt><dd>'.(
						is_array($property['VALUE'])
							? implode(' / ', $property['VALUE'])
							: $property['VALUE']
						).'</dd>';
					$strAllProps .= $current;

					if (isset($arParams['MAIN_BLOCK_OFFERS_PROPERTY_CODE'][$property['CODE']]))
					{
						$strMainProps .= $current;
					}
				}

				unset($current);
			}
		}

		if ($arParams['USE_PRICE_COUNT'] && count($jsOffer['ITEM_QUANTITY_RANGES']) > 1)
		{
			$strPriceRangesRatio = '('.Loc::getMessage(
					'CT_BCE_CATALOG_RATIO_PRICE',
					array('#RATIO#' => ($useRatio
							? $fullOffer['ITEM_MEASURE_RATIOS'][$fullOffer['ITEM_MEASURE_RATIO_SELECTED']]['RATIO']
							: '1'
						).' '.$measureName)
				).')';

			foreach ($jsOffer['ITEM_QUANTITY_RANGES'] as $range)
			{
				if ($range['HASH'] !== 'ZERO-INF')
				{
					$itemPrice = false;

					foreach ($jsOffer['ITEM_PRICES'] as $itemPrice)
					{
						if ($itemPrice['QUANTITY_HASH'] === $range['HASH'])
						{
							break;
						}
					}

					if ($itemPrice)
					{
						$strPriceRanges .= '<dt>'.Loc::getMessage(
								'CT_BCE_CATALOG_RANGE_FROM',
								array('#FROM#' => $range['SORT_FROM'].' '.$measureName)
							).' ';

						if (is_infinite($range['SORT_TO']))
						{
							$strPriceRanges .= Loc::getMessage('CT_BCE_CATALOG_RANGE_MORE');
						}
						else
						{
							$strPriceRanges .= Loc::getMessage(
								'CT_BCE_CATALOG_RANGE_TO',
								array('#TO#' => $range['SORT_TO'].' '.$measureName)
							);
						}

						$strPriceRanges .= '</dt><dd>'.($useRatio ? $itemPrice['PRINT_RATIO_PRICE'] : $itemPrice['PRINT_PRICE']).'</dd>';
					}
				}
			}

			unset($range, $itemPrice);
		}

		$jsOffer['DISPLAY_PROPERTIES'] = $strAllProps;
		$jsOffer['DISPLAY_PROPERTIES_MAIN_BLOCK'] = $strMainProps;
		$jsOffer['PRICE_RANGES_RATIO_HTML'] = $strPriceRangesRatio;
		$jsOffer['PRICE_RANGES_HTML'] = $strPriceRanges;
	}

	$templateData['OFFER_IDS'] = $offerIds;
	$templateData['OFFER_CODES'] = $offerCodes;
	unset($jsOffer, $strAllProps, $strMainProps, $strPriceRanges, $strPriceRangesRatio, $useRatio);
	
	$jsParams = array(
		'CONFIG' => array(
			'USE_CATALOG' => false,
			'SHOW_QUANTITY' => false,
			'SHOW_PRICE' => false,
			'SHOW_DISCOUNT_PERCENT' => false,
			'SHOW_OLD_PRICE' => false,
			'USE_PRICE_COUNT' => false,
			'DISPLAY_COMPARE' => false,
			'SHOW_SKU_PROPS' => $arResult['SHOW_OFFERS_PROPS'],
			'OFFER_GROUP' => $arResult['OFFER_GROUP'],
			'MAIN_PICTURE_MODE' => false,
			'ADD_TO_BASKET_ACTION' => false,
			'SHOW_CLOSE_POPUP' => false,
			'SHOW_MAX_QUANTITY' => false,
			'RELATIVE_QUANTITY_FACTOR' => false,
			'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
			'USE_STICKERS' => false,
			'USE_SUBSCRIBE' => false,
			'SHOW_SLIDER' => false,
			'SLIDER_INTERVAL' => false,
			'ALT' => $alt,
			'TITLE' => $title,
			'MAGNIFIER_ZOOM_PERCENT' => 200,
			'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
			'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
			'BRAND_PROPERTY' => !empty($arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']])
				? $arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']]['DISPLAY_VALUE']
				: null,
			'SHOW_SKU_DESCRIPTION' => $arParams['SHOW_SKU_DESCRIPTION'],
			'DISPLAY_PREVIEW_TEXT_MODE' => $arParams['DISPLAY_PREVIEW_TEXT_MODE']
		),
		'PRODUCT_TYPE' => $arResult['PRODUCT']['TYPE'],
		'VISUAL' => $itemIds,
		'DEFAULT_PICTURE' => array(
			'PREVIEW_PICTURE' => $arResult['DEFAULT_PICTURE'],
			'DETAIL_PICTURE' => $arResult['DEFAULT_PICTURE']
		),
		'PRODUCT' => array(
			'ID' => $arResult['ID'],
			'ACTIVE' => $arResult['ACTIVE'],
			'NAME' => $arResult['~NAME'],
			'CATEGORY' => $arResult['CATEGORY_PATH'],
			'DETAIL_TEXT' => $arResult['DETAIL_TEXT'],
			'DETAIL_TEXT_TYPE' => $arResult['DETAIL_TEXT_TYPE'],
			'PREVIEW_TEXT' => $arResult['PREVIEW_TEXT'],
			'PREVIEW_TEXT_TYPE' => $arResult['PREVIEW_TEXT_TYPE']
		),
		'BASKET' => array(
			'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
			'BASKET_URL' => $arParams['BASKET_URL'],
			'SKU_PROPS' => $arResult['OFFERS_PROP_CODES'],
			'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
			'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE']
		),
		'OFFERS' => $arResult['JS_OFFERS'],
		'OFFER_SELECTED' => $arResult['OFFERS_SELECTED'],
		'TREE_PROPS' => $skuProps
	);
?>
<script>
	BX.message({
		ECONOMY_INFO_MESSAGE: '<?=GetMessageJS('CT_BCE_CATALOG_ECONOMY_INFO2')?>',
		TITLE_ERROR: '<?=GetMessageJS('CT_BCE_CATALOG_TITLE_ERROR')?>',
		TITLE_BASKET_PROPS: '<?=GetMessageJS('CT_BCE_CATALOG_TITLE_BASKET_PROPS')?>',
		BASKET_UNKNOWN_ERROR: '<?=GetMessageJS('CT_BCE_CATALOG_BASKET_UNKNOWN_ERROR')?>',
		BTN_SEND_PROPS: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_SEND_PROPS')?>',
		BTN_MESSAGE_BASKET_REDIRECT: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_BASKET_REDIRECT')?>',
		BTN_MESSAGE_CLOSE: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_CLOSE')?>',
		BTN_MESSAGE_CLOSE_POPUP: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_CLOSE_POPUP')?>',
		TITLE_SUCCESSFUL: '<?=GetMessageJS('CT_BCE_CATALOG_ADD_TO_BASKET_OK')?>',
		COMPARE_MESSAGE_OK: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_OK')?>',
		COMPARE_UNKNOWN_ERROR: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_UNKNOWN_ERROR')?>',
		COMPARE_TITLE: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_TITLE')?>',
		BTN_MESSAGE_COMPARE_REDIRECT: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_COMPARE_REDIRECT')?>',
		PRODUCT_GIFT_LABEL: '<?=GetMessageJS('CT_BCE_CATALOG_PRODUCT_GIFT_LABEL')?>',
		PRICE_TOTAL_PREFIX: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_PRICE_TOTAL_PREFIX')?>',
		RELATIVE_QUANTITY_MANY: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_MANY'])?>',
		RELATIVE_QUANTITY_FEW: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_FEW'])?>',
		SITE_ID: '<?=CUtil::JSEscape($component->getSiteId())?>'
	});

	var <?=$obName?> = new JCCatalogElement(<?=CUtil::PhpToJSObject($jsParams, false, true)?>);
	
	<? if(isset($_GET['bxajaxid'])){ ?>
	$("html, body").animate({scrollTop: 0}, 600);
	setTimeout(function(){
		$('.slider-for').slick({
			arrows: false,
			dots: true,
			infinite: true,
			slidesToShow: 1,
			slidesToScroll: 1,
			asNavFor: '.slider-nav',
			touchThreshold: 1000,
			prevArrow: '<div class="slick-prev slick-arrow"><svg width="23" height="42" viewBox="0 0 23 42" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M22 41L2 21.5L22 1" stroke="white" stroke-width="2"/></svg><div/>',
			nextArrow: '<div class="slick-next slick-arrow"><svg width="23" height="42" viewBox="0 0 23 42" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 41L21 21.5L1 1" stroke="white" stroke-width="2"/></svg><div/>',
			responsive: [
			{
				breakpoint: 767,
				settings: {
					adaptiveHeight: true,
					dots: false,
				}
			}
			]
		});

		$('.slider-nav').slick({
			arrows: false,
			dots: false,
			infinite: true,
			slidesToShow: 6,
			verticalSwiping: true,
			vertical: true,
			slidesToScroll: 1,
			asNavFor: '.slider-for',
			touchThreshold: 1000,
			focusOnSelect: true,
			prevArrow: '<div class="slick-prev slick-arrow"><svg width="23" height="42" viewBox="0 0 23 42" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M22 41L2 21.5L22 1" stroke="white" stroke-width="2"/></svg><div/>',
			nextArrow: '<div class="slick-next slick-arrow"><svg width="23" height="42" viewBox="0 0 23 42" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 41L21 21.5L1 1" stroke="white" stroke-width="2"/></svg><div/>',
			
		});
		
		$('.row_slider').slick({
			arrows: false,
			dots: true,
			infinite: true,
			slidesToShow: 2,
			slidesToScroll: 2,
			prevArrow: '<div class="slick-prev slick-arrow"><svg width="23" height="42" viewBox="0 0 23 42" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M22 41L2 21.5L22 1" stroke="white" stroke-width="2"/></svg><div/>',
			nextArrow: '<div class="slick-next slick-arrow"><svg width="23" height="42" viewBox="0 0 23 42" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 41L21 21.5L1 1" stroke="white" stroke-width="2"/></svg><div/>',
			mobileFirst: true,
			responsive: [
			{
				breakpoint: 768,
				settings: 'unslick'
			}
			]
		});
		
		$(".tab-pane__title").click(function() {
			$(this).parent().siblings().find(".tab-pane__title").removeClass("active");
			$(this).parent().siblings().find(".tab-pane__content").slideUp(200);
			$(this).toggleClass("active");
			$(this).siblings(".tab-pane__content").slideToggle(200);
		});
		
		var austDay = new Date($(".countdown").attr("date-time"));
		$(".countdown").countdown({until: austDay, format: 'dHMS'});
		
	}, 600);
<? } ?>
</script>
<?php
}
unset($actualItem, $itemIds, $jsParams);
?>