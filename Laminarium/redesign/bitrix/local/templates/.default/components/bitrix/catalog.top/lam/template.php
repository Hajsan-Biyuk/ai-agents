<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/** Карточки товаров (LAM CARD) для «Популярных предложений». Пусто — ничего не выводим. */
$this->setFrameMode(true);
if (empty($arResult['ITEMS'])) return;

$lamImg = '/local/templates/.default/lam/img/';
$lamBadges = array('STOCK' => array('badge-sale.svg', 'Акция'), 'HIT' => array('badge-hit.svg', 'Хит продаж'));

if (!function_exists('lam_price_format')) {
	function lam_price_format($value)
	{
		$value = (float)$value;
		return number_format($value, (floor($value) == $value ? 0 : 2), ',', ' ') . ' ₽';
	}
}
if (!function_exists('lam_measure_symbol')) {
	function lam_measure_symbol($measureId)
	{
		static $cache = array();
		$measureId = (int)$measureId;
		if ($measureId <= 0 || !class_exists('CCatalogMeasure')) return '';
		if (!isset($cache[$measureId])) {
			$m = CCatalogMeasure::getList(array(), array('ID' => $measureId), false, false, array())->GetNext();
			$cache[$measureId] = $m ? (string)$m['SYMBOL_RUS'] : '';
		}
		return $cache[$measureId];
	}
}

foreach ($arResult['ITEMS'] as $arItem):
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_EDIT'));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => 'Удалить товар?'));

	$name = (!empty($arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'])) ? $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] : $arItem['NAME'];
	$url = $arItem['DETAIL_PAGE_URL'];

	// Картинка: анонс, иначе детальная, иначе заглушка Аспро.
	$pic = !empty($arItem['PREVIEW_PICTURE']) ? $arItem['PREVIEW_PICTURE'] : (!empty($arItem['DETAIL_PICTURE']) ? $arItem['DETAIL_PICTURE'] : null);
	if ($pic) {
		$picResized = CFile::ResizeImageGet($pic, array('width' => 400, 'height' => 400), BX_RESIZE_IMAGE_PROPORTIONAL, true);
		$picSrc = $picResized['src'];
	} else {
		$picSrc = '/bitrix/templates/aspro_mshop/images/no_photo_medium.png';
	}

	// Цена: MIN_PRICE (режим совместимости), иначе минимальная из PRICES / из торговых предложений.
	$price = !empty($arItem['MIN_PRICE']) ? $arItem['MIN_PRICE'] : null;
	if (!$price && !empty($arItem['PRICES'])) {
		foreach ($arItem['PRICES'] as $p) {
			if (!empty($p['CAN_ACCESS']) && (!$price || $p['DISCOUNT_VALUE'] < $price['DISCOUNT_VALUE'])) $price = $p;
		}
	}
	if (!$price && !empty($arItem['OFFERS'])) {
		foreach ($arItem['OFFERS'] as $offer) {
			if (!empty($offer['MIN_PRICE']) && (!$price || $offer['MIN_PRICE']['DISCOUNT_VALUE'] < $price['DISCOUNT_VALUE'])) $price = $offer['MIN_PRICE'];
		}
	}
	$measure = '';
	if (!empty($arItem['OFFERS']) && !empty($price['CATALOG_MEASURE_NAME'])) {
		$measure = $price['CATALOG_MEASURE_NAME'];
	} elseif ($arParams['SHOW_MEASURE'] === 'Y' && !empty($arItem['CATALOG_MEASURE'])) {
		$measure = lam_measure_symbol($arItem['CATALOG_MEASURE']);
	}
	$measure = $measure !== '' ? '/' . $measure : '';

	// Наличие — тот же текст, что у Аспро («Много», «Достаточно», «Нет в наличии»…).
	$stockText = '';
	$inStock = false;
	if (class_exists('CMShop')) {
		$q = CMShop::GetQuantityArray(CMShop::GetTotalCount($arItem));
		$stockText = trim(strip_tags((string)$q['HTML']));
		$inStock = strpos((string)$q['HTML'], 'icon stock') !== false;
	}

	$hitCodes = !empty($arItem['PROPERTIES']['HIT']['VALUE_XML_ID']) ? (array)$arItem['PROPERTIES']['HIT']['VALUE_XML_ID'] : array();
	$canWish = $arItem['CAN_BUY'] && empty($arItem['OFFERS']) && $arParams['DISPLAY_WISH_BUTTONS'] !== 'N';
	$canCompare = ($arParams['DISPLAY_COMPARE'] === true || $arParams['DISPLAY_COMPARE'] === 'Y'); // catalog.top приводит параметр к bool
?>
<!-- LAM CARD -->
<li class="lam-track__item" id="<?=$this->GetEditAreaId($arItem['ID'])?>">
	<article class="lam-card">
		<div class="lam-card__media">
			<a class="lam-card__image" href="<?=$url?>" tabindex="-1" aria-hidden="true"><img src="<?=$picSrc?>" alt="" width="400" height="400" loading="lazy"></a>
			<?php if (array_intersect($hitCodes, array_keys($lamBadges))): ?>
				<div class="lam-card__badges">
					<?php foreach ($lamBadges as $code => $badge): if (!in_array($code, $hitCodes, true)) continue; ?>
						<span class="lam-card__badge"><img src="<?=$lamImg . $badge[0]?>" alt="<?=$badge[1]?>" width="42" height="42"></span>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
			<?php if ($canWish || $canCompare): ?>
				<div class="lam-card__actions">
					<?php if ($canWish): ?>
						<button class="lam-card__action lam-card__action--fav wish_item text" type="button" data-item="<?=$arItem['ID']?>" data-iblock="<?=$arParams['IBLOCK_ID']?>" aria-label="В избранное"><img src="<?=$lamImg?>ic-heart.svg" alt=""></button>
					<?php endif; ?>
					<?php if ($canCompare): ?>
						<button class="lam-card__action lam-card__action--compare compare_item text" type="button" data-item="<?=$arItem['ID']?>" data-iblock="<?=$arParams['IBLOCK_ID']?>" aria-label="Сравнить"><img src="<?=$lamImg?>ic-compare.svg" alt=""></button>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
		<div class="lam-card__body">
			<a class="lam-card__name" href="<?=$url?>"><?=$name?></a>
			<?php if ($stockText !== ''): ?>
				<div class="lam-card__stock"><?php if ($inStock): ?><img src="<?=$lamImg?>ic-check-green.svg" alt="" width="16" height="16"><?php endif; ?><?=$stockText?></div>
			<?php endif; ?>
			<?php if ($price): ?>
				<div class="lam-card__prices">
					<div class="lam-card__price"><?=empty($arItem['OFFERS']) ? '' : 'от '?><?=lam_price_format($price['DISCOUNT_VALUE'])?><?=$measure?></div>
					<?php if ($price['DISCOUNT_VALUE'] < $price['VALUE']): ?>
						<div class="lam-card__old-price"><span class="lam-visually-hidden">Старая цена: </span><?=lam_price_format($price['VALUE'])?><?=$measure?></div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</article>
</li>
<!-- /LAM CARD -->
<?php endforeach; ?>
