<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?
	use Bitrix\Main\Page\Asset;
	
	$curPage = $APPLICATION->GetCurPage(true);
	
	Asset::getInstance()->addString('<meta charset="UTF-8"/>');
	Asset::getInstance()->addString('<meta name="viewport" content="width=device-width, initial-scale=1.0"/>');
	Asset::getInstance()->addString('<meta name="format-detection" content="telephone=no"/>');
	Asset::getInstance()->addString('<meta http-equiv="X-UA-Compatible" content="IE=edge"/>');
	
	Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . '/libs/bootstrap/bootstrap-grid.css');
	Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . '/libs/fontawesome-free-5.12.0-web/css/all.min.css');
	Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . '/libs/fancybox/jquery.fancybox.css');
	Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . '/libs/slick-1.5.9/slick/slick.css');
	Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . '/libs/slick-1.5.9/slick/slick-theme.css');
	Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . '/libs/ion.rangeSlider-master/css/ion.rangeSlider.min.css');
	Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . '/css/fonts.css?ver=1.3');
	Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . '/css/main.css?v='.time());
	Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . '/css/media.css?v='.time());
	Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . '/css/app.css?v='.time());
	
	
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . '/libs/jquery/jquery-3.6.0.min.js');
	
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . '/libs/jquery-mousewheel/jquery.mousewheel.min.js');
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . '/libs/fancybox/jquery.fancybox.pack.js');
	
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . '/libs/countdown/jquery.plugin.js');
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . '/libs/countdown/jquery.countdown.min.js');
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . '/libs/countdown/jquery.countdown-ru.js');
	
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . '/libs/slick-1.5.9/slick/slick.min.js');
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . '/libs/scroll2id/PageScroll2id.min.js');
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . '/libs/mask/jquery.maskedinput.js');
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . '/libs/ion.rangeSlider-master/js/ion.rangeSlider.min.js');
	
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . '/libs/jQueryFormStyler-master/jquery.formstyler.min.js');
	
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . '/js/jquery.cookie.js');
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . '/js/common.js?v='.time());
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . '/js/app.js?v='.time());
	
?>
<!DOCTYPE html>
<html xml:lang="<?=LANGUAGE_ID?>" lang="<?=LANGUAGE_ID?>" ontouchmove>
<head>
    <title><?$APPLICATION->ShowTitle()?></title>
	<link rel="shortcut icon" type="image/png" href="<?=SITE_DIR?>favicon.png" />
	<meta name="google-site-verification" content="R2PZ4YE3ER1AzmuasOFB4NTE99cC4ql1a6gz_JviqgU" />
	<? $APPLICATION->ShowHead(); ?>
	<script>
		var template = {
			'url_theme' : '<?=SITE_TEMPLATE_PATH?>',
		}
	</script>
<?php if ($_SERVER['REQUEST_URI'] == '/') { ?>
<script type="application/ld+json">
{
	"@context": "http://schema.org/",
	"@type": "Organization",
	"name": "Юг Тойз",
	"logo": "https://www.yugtoys.ru/local/templates/ugtoiz/img/logo.svg",
	"url": "https://www.yugtoys.ru/",
	"address": {
		"@type": "PostalAddress",
		"streetAddress": "ул. Уральская 97",
		"addressLocality": "Краснодар",
		"addressRegion": "Краснодарский край",
		"postalCode": "350001",
		"addressCountry": "Russian Federation"
	},
	"sameAs": []
}
</script>
<?php } ?>
	<?if ($curPage == SITE_DIR.'index.php' || strpos($curPage, SITE_DIR.'about/contacts/') === 0): // П-3.4: карты только на главной и в контактах ?>
		<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU"></script>
	<?endif?>
	<meta name="yandex-verification" content="289b2b231f97478e" />
</head>
<body>
	<div id="panel"><?$APPLICATION->ShowPanel();?></div>
	<div class="wrapper <?=($curPage != SITE_DIR.'index.php' ? 'wrapper_page' : '')?>">
		<?$APPLICATION->IncludeComponent(
			"bitrix:sale.basket.basket.line",
			"fixed_buttons",
			Array(
				"HIDE_ON_BASKET_PAGES" => "N",
				"POSITION_FIXED" => "N",
				"SHOW_AUTHOR" => "N",
				"SHOW_EMPTY_VALUES" => "Y",
				"SHOW_NUM_PRODUCTS" => "N",
				"SHOW_PERSONAL_LINK" => "N",
				"SHOW_REGISTRATION" => "N",
				"SHOW_TOTAL_PRICE" => "N",
				"SHOW_PRODUCTS" => "Y",
			),
			false,
			array('HIDE_ICONS' => 'Y')
		);?>
		<header class="header default">
			<div class="header-top">
				<div class="container">
					<div class="header__wrapper">
						<?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/header_manu.php"], false, ['HIDE_ICONS' => 'N']);?>
						<? if($USER->IsAuthorized()){ ?>
						<?
							$name = trim($USER->GetFullName());
							if(!$name) $name = trim($USER->GetLogin());
							
							
						?>
						<a href="/personal/" class="btn-user"><img src="<?=SITE_TEMPLATE_PATH?>/img/user.svg" alt=""><?=htmlspecialcharsbx($name)?></a>
						<? }else{ ?>
						<a href="/personal/private/" class="btn-user"><img src="<?=SITE_TEMPLATE_PATH?>/img/user.svg" alt="">Вход в оптовый кабинет</a>
						<? } ?>
					</div>
				</div>
			</div>
			<div class="header-middle">
				<div class="container">
					<div class="header__wrapper">
						<a href="/" class="logo">
							<img src="<?=SITE_TEMPLATE_PATH?>/img/logo.svg" alt="Юг Тойз — оптовая компания">
						</a>
						<div class="location-main">
							<div class="location-main__value"><i class="fas fa-chevron-down"></i><?echo \COption::GetOptionString("askaron.settings", "UF_ADDRESS_HEAD");?></div>
							<ul class="location-main__dropdown">
								<li><a href="#"><?echo \COption::GetOptionString("askaron.settings", "UF_ADDRESS_DD1");?></a></li>
								<li><a href="#"><?echo \COption::GetOptionString("askaron.settings", "UF_ADDRESS_DD2");?></a></li>
							</ul>
						</div>
						<?$APPLICATION->IncludeComponent("bitrix:search.title", "bootstrap_v4", Array(
							"SHOW_INPUT" => "Y",
							"INPUT_ID" => "title-search-input",
							"CONTAINER_ID" => "title-search",
							"PRICE_CODE" => (IsAuthorized() ? ["BASE"] : []),
							"PRICE_VAT_INCLUDE" => "Y",
							"PREVIEW_TRUNCATE_LEN" => "150",
							"SHOW_PREVIEW" => "Y",
							"PREVIEW_WIDTH" => "80",
							"PREVIEW_HEIGHT" => "80",
							"CONVERT_CURRENCY" => "Y",
							"CURRENCY_ID" => "RUB",
							"PAGE" => "#SITE_DIR#search/",
							"NUM_CATEGORIES" => "1",
							"TOP_COUNT" => "20",
							"ORDER" => "date",
							"USE_LANGUAGE_GUESS" => "Y",
							"CHECK_DATES" => "Y",
							"SHOW_OTHERS" => "Y",
							"CATEGORY_0" => array("iblock_catalog"),
							"CATEGORY_0_iblock_catalog" => array("all"),
							"CATEGORY_0_TITLE" => "Каталог",
						), false, ['HIDE_ICONS' => 'Y']);?>
						<? if(!IsAuthorized()){ ?>
						<a href="/registration/" class="btn-main fancybox">Регистрация оптовика</a>
						<? } ?>
						<div class="phones-contacts">
							<a href="tel:<?=preg_replace('/[^0-9+]/', '', \COption::GetOptionString( "askaron.settings", "UF_PHONE"))?>" class="phone-main"><?echo \COption::GetOptionString("askaron.settings", "UF_PHONE");?></a>
							<div class="descr-min">Бесплатно по России</div>
							<div class="descr-min">Звоните с 8:30 до 17:30</div>
						</div>
						<a href="/personal/private/" class="btn-user-mobile"><img src="<?=SITE_TEMPLATE_PATH?>/img/user.svg" alt="Личный кабинет"></a>
						<a href="tel:<?=preg_replace('/[^0-9]/', '', \COption::GetOptionString( "askaron.settings", "UF_PHONE"))?>" class="btn-call"><i class="fas fa-phone-alt"></i></a>
						<div class="btn-catalog">
							<span class='sandwich'>
								<span class='sw-topper'></span>
								<span class='sw-bottom'></span>
								<span class='sw-footer'></span>
							</span>
						</div>
						<span class='sandwich sandwich_menu'>
							<span></span>
							<span></span>
							<span></span>
						</span>
					</div>
				</div>
			</div>
			<div class="header-bottom">
				<div class="container">
					<div class="header__wrapper">
						<div class="btn-catalog">
							<span class='sandwich'>
								<span class='sw-topper'></span>
								<span class='sw-bottom'></span>
								<span class='sw-footer'></span>
							</span>
							<div class="btn-catalog__title">КАТАЛОГ</div>
						</div>
						<ul class="nav">
							<li><a href="/catalog/novinki/"><img alt="" src="/local/templates/ugtoiz/img/flash.svg">НОВИНКИ</a></li>
							<?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/main_head_menu.php"], false, ['HIDE_ICONS' => 'Y']);?>
						</ul>
					</div>
				</div>
			</div>
			<div class="menu-mobile">
			    	<a href="/registration/" class="btn-main fancybox">Регистрация оптовика</a>
				<ul class="menu">
					<li><a href="/about/">О компании</a></li>
					<li><a href="/about/payment-and-delivery/">Оплата и доставка</a></li>
					<li><a href="/faq/">Вопрос-Ответ</a></li>
					<li><a href="/news/">Блог</a></li>
					<li><a href="/about/contacts/">Контакты</a></li>
					<li><a href="/catalog/novinki/"><img alt="" src="/local/templates/ugtoiz/img/flash.svg">НОВИНКИ</a></li>
					<?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/main_head_menu.php"], false, ['HIDE_ICONS' => 'Y']);?>
				</ul>
				<div class="location-main">
					<div class="location-main__value"><i class="fas fa-chevron-down"></i><?echo \COption::GetOptionString("askaron.settings", "UF_ADDRESS_HEAD");?></div>
					<ul class="location-main__dropdown">
						<li><a href="#"><?echo \COption::GetOptionString("askaron.settings", "UF_ADDRESS_DD1");?></a></li>
						<li><a href="#"><?echo \COption::GetOptionString("askaron.settings", "UF_ADDRESS_DD2");?></a></li>
					</ul>
				</div>
			
				<div class="phones-contacts">
					<a href="tel:<?=preg_replace('/[^0-9]/', '', \COption::GetOptionString( "askaron.settings", "UF_PHONE"))?>" class="phone-main"><?echo \COption::GetOptionString("askaron.settings", "UF_PHONE");?></a>
					<div class="descr-min">Бесплатно по России</div>
				</div>
				<ul class="social-list">
					<li><a target="_blank" href="<?echo \COption::GetOptionString("askaron.settings", "UF_WHATSAPP");?>"><img src="<?=SITE_TEMPLATE_PATH?>/img/wp2.svg" alt="WhatsApp"></a></li>
					<li><a target="_blank" href="<?echo \COption::GetOptionString("askaron.settings", "UF_TELEGRAM");?>"><img src="<?=SITE_TEMPLATE_PATH?>/img/tg.svg" alt="Telegram"></a></li>
					<li><a target="_blank" href="<?echo \COption::GetOptionString("askaron.settings", "UF_VIBER");?>"><img src="<?=SITE_TEMPLATE_PATH?>/img/vb.svg" alt="Viber"></a></li>
				</ul>
			</div>
			<div class="container">
				<div class="catalog-dropdown">
					<?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR.	"include/menu_catalog.php"], false, ['HIDE_ICONS' => 'Y']);?>
				</div>
			</div>
		</header>