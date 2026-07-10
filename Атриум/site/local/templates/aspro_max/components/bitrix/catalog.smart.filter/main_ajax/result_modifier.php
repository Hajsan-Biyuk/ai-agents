<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if ($_GET['dev'] == 1) {
	// echo '<pre>';
	// var_dump($arResult["ITEMS"][1471]);
	// 	echo '</pre>';
}

if (in_array($arResult["SECTION"]['ID'], [744, 947, 747, 746, 745])) {
	$arResult["ITEMS"][1471]["NAME"] = "Наличие экрана";
	$arResult["ITEMS"][1471]["~NAME"] = "Наличие экрана";	
}

if($arResult['ITEMS'])
{
	foreach($arResult['ITEMS'] as &$arItem){
		/*Удаляем ненужные фильтры в разделах*/
		if(
			($arItem["ID"] == 1383 && $arParams['SECTION_ID'] == 328) ||
			($arItem["ID"] == 1328 && $arParams['SECTION_ID'] == 327) ||
			($arItem["ID"] == 927 && $arParams['SECTION_ID'] == 327) ||
			($arItem["ID"] == 1471 && $arParams['SECTION_ID'] == 319) ||
			($arItem["ID"] == 1368 && $arParams['SECTION_ID'] == 361) ||
			($arItem["ID"] == 1461 && $arParams['SECTION_ID'] == 477)
			){
				unset($arResult['ITEMS'][$arItem["ID"]]);
			}

	}


	$arParams["POPUP_POSITION"] = (isset($arParams["POPUP_POSITION"]) && in_array($arParams["POPUP_POSITION"], array("left", "right"))) ? $arParams["POPUP_POSITION"] : "left";
	foreach($arResult["ITEMS"] as $key => $arItem)
	{
		/*unset empty values*/
		if (
			(
			 ($arItem["DISPLAY_TYPE"] == "A" || isset($arItem["PRICE"]))
			 && ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0)
			)
			|| !$arItem["VALUES"]
		)
			unset($arResult["ITEMS"][$key]);
		/**/
		
		if( $arItem['PROPERTY_TYPE'] === 'L' ){
			$arPropInline[] = $arItem['ID'];
			$arPropInlineName[$arItem['ID']] = $arItem['NAME'];
		}

		if(isset($arItem['PRICE']) && $arItem['PRICE'])
		{
			if(isset($arItem['VALUES']['MIN']['HTML_VALUE']) || $arItem['VALUES']['MAX']['HTML_VALUE'])
			{
				$arResult['PRICE_SET'] = 'Y';
				break;
			}
		}

		$i = 0;

		if($arItem['PROPERTY_TYPE'] == 'S' || $arItem['PROPERTY_TYPE'] == 'L' || $arItem['PROPERTY_TYPE'] == 'E')
		{
			foreach($arItem['VALUES'] as $arValue)
			{
				if(isset($arValue['CHECKED']) && $arValue['CHECKED'])
				{
					$arResult["ITEMS"][$key]['PROPERTY_SET'] = 'Y';
					++$i;
				}
			}

			if($i)
			{
				$arResult["ITEMS"][$key]['COUNT_SELECTED'] = $i;
			}
		}

		if($arItem['PROPERTY_TYPE'] == 'N')
		{
			foreach($arItem['VALUES'] as $arValue)
			{
				if(isset($arValue['HTML_VALUE']) && $arValue['HTML_VALUE'])
				{
					$arResult['ITEMS'][$key]['PROPERTY_SET'] = 'Y';
				}
			}
		}
	}
	$resultEnum = Bitrix\Iblock\PropertyEnumerationTable::getList([
		'select' => ['PROPERTY_ID', 'COUNT'],
		'group' => ['PROPERTY_ID'],
		'filter' => ['=COUNT' => 1, 'PROPERTY_ID' => $arPropInline],
		'runtime' => array(
		new Bitrix\Main\Entity\ExpressionField('COUNT', 'COUNT(*)')
		)
	]);
	while ($rowEnum = $resultEnum->fetch()){
		if(is_array($arResult["ITEMS"][$rowEnum['PROPERTY_ID']]["VALUES"]))
			sort($arResult["ITEMS"][$rowEnum['PROPERTY_ID']]["VALUES"]);
		if($arResult["ITEMS"][$rowEnum['PROPERTY_ID']]["VALUES"])
			$arResult["ITEMS"][$rowEnum['PROPERTY_ID']]["VALUES"][0]["VALUE"] = $arPropInlineName[$rowEnum['PROPERTY_ID']];
		$arResult['ITEMS'][$rowEnum['PROPERTY_ID']]['IS_PROP_INLINE'] = true;
	}
}

\Bitrix\Main\Localization\Loc::loadLanguageFile(__FILE__);

if (!$arResult['ITEMS']) {
	$arResult['EMPTY_ITEMS'] = true;
}

// sort
if ($arParams['SHOW_SORT']) {
	include 'sort.php';
}

global $sotbitFilterResult;
$sotbitFilterResult = $arResult;
if( \Bitrix\Main\Loader::includeModule('dwstroy.seochpulite') ){
\Dwstroy\SeoChpuLite\Helper::returnNewUrl($arParams, $arResult);
}