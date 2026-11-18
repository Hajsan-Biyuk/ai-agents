<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/** Карточки товаров главной (редизайн 2026). Цены — как в шаблоне sale (StartShop). */
use Bas\Pict;
if (empty($arResult['ITEMS'])) { ?><p class="sk-goods__empty">Скоро здесь появятся товары.</p><?php return; }
$brands = array();
?>
<ul class="sk-goods">
<?php foreach ($arResult['ITEMS'] as $el):
	$this->AddEditAction($el['ID'], $el['EDIT_LINK'], CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_EDIT'));
	$p = $el['PROPERTIES'];
	$measure = $p['MEASURE']['VALUE'] ? ' руб./' . $p['MEASURE']['~VALUE'] : ' руб.';
	if (empty($el['STARTSHOP']['OFFERS'])) {
		$v = trim((string)$el['STARTSHOP']['PRICES']['MINIMAL']['PRINT_VALUE']);
		$price = $v !== '' ? $v . $measure : '';
	} else {
		$min = CStartShopToolsIBlock::GetOffersMinPrice($el);
		$price = !empty($min) ? 'от ' . $min['PRINT_VALUE'] . $measure : '';
	}
	$brandId = (int)$p['BREND']['VALUE'];
	if ($brandId && !isset($brands[$brandId])) {
		$b = CIBlockElement::GetByID($brandId)->GetNext();
		$brands[$brandId] = $b ? $b['NAME'] : '';
	}
	$img = $el['PREVIEW_PICTURE'] ? Pict::getResizeWebpSrc($el['PREVIEW_PICTURE'], 500, 500) : '';
	$inStock = $p['NALICHIE']['VALUE'] && $p['NALICHIE']['VALUE_ENUM_ID'] == '30';
?>
	<li class="sk-good" id="<?= $this->GetEditAreaId($el['ID']) ?>">
		<a class="sk-good__img" href="<?= $el['DETAIL_PAGE_URL'] ?>" tabindex="-1" aria-hidden="true"><?php if ($img): ?><img src="<?= $img ?>" alt="" width="500" height="500" loading="lazy"><?php endif; ?></a>
		<div class="sk-good__body">
			<?php if ($brandId && $brands[$brandId]): ?><span class="sk-good__brand"><?= $brands[$brandId] ?></span><?php endif; ?>
			<a class="sk-good__name" href="<?= $el['DETAIL_PAGE_URL'] ?>"><?= $el['NAME'] ?></a>
		</div>
		<div class="sk-good__foot">
			<?php if ($p['NALICHIE']['VALUE']): ?><span class="sk-good__stock<?= $inStock ? '' : ' sk-good__stock--no' ?>"><?= $p['NALICHIE']['VALUE'] ?></span><?php endif; ?>
			<span class="sk-good__price"><?= $price !== '' ? $price : 'Цена по запросу' ?></span>
		</div>
	</li>
<?php endforeach; ?>
</ul>
