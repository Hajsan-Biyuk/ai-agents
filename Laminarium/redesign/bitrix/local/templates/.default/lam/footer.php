<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/** Новый подвал (редизайн). Подключается из aspro_mshop/footer.php только при lam_preview(). */
global $APPLICATION;

$lamDir = '/local/templates/.default/lam';
$lamImg = $lamDir . '/img/';
$lamCfg = include __DIR__ . '/config.php';

$MESSENGER_MAX_URL = '';
$lamMsgFile = $_SERVER['DOCUMENT_ROOT'] . SITE_DIR . 'include/messengers-links.php';
if (is_file($lamMsgFile)) {
	include $lamMsgFile;
}
$lamFooterMenu = array(
	"MENU_CACHE_TYPE" => "Y",
	"MENU_CACHE_TIME" => "86400",
	"MENU_CACHE_USE_GROUPS" => "N",
	"MENU_CACHE_GET_VARS" => array(),
	"MAX_LEVEL" => "1",
	"USE_EXT" => "N",
	"DELAY" => "N",
	"ALLOW_MULTI_SELECT" => "N",
);
?>
<!-- LAM FOOTER START -->
<footer class="lam-footer">
	<div class="lam-container lam-footer__in">
		<div class="lam-footer__aside">
			<div class="lam-footer__brand">
				<a class="lam-footer__logo" href="<?=SITE_DIR?>"><img src="<?=$lamImg?>logo.svg" alt="Ламинариум — ламинат по реальным ценам" width="338" height="74" loading="lazy"></a>
				<p class="lam-footer__requisites"><?=$lamCfg['ORG']?><br>ИНН <?=$lamCfg['INN']?><br><?=$lamCfg['OGRN']?><br>E-mail: <a href="mailto:<?=$lamCfg['EMAIL']?>"><?=$lamCfg['EMAIL']?></a></p>
			</div>

			<?php $APPLICATION->IncludeComponent("bitrix:subscribe.form", "lam", array(
				"LAM_VIEW" => "footer",
				"AJAX_MODE" => "N",
				"SHOW_HIDDEN" => "N",
				"ALLOW_ANONYMOUS" => "Y",
				"SHOW_AUTH_LINKS" => "N",
				"CACHE_TYPE" => "A",
				"CACHE_TIME" => "86400",
				"CACHE_NOTES" => "",
				"SET_TITLE" => "N",
				"LK" => "Y",
				"USE_PERSONALIZATION" => "Y",
				"PAGE" => SITE_DIR . "personal/subscribe/",
			), false, array("HIDE_ICONS" => "Y")); ?>

			<div class="lam-footer__messengers">
				<p class="lam-footer__heading">Наши мессенджеры</p>
				<div class="lam-footer__messengers-list">
					<?php if ($MESSENGER_MAX_URL !== ''): ?>
						<a href="<?=htmlspecialcharsbx($MESSENGER_MAX_URL)?>" target="_blank" rel="noopener" aria-label="Написать в MAX"><img src="<?=$lamImg?>msg-1.svg" alt="" width="36" height="36" loading="lazy"></a>
					<?php endif; ?>
					<a href="<?=$lamCfg['TG_URL']?>" target="_blank" rel="noopener" aria-label="Написать в Telegram"><img src="<?=$lamImg?>msg-2.svg" alt="" width="36" height="36" loading="lazy"></a>
				</div>
			</div>
		</div>

		<div class="lam-footer__cols">
			<nav class="lam-footer__col lam-footer__col--company" aria-label="Компания">
				<p class="lam-footer__title">Компания</p>
				<?php $APPLICATION->IncludeComponent("bitrix:menu", "lam_footer", array("ROOT_MENU_TYPE" => "bottom_company") + $lamFooterMenu, false, array("HIDE_ICONS" => "Y")); ?>
			</nav>
			<nav class="lam-footer__col" aria-label="Информация">
				<p class="lam-footer__title">Информация</p>
				<?php $APPLICATION->IncludeComponent("bitrix:menu", "lam_footer", array("ROOT_MENU_TYPE" => "bottom_info") + $lamFooterMenu, false, array("HIDE_ICONS" => "Y")); ?>
			</nav>
			<nav class="lam-footer__col" aria-label="Каталог">
				<p class="lam-footer__title"><a href="<?=SITE_DIR?>catalog/">Каталог</a></p>
				<?php $APPLICATION->IncludeComponent("bitrix:menu", "lam_footer", array("ROOT_MENU_TYPE" => "bottom_help") + $lamFooterMenu, false, array("HIDE_ICONS" => "Y")); ?>
			</nav>
			<div class="lam-footer__col lam-footer__col--contacts">
				<p class="lam-footer__title"><a href="<?=SITE_DIR?>contacts/">Контакты</a></p>
				<?php $APPLICATION->IncludeFile($lamDir . '/inc_footer_contacts.php', array(), array('MODE' => 'html', 'NAME' => 'контакты и оплата в подвале')); ?>
			</div>
		</div>
	</div>

	<div class="lam-footer__bottom">
		<div class="lam-container lam-footer__bottom-in">
			<ul class="lam-footer__legal">
				<li><a href="<?=$lamCfg['PRIVACY_URL']?>">Политика конфиденциальности</a></li>
				<li><a href="<?=$lamCfg['CONSENT_URL']?>">Согласие на обработку моих персональных данных</a></li>
				<li class="lam-footer__copy">© Copyright <?=date('Y')?> г.</li>
			</ul>
			<a class="lam-footer__dev" href="https://sheer.ru/" target="_blank" rel="noopener">Сделано в <img src="<?=$lamImg?>sheer.svg" alt="Sheer" width="57" height="13" loading="lazy"></a>
		</div>
	</div>
</footer>
<!-- LAM FOOTER END -->
<script src="<?=$lamDir?>/js/lam.js?ver=4"></script>
