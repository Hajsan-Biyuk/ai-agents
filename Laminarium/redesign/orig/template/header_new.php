<?
global $APPLICATION;
$APPLICATION->SetAdditionalCss(SITE_TEMPLATE_PATH . "/css/header_new.css");
?>
<div class="top-h-row">
	<div class="wrapper_inner">
		<div class="top_header">
			<div class="logo_wraper">
				<div class="logo_b">
					<a href="/"><img src="<?=SITE_TEMPLATE_PATH?>/css/svg/logo.svg"></a>
				</div>
			</div>
			<div class="top_menu">
				<?$APPLICATION->IncludeComponent("bitrix:menu", "top_content_row", array(
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
					),false
				);?>
			</div>
			<?
			$messengersLinksFile = $_SERVER['DOCUMENT_ROOT'] . SITE_DIR . 'include/messengers-links.php';
			if (is_file($messengersLinksFile)) {
				include $messengersLinksFile;
			}
			if (!isset($MESSENGER_MAX_URL)) {
				$MESSENGER_MAX_URL = '';
			}
			?>
			<?if ($MESSENGER_MAX_URL !== ''):?>
				<div class="top-max-link-wrap">
					<a href="<?=htmlspecialcharsbx($MESSENGER_MAX_URL)?>" target="_blank" rel="noopener noreferrer nofollow" aria-label="MAX" class="top-max-link">
						<img src="/upload/social_icons/max.svg" alt="MAX" class="top-max-icon">
					</a><a href="https://t.me/laminarium_krd" target="_blank" rel="noopener noreferrer nofollow" aria-label="MAX" class="top-max-link">
						<img src="/upload/social_icons/telegram_macos_bigsur_icon_189662 (1).png" alt="MAX" class="top-max-icon">
					</a>
				</div>
			<?endif;?>
			<div class="phones">
				<span class="phone_wrap">
					<span class="phone_text">
						<a href="tel:+79180227999">+7 (918) 022-79-99</a>
					</span>
				</span>
				<span class="order_wrap_btn">
					<?if( \Bitrix\Main\Config\Option::get("aspro.mshop", "SHOW_CALLBACK", "Y") != "N"):?>
						<span class="callback_btn"><img src="<?=SITE_TEMPLATE_PATH?>/css/svg/call_back.svg"></span>
					<?endif;?>
				</span>
			</div>
		</div>
	</div>
</div>
<header id="header">
	<div class="wrapper_inner">
		<div class="bottom_header">
			<a href="#" class="catalog_btn js-catalog-btn"><img src="<?=SITE_TEMPLATE_PATH?>/css/svg/catalog_btn.svg"></a>
			<div class="search"><?include($_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include/search.title.catalog.php');?></div>
			<div class="fv-sale-btn-wrap"><?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/fv-sale-btn-img.php"], false, ['HIDE_ICONS' => 'N']);?></div>
			<div class="h-user-block">
				<?$APPLICATION->IncludeComponent(
					"bitrix:sale.basket.basket.line",
					"header_links",
					Array(
						"HIDE_ON_BASKET_PAGES" => "N",
						"PATH_TO_AUTHORIZE" => "",
						"PATH_TO_BASKET" => SITE_DIR."personal/cart/",
						"PATH_TO_ORDER" => SITE_DIR."personal/order/make/",
						"PATH_TO_PERSONAL" => SITE_DIR."personal/",
						"PATH_TO_PROFILE" => SITE_DIR."personal/",
						"PATH_TO_REGISTER" => SITE_DIR."login/",
						"POSITION_FIXED" => "N",
						"SHOW_AUTHOR" => "N",
						"SHOW_EMPTY_VALUES" => "Y",
						"SHOW_NUM_PRODUCTS" => "N",
						"SHOW_PERSONAL_LINK" => "N",
						"SHOW_REGISTRATION" => "N",
						"SHOW_TOTAL_PRICE" => "N",
						"SHOW_PRODUCTS" => "N",
					),
					false,
					array('HIDE_ICONS' => 'Y')
				);?>
			</div>
		</div>
		<div class="catalog_menu_fly">
			<?include($_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include/_menu.top_catalog_multilevel.php');?>
		</div>
	</div>
</header>
