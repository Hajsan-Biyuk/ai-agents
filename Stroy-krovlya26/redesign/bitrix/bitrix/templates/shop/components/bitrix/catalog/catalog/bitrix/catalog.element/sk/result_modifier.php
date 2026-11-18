<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?if (!CModule::IncludeModule('intec.startshop')) return;?>
<?
	use Bitrix\Iblock;

	$arEmptyPreview = null;
	$arDefaultParams = array(

	);

	$arParams = array_merge($arDefaultParams, $arParams);

 CStartShopTheme::ApplyTheme(SITE_ID);

 if (!empty($arResult['ID'])) {
 $arProduct = CStartShopCatalogProduct::GetByID(
 $arResult['ID'],
 array(),
 array(),
 ($arParams['USE_COMMON_CURRENCY'] == "Y" && !empty($arParams['CURRENCY']) ? $arParams['CURRENCY'] : false),
 $arParams['PRICE_CODE']
 )->Fetch();

 if (!empty($arProduct)) {
 $arResult['STARTSHOP'] = $arProduct['STARTSHOP'];
 }
 }

 $arResult['PICTURE'] = CStartShopToolsIBlock::GetItemPicture($arResult, 450, 450);
	$arResult['MORE_PHOTO'] = array();

 $arMorePictures = $arResult['PROPERTIES'][$arParams['PROPERTY_MORE_PICTURES']]['VALUE'];

	if (!empty($arParams['PROPERTY_MORE_PICTURES']))
		if (!empty($arMorePictures) && is_array($arMorePictures))
			foreach ($arMorePictures as $iPicture)
 $arResult['MORE_PHOTO'][] = CFile::GetPath($iPicture);

	$arResult['MORE_PHOTO_COUNT'] = count($arResult['MORE_PHOTO']);

 if (!empty($arResult['STARTSHOP']['OFFERS'])) {
 foreach ($arResult['STARTSHOP']['OFFERS'] as $iOfferID => $arOffer) {
 $arResult['STARTSHOP']['OFFERS'][$iOfferID]['PICTURE'] = CStartShopToolsIBlock::GetItemPicture($arOffer, 450, 450);
 $arResult['STARTSHOP']['OFFERS'][$iOfferID]['MORE_PHOTO'] = array();

 $arMorePictures = $arOffer['PROPERTIES'][$arParams['PROPERTY_MORE_PICTURES_OFFERS']]['VALUE'];

 if (!empty($arParams['PROPERTY_MORE_PICTURES_OFFERS']))
 if (!empty($arMorePictures) && is_array($arMorePictures))
 foreach ($arMorePictures as $iPicture)
 $arResult['STARTSHOP']['OFFERS'][$iOfferID]['MORE_PHOTO'][] = CFile::GetPath($iPicture);
 }
 }

 unset($arMorePictures, $iPicture, $iOfferID, $arOffer);

 $arPropertiesExtend = array(
 'PROPERTY_ARTICLE' => 'ARTICLE'
 );

 $arResult['TEMPLATE_PROPERTIES'] = array();

 foreach ($arPropertiesExtend as $sPropertyExtend => $sTemplateProperty) {
 $sPropertyExtendKey = $arParams[$sPropertyExtend];

 if (!empty($sPropertyExtendKey))
 if (!empty($arResult['PROPERTIES'][$sPropertyExtendKey]))
 $arResult['TEMPLATE_PROPERTIES'][$sTemplateProperty] = $arResult['PROPERTIES'][$sPropertyExtendKey];
 }
	
	$sComparePath = parse_url($arResult['COMPARE_URL']);
 $sComparePath = $sComparePath['path'];
 $arResult['COMPARE_REMOVE_URL'] = $sComparePath.'?'.$arParams['ACTION_VARIABLE'].'=DELETE_FROM_COMPARE_LIST&'.$arParams['PRODUCT_ID_VARIABLE'].'='.$arResult['ID'];
?><?
// Редизайн 2026: варианты товара (ТП, инфоблок 22) — покрытие, толщина, цена, доступные цвета (справочник HL «Tsveta»).
$arResult['SK_OFFERS'] = array();
$arResult['SK_COLORS'] = array();
if (!empty($arResult['ID'])) {
	if (defined('BX_COMP_MANAGED_CACHE')) $GLOBALS['CACHE_MANAGER']->RegisterTag('iblock_id_22');
	$skColorCodes = array();
	$rs = CIBlockElement::GetList(
		array('SORT' => 'ASC', 'ID' => 'ASC'),
		array('IBLOCK_ID' => 22, 'ACTIVE' => 'Y', 'PROPERTY_STARTSHOP_LINK' => $arResult['ID']),
		false, false,
		array('ID', 'IBLOCK_ID', 'NAME', 'PROPERTY_POKRYTIE', 'PROPERTY_TOLSHINA_LISTA', 'PROPERTY_STARTSHOP_PRICE_1', 'PROPERTY_OLD_PRICE')
	);
	while ($o = $rs->Fetch()) {
		$colors = array();
		$rc = CIBlockElement::GetProperty(22, $o['ID'], array(), array('CODE' => 'COLOR'));
		while ($c = $rc->Fetch()) if ($c['VALUE'] !== '' && $c['VALUE'] !== null) $colors[] = (string)$c['VALUE'];
		$skColorCodes = array_merge($skColorCodes, $colors);
		$arResult['SK_OFFERS'][] = array(
			'id' => (int)$o['ID'],
			'coat' => (string)$o['PROPERTY_POKRYTIE_VALUE'],
			'thick' => (string)$o['PROPERTY_TOLSHINA_LISTA_VALUE'],
			'price' => (float)$o['PROPERTY_STARTSHOP_PRICE_1_VALUE'],
			'old' => (float)preg_replace('/[^0-9.]/', '', str_replace(',', '.', (string)$o['PROPERTY_OLD_PRICE_VALUE'])),
			'colors' => $colors,
		);
	}
	$skColorCodes = array_values(array_unique($skColorCodes));
	if ($skColorCodes && \Bitrix\Main\Loader::includeModule('highloadblock')) {
		$hl = \Bitrix\Highloadblock\HighloadBlockTable::getList(array('filter' => array('=TABLE_NAME' => 'b_hlbd_tsveta')))->fetch();
		if ($hl) {
			$cls = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hl)->getDataClass();
			$rows = $cls::getList(array('filter' => array('=UF_XML_ID' => $skColorCodes), 'order' => array('UF_SORT' => 'ASC', 'UF_NAME' => 'ASC')));
			while ($row = $rows->fetch()) {
				$arResult['SK_COLORS'][$row['UF_XML_ID']] = array(
					'name' => $row['UF_NAME'],
					'img' => $row['UF_FILE'] ? CFile::GetPath($row['UF_FILE']) : '',
				);
			}
		}
	}
	unset($rs, $o, $rc, $c, $colors, $skColorCodes, $hl, $cls, $rows, $row);
}
?>
