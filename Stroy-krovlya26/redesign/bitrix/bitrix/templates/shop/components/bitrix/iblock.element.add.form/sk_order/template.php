<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/** Форма «Быстрый расчёт» на новой главной (редизайн 2026). Логика как в order-form: успех → «Спасибо». */
if (!empty($arResult['MESSAGE'])) {
	LocalRedirect('/include/spasibo.php');
}
$val = function ($code) use ($arResult) {
	return isset($_POST['PROPERTY'][$code][0]) && !empty($arResult['ERRORS']) ? htmlspecialcharsbx($_POST['PROPERTY'][$code][0]) : '';
};
?>
<form class="sk-form sk-js-bxform" name="iblock_add" action="<?= POST_FORM_ACTION_URI ?>" method="post" enctype="multipart/form-data" data-goal="poluchit_bistrii_raschet">
	<?= bitrix_sessid_post() ?>
	<input type="hidden" name="PROPERTY[270][0]" value="<?= htmlspecialcharsbx($_SERVER['HTTP_HOST'] . $GLOBALS['APPLICATION']->GetCurPage()) ?>">
	<input type="hidden" name="iblock_submit" value="Получить расчет">
	<p class="sk-form__title">Быстрый расчёт стоимости кровли</p>
	<?php if (!empty($arResult['ERRORS'])): ?>
	<p class="sk-form__error" role="alert"><?= implode('<br>', $arResult['ERRORS']) ?></p>
	<?php endif; ?>
	<div class="sk-form__fields">
		<div class="sk-form__item">
			<label class="sk-form__label" for="sk-calc-name">Имя</label>
			<input class="sk-form__input" id="sk-calc-name" type="text" name="PROPERTY[NAME][0]" value="<?= $val('NAME') ?>" placeholder="Ваше имя" autocomplete="name" maxlength="100" required>
		</div>
		<div class="sk-form__item">
			<label class="sk-form__label" for="sk-calc-area">Площадь кровли, м²</label>
			<input class="sk-form__input" id="sk-calc-area" type="number" name="PROPERTY[45][0]" value="<?= $val('45') ?>" placeholder="Например, 180" min="10" max="10000" inputmode="numeric">
		</div>
		<div class="sk-form__item">
			<label class="sk-form__label" for="sk-calc-phone">Телефон</label>
			<input class="sk-form__input sk-js-phone" id="sk-calc-phone" type="tel" inputmode="numeric" name="PROPERTY[47][0]" value="<?= $val('47') ?>" placeholder="+7 (___) ___-__-__" autocomplete="tel" required>
		</div>
	</div>
	<div class="sk-form__bottom">
		<label class="sk-check">
			<input class="sk-check__input" type="checkbox" name="agree" checked required>
			<span class="sk-check__box" aria-hidden="true"></span>
			<span class="sk-check__text">Я согласен(а) с <a href="/politika-konfidentsialnosti/" target="_blank">политикой конфиденциальности</a> и на <a href="/soglashenie-na-obrabotku-personalnykh-dannykh/" target="_blank">обработку персональных данных</a>. Оператор: ООО «СТРОЙ КРОВЛЯ»<?= SK_INN ? ', ИНН ' . SK_INN : '' ?></span>
		</label>
		<button class="sk-btn sk-btn--block" type="submit">Получить расчёт</button>
	</div>
</form>
