<?
$productTitle = $item['PROPERTIES']['TITLE_EN']['VALUE'];

$productPrice = $price['PRINT_RATIO_PRICE'];
if(!empty($item['PROPERTIES']['PRICE_UE']['VALUE'])){
	$productPrice = CurrencyFormat(str_replace(',', '.', $item['PROPERTIES']['PRICE_UE']['VALUE']), $item['PROPERTIES']['PRICE_UE_LABEL']['VALUE_XML_ID']);
}
?>


<?php /* if(!empty($item['PROPERTIES']['OZON_LINK']['VALUE'])) {?>

<a href="<?=$item['PROPERTIES']['OZON_LINK']['VALUE']?>" class="item-catalog__image">
	<img src="<?=$item['PREVIEW_PICTURE']['SRC']?>" alt="alt">
</a>
<a href="<?=$item['PROPERTIES']['OZON_LINK']['VALUE']?>" class="item-catalog__title"><?=$productTitle?></a>
<div class="item-catalog__descr"><?=(!empty($item['PROPERTIES']['PREW_EN']['VALUE']) ? $item['PROPERTIES']['PREW_EN']['~VALUE']['TEXT'] : '')?></div>
<div class="item-catalog__bottom">
	<div class="price-main"><?=$productPrice?>/<?=$item['ITEM_MEASURE']['TITLE']?></div>
	<a href="<?=$item['PROPERTIES']['OZON_LINK']['VALUE']?>" class="btn-main">More detailed</a>
</div>

<?php } else { */ ?>

<div class="previmg">
<div class="previmg-actions">


<?php
		// Проверка наличия и значения свойства "HIT_SALE"
		if (!empty($item['PROPERTIES']['HIT_SALE']) && $item['PROPERTIES']['HIT_SALE']['VALUE'] == 'Y') {
			echo '<img src="	/local/templates/atika/img/hit-sale_eng.svg">';
		}
	?>


</div>


<a href="<?=$item['DETAIL_PAGE_URL']?>" class="item-catalog__image">
	<img src="<?=$item['PREVIEW_PICTURE']['SRC']?>" alt="alt">
</a>

</div>



<a href="<?=$item['DETAIL_PAGE_URL']?>" class="item-catalog__title"><?=$productTitle?></a>
<div class="item-catalog__descr"><?=(!empty($item['PROPERTIES']['PREW_EN']['VALUE']) ? $item['PROPERTIES']['PREW_EN']['~VALUE']['TEXT'] : '')?></div>
<div class="item-catalog__bottom">
	<div class="price-main">
	<? if(!empty($item['PROPERTIES']['PRICE_UE']['VALUE'])){ ?>
		<?=$productPrice?>/<?=$item['ITEM_MEASURE']['TITLE']?>
	<? } ?>
	</div>
	<? if(!empty($item['PROPERTIES']['OZON_LINK']['VALUE'])) {?>
		<a href="<?=$item['PROPERTIES']['OZON_LINK']['VALUE']?>" class="btn-main btn-short">Order on OZON</a>
	<? } ?>
	<a href="<?=$item['DETAIL_PAGE_URL']?>" class="btn-main">More detailed</a>
</div>

<?php /* } */ ?>





<?/* ['DETAIL_PAGE_URL'] везде где ['PROPERTIES']['OZON_LINK']['VALUE']  */?>