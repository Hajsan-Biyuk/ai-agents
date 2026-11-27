<?php
if (!defined('B_PROLOG_INCLUDED')  ||  B_PROLOG_INCLUDED !== true) die();

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

if ($arParams['SHOW_SKU_DESCRIPTION'] === 'Y')
{
	$skuDescription = false;
	foreach ($arResult['OFFERS'] as $offer)
	{
		if ($offer['DETAIL_TEXT'] != ''  ||  $offer['PREVIEW_TEXT'] != '')
		{
			$skuDescription = true;
			break;
		}
	}
	$showDescription = $skuDescription  ||  !empty($arResult['PREVIEW_TEXT'])  ||  !empty($arResult['DETAIL_TEXT']);
}
else
{
	$showDescription = !empty($arResult['PREVIEW_TEXT'])  ||  !empty($arResult['DETAIL_TEXT']);
}
$showBuyBtn = in_array('BUY', $arParams['ADD_TO_BASKET_ACTION']);
$buyButtonClassName = in_array('BUY', $arParams['ADD_TO_BASKET_ACTION_PRIMARY']) ? 'btn-primary' : 'btn-link';
$showAddBtn = in_array('ADD', $arParams['ADD_TO_BASKET_ACTION']);
$showButtonClassName = in_array('ADD', $arParams['ADD_TO_BASKET_ACTION_PRIMARY']) ? 'btn-primary' : 'btn-link';
$showSubscribe = $arParams['PRODUCT_SUBSCRIPTION'] === 'Y' && ($arResult['PRODUCT']['SUBSCRIBE'] === 'Y'  ||  $haveOffers);

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
	if(empty($arResult['MORE_PHOTO'][0])){
		$arResult['MORE_PHOTO'] = [['SRC' => '/images/no_photo.png']];
	}
?>
<?
if(LANGUAGE_ID == 'en'){
	include __DIR__ . '/template_en.php';
}
else{
?>
<section class="page-card" id="<?=$itemIds['ID']?>" product_id="<?=$arResult['ID']?>">
	<div class="container">
		<?$APPLICATION->IncludeComponent("bitrix:breadcrumb", "bootstrap_v4", ["PATH" => "", "SITE_ID" => "s1", "START_FROM" => "0"]);?>
		<h1 class="title-page"><?=$name?></h1>
		<div class="row">
			<div class="col-lg-1 col-md-2">
				<ul class="slider-nav">
				<?
					if(!empty($actualItem['MORE_PHOTO'])){
						foreach ($actualItem['MORE_PHOTO'] as $key => $photo){
				?>
					<li>
						<div class="slider-nav__item">
							<img src="<?=$photo['SRC']?>" alt="<?=$alt?>">
						</div>
					</li>
				<?
						}
					}
				?>
				</ul>
			</div>
			<div class="col-lg-3 col-md-4 col_for">

				<?php
					// Проверка наличия и значения свойства "HIT_SALE"
					if (!empty($arResult['PROPERTIES']['HIT_SALE']) && $arResult['PROPERTIES']['HIT_SALE']['VALUE'] == 'Y') {
				?>

					<div class="hit-sale">
						<img src="/local/templates/atika/img/hit-sale_rus.svg">
					</div>

				<?php				
				} 
				?>

				<ul class="slider-for">
				<?
					if(!empty($actualItem['MORE_PHOTO'])){
						foreach ($actualItem['MORE_PHOTO'] as $key => $photo){
				?>
					<li>
						<div class="slider-for__item">
							<img src="<?=$photo['SRC']?>" alt="<?=$alt?>">
						</div>
					</li>
				<?
						}
					}
				?>
				</ul>
			</div>
			<div class="col-lg-4 col-md-6">
				<div class="card-info">
					<div class="card-info__text">
						<p><?=$arResult['PREVIEW_TEXT']?></p>
					</div>
					<div class="card-info__price"><?=$price['PRINT_RATIO_PRICE']?>/кг<?/*=$arResult['ITEM_MEASURE']['TITLE']*/?></div>
					<div class="card-info__buttons">
						<a href="#buy" class="btn-main btn-main_transparent fancybox" info="<?=htmlspecialchars($name)?>">Заказать образец</a>
						<?php if(!empty($arResult['PROPERTIES']['OZON_LINK']['VALUE'])) { ?>
							<a rel="noindex" href="<?=$arResult['PROPERTIES']['OZON_LINK']['VALUE']?>" class="btn-main btn-main_transparent" target="_blank">Купить на OZON</a>
						<?php  } ?>
						<a href="#callback" class="btn-main fancybox">Задать вопрос отделу продаж</a>
					</div>
				</div>
			</div>
			<div class="col-lg-4">
				<?$APPLICATION->IncludeFile(SITE_DIR."include/card-sidebar.php", [], ["MODE" => "php"])?>
			</div>
			<div class="col-lg-8">
				<ul class="tabs">
					<li class="active"><a href="#tab_card1">TDS</a></li>
					<? if(!empty($arResult['PROPERTIES']['APPLICATION']['VALUE']['TEXT']) || !empty($arResult['PROPERTIES']['PRODUCTION_VIDEO']['VALUE'])){ ?>
					<li><a href="#tab_card2">Рецептура</a></li>
					<? } ?>
					<? if(!empty($arResult['PROPERTIES']['KEEPING']['VALUE']['TEXT']) || !empty($arResult['PROPERTIES']['APPLICATION_VIDEO']['VALUE']) and $arResult['PROPERTIES']['KEEPING']['VALUE']['TEXT']!='Нанесение' ){ ?>
					<li><a href="#tab_card3">Нанесение</a></li>
					<? } ?>
					<? if(!empty($arResult['PROPERTIES']['TRANSPORTATION']['VALUE']['TEXT'])){ ?>
					<li><a href="#tab_card4">Хранение</a></li>
					<? } ?>
					<? if(!empty($arResult['PROPERTIES']['PRECAUTIONS']['VALUE']['TEXT'])  and $arResult['PROPERTIES']['PRECAUTIONS']['VALUE']['TEXT']!='Применение' || !empty($arResult['PROPERTIES']['USAGE_VIDEO']['VALUE'])){ ?>
					<li><a href="#tab_card5">Применение</a></li>
					<? } ?>
					<? if(!empty($arResult['PROPERTIES']['PACKAGING']['VALUE']['TEXT']) and $arResult['PROPERTIES']['PACKAGING']['VALUE']['TEXT']!=='Презентация' || !empty($arResult['PROPERTIES']['PRESENTATION_VIDEO']['VALUE'])){ ?>
					<li><a href="#tab_card6">Презентация</a></li>
					<? } ?>
					<? if(!empty($arResult['PROPERTIES']['SPECIAL_OFFERS']['VALUE']['TEXT'])){ ?>
					<li><a href="#tab_card7">Спецпредложения</a></li>
					<? } ?>
				</ul>
				<div class="tab-container">
					<div class="tab-pane" id="tab_card1">

						<div class="tab-content-container">
						<div class="video-block">
						<? if(!empty($arResult['PROPERTIES']['RESIN_VIDEO']['VALUE'])){ ?>
							<iframe class="yt_players" width="100%" height="230" src="https://www.youtube.com/embed/<?=$arResult['PROPERTIES']['RESIN_VIDEO']['VALUE']?>?rel=0&wmode=Opaque&enablejsapi=1;showinfo=0;controls=0" frameborder="0" allowfullscreen></iframe>
						<?php } ?>
						</div>

						<div class="content-block">
						<div class="text-small">
							<?=$arResult['DETAIL_TEXT']?>
						</div>
						<? if(!empty($arResult['PROPERTIES']['FILE']['VALUE'])){ ?>
						<a href="<?=CFile::GetPath($arResult['PROPERTIES']['FILE']['VALUE'])?>" target="_blank" class="link-download">Скачать паспорт продукта <img src="<?=TMPL_URL?>/img/download.svg" alt="alt"></a>
						<? } ?>

						<? if(!empty($arResult['PROPERTIES']['RECIPE_RU']['VALUE'])){ ?>
						<a href="<?=CFile::GetPath($arResult['PROPERTIES']['RECIPE_RU']['VALUE'])?>" target="_blank" class="link-download">Рецептура</a>
						<? } ?>

						<? if(!empty($arResult['PROPERTIES']['PRODUCT_PROPERTIES_RU']['VALUE'])){ ?>
						<a href="<?=CFile::GetPath($arResult['PROPERTIES']['PRODUCT_PROPERTIES_RU']['VALUE'])?>" target="_blank" class="link-download">Свойства </a>
						<? } ?>

						<? if(!empty($arResult['PROPERTIES']['PRODUCT_APPLICATION_RU']['VALUE'])){ ?>
						<a href="<?=CFile::GetPath($arResult['PROPERTIES']['PRODUCT_APPLICATION_RU']['VALUE'])?>" target="_blank" class="link-download">Применение </a>
						<? } ?>
						</div>
						</div>


					</div>
					<? if(!empty($arResult['PROPERTIES']['APPLICATION']['VALUE']) || !empty($arResult['PROPERTIES']['PRODUCTION_VIDEO']['VALUE'])){ ?>
					<div class="tab-pane" id="tab_card2">

						<div class="tab-content-container">
						<div class="video-block">
						<? if(!empty($arResult['PROPERTIES']['PRODUCTION_VIDEO']['VALUE'])){ ?>
							<iframe class="yt_players" width="375" height="230" src="https://www.youtube.com/embed/<?=$arResult['PROPERTIES']['PRODUCTION_VIDEO']['VALUE']?>?rel=0&wmode=Opaque&enablejsapi=1;showinfo=0;controls=0" frameborder="0" allowfullscreen></iframe>
						<?php } ?>
						</div>

						<div class="content-block">
						<? if(!empty($arResult['PROPERTIES']['APPLICATION']['VALUE'])){ ?>
						<div class="text-small">
							<?=$arResult['PROPERTIES']['APPLICATION']['~VALUE']['TEXT']?>
						</div>
						<? } ?>
						</div>
						</div>

					</div>
					<? } ?>
					<? if(!empty($arResult['PROPERTIES']['KEEPING']['VALUE']) || !empty($arResult['PROPERTIES']['APPLICATION_VIDEO']['VALUE'])){ ?>
					<div class="tab-pane" id="tab_card3">
						
						<div class="tab-content-container">
						<div class="video-block">
						<? if(!empty($arResult['PROPERTIES']['APPLICATION_VIDEO']['VALUE'])){ ?>
							<iframe class="yt_players" width="375" height="230" src="https://www.youtube.com/embed/<?=$arResult['PROPERTIES']['APPLICATION_VIDEO']['VALUE']?>?rel=0&wmode=Opaque&enablejsapi=1;showinfo=0;controls=0" frameborder="0" allowfullscreen></iframe>
						<?php } ?>
						</div>
						
						<div class="content-block">
						<? if(!empty($arResult['PROPERTIES']['KEEPING']['VALUE'])){ ?>
						<div class="text-small">
							<?=$arResult['PROPERTIES']['KEEPING']['~VALUE']['TEXT']?>
						</div>
						<? } ?>
						</div>
						</div>

					</div>
					<? } ?>
					<? if(!empty($arResult['PROPERTIES']['TRANSPORTATION']['VALUE'])){ ?>
					<div class="tab-pane" id="tab_card4">			
						<div class="text-small">
							<?=$arResult['PROPERTIES']['TRANSPORTATION']['~VALUE']['TEXT']?>
						</div>
					</div>
					<? } ?>
					<? if(!empty($arResult['PROPERTIES']['PRECAUTIONS']['VALUE']) || !empty($arResult['PROPERTIES']['USAGE_VIDEO']['VALUE'])){ ?>
					<div class="tab-pane" id="tab_card5">		
						
						<div class="tab-content-container">
						<div class="video-block">
						<? if(!empty($arResult['PROPERTIES']['USAGE_VIDEO']['VALUE'])){ ?>
							<iframe class="yt_players" width="375" height="230" src="https://www.youtube.com/embed/<?=$arResult['PROPERTIES']['USAGE_VIDEO']['VALUE']?>?rel=0&wmode=Opaque&enablejsapi=1;showinfo=0;controls=0" frameborder="0" allowfullscreen></iframe>
						<?php } ?>
						</div>

						<div class="content-block">
						<? if(!empty($arResult['PROPERTIES']['PRECAUTIONS']['VALUE'])){ ?>
						<div class="text-small">
							<?=$arResult['PROPERTIES']['PRECAUTIONS']['~VALUE']['TEXT']?>
						</div>
						<? } ?>
						</div>
						</div>

					</div>
					<? } ?>
					<? if(!empty($arResult['PROPERTIES']['PACKAGING']['VALUE']) || !empty($arResult['PROPERTIES']['PRESENTATION_VIDEO']['VALUE'])){ ?>
					<div class="tab-pane" id="tab_card6">
						
						<div class="tab-content-container">
						<div class="video-block">
						<? if(!empty($arResult['PROPERTIES']['PRESENTATION_VIDEO']['VALUE'])){ ?>
							<iframe class="yt_players" width="375" height="230" src="https://www.youtube.com/embed/<?=$arResult['PROPERTIES']['PRESENTATION_VIDEO']['VALUE']?>?rel=0&wmode=Opaque&enablejsapi=1;showinfo=0;controls=0" frameborder="0" allowfullscreen></iframe>
						<?php }?>
						</div>

						<div class="content-block">
						<? if(!empty($arResult['PROPERTIES']['PACKAGING']['VALUE'])){ ?>
						<div class="text-small">
							<?=$arResult['PROPERTIES']['PACKAGING']['~VALUE']['TEXT']?>
						</div>
						<? } ?>
						</div>
						</div>


					</div>
					<? } ?>
					<? if(!empty($arResult['PROPERTIES']['SPECIAL_OFFERS']['VALUE'])){ ?>
					<div class="tab-pane" id="tab_card7">
						<div class="text-small">
							<?=$arResult['PROPERTIES']['SPECIAL_OFFERS']['~VALUE']['TEXT']?>
						</div>
					</div>
					<? } ?>
				</div>
			</div>
		</div>
	</div>
</section>
<?php if (!empty($arResult['NAME'])): ?>
<?php
// --- генератор рейтинга и количества отзывов (перед JSON-LD) ---
$curr_review_count = strlen($_SERVER['REQUEST_URI']);
$curr_url_count = $curr_review_count % 6;

switch ($curr_url_count){
	case 0: $result_rating = 5;   break;
	case 1: $result_rating = 4.9; break;
	case 2: $result_rating = 4.8; break;
	case 3: $result_rating = 4.7; break;
	case 4: $result_rating = 4.6; break;
	case 5: $result_rating = 4.5; break;
}
$review_count = ($curr_review_count % 23) + 1; // всегда >= 1
?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Product",
  "name": "<?php echo htmlspecialchars($arResult['NAME']); ?>",
  "description": "<?php
    $desc = $arResult['PREVIEW_TEXT'] ?: $arResult['DETAIL_TEXT'] ?: 'Химическое сырьё от ГК «Атика»';
    echo htmlspecialchars(mb_substr($desc, 0, 160, 'UTF-8'));
  ?>",
  "url": "https://attikarus.ru<?php echo htmlspecialchars($APPLICATION->GetCurPage()); ?>",
  "category": "<?php echo htmlspecialchars($arResult['CATEGORY_PATH']); ?>",
  "brand": {
    "@type": "Brand",
    "name": "Атика"
  },
  "image": [
    <?php
    if (!empty($actualItem['MORE_PHOTO'])) {
        $images = array_map(function($photo) {
            return '"https://attikarus.ru' . htmlspecialchars($photo['SRC']) . '"';
        }, $actualItem['MORE_PHOTO']);
        echo implode(',', $images);
    } else {
        echo '"https://attikarus.ru/images/no_photo.png"';
    }
    ?>
  ],
  "offers": {
    "@type": "Offer",
    "price": "<?php echo $price['RATIO_PRICE'] ?: '0.00'; ?>",
    "priceCurrency": "RUB",
    "availability": "<?php echo ($actualItem['CAN_BUY'] ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock'); ?>"
  },
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": <?php echo $result_rating; ?>,
    "reviewCount": <?php echo $review_count; ?>,
    "bestRating": 5,
    "worstRating": 1
  },
  "review": [
    {
      "@type": "Review",
      "author": { "@type": "Organization", "name": "Атика" },
      "datePublished": "<?php echo date('Y-m-d'); ?>",
      "name": "Оценка товара",
      "reviewBody": "Автоматически сформированная агрегированная оценка.",
      "reviewRating": {
        "@type": "Rating",
        "ratingValue": <?php echo $result_rating; ?>,
        "bestRating": 5,
        "worstRating": 1
      }
    }
  ]
}
</script>

<?php endif; ?>
<? } ?>