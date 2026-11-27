<?php
use Bitrix\Main,
	Bitrix\Main\Localization\Loc as Loc,
	Bitrix\Main\Loader,
	Bitrix\Main\Config\Option,
	Bitrix\Sale\Delivery,
	Bitrix\Sale\PaySystem,
	Bitrix\Sale,
	Bitrix\Sale\Order,
	Bitrix\Sale\DiscountCouponsManager,
	Bitrix\Main\Context,
	Bitrix\Main\Entity,
	Bitrix\Highloadblock as HL;

//COption::SetOptionString("catalog", "DEFAULT_SKIP_SOURCE_CHECK", "Y");
//COption::SetOptionString("sale", "secure_1c_exchange", "N");
//http://xxx.sheer82.ru/bitrix/admin/1c_exchange.php?type=catalog&mode=import&filename=offers.xml

\Bitrix\Main\Loader::includeModule('shestpa.lastmodified');

define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/log.txt");
//AddMessage2Log();

define("SITE_URL", ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']);
define("SITE_PATH", $_SERVER['DOCUMENT_ROOT']);
define("TMPL_URL", ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/local/templates/atika');
define("TMPL_PATH", $_SERVER['DOCUMENT_ROOT'] . '/local/templates/atika');
define("CATALOG", 2);


function AddChainSections($arCurSection){
	global $APPLICATION;
	
	$arSections = [];
	$arSections = sectionClose($arSections, $arCurSection['ID']);
	$arSections = array_reverse($arSections);
	foreach($arSections as $arSection){
		$NAME = (!empty($arSection['UF_NAME_EN']) ? $arSection['UF_NAME_EN'] : $arSection['NAME']);
		$APPLICATION->AddChainItem($NAME, $arSection['SECTION_PAGE_URL']);
	}
}
function sectionClose($arSect, $sectionId){
	CModule::IncludeModule('iblock');
	$dbRes = CIBlockSection::GetList([], ["IBLOCK_ID" => CATALOG, "ID" => $sectionId], false, array("*", "UF_*"));
	if($section = $dbRes->GetNext()){
        $arSect[] = $section;
        if($section["IBLOCK_SECTION_ID"] > 0){
			$arSect = sectionClose($arSect, $section["IBLOCK_SECTION_ID"]);
        }
    }
    return $arSect;
}




function shortcode(&$arResult){
	$arResult["DETAIL_TEXT"] = str_replace(['<table>', '</table>'], ['<div class="table-main"><table>', '</table></div>'], $arResult["DETAIL_TEXT"]);
	$tags = findTags($arResult["DETAIL_TEXT"], 'BLOCK_PIC_1_DESC', '<section class="BLOCK_PIC_1_DESC">', '</section>');
	krsort($tags);
	if(is_array($arResult["PROPERTIES"]["PICS_NEWS"]['VALUE']))
		$arResult["PROPERTIES"]["PICS_NEWS"]['VALUE'] = array_reverse($arResult["PROPERTIES"]["PICS_NEWS"]['VALUE']);
	
	$IMG_INDEX = 0; $H_INDEX = 0;
	foreach($tags as $tag){
		switch($tag['typetag']){
			case 'BLOCK_PIC_1_DESC':
				$arResult["DETAIL_TEXT"] = mb_substr($arResult["DETAIL_TEXT"], 0, $tag['startpos']) . 
				'<div class="row align-items-center">
					<div class="col-md-6">
						<img src="'. CFile::GetPath($arResult["PROPERTIES"]["PICS_NEWS"]['VALUE'][$IMG_INDEX]) .'" alt="alt">
					</div>
					<div class="col-md-6">
						<div class="article-info">'. htmlspecialchars_decode($tag['content']) .'</div>
					</div>
				</div>'
				 . mb_substr($arResult["DETAIL_TEXT"], $tag['endpos']);
				
				$IMG_INDEX++;
			break;
		}
	}
}

function findTags($text, $type_tag, $start_tag, $end_tag){
	
	$arResult = [];
	$offset = 0;
	while(($startpos = mb_strpos($text, $start_tag, $offset)) !== false){
		$offset = $startpos + 1;
		$startpos_content = $startpos + strlen($start_tag);
		$endpos_content = mb_strpos($text, $end_tag, $startpos_content);
		if($endpos_content !== false){
			$content = mb_substr($text, $startpos_content, $endpos_content - $startpos_content);
			$endpos = $endpos_content + strlen($end_tag);
			$arResult[$startpos] = [
				'typetag' => 'BLOCK_PIC_1_DESC',
				'startpos' => $startpos,
				'endpos' => $endpos,
				'content' => $content,
			];
		}
	}
	return $arResult;
	
}


AddEventHandler('fileman', 'OnBeforeHTMLEditorScriptRuns', 'addHtmlGalleryEditor');
function addHtmlGalleryEditor(){
    static $init = false;
    if($init) return;
    $init = true;
	echo '<script>(function(){'.file_get_contents(__DIR__ . '/visual_editor/visual_editor.js').'})();</script>';
}

// AddEventHandler("iblock", "OnBeforeIBlockSectionUpdate", "DoNotSectionUpdate");
// function DoNotSectionUpdate(&$arFields){
	// if($_SERVER["SCRIPT_NAME"] == '/bitrix/admin/1c_exchange.php') unset($arFields["ACTIVE"]);
// }
// AddEventHandler("iblock", "OnBeforeIBlockSectionAdd", "DoNotSectionAdd");
// function DoNotSectionAdd(&$arFields){
	// if($_SERVER["SCRIPT_NAME"] == '/bitrix/admin/1c_exchange.php') unset($arFields["ACTIVE"]);
    // if($_REQUEST['mode']=='import'){
        // global $APPLICATION;
		// $APPLICATION->throwException(" Отмена добавления раздела. ( ID: " . $arFields["NAME"].") ");
		// return false;
    // }
// }

// AddEventHandler("iblock", "OnBeforeIBlockElementAdd", "DoNotAdd");
// function DoNotAdd(&$arFields){
    // if($_REQUEST['mode']=='import'){
		// $arFields['IBLOCK_SECTION_ID'] = 93;
		// $arFields['IBLOCK_SECTION'] = array(93);
    // }
// }
// AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", "DoNotUpdate");
// function DoNotUpdate(&$arFields){
    // if($_REQUEST['mode']=='import'){
        // unset($arFields['PREVIEW_PICTURE']);
        // unset($arFields['DETAIL_PICTURE']);
        // unset($arFields['PREVIEW_TEXT']);
        // unset($arFields['DETAIL_TEXT']);
		// //unset($arFields['NAME']);
		// unset($arFields['IBLOCK_SECTION_ID']);
		// unset($arFields['IBLOCK_SECTION']);
    // }
// }

function is_followers($priduct_id){
	if(!isset($_SESSION['followers'])) $_SESSION['followers'] = [];
	return in_array($priduct_id, $_SESSION['followers']);
}

function set_followers(){
	if(!isset($_SESSION['followers'])) $_SESSION['followers'] = [];
	
	if(!isset($_SESSION['followers'])){
		if(!isset($_COOKIE['followers'])) $_SESSION['followers'] = array();
		else $_SESSION['followers'] = json_decode($_COOKIE['followers']);
	}
	else{
		setcookie('followers', json_encode($_SESSION['followers']), time() + 31536000, '/');
	}
}
set_followers();


// \Bitrix\Main\EventManager::getInstance()->addEventHandlerCompatible('catalog', 'OnSuccessCatalogImport1C', function(){
	
// });
// function hide_elements_E(){
	// CModule::IncludeModule('iblock');
	// $res = CIBlockElement::GetList([], ["IBLOCK_ID" => CATALOG, 'NAME' => 'E%'], ['ID', 'NAME']);
	// $list = '';
	// while($ar_fields = $res->GetNext()){
		// $el = new CIBlockElement;
		// $el->Update($ar_fields['ID'], [
			// 'IBLOCK_SECTION_ID' => 94,
			// 'IBLOCK_SECTION' => [94],
		// ]);
		// $list .= $ar_fields['NAME'].'<br>';
	// }
	// return $list;
// }

function atikaPriceNegotiable()
{
	return COption::GetOptionString('main', 'atika_negotiable_price', 'N') === 'Y';
}

function atikaClearPublicCache()
{
	if (isset($GLOBALS['CACHE_MANAGER']) && is_object($GLOBALS['CACHE_MANAGER']))
	{
		$GLOBALS['CACHE_MANAGER']->CleanAll();
	}

	BXClearCache(true);

	if (class_exists('\Bitrix\Main\Data\ManagedCache'))
	{
		$managedCache = new \Bitrix\Main\Data\ManagedCache();
		if (method_exists($managedCache, 'cleanAll'))
		{
			$managedCache->cleanAll();
		}
	}

	if (class_exists('\Bitrix\Main\Data\StaticHtmlCache'))
	{
		\Bitrix\Main\Data\StaticHtmlCache::getInstance()->deleteAll();
	}
}

AddEventHandler('main', 'OnBuildGlobalMenu', 'atikaNegotiablePriceMenu');
function atikaNegotiablePriceMenu(&$adminMenu, &$moduleMenu)
{
	$moduleMenu[] = array(
		'parent_menu' => 'global_menu_settings',
		'section' => 'atika_negotiable_price',
		'sort' => 1000,
		'text' => 'Цена договорная',
		'title' => 'Показать на сайте «Цена договорная» вместо цен',
		'url' => 'atika_negotiable_price.php?lang='.LANGUAGE_ID,
		'icon' => 'sys_menu_icon',
		'page_icon' => 'sys_page_icon',
		'items_id' => 'menu_atika_negotiable_price',
	);
}

AddEventHandler('sale', 'OnSaleComponentOrderJsData', 'atikaHideOrderPrices');
function atikaHideOrderPrices(&$arResult, &$arParams)
{
	if (!atikaPriceNegotiable() || empty($arResult['JS_DATA']) || !is_array($arResult['JS_DATA']))
	{
		return;
	}

	$text = 'Цена договорная';
	$totalKeys = array(
		'PRICE_WITHOUT_DISCOUNT',
		'BASKET_PRICE_DISCOUNT_DIFF',
		'ORDER_TOTAL_PRICE_FORMATED',
		'ORDER_TOTAL_LEFT_TO_PAY_FORMATED',
		'ORDER_PRICE_FORMATED',
		'VAT_SUM_FORMATED',
		'DISCOUNT_PRICE_FORMATED',
		'DELIVERY_PRICE_FORMATED',
		'PAY_SYSTEM_PRICE_FORMATTED',
	);

	if (!empty($arResult['JS_DATA']['TOTAL']) && is_array($arResult['JS_DATA']['TOTAL']))
	{
		foreach ($totalKeys as $key)
		{
			if (!empty($arResult['JS_DATA']['TOTAL'][$key]))
			{
				$arResult['JS_DATA']['TOTAL'][$key] = $text;
			}
		}
	}

	if (!empty($arResult['JS_DATA']['GRID']['ROWS']) && is_array($arResult['JS_DATA']['GRID']['ROWS']))
	{
		foreach ($arResult['JS_DATA']['GRID']['ROWS'] as &$row)
		{
			if (empty($row['data']) || !is_array($row['data']))
			{
				continue;
			}

			foreach (array('PRICE_FORMATED', 'BASE_PRICE_FORMATED', 'SUM', 'DISCOUNT_PRICE_FORMATED') as $key)
			{
				if (!empty($row['data'][$key]))
				{
					$row['data'][$key] = $text;
				}
			}
		}
		unset($row);
	}
}
