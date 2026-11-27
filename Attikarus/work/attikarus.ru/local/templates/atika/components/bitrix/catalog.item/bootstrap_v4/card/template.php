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

	$preview_picture = CFile::ResizeImageGet($item['PREVIEW_PICTURE']['ID'], ['width'=>300, 'height'=>300], BX_RESIZE_IMAGE_PROPORTIONAL);
	if(empty($preview_picture['src'])) $item['PREVIEW_PICTURE']['SRC'] = '/images/no_photo.png';
	else $item['PREVIEW_PICTURE']['SRC'] = $preview_picture['src'];
?>
<?
if(LANGUAGE_ID == 'en'){
	include 'template_en.php';
}
else{
?>


<?php /* if(!empty($item['PROPERTIES']['OZON_LINK']['VALUE'])) {?>
<a href="<?=$item['PROPERTIES']['OZON_LINK']['VALUE']?>" class="item-catalog__image"> 
	<img src="<?=$item['PREVIEW_PICTURE']['SRC']?>" alt="alt">
</a>
<a href="<?=$item['PROPERTIES']['OZON_LINK']['VALUE']?>" class="item-catalog__title"><?=$productTitle?></a>
<div class="item-catalog__descr"><?=$item['PREVIEW_TEXT']?></div>
<div class="item-catalog__bottom">
	<div class="price-main"><?=$price['PRINT_RATIO_PRICE']?>/<?=$item['ITEM_MEASURE']['TITLE']?></div>
	<a href="<?=$item['PROPERTIES']['OZON_LINK']['VALUE']?>" class="btn-main">Подробнее</a>
</div>
<?php } else { */ ?>

<div class="previmg">
	<div class="previmg-actions">

	<?php
		// Проверка наличия и значения свойства "HIT_SALE"
		if (!empty($item['PROPERTIES']['HIT_SALE']) && $item['PROPERTIES']['HIT_SALE']['VALUE'] == 'Y') {
			echo '<img src="	/local/templates/atika/img/hit-sale_rus.svg">';
		}
	?>


</div>

<a href="<?=$item['DETAIL_PAGE_URL']?>" class="item-catalog__image"> 
	<img src="<?=$item['PREVIEW_PICTURE']['SRC']?>" alt="<?=htmlspecialchars($item['PREVIEW_PICTURE']['ALT'] ?: $item['NAME'])?>">
</a>
</div>


<a href="<?=$item['DETAIL_PAGE_URL']?>" class="item-catalog__title"><?=$productTitle?></a>
<div class="item-catalog__descr"><?=$item['PREVIEW_TEXT']?></div>
<div class="price-main"><?php if (atikaPriceNegotiable()) { echo 'Цена договорная'; } else { echo $price['PRINT_RATIO_PRICE'].'/'.$item['ITEM_MEASURE']['TITLE']; } ?></div>
<div class="item-catalog__bottom">
	
	<? if(!empty($item['PROPERTIES']['OZON_LINK']['VALUE'])) {?>
		<a href="<?=$item['PROPERTIES']['OZON_LINK']['VALUE']?>" class="btn-main btn-short">Купить на OZON</a>
	<? } ?>
	<a href="<?=$item['DETAIL_PAGE_URL']?>" class="btn-main">Подробнее</a>
</div>

<?php /* } */ ?>

<? } ?>


<?/* ['DETAIL_PAGE_URL'] везде где ['PROPERTIES']['OZON_LINK']['VALUE']  */?>