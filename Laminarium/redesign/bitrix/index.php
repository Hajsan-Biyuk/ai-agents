<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("keywords", "Купить ламинат и напольное покрытие в Краснодаре");
$APPLICATION->SetPageProperty("description", "Магазин ламината и напольных покрытий Ламинариум в Краснодаре. Качественный ламинат, винил, SPC ламинат и аксессуары для напольных покрытий с доставкой");
$APPLICATION->SetPageProperty("title", "Ламинат и напольное покрытие в Краснодаре - магазин Ламинариум");
$APPLICATION->SetTitle("Купить ламинат в Краснодаре");
// LAM: новая главная только в предпросмотре (?lam_preview=1)
$lamHome = function_exists("lam_preview") && lam_preview() && is_file($_SERVER["DOCUMENT_ROOT"]."/local/templates/.default/lam/home.php");
if($lamHome){ include $_SERVER["DOCUMENT_ROOT"]."/local/templates/.default/lam/home.php"; } else {

?><?global $SITE_THEME, $TEMPLATE_OPTIONS;?> <?$APPLICATION->IncludeComponent(
	"aspro:com.banners.mshop",
	"top_slider_banners",
	Array(
		"BANNER_TYPE_THEME" => "TOP",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "Y",
		"IBLOCK_ID" => "6",
		"IBLOCK_TYPE" => "aspro_mshop_adv",
		"NEWS_COUNT" => "10",
		"PROPERTY_CODE" => array(0=>"TEXT_POSITION",1=>"TARGETS",2=>"TEXTCOLOR",3=>"URL_STRING",4=>"BUTTON1TEXT",5=>"BUTTON1LINK",6=>"BUTTON2TEXT",7=>"BUTTON2LINK",8=>"",),
		"SET_BANNER_TYPE_FROM_THEME" => "N",
		"SITE_THEME" => $SITE_THEME,
		"SORT_BY1" => "SORT",
		"SORT_BY2" => "ID",
		"SORT_ORDER1" => "ASC",
		"SORT_ORDER2" => "DESC",
		"TYPE_BANNERS_IBLOCK_ID" => "5"
	)
);?>
<div class="wrapper_inner">
	 <?$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"mshop",
	Array(
		"ACTIVE_DATE_FORMAT" => "j F Y",
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
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array(0=>"",1=>"",),
		"FILTER_NAME" => "",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "7",
		"IBLOCK_TYPE" => "aspro_mshop_content",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"INCLUDE_SUBSECTIONS" => "Y",
		"NEWS_COUNT" => "5",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => "",
		"PAGER_TITLE" => "",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"PREVIEW_TRUNCATE_LEN" => "",
		"PROPERTY_CODE" => array(0=>"LINK",1=>"",),
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SORT_BY1" => "SORT",
		"SORT_BY2" => "ID",
		"SORT_ORDER1" => "ASC",
		"SORT_ORDER2" => "DESC"
	)
);?>
</div>
<div class="wrapper_inner wides">
	 <?$APPLICATION->IncludeComponent(
	"aspro:com.banners.mshop",
	"mshop",
	Array(
		"BANNER_TYPE_THEME" => "FLOAT",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "Y",
		"IBLOCK_ID" => "6",
		"IBLOCK_TYPE" => "aspro_mshop_adv",
		"NEWS_COUNT" => "6",
		"PROPERTY_CODE" => array(0=>"TEXT_POSITION",1=>"TARGETS",2=>"TEXTCOLOR",3=>"URL_STRING",4=>"BUTTON1TEXT",5=>"BUTTON1LINK",6=>"BUTTON2TEXT",7=>"BUTTON2LINK",8=>"",),
		"SET_BANNER_TYPE_FROM_THEME" => "N",
		"SITE_THEME" => $SITE_THEME,
		"SORT_BY1" => "SORT",
		"SORT_BY2" => "ID",
		"SORT_ORDER1" => "ASC",
		"SORT_ORDER2" => "DESC",
		"TYPE_BANNERS_IBLOCK_ID" => "5"
	)
);?>
</div>
<div class="grey_bg">
	<div class="wrapper_inner">
		 <?$APPLICATION->IncludeComponent(
	"aspro:tabs.mshop",
	"main",
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
		"COMPONENT_TEMPLATE" => "main",
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
);?>
	</div>
</div>
<div class="wrapper_inner">
	 <?$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"brands_slider",
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
		"FIELD_CODE" => array(0=>"PREVIEW_PICTURE",1=>"",),
		"FILTER_NAME" => "",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "12",
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
);?>
	<hr class="grey">
	<div class="wrap_md">
		<div class="news_wrap">
			 <?$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"news_front",
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
		"COMPONENT_TEMPLATE" => "news_front",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"DETAIL_URL" => "#SITE_DIR#company/news/#YEAR#/#ELEMENT_CODE#/",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array(0=>"",1=>"",),
		"FILTER_NAME" => "",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "14",
		"IBLOCK_TYPE" => "aspro_mshop_content",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"INCLUDE_SUBSECTIONS" => "Y",
		"MESSAGE_404" => "",
		"NEWS_COUNT" => "2",
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
		"TITLE_BLOCK" => "Новости"
	)
);?>
		</div>
		<div class="subscribe_wrap">
			 <?$APPLICATION->IncludeComponent(
	"bitrix:subscribe.form",
	"main",
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
		"COMPONENT_TEMPLATE" => "main",
		"LK" => "Y",
		"PAGE" => SITE_DIR."personal/subscribe/",
		"SET_TITLE" => "N",
		"SHOW_AUTH_LINKS" => "N",
		"SHOW_HIDDEN" => "N",
		"URL_SUBSCRIBE" => SITE_DIR."subscribe/",
		"USE_PERSONALIZATION" => "Y"
	)
);?>
		</div>
	</div>
</div>
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
<?/*
<div class="wrapper_inner">
	 <?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	".default",
	Array(
		"AREA_FILE_RECURSIVE" => "Y",
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "",
		"COMPONENT_TEMPLATE" => ".default",
		"EDIT_TEMPLATE" => "standard.php",
		"PATH" => SITE_DIR."include/mainpage/comp_instagram.php"
	)
);?>
</div>
*/?>
<div class="grey_bg">
	<div class="wrapper_inner">
		<div class="wrap_md">
			<div class="md-50 img">
				 <?$APPLICATION->IncludeFile(SITE_DIR."include/front_img.php", Array(), Array( "MODE" => "html", "NAME" => GetMessage("FRONT_IMG"), )); ?>
			</div>
			<div class="md-50 big">
				 <?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"front",
	Array(
		"AREA_FILE_SHOW" => "file",
		"EDIT_TEMPLATE" => "",
		"PATH" => SITE_DIR."include/front_info.php"
	)
);?>
			</div>
		</div>
	</div>
</div>
<div class="wrapper_inner">
	 <?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	".default",
	Array(
		"AREA_FILE_RECURSIVE" => "Y",
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "",
		"COMPONENT_TEMPLATE" => ".default",
		"EDIT_TEMPLATE" => "standard.php",
		"PATH" => SITE_DIR."include/front_comp_viewed.php"
	)
);?>
</div><?php } ?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>