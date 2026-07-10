<?$APPLICATION->SetPageProperty("HIDE_LEFT_BLOCK", "Y")?>
<?$APPLICATION->SetPageProperty("WITH_LEFT_BLOCK", "")?>

<div class="maxwidth-theme">
<h1><?= $arResult["NAME"] ?></h1>
	<div class="card">
		<div class="product-view product-view--side-left card-img">
			<?$ElementID = $APPLICATION->IncludeComponent(
				"bitrix:catalog.element",
				"main_collection",
				Array(
					"USE_REGION" => ($arRegion ? "Y" : "N"),
					"USE_PREDICTION" => $arParams['USE_DETAIL_PREDICTION'],
					"SECTION_TIZERS"=>$arSection["UF_SECTION_TIZERS"],
					"HELP_TEXT"=>$arSection["UF_HELP_TEXT"],
					"ALT_TITLE_GET" => $arParams["ALT_TITLE_GET"],
					"GRUPPER_PROPS" => $arParams["GRUPPER_PROPS"],
					"USE_CUSTOM_RESIZE" => $arParams["USE_CUSTOM_RESIZE"],
					"SHOW_DISCOUNT_TIME_EACH_SKU" => $arParams["SHOW_DISCOUNT_TIME_EACH_SKU"],
					"SHOW_UNABLE_SKU_PROPS"=>$arParams["SHOW_UNABLE_SKU_PROPS"],
					"SHOW_ARTICLE_SKU" => $arParams["SHOW_ARTICLE_SKU"],
					"SHOW_MEASURE_WITH_RATIO" => $arParams["SHOW_MEASURE_WITH_RATIO"],
					"STORES_FILTER" => ($arParams["STORES_FILTER"] ? $arParams["STORES_FILTER"] : "TITLE"),
					"STORES_FILTER_ORDER" => ($arParams["STORES_FILTER_ORDER"] ? $arParams["STORES_FILTER_ORDER"] : "SORT_ASC"),
					"BUNDLE_ITEMS_COUNT" => $arParams["BUNDLE_ITEMS_COUNT"],
					"WIDE_BLOCK" => $isWideBlock,
					"PICTURE_RATIO" => $sViewPictureDetail,
					"DETAIL_DOCS_PROP"=>$arParams["DETAIL_DOCS_PROP"],
					"SHOW_DISCOUNT_TIME"=>$arParams["SHOW_DISCOUNT_TIME"],
					"TYPE_SKU" => ($typeSKU ? $typeSKU : $arTheme["TYPE_SKU"]["VALUE"]),
					"SEF_URL_TEMPLATES" => $arParams["SEF_URL_TEMPLATES"],
					"IBLOCK_REVIEWS_TYPE" => $arParams["IBLOCK_REVIEWS_TYPE"],
					"IBLOCK_REVIEWS_ID" => $arParams["IBLOCK_REVIEWS_ID"],
					"SHOW_ONE_CLICK_BUY" => $arParams["SHOW_ONE_CLICK_BUY"],
					"SEF_MODE_BRAND_SECTIONS" => $arParams["SEF_MODE_BRAND_SECTIONS"],
					"SEF_MODE_BRAND_ELEMENT" => $arParams["SEF_MODE_BRAND_ELEMENT"],
					"DISPLAY_COMPARE" => CMax::GetFrontParametrValue('CATALOG_COMPARE'),
					"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
					"IBLOCK_ID" => $arParams["IBLOCK_ID"],
					"PROPERTY_CODE" => $arParams["DETAIL_PROPERTY_CODE"],
					"META_KEYWORDS" => $arParams["DETAIL_META_KEYWORDS"],
					"META_DESCRIPTION" => $arParams["DETAIL_META_DESCRIPTION"],
					"BROWSER_TITLE" => $arParams["DETAIL_BROWSER_TITLE"],
					"BASKET_URL" => $arParams["BASKET_URL"],
					"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
					"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
					"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
					"DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
					"CACHE_TYPE" => $arParams["CACHE_TYPE"],
					"CACHE_TIME" => $arParams["CACHE_TIME"],
					"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
					"SET_TITLE" => $arParams["SET_TITLE"],
					'OFFER_SHOW_PREVIEW_PICTURE_PROPS' => $arParams['OFFER_SHOW_PREVIEW_PICTURE_PROPS'],
					"SHOW_CHEAPER_FORM" => $arParams["SHOW_CHEAPER_FORM"],
					"SET_CANONICAL_URL" => $arParams["DETAIL_SET_CANONICAL_URL"],
					"SET_LAST_MODIFIED" => "Y",
					"SET_STATUS_404" => $arParams["SET_STATUS_404"],
					"MESSAGE_404" => $arParams["MESSAGE_404"],
					"SHOW_404" => $arParams["SHOW_404"],
					"FILE_404" => $arParams["FILE_404"],
					"SORT_REGION_PRICE" => $arParams["SORT_REGION_PRICE"],
					"PRICE_CODE" => $arParams['PRICE_CODE'],
					"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
					"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
					"USE_RATIO_IN_RANGES" => $arParams["USE_RATIO_IN_RANGES"],
					"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
					"PRICE_VAT_SHOW_VALUE" => $arParams["PRICE_VAT_SHOW_VALUE"],
					"LINK_IBLOCK_TYPE" => $arParams["LINK_IBLOCK_TYPE"],
					"LINK_IBLOCK_ID" => $arParams["LINK_IBLOCK_ID"],
					"LINK_PROPERTY_SID" => $arParams["LINK_PROPERTY_SID"],
					"LINK_ELEMENTS_URL" => $arParams["LINK_ELEMENTS_URL"],
					"USE_ALSO_BUY" => $arParams["USE_ALSO_BUY"],
					'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
					'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
					"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
					"OFFERS_FIELD_CODE" => $arParams["DETAIL_OFFERS_FIELD_CODE"],
					"OFFERS_PROPERTY_CODE" => $arParams["DETAIL_OFFERS_PROPERTY_CODE"],
					"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
					"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
					"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
					"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
					"LINKED_ELEMENT_TAB_SORT_FIELD" => $arParams["LINKED_ELEMENT_TAB_SORT_FIELD"],
					"LINKED_ELEMENT_TAB_SORT_ORDER" => $arParams["LINKED_ELEMENT_TAB_SORT_ORDER"],
					"LINKED_ELEMENT_TAB_SORT_FIELD2" => $arParams["LINKED_ELEMENT_TAB_SORT_FIELD2"],
					"LINKED_ELEMENT_TAB_SORT_ORDER2" => $arParams["LINKED_ELEMENT_TAB_SORT_ORDER2"],
					"SKU_DETAIL_ID" => $arParams["SKU_DETAIL_ID"],
					"SKU_DISPLAY_LOCATION" => $arParams["SKU_DISPLAY_LOCATION"],
					"ELEMENT_ID" => $arResult["VARIABLES"]["ELEMENT_ID"],
					"ELEMENT_CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"],
					"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
					"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
					"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
					"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
					"ADD_SECTIONS_CHAIN" => $arParams["ADD_SECTIONS_CHAIN"],
					"ADD_ELEMENT_CHAIN" => $arParams["ADD_ELEMENT_CHAIN"],
					"USE_STORE" => $arParams["USE_STORE"],
					"USE_STORE_PHONE" => $arParams["USE_STORE_PHONE"],
					"USE_STORE_SCHEDULE" => $arParams["USE_STORE_SCHEDULE"],
					"USE_MIN_AMOUNT" => $arParams["USE_MIN_AMOUNT"],
					"MIN_AMOUNT" => $arParams["MIN_AMOUNT"],
					"STORE_PATH" => $arParams["STORE_PATH"],
					"MAIN_TITLE" => $arParams["MAIN_TITLE"],
					"USE_PRODUCT_QUANTITY" => $arParams["USE_PRODUCT_QUANTITY"],
					"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
					"BLOG_URL" => $arParams["BLOG_URL"],

					"IBLOCK_LINK_SALE_ID" => $arParams["IBLOCK_STOCK_ID"],
					"IBLOCK_LINK_NEWS_ID" => $arParams["IBLOCK_LINK_NEWS_ID"],
					"IBLOCK_SERVICES_ID" => $arParams["IBLOCK_SERVICES_ID"],
					"IBLOCK_LINK_REVIEWS_ID" => $arParams["IBLOCK_LINK_REVIEWS_ID"],
					"IBLOCK_LINK_BLOG_ID" => $arParams["BLOG_IBLOCK_ID"],
					"IBLOCK_TIZERS_ID" => $arParams["IBLOCK_TIZERS_ID"],
					"IBLOCK_LINK_STAFF_ID" => $arParams["STAFF_IBLOCK_ID"],
					"IBLOCK_LINK_VACANCY_ID" => $arParams["VACANCY_IBLOCK_ID"],

					"SEF_MODE_STOCK_SECTIONS" => $arParams["SEF_MODE_STOCK_SECTIONS"],
					"SHOW_QUANTITY" => $arParams["SHOW_QUANTITY"],
					"SHOW_QUANTITY_COUNT" => $arParams["SHOW_QUANTITY_COUNT"],
					"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
					"CURRENCY_ID" => $arParams["CURRENCY_ID"],
					'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
					'HIDE_NOT_AVAILABLE_OFFERS' => $arParams["HIDE_NOT_AVAILABLE_OFFERS"],
					'SHOW_DEACTIVATED' => $arParams['SHOW_DEACTIVATED'],
					"USE_ELEMENT_COUNTER" => $arParams["USE_ELEMENT_COUNTER"],
					"STAFF_VIEW_TYPE" => ($arParams["STAFF_VIEW_TYPE"] ? $arParams["STAFF_VIEW_TYPE"] : "staff_block"),
					'STRICT_SECTION_CHECK' => (isset($arParams['DETAIL_STRICT_SECTION_CHECK']) ? $arParams['DETAIL_STRICT_SECTION_CHECK'] : ''),
					'RELATIVE_QUANTITY_FACTOR' => (isset($arParams['RELATIVE_QUANTITY_FACTOR']) ? $arParams['RELATIVE_QUANTITY_FACTOR'] : ''),
					
					"DETAIL_USE_COMMENTS" => (isset($arParams['DETAIL_USE_COMMENTS']) ? $arParams['DETAIL_USE_COMMENTS'] : 'N'),
					"COMMENTS_COUNT" => (isset($arParams['COMMENTS_COUNT']) ? $arParams['COMMENTS_COUNT'] : '5'),
					"DETAIL_BLOG_EMAIL_NOTIFY" => (isset($arParams['DETAIL_BLOG_EMAIL_NOTIFY']) ? $arParams['DETAIL_BLOG_EMAIL_NOTIFY'] : 'Y'),
					"MAX_IMAGE_SIZE" => (isset($arParams['MAX_IMAGE_SIZE']) ? $arParams['MAX_IMAGE_SIZE'] : '0.5'),

					"USE_RATING" => $arParams["USE_RATING"],
					"USE_REVIEW" => $arParams["USE_REVIEW"],
					"REVIEWS_VIEW" => $arTheme["REVIEWS_VIEW"]["VALUE"],
					"REVIEW_COMMENT_REQUIRED" => $arParams["REVIEW_COMMENT_REQUIRED"],
					"REVIEW_FILTER_BUTTONS" => $arParams["REVIEW_FILTER_BUTTONS"],
					"REAL_CUSTOMER_TEXT" => $arParams["REAL_CUSTOMER_TEXT"],
					"FORUM_ID" => $arParams["FORUM_ID"],
					"MESSAGES_PER_PAGE" => $arParams["MESSAGES_PER_PAGE"],
					"MAX_AMOUNT" => $arParams["MAX_AMOUNT"],
					"USE_ONLY_MAX_AMOUNT" => $arParams["USE_ONLY_MAX_AMOUNT"],
					"DISPLAY_WISH_BUTTONS" => $arParams["DISPLAY_WISH_BUTTONS"],
					"DEFAULT_COUNT" => $arParams["DEFAULT_COUNT"],
					"SHOW_BRAND_PICTURE" => $arParams["SHOW_BRAND_PICTURE"],
					"PROPERTIES_DISPLAY_LOCATION" => $arParams["PROPERTIES_DISPLAY_LOCATION"],
					"PROPERTIES_DISPLAY_TYPE" => $arParams["PROPERTIES_DISPLAY_TYPE"],
					"SHOW_ADDITIONAL_TAB" => $arParams["SHOW_ADDITIONAL_TAB"],
					"SHOW_ASK_BLOCK" => $arParams["SHOW_ASK_BLOCK"],
					"ASK_FORM_ID" => $arParams["ASK_FORM_ID"],
					"SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
					"SHOW_HINTS" => $arParams["SHOW_HINTS"],
					"OFFER_HIDE_NAME_PROPS" => $arParams["OFFER_HIDE_NAME_PROPS"],
					"SHOW_KIT_PARTS" => $arParams["SHOW_KIT_PARTS"],
					"SHOW_KIT_PARTS_PRICES" => $arParams["SHOW_KIT_PARTS_PRICES"],
					"SHOW_KIT_ALL" => $arParams['SHOW_KIT_ALL'],
					"SHOW_DISCOUNT_PERCENT_NUMBER" => $arParams["SHOW_DISCOUNT_PERCENT_NUMBER"],
					"SHOW_DISCOUNT_PERCENT" => $arParams["SHOW_DISCOUNT_PERCENT"],
					"SHOW_OLD_PRICE" => $arParams["SHOW_OLD_PRICE"],
					'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
					'ADD_DETAIL_TO_SLIDER' => (isset($arParams['DETAIL_ADD_DETAIL_TO_SLIDER']) ? $arParams['DETAIL_ADD_DETAIL_TO_SLIDER'] : ''),
					"SHOW_EMPTY_STORE" => $arParams['SHOW_EMPTY_STORE'],
					"SHOW_GENERAL_STORE_INFORMATION" => $arParams['SHOW_GENERAL_STORE_INFORMATION'],
					"USER_FIELDS" => $arParams['USER_FIELDS'],
					"FIELDS" => $arParams['FIELDS'],
					"STORES" => $arParams['STORES'],
					"BIG_DATA_RCM_TYPE" => $arParams['BIG_DATA_RCM_TYPE'],
					"USE_BIG_DATA" => $arParams['USE_BIG_DATA'],
					"USE_MAIN_ELEMENT_SECTION" => $arParams["USE_MAIN_ELEMENT_SECTION"],
					"PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
					"ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
					"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
					"SALE_STIKER" => $arParams["SALE_STIKER"],
					"STIKERS_PROP" => $arParams["STIKERS_PROP"],
					"SHOW_RATING" => $arParams["SHOW_RATING"],

					"MAX_GALLERY_ITEMS" => $arParams["MAX_GALLERY_ITEMS"],
					"SHOW_GALLERY" => $arParams["SHOW_GALLERY"],
					"SHOW_PROPS" => (CMax::GetFrontParametrValue("SHOW_PROPS_BLOCK") == "Y" ? "Y" : "N"),
					'SHOW_POPUP_PRICE' => (CMax::GetFrontParametrValue('SHOW_POPUP_PRICE') == 'Y' ? "Y" : "N"),

					"OFFERS_LIMIT" => $arParams["DETAIL_OFFERS_LIMIT"],

					'SHOW_BASIS_PRICE' => (isset($arParams['DETAIL_SHOW_BASIS_PRICE']) ? $arParams['DETAIL_SHOW_BASIS_PRICE'] : 'Y'),
					"DETAIL_PICTURE_MODE" => (isset($arTheme["DETAIL_PICTURE_MODE"]["VALUE"]) ? $arTheme["DETAIL_PICTURE_MODE"]["VALUE"] : 'POPUP'),

					'DISABLE_INIT_JS_IN_COMPONENT' => (isset($arParams['DISABLE_INIT_JS_IN_COMPONENT']) ? $arParams['DISABLE_INIT_JS_IN_COMPONENT'] : ''),
					'COMPATIBLE_MODE' => (isset($arParams['COMPATIBLE_MODE']) ? $arParams['COMPATIBLE_MODE'] : ''),
					'SET_VIEWED_IN_COMPONENT' => (isset($arParams['DETAIL_SET_VIEWED_IN_COMPONENT']) ? $arParams['DETAIL_SET_VIEWED_IN_COMPONENT'] : ''),

					'SHOW_SLIDER' => (isset($arParams['DETAIL_SHOW_SLIDER']) ? $arParams['DETAIL_SHOW_SLIDER'] : ''),
					'SLIDER_INTERVAL' => (isset($arParams['DETAIL_SLIDER_INTERVAL']) ? $arParams['DETAIL_SLIDER_INTERVAL'] : ''),
					'SLIDER_PROGRESS' => (isset($arParams['DETAIL_SLIDER_PROGRESS']) ? $arParams['DETAIL_SLIDER_PROGRESS'] : ''),
					'USE_ENHANCED_ECOMMERCE' => (isset($arParams['USE_ENHANCED_ECOMMERCE']) ? $arParams['USE_ENHANCED_ECOMMERCE'] : ''),
					'DATA_LAYER_NAME' => (isset($arParams['DATA_LAYER_NAME']) ? $arParams['DATA_LAYER_NAME'] : ''),
					// 'SHOW_POPUP_PRICE' => (CMax::GetFrontParametrValue('SHOW_POPUP_PRICE') == 'Y'),
					'GALLERY_THUMB_POSITION' => CMax::GetFrontParametrValue('CATALOG_PAGE_DETAIL_THUMBS'),


					"USE_GIFTS_DETAIL" => $arParams['USE_GIFTS_DETAIL']?: 'Y',
					"USE_GIFTS_MAIN_PR_SECTION_LIST" => $arParams['USE_GIFTS_MAIN_PR_SECTION_LIST']?: 'Y',
					"GIFTS_SHOW_DISCOUNT_PERCENT" => $arParams['GIFTS_SHOW_DISCOUNT_PERCENT'],
					"GIFTS_SHOW_OLD_PRICE" => $arParams['GIFTS_SHOW_OLD_PRICE'],
					"GIFTS_DETAIL_PAGE_ELEMENT_COUNT" => $arParams['GIFTS_DETAIL_PAGE_ELEMENT_COUNT'],
					"GIFTS_DETAIL_HIDE_BLOCK_TITLE" => $arParams['GIFTS_DETAIL_HIDE_BLOCK_TITLE'],
					"GIFTS_DETAIL_TEXT_LABEL_GIFT" => $arParams['GIFTS_DETAIL_TEXT_LABEL_GIFT'],
					"GIFTS_DETAIL_BLOCK_TITLE" => $arParams["GIFTS_DETAIL_BLOCK_TITLE"],
					"GIFTS_SHOW_NAME" => $arParams['GIFTS_SHOW_NAME'],
					"GIFTS_SHOW_IMAGE" => $arParams['GIFTS_SHOW_IMAGE'],
					"GIFTS_MESS_BTN_BUY" => $arParams['GIFTS_MESS_BTN_BUY'],
					"VISIBLE_PROP_COUNT" => $arParams['VISIBLE_PROP_COUNT'],
					"SHOW_SEND_GIFT" => $arParams['SHOW_SEND_GIFT'],
					"SEND_GIFT_FORM_NAME" => $arParams['SEND_GIFT_FORM_NAME'],

					"GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT" => $arParams['GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT'],
					"GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE" => $arParams['GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE'],

					"TAB_OFFERS_NAME" => ($arParams["TAB_OFFERS_NAME"] ? $arParams["TAB_OFFERS_NAME"] : GetMessage("OFFER_PRICES")),
					"TAB_VIDEO_NAME" => ($arParams["TAB_VIDEO_NAME"] ? $arParams["TAB_VIDEO_NAME"] : GetMessage("VIDEO_TAB")),
					"TAB_BUY_SERVICES_NAME" => ($arParams["TAB_BUY_SERVICES_NAME"] ? $arParams["TAB_BUY_SERVICES_NAME"] : GetMessage("BUY_SERVICES_TAB")),
					"TAB_REVIEW_NAME" => ($arParams["TAB_REVIEW_NAME"] ? $arParams["TAB_REVIEW_NAME"] : GetMessage("REVIEW_TAB")),
					"TAB_FAQ_NAME" => $arParams["TAB_FAQ_NAME"],
					"TAB_STOCK_NAME" => ($arParams["TAB_STOCK_NAME"] ? $arParams["TAB_STOCK_NAME"] : GetMessage("STORES_TAB")),
					"TAB_NEWS_NAME" => ($arParams["TAB_NEWS_NAME"] ? $arParams["TAB_NEWS_NAME"] : GetMessage("TAB_NEWS_NAME")),
					"TAB_DOPS_NAME" => ($arParams["TAB_DOPS_NAME"] ? $arParams["TAB_DOPS_NAME"] : GetMessage("ADDITIONAL_TAB")),
					"TAB_STAFF_NAME" => ($arParams["TAB_STAFF_NAME"] ? $arParams["TAB_STAFF_NAME"] : GetMessage("TAB_STAFF_NAME")),
					"TAB_VACANCY_NAME" => ($arParams["TAB_VACANCY_NAME"] ? $arParams["TAB_VACANCY_NAME"] : GetMessage("TAB_VACANCY_NAME")),
					"TAB_BLOG_NAME" => ($arParams["BLOCK_BLOG_NAME"] ? $arParams["BLOCK_BLOG_NAME"] : GetMessage("TAB_BLOG_NAME")),
					"BLOCK_SERVICES_NAME" => ($arParams["BLOCK_SERVICES_NAME"] ? $arParams["BLOCK_SERVICES_NAME"] : GetMessage("SERVICES_TITLE")),
					"BLOCK_DOCS_NAME" => ($arParams["BLOCK_DOCS_NAME"] ? $arParams["BLOCK_DOCS_NAME"] : GetMessage("DOCUMENTS_TITLE")),
					"CHEAPER_FORM_NAME" => $arParams["CHEAPER_FORM_NAME"],
					"USE_ADDITIONAL_GALLERY" => $arParams["USE_ADDITIONAL_GALLERY"],
					"ADDITIONAL_GALLERY_TYPE" => $arParams["ADDITIONAL_GALLERY_TYPE"],
					"ADDITIONAL_GALLERY_PROPERTY_CODE" => $arParams["ADDITIONAL_GALLERY_PROPERTY_CODE"],
					"ADDITIONAL_GALLERY_OFFERS_PROPERTY_CODE" => $arParams["ADDITIONAL_GALLERY_OFFERS_PROPERTY_CODE"],
					"BLOCK_ADDITIONAL_GALLERY_NAME" => ($arParams["BLOCK_ADDITIONAL_GALLERY_NAME"] ? $arParams["BLOCK_ADDITIONAL_GALLERY_NAME"] : GetMessage("BLOCK_ADDITIONAL_GALLERY_NAME")),

					"T_KOMPLECT" => $arParams["TAB_KOMPLECT_NAME"],
					"T_NABOR" => $arParams["TAB_NABOR_NAME"],
					"T_DESC" => $arParams["TAB_DESCR_NAME"],
					"T_CHARACTERISTICS" => $arParams["TAB_CHAR_NAME"],

					"DETAIL_LINKED_GOODS_SLIDER" => $arParams["DETAIL_LINKED_GOODS_SLIDER"],
					"DETAIL_LINKED_GOODS_TABS" => $arParams["DETAIL_LINKED_GOODS_TABS"],

					"DETAIL_ASSOCIATED_TITLE" => $arParams["DETAIL_ASSOCIATED_TITLE"],
					"DETAIL_EXPANDABLES_TITLE" => $arParams["DETAIL_EXPANDABLES_TITLE"],

					"LINKED_FILTER_BY_PROP" => $arAllValues,
					"LINKED_FILTER_BY_FILTER" => $arTab,
					"LINKED_BLOG" => $linkedArticles,

					"SHOW_PAYMENT" => (isset($arParams["SHOW_PAYMENT"]) ? $arParams["SHOW_PAYMENT"] : "Y"),
					"SHOW_DELIVERY" => (isset($arParams["SHOW_DELIVERY"]) ? $arParams["SHOW_DELIVERY"] : "Y"),
					"SHOW_HOW_BUY" => (isset($arParams["SHOW_HOW_BUY"]) ? $arParams["SHOW_HOW_BUY"] : "Y"),
					"TITLE_HOW_BUY" => ($arParams["TITLE_HOW_BUY"] ? $arParams["TITLE_HOW_BUY"] : GetMessage("TITLE_HOW_BUY")),
					"TITLE_DELIVERY" => ($arParams["TITLE_DELIVERY"] ? $arParams["TITLE_DELIVERY"] : GetMessage("TITLE_DELIVERY")),
					"TITLE_PAYMENT" => ($arParams["TITLE_PAYMENT"] ? $arParams["TITLE_PAYMENT"] : GetMessage("TITLE_PAYMENT")),
					"TITLE_SLIDER" => $arParams['TITLE_SLIDER'],
					"DISPLAY_ELEMENT_SLIDER" => $arParams['DISPLAY_ELEMENT_SLIDER'],

					"CALCULATE_DELIVERY" => $arTheme["CALCULATE_DELIVERY"]["VALUE"],
					"EXPRESSION_FOR_CALCULATE_DELIVERY" => $arTheme["EXPRESSION_FOR_CALCULATE_DELIVERY"]["VALUE"],

					"DETAIL_BLOCKS_ORDER" => ($arParams["DETAIL_BLOCKS_ORDER"] ? $arParams["DETAIL_BLOCKS_ORDER"] : 'complect,nabor,offers,tabs,services,news,blog,staff,vacancy,goods'),
					"DETAIL_BLOCKS_TAB_ORDER" => ($arParams["DETAIL_BLOCKS_TAB_ORDER"] ? $arParams["DETAIL_BLOCKS_TAB_ORDER"] : 'desc,char,buy,payment,delivery,video,stores,reviews,custom_tab,buy_services'),
					"DETAIL_BLOCKS_ALL_ORDER" => ($arParams["DETAIL_BLOCKS_ALL_ORDER"] ? $arParams["DETAIL_BLOCKS_ALL_ORDER"] : 'complect,nabor,offers,desc,char,buy,payment,delivery,video,stores,custom_tab,services,news,reviews,blog,staff,vacancy,goods,buy_services'),
					"COUNT_SERVICES_IN_ANNOUNCE" => (isset($arParams["COUNT_SERVICES_IN_ANNOUNCE"]) ? $arParams["COUNT_SERVICES_IN_ANNOUNCE"] : '2') ,
					"SHOW_ALL_SERVICES_IN_SLIDE" => (isset($arParams["SHOW_ALL_SERVICES_IN_SLIDE"]) ? $arParams["SHOW_ALL_SERVICES_IN_SLIDE"] : 'N') ,
					"FILTER_NAME" => $arParams["FILTER_NAME"],
					"DISPLAY_LINKED_PAGER" => ($arParams["DETAIL_LINKED_GOODS_SLIDER"] == "Y") ? "N" : $arParams["DISPLAY_LINKED_PAGER"],
					"DETAIL_SET_PRODUCT_TITLE" => ($arParams["DETAIL_SET_PRODUCT_TITLE"] ? $arParams["DETAIL_SET_PRODUCT_TITLE"] : GetMessage("DETAIL_SET_PRODUCT")),
					"LIST_OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
					"LIST_OFFERS_PROPERTY_CODE" => $arParams["LIST_OFFERS_PROPERTY_CODE"],
					"LIST_OFFERS_LIMIT" => $arParams["LIST_OFFERS_LIMIT"],
					"MODULES_ELEMENT_COUNT" => $arParams["MODULES_ELEMENT_COUNT"],
					"OID" => $arParams["OID"],
					"SHOW_SKU_DESCRIPTION" => $arParams["SHOW_SKU_DESCRIPTION"],
					"VISIBLE_PROP_WITH_OFFER" => $arParams["VISIBLE_PROP_WITH_OFFER"],
				),
				$component
			);?>
		</div>
		<div class="card-info left_block main_item_wrapper product-main <?=($arParams["SHOW_UNABLE_SKU_PROPS"] != "N" ? "show_un_props" : "unshow_un_props");?>">
			<?$APPLICATION->ShowViewContent('PRODUCT_SIDE_INFO')?>

			<?//bigdata?>
			<?if($arParams['USE_BIG_DATA'] == 'Y'):?>
				<?
				$GLOBALS['CATALOG_CURRENT_ELEMENT_ID'] = $ElementID;

				$GLOBALS['arrFilterBigData']['IBLOCK_ID'] = $arParams['IBLOCK_ID'];
				CMax::makeElementFilterInRegion($GLOBALS['arrFilterBigData']);
				?>			
			<?endif;?>

			<?//feedback?>
			<?if($arParams['SHOW_ASK_BLOCK'] == 'Y'):?>
				<div class="side-block side-block--feedback rounded2 bordered box-shadow colored_theme_hover_bg-block">
					<div class="side-block__top text-center">
						<?=CMax::showIconSvg("icon colored", SITE_TEMPLATE_PATH.'/images/svg/catalog/ask_question.svg', '', '', true, false);?>
						<div class="side-block__text side-block__text--small">
							<?$APPLICATION->IncludeComponent(
								"bitrix:main.include",
								"",
								Array(
									"AREA_FILE_SHOW" => "page",
									"AREA_FILE_SUFFIX" => "feedback",
									"EDIT_TEMPLATE" => ""
								)
							);?>
						</div>
					</div>
					<div class="side-block__bottom side-block__bottom--last">
						<span class="btn btn-lg btn-transparent btn-wide round-ignore font_upper animate-load colored_theme_hover_bg-el has-ripple" data-event="jqm" data-param-form_id="ASK" data-name="ask"><?=($arParams["ASK_TAB"] ? $arParams["ASK_TAB"] : GetMessage("ASK_TAB"))?></span>
					</div>
				</div>
			<?endif;?>
		</div>
		
	</div>
</div>
<?
use \Bitrix\Main\Data\Cache;
$cache = Cache::createInstance();

$cachePath = 'collection_cache_key_path'; // папка, в которой лежит кеш
$cacheTtl = 3600; // срок годности кеша (в секундах)
$cacheKey = 'collection_cache_key' . $arParams['IBLOCK_ID'] . $arResult['VARIABLES']['ELEMENT_ID'] ; // имя кеша

if ($cache->initCache($cacheTtl, $cacheKey, $cachePath)) {

    $collection_property = $cache->getVars();

} elseif ($cache->startDataCache()) {

	$props_val = [];
	$property_enums = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID"=>61, "CODE"=>"PROP_1240"));
	while($enum_fields = $property_enums->GetNext())
	{
		$props_val[$enum_fields["ID"]] = $enum_fields["VALUE"];
	//   echo $enum_fields["ID"]." - ".$enum_fields["VALUE"]."<br>";
	}
	
	$res = CIBlockElement::GetProperty($arParams['IBLOCK_ID'], $arResult['VARIABLES']['ELEMENT_ID'], array("sort" => "asc"), array("CODE" => "collection_connect"));
	while ($ob = $res->GetNext()) {
	
		// echo '<pre>'; print_r($ob); echo '</pre>';
		if(!empty($props_val[$ob['VALUE']])) $collection_property[] = $props_val[$ob['VALUE']];
	
	}

    $cache->endDataCache($collection_property);
}
// print_r($collection_property);
// $iterator = CIBlockElement::GetPropertyValues(61, array('ACTIVE' => 'Y'), true, array('ID' => array(1240)));
// while ($row = $iterator->Fetch())
// {
//   echo '<pre>'; print_r($row); echo '</pre>';
// }

global $arrFilterElementsCollection;
$arrFilterElementsCollection = array('PROPERTY_PROP_1240_VALUE' => $collection_property);			

// собираем св-ва для фильтра
if(!empty($collection_property)) {


	$arFilterColl = Array("IBLOCK_ID" => 61, "PROPERTY_PROP_1240_VALUE" => $collection_property);
	$resColl = CIBlockElement::GetList(Array(), $arFilterColl);
	
	while(($obCol = $resColl->GetNextElement())){
	
	// if ($obCol = $resColl->GetNextElement()){
		$arFields = $obCol->GetFields(); // поля элемента
		// echo '<pre>'; print_r($arFields); echo '</pre>';		
		
		if($arFields['IBLOCK_SECTION_ID'] > 0) {
			$query_section = CIBlockSection::GetByID($arFields['IBLOCK_SECTION_ID']);
			if($arSectionResult = $query_section->GetNext())
				if(!$sections[$arFields['IBLOCK_SECTION_ID']]) $sections[$arFields['IBLOCK_SECTION_ID']] = ["ID" => $arFields['IBLOCK_SECTION_ID'], "NAME" => $arSectionResult['NAME']];
		}
	
		// $picture = (CFile::GetPath($arFields["PREVIEW_PICTURE"]));
		$arProps = $obCol->GetProperties(); // свойства элемента
			// echo '<pre>'; print_r($arProps['PROP_2033']); echo '</pre>';
		if($arProps['PROP_2065']['VALUE']) $sizes[$arProps['PROP_2065']['VALUE']] = array("ID" => $arProps['PROP_2065']['PROPERTY_VALUE_ID'], "VALUE" => $arProps['PROP_2065']['VALUE']);
		if($arProps['PROP_2033']['VALUE']) $types[$arProps['PROP_2033']['VALUE']] = array("ID" => $arProps['PROP_2033']['PROPERTY_VALUE_ID'], "VALUE" => $arProps['PROP_2033']['VALUE']); //$arProps['PROP_2033']['PROPERTY_VALUE_ID'];		
			
	}
}

// echo '<pre>';
// print_r($types);
// echo '</pre>';
?>
<?//$APPLICATION->ShowViewContent('PRODUCT_INFO_BOTTOM')?>
<div class="card-tab">
	<div class="card-tab__head">
		<div class="iq-container">
			<div class="card-tab__head__wrap">
				<div class="card-tab__head__item card-tab__head__item--selected" data-card="1">Товары</div>
				<div class="card-tab__head__item" data-card="2">описание</div>
				<div class="card-tab__head__item" data-card="3">оплата</div>
				<div class="card-tab__head__item" data-card="4">доставка</div> 
				<div class="card-tab__head__item" data-card="5">отзывы</div>
			</div>
		</div>
	</div>
	
	<div class="card-tab__body EXTENDED">
		<div class="iq-container tabs-block">
			<div class="card-tab__body__item card-tab-products" data-card="1" style="display: block;">
				<!-------------------------------------------------------------------->
				<?if(!empty($sections) || !empty($types)):?>
					<div class="iq-chosen">
						<?//if(!empty($sections)):?>	
							<div class="iq-chosen-item iq-chosen-item-detected filter-section">
								<div class="iq-chosen-item__text">Тип товара:</div>
								<div class="iq-chosen-item__select">
									<select name="types_filter" class="chosen-select chosen-select-system" data-placeholder="Все значения" multiple>
										<?foreach($types as $t):?>
											<option value="<?=$t['ID']?>"><?=$t['VALUE']?></option>
										<?endforeach?>
									</select>
								</div>
							</div>
						<?//endif;?>
						<?if(!empty($sizes)):?>	
							<div class="iq-chosen-item iq-chosen-item-detected filter-size">
								<div class="iq-chosen-item__text">Размеры:</div>
								<div class="iq-chosen-item__select">
									<select name="size_filter" class="chosen-select chosen-select-system" data-placeholder="Все значения" multiple>
										<?foreach($sizes as $t):?>
											<option value="<?=$t['ID']?>"><?=$t['VALUE']?></option>
										<?endforeach?>							
									</select>
								</div>
							</div>
						<?endif;?>
						<div class="chosen-select-clear">Сбросить фильтры</div>
					</div>
				<?endif;?>
				<!-------------------------------------------------------------------->				
				<!-- load collection -->
		
				<?if(!empty($collection_property) && $sections):?>
					<?

					$sort_array = [
						'Плитка настенная',
						'Декор',
						'Бордюр',
						'Панно',
						'Мозаика',
						'Плинтус',
						'Ступень прямая',
						'Плитка напольная',
						'Керамогранит',
						'2 стакана',
						'1 стакан',
						'Без перелива',
						'Биде',
						'Бордюр',
						'Ванна',
						'Водяной',
						'Врезные на борт ванны',
						'Декор',
						'Держатели для уголков и дверей',
						'Держатель лейки с шлаговым соединением',
						'Душевая дверь',
						'Душевая система',
						'Душевая шторка на ванну',
						'Душевое ограждение',
						'Душевые гарнитуры',
						'Зеркало',
						'Зеркальные шкафы',
						'Инсталляции для унитаза',
						'Инсталляции с клавишей',
						'Каркас',
						'Керамогранит',
						'Клавиши к инсталляциям',
						'Комод',
						'Комплекты крепления',
						'Крепежи',
						'Лейка',
						'Мозаика',
						'Напольное биде',
						'Напольный унитаз',
						'Ножки',
						'Ножки для поддонов',
						'Панель',
						'Панно',
						'Пенал',
						'Плинтус',
						'Плитка напольная',
						'Плитка настенная',
						'Подвесное биде',
						'Подвесной унитаз',
						'Прочее',
						'Поддон',
						'Раковина-чаша',
						'Регулирующий профиль',
						'С переливом',
						'Сантехника',
						'Смеситель для ванны',
						'Сифоны для поддонов',
						'Смеситель для раковины',
						'Смеситель для кухни',
						'Смеситель для душа',
						'Соединяющий профиль',
						'Смеситель с гигиеническим душем',
						'Смеситель Смеситель для раковины-чаши',
						'Стекла для уголков',
						'Ступень прямая',
						'Столешница',
						'Тумбы с раковиной',
						'Унитаз-биде -2',
						'Шкаф подвесной',
						'Шланг',
						'Унитазы',
						'Электрический (Сухой)',
						'Электрический (ТЭН)',
						'Экраны для поддонов',						
					];					
				

				$sort_box = [];
				foreach ($sort_array as $sort_item) {
					if(!empty($types[$sort_item]))
						$sort_box[$sort_item] = $types[$sort_item];
				}
				$types = $sort_box;

				?>	
					<?foreach($types as $c => $t):?>
						<div class="segment-filter-section" data-section="<?=$t['VALUE']?>">
							<div class="card-tab-title">
								<div class="card-tab-title__text"><?=$t['VALUE']?></div>
								<div class="card-tab-title__counter"></div>
							</div>

							<div class="card-tab-related">
								<?
								global $arrFilterElementsCollection;
								$arrFilterElementsCollection = array('PROPERTY_PROP_1240_VALUE' => $collection_property, "PROPERTY_PROP_2033" => $t['VALUE'] );
								// print_r($arrFilterElementsCollection);
								?>
								<?$APPLICATION->IncludeComponent(
										"bitrix:catalog.section",
										"catalog_block",
										Array(
											"ACTION_VARIABLE" => "action",
											"ADD_PICT_PROP" => "MORE_PHOTO",
											"ADD_PROPERTIES_TO_BASKET" => "Y",
											"ADD_SECTIONS_CHAIN" => "N",
											"ADD_TO_BASKET_ACTION" => "ADD",
											"AJAX_MODE" => "N",
											"AJAX_OPTION_ADDITIONAL" => "",
											"AJAX_OPTION_HISTORY" => "N",
											"AJAX_OPTION_JUMP" => "N",
											"AJAX_OPTION_STYLE" => "Y",
											"BACKGROUND_IMAGE" => "UF_BACKGROUND_IMAGE",
											"BASKET_URL" => "/personal/basket.php",
											"BRAND_PROPERTY" => "BRAND_REF",
											"BROWSER_TITLE" => "-",
											"CACHE_FILTER" => "N",
											"CACHE_GROUPS" => "Y",
											"CACHE_TIME" => "36000000",
											"CACHE_TYPE" => "A",
											"COMPATIBLE_MODE" => "Y",
											"CONVERT_CURRENCY" => "Y",
											"CURRENCY_ID" => "RUB",
											"CUSTOM_FILTER" => "",
											"DATA_LAYER_NAME" => "dataLayer",
											"DETAIL_URL" => "",
											"DISABLE_INIT_JS_IN_COMPONENT" => "N",
											"DISCOUNT_PERCENT_POSITION" => "bottom-right",
											"DISPLAY_BOTTOM_PAGER" => "N",
											"DISPLAY_TOP_PAGER" => "N",
											"ELEMENT_SORT_FIELD" => "sort",
											"ELEMENT_SORT_FIELD2" => "id",
											"ELEMENT_SORT_ORDER" => "asc",
											"ELEMENT_SORT_ORDER2" => "desc",
											"ENLARGE_PRODUCT" => "PROP",
											"ENLARGE_PROP" => "NEWPRODUCT",
											"FILTER_NAME" => "arrFilterElementsCollection",
											"HIDE_NOT_AVAILABLE" => "N",
											"HIDE_NOT_AVAILABLE_OFFERS" => "N",
											"IBLOCK_ID" => "61",
											"IBLOCK_TYPE" => "aspro_max_catalog",
											"INCLUDE_SUBSECTIONS" => "Y",
											"LABEL_PROP" => array("NEWPRODUCT"),
											"LABEL_PROP_MOBILE" => array(),
											"LABEL_PROP_POSITION" => "top-left",
											"LAZY_LOAD" => "Y",
											"LINE_ELEMENT_COUNT" => "4",
											"LOAD_ON_SCROLL" => "N",
											"MESSAGE_404" => "",
											"MESS_BTN_ADD_TO_BASKET" => "В корзину",
											"MESS_BTN_BUY" => "Купить",
											"MESS_BTN_DETAIL" => "Подробнее",
											"MESS_BTN_LAZY_LOAD" => "Показать ещё",
											"MESS_BTN_SUBSCRIBE" => "Подписаться",
											"MESS_NOT_AVAILABLE" => "Нет в наличии",
											"META_DESCRIPTION" => "-",
											"META_KEYWORDS" => "-",
											"OFFERS_CART_PROPERTIES" => array("ARTNUMBER","COLOR_REF","SIZES_SHOES","SIZES_CLOTHES"),
											"OFFERS_FIELD_CODE" => array("",""),
											"OFFERS_LIMIT" => "5",
											"OFFERS_PROPERTY_CODE" => array("COLOR_REF","SIZES_SHOES","SIZES_CLOTHES",""),
											"OFFERS_SORT_FIELD" => "sort",
											"OFFERS_SORT_FIELD2" => "id",
											"OFFERS_SORT_ORDER" => "asc",
											"OFFERS_SORT_ORDER2" => "desc",
											"OFFER_ADD_PICT_PROP" => "MORE_PHOTO",
											"OFFER_TREE_PROPS" => array("COLOR_REF","SIZES_SHOES","SIZES_CLOTHES"),
											"PAGER_BASE_LINK_ENABLE" => "N",
											"PAGER_DESC_NUMBERING" => "N",
											"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
											"PAGER_SHOW_ALL" => "N",
											"PAGER_SHOW_ALWAYS" => "N",
											"PAGER_TEMPLATE" => ".default",
											"PAGER_TITLE" => "Товары",
											"PAGE_ELEMENT_COUNT" => "2000",
											"PARTIAL_PRODUCT_PROPERTIES" => "Y",
											"PRICE_CODE" => array("BASE"),
											"PRICE_VAT_INCLUDE" => "Y",
											"PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons,compare",
											"PRODUCT_DISPLAY_MODE" => "Y",
											"PRODUCT_ID_VARIABLE" => "id",
											"PRODUCT_PROPERTIES" => array("NEWPRODUCT","MATERIAL"),
											"PRODUCT_PROPS_VARIABLE" => "prop",
											"PRODUCT_QUANTITY_VARIABLE" => "",
											"PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':true}]",
											"PRODUCT_SUBSCRIPTION" => "Y",
											"PROPERTY_CODE" => array("NEWPRODUCT",""),
											"PROPERTY_CODE_MOBILE" => array(),
											"RCM_PROD_ID" => $_REQUEST["PRODUCT_ID"],
											"RCM_TYPE" => "personal",
											"SECTION_CODE" => "",
											//"SECTION_ID" => $section['ID'],
											"SECTION_ID_VARIABLE" => "SECTION_ID",
											"SECTION_URL" => "",
											"SECTION_USER_FIELDS" => array("",""),
											"SEF_MODE" => "N",
											"SET_BROWSER_TITLE" => "N",
											"SET_LAST_MODIFIED" => "N",
											"SET_META_DESCRIPTION" => "N",
											"SET_META_KEYWORDS" => "N",
											"SET_STATUS_404" => "N",
											"SET_TITLE" => "N",
											"SHOW_404" => "N",
											"SHOW_ALL_WO_SECTION" => "N",
											"SHOW_CLOSE_POPUP" => "N",
											"SHOW_DISCOUNT_PERCENT" => "Y",
											"SHOW_FROM_SECTION" => "N",
											"SHOW_MAX_QUANTITY" => "N",
											"SHOW_OLD_PRICE" => "N",
											"SHOW_PRICE_COUNT" => "1",
											"SHOW_SLIDER" => "Y",
											"SLIDER_INTERVAL" => "3000",
											"SLIDER_PROGRESS" => "N",
											"TEMPLATE_THEME" => "blue",
											"USE_ENHANCED_ECOMMERCE" => "Y",
											"USE_MAIN_ELEMENT_SECTION" => "N",
											"USE_PRICE_COUNT" => "N",
											"USE_PRODUCT_QUANTITY" => "N"
										)
									);?>
							</div>

						</div>
					<?endforeach;?>

				<?endif;?>
			</div>
			<div class="card-tab__body__item" data-card="2">
				<div class="card-tab-desc">
					<?$APPLICATION->ShowViewContent('PRODUCT_DESCRIPTION')?>

				</div>
			</div>	
			
			<div class="card-tab__body__item" data-card="3">
				<?$APPLICATION->IncludeFile(SITE_DIR."include/product_pay.php", array(), array("MODE" => "html", "NAME" => ""));?>
			</div>
			
			<div class="card-tab__body__item" data-card="4" style="display: none;">
				<?$APPLICATION->IncludeFile(SITE_DIR."include/product_delivery.php", array(), array("MODE" => "html", "NAME" => ""));?>
			</div>	
			
			<div class="card-tab__body__item" data-card="5" style="display: none;">
				<div id="reviews_content">

					<div class="show-comment btn btn-xs btn-default">
						<?=GetMessage('ADD_REVIEW')?>
					</div> <br><br>				
					<?$APPLICATION->IncludeComponent(
						"bitrix:catalog.comments",
						"catalog",
						array(
							"CACHE_TYPE" => "A",
							"CACHE_TIME" => "0",
							// 'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
							// "COMMENTS_COUNT" => (isset($arParams["MESSAGES_PER_PAGE"]) ? $arParams["MESSAGES_PER_PAGE"] : $arParams['COMMENTS_COUNT']),
							"ELEMENT_CODE" => "",
							"ELEMENT_ID" => $arResult['VARIABLES']['ELEMENT_ID'],
							// "XML_ID" => $templateData['XML_ID'].(isset($templateData['OFFERS_INFO']) && isset($templateData['OFFERS_INFO']['OFFERS']) && count($templateData['OFFERS_INFO']['OFFERS']) ? '%' : ''),
							"IBLOCK_ID" => $arParams['IBLOCK_ID'],
							"IBLOCK_TYPE" => "collections",
							"SHOW_DEACTIVATED" => "N",
							"TEMPLATE_THEME" => "blue",
							"URL_TO_COMMENT" => "",
							"AJAX_POST" => "Y",
							"WIDTH" => "",
							"COMPONENT_TEMPLATE" => ".default",
							"BLOG_USE" => 'Y',
							"PATH_TO_SMILE" => '/bitrix/images/blog/smile/',
							// "EMAIL_NOTIFY" => $arParams["DETAIL_BLOG_EMAIL_NOTIFY"],
							"SHOW_SPAM" => "Y",
							"SHOW_RATING" => "Y",
							"RATING_TYPE" => "like_graphic_catalog_reviews",
							// "MAX_IMAGE_SIZE" => $arParams["MAX_IMAGE_SIZE"],
							// "BLOG_URL" => $arParams["BLOG_URL"],
							// "REVIEW_COMMENT_REQUIRED" => $arParams["REVIEW_COMMENT_REQUIRED"],
							// "REVIEW_FILTER_BUTTONS" => $arParams["REVIEW_FILTER_BUTTONS"],
							// "REAL_CUSTOMER_TEXT" => $arParams["REAL_CUSTOMER_TEXT"],
						),
						false, array("HIDE_ICONS" => "Y")
					);?>				

				</div>
										
			</div>				

		</div>
	</div>
</div>
<?


$cachePath = 'brand_cache_key_path'; // папка, в которой лежит кеш
$cacheTtl = 3600; // срок годности кеша (в секундах)
$cacheKey = 'brand_cache_key' . $arParams['IBLOCK_ID'] . $arResult['VARIABLES']['ELEMENT_ID'] ; // имя кеша


if ($cache->initCache($cacheTtl, $cacheKey, $cachePath)) {

    $brand_property = $cache->getVars();

} elseif ($cache->startDataCache()) {

	$res = CIBlockElement::GetProperty($arParams['IBLOCK_ID'], $arResult['VARIABLES']['ELEMENT_ID'], array("sort" => "asc"));
	while ($ob = $res->GetNext()) {

		if(!empty($brand_property[$ob['CODE']])) {
			$v = $brand_property[$ob['CODE']]['VALUE'];
			unset($brand_property[$ob['CODE']]['VALUE']);
			$brand_property[$ob['CODE']]['VALUE'][] = $v;
			$brand_property[$ob['CODE']]['VALUE'][] = $ob['VALUE'];
		} else {
			$brand_property[$ob['CODE']] = $ob;
		}
	
	}
    $cache->endDataCache($brand_property);
}

// echo '<pre>';
// print_r($arResult );
// echo '</pre>';


?>
<div class="iq-container">
<?if($arResult['VARIABLES']['SECTION_CODE'] == 'plitka'):?>
<div class="card-related">
	<div class="card-tab-title">
		<div class="card-tab-title__text">Хиты коллекций плитки</div>
	</div>

	<div class="card-tab-related">
		<?$APPLICATION->IncludeComponent(
			"bitrix:catalog.section",
			"catalog_block",
			Array(
				"ACTION_VARIABLE" => "action",
				"ADD_PICT_PROP" => "MORE_PHOTO",
				"ADD_PROPERTIES_TO_BASKET" => "Y",
				"ADD_SECTIONS_CHAIN" => "N",
				"ADD_TO_BASKET_ACTION" => "ADD",
				"AJAX_MODE" => "N",
				"AJAX_OPTION_ADDITIONAL" => "",
				"AJAX_OPTION_HISTORY" => "N",
				"AJAX_OPTION_JUMP" => "N",
				"AJAX_OPTION_STYLE" => "Y",
				"BACKGROUND_IMAGE" => "UF_BACKGROUND_IMAGE",
				"BASKET_URL" => "/personal/basket.php",
				"BRAND_PROPERTY" => "BRAND_REF",
				"BROWSER_TITLE" => "-",
				"CACHE_FILTER" => "N",
				"CACHE_GROUPS" => "Y",
				"CACHE_TIME" => "36000000",
				"CACHE_TYPE" => "A",
				"COMPATIBLE_MODE" => "Y",
				"CONVERT_CURRENCY" => "Y",
				"CURRENCY_ID" => "RUB",
				"CUSTOM_FILTER" => "",
				"DATA_LAYER_NAME" => "dataLayer",
				"DETAIL_URL" => "",
				"DISABLE_INIT_JS_IN_COMPONENT" => "N",
				"DISCOUNT_PERCENT_POSITION" => "bottom-right",
				"DISPLAY_BOTTOM_PAGER" => "N",
				"DISPLAY_TOP_PAGER" => "N",
				"ELEMENT_SORT_FIELD" => "shows",
				"ELEMENT_SORT_FIELD2" => "id",
				"ELEMENT_SORT_ORDER" => "asc",
				"ELEMENT_SORT_ORDER2" => "desc",
				"ENLARGE_PRODUCT" => "PROP",
				"ENLARGE_PROP" => "NEWPRODUCT",
				"FILTER_NAME" => "arrFilterElementsCollection",
				"HIDE_NOT_AVAILABLE" => "N",
				"HIDE_NOT_AVAILABLE_OFFERS" => "N",
				"IBLOCK_ID" => "73",
				"IBLOCK_TYPE" => "aspro_max_catalog",
				"INCLUDE_SUBSECTIONS" => "Y",
				"LABEL_PROP" => array("NEWPRODUCT"),
				"LABEL_PROP_MOBILE" => array(),
				"LABEL_PROP_POSITION" => "top-left",
				"LAZY_LOAD" => "Y",
				"LINE_ELEMENT_COUNT" => "8",
				"LOAD_ON_SCROLL" => "N",
				"MESSAGE_404" => "",
				"MESS_BTN_ADD_TO_BASKET" => "В корзину",
				"MESS_BTN_BUY" => "Купить",
				"MESS_BTN_DETAIL" => "Подробнее",
				"MESS_BTN_LAZY_LOAD" => "Показать ещё",
				"MESS_BTN_SUBSCRIBE" => "Подписаться",
				"MESS_NOT_AVAILABLE" => "Нет в наличии",
				"META_DESCRIPTION" => "-",
				"META_KEYWORDS" => "-",
				"OFFERS_CART_PROPERTIES" => array("ARTNUMBER","COLOR_REF","SIZES_SHOES","SIZES_CLOTHES"),
				"OFFERS_FIELD_CODE" => array("",""),
				"OFFERS_LIMIT" => "5",
				"OFFERS_PROPERTY_CODE" => array("COLOR_REF","SIZES_SHOES","SIZES_CLOTHES",""),
				"OFFERS_SORT_FIELD" => "sort",
				"OFFERS_SORT_FIELD2" => "id",
				"OFFERS_SORT_ORDER" => "asc",
				"OFFERS_SORT_ORDER2" => "desc",
				"OFFER_ADD_PICT_PROP" => "MORE_PHOTO",
				"OFFER_TREE_PROPS" => array("COLOR_REF","SIZES_SHOES","SIZES_CLOTHES"),
				"PAGER_BASE_LINK_ENABLE" => "N",
				"PAGER_DESC_NUMBERING" => "N",
				"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
				"PAGER_SHOW_ALL" => "N",
				"PAGER_SHOW_ALWAYS" => "N",
				"PAGER_TEMPLATE" => ".default",
				"PAGER_TITLE" => "Товары",
				"PAGE_ELEMENT_COUNT" => "4",
				"PARTIAL_PRODUCT_PROPERTIES" => "N",
				"PRICE_CODE" => array("BASE"),
				"PRICE_VAT_INCLUDE" => "Y",
				"PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons,compare",
				"PRODUCT_DISPLAY_MODE" => "Y",
				"PRODUCT_ID_VARIABLE" => "id",
				"PRODUCT_PROPERTIES" => array("NEWPRODUCT","MATERIAL"),
				"PRODUCT_PROPS_VARIABLE" => "prop",
				"PRODUCT_QUANTITY_VARIABLE" => "",
				"PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':true}]",
				"PRODUCT_SUBSCRIPTION" => "Y",
				"PROPERTY_CODE" => array("NEWPRODUCT",""),
				"PROPERTY_CODE_MOBILE" => array(),
				"RCM_PROD_ID" => $_REQUEST["PRODUCT_ID"],
				"RCM_TYPE" => "personal",
				"SECTION_CODE" => "",
				"SECTION_ID" => "268",
				"SECTION_ID_VARIABLE" => "SECTION_ID",
				"SECTION_URL" => "",
				"SECTION_USER_FIELDS" => array("",""),
				"SEF_MODE" => "N",
				"SET_BROWSER_TITLE" => "N",
				"SET_LAST_MODIFIED" => "N",
				"SET_META_DESCRIPTION" => "N",
				"SET_META_KEYWORDS" => "N",
				"SET_STATUS_404" => "N",
				"SET_TITLE" => "N",
				"SHOW_404" => "N",
				"SHOW_ALL_WO_SECTION" => "N",
				"SHOW_CLOSE_POPUP" => "N",
				"SHOW_DISCOUNT_PERCENT" => "Y",
				"SHOW_FROM_SECTION" => "N",
				"SHOW_MAX_QUANTITY" => "N",
				"SHOW_OLD_PRICE" => "N",
				"SHOW_PRICE_COUNT" => "1",
				"SHOW_SLIDER" => "Y",
				"SLIDER_INTERVAL" => "3000",
				"SLIDER_PROGRESS" => "N",
				"TEMPLATE_THEME" => "blue",
				"USE_ENHANCED_ECOMMERCE" => "Y",
				"USE_MAIN_ELEMENT_SECTION" => "N",
				"USE_PRICE_COUNT" => "N",
				"USE_PRODUCT_QUANTITY" => "N"
			)
		);?>
	</div>

	<!--<div class="iq-btn"><a href="">Загрузить ещё</a></div>		-->	
</div>
<?endif;?>

	<?if(!empty($brand_property['HITS_PUSH']['VALUE'])):?>
		<?	
			global $arrFilterCooection;
			$arrFilterCooection = array('ID' => $brand_property['HITS_PUSH']['VALUE']);			
			?>

	<?endif;?>
	<?if(!empty($brand_property['BRAND']['VALUE']) && $brand_property['BRAND']['VALUE'] > 0):?>
		<?
		global $arrFilterBrand;
		$arrFilterBrand = array('PROPERTY_BRAND.ID' => $brand_property['BRAND']['VALUE']);

		$result_brand = CIBlockElement::GetByID($brand_property['BRAND']['VALUE']);
		if($return_result_brand = $result_brand->GetNext()) {
			$build_brand = $return_result_brand;
		}
		//   echo $ar_res['NAME'];		
		?>
		<div class="card-related">
			
			<div class="card-tab-title">
				<div class="card-tab-title__text">Все коллекции бренда <?=$build_brand['NAME']?></div>
				<!-- <div class="card-tab-title__counter">184</div>	-->					
			</div>

			<div class="card-tab-related">
				<?$APPLICATION->IncludeComponent(
					"bitrix:catalog.section",
					"catalog_collection",
					Array(
						"ACTION_VARIABLE" => "action",
						"ADD_PICT_PROP" => "MORE_PHOTO",
						"ADD_PROPERTIES_TO_BASKET" => "Y",
						"ADD_SECTIONS_CHAIN" => "N",
						"ADD_TO_BASKET_ACTION" => "ADD",
						"AJAX_MODE" => "N",
						"AJAX_OPTION_ADDITIONAL" => "",
						"AJAX_OPTION_HISTORY" => "N",
						"AJAX_OPTION_JUMP" => "N",
						"AJAX_OPTION_STYLE" => "Y",
						"BACKGROUND_IMAGE" => "UF_BACKGROUND_IMAGE",
						"BASKET_URL" => "/personal/basket.php",
						"BRAND_PROPERTY" => "BRAND_REF",
						"BROWSER_TITLE" => "-",
						"CACHE_FILTER" => "N",
						"CACHE_GROUPS" => "Y",
						"CACHE_TIME" => "36000000",
						"CACHE_TYPE" => "A",
						"COMPATIBLE_MODE" => "Y",
						"CONVERT_CURRENCY" => "Y",
						"CURRENCY_ID" => "RUB",
						"CUSTOM_FILTER" => "",
						"DATA_LAYER_NAME" => "dataLayer",
						"DETAIL_URL" => "",
						"DISABLE_INIT_JS_IN_COMPONENT" => "N",
						"DISCOUNT_PERCENT_POSITION" => "bottom-right",
						"DISPLAY_BOTTOM_PAGER" => "N",
						"DISPLAY_TOP_PAGER" => "N",
						"ELEMENT_SORT_FIELD" => "sort",
						"ELEMENT_SORT_FIELD2" => "id",
						"ELEMENT_SORT_ORDER" => "asc",
						"ELEMENT_SORT_ORDER2" => "desc",
						"ENLARGE_PRODUCT" => "PROP",
						"ENLARGE_PROP" => "NEWPRODUCT",
						"FILTER_NAME" => "arrFilterBrand",
						"HIDE_NOT_AVAILABLE" => "N",
						"HIDE_NOT_AVAILABLE_OFFERS" => "N",
						"IBLOCK_ID" => "73",
						"IBLOCK_TYPE" => "collections",
						"INCLUDE_SUBSECTIONS" => "Y",
						"LABEL_PROP" => array("NEWPRODUCT"),
						"LABEL_PROP_MOBILE" => array(),
						"LABEL_PROP_POSITION" => "top-left",
						"LAZY_LOAD" => "Y",
						"LINE_ELEMENT_COUNT" => "4",
						"LOAD_ON_SCROLL" => "N",
						"MESSAGE_404" => "",
						"MESS_BTN_ADD_TO_BASKET" => "В корзину",
						"MESS_BTN_BUY" => "Купить",
						"MESS_BTN_DETAIL" => "Подробнее",
						"MESS_BTN_LAZY_LOAD" => "Показать ещё",
						"MESS_BTN_SUBSCRIBE" => "Подписаться",
						"MESS_NOT_AVAILABLE" => "Нет в наличии",
						"META_DESCRIPTION" => "-",
						"META_KEYWORDS" => "-",
						"OFFERS_CART_PROPERTIES" => array("ARTNUMBER","COLOR_REF","SIZES_SHOES","SIZES_CLOTHES"),
						"OFFERS_FIELD_CODE" => array("",""),
						"OFFERS_LIMIT" => "5",
						"OFFERS_PROPERTY_CODE" => array("COLOR_REF","SIZES_SHOES","SIZES_CLOTHES",""),
						"OFFERS_SORT_FIELD" => "sort",
						"OFFERS_SORT_FIELD2" => "id",
						"OFFERS_SORT_ORDER" => "asc",
						"OFFERS_SORT_ORDER2" => "desc",
						"OFFER_ADD_PICT_PROP" => "MORE_PHOTO",
						"OFFER_TREE_PROPS" => array("COLOR_REF","SIZES_SHOES","SIZES_CLOTHES"),
						"PAGER_BASE_LINK_ENABLE" => "N",
						"PAGER_DESC_NUMBERING" => "N",
						"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
						"PAGER_SHOW_ALL" => "N",
						"PAGER_SHOW_ALWAYS" => "N",
						"PAGER_TEMPLATE" => ".default",
						"PAGER_TITLE" => "Товары",
						"PAGE_ELEMENT_COUNT" => "4",
						"PARTIAL_PRODUCT_PROPERTIES" => "N",
						"PRICE_CODE" => array("BASE"),
						"PRICE_VAT_INCLUDE" => "Y",
						"PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons,compare",
						"PRODUCT_DISPLAY_MODE" => "Y",
						"PRODUCT_ID_VARIABLE" => "id",
						"PRODUCT_PROPERTIES" => array("NEWPRODUCT","MATERIAL"),
						"PRODUCT_PROPS_VARIABLE" => "prop",
						"PRODUCT_QUANTITY_VARIABLE" => "",
						"PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':true}]",
						"PRODUCT_SUBSCRIPTION" => "Y",
						"PROPERTY_CODE" => array("NEWPRODUCT",""),
						"PROPERTY_CODE_MOBILE" => array(),
						"RCM_PROD_ID" => $_REQUEST["PRODUCT_ID"],
						"RCM_TYPE" => "personal",
						"SECTION_CODE" => "",
						"SECTION_ID" => "",
						"SECTION_ID_VARIABLE" => "SECTION_ID",
						"SECTION_URL" => "",
						"SECTION_USER_FIELDS" => array("",""),
						"SEF_MODE" => "N",
						"SET_BROWSER_TITLE" => "N",
						"SET_LAST_MODIFIED" => "N",
						"SET_META_DESCRIPTION" => "N",
						"SET_META_KEYWORDS" => "N",
						"SET_STATUS_404" => "N",
						"SET_TITLE" => "N",
						"SHOW_404" => "N",
						"SHOW_ALL_WO_SECTION" => "N",
						"SHOW_CLOSE_POPUP" => "N",
						"SHOW_DISCOUNT_PERCENT" => "Y",
						"SHOW_FROM_SECTION" => "N",
						"SHOW_MAX_QUANTITY" => "N",
						"SHOW_OLD_PRICE" => "N",
						"SHOW_PRICE_COUNT" => "1",
						"SHOW_SLIDER" => "Y",
						"SLIDER_INTERVAL" => "3000",
						"SLIDER_PROGRESS" => "N",
						"TEMPLATE_THEME" => "blue",
						"USE_ENHANCED_ECOMMERCE" => "Y",
						"USE_MAIN_ELEMENT_SECTION" => "N",
						"USE_PRICE_COUNT" => "N",
						"USE_PRODUCT_QUANTITY" => "N"
					)
				);?>
			</div>
			
			<div class="btn_wrapper">
				<a href="<?=$build_brand['DETAIL_PAGE_URL'];?>">Больше коллекций</a>
			</div>
			
		</div>
		<div class="card-brand">
			<div class="card-brand__img">
				<img src="<?=CFile::GetPath($build_brand['PREVIEW_PICTURE'])?>" alt="">
			</div>
			<div class="card-brand__text"><?=$build_brand['PREVIEW_TEXT']?></div>
		</div>
	<?endif;?>
</div>
<script>
	window.addEventListener('load', function () {

		$('body').on('click', '.chosen-select-clear', function () {
console.log('test');
			$('.segment-filter-section').removeClass('closet-visible').show();;
				$('.card-tab__body__item .catalog_block .item_block').show();

		});

		
			// myArray[2] = "Джулиет";		
		$('body').on('change', '.filter-size select[name=size_filter], .filter-section select[name=types_filter]', function() {			
			
			var myArray = [];
			console.log(myArray);

			setTimeout(function () {


				$('.iq-chosen-item__select .search-choice').each(function (index) {
					// $( this ).text()
					myArray.push($( this ).text());
				});

				if(myArray.length > 0) {
					
					$('.card-tab__body__item .catalog_block .item_block').hide().closest('.segment-filter-section').removeClass('closet-visible');

					myArray.forEach(function(item, i, arr) {
						//console.log( i + ": " +  + " (массив:" + arr + ")" );
						//$(".card-tab__body__item .catalog_block .item_block[data-type='"+item+"']").show();

						$(".card-tab__body__item .catalog_block .item_block[data-size='"+item+"'], .card-tab__body__item .catalog_block .item_block[data-type='"+item+"'] ").show().addClass('element-closet-visible');				
						$(".card-tab__body__item .catalog_block .item_block[data-size='"+item+"'], .card-tab__body__item .catalog_block .item_block[data-type='"+item+"'] ").closest('.segment-filter-section').addClass('closet-visible');
						
					});

					$('.segment-filter-section').each(function (index) {

						if(!$(this).hasClass('closet-visible')) $(this).hide(); else $(this).show();

					});					

				} else {

					$('.card-tab__body__item .catalog_block .item_block').show();
					$('.segment-filter-section').removeClass('closet-visible');

					// проверяем по секциям
					sectionBuild();

				}




				myArray.length=0;

			}, 500);


		});

		$('body').on('change', '.filter-section select[name=types_filter]', function() {

			//sectionBuild();

			
		});

		function sectionBuild() {
			var myArray = [];
			setTimeout(function () {
				
				$('.filter-section .iq-chosen-item__select .search-choice').each(function (index) {
					// $( this ).text()
					myArray.push($( this ).text());
				});

				// segment-filter-section

				if (myArray.length > 0) {

					$('.card-tab__body__item .segment-filter-section').hide();

					myArray.forEach(function(item, i, arr) {
						//console.log( i + ": " +  + " (массив:" + arr + ")" );
						$(".card-tab__body__item .segment-filter-section[data-section='"+item+"']").show();
						// $(".card-tab__body__item .catalog_block .item_block[data-size='"+item+"']").show();				
					});					

				} else {

					$('.card-tab__body__item .segment-filter-section').show();

				}

				// Нужно чекнуть все элементы
				let buildSegment = false;
				$('.segment-filter-section').each(function (index) {

					if($(this).hasClass('closet-visible')) buildSegment = true;

				});
				console.log(buildSegment);
				if(buildSegment == true) {
					$('.segment-filter-section').each(function (index) {

						if(!$(this).hasClass('closet-visible')) $(this).hide();

					});				
				}
								
			}, 500)
		}

	});
</script>