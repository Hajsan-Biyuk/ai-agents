<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogSectionComponent $component
 */

$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();

Bitrix\Main\Loader::includeModule("catalog");
Bitrix\Main\Loader::includeModule("sale");


$arrMetaFilter = array();
foreach($GLOBALS['arrFilter'] as $key=>$val){
    $property_enums = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID" => CATALOG, "ID" => $val));
    while($enum_fields = $property_enums->GetNext()){
        $arrMetaFilter[$enum_fields["PROPERTY_CODE"]]["NAME"] = $enum_fields["PROPERTY_NAME"];
        $arrMetaFilter[$enum_fields["PROPERTY_CODE"]]["VALUE"][] = $enum_fields["VALUE"];
        $arrMetaFilter[$enum_fields["PROPERTY_CODE"]]["ALL"][] = $enum_fields;
    }
}
$arResult['arrMetaFilter'] = $arrMetaFilter;


$basket = Bitrix\Sale\Basket::loadItemsForFUser(Bitrix\Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());
$basketItems = $basket->getBasketItems();
$arResult['basketItems'] = [];
foreach($basketItems as $basketItem){
	$arResult['basketItems'][$basketItem->getProductId()] = $basketItem->getQuantity();
}