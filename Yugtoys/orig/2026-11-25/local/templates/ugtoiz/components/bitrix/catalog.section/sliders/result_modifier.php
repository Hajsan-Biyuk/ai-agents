<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogSectionComponent $component
 */

$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();

Bitrix\Main\Loader::includeModule("catalog");
Bitrix\Main\Loader::includeModule("sale");

$basket = Bitrix\Sale\Basket::loadItemsForFUser(Bitrix\Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());
$basketItems = $basket->getBasketItems();
$arResult['basketItems'] = [];
foreach($basketItems as $basketItem){
	$arResult['basketItems'][$basketItem->getProductId()] = $basketItem->getQuantity();
}