<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/** Новая главная (редизайн). Подключается из /index.php только при lam_preview(). */
global $APPLICATION;
$lamDir = '/local/templates/.default/lam';
$lamImg = $lamDir . '/img/';
?>
<div class="lam-home">

	<!-- LAM HOME: Hero — слайдер (инфоблок «Слайды на главной», lam_hero_slides) -->
	<section class="lam-hero">
		<div class="lam-container">
			<div class="lam-hero__box" data-lam-hero>
				<div class="lam-hero__art lam-hero__art--shapes" aria-hidden="true">
					<img class="lam-hero__shape lam-hero__shape--blue" src="<?=$lamImg?>hero-shape-blue.svg" alt="">
					<img class="lam-hero__shape lam-hero__shape--outline" src="<?=$lamImg?>hero-shape-outline.svg" alt="">
				</div>
				<?php $APPLICATION->IncludeComponent("bitrix:news.list", "lam_adv", array(
					"IBLOCK_TYPE" => "aspro_mshop_adv",
					"IBLOCK_ID" => "112",
					"NEWS_COUNT" => "10",
					"SORT_BY1" => "SORT", "SORT_ORDER1" => "ASC",
					"SORT_BY2" => "ID", "SORT_ORDER2" => "ASC",
					"FIELD_CODE" => array("PREVIEW_TEXT", "PREVIEW_PICTURE"),
					"PROPERTY_CODE" => array("LABEL", "LABEL_COLOR", "BUTTON_TEXT", "BUTTON_LINK"),
					"CHECK_DATES" => "Y",
					"ACTIVE_DATE_FORMAT" => "d.m.Y",
					"SET_TITLE" => "N", "SET_BROWSER_TITLE" => "N", "SET_META_KEYWORDS" => "N", "SET_META_DESCRIPTION" => "N",
					"SET_LAST_MODIFIED" => "N", "SET_STATUS_404" => "N", "INCLUDE_IBLOCK_INTO_CHAIN" => "N", "ADD_SECTIONS_CHAIN" => "N",
					"DISPLAY_TOP_PAGER" => "N", "DISPLAY_BOTTOM_PAGER" => "N",
					"CACHE_TYPE" => "A", "CACHE_TIME" => "3600", "CACHE_FILTER" => "N", "CACHE_GROUPS" => "N",
					"LAM_V" => "1", // сменить, чтобы сбросить кеш шаблона
				), false); ?>
				<img class="lam-hero__shape lam-hero__shape--yellow" src="<?=$lamImg?>hero-shape-yellow.svg" alt="" aria-hidden="true">
				<div class="lam-hero__swatches-wrap">
					<?php $APPLICATION->IncludeFile($lamDir . '/inc_hero_swatches.php', array(), array('MODE' => 'html', 'NAME' => 'образцы оттенков')); ?>
				</div>
			</div>
		</div>
	</section>

	<!-- LAM HOME: Преимущества (полоса под баннером) -->
	<section class="lam-advantages" aria-label="Преимущества">
		<div class="lam-container">
			<?php $APPLICATION->IncludeFile($lamDir . '/inc_adv.php', array(), array('MODE' => 'html', 'NAME' => 'преимущества')); ?>
		</div>
	</section>

	<!-- LAM HOME: Категории каталога -->
	<section class="lam-section lam-cats">
		<div class="lam-container">
			<?php $APPLICATION->IncludeFile($lamDir . '/inc_cats.php', array(), array('MODE' => 'html', 'NAME' => 'категории')); ?>
		</div>
	</section>

	<!-- LAM HOME: Популярные предложения (вкладки + карусель товаров) -->
	<?php $APPLICATION->IncludeComponent(
	"aspro:tabs.mshop",
	"lam",
	Array(
		"ACTION_VARIABLE" => "action",
		"ADD_SECTIONS_CHAIN" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"BASKET_URL" => "/basket/",
		"BROWSER_TITLE" => "-",
		"CACHE_FILTER" => "Y",
		"CACHE_GROUPS" => "N",
		"CACHE_TIME" => "250000",
		"CACHE_TYPE" => "A",
		"COMPONENT_TEMPLATE" => "lam",
		"LAM_V" => "3", // версия разметки lam: сменить, чтобы сбросить кеш компонента
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"CONVERT_CURRENCY" => "N",
		"DETAIL_URL" => "",
		"DISCOUNT_PRICE_CODE" => "",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"DISPLAY_COMPARE" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_WISH_BUTTONS" => "Y",
		"ELEMENT_SORT_FIELD" => "sort",
		"ELEMENT_SORT_FIELD2" => "id",
		"ELEMENT_SORT_ORDER" => "asc",
		"ELEMENT_SORT_ORDER2" => "desc",
		"FILTER_NAME" => "arrFilterProp",
		"HIDE_NOT_AVAILABLE" => "N",
		"IBLOCK_ID" => "17",
		"IBLOCK_TYPE" => "aspro_mshop_catalog",
		"INCLUDE_SUBSECTIONS" => "Y",
		"LINE_ELEMENT_COUNT" => "5",
		"META_DESCRIPTION" => "-",
		"META_KEYWORDS" => "-",
		"OFFERS_CART_PROPERTIES" => array(),
		"OFFERS_FIELD_CODE" => array(0=>"ID",1=>"",),
		"OFFERS_LIMIT" => "10",
		"OFFERS_PROPERTY_CODE" => array(0=>"",1=>"",),
		"OFFERS_SORT_FIELD" => "sort",
		"OFFERS_SORT_FIELD2" => "id",
		"OFFERS_SORT_ORDER" => "asc",
		"OFFERS_SORT_ORDER2" => "desc",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "Товары",
		"PAGE_ELEMENT_COUNT" => "20",
		"PRICE_CODE" => array(0=>"BASE",),
		"PRICE_VAT_INCLUDE" => "Y",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRODUCT_PROPERTIES" => array(),
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"PROPERTY_CODE" => array(0=>"",1=>"",),
		"SALE_STIKER" => "SALE_TEXT",
		"SECTION_CODE" => "",
		"SECTION_ID" => "",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"SECTION_NAME_FILTER" => "",
		"SECTION_SLIDER_FILTER" => "21",
		"SECTION_URL" => "",
		"SECTION_USER_FIELDS" => array(0=>"",1=>"",),
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_ADD_FAVORITES" => "Y",
		"SHOW_ALL_WO_SECTION" => "Y",
		"SHOW_DISCOUNT_PERCENT" => "Y",
		"SHOW_DISCOUNT_TIME" => "Y",
		"SHOW_MEASURE" => "Y",
		"SHOW_OLD_PRICE" => "Y",
		"SHOW_PRICE_COUNT" => "1",
		"TABS_CODE" => "HIT",
		"USE_PRICE_COUNT" => "N",
		"USE_PRODUCT_QUANTITY" => "N"
	)
); ?>

	<!-- LAM HOME: Бренды (карусель логотипов) -->
	<?php $APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"lam_brands",
	Array(
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"ADD_SECTIONS_CHAIN" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"CACHE_FILTER" => "Y",
		"CACHE_GROUPS" => "N",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "N",
		"DISPLAY_PREVIEW_TEXT" => "N",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array(0=>"PREVIEW_PICTURE",1=>"DETAIL_PICTURE",),
		"FILTER_NAME" => "",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "12",
		"LAM_V" => "2", // сменить, чтобы сбросить кеш шаблона
		"IBLOCK_TYPE" => "aspro_mshop_content",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"NEWS_COUNT" => "9999",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "3600",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => "",
		"PAGER_TITLE" => "",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"PREVIEW_TRUNCATE_LEN" => "",
		"PROPERTY_CODE" => array(0=>"",1=>"",),
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SORT_BY1" => "SORT",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "DESC",
		"SORT_ORDER2" => "ASC"
	)
); ?>

	<!-- LAM HOME: Новости компании -->
	<?php $APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"lam_news",
	Array(
		"ACTIVE_DATE_FORMAT" => "j F Y",
		"ADD_SECTIONS_CHAIN" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"ALL_URL" => "company/news/",
		"CACHE_FILTER" => "Y",
		"CACHE_GROUPS" => "N",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "Y",
		"COMPONENT_TEMPLATE" => "lam_news",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"DETAIL_URL" => "#SITE_DIR#company/news/#YEAR#/#ELEMENT_CODE#/",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array(0=>"PREVIEW_PICTURE",1=>"DETAIL_PICTURE",),
		"FILTER_NAME" => "",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "14",
		"LAM_V" => "2", // сменить, чтобы сбросить кеш шаблона
		"IBLOCK_TYPE" => "aspro_mshop_content",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"INCLUDE_SUBSECTIONS" => "Y",
		"MESSAGE_404" => "",
		"NEWS_COUNT" => "3",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => "",
		"PAGER_TITLE" => "",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"PREVIEW_TRUNCATE_LEN" => "140",
		"PROPERTY_CODE" => array(0=>"",1=>"",),
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "DESC",
		"SORT_ORDER2" => "ASC",
		"STRICT_SECTION_CHECK" => "N",
		"TITLE_BLOCK" => "Новости компании"
	)
); ?>

	<!-- LAM HOME: Подписка на рассылку -->
	<section class="lam-subscribe">
		<div class="lam-container">
			<div class="lam-subscribe__box">
				<img class="lam-subscribe__shape lam-subscribe__shape--blue1" src="<?=$lamImg?>sub-shape-blue1.svg" alt="" aria-hidden="true">
				<img class="lam-subscribe__shape lam-subscribe__shape--orange" src="<?=$lamImg?>sub-shape-orange.svg" alt="" aria-hidden="true">
				<img class="lam-subscribe__shape lam-subscribe__shape--blue2" src="<?=$lamImg?>sub-shape-blue2.svg" alt="" aria-hidden="true">
				<img class="lam-subscribe__icon" src="<?=$lamImg?>subscribe-mail.svg" alt="" width="110" height="110" aria-hidden="true">

				<div class="lam-subscribe__content">
					<span class="lam-label lam-label--orange">Рассылка Ламинариум</span>
					<h2 class="lam-title">Будьте в курсе новостей<br>и выгодных предложений</h2>
					<p class="lam-subtitle">Рассказываем о новинках, скидках и полезных советах по выбору пола</p>
				</div>

				<?php $APPLICATION->IncludeComponent(
	"bitrix:subscribe.form",
	"lam",
	Array(
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"ALLOW_ANONYMOUS" => "Y",
		"CACHE_NOTES" => "",
		"CACHE_TIME" => "86400",
		"CACHE_TYPE" => "A",
		"COMPONENT_TEMPLATE" => "lam",
		"LAM_VIEW" => "home",
		"LK" => "Y",
		"PAGE" => SITE_DIR."personal/subscribe/",
		"SET_TITLE" => "N",
		"SHOW_AUTH_LINKS" => "N",
		"SHOW_HIDDEN" => "N",
		"URL_SUBSCRIBE" => SITE_DIR."subscribe/",
		"USE_PERSONALIZATION" => "Y"
	)
); ?>
			</div>
		</div>
	</section>

	<!-- LAM HOME: О магазине (SEO-текст) -->
	<section class="lam-about">
		<div class="lam-container lam-about__in">
			<div class="lam-about__media">
				<img src="<?=$lamImg?>about.webp" alt="Образцы ламината разных оттенков" width="543" height="624" loading="lazy">
			</div>
			<div class="lam-about__content">
				<div class="lam-about__intro">
					<?php $APPLICATION->IncludeFile($lamDir . '/inc_about.php', array(), array('MODE' => 'html', 'NAME' => 'текст о магазине')); ?>
				</div>
				<div class="lam-about__why">
					<h3 class="lam-about__why-title">Почему выбирают Ламинариум</h3>
					<ul class="lam-about__list">
						<li>Широкий выбор ламината и аксессуаров</li>
						<li>Конкурентные цены в Краснодаре</li>
						<li>Профессиональная укладка</li>
						<li>Онлайн-заказ и оплата</li>
					</ul>
				</div>
				<ul class="lam-adv lam-adv--about">
					<li class="lam-adv__item"><img src="<?=$lamImg?>adv-stack.svg" alt="" width="36" height="36"><span class="lam-adv__text">2000+<br>декоров</span></li>
					<li class="lam-adv__item"><img src="<?=$lamImg?>adv-calendar.svg" alt="" width="36" height="36"><span class="lam-adv__text">7 дней<br>в неделю</span></li>
					<li class="lam-adv__item"><img src="<?=$lamImg?>adv-pin.svg" alt="" width="36" height="36"><span class="lam-adv__text">4 шоу-рума<br>в Краснодаре</span></li>
				</ul>
				<a class="lam-btn lam-about__btn" href="<?=SITE_DIR?>company/">Подробнее о компании</a>
			</div>
		</div>
	</section>

</div>
<!-- /lam-home -->
<?php if (is_file(__DIR__ . '/inc_old_blocks.php')) include __DIR__ . '/inc_old_blocks.php'; ?>
