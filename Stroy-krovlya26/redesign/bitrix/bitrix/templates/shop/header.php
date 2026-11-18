<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
require_once $_SERVER["DOCUMENT_ROOT"]."/include/sk_recaptcha.php";
sk_recaptcha_boot();
if (!empty($_REQUEST['strIMessage'])) { LocalRedirect('/include/spasibo.php'); }
echo '<script data-skip-moving="true" src="/bitrix/templates/shop/js/bx-core.js"></script>';
IncludeTemplateLangFile(__FILE__);
require_once __DIR__ . "/sk/flag.php";

// Вебвизор / карты кликов: разрешить iframe с доменов Яндекса
// (если Bitrix/nginx позже добавит X-Frame-Options, современные браузеры берут frame-ancestors)
if (!headers_sent()) {
	header_remove("X-Frame-Options");
	header(
		"Content-Security-Policy: frame-ancestors 'self' https://metrika.yandex.ru https://metrika.yandex.by https://metrica.yandex.com https://metrica.yandex.com.tr https://webvisor.com https://*.webvisor.com"
	);
}

?>
<!DOCTYPE html>
<html xml:lang="<?=LANGUAGE_ID?>" lang="<?=LANGUAGE_ID?>">
<head>
	<link type="image/x-icon" href="/favicon.ico/" />
<meta name="yandex-verification" content="e7ab75e0d15892b8" />
<meta name="google-site-verification" content="RmNnUuKX6tMCZ5Yr9nYXIL0473LwKAsQZOTnTcxkYN4" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title><?$APPLICATION->ShowTitle()?></title>
	<?$APPLICATION->ShowHead();?> 
	

	<script src="<?=SITE_TEMPLATE_PATH?>/js/jquery.min.js"></script> 
	<script src="<?=SITE_TEMPLATE_PATH?>/jquery.fancybox.min.js"></script> 
	<link href="<?=SITE_TEMPLATE_PATH?>/jquery.fancybox.min.css" rel="stylesheet" type="text/css">
	<script src="<?=SITE_TEMPLATE_PATH?>/js/scripts.js"></script> 
	<script src="<?=SITE_TEMPLATE_PATH?>/js/gallery.js"></script>
	<script src="<?=SITE_TEMPLATE_PATH?>/js/owl.carousel.min.js"></script>
	<script src="<?=SITE_TEMPLATE_PATH?>/js/jquery.formstyler.js"></script>
	<script src="<?=SITE_TEMPLATE_PATH?>/js/moremenu.js"></script>
	<link href="<?=SITE_TEMPLATE_PATH?>/owl.carousel.min.css" rel="stylesheet" type="text/css">
	<link href="<?=SITE_TEMPLATE_PATH?>/media-queries.css" rel="stylesheet" type="text/css">
	<script src="/include/sk_source.js?v=1"></script><?// источник заявки для Telegram (cookie tz_src) ?>
	<?if (SK_NEW):?>
	<link href="<?=SK_PATH?>/css/fonts.css?v=<?=SK_V?>" rel="stylesheet" type="text/css">
	<link href="<?=SK_PATH?>/css/sk.css?v=<?=SK_V?>" rel="stylesheet" type="text/css">
	<link href="<?=SK_PATH?>/css/sk-bx.css?v=<?=SK_V?>" rel="stylesheet" type="text/css">
	<link href="<?=SK_PATH?>/css/sk-inner.css?v=<?=SK_V?>" rel="stylesheet" type="text/css">
	<?endif;?>
	<meta name="google-site-verification" content="-mSbv4-bI-MQ3P5hdPgv1RmB_NBeu3-mJfiC--3E2xc">
	<meta name="yandex-verification" content="258f6b5fedd4c3ff" />

	<script src="//code-ya.jivosite.com/widget/yfmu4jTwc2" async></script>
	<script type="application/ld+json">
	{
	 "@context": "https://schema.org",
	 "@type": "LocalBusiness",
	 "name": "\u0421\u0442\u0440\u043e\u0439-\u041a\u0440\u043e\u0432\u043b\u044f",
	 "url": "https://stroy-krovlya26.ru/",
	 "logo": "https://stroy-krovlya26.ru/bitrix/templates/shop/images/logo.png",
	 "image": "https://stroy-krovlya26.ru/bitrix/templates/shop/images/logo.png",
	 "telephone": "+7 (8652) 33-40-41",
	 "email": "info@stroy-krovlya26.ru",
	 "priceRange": "RUB",
	 "address": {
	 "@type": "PostalAddress",
	 "addressLocality": "\u0421\u0442\u0430\u0432\u0440\u043e\u043f\u043e\u043b\u044c",
	 "streetAddress": "\u0421\u0442\u0430\u0440\u043e\u043c\u0430\u0440\u044c\u0435\u0432\u0441\u043a\u043e\u0435 \u0448\u043e\u0441\u0441\u0435, 6",
	 "addressCountry": "RU"
	 },
	 "areaServed": "\u0421\u0442\u0430\u0432\u0440\u043e\u043f\u043e\u043b\u044c \u0438 \u0421\u0442\u0430\u0432\u0440\u043e\u043f\u043e\u043b\u044c\u0441\u043a\u0438\u0439 \u043a\u0440\u0430\u0439"
	}
	</script>
	
	<script type="text/javascript">
	(function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
	m[i].l=1*new Date();
	for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
	k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
	(window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");
	ym(38770565, "init", {
		clickmap:true,
		trackLinks:true,
		accurateTrackBounce:true,
		webvisor:true
	});
	</script>
	<noscript><div><img src="https://mc.yandex.ru/watch/38770565" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
	
<?php sk_recaptcha_print_assets(); ?>
</head> 
<body>
<div id="panel"><?$APPLICATION->ShowPanel();?></div>
<div class="Holder<?=SK_NEW ? ' sk-on' : ''?> <?if ($GLOBALS["APPLICATION"]->GetCurPage() != "/"):?> Cont<?if(CSite::InDir('/catalog/')):?> CatalogPage<?endif;?><?endif;?>">
 <div id="Blocks<?if ($GLOBALS["APPLICATION"]->GetCurPage() == "/kontakty/"):?>Contacts<?endif;?>">
 	<div class="SiteHolder">
			<div class="Block">
				<? /*$APPLICATION->IncludeComponent(
					"bitrix:news.detail", 
					"banner", 
					array(
						"DISPLAY_DATE" => "Y",
						"DISPLAY_NAME" => "Y",
						"DISPLAY_PICTURE" => "Y",
						"DISPLAY_PREVIEW_TEXT" => "Y",
						"USE_SHARE" => "N",
						"AJAX_MODE" => "N",
						"IBLOCK_TYPE" => "info",
						"IBLOCK_ID" => "53",
						"ELEMENT_ID" => "3105",
						"ELEMENT_CODE" => "",
						"CHECK_DATES" => "Y",
						"FIELD_CODE" => array(
							0 => "",
							1 => "",
						),
						"PROPERTY_CODE" => array(
							0 => "",
							1 => "",
						),
						"IBLOCK_URL" => "",
						"DETAIL_URL" => "",
						"SET_TITLE" => "N",
						"SET_CANONICAL_URL" => "N",
						"SET_BROWSER_TITLE" => "N",
						"BROWSER_TITLE" => "-",
						"SET_META_KEYWORDS" => "N",
						"META_KEYWORDS" => "-",
						"SET_META_DESCRIPTION" => "N",
						"META_DESCRIPTION" => "-",
						"SET_LAST_MODIFIED" => "N",
						"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
						"ADD_SECTIONS_CHAIN" => "N",
						"ADD_ELEMENT_CHAIN" => "N",
						"ACTIVE_DATE_FORMAT" => "d.m.Y",
						"USE_PERMISSIONS" => "N",
						"STRICT_SECTION_CHECK" => "N",
						"CACHE_TYPE" => "A",
						"CACHE_TIME" => "86400",
						"CACHE_GROUPS" => "N",
						"PAGER_TEMPLATE" => ".default",
						"DISPLAY_TOP_PAGER" => "N",
						"DISPLAY_BOTTOM_PAGER" => "Y",
						"PAGER_TITLE" => "Страница",
						"PAGER_SHOW_ALL" => "N",
						"PAGER_BASE_LINK_ENABLE" => "N",
						"SET_STATUS_404" => "N",
						"SHOW_404" => "N",
						"MESSAGE_404" => "",
						"AJAX_OPTION_JUMP" => "N",
						"AJAX_OPTION_STYLE" => "Y",
						"AJAX_OPTION_HISTORY" => "N",
						"COMPOSITE_FRAME_MODE" => "A",
						"COMPOSITE_FRAME_TYPE" => "AUTO",
						"COMPONENT_TEMPLATE" => "banner",
						"AJAX_OPTION_ADDITIONAL" => ""
					),
					false
);*/ ?>
			</div>
<?if (SK_NEW): include __DIR__ . "/sk/header.php";?>
			<div class="Header sk-oldheader">
<?else:?>
			<div class="Header">
				<div class="Wrapper">
					<div class="Flex">
						<div class="Logo"><a href="/"><img src="<?=SITE_TEMPLATE_PATH?>/images/logo.png" alt="«Строй-Кровля» - кровельные материалы" title="«Строй-Кровля» - кровельные материалы" width="163px" height="86px"></a></div>
						<div class="Info">
							<div class="TopBlock">
								<div class="Flex Baseline">
									<div class="Tel"><a href="tel:<?php include $_SERVER['DOCUMENT_ROOT']."/include/tel-open.php";?>" onclick="ym(38770565, 'reachGoal', 'Perehod_po_nomeru_telefona'); return true;">
									<?$APPLICATION->IncludeFile(
										SITE_DIR."include/tel.php",
										Array(),
										Array("MODE"=>"text")
									);?></a></div>
									<div class="OrderCall"><a data-fancybox="" data-src="#OrderCall" href="javascript:;" >Перезвоните мне</a></div>
									<div class="Social">
										
									</div>
									<div class="Cart ml-auto">
									<?/*$APPLICATION->IncludeComponent("intec:startshop.basket.basket.small", "basket", Array(
										"USE_BUTTON_BUY" => "Y",
										"URL_ORDER" => "",
										"CFO_USE_FASTORDER" => "Y",
										"URL_BASKET" => "/cart/",	// Адрес страницы с корзиной
										"CURRENCY" => "RUB",	// Валюта
										"REQUEST_VARIABLE_ACTION" => "action",	// Переменная, в которой передается действие
										"REQUEST_VARIABLE_ITEM" => "item",	// Переменная, в которой передается позиция
										"REQUEST_VARIABLE_QUANTITY" => "quantity",	// Переменная, в которой передается количетсво
										"COMPONENT_TEMPLATE" => ".default",
										"USE_COUNT" => "Y",	// Отображать количество товаров в корзине
										"USE_SUM" => "N",	// Отображать общую сумму
										"USE_COUNT_IF_EMPTY" => "Y",	// Отображать количество, если корзина пуста
									),
									false
								);*/?>
									</div>
								</div>
							</div>
							<div class="MenuBlock">
								<div class="Flex Baseline">
									<div class="MainMenu">
										<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.sections.top", 
	"main-menu", 
	array(
		"IBLOCK_TYPE" => "catalog",
		"IBLOCK_ID" => "48",
		"SECTION_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"SECTION_USER_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"SECTION_SORT_FIELD" => "sort",
		"SECTION_SORT_ORDER" => "asc",
		"ELEMENT_SORT_FIELD" => "sort",
		"ELEMENT_SORT_ORDER" => "asc",
		"ELEMENT_SORT_FIELD2" => "id",
		"ELEMENT_SORT_ORDER2" => "desc",
		"FILTER_NAME" => "arrFilter",
		"SECTION_URL" => "",
		"DETAIL_URL" => "",
		"BASKET_URL" => "/",
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"DISPLAY_COMPARE" => "N",
		"USE_MAIN_ELEMENT_SECTION" => "N",
		"SECTION_COUNT" => "10",
		"ELEMENT_COUNT" => "10",
		"LINE_ELEMENT_COUNT" => "1",
		"PROPERTY_CODE" => array(
			0 => "LINK",
			1 => "",
		),
		"PRICE_CODE" => array(
		),
		"USE_PRICE_COUNT" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"PRICE_VAT_INCLUDE" => "Y",
		"PRODUCT_PROPERTIES" => array(
		),
		"USE_PRODUCT_QUANTITY" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "86400",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "N",
		"COMPONENT_TEMPLATE" => "main-menu"
	),
	false
);?> 
									</div>
									<div class="IconBlock ml-auto">
										<div class="Flex"> 
											<div class="AllMenu"><span class="OpenMenu"><span>Все меню</span></span></div>
												<div class="Cart">
													<?/*$APPLICATION->IncludeComponent("intec:startshop.basket.basket.small", "basket", Array(
														"USE_BUTTON_BUY" => "Y",
														"URL_ORDER" => "",
														"CFO_USE_FASTORDER" => "Y",
														"URL_BASKET" => "/cart/",	// Адрес страницы с корзиной
														"CURRENCY" => "RUB",	// Валюта
														"REQUEST_VARIABLE_ACTION" => "action",	// Переменная, в которой передается действие
														"REQUEST_VARIABLE_ITEM" => "item",	// Переменная, в которой передается позиция
														"REQUEST_VARIABLE_QUANTITY" => "quantity",	// Переменная, в которой передается количетсво
														"COMPONENT_TEMPLATE" => ".default",
														"USE_COUNT" => "Y",	// Отображать количество товаров в корзине
														"USE_SUM" => "N",	// Отображать общую сумму
														"USE_COUNT_IF_EMPTY" => "Y",	// Отображать количество, если корзина пуста
													),
													false
												);*/?>
												</div>
											<div class="Search"><span class="SearchPopup"><span></span></span></div>
										</div> 
									</div>
								</div>
								<div class="PopupMenu CloseBlock">
									<div class="AllMenu"><span class="CloseMenu"><span>Все меню</span></span></div>
									<div class="Menu">
										<div class="Items">
											<div class="Item">
												<div class="Item_Inner">
													<div class="Title">Компания</div>
													<?$APPLICATION->IncludeComponent(
														"bitrix:menu", 
														"link-menu", 
														array(
															"ROOT_MENU_TYPE" => "bottom",
															"MAX_LEVEL" => "1",
															"CHILD_MENU_TYPE" => "",
															"USE_EXT" => "Y",
															"MENU_CACHE_TYPE" => "A",
															"MENU_CACHE_TIME" => "86400",
															"MENU_CACHE_USE_GROUPS" => "N",
															"MENU_CACHE_GET_VARS" => array(
															),
															"COMPONENT_TEMPLATE" => "link-menu",
															"DELAY" => "N",
															"ALLOW_MULTI_SELECT" => "N"
														),
														false,
														array(
															"ACTIVE_COMPONENT" => "Y"
														)
													);?> 
												</div>
											</div>
											<div class="Item">
												<div class="Item_Inner">
													<div class="Title">Каталог</div>
													<?$APPLICATION->IncludeComponent(
														"bitrix:menu", 
														"link-menu", 
														array(
															"ROOT_MENU_TYPE" => "catalog-link",
															"MAX_LEVEL" => "1",
															"CHILD_MENU_TYPE" => "",
															"USE_EXT" => "Y",
															"MENU_CACHE_TYPE" => "A",
															"MENU_CACHE_TIME" => "86400",
															"MENU_CACHE_USE_GROUPS" => "N",
															"MENU_CACHE_GET_VARS" => array(
															),
															"COMPONENT_TEMPLATE" => "link-menu",
															"DELAY" => "N",
															"ALLOW_MULTI_SELECT" => "N"
														 ),
					 $component, // Добавляем
					 array("HIDE_ICONS" => "Y") // Добавляем
					);?>
												</div>
											</div>
											<div class="Item">
												<div class="Item_Inner">
													<div class="Title">Производители</div>
													<?$APPLICATION->IncludeComponent(
														"bitrix:menu", 
														"brends-menu", 
														array(
															"ROOT_MENU_TYPE" => "brends",
															"MAX_LEVEL" => "1",
															"CHILD_MENU_TYPE" => "",
															"USE_EXT" => "Y",
															"MENU_CACHE_TYPE" => "A",
															"MENU_CACHE_TIME" => "86400",
															"MENU_CACHE_USE_GROUPS" => "N",
															"MENU_CACHE_GET_VARS" => array(
															),
															"COMPONENT_TEMPLATE" => "brends-menu",
															"DELAY" => "N",
															"ALLOW_MULTI_SELECT" => "N"
														 ),
					 $component, // Добавляем
					 array("HIDE_ICONS" => "Y") // Добавляем
					);?>
												</div>
											</div>
											<div class="Clear"></div>
										</div>
									</div>
									<br />
									<div class="Contacts">
										<div class="Title">
											<div class="Flex Baseline">
												<span>Контакты</span>
												<div class="AllMenu"><span class="CloseMenu"><span>Закрыть</span></span></div>
											</div>
										</div>
										<div class="Flex Start">
											<div class="Box">
												<div class="Tel">
												<a href="tel:<?php include $_SERVER['DOCUMENT_ROOT']."/include/tel-open.php";?>">
														<?$APPLICATION->IncludeFile(
															SITE_DIR."include/tel.php",
															Array(),
															Array("MODE"=>"text")
														);?></a></div>
											<div class="Mail"><a href="mailto:<?php include $_SERVER['DOCUMENT_ROOT']."/include/mail.php";?>">
														<?$APPLICATION->IncludeFile(
															SITE_DIR."include/mail.php",
															Array(),
															Array("MODE"=>"text")
														);?></a></div>
											</div>
											<div class="Box">
												<div class="Adress"><?$APPLICATION->IncludeFile(
															SITE_DIR."include/adress.php",
															Array(),
															Array("MODE"=>"text")
														);?></div>
												<div class="Map"><a href="/kontakty/">Карта проезда</a></div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>			
<?endif;?>
				<div class="PopupSearch CloseSearch">
					<div class="Search">
						<div class="Flex">
							<div class="SearchForm">
								<div class="Flex">
									<?$APPLICATION->IncludeComponent(
	"bitrix:search.title", 
	"search", 
	array(
		"SHOW_INPUT" => "Y",
		"INPUT_ID" => "title-search-input",
		"CONTAINER_ID" => "title-search",
		"PRICE_CODE" => array(
		),
		"PRICE_VAT_INCLUDE" => "Y",
		"PREVIEW_TRUNCATE_LEN" => "",
		"SHOW_PREVIEW" => "Y",
		"PREVIEW_WIDTH" => "75",
		"PREVIEW_HEIGHT" => "75",
		"PAGE" => "#SITE_DIR#search/index.php",
		"NUM_CATEGORIES" => "1",
		"TOP_COUNT" => "5",
		"ORDER" => "date",
		"USE_LANGUAGE_GUESS" => "Y",
		"CHECK_DATES" => "N",
		"SHOW_OTHERS" => "N",
		"CATEGORY_0_TITLE" => "",
		"CATEGORY_0" => array(
			0 => "iblock_info",
			1 => "iblock_catalog",
		),
		"CATEGORY_0_iblock_catalog" => array(
			0 => "17",
		),
		"COMPONENT_TEMPLATE" => "search",
		"CATEGORY_0_iblock_info" => array(
			0 => "10",
			1 => "14",
		),
		"TEMPLATE_THEME" => "blue"
	),
	false
);?>
									<span class="SearchClose"><span>Закрыть</span></span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?if ($GLOBALS["APPLICATION"]->GetCurPage() != "/"):?>
			<?if (SK_NEW):?>
			<div class="sk-scope sk-pagehead"><div class="sk-container">
				<?$APPLICATION->IncludeComponent("bitrix:breadcrumb", "sk", array("START_FROM" => "1", "PATH" => "", "SITE_ID" => "s1"), false, array("HIDE_ICONS" => "Y"));?>
				<?$skH = $APPLICATION->GetCurPage() == "/404.php" ? "p" : "h1"; // на 404 свой h1 в контенте ?>
				<<?=$skH?> class="sk-page__title"><?$APPLICATION->ShowTitle(false);?></<?=$skH?>>
			</div></div>
			<?else:?>
			<div class="TitleBlock">
				<div class="Wrapper">
					<div class="Block">
						<div class="Title"><h1><?$APPLICATION->ShowTitle(false);?></h1></div>
						<?$APPLICATION->IncludeComponent(
							"bitrix:breadcrumb", 
							"nav", 
							array(
								"START_FROM" => "1",
								"PATH" => "",
								"SITE_ID" => "s1",
								"COMPONENT_TEMPLATE" => "nav"
							),
							false
						);?>
					</div>
				</div>
			</div>
			<?endif;?>
			<div class="Conteiner">
				<?if ($GLOBALS["APPLICATION"]->GetCurPage() != "/kontakty/"):?> 
				<div class="Wrapper">
				<?endif;?>
					<?if ($GLOBALS["APPLICATION"]->GetCurPage() == "/catalog/" || $GLOBALS["APPLICATION"]->GetCurPage() == "/cart/" || $GLOBALS["APPLICATION"]->GetCurPage() == "/order.php" || $GLOBALS["APPLICATION"]->GetCurPage() == "/brends/" || $GLOBALS["APPLICATION"]->GetCurPage() == "/kontakty/" || $GLOBALS["APPLICATION"]->GetCurPage() == "/nashi-obekty/" || $GLOBALS["APPLICATION"]->GetCurPage() == "/stati/" || $GLOBALS["APPLICATION"]->GetCurPage() == "/aktsii/"):?> 
					<?else:?> 
					<div class="LeftBlock">
							<?$APPLICATION->ShowViewContent('left-block');?>
							<?$APPLICATION->IncludeComponent(
								"bitrix:menu", 
								"left-menu", 
								array(
									"ROOT_MENU_TYPE" => "left",
									"MAX_LEVEL" => "1",
									"CHILD_MENU_TYPE" => "",
									"USE_EXT" => "Y",
									"MENU_CACHE_TYPE" => "A",
									"MENU_CACHE_TIME" => "86400",
									"MENU_CACHE_USE_GROUPS" => "N",
									"MENU_CACHE_GET_VARS" => array(
									),
									"COMPONENT_TEMPLATE" => "main-menu",
									"DELAY" => "N",
									"ALLOW_MULTI_SELECT" => "N"
								),
								false,
								array(
									"ACTIVE_COMPONENT" => "Y"
								)
							);?> 
							<?$APPLICATION->ShowViewContent('filter');?>
						<div class="BannerCalc">
							<div class="Icon"><img src="<?=SITE_TEMPLATE_PATH?>/images/banner-calc.png" title="Cделать расчет количества кровли" alt="Cделать расчет количества кровли"></div>
							<div class="Title">Необходимо сделать расчет количества кровли или других материалов?</div>
							<div class="Button"><a data-fancybox data-type="ajax" data-src="/include/forms.php" href="javascript:;" class="Btn"><span>Заявка на расчет</span></a></div>
						</div>
									<?$APPLICATION->IncludeComponent(
										"bitrix:main.include",
										"",
										Array(
											"AREA_FILE_SHOW" => "file",
											"PATH" => SITE_DIR."include/benefits.php",
											"AREA_FILE_RECURSIVE" => "N",
											"EDIT_MODE" => "html",
										),
										false,
										Array('HIDE_ICONS' => 'Y')
									);?>
					</div>
					<div class="ContBlock">
						<div class="Content">
							<div class="<?if($contbox == "Y"){?> Box<?}?>">
					<?endif;?>
			<?endif;?>