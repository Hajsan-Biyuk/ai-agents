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


	$preview_picture = CFile::ResizeImageGet($item['PREVIEW_PICTURE']['ID'], ['width'=>180, 'height'=>222], BX_RESIZE_IMAGE_PROPORTIONAL);
	if(empty($preview_picture['src'])) $item['PREVIEW_PICTURE']['SRC'] = '/local/templates/aspro_max/images/svg/noimage_product.svg';
	else $item['PREVIEW_PICTURE']['SRC'] = $preview_picture['src'];

	require_once $_SERVER['DOCUMENT_ROOT'].'/local/templates/aspro_max/atrium_sale.php';
	$atrSale = empty($item['ATRIUM_NOT_FOR_SALE']) ? atriumSaleInfo($item['PROPERTIES']['STARAYA_CENA']['VALUE'], $price['PRICE'], $price['BASE_PRICE']) : null;
?>
<div class="item-sale-product-img">
	<img src="<?=$item['PREVIEW_PICTURE']['SRC']?>">
	<? if($atrSale){ ?>
	<div class="item-sale-product-percent">- <?=$atrSale['PERCENT']?>%</div>
	<? } ?>
</div>
<div class="item-sale-product-title">
	<?if(!empty($item['ATRIUM_NOT_FOR_SALE'])):?>
		<div class="item-sale-product-price atrium-not-for-sale">Нет в продаже</div>
	<?else:?>
	<div class="item-sale-product-price"><?=\CCurrencyLang::CurrencyFormat($price['PRICE'], 'RUB', false)?> ₽/<?=$item['ITEM_MEASURE']['TITLE']?></div>
	<? if($atrSale){ ?>
		<div class="item-sale-product-price-old"><?=\CCurrencyLang::CurrencyFormat($atrSale['OLD'], 'RUB', false)?> ₽/<?=$item['ITEM_MEASURE']['TITLE']?></div>
	<? } ?>
	<?endif;?>
</div>