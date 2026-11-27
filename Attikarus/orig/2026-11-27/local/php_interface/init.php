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


if(!function_exists('mb_ucfirst')){
	function mb_ucfirst($str){
		$fc = mb_strtoupper(mb_substr($str, 0, 1));
		return $fc . mb_substr($str, 1);
	}
}
if(!function_exists('mb_lcfirst')){
	function mb_lcfirst($str){
		$fc = mb_strtolower(mb_substr($str, 0, 1));
		return $fc . mb_substr($str, 1);
	}
}
function get_UF_SECTION_META($SECTION_ID, $META_NAME){
	CModule::IncludeModule('iblock');
	$rsSect = CIBlockSection::GetList([], ["IBLOCK_ID" => CATALOG, "=ID" => $SECTION_ID], false, ["ID", "NAME", "IBLOCK_SECTION_ID", "UF_*"], false);
	if($arSect = $rsSect->GetNext()){
		if(!empty($arSect[$META_NAME])){
			return $arSect[$META_NAME];
		}
		elseif($arSect['IBLOCK_SECTION_ID'] > 0){
			return get_UF_SECTION_META($arSect['IBLOCK_SECTION_ID'], $META_NAME);
		}
	}
	return false;
}
function calc_SECTION_META($SECTION_ID, $UF_META){
	CModule::IncludeModule('iblock');
	$rsSect = CIBlockSection::GetList([], ["IBLOCK_ID" => CATALOG, "=ID" => $SECTION_ID], false, ["*", "UF_*"], false);
	if($arSect = $rsSect->GetNext()){
		$NAME = (!empty($arSect['UF_NAME_EN']) ? $arSect['UF_NAME_EN'] : $arSect['NAME']);
		return preg_replace(['/{=this.Name}/s', '/{=this.name}/s'], [$NAME, mb_lcfirst($NAME)], $UF_META);
	}
}
function calc_ELEMENT_META($ELEMENT_ID, $UF_META){
	CModule::IncludeModule('iblock');
	$rsElem = CIBlockElement::GetList([], ["IBLOCK_ID" => CATALOG, "=ID" => $ELEMENT_ID], false, false, ["*", "PROPERTY_TITLE_EN"]);
	if($arElem = $rsElem->GetNext()){
		$NAME = (!empty($arElem['PROPERTY_TITLE_EN_VALUE']) ? $arElem['PROPERTY_TITLE_EN_VALUE'] : $arElem['NAME']);
		return preg_replace(['/{=this.Name}/s', '/{=this.name}/s'], [$NAME, mb_lcfirst($NAME)], $UF_META);
	}
}

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
	
	if(is_array($arResult["PROPERTIES"]["PICS_NEWS"]['VALUE']) && $IMG_INDEX < count($arResult["PROPERTIES"]["PICS_NEWS"]['VALUE'])){
		$arResult["DETAIL_TEXT"] .= '
		<section class="gallery">
			<div class="container">
				<div class="head-section">
					<div class="title-section">Галерея</div>
				</div>
				<div class="row row_gallery">';
				foreach($arResult["PROPERTIES"]["PICS_NEWS"]['VALUE'] as $index => $PICS){
					if($index <= $IMG_INDEX) continue;
					$arResult["DETAIL_TEXT"] .= '<div class="col-lg-4 col-6">
						<a data-fancybox="art-gallery" href="'. CFile::GetPath($arResult["PROPERTIES"]["PICS_NEWS"]['VALUE'][$index]) .'" class="item-gallery fancybox">
							<span class="item-gallery__image">
								<img src="'. CFile::GetPath($arResult["PROPERTIES"]["PICS_NEWS"]['VALUE'][$index]) .'" alt="alt">
							</span>
						</a>
					</div>';
				}
		$arResult["DETAIL_TEXT"] .= '
				</div>
			</div>
		</section>';
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


\Bitrix\Main\EventManager::getInstance()->addEventHandlerCompatible('catalog', 'OnSuccessCatalogImport1C', function(){
	
});
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