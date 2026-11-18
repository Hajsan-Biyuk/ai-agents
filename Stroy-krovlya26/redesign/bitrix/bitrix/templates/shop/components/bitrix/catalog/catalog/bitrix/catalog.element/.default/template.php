<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bas\Pict;?>
<?if (!CModule::IncludeModule('intec.startshop')) return;?>
<?$sUniqueID = 'startshop_catalog_element_'.spl_object_hash($this);?>
<?
 global $APPLICATION;
	global $options;

 $this->setFrameMode(true);
 $frame = $this->createFrame()->begin();


 $arFlags = array(
 'LEFT_MENU' => $options['CATALOG_PRODUCT_MENU']['ACTIVE_VALUE'] == "Y" ? true : false,
 'SLIDER_SHOW' => $arParams['SHOW_SLIDER_IN_ELEMENT'] == "Y" ? true : false,
 'PROPERTIES_MINIMAL_SHOW' => $options['CATALOG_PRODUCT_MIN_PROPERTIES']['ACTIVE_VALUE'] == "Y" ? true : false,
 'ADAPTABLE' => $options['ADAPTIV']['ACTIVE_VALUE'] == "Y" ? true : false,
		'SHOW_PRODUCT_OF_DAY' => !empty($arResult['PROPERTIES']['CML2_DAY_PROD']['VALUE'])
 );
?>
<?
$this->SetViewTarget('left-block');
if($arResult["SECTION"]["IBLOCK_SECTION_ID"]):
$sect=$arResult["SECTION"]["IBLOCK_SECTION_ID"];
?>
<? 
$APPLICATION->IncludeComponent(
	"bitrix:catalog.section.list",
	"left-block",
	array(
		"IBLOCK_TYPE" => $arResult["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arResult["IBLOCK_ID"],
		"SECTION_ID" => $sect,
		"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
		"CACHE_TYPE" => $arResult["CACHE_TYPE"],
		"CACHE_TIME" => $arResult["CACHE_TIME"],
		"CACHE_GROUPS" => $arResult["CACHE_GROUPS"],
		"COUNT_ELEMENTS" => $arResult["SECTION_COUNT_ELEMENTS"],
		"TOP_DEPTH" => $arResult["SECTION_TOP_DEPTH"],
		"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
		"VIEW_MODE" => $arResult["SECTIONS_VIEW_MODE"],
		"SHOW_PARENT_NAME" => $arResult["SECTIONS_SHOW_PARENT_NAME"],
		"HIDE_SECTION_NAME" => (isset($arResult["SECTIONS_HIDE_SECTION_NAME"]) ? $arResult["SECTIONS_HIDE_SECTION_NAME"] : "N"),
		"ADD_SECTIONS_CHAIN" => "N",
	),
	$component,
	array("HIDE_ICONS" => "Y")
);
else:
$sect1=$arResult["SECTION"]["ID"];
$APPLICATION->IncludeComponent(
	"bitrix:catalog.section.list",
	"left-block",
	array(
		"IBLOCK_TYPE" => $arResult["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arResult["IBLOCK_ID"],
		"SECTION_ID" => $sect1,
		"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
		"CACHE_TYPE" => $arResult["CACHE_TYPE"],
		"CACHE_TIME" => $arResult["CACHE_TIME"],
		"CACHE_GROUPS" => $arResult["CACHE_GROUPS"],
		"COUNT_ELEMENTS" => $arResult["SECTION_COUNT_ELEMENTS"],
		"TOP_DEPTH" => $arResult["SECTION_TOP_DEPTH"],
		"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
		"VIEW_MODE" => $arResult["SECTIONS_VIEW_MODE"],
		"SHOW_PARENT_NAME" => $arResult["SECTIONS_SHOW_PARENT_NAME"],
		"HIDE_SECTION_NAME" => (isset($arResult["SECTIONS_HIDE_SECTION_NAME"]) ? $arResult["SECTIONS_HIDE_SECTION_NAME"] : "N"),
		"ADD_SECTIONS_CHAIN" => "N",
	),
	$component,
	array("HIDE_ICONS" => "Y")
);
endif;
$this->EndViewTarget();
?>
<div class="CatalogDetail startshop-catalog<?=$arFlags['ADAPTABLE'] ? ' adaptiv' : ''?>" id="<?=$sUniqueID?>">
	<div class="startshop-item<?=$arFlags['LEFT_MENU']?' with-menu':''?>">
	<?reset($arResult['MORE_PHOTO']);?>
		<div class="Box">
			<div class="ImagesBlock">
									<?if (is_array($arResult["PROPERTIES"]["STIKER"]["VALUE_XML_ID"])):?>
									<div class="Stiker">
										<?foreach($arResult["PROPERTIES"]["STIKER"]["VALUE_XML_ID"] as $key=>$class){?>
										<span class="<?=$class?>"><?=$arResult["PROPERTIES"]["STIKER"]["VALUE"][$key]?></span>
										<?}?>
									</div>
									<?endif;?>
									<?if (is_array($arResult["MORE_PHOTO"]) && count($arResult["MORE_PHOTO"]) > 0):?>
										<div id="Gallery" class="owl-carousel owl-theme">
											<?if(is_array($arResult["PREVIEW_PICTURE"])):
												$imgpreview = CFile::ResizeImageGet($arResult["PREVIEW_PICTURE"], Array('width'=>600, 'height'=>500), BX_RESIZE_IMAGE_EXACT, true);
												$imgdetail = CFile::ResizeImageGet($arResult["PREVIEW_PICTURE"], Array('width'=>1000, 'height'=>1000), BX_RESIZE_IMAGE_PROPORTIONAL, true);
											?>
											<div class="Item">
												<a href="<?=$imgdetail["src"]?>" class="BigPhoto" data-fancybox="images"></a>
												<img src="<?=Pict::getResizeWebpSrc($arResult['PREVIEW_PICTURE'], 600, 600)?>" alt="<?=$arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT']?> 1" title="<?=$arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE']?> 1">
											</div>
											<?endif?>
											<?
											$item=2;
											foreach($arResult["PROPERTIES"]["MORE_PHOTO"]["VALUE"] as $PHOTO):
												$img = CFile::ResizeImageGet($PHOTO, Array('width'=>600, 'height'=>500), BX_RESIZE_IMAGE_EXACT, true);
												$img_big = CFile::ResizeImageGet($PHOTO, Array('width'=>1000, 'height'=>1000), BX_RESIZE_IMAGE_PROPORTIONAL, true);
											?>
											<div class="Item">
												<a href="<?=$img_big["src"]?>" class="BigPhoto" data-fancybox="images"></a>
												<img src="<?=Pict::getResizeWebpSrc($PHOTO, 600, 600)?>" alt="<?=$arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT']?><?=$item;?>" title="<?=$arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE']?><?=$item;?>">
											</div> 
											<?$item++;
											endforeach?>
										</div>
										<div id="Miniatures" class="owl-carousel owl-theme">
											<?if(is_array($arResult["PREVIEW_PICTURE"])):
												$imgmoresmall = CFile::ResizeImageGet($arResult["PREVIEW_PICTURE"], Array('width'=>90, 'height'=>80), BX_RESIZE_IMAGE_EXACT, true);
											?>
											<div class="Item">
												<img src="<?=Pict::getResizeWebpSrc($arResult["PREVIEW_PICTURE"], 90, 80)?>" alt="<?=$arResult["NAME"]?>" title="<?=$arResult["NAME"]?>">
											</div>
											<?endif?>
											<?foreach($arResult["PROPERTIES"]["MORE_PHOTO"]["VALUE"] as $PHOTO):
												$imgsmall = CFile::ResizeImageGet($PHOTO, Array('width'=>90, 'height'=>80), BX_RESIZE_IMAGE_EXACT, true);
											?>
											<div class="Item">
												<img src="<?=Pict::getResizeWebpSrc($PHOTO, 90, 80)?>" alt="<?=$arResult["NAME"]?>" title="<?=$arResult["NAME"]?>">
											</div>
											<?endforeach?>
										</div>
									<?elseif(is_array($arResult["PREVIEW_PICTURE"])):
										$imgdetail = CFile::ResizeImageGet($arResult["PREVIEW_PICTURE"], Array('width'=>500, 'height'=>500), BX_RESIZE_IMAGE_PROPORTIONAL, true);
										$img_big_detail = CFile::ResizeImageGet($arResult["PREVIEW_PICTURE"], Array('width'=>1000, 'height'=>1000), BX_RESIZE_IMAGE_PROPORTIONAL, true);
									?>
									<div class="ImageBlock">
										<a href="<?=$img_big_detail["src"]?>" class="BigPhoto" data-fancybox="gallery"></a>
										<img itemprop="image" class="item_img" src="<?=Pict::getResizeWebpSrc($arResult["PREVIEW_PICTURE"], 600, 600)?>" alt="<?=$arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT']?> 1" title="<?=$arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE']?> 1" />
									</div>
									<?endif?>
			</div>
			<div class="DescBlock startshop-information">
				<div class="Info">
					<?if($arResult["PROPERTIES"]["BREND"]["VALUE"]):?> 
					<div class="Brend">
						<?$res = CIBlockElement::GetByID($arResult["PROPERTIES"]["BREND"]["VALUE"]);
						if($ar_res = $res->GetNext())
						echo "Производитель: <a href=".$ar_res['DETAIL_PAGE_URL'].">".$ar_res['NAME']."</a>";?>
					</div>
					<?endif?>
					<?if($arResult["PROPERTIES"]["NALICHIE"]["VALUE"]):?>
							<div class="Availability"><?if($arResult["PROPERTIES"]["NALICHIE"]["VALUE_ENUM_ID"]=="30"):?><div class="available"><?else:?><div class="unavailable"><?endif?><?=$arResult["PROPERTIES"]["NALICHIE"]["VALUE"]?></div></div>
					<?endif?>	
				</div>
				<div class="Info">
 <?if (!empty($arResult['STARTSHOP']['OFFERS'])):?>
					 <?include('parts/OffersProperties.php')?>
 <?endif;?>
					<?include('parts/Order.php')?>
				</div>
			</div>
		</div>
								<div class="Clear"></div>
								<div class="PropsTabs <?if($arResult["PROPERTIES"]["VIEW_GARANTY"]["VALUE"]=="Да"):?> Big<?endif;?>">
									<ul class="Links">
										<?if($arResult["PREVIEW_TEXT"]):?>
										<li class="Active"><span>Описание</span></li>
										<?endif;?>
										<?if($arResult["PROPERTIES"]["PROPS"]["VALUE"]):?>
										<li<?if(!$arResult["PREVIEW_TEXT"]):?> class="Active"<?endif;?>>Характеристики</li>
										<?endif;?>
									</ul> 
									<?if($arResult["PREVIEW_TEXT"]):?>
									<div class="Box Active">
										<div class="Files">
											<div class="Garantiya">
												<div class="Image">
													<img src="<?=SITE_TEMPLATE_PATH?>/images/garantiya.png" alt="Гарантия цены" title="Гарантия цены" width="79px" height="79px">
												</div>
												<div class="Text">
													<div class="Title">Гарантия цены</div>
													<p>Если вы найдете данный товар по более низкой цене, мы сделаем лучшее предложение или дадим такую же цену.</p>
													<p>Вам достаточно предоставить информацию об альтернативном предложении.</p>
												</div>
											</div> 
										<?if($arResult["DISPLAY_PROPERTIES"]["FILE"]["VALUE"]):?>
											<?if(is_array($arResult["DISPLAY_PROPERTIES"]['FILE']["DISPLAY_VALUE"])):
												foreach($arResult["DISPLAY_PROPERTIES"]['FILE']["DISPLAY_VALUE"] as $key=>$value):				
												$ext=substr($arResult["DISPLAY_PROPERTIES"]['FILE']['FILE_VALUE'][$key]['SRC'], strrpos($arResult["DISPLAY_PROPERTIES"]['FILE']['FILE_VALUE'][$key]['SRC'], '.') + 1);?>
												<div class="Item">
													<a href="<?=$arResult["DISPLAY_PROPERTIES"]['FILE']['FILE_VALUE'][$key]['SRC']?>" title="<?=$arResult["DISPLAY_PROPERTIES"]['DESC']['VALUE']?>"><?=$arResult["DISPLAY_PROPERTIES"]['FILE']['FILE_VALUE'][$key]['DESCRIPTION']?></a>
												</div>
												<?endforeach;
													elseif($arResult["DISPLAY_PROPERTIES"]['FILE']['FILE_VALUE']['SRC']):
													$ext=substr($arResult["DISPLAY_PROPERTIES"]['FILE']['FILE_VALUE']['SRC'], strrpos($arResult["DISPLAY_PROPERTIES"]['FILE']['FILE_VALUE']['SRC'], '.') + 1);?>
												<div class="Item">
													<a href="<?=$arResult["DISPLAY_PROPERTIES"]['FILE']['FILE_VALUE']['SRC']?>" title="<?=$arResult["DISPLAY_PROPERTIES"]['DESC']['VALUE']?>"><?=$arResult["DISPLAY_PROPERTIES"]['FILE']['FILE_VALUE']['DESCRIPTION']?></a>
												</div>
											<?endif;?>	
										<?endif;?>
										</div> 
										<div class="Anonse <?if(empty($arResult["DISPLAY_PROPERTIES"]["FILE"]["VALUE"])):?>Big<?endif;?>">
											<div class="Text">
												<?=$arResult["PREVIEW_TEXT"]?>
											</div>
										</div>
										<div class="Clear"></div>
									</div> 
									<?endif;?>
									<?if($arResult["DISPLAY_PROPERTIES"]["PROPS"]["VALUE"]):?> 
									<div class="Box <?if(!$arResult["PREVIEW_TEXT"]):?>Active<?endif;?>">
									<h2 class="ProductSeoTitle">
 Купить <?=htmlspecialcharsbx($arResult["NAME"])?> в Ставрополе: описание, характеристики и цена
</h2>
										<div class="Props">
											<div class="Labels">
												<span class="Name">Наименование</span>
												<span class="Value">Значение</span>
											</div>
										<?foreach($arResult["DISPLAY_PROPERTIES"]["PROPS"]["VALUE"] as $k=>$value):?>
											<div class="Labels">
												<span class="Name"><?=$value?></span>
												<span class="Value"><?=$arResult["DISPLAY_PROPERTIES"]["PROPS"]["DESCRIPTION"][$k]?></span>
											</div>
										<?endforeach?>
										</div> 
									</div> 
									<?endif;?> 
								</div>
									<?if($arResult["PROPERTIES"]["VIEW_GARANTY"]["VALUE"]=="Да"):?> 
										<div class="Files Big">
										<?if($arResult["DISPLAY_PROPERTIES"]["FILE"]["VALUE"]):?>
											<?if(is_array($arResult["DISPLAY_PROPERTIES"]['FILE']["DISPLAY_VALUE"])):
												foreach($arResult["DISPLAY_PROPERTIES"]['FILE']["DISPLAY_VALUE"] as $key=>$value):				
												$ext=substr($arResult["DISPLAY_PROPERTIES"]['FILE']['FILE_VALUE'][$key]['SRC'], strrpos($arResult["DISPLAY_PROPERTIES"]['FILE']['FILE_VALUE'][$key]['SRC'], '.') + 1);?>
												<div class="Item">
													<a href="<?=$arResult["DISPLAY_PROPERTIES"]['FILE']['FILE_VALUE'][$key]['SRC']?>" title="<?=$arResult["DISPLAY_PROPERTIES"]['DESC']['VALUE']?>"><?=$arResult["DISPLAY_PROPERTIES"]['FILE']['FILE_VALUE'][$key]['DESCRIPTION']?></a>
												</div>
												<?endforeach;
													elseif($arResult["DISPLAY_PROPERTIES"]['FILE']['FILE_VALUE']['SRC']):
													$ext=substr($arResult["DISPLAY_PROPERTIES"]['FILE']['FILE_VALUE']['SRC'], strrpos($arResult["DISPLAY_PROPERTIES"]['FILE']['FILE_VALUE']['SRC'], '.') + 1);?>
												<div class="Item">
													<a href="<?=$arResult["DISPLAY_PROPERTIES"]['FILE']['FILE_VALUE']['SRC']?>" title="<?=$arResult["DISPLAY_PROPERTIES"]['DESC']['VALUE']?>"><?=$arResult["DISPLAY_PROPERTIES"]['FILE']['FILE_VALUE']['DESCRIPTION']?></a>
												</div>
											<?endif;?>	
										<?endif;?>
										</div>
									<?endif;?> 
		<?session_start();?>
	</div>
	<div style="clear: both;"></div>
</div>
	 <?$arProperty = $arResult["PROPERTIES"]["PRODUCT"]["PROPERTY_VALUE_ID"];
				if($arProperty !=""): 
					global $arRecPrFilter;
					
					$arRecPrFilter["ID"] = $arResult["PROPERTIES"]["PRODUCT"]["VALUE"];
					$APPLICATION->IncludeComponent(
					"bitrix:catalog.section", "related",
					array(
						"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
						"IBLOCK_ID" => $arParams["IBLOCK_ID"],
						"ELEMENT_SORT_FIELD" => $sort,
						"ELEMENT_SORT_ORDER" => $sort_order,
						"ELEMENT_SORT_FIELD2" => $sort,
						"ELEMENT_SORT_ORDER2" => $sort_order,
						"PROPERTY_CODE" => array(
			1 => "BREND",
			4 => "MEASURE",
			5 => "NALICHIE",
			6 => "STIKER",
			9 => "OLD_PRICE",
		),
						"META_KEYWORDS" => $arParams["LIST_META_KEYWORDS"],
						"META_DESCRIPTION" => $arParams["LIST_META_DESCRIPTION"],
						"BROWSER_TITLE" => $arParams["LIST_BROWSER_TITLE"],
						"SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
						"INCLUDE_SUBSECTIONS" => $arParams["INCLUDE_SUBSECTIONS"],
						"SHOW_ALL_WO_SECTION" => "Y",
						"BASKET_URL" => $arParams["BASKET_URL"],
						"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
						"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
						"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
						"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
						"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
						"FILTER_NAME" =>"arRecPrFilter", 
						"CACHE_TYPE" => $arParams["CACHE_TYPE"],
						"CACHE_TIME" => $arParams["CACHE_TIME"],
						"CACHE_FILTER" => $arParams["CACHE_FILTER"],
						"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
						"SET_TITLE" => $arParams["SET_TITLE"],
						"MESSAGE_404" => $arParams["MESSAGE_404"],
						"SET_STATUS_404" => $arParams["SET_STATUS_404"],
						"SHOW_404" => $arParams["SHOW_404"],
						"FILE_404" => $arParams["FILE_404"],
						"DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
						"PAGE_ELEMENT_COUNT" => $arParams["PAGE_ELEMENT_COUNT"],
						"LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
						"PRICE_CODE" => $arParams["PRICE_CODE"],
						"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
						"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],

						"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
						"USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
						"ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
						"PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
						"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],

						"DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
						"DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
						"PAGER_TITLE" => $arParams["PAGER_TITLE"],
						"PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
						"PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
						"PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
						"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
						"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
						"PAGER_BASE_LINK_ENABLE" => $arParams["PAGER_BASE_LINK_ENABLE"],
						"PAGER_BASE_LINK" => $arParams["PAGER_BASE_LINK"],
						"PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],

						"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
						"OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
						"OFFERS_PROPERTY_CODE" => $arParams["LIST_OFFERS_PROPERTY_CODE"],
						"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
						"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
						"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
						"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
						"OFFERS_LIMIT" => $arParams["LIST_OFFERS_LIMIT"],

						"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
						"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
						"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
						"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
						"USE_MAIN_ELEMENT_SECTION" => $arParams["USE_MAIN_ELEMENT_SECTION"],
						'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
						'CURRENCY_ID' => $arParams['CURRENCY_ID'],
						'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],

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

						'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
						"ADD_SECTIONS_CHAIN" => "N",
						'ADD_TO_BASKET_ACTION' => $basketAction,
						'SHOW_CLOSE_POPUP' => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
						'COMPARE_PATH' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['compare']
					),
						false
					);
					unset($arResult["PROPERTIES"]["PRODUCT"]);
				endif;?> 

<?
$productSchemaImages = array();
if (is_array($arResult["DETAIL_PICTURE"]) && !empty($arResult["DETAIL_PICTURE"]["SRC"])) {
	$productSchemaImages[] = $arResult["DETAIL_PICTURE"]["SRC"];
}
if (is_array($arResult["PREVIEW_PICTURE"]) && !empty($arResult["PREVIEW_PICTURE"]["SRC"])) {
	$productSchemaImages[] = $arResult["PREVIEW_PICTURE"]["SRC"];
}
if (!empty($arResult["PROPERTIES"]["MORE_PHOTO"]["VALUE"]) && is_array($arResult["PROPERTIES"]["MORE_PHOTO"]["VALUE"])) {
	foreach ($arResult["PROPERTIES"]["MORE_PHOTO"]["VALUE"] as $productSchemaPhotoId) {
		$productSchemaPhotoSrc = CFile::GetPath($productSchemaPhotoId);
		if ($productSchemaPhotoSrc) {
			$productSchemaImages[] = $productSchemaPhotoSrc;
		}
	}
}
$productSchemaImages = array_values(array_unique(array_filter($productSchemaImages)));
foreach ($productSchemaImages as $productSchemaImageKey => $productSchemaImage) {
	if (strpos($productSchemaImage, 'http') !== 0) {
		$productSchemaImages[$productSchemaImageKey] = (CMain::IsHTTPS() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $productSchemaImage;
	}
}

$productSchemaBrand = '';
if (!empty($arResult["PROPERTIES"]["BREND"]["VALUE"])) {
	$productSchemaBrandRes = CIBlockElement::GetByID($arResult["PROPERTIES"]["BREND"]["VALUE"]);
	if ($productSchemaBrandData = $productSchemaBrandRes->GetNext()) {
		$productSchemaBrand = $productSchemaBrandData['NAME'];
	}
}

$productSchemaPriceRaw = '';
if (!empty($arResult["PROPERTIES"]["STARTSHOP_PRICE_1"]["VALUE"])) {
	$productSchemaPriceRaw = $arResult["PROPERTIES"]["STARTSHOP_PRICE_1"]["VALUE"];
} elseif (!empty($arResult['STARTSHOP']['PRICES']['MINIMAL']['VALUE'])) {
	$productSchemaPriceRaw = $arResult['STARTSHOP']['PRICES']['MINIMAL']['VALUE'];
}
$productSchemaPrice = preg_replace('/[^0-9\.,]/', '', (string)$productSchemaPriceRaw);
$productSchemaPrice = str_replace(',', '.', $productSchemaPrice);

$productSchemaUrl = (CMain::IsHTTPS() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $APPLICATION->GetCurPage(false);
$productSchemaDescription = trim(strip_tags($arResult['PREVIEW_TEXT'] ?: $arResult['DETAIL_TEXT']));
$productSchemaDescription = preg_replace('/\s+/u', ' ', $productSchemaDescription);

$productSchema = array(
	'@context' => 'https://schema.org',
	'@type' => 'Product',
	'name' => $arResult['NAME'],
	'url' => $productSchemaUrl,
);
if ($productSchemaDescription !== '') {
	$productSchema['description'] = $productSchemaDescription;
}
if (!empty($productSchemaImages)) {
	$productSchema['image'] = $productSchemaImages;
}
if ($productSchemaBrand !== '') {
	$productSchema['brand'] = array(
		'@type' => 'Brand',
		'name' => $productSchemaBrand,
	);
}
if (!empty($arResult['ID'])) {
	$productSchema['sku'] = (string)$arResult['ID'];
}

$productSchemaOffer = array(
	'@type' => 'Offer',
	'url' => $productSchemaUrl,
	'priceCurrency' => 'RUB',
	'availability' => (!empty($arResult["PROPERTIES"]["NALICHIE"]["VALUE_ENUM_ID"]) && (string)$arResult["PROPERTIES"]["NALICHIE"]["VALUE_ENUM_ID"] === '30')
		? 'https://schema.org/InStock'
		: 'https://schema.org/PreOrder',
);
if ($productSchemaPrice !== '') {
	$productSchemaOffer['price'] = $productSchemaPrice;
}
$productSchema['offers'] = $productSchemaOffer;
?>
<script type="application/ld+json"><?=json_encode($productSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)?></script>

<?include('parts/OffersScript.php')?>
<?$frame->end()
?>
