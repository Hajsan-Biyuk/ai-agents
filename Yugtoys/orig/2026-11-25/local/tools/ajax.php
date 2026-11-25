<?php
define("NO_KEEP_STATISTIC", true); // Не собираем стату по действиям AJAX
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if(isset($_REQUEST['action']) && !empty($_REQUEST['action'])){
	switch($_REQUEST['action']){
		case 'followers-add':
			echo add_followers($_REQUEST);
		break;
		case 'followers-delete':
			echo delete_followers($_REQUEST);
		break;
		case 'followers-reset':
			echo reset_followers();
		break;
		case 'send-form':
			header("Content-type: application/json; charset=utf-8");
			echo sendForm($_POST);
		break;
		case 'buy-product':
			header("Content-type: application/json; charset=utf-8");
			echo buy_product($_REQUEST);
		break;
		case 'create-order-retail':
			header("Content-type: application/json; charset=utf-8");
			echo create_order_retail($_REQUEST);
		break;
		case 'unactive-users':
			echo unactive_users();
		break;
		default:
			echo 'error actions';
		break;
	}
}
else{
	echo 'error actions';
}

function create_order_retail($param){
	global $USER;
	
	\Bitrix\Main\Loader::includeModule("catalog");
	\Bitrix\Main\Loader::includeModule("sale");
	
	$reply = array(
		'status' => 'error',
		'msg' => 'Ошибка',
		'filds' => [],
	);
	$currency = \Bitrix\Currency\CurrencyManager::getBaseCurrency();
	$basket = \Bitrix\Sale\Basket::loadItemsForFUser(\Bitrix\Sale\Fuser::getId(), SITE_ID);
	$availableBasket = $basket->getOrderableItems();
	
	if(count($availableBasket) <= 0){
		$reply = array(
			'status' => 'error',
			'filds' => [],
			'msg' => 'Ваша корзина пуста',
		);
	}
	else{
		$order_user_id = 1757;
		
		if($order = \Bitrix\Sale\Order::create(SITE_ID, $order_user_id, $currency)){
			
			$arUser = CUser::GetByID($order_user_id)->Fetch();
			
			$order->setPersonTypeId(2);
			
			$basket = \Bitrix\Sale\Basket::loadItemsForFUser(\Bitrix\Sale\Fuser::getId(), SITE_ID);
			$availableBasket = $basket->getOrderableItems();
			$order->setBasket($availableBasket);
			
			$shipmentCollection = $order->getShipmentCollection();
			$shipment = $shipmentCollection->createItem();
			$service = Bitrix\Sale\Delivery\Services\Manager::getById(3);
			
			$shipment->setField('CURRENCY', 'RUB');
			
			$shipment->setFields(['DELIVERY_ID' => $service['ID'], 'DELIVERY_NAME' => $service['NAME'], 'CURRENCY' => 'RUB']);
			$shipmentItemCollection = $shipment->getShipmentItemCollection();
			foreach($availableBasket as $basketItem) {
				$item = $shipmentItemCollection->createItem($basketItem);
				$item->setQuantity($basketItem->getQuantity());
			}
			
			$paymentCollection = $order->getPaymentCollection();
			$payment = $paymentCollection->createItem();
			$paySystemService = Bitrix\Sale\PaySystem\Manager::getObjectById(2);
			$payment->setFields(array(
				'PAY_SYSTEM_ID' => $paySystemService->getField("PAY_SYSTEM_ID"),
				'PAY_SYSTEM_NAME' => $paySystemService->getField("NAME"),
				'SUM'=> $order->getPrice(),
			));
			
			$propertyCollection = $order->getPropertyCollection();
			$propertyCodeToId = array();
			
			foreach($propertyCollection as $propertyValue) $propertyCodeToId[$propertyValue->getField('CODE')] = $propertyValue->getField('ORDER_PROPS_ID');
			
			$propertyValue = $propertyCollection->getItemByOrderPropertyId(13);
			$propertyValue->setValue($arUser['EMAIL']);
			
			//$propertyValue = $propertyCollection->getItemByOrderPropertyId($propertyCodeToId['FIO']);
			//$propertyValue->setValue($param['NAME']);

			$propertyValue = $propertyCollection->getItemByOrderPropertyId(12);
			$propertyValue->setValue($param['NAME']);
			
			$propertyValue = $propertyCollection->getItemByOrderPropertyId(14);
			$propertyValue->setValue($param['PHONE']);

			$propertyValue = $propertyCollection->getItemByOrderPropertyId(8);
			$propertyValue->setValue('Розничный покупатель');
			
			//$propertyValue = $propertyCollection->getItemByOrderPropertyId(19);
			//$propertyValue->setValue('Самовывоз');

			
			
			$order->setField('USER_DESCRIPTION', $param['COMMENTS']);
			
			$order->doFinalAction(true);
			$result = $order->save();
			if($result->isSuccess()){
				$orderID = $order->getId();
				$accountNumber = $order->getField('ACCOUNT_NUMBER');
				$reply = array(
					'status' => 'success',
					'msg' => 'Заказ создан',
					'accountNumber' => $accountNumber,
					'filds' => [],
				);
			}
			else{
				$reply = array(
					'reload' => 'Y',
					'status' => 'error',
					'msg' => "Ошибка создания заказа: " . implode(", ",$result->getErrorMessages()),
					'filds' => [],
				);
			}
		}
	}
	return json_encode($reply);
}

function unactive_users(){//   /local/tools/ajax.php?action=unactive-users
	global $USER;
	CModule::IncludeModule('sale');
	
	$rsUsers = CUser::GetList([$by = "ID"], [$order = "desc"], false);
	while($arUser = $rsUsers->Fetch()){
		if(in_array(1, CUser::GetUserGroup($arUser['ID']))) continue;
		
		$dbOrders = CSaleOrder::GetList(['ID' => 'desc'], ['USER_ID' => $arUser['ID']], false, ['nTopCount' => '1'], ['ID']);
		while($arOrder = $dbOrders->Fetch()){
			$userHasOrder = $arOrder['ID'];
		}
		if(!$userHasOrder){
			$obUser = new CUser;
			$obUser->Update($arUser['ID'], ["ACTIVE" => "N"]);
		}
		unset($userHasOrder);
	}
}

function add_followers($param){
	
	$followers = $_SESSION['followers'];
	$followers[] = (int)$param['product_id'];
	array_unique($followers);
	$_SESSION['followers'] = $followers;
	
	set_followers();
	
	return count($_SESSION['followers']);
}

function delete_followers($param){
	
	$followers = array();
	foreach($_SESSION['followers'] as $elem){
		if($elem <> (int)$param['product_id']) $followers[] = $elem;
	}
	$_SESSION['followers'] = $followers;
	
	set_followers();
	
	return count($_SESSION['followers']);
}

function reset_followers(){
	
	$_SESSION['followers'] = array();
	set_followers();
	
	return count($_SESSION['followers']);
}

function sendForm($param){
	global $USER;
	
	$form_param = $param;
	$temp_table ='';
	
	$reply = array(
		'status' => 'success',//error
		'fields' => array(),
		'msg' => 'Ваше сообщение успешно отправлено',
		'param' => $form_param,
	);
	
	$fileId = false;
	if($_FILES['file']) $fileId = CFile::SaveFile($_FILES['file'], "mailatt");
	
	if($reply['status'] == 'success'){
		$form_title = $form_param['form-title'];
		$form_name = $form_param['form-name'];
		unset($form_param['action']);
		unset($form_param['form-name']);
		unset($form_param['form-title']);
		
		$form_param['USER_IP'] = FVGetIP();
		$form_param['DATE'] = date('Y-m-d H:i:s');
		$form_param['HTTP_REFERER'] = $_SERVER["HTTP_REFERER"];
		
		if($form_name == 'registration'){
			
			$rsUsers = CUser::GetList(($by="id"), ($order="desc"), ['LOGIC' => 'OR', 'WORK_PROFILE' => $form_param['INN'] . '/' . $form_param['OGRN'], 'WORK_PROFILE' => $form_param['INN'] . '%', 'WORK_PROFILE' => '%' . $form_param['OGRN']]);
			if($arUser = $rsUsers->GetNext()){
				$reply = array(
					'status' => 'error',
					'fields' => array(),
					'msg' => 'Организация ' . $form_param['INN'] . '/' . $form_param['OGRN'] . ' уже зарегестрированна.',
					'param' => $form_param,
				);
				return json_encode($reply);
			}
			
			$user = new CUser;
			//$U_NAME = explode(' ', $form_param['NAME']);
			$arFields = Array(
				"WORK_COMPANY" => $form_param['COMPANY'],
				"WORK_PHONE" => $form_param['PHONE'],
				"WORK_CITY" => $form_param['CITY'],
				"WORK_PROFILE" => $form_param['INN'] . '/' . $form_param['OGRN'],
				"NAME" => $form_param['NAME'],
				"LAST_NAME" => $form_param['LAST_NAME'],
				//"SECOND_NAME" => $U_NAME[2],
				"PERSONAL_PHONE" => $form_param['PHONE'],
				"EMAIL"             => $form_param['EMAIL'],
				"LOGIN"             => $form_param['U_LOGIN'],
				"ACTIVE"            => "N",
				"GROUP_ID"          => array(2, 6),
				"PASSWORD"          => $form_param['U_PASSWORD'],
				"CONFIRM_PASSWORD"  => $form_param['UC_PASSWORD'],
			);
			$ID = $user->Add($arFields);
			if(intval($ID) > 0){
				$reply = array(
					'status' => 'success',
					'fields' => array(),
					'msg' => 'Успешная регистрация. Наш менеджер свяжется с Вами в ближайшее время.',
				);
				
				\Bitrix\Main\Mail\Event::send(array(
					"EVENT_NAME" => "SEND_REGISTRATION_OPT",
					"LID" => "s1",
					"C_FIELDS" => array(
						'EMAIL' => $form_param['EMAIL'],
						'LOGIN' => $form_param['U_LOGIN'],
						'PASSWORD' => $form_param['U_PASSWORD'],
					)
				));
			}
			else{
				$reply = array(
					'status' => 'error',
					'fields' => array(),
					'msg' => $user->LAST_ERROR,
					'param' => $form_param,
				);
				return json_encode($reply);
			}
		}
		elseif($form_name == 'login_lk'){
			global $USER;
			if(!is_object($USER)) $USER = new CUser;
			$arAuthResult = $USER->Login($form_param['U_LOGIN'], $form_param['U_PASSWORD'], "Y");
			if($arAuthResult['TYPE'] == "ERROR"){
				$reply = array(
					'status' => 'error',
					'fields' => array(),
					'msg' => strip_tags($arAuthResult['MESSAGE']),
					'param' => $form_param,
				);
			}
			else{
				$reply = array(
					'status' => 'success',
					'fields' => array(),
					'msg' => '',
				);
			}

			return json_encode($reply);
		}
		
		if($fileId){
			\Bitrix\Main\Mail\Event::sendImmediate(array(
				"EVENT_NAME" => "SEND_FORM",
				"LID" => "s1",
				"C_FIELDS" => array(
					'EMAIL' => 'sale1@yugtoys.ru',//COption::GetOptionString("main", "email_from"),
					'TITLE' => $form_title,
					'FIELDS' => implode('<br> ', $form_param),
				),
				"FILE" => array($fileId),
			));
			CFile::Delete($fileId);
		}
		else{
			\Bitrix\Main\Mail\Event::send(array(
				"EVENT_NAME" => "SEND_FORM",
				"LID" => "s1",
				"C_FIELDS" => array(
					'EMAIL' => 'sale1@yugtoys.ru',//COption::GetOptionString("main", "email_from"),
					'TITLE' => $form_title,
					'FIELDS' => implode('<br> ', $form_param),
					'TABLE_PRODUCTS' => $temp_table,
				)
			));
		}
	}
	
	return json_encode($reply);
	
}

function buy_product($param){
	$reply = [
		'msg' => 'Ошибка добавления товара в корзину',
		'status' => 'error',
	];
	if(!empty((int)$param['product_id'])){
		CModule::IncludeModule("sale");
		$quantity = !empty((int)$param['quantity']) ? (int)$param['quantity'] : 1;
		
		$arProduct = CCatalogProduct::GetByID((int)$param['product_id']);
		$dbBasketItems = CSaleBasket::GetList(["ID" => "ASC"], ["FUSER_ID" => CSaleBasket::GetBasketUserID(), "LID" => SITE_ID, "PRODUCT_ID" => (int)$param['product_id'], "ORDER_ID" => "NULL"], false, false, ["*"]);
		if($arItems = $dbBasketItems->Fetch()){
			$arProduct['QUANTITY'] = $arProduct['QUANTITY'] - $arItems['QUANTITY'];
		}
		
		if($arProduct['QUANTITY'] >= $quantity){
			$result = Add2BasketByProductID((int)$param['product_id'], $quantity);
			if(!$result){
				if($ex = $APPLICATION->GetException()) echo $ex->GetString();
			}
			else{
				$reply = [
					'msg' => 'Товар добавлен в корзину',
					'status' => 'ok',
				];
			}
		}
		else{
			$reply['msg'] .= ': доступное кол-во ' . $arProduct['QUANTITY'];
		}
	}
	echo json_encode($reply);
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>