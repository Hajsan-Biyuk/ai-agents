<?
require $_SERVER["DOCUMENT_ROOT"].'/local/vendor/autoload.php';

use Bitrix\Main,
	Bitrix\Main\Localization\Loc as Loc,
	Bitrix\Main\Loader,
	Bitrix\Main\Config\Option,
	Bitrix\Sale\Delivery,
	Bitrix\Sale\PaySystem,
	Bitrix\Sale,
	Bitrix\Sale\Order,
	Bitrix\Sale\DiscountCouponsManager,
	Bitrix\Conversion\Internals\MobileDetect,
	Bitrix\Main\Context,
	Bitrix\Main\Entity,
	Bitrix\Highloadblock as HL,
	PhpOffice\PhpSpreadsheet\Spreadsheet,
	PhpOffice\PhpSpreadsheet\Writer\Xlsx;
	
define("SITE_URL", ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']);
define("SITE_PATH", $_SERVER['DOCUMENT_ROOT']);
define("TMPL_URL", ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/local/templates/ugtoiz');
define("TMPL_PATH", $_SERVER['DOCUMENT_ROOT'] . '/local/templates/ugtoiz');



define("CATALOG", 2);



define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/log.txt");
//AddMessage2Log($xxx);


function FVGetIP(){
	if(!empty($_SERVER['HTTP_CLIENT_IP'])){
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	}
	elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	else{
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}


function is_followers($product_id){
	return in_array($product_id, (array)($_SESSION['followers'] ?? []));
}
function set_followers(){
	if(!isset($_SESSION['followers'])){
		// cookie — ввод клиента: только целые ID через запятую
		$_SESSION['followers'] = (isset($_COOKIE['followers']) && is_string($_COOKIE['followers']))
			? array_values(array_unique(array_filter(array_map('intval', explode(',', $_COOKIE['followers'])))))
			: array();
	}
	else{
		// PHP 8: setcookie принимает только строку (массив = TypeError → 500 при клике на сердечко)
		setcookie('followers', implode(',', array_map('intval', (array)$_SESSION['followers'])), time() + 31536000, '/');
	}
}
function get_followers(){
	if(!isset($_SESSION['followers'])) $_SESSION['followers'] = [];
	return count($_SESSION['followers']);
}
// после старта сессии (init.php подключается раньше — восстановленное из cookie затиралось)
AddEventHandler('main', 'OnBeforeProlog', 'set_followers');

// Старая закладка админки Joomla (/administrator/?option=com_jshopping…&attempt=2) запоминалась как «последняя страница сайта»:
// кнопка перехода на сайт в админке вела туда, а на /administrator защита хостинга от перебора → 403. Ведём на главную.
AddEventHandler('main', 'OnBeforeProlog', function () {
	$isJoomla = function ($url) { return is_string($url) && stripos($url, '/administrator') === 0; };
	if (isset($_GET['back_url_pub']) && $isJoomla($_GET['back_url_pub'])) $_GET['back_url_pub'] = '/';
	$session = \Bitrix\Main\Application::getInstance()->getSession();
	if (isset($session['BACK_URL_PUB']) && $isJoomla($session['BACK_URL_PUB'])) $session['BACK_URL_PUB'] = '/';
});

function getHLList($HL){
	$reply = [];
	if(CModule::IncludeModule("highloadblock")){
		$hlblock = HL\HighloadBlockTable::getById($HL)->fetch();
		$entity = HL\HighloadBlockTable::compileEntity($hlblock);
		$entity_data_class = $entity->getDataClass();
		$rsData = $entity_data_class::getList(array(
			"select" => array("*"),
			"order" => array("ID" => "ASC"),
			"filter" => array()
		));
		while($arData = $rsData->Fetch()){
			if(isset($arData['UF_XML_ID'])) $reply[$arData['UF_XML_ID']] = $arData;
			else $reply[$arData['ID']] = $arData;
		}
	}
	return $reply;
}

function getUserFieldEnum($USER_FIELD_ID){
	$reply = [];
	if(CModule::IncludeModule("iblock")){
		$rsData = CUserFieldEnum::GetList([], ["USER_FIELD_ID" => $USER_FIELD_ID]);
		while($arEnum = $rsData->Fetch()){
			$reply[$arEnum["ID"]] = $arEnum["VALUE"];
		}
	}
	return $reply;
}


function IsAuthorized(){
	global $USER;
	return $USER->IsAuthorized();
}

function isMobile(){
	$detect = new MobileDetect;
	return $detect->isMobile();
}

AddEventHandler("sale", "OnOrderSave", "OrderMySave");
function OrderMySave($orderID, $fields, $orderFields){
	printOrderXLS($orderID);
}

function printOrderXLS($orderID, $output = false){
	
	//$PHPEXCELPATH = $_SERVER["DOCUMENT_ROOT"]. "/local/PHPExcel-1.8/Classes";
	//include_once($PHPEXCELPATH.'/PHPExcel.php');
	//include_once($PHPEXCELPATH.'/PHPExcel/Writer/Excel5.php');
	
	CModule::IncludeModule("sale");
	
	$order_id = $orderID;
	
	$order = \Bitrix\Sale\Order::load($order_id);
	$propertyCollection = $order->getPropertyCollection();
	
	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();
	$sheet->setTitle('AllPages');
	
	$sheet->getColumnDimension("A")->setWidth(80);
	$sheet->getColumnDimension("B")->setWidth(12);
	$sheet->getColumnDimension("C")->setWidth(12);
	$sheet->getColumnDimension("D")->setWidth(12);
	$sheet->getColumnDimension("E")->setWidth(12);
	
	$border = array(
		'borders'=>array(
			'allborders' => array(
				'style' => 'thin',
				'color' => array('rgb' => '000000')
			),
		)
	);
	
	
	$i = 1;
	
	$sheet->setCellValue("A".$i++, 'Номер заказа: ' . $order_id);
	$sheet->setCellValue("A".$i++, 'Дата заказа: ' . $order->getDateInsert());
	$sheet->setCellValue("A".$i++, 'Название компании: ' . $propertyCollection->getItemByOrderPropertyCode('COMPANY')->getValue());
	//$sheet->setCellValue("A".$i++, 'ИНН/КПП: ' . $propertyCollection->getItemByOrderPropertyCode('INN')->getValue() . '/' . $propertyCollection->getItemByOrderPropertyCode('KPP')->getValue());
	$sheet->setCellValue("A".$i++, 'Контактное лицо: ' . $propertyCollection->getItemByOrderPropertyCode('CONTACT_PERSON')->getValue());
	$sheet->setCellValue("A".$i++, 'Телефон: ' . $propertyCollection->getItemByOrderPropertyCode('PHONE')->getValue());
	if($propertyCollection->getItemByOrderPropertyCode('ADDRESS')){
		$sheet->setCellValue("A".$i++, 'Адрес доставки: ' . $propertyCollection->getItemByOrderPropertyCode('ADDRESS')->getValue());
	}
	//$propertyCollection->getItemByOrderPropertyCode('ZIP')->getValue() . ', ' . $propertyCollection->getItemByOrderPropertyCode('CITY')->getValue() . ', ' 
	$sheet->setCellValue("A".$i++, 'Комментарии покупателя: ' . $order->getField('USER_DESCRIPTION'));
	
	$i += 5;
	
	$start_table_i = $i;
	
	$sheet->setCellValue("A".$i, 'Наименование товара');
	$sheet->setCellValue("B".$i, 'Код товара');
	$sheet->setCellValue("C".$i, 'Количество');
	$sheet->setCellValue("D".$i, 'Цена');
	$sheet->setCellValue("E".$i, 'Сумма');
	
	$sheet->getStyle('A'.$i.":E".$i)->getFill()->setFillType('solid');
	$sheet->getStyle('A'.$i.":E".$i)->getFill()->getStartColor()->setRGB('EEEEEE');
	$sheet->getStyle('A'.$i.":E".$i)->getFont()->setBold(true);
	
	
	$sheet->getStyle('A'.$i)->getAlignment()->setHorizontal('center');
	$sheet->getStyle('B'.$i)->getAlignment()->setHorizontal('center');
	$sheet->getStyle('C'.$i)->getAlignment()->setHorizontal('center');
	$sheet->getStyle('D'.$i)->getAlignment()->setHorizontal('center');
	$sheet->getStyle('E'.$i)->getAlignment()->setHorizontal('center');
	
	$basket = $order->getBasket();
	$basketItems = $basket->getBasketItems();
	
	$i++;
	foreach($basketItems as $item){
		
		$obARTNUMBER = CIBlockElement::GetProperty(CATALOG, $item->getProductId(), [], ["CODE" => "ARTNUMBER"])->GetNext();
		$obZAL = CIBlockElement::GetProperty(CATALOG, $item->getProductId(), [], ["CODE" => "Zal"])->GetNext();
		
		$sheet->setCellValue("A".$i, $item->getField('NAME').', ' . $obZAL['VALUE_ENUM']);
		$sheet->setCellValue("B".$i, $obARTNUMBER['VALUE']);
		$sheet->getStyle('B'.$i)->getAlignment()->setHorizontal('center');
		$sheet->setCellValue("C".$i, $item->getQuantity());
		$sheet->getStyle('C'.$i)->getAlignment()->setHorizontal('center');
		$sheet->setCellValue("D".$i, number_format($item->getPrice(), 2, ',', ' '));
		$sheet->getStyle('D'.$i)->getAlignment()->setHorizontal('center');
		$sheet->setCellValue("E".$i, number_format($item->getFinalPrice(), 2, ',', ' '));
		$sheet->getStyle('E'.$i)->getAlignment()->setHorizontal('center');
		$i++;
	}
	
	$i += 2;
	
	$sheet->setCellValue("A".$i, 'Итого: ');
	$sheet->setCellValue("E".$i, number_format($order->getPrice() - $order->getDeliveryPrice(), 2, ',', ' '));
	$sheet->getStyle('E'.$i)->getAlignment()->setHorizontal('center');
	
	$sheet->getStyle('A'.$i.":E".$i)->getFill()->setFillType('solid');
	$sheet->getStyle('A'.$i.":E".$i)->getFill()->getStartColor()->setRGB('EEEEEE');
	$sheet->getStyle('A'.$i.":E".$i)->getFont()->setBold(true);
	
	$i++;
	$sheet->setCellValue("A".$i, 'Стоимость доставки: ');
	$sheet->setCellValue("E".$i, number_format($order->getDeliveryPrice(), 2, ',', ' '));
	$sheet->getStyle('E'.$i)->getAlignment()->setHorizontal('center');
	
	$sheet->getStyle('A'.$i.":E".$i)->getFill()->setFillType('solid');
	$sheet->getStyle('A'.$i.":E".$i)->getFill()->getStartColor()->setRGB('EEEEEE');
	$sheet->getStyle('A'.$i.":E".$i)->getFont()->setBold(true);
	
	$i++;
	$sheet->setCellValue("A".$i, 'Итого к оплате: ');
	$sheet->setCellValue("E".$i, number_format($order->getPrice(), 2, ',', ' '));
	$sheet->getStyle('E'.$i)->getAlignment()->setHorizontal('center');
	
	$sheet->getStyle('A'.$i.":E".$i)->getFill()->setFillType('solid');
	$sheet->getStyle('A'.$i.":E".$i)->getFill()->getStartColor()->setRGB('EEEEEE');
	$sheet->getStyle('A'.$i.":E".$i)->getFont()->setBold(true);
	
	$sheet->getStyle("A".$start_table_i.":E".$i)->applyFromArray($border);
	
	$writer = new Xlsx($spreadsheet);
	if($output){
		header("Cache-Control:must-revalidate,post-check=0,pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");
		header("Content-Disposition:attachment;filename=order_".$order_id.".xlsx");
		header("Content-Transfer-Encoding:binary");
		$writer->save("php://output");
	}
	else{
		$writer->save($_SERVER["DOCUMENT_ROOT"].'/upload/orders_xls/order_'.$order_id.'.xlsx');
	}
}






AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", "BeforeIBlockElementUpdate");
function BeforeIBlockElementUpdate(&$arFields){
	
	
	if(isset($arFields['DETAIL_PICTURE']) && !empty($arFields['DETAIL_PICTURE'])){
		$arFields['DETAIL_PICTURE']['name'] = mb_strtolower($arFields['DETAIL_PICTURE']['name']);
	}
	//AddMessage2Log($arFields);
	
	//https://api.telegram.org/bot6290360221:AAFixVpBGt7tgys99QM0WCY745iB4eWYoYk/setWebhook?url=http://yugtoys.ru/tg_bot.php
	//https://api.telegram.org/bot6290360221:AAFixVpBGt7tgys99QM0WCY745iB4eWYoYk/sendMessage?chat_id=@tupnichok26&text=Hi
	//https://api.telegram.org/bot6090380206:AAH94UKMH_N9Q5_-s8GoGK_j-agY8dbUjJc/sendMessage?chat_id=@yugtoys_ru&text=Hi
	//AddMessage2Log();
	
	
	
	//$token = '6290360221:AAFixVpBGt7tgys99QM0WCY745iB4eWYoYk';
	//$token = '6090380206:AAH94UKMH_N9Q5_-s8GoGK_j-agY8dbUjJc';
	
	// $response = array(
		// 'chat_id' => -1001977174349,
		// 'caption' => '[' . $arFields['NAME'] . '](' . 'https://yugtoys.ru/catalog/zhivotnye-nabory-zhivotnykh/2036-tantsuyushchaya-lyagushka/' . ')',
		// 'photo' => 'https://yugtoys.ru/upload/iblock/071/d7nseid82457lct7nhrhs7qwq4qcmlhg.jpg',
		// 'parse_mode' => 'markdown',
	// );
	
	// $ch = curl_init('https://api.telegram.org/bot' . $token . '/sendPhoto');
	// curl_setopt($ch, CURLOPT_POST, 1);
	// curl_setopt($ch, CURLOPT_POSTFIELDS, $response);
	// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	// curl_setopt($ch, CURLOPT_HEADER, false);
	// curl_exec($ch);
	// curl_close($ch);
	
	// $link = '[ ](' . 'https://yugtoys.ru/upload/iblock/071/d7nseid82457lct7nhrhs7qwq4qcmlhg.jpg' . ')' . '[' . $arFields['NAME'] . '](' . 'https://yugtoys.ru/catalog/zhivotnye-nabory-zhivotnykh/2036-tantsuyushchaya-lyagushka/' . ')';
	
	// $response = array(
		// 'chat_id' => -1001977174349,
		// 'text' => $link,
		// 'parse_mode' => 'markdown',
	// );
	
	// $ch = curl_init('https://api.telegram.org/bot' . $token . '/sendMessage');
	// curl_setopt($ch, CURLOPT_POST, 1);
	// curl_setopt($ch, CURLOPT_POSTFIELDS, $response);
	// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	// curl_setopt($ch, CURLOPT_HEADER, false);
	// curl_exec($ch);
	// curl_close($ch);
	
}

AddEventHandler("iblock", "OnBeforeIBlockElementAdd", "BeforeIBlockElementAdd");
function BeforeIBlockElementAdd(&$arFields){
	
}

AddEventHandler("iblock", "OnAfterIBlockElementAdd", "AfterIBlockElementAdd");
function AfterIBlockElementAdd(&$arFields){
	
	if($arFields['RESULT']){
		CModule::IncludeModule("iblock");
		$arElem = CIBlockElement::GetByID($arFields['RESULT'])->GetNext();
		
		//$token = '6290360221:AAFixVpBGt7tgys99QM0WCY745iB4eWYoYk';
		$token = '6090380206:AAH94UKMH_N9Q5_-s8GoGK_j-agY8dbUjJc';
		
		$response = array(
			'chat_id' => -1001468228627,
			'caption' => '[' . htmlspecialchars(strip_tags($arElem['NAME'])) . '](' . 'https://yugtoys.ru' . $arElem['DETAIL_PAGE_URL'] . ')',
			'photo' => 'https://yugtoys.ru' . CFile::GetPath($arElem['DETAIL_PICTURE']),
			'parse_mode' => 'markdown',
		);
		//AddMessage2Log($response);
		
		$ch = curl_init('https://api.telegram.org/bot' . $token . '/sendPhoto');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $response);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, false);
		$res = curl_exec($ch);
		
		//AddMessage2Log($res);
		curl_close($ch);
		
	}
	
}