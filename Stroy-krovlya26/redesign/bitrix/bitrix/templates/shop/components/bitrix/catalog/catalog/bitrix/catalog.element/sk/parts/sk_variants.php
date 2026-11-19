<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/** Редизайн 2026: выбор покрытия, толщины и цвета с ценой + «Заказать». Логика — sk.js (.sk-js-variants). */
$skMeasure = $arResult['PROPERTIES']['MEASURE']['VALUE'] ? (string)$arResult['PROPERTIES']['MEASURE']['~VALUE'] : '';
$skOffers = $arResult['SK_OFFERS'];
$skBase = (float)preg_replace('/[^0-9.]/', '', str_replace(',', '.', (string)$arResult['PROPERTIES']['STARTSHOP_PRICE_1']['VALUE']));
if (!$skBase && !empty($arResult['STARTSHOP']['PRICES']['MINIMAL']['VALUE'])) $skBase = (float)$arResult['STARTSHOP']['PRICES']['MINIMAL']['VALUE'];
$skOld = (float)preg_replace('/[^0-9.]/', '', str_replace(',', '.', (string)$arResult['PROPERTIES']['OLD_PRICE']['VALUE']));
$skUniq = function ($key) use ($skOffers) {
	$v = array();
	foreach ($skOffers as $o) if ($o[$key] !== '' && !in_array($o[$key], $v, true)) $v[] = $o[$key];
	return $v;
};
$skCoats = $skUniq('coat');
$skThicks = $skUniq('thick');
usort($skThicks, function ($a, $b) { return (float)str_replace(',', '.', $a) <=> (float)str_replace(',', '.', $b); }); // 0.4 < 0.45 < 0.5 (natsort ставил 0.45 после 0.5)
$skData = array(
	'name' => $arResult['NAME'],
	'measure' => $skMeasure,
	'base' => $skBase,
	'old' => $skOld,
	'offers' => $skOffers,
	'colors' => $arResult['SK_COLORS'],
);
?>
<div class="sk-scope sk-variants sk-js-variants" data-sk='<?= htmlspecialcharsbx(json_encode($skData, JSON_UNESCAPED_UNICODE)) ?>'>
	<?php if ($skCoats): ?>
	<div class="sk-variants__group" data-group="coat">
		<p class="sk-variants__label">Покрытие: <b class="sk-js-v-value"></b></p>
		<div class="sk-variants__opts">
			<?php foreach ($skCoats as $v): ?><button class="sk-variants__opt" type="button" data-value="<?= htmlspecialcharsbx($v) ?>"><?= htmlspecialcharsbx($v) ?></button><?php endforeach; ?>
		</div>
	</div>
	<?php endif; ?>
	<?php if ($skThicks): ?>
	<div class="sk-variants__group" data-group="thick">
		<p class="sk-variants__label">Толщина: <b class="sk-js-v-value"></b></p>
		<div class="sk-variants__opts">
			<?php foreach ($skThicks as $v): ?><button class="sk-variants__opt" type="button" data-value="<?= htmlspecialcharsbx($v) ?>"><?= htmlspecialcharsbx($v) ?></button><?php endforeach; ?>
		</div>
	</div>
	<?php endif; ?>
	<?php if ($arResult['SK_COLORS']): ?>
	<div class="sk-variants__group" data-group="color">
		<p class="sk-variants__label">Цвет: <b class="sk-js-v-value"></b></p>
		<div class="sk-variants__opts sk-variants__opts--colors">
			<?php foreach ($arResult['SK_COLORS'] as $code => $c): ?><button class="sk-variants__color" type="button" data-value="<?= htmlspecialcharsbx($code) ?>" title="<?= htmlspecialcharsbx($c['name']) ?>" aria-label="<?= htmlspecialcharsbx($c['name']) ?>"><?php if ($c['img']): ?><img src="<?= htmlspecialcharsbx($c['img']) ?>" alt="" loading="lazy"><?php endif; ?></button><?php endforeach; ?>
		</div>
	</div>
	<?php endif; ?>

	<div class="sk-variants__buy">
		<div class="sk-variants__prices">
			<span class="sk-variants__price sk-js-v-price"></span>
			<span class="sk-variants__old sk-js-v-old"></span>
		</div>
		<a class="sk-btn sk-variants__order sk-js-v-order" href="javascript:;" data-fancybox data-src="#sk-order-product">Заказать</a>
		<a class="sk-btn sk-btn--outline sk-variants__calc" href="javascript:;" data-fancybox data-type="ajax" data-src="/include/forms.php">Получить расчёт</a>
	</div>
	<p class="sk-variants__note sk-js-v-note" hidden>Этого сочетания нет — выберите другое покрытие или толщину.</p>
</div>
