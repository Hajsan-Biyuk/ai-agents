
<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arParams
 * @var array $arResult
 * @var SaleOrderAjax $component
 */

$component = $this->__component;
$component::scaleImages($arResult['JS_DATA'], $arParams['SERVICES_IMAGES_SCALING']);

foreach($arResult['BASKET_ITEMS'] as $key => $item)
{
	$koef = array_values(\Bitrix\Catalog\MeasureRatioTable::getCurrentRatio($item['PRODUCT_ID']));
	$arResult['BASKET_ITEMS'][$key]['QUANTITY'] = $item['QUANTITY'] / $koef[0];
}
unset($key, $item);

$o = 0;
foreach($arResult['ITEMS_DIMENSIONS'] as $key => $d)
{
		// из мм в метры
	$width = $d['WIDTH']/1000;
	$height = $d['HEIGHT']/1000;
	$length = $d['LENGTH']/1000;
	
    $f = ( $width * $height * $length ) * $arResult['BASKET_ITEMS'][$key]['QUANTITY'];
	//$f = ( ( $width * $length ) * $height ) * $arResult['BASKET_ITEMS'][$key]['QUANTITY'];
	
    $o = $o + $f;
}
$arResult['JS_DATA']['TOTAL']['O'] = $o.' м3'; // round($o, 2);

if($USER->IsAdmin())
{
	//echo "<pre style='color:#02f702; background:#414354;'>";print_r($arResult['JS_DATA']['TOTAL']['O']);echo "</pre>";
	//echo "<pre style='color:#02f702; background:#414354;'>";print_r($arResult['BASKET_ITEMS']);echo "</pre>";
	//echo "<pre style='color:#02f702; background:#414354;'>";print_r($arResult['ITEMS_DIMENSIONS']);echo "</pre>";
}