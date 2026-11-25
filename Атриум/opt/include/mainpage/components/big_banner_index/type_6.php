<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$APPLICATION->IncludeComponent(
	"aspro:com.banners.max", 
	"top_big_banner_2", 
	array(
		"IBLOCK_TYPE" => "aspro_max_adv",
		"IBLOCK_ID" => "62",
		"TYPE_BANNERS_IBLOCK_ID" => "39",
		"SET_BANNER_TYPE_FROM_THEME" => "N",
		"NEWS_COUNT" => "20",
		"NEWS_COUNT2" => "20",
		"SORT_BY1" => "SORT",
		"SORT_ORDER1" => "ASC",
		"SORT_BY2" => "ID",
		"SORT_ORDER2" => "DESC",
		"PROPERTY_CODE" => array(
			0 => "TEXT_POSITION",
			1 => "TARGETS",
			2 => "TEXTCOLOR",
			3 => "URL_STRING",
			4 => "BUTTON1TEXT",
			5 => "BUTTON1LINK",
			6 => "BUTTON2TEXT",
			7 => "BUTTON2LINK",
			8 => "",
		),
		"CHECK_DATES" => "Y",
		"AJAX_OPTION_STYLE" => "Y",
		"CACHE_GROUPS" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"BANNER_TYPE_THEME" => "TOP",
		"COMPONENT_TEMPLATE" => "top_big_banner_2",
		"FILTER_NAME" => "arRegionLink"
	),
	false
);?>

<?
// if ($_GET['dev'] == 1) {
// 	echo 'test';
// }
?>
