<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/** Новая шапка (редизайн). Подключается из aspro_mshop/header.php только при lam_preview(). */
global $APPLICATION, $TEMPLATE_OPTIONS;

$lamDir = '/local/templates/.default/lam';
$lamImg = $lamDir . '/img/';
$lamCfg = include __DIR__ . '/config.php';

$APPLICATION->SetAdditionalCSS($lamDir . '/css/lam.css?ver=3', true);
$APPLICATION->SetAdditionalCSS($lamDir . '/css/lam-bx.css?ver=1', true);
$APPLICATION->AddHeadString('<link rel="preload" href="' . $lamDir . '/fonts/onest-cyrillic.woff2" as="font" type="font/woff2" crossorigin>');
$APPLICATION->AddHeadString('<link rel="preload" href="' . $lamDir . '/fonts/inter-cyrillic.woff2" as="font" type="font/woff2" crossorigin>');

// Ссылка «Горячее предложение» — из той же включаемой области, что и в старой шапке.
$lamHotHref = '/catalog/';
$lamHotFile = $_SERVER['DOCUMENT_ROOT'] . SITE_DIR . 'include/fv-sale-btn.php';
if (is_file($lamHotFile) && preg_match('/href="([^"]+)"/', (string)file_get_contents($lamHotFile), $lamM)) {
	$lamHotHref = $lamM[1];
}

$MESSENGER_MAX_URL = '';
$lamMsgFile = $_SERVER['DOCUMENT_ROOT'] . SITE_DIR . 'include/messengers-links.php';
if (is_file($lamMsgFile)) {
	include $lamMsgFile;
}
$lamPhoneHref = 'tel:+' . preg_replace('/\D/', '', $lamCfg['PHONE']);
?>
<!-- LAM HEADER START -->
<header class="lam-header">
	<div class="lam-container lam-header__in">
		<div class="lam-header__row lam-header__row--top">
			<?php if ($APPLICATION->GetCurPage(false) === SITE_DIR):?>
				<span class="lam-header__logo"><img src="<?=$lamImg?>logo.svg" alt="Ламинариум — ламинат по реальным ценам" width="219" height="48"></span>
			<?php else:?>
				<a class="lam-header__logo" href="<?=SITE_DIR?>"><img src="<?=$lamImg?>logo.svg" alt="Ламинариум — ламинат по реальным ценам" width="219" height="48"></a>
			<?php endif;?>

			<div class="lam-header__center">
				<?php $APPLICATION->IncludeComponent("bitrix:search.title", "lam", array(
					"NUM_CATEGORIES" => "1",
					"TOP_COUNT" => "5",
					"ORDER" => "date",
					"USE_LANGUAGE_GUESS" => "Y",
					"CHECK_DATES" => "Y",
					"SHOW_OTHERS" => "N",
					"PAGE" => SITE_DIR . "catalog/",
					"CATEGORY_0_TITLE" => "Товары",
					"CATEGORY_0" => array(0 => "iblock_aspro_mshop_catalog"),
					"CATEGORY_0_iblock_aspro_mshop_catalog" => array(0 => "17"),
					"SHOW_INPUT" => "Y",
					"INPUT_ID" => "lam-header-q",
					"CONTAINER_ID" => "lam-header-search",
					"PRICE_CODE" => array(0 => "BASE"),
					"PRICE_VAT_INCLUDE" => "Y",
					"SHOW_ANOUNCE" => "N",
					"PREVIEW_TRUNCATE_LEN" => "50",
					"SHOW_PREVIEW" => "Y",
					"PREVIEW_WIDTH" => "38",
					"PREVIEW_HEIGHT" => "38",
					"CONVERT_CURRENCY" => "N",
				), false, array("HIDE_ICONS" => "Y"));?>
				<a class="lam-header__hot" href="<?=htmlspecialcharsbx($lamHotHref)?>" target="_blank" rel="noopener"><img src="<?=$lamImg?>ic-fire.svg" alt="" width="24" height="24">Горячее предложение</a>
			</div>

			<div class="lam-header__contacts">
				<div class="lam-header__messengers">
					<?php if ($MESSENGER_MAX_URL !== ''):?>
						<a class="lam-header__messenger" href="<?=htmlspecialcharsbx($MESSENGER_MAX_URL)?>" target="_blank" rel="noopener" aria-label="Написать в MAX"><img src="<?=$lamImg?>msg-1.svg" alt="" width="32" height="32"></a>
					<?php endif;?>
					<a class="lam-header__messenger" href="<?=$lamCfg['TG_URL']?>" target="_blank" rel="noopener" aria-label="Написать в Telegram"><img src="<?=$lamImg?>msg-2.svg" alt="" width="32" height="32"></a>
				</div>
				<a class="lam-header__phone" href="<?=$lamPhoneHref?>"><span class="lam-header__phone-text"><?=$lamCfg['PHONE']?></span><img src="<?=$lamImg?>ic-phone.svg" alt="" width="36" height="36"></a>
			</div>

			<button class="lam-header__burger" type="button" aria-label="Открыть меню" aria-expanded="false" aria-controls="lam-header-nav">
				<span></span><span></span><span></span>
			</button>
		</div>

		<div class="lam-header__row lam-header__row--bottom">
			<a class="lam-header__catalog" href="<?=SITE_DIR?>catalog/"><img src="<?=$lamImg?>ic-burger.svg" alt="" width="20" height="14">Каталог</a>

			<nav class="lam-header__nav" id="lam-header-nav" aria-label="Основное меню">
				<?php $APPLICATION->IncludeComponent("bitrix:menu", "lam_top", array(
					"ROOT_MENU_TYPE" => $TEMPLATE_OPTIONS["HEAD"]["CURRENT_MENU"],
					"MENU_CACHE_TYPE" => "Y",
					"MENU_CACHE_TIME" => "86400",
					"MENU_CACHE_USE_GROUPS" => "N",
					"MENU_CACHE_GET_VARS" => array(),
					"MAX_LEVEL" => "1",
					"CHILD_MENU_TYPE" => "left",
					"USE_EXT" => "N",
					"DELAY" => "N",
					"ALLOW_MULTI_SELECT" => "N",
				), false, array("HIDE_ICONS" => "Y"));?>
			</nav>

			<?php $APPLICATION->IncludeComponent("bitrix:sale.basket.basket.line", "lam", array(
				"HIDE_ON_BASKET_PAGES" => "N",
				"PATH_TO_BASKET" => SITE_DIR . "basket/",
				"PATH_TO_ORDER" => SITE_DIR . "order/",
				"PATH_TO_PERSONAL" => SITE_DIR . "personal/",
				"PATH_TO_PROFILE" => SITE_DIR . "personal/",
				"PATH_TO_REGISTER" => SITE_DIR . "login/",
				"PATH_TO_AUTHORIZE" => SITE_DIR . "login/",
				"POSITION_FIXED" => "N",
				"SHOW_AUTHOR" => "N",
				"SHOW_EMPTY_VALUES" => "Y",
				"SHOW_NUM_PRODUCTS" => "Y",
				"SHOW_PERSONAL_LINK" => "N",
				"SHOW_REGISTRATION" => "N",
				"SHOW_TOTAL_PRICE" => "N",
				"SHOW_PRODUCTS" => "Y",
				"SHOW_DELAY" => "Y",
			), false, array("HIDE_ICONS" => "Y"));?>
		</div>
	</div>
</header>
<!-- LAM HEADER END -->
