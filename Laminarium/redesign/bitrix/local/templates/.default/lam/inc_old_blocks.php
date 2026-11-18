<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/** Блоки старой главной, которых нет в макете. Включаются флагами SHOW_* в config.php.
 *  Код скопирован 1:1 из /index.php (бэкап orig/index.php), вид — старый Аспро: макета под новый дизайн нет.
 *  Подключается из home.php ПОСЛЕ .lam-home, чтобы нормализация lam.css их не задела. */
global $APPLICATION, $TEMPLATE_OPTIONS;
$lamCfg = include __DIR__ . '/config.php';
?>
<?if (!empty($lamCfg['SHOW_PROMO'])):?>
<!-- LAM OLD: слайдер акций -->
<div class="grey_bg">
	<div class="wrapper_inner">
		 <?$APPLICATION->IncludeComponent(
	"bitrix:news.list", 
	"news_akc_slider", 
	array(
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
		"COMPONENT_TEMPLATE" => "news_akc_slider",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"DETAIL_URL" => "",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array(
			0 => "DETAIL_PICTURE",
			1 => "",
		),
		"FILTER_NAME" => "",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "15",
		"IBLOCK_TYPE" => "aspro_mshop_content",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"INCLUDE_SUBSECTIONS" => "Y",
		"MESSAGE_404" => "",
		"NEWS_COUNT" => "4",
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
		"PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
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
		"TITLE_BLOCK" => "Действующие акции"
	),
	false
);?>
	</div>
</div>
<?endif;?>
<?if (!empty($lamCfg['SHOW_REVIEWS'])):?>
<!-- LAM OLD: отзывы -->
<div class="grey_bg reviews">
	<div class="wrapper_inner">
		<?$APPLICATION->IncludeComponent(
			"bitrix:news.list", 
			"front_reviews", 
			array(
				"IBLOCK_TYPE" => "aspro_mshop_content",
				"IBLOCK_ID" => CMShopCache::$arIBlocks[SITE_ID]["aspro_mshop_content"]["aspro_mshop_add_review"][0],
				"NEWS_COUNT" => "15",
				"SORT_BY1" => "SORT",
				"SORT_ORDER1" => "ASC",
				"SORT_BY2" => "ID",
				"SORT_ORDER2" => "DESC",
				"FILTER_NAME" => "arFrontLink",
				"FIELD_CODE" => array(
					0 => "PREVIEW_PICTURE",
					1 => "PREVIEW_TEXT",
					2 => "DETAIL_PICTURE",
					3 => "",
				),
				"PROPERTY_CODE" => array(
					// 0 => "POST",
					1 => "RATING",
				),
				"CHECK_DATES" => "Y",
				"DETAIL_URL" => "",
				"AJAX_MODE" => "N",
				"AJAX_OPTION_JUMP" => "N",
				"AJAX_OPTION_STYLE" => "Y",
				"AJAX_OPTION_HISTORY" => "N",
				"CACHE_TYPE" => "A",
				"CACHE_TIME" => "36000000",
				"CACHE_FILTER" => "Y",
				"CACHE_GROUPS" => "N",
				"PREVIEW_TRUNCATE_LEN" => "250",
				"ACTIVE_DATE_FORMAT" => "d F Y",
				"SET_TITLE" => "N",
				"SHOW_DETAIL_LINK" => "N",
				"SET_STATUS_404" => "N",
				"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
				"ADD_SECTIONS_CHAIN" => "N",
				"HIDE_LINK_WHEN_NO_DETAIL" => "N",
				"PARENT_SECTION" => "",
				"PARENT_SECTION_CODE" => "",
				"DISPLAY_TOP_PAGER" => "N",
				"DISPLAY_BOTTOM_PAGER" => "N",
				"PAGER_TITLE" => "",
				"PAGER_SHOW_ALWAYS" => "N",
				"PAGER_TEMPLATE" => "ajax",
				"PAGER_DESC_NUMBERING" => "N",
				"PAGER_DESC_NUMBERING_CACHE_TIME" => "3600",
				"PAGER_SHOW_ALL" => "N",
				"DISPLAY_DATE" => "Y",
				"DISPLAY_NAME" => "Y",
				"DISPLAY_PICTURE" => "N",
				"DISPLAY_PREVIEW_TEXT" => "N",
				"AJAX_OPTION_ADDITIONAL" => "",
				"COMPONENT_TEMPLATE" => "front_review",
				"SET_BROWSER_TITLE" => "N",
				"SET_META_KEYWORDS" => "N",
				"SET_META_DESCRIPTION" => "N",
				"SET_LAST_MODIFIED" => "N",
				"INCLUDE_SUBSECTIONS" => "Y",
				"STRICT_SECTION_CHECK" => "N",
				"TITLE_BLOCK" => "О нас пишут",
				"TITLE_BLOCK_ALL" => "Все отзывы",
				"SHOW_ADD_REVIEW" => "Y",
				"TITLE_ADD_REVIEW" => "Оставить отзыв",
				"ALL_URL" => "company/reviews/",
				"PAGER_BASE_LINK_ENABLE" => "N",
				"SHOW_404" => "N",
				"COMPACT" => "Y",
				"SIZE_IN_ROW" => "2",
				"MESSAGE_404" => ""
			),
			false
		);?>
	</div>
</div>
<?endif;?>
<?if (!empty($lamCfg['SHOW_STORES'])):?>
<!-- LAM OLD: магазины -->
<?if($TEMPLATE_OPTIONS["STORES"]["CURRENT_VALUE"] != 'NO'):?> <?if($TEMPLATE_OPTIONS["STORES_SOURCE"]["CURRENT_VALUE"] != 'IBLOCK'):?> <?$APPLICATION->IncludeComponent(
	"bitrix:catalog.store.list",
	"mshop",
	Array(
		"ALL_URL" => "contacts/stores/",
		"CACHE_TIME" => "86400",
		"CACHE_TYPE" => "A",
		"COMPONENT_TEMPLATE" => "mshop",
		"MAP_TYPE" => "1",
		"PATH_TO_ELEMENT" => "contacts/stores/#store_id#/",
		"PHONE" => "Y",
		"SCHEDULE" => "Y",
		"SEF_MODE" => "N",
		"SET_TITLE" => "N",
		"SITE_THEME" => $SITE_THEME,
		"TITLE" => "",
		"TITLE_BLOCK" => "Наши магазины",
		"TYPE" => "LIGHT"
	)
);?> <?else:?> <?$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"shops_front",
	Array(
		"ACTIVE_DATE_FORMAT" => "j F Y",
		"ADD_SECTIONS_CHAIN" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"CACHE_FILTER" => "Y",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "Y",
		"COMPONENT_TEMPLATE" => "shops_front",
		"DETAIL_URL" => "",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array(0=>"NAME",1=>"",),
		"FILTER_NAME" => "",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "8",
		"IBLOCK_TYPE" => "aspro_mshop_content",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"INCLUDE_SUBSECTIONS" => "Y",
		"MESSAGE_404" => "",
		"NEWS_COUNT" => "100",
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
		"PROPERTY_CODE" => array(0=>"ADDRESS",1=>"PHONE",2=>"",),
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SORT_BY1" => "SORT",
		"SORT_BY2" => "NAME",
		"SORT_ORDER1" => "ASC",
		"SORT_ORDER2" => "ASC",
		"TITLE_BLOCK" => "Наши магазины"
	)
);?> <?endif;?> <?endif;?>
<?endif;?>
