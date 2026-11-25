<?php

require_once __DIR__ . '/init_files_to_more_photo.php';
//require_once __DIR__ . '/init_hide_empty_sections.php';

function pre ($c){
   global $USER;
   if($USER->IsAdmin()){
       print_r('<pre>');
       print_r($c);
       print_r('</pre>');
   }
}

if ($_SERVER['REQUEST_URI'] === '/?') {
    LocalRedirect('/', true, '301 Moved Permanently');
}


function getSectionsParents($SECTION_ID){
	CModule::IncludeModule('iblock');
	$arSections = [];
	$dbSections = CIBlockSection::GetNavChain(false, $SECTION_ID, ['ID', 'NAME']);
	while($arSection = $dbSections->GetNext()){
		$arSections[$arSection['ID']] = $arSection;
	}
	return $arSections;
}
function num_word($value, $words, $show = true){
	$num = $value % 100;
	if ($num > 19) { 
		$num = $num % 10; 
	}
	$out = ($show) ?  $value . ' ' : '';
	switch ($num) {
		case 1:  $out .= $words[0]; break;
		case 2: 
		case 3: 
		case 4:  $out .= $words[1]; break;
		default: $out .= $words[2]; break;
	}
	return $out;
}



AddEventHandler('sale', 'OnOrderNewSendEmail', array('CSendOrderTable', 'OnOrderNewSendEmailHandler'));
class CSendOrderTable {
   public static function OnOrderNewSendEmailHandler($ID, &$eventName, &$arFields) {

    $arOrder = CSaleOrder::GetByID($ID);

    //-- получаем телефоны и адрес
    $order_props = CSaleOrderPropsValue::GetOrderProps($ID);   
    
    $phone="";
    $index = ""; 
    $country_name = "";
    $city_name = "";  
    $address = "";
    $rz = '';
    $p = '';
    $u = '';        
    while ($arProps = $order_props->Fetch())
    {
      if ($arProps["CODE"] == "PHONE")
      {
         $phone = htmlspecialchars($arProps["VALUE"]);
      }
      if ($arProps["CODE"] == "LOCATION")
      {
          $arLocs = CSaleLocation::GetByID($arProps["VALUE"]);
          $country_name =  $arLocs["COUNTRY_NAME_ORIG"];
          $city_name = $arLocs["CITY_NAME_ORIG"];
      }
  
      if ($arProps["CODE"] == "INDEX")
      {
        $index = $arProps["VALUE"];   
      }
  
      if ($arProps["CODE"] == "ADDRESS")
      {
        $address = $arProps["VALUE"];
      }

      if ($arProps["CODE"] == "RAZGRUZKA")
      {
        $rz = ($arProps["VALUE"] == "Y" ? 'Да' : 'Нет');
      }

      if ($arProps["CODE"] == "POD")
      {
        $p = ($arProps["VALUE"] == "Y" ? 'Да' : 'Нет');
      }
      
      if ($arProps["CODE"] == "USTANOVKA")
      {
        $u = ($arProps["VALUE"] == "Y" ? 'Да' : 'Нет');
      }      

    }

    $arFields["RAZGRUZKA"] =  $rz;
    $arFields["POD"] =  $p;
    $arFields["USTANOVKA"] =  $u;        
  
    $full_address = $index.", ".$country_name."-".$city_name.", ".$address;

    $arFields["FULL_ADDRESS"] = $full_address;   

    $arFields["PHONE"] =  $phone;

    //-- получаем название платежной системы   
    $arPaySystem = CSalePaySystem::GetByID($arOrder["PAY_SYSTEM_ID"]);
    $pay_system_name = "";
    if ($arPaySystem)
    {
        $pay_system_name = $arPaySystem["NAME"];
    }    

    $arFields["PAY_SYSTEM_NAME"] =  $pay_system_name;

    //-- получаем название службы доставки
    $arDeliv = CSaleDelivery::GetByID($arOrder["DELIVERY_ID"]);
    $delivery_name = "";
    if ($arDeliv)
    {
        $delivery_name = $arDeliv["NAME"];
    }    

    $arFields["DELIVERY_NAME"] =  $delivery_name;

      if ($ID>0 && CModule::IncludeModule('iblock')) {
         $ratio = 'шт';
         $arFields['ORDER_LIST'] = '<table cellpadding="5" cellspacing="5">';
         $rsBasket = CSaleBasket::GetList(array(), array('ORDER_ID' => $ID));
         while ($arBasket = $rsBasket->GetNext()) {
            $arPicture = false;
            //мы берем картинку только если это товар из инфоблока
            if ($arBasket['MODULE'] == 'catalog') {
               if ($arProduct = CIBlockElement::GetByID($arBasket['PRODUCT_ID'])->GetNext()) {
                   $url = $arProduct['DETAIL_PAGE_URL'];
                  if ($arProduct['PREVIEW_PICTURE'] > 0) {
                     $fileID = $arProduct['PREVIEW_PICTURE'];
                  } elseif ($arProduct['DETAIL_PICTURE'] > 0) {
                     $fileID = $arProduct['DETAIL_PICTURE'];
                  } else {
                     $fileID = 0;
                  }
                  $arPicture = CFile::ResizeImageGet($fileID, array('width' => 90, 'height' => 110));
                  $arPicture['SIZE'] = getimagesize($_SERVER['DOCUMENT_ROOT'].$arPicture['src']);
                  
                  $resRation = CCatalogMeasureRatio::getList(Array(), array('IBLOCK_ID'=>$arProduct['IBLOCK_ID'], 'PRODUCT_ID' => $arBasket['PRODUCT_ID']), false, false, array());
                  $ar_fields_ration = $resRation->Fetch();
                  $ratio = $ar_fields['RATIO']; 

               }



            }
            $arFields['ORDER_LIST'] .= '<tr valign="top">'
                                 . '<td>'.($arPicture ? '<img src="https://'.$GLOBALS['SERVER_NAME'].(str_replace(array('+', ' '), '%20', $arPicture['src'])).'" width="'.$arPicture['SIZE'][0].'" height="'.$arPicture['SIZE'][1].'" alt="">' : '').'</td>'
                                 . '<td><a href="https://'.$GLOBALS['SERVER_NAME'].$url.'">'.$arBasket['NAME'].'</a></td>'
                                 . '<td style="white-space: nowrap">'.(float)$arBasket['QUANTITY'].' ' . $ratio . '.</td>'
                                 . '<td style="white-space: nowrap">'.SaleFormatCurrency($arBasket['PRICE'], $arBasket['CURRENCY']).'</td>'
                              . '</tr>';
         }
         $arFields['ORDER_LIST'] .= '</table>';
      }
   }
}

AddEventHandler("main", "OnEndBufferContent", "ChangeMyContent");

function ChangeMyContent(&$content)
{
    $content = sanitize_output($content);
}

function sanitize_output($buffer)
{
    global $APPLICATION;
    if(substr_count($APPLICATION->GetCurPage(false),'/bitrix/') == 0){
      //  $buffer = str_replace('/politika-konfidencialnosti', '/politika-konfidencialnosti/', $buffer);
      
      $buffer = preg_replace(
        '#<img (.*)\/>#', 
        '<img $1  />', //itemprop="contentUrl"
        $buffer
      );

      $buffer = preg_replace('~>\s*\n\s*<~', '><', $buffer);

   }


    return $buffer;
}

AddEventHandler('main', 'OnEpilog', 'pag');
function pag() {
  if ($page = preg_grep("/PAGEN_(.*)/i", array_keys($_REQUEST))) {
      $page = intval($_REQUEST[reset($page)]);
      // canonical
      $GLOBALS['APPLICATION']->SetPageProperty('canonical',((!empty($_SERVER["HTTPS"])) ? "https://" : "http://") . $_SERVER['SERVER_NAME'].$GLOBALS['APPLICATION']->sDirPath ,true);
      //h1
      //$GLOBALS['APPLICATION']->SetTitle($GLOBALS['APPLICATION']->GetTitle(false) . ' — Страница ' . $page;
      //title
      $title = $GLOBALS['APPLICATION']->GetProperty('title');
      $GLOBALS['APPLICATION']->SetPageProperty('title', $title . ' страница ' . $page);
      //description
/*       $description = $GLOBALS['APPLICATION']->GetProperty('description');
      $GLOBALS['APPLICATION']->SetPageProperty('description', $description . ' — Страница ' . $page); */
  }
}

AddEventHandler("main", "OnBeforeProlog", function() {
    // Получаем текущий сайт через глобальный объект
    $currentSite = defined("SITE_ID") ? SITE_ID : CSite::GetByID(SITE_ID)->Fetch()['ID'] ?? 's1';
// 
    // переключатель доступен только для сайта s2
    if($currentSite !== 's2') {
        return;
    }

    if(isset($_GET['price_type'])) {
        $priceType = $_GET['price_type'];
        if(in_array($priceType, ['OPT', 'RETAIL'])) {
            setcookie('PRICE_TYPE', $priceType, time() + 3600 * 24, '/');
            $_COOKIE['PRICE_TYPE'] = $priceType;
        }
    }

   //  $allowedPages = [
   //      '/auth/',                  // страница авторизации
   //      '/auth/registration.php',  
   //      '/auth/forgot.php',        
   //      '/bitrix/admin/',          // админка
   //      '/favicon.ico',
   //      '/robots.txt'
   //  ];
    
   //  $currentPage = $APPLICATION->GetCurPage(false);

   //  if (!$USER->IsAuthorized() && !in_array($currentPage, $allowedPages)) {
   //      LocalRedirect('/auth/');
   //      exit();
   //  }    

});

// Удаляем лишние пробелы перед выводом
// AddEventHandler("main", "OnEndBufferContent", "ChangeMyContent");

// function ChangeMyContent(&$content)
// {
//    $content = sanitize_output($content);
// }

// function sanitize_output($buffer)
// {
//    return preg_replace('~>\s*\n\s*<~', '><', $buffer);
// }
AddEventHandler("catalog", "OnBeforeCatalogStoreUpdate", "OnBeforeCatalogStoreUpdateHandler");
function OnBeforeCatalogStoreUpdateHandler(&$arFields){
	if($_SERVER["SCRIPT_NAME"] == '/bitrix/admin/1c_exchange.php' || $_REQUEST['mode'] == 'import'){
		unset($arFields['TITLE']);
	}
}


/*

AddEventHandler("catalog", "OnBeforeIBlockSectionUpdate", "OnBeforeIBlockSectionUpdateHandler");
function OnBeforeIBlockSectionUpdateHandler(&$arFields){
	if($_SERVER["SCRIPT_NAME"] == '/bitrix/admin/1c_exchange.php' || $_REQUEST['mode'] == 'import'){
		unset($arFields['ACTIVE']);
	}
}

AddEventHandler("catalog", "OnBeforeIBlockElementUpdate", "OnBeforeIBlockElementUpdateHandler");
function OnBeforeIBlockElementUpdateHandler(&$arFields){
	if($_SERVER["SCRIPT_NAME"] == '/bitrix/admin/1c_exchange.php' || $_REQUEST['mode'] == 'import'){
		unset($arFields['ACTIVE']);
	}
}

AddEventHandler("catalog", "OnBeforeCatalogStoreUpdate", "OnBeforeCatalogStoreUpdateHandler");
function OnBeforeCatalogStoreUpdateHandler(&$arFields){
	if($_SERVER["SCRIPT_NAME"] == '/bitrix/admin/1c_exchange.php' || $_REQUEST['mode'] == 'import'){
		unset($arFields['TITLE']);
	}
}

*/