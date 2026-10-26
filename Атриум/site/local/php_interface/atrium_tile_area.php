<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

function atrium_is_tile_area_measure($code, $text)
{
	$code = trim((string)$code);
	if (in_array($code, array('7', '55', '055'), true)) {
		return true;
	}

	$text = mb_strtolower(trim((string)$text));
	$text = str_replace(array(' ', '²', '.'), array('', '2', ''), $text);

	return in_array($text, array('м2', 'квм', 'm2'), true);
}

function atrium_tile_quantity_label($quantity, $measureCode, $measureText, $ratio)
{
	if (!atrium_is_tile_area_measure($measureCode, $measureText)) {
		return null;
	}

	$quantity = (float)$quantity;
	$ratio = (float)$ratio;
	if ($quantity <= 0 || $ratio <= 0 || abs($ratio - 1) < 0.00001) {
		return null;
	}

	$pieces = (int)round($quantity / $ratio);
	if ($pieces < 1) {
		return null;
	}

	$area = number_format($quantity, 2, ',', '');

	return $pieces.' шт., '.$area.' м²';
}

function atrium_apply_tile_area_to_order(&$arResult)
{
	if (empty($arResult['JS_DATA']['GRID']['ROWS']) || !CModule::IncludeModule('catalog')) {
		return;
	}

	$productIds = array();
	foreach ($arResult['JS_DATA']['GRID']['ROWS'] as $row) {
		$productId = (int)($row['data']['PRODUCT_ID'] ?? 0);
		if ($productId > 0) {
			$productIds[$productId] = $productId;
		}
	}
	if (!$productIds) {
		return;
	}

	$ratios = \Bitrix\Catalog\MeasureRatioTable::getCurrentRatio(array_values($productIds));
	foreach ($arResult['JS_DATA']['GRID']['ROWS'] as &$row) {
		$data = isset($row['data']) && is_array($row['data']) ? $row['data'] : array();
		$productId = (int)($data['PRODUCT_ID'] ?? 0);
		$measureText = (string)($data['MEASURE_TEXT'] ?? $data['MEASURE_NAME'] ?? '');
		$label = atrium_tile_quantity_label(
			$data['QUANTITY'] ?? 0,
			$data['MEASURE_CODE'] ?? '',
			$measureText,
			$ratios[$productId] ?? 0
		);
		if ($label === null) {
			continue;
		}
		if (!isset($row['columns']) || !is_array($row['columns'])) {
			$row['columns'] = array();
		}
		$row['columns']['QUANTITY'] = '<span class="atrium-tile-qty">'.htmlspecialcharsbx($label).'</span>';
	}
	unset($row);
}

function atrium_hide_order_extra_props(&$arResult)
{
	if (empty($arResult['JS_DATA']['ORDER_PROP']['properties']) || !is_array($arResult['JS_DATA']['ORDER_PROP']['properties'])) {
		return;
	}

	$hide = array('POD', 'RAZGRUZKA', 'USTANOVKA');
	$properties = array();
	$usedGroups = array();
	foreach ($arResult['JS_DATA']['ORDER_PROP']['properties'] as $property) {
		$code = isset($property['CODE']) ? (string)$property['CODE'] : '';
		if (in_array($code, $hide, true)) {
			continue;
		}
		$properties[] = $property;
		if (isset($property['PROPS_GROUP_ID'])) {
			$usedGroups[(string)$property['PROPS_GROUP_ID']] = true;
		}
	}
	$arResult['JS_DATA']['ORDER_PROP']['properties'] = $properties;

	if (empty($arResult['JS_DATA']['ORDER_PROP']['groups']) || !is_array($arResult['JS_DATA']['ORDER_PROP']['groups'])) {
		return;
	}
	$groups = array();
	foreach ($arResult['JS_DATA']['ORDER_PROP']['groups'] as $group) {
		if (!empty($usedGroups[(string)$group['ID']])) {
			$groups[] = $group;
		}
	}
	$arResult['JS_DATA']['ORDER_PROP']['groups'] = $groups;
}

function atrium_order_prop_text($order, $code)
{
	$property = $order->getPropertyCollection()->getItemByOrderPropertyCode($code);
	if (!$property) {
		return '';
	}
	$value = $property->getValue();
	if (is_array($value)) {
		$value = implode(', ', array_filter(array_map('strval', $value), 'strlen'));
	}

	return trim((string)$value);
}

function atrium_fill_order_mail_fields($order, &$arFields)
{
	$phone = atrium_order_prop_text($order, 'PHONE');
	$city = atrium_order_prop_text($order, 'CITY');
	$address = atrium_order_prop_text($order, 'ADDRESS');
	if ($city !== '' && ($address === '' || mb_stripos($address, $city) === false)) {
		$address = $city.($address !== '' ? ', '.$address : '');
	}
	if ($phone !== '') {
		$arFields['PHONE'] = $phone;
	}
	if ($address !== '') {
		$arFields['ADDRESS'] = $address;
	}

	$payments = array();
	foreach ($order->getPaymentCollection() as $payment) {
		if (method_exists($payment, 'isInner') && $payment->isInner()) {
			continue;
		}
		$name = trim((string)$payment->getPaymentSystemName());
		if ($name !== '') {
			$payments[] = $name;
		}
	}
	if ($payments) {
		$arFields['PAY_SYSTEM'] = implode(', ', $payments);
	}

	$deliveries = array();
	foreach ($order->getShipmentCollection() as $shipment) {
		if ($shipment->isSystem()) {
			continue;
		}
		$name = trim((string)$shipment->getDeliveryName());
		if ($name !== '') {
			$deliveries[] = $name;
		}
	}
	if ($deliveries) {
		$arFields['DELIVERY'] = implode(', ', $deliveries);
	}

	$comment = trim((string)$order->getField('USER_DESCRIPTION'));
	$arFields['ORDER_COMMENT'] = '';
	if ($comment !== '') {
		$safe = nl2br(htmlspecialchars($comment, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));
		$arFields['ORDER_COMMENT'] = '<br>Комментарий к заказу: '.$safe;
	}

	unset($arFields['RAZGRUZKA'], $arFields['USTANOVKA'], $arFields['POD']);
}

function atriumOnOrderNewSendEmail($orderId, &$eventName, &$arFields)
{
	if (!CModule::IncludeModule('sale')) {
		return;
	}

	$order = \Bitrix\Sale\Order::load((int)$orderId);
	if (!$order) {
		return;
	}

	atrium_fill_order_mail_fields($order, $arFields);

	if (!$order->getBasket() || !CModule::IncludeModule('catalog')) {
		return;
	}

	$productIds = array();
	foreach ($order->getBasket() as $basketItem) {
		$productId = (int)$basketItem->getProductId();
		if ($productId > 0) {
			$productIds[$productId] = $productId;
		}
	}
	$ratios = $productIds ? \Bitrix\Catalog\MeasureRatioTable::getCurrentRatio(array_values($productIds)) : array();

	foreach ($order->getBasket() as $basketItem) {
		$measureName = trim((string)$basketItem->getField('MEASURE_NAME'));
		$label = atrium_tile_quantity_label(
			$basketItem->getQuantity(),
			$basketItem->getField('MEASURE_CODE'),
			$measureName,
			$ratios[(int)$basketItem->getProductId()] ?? 0
		);
		if ($label === null) {
			continue;
		}

		$name = (string)$basketItem->getField('NAME');
		$pattern = '/('.preg_quote($name, '/').'(?: \[[^\]]*\])? - )[\d]+(?:[.,]\d+)?(?:\s+\S+)?/u';
		$updated = preg_replace($pattern, '${1}'.$label, $arFields['ORDER_LIST'], 1, $count);
		if ($count) {
			$arFields['ORDER_LIST'] = $updated;
		}
	}
}
