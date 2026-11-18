<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/** Попап «Заказать товар» на карточке (редизайн 2026). Товар с вариантом подставляет sk.js в PROPERTY[274]. */
if (!empty($arResult['MESSAGE'])) {
	LocalRedirect('/include/spasibo.php');
}
$err = !empty($arResult['ERRORS']);
$val = function ($code) use ($err) {
	return $err && isset($_POST['PROPERTY'][$code][0]) ? htmlspecialcharsbx($_POST['PROPERTY'][$code][0]) : '';
};
?>
<form class="sk-form sk-js-bxform" name="iblock_add" action="<?= POST_FORM_ACTION_URI ?>" method="post" enctype="multipart/form-data" data-goal="zakazat_tovar">
	<?= bitrix_sessid_post() ?>
	<input type="hidden" name="iblock_submit" value="Заказать">
	<input type="hidden" name="PROPERTY[274][0]" class="sk-js-order-product" value="<?= $val('274') ?>">
	<p class="sk-form__title">Заказать товар</p>
	<p class="sk-form__descr sk-js-order-product-text"></p>
	<?php if ($err): ?>
	<p class="sk-form__error" role="alert"><?= implode('<br>', $arResult['ERRORS']) ?></p>
	<script>document.addEventListener('DOMContentLoaded', function () { if (window.jQuery && jQuery.fancybox) jQuery.fancybox.open({ src: '#sk-order-product', type: 'inline' }); });</script>
	<?php endif; ?>
	<div class="sk-form__fields">
		<div class="sk-form__item">
			<label class="sk-form__label" for="sk-op-name">Имя</label>
			<input class="sk-form__input" id="sk-op-name" type="text" name="PROPERTY[NAME][0]" value="<?= $val('NAME') ?>" placeholder="Ваше имя" autocomplete="name" maxlength="100" required>
		</div>
		<div class="sk-form__item">
			<label class="sk-form__label" for="sk-op-phone">Телефон</label>
			<input class="sk-form__input sk-js-phone" id="sk-op-phone" type="tel" inputmode="numeric" name="PROPERTY[273][0]" value="<?= $val('273') ?>" placeholder="+7 (___) ___-__-__" autocomplete="tel" required>
		</div>
	</div>
	<div class="sk-form__bottom">
		<label class="sk-check">
			<input class="sk-check__input" type="checkbox" name="agree" checked required>
			<span class="sk-check__box" aria-hidden="true"></span>
			<span class="sk-check__text">Я согласен(а) с <a href="/politika-konfidentsialnosti/" target="_blank">политикой конфиденциальности</a> и на <a href="/soglashenie-na-obrabotku-personalnykh-dannykh/" target="_blank">обработку персональных данных</a>. Оператор: ООО «СТРОЙ КРОВЛЯ»<?= SK_INN ? ', ИНН ' . SK_INN : '' ?></span>
		</label>
		<button class="sk-btn sk-btn--block" type="submit">Отправить заявку</button>
	</div>
</form>
<script src="/bitrix/templates/shop/js/jquery.validateform.js?v=20261022a"></script>
