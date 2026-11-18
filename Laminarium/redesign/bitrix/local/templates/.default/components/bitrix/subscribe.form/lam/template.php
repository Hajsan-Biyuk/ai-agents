<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/** Подписка: LAM_VIEW = home (блок на главной) | footer (подвал). Отправка — как у Аспро: GET на страницу подписки. */
$this->setFrameMode(true);
$lamCfg = include $_SERVER['DOCUMENT_ROOT'] . '/local/templates/.default/lam/config.php';
$lamFooter = ($arParams['LAM_VIEW'] ?? '') === 'footer';
$lamId = $lamFooter ? 'lam-footer-email' : 'lam-subscribe-email';
$lamRubrics = '';
foreach ((array)$arResult['RUBRICS'] as $arRubric) {
	$lamRubrics .= '<input type="hidden" name="sf_RUB_ID[]" value="' . (int)$arRubric['ID'] . '">';
}
$lamEmail = htmlspecialcharsbx((string)$arResult['EMAIL']);
?>
<?php if ($lamFooter): ?>
<form class="lam-footer__subscribe" action="<?=$arResult['FORM_ACTION']?>" method="get">
	<?=$lamRubrics?>
	<p class="lam-footer__heading">Будьте в курсе новостей</p>
	<p class="lam-footer__note">Подпишитесь на рассылку</p>
	<div class="lam-footer__subscribe-row">
		<label class="lam-visually-hidden" for="<?=$lamId?>">Ваша почта</label>
		<input class="lam-input" id="<?=$lamId?>" type="email" name="sf_EMAIL" value="<?=$lamEmail?>" placeholder="Ваша почта" autocomplete="email" required>
		<button class="lam-btn lam-btn--sm" type="submit" name="OK" value="Y">Подписаться</button>
	</div>
	<label class="lam-consent">
		<input class="lam-consent__input" type="checkbox" name="agree" value="Y" required>
		<span class="lam-consent__box" aria-hidden="true"></span>
		<span class="lam-consent__text">Даю <a href="<?=$lamCfg['CONSENT_URL']?>" target="_blank">согласие на обработку персональных данных</a> <?=$lamCfg['ORG']?> (ИНН <?=$lamCfg['INN']?>) и принимаю <a href="<?=$lamCfg['PRIVACY_URL']?>" target="_blank">политику конфиденциальности</a></span>
	</label>
</form>
<?php else: ?>
<form class="lam-subscribe__form" action="<?=$arResult['FORM_ACTION']?>" method="get">
	<?=$lamRubrics?>
	<div class="lam-subscribe__row">
		<label class="lam-visually-hidden" for="<?=$lamId?>">Ваша почта</label>
		<input class="lam-input" id="<?=$lamId?>" type="email" name="sf_EMAIL" value="<?=$lamEmail?>" placeholder="Ваша почта" autocomplete="email" required>
		<button class="lam-btn" type="submit" name="OK" value="Y">Подписаться</button>
	</div>
	<label class="lam-consent">
		<input class="lam-consent__input" type="checkbox" name="agree" value="Y" required>
		<span class="lam-consent__box" aria-hidden="true"></span>
		<span class="lam-consent__text">Я даю согласие на обработку моих персональных данных <?=$lamCfg['ORG']?> (ИНН <?=$lamCfg['INN']?>) в целях обработки заявки и обратной связи. <a href="<?=$lamCfg['CONSENT_URL']?>" target="_blank">Согласие на обработку</a>, политика конфиденциальности — <a href="<?=$lamCfg['PRIVACY_URL']?>" target="_blank">по ссылке</a></span>
	</label>
</form>
<?php endif; ?>
