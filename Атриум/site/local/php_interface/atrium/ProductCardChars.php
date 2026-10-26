<?php
/**
 * Mega-menu tile attributes → product «Характеристики».
 * Empty values are skipped. Does not change the photo-side list-features block
 * if called after that ViewTarget is closed.
 */

if (!function_exists('atriumCardCharAdd')) {
	function atriumCardCharAdd(array &$display, $code, $name, $value)
	{
		$value = trim((string) $value);
		if ($value === '' || $name === '') {
			return;
		}
		$html = htmlspecialcharsbx($value);
		if (isset($display[$code]) && is_array($display[$code])) {
			$display[$code]['NAME'] = $name;
			$display[$code]['VALUE'] = $value;
			$display[$code]['~VALUE'] = $value;
			$display[$code]['DISPLAY_VALUE'] = $html;
			return;
		}
		$display[$code] = [
			'ID' => 0,
			'CODE' => $code,
			'NAME' => $name,
			'VALUE' => $value,
			'~VALUE' => $value,
			'DISPLAY_VALUE' => $html,
		];
	}
}

if (!function_exists('atriumCardCharPropText')) {
	function atriumCardCharPropText($iblockId, $elementId, $code, array $arResult = [])
	{
		$vals = [];
		$propMeta = $arResult['PROPERTIES'][$code] ?? null;
		$rows = [];
		$userType = '';

		$hasValue = false;
		if (is_array($propMeta)) {
			$userType = (string) ($propMeta['USER_TYPE'] ?? '');
			$raw = $propMeta['VALUE'] ?? '';
			if (is_array($raw)) {
				$hasValue = implode('', array_map('strval', $raw)) !== '';
			} else {
				$hasValue = trim((string) $raw) !== '';
			}
			if ($hasValue) {
				if (!empty($propMeta['VALUE_ENUM'])) {
					$enums = $propMeta['VALUE_ENUM'];
					if (!is_array($enums)) {
						$enums = [$enums];
					}
					foreach ($enums as $e) {
						$e = trim((string) $e);
						if ($e !== '') {
							$vals[] = $e;
						}
					}
				}
				if (!$vals) {
					$rawList = is_array($raw) ? $raw : [$raw];
					foreach ($rawList as $v) {
						$v = trim((string) $v);
						if ($v !== '') {
							$vals[] = $v;
						}
					}
				}
			}
		}

		if (!$vals) {
			$rs = CIBlockElement::GetProperty((int) $iblockId, (int) $elementId, ['sort' => 'asc'], ['CODE' => $code]);
			while ($row = $rs->Fetch()) {
				$rows[] = $row;
				$userType = (string) ($row['USER_TYPE'] ?? $userType);
				$piece = '';
				if (!empty($row['VALUE_ENUM'])) {
					$piece = (string) $row['VALUE_ENUM'];
				} elseif (isset($row['VALUE'])) {
					$v = $row['VALUE'];
					if (is_array($v)) {
						$v = implode(', ', array_filter(array_map('trim', $v)));
					}
					$piece = trim((string) $v);
				}
				if ($piece !== '') {
					$vals[] = $piece;
				}
			}
		}

		$vals = array_values(array_unique($vals));
		if (!$vals) {
			return '';
		}

		if ($userType === 'directory' || $code === 'COLOR_REF2') {
			$vals = array_map('atriumCardCharColorName', $vals);
			$vals = array_values(array_filter($vals, static function ($v) {
				return $v !== '';
			}));
		}

		return implode(', ', $vals);
	}
}

if (!function_exists('atriumCardCharColorName')) {
	function atriumCardCharColorName($xmlId)
	{
		static $map = null;
		$xmlId = trim((string) $xmlId);
		if ($xmlId === '') {
			return '';
		}
		if ($map === null) {
			$map = [];
			if (\Bitrix\Main\Loader::includeModule('highloadblock')) {
				$prop = CIBlockProperty::GetList([], ['IBLOCK_ID' => 61, 'CODE' => 'COLOR_REF2'])->Fetch();
				$settings = $prop['USER_TYPE_SETTINGS'] ?? [];
				if (is_string($settings)) {
					$settings = unserialize($settings, ['allowed_classes' => false]);
				}
				$table = is_array($settings) ? (string) ($settings['TABLE_NAME'] ?? '') : '';
				if ($table !== '') {
					$hl = \Bitrix\Highloadblock\HighloadBlockTable::getList(['filter' => ['=TABLE_NAME' => $table]])->fetch();
					if ($hl) {
						$dataClass = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hl)->getDataClass();
						$rs = $dataClass::getList(['select' => ['UF_XML_ID', 'UF_NAME']]);
						while ($row = $rs->fetch()) {
							$map[(string) $row['UF_XML_ID']] = (string) $row['UF_NAME'];
						}
					}
				}
			}
		}
		if (isset($map[$xmlId]) && $map[$xmlId] !== '') {
			return $map[$xmlId];
		}
		return $xmlId;
	}
}

if (!function_exists('atriumInjectTileMenuChars')) {
	function atriumInjectTileMenuChars(array &$arResult)
	{
		$elementId = (int) ($arResult['ID'] ?? 0);
		$iblockId = (int) ($arResult['IBLOCK_ID'] ?? 0);
		if ($elementId <= 0 || $iblockId <= 0) {
			return;
		}
		if (!isset($arResult['DISPLAY_PROPERTIES']) || !is_array($arResult['DISPLAY_PROPERTIES'])) {
			$arResult['DISPLAY_PROPERTIES'] = [];
		}

		$room = [];
		$purpose = [];
		$sec = CIBlockElement::GetElementGroups($elementId, true);
		while ($s = $sec->Fetch()) {
			$code = (string) ($s['CODE'] ?? '');
			$name = trim((string) ($s['NAME'] ?? ''));
			if ($code === 'dlya-vannoy' || $name === 'Для ванной') {
				$room['Для ванной'] = true;
			}
			if ($code === 'dlya-kuhni' || $name === 'Для кухни') {
				$room['Для кухни'] = true;
			}
			if ($code === 'plitka-napolnaya' || $code === 'napolnaya1' || $name === 'Плитка напольная' || $name === 'Напольная') {
				$purpose['Для пола'] = true;
			}
			if ($code === 'dlya-fasada' || mb_stripos($name, 'фасад') !== false) {
				$purpose['Для фасада'] = true;
			}
		}

		$display = &$arResult['DISPLAY_PROPERTIES'];

		if ($room) {
			atriumCardCharAdd($display, 'ATRIUM_POMESHCHENIE', 'Помещение', implode(', ', array_keys($room)));
		}
		if ($purpose) {
			atriumCardCharAdd($display, 'ATRIUM_NAZNACHENIE', 'Назначение', implode(', ', array_keys($purpose)));
		}

		$style = atriumCardCharPropText($iblockId, $elementId, 'PROP_342', $arResult);
		atriumCardCharAdd($display, 'PROP_342', 'Стиль', $style);

		$pattern = atriumCardCharPropText($iblockId, $elementId, 'IMITACIYA', $arResult);
		if ($pattern === '') {
			$pattern = atriumCardCharPropText($iblockId, $elementId, 'RISUNOK', $arResult);
		}
		atriumCardCharAdd($display, 'IMITACIYA', 'Имитация', $pattern);

		$surface = atriumCardCharPropText($iblockId, $elementId, 'PROP_1242', $arResult);
		atriumCardCharAdd($display, 'PROP_1242', 'Поверхность', $surface);

		$color = atriumCardCharPropText($iblockId, $elementId, 'COLOR_REF2', $arResult);
		atriumCardCharAdd($display, 'COLOR_REF2', 'Основной цвет', $color);

		$material = atriumCardCharPropText($iblockId, $elementId, 'PROP_2083', $arResult);
		atriumCardCharAdd($display, 'PROP_2083', 'Материал', $material);

		$sectionId = (int) ($arResult['IBLOCK_SECTION_ID'] ?? 0);
		$isTile = false;
		if ($sectionId > 0) {
			$nav = CIBlockSection::GetNavChain($iblockId, $sectionId, ['ID', 'CODE']);
			while ($navRow = $nav->Fetch()) {
				if ((int) $navRow['ID'] === 256 || (string) ($navRow['CODE'] ?? '') === 'plitka') {
					$isTile = true;
					break;
				}
			}
		}
		if ($isTile) {
			$sizeCm = atriumCardCharPropText($iblockId, $elementId, 'PROP_366', $arResult);
			atriumCardCharAdd($display, 'PROP_366', 'Размеры, см', $sizeCm);
			$length = atriumCardCharPropText($iblockId, $elementId, 'PROP_2061', $arResult);
			atriumCardCharAdd($display, 'PROP_2061', 'Длина, мм', $length);
			$width = atriumCardCharPropText($iblockId, $elementId, 'PROP_1403', $arResult);
			atriumCardCharAdd($display, 'PROP_1403', 'Ширина, мм', $width);
			$thick = atriumCardCharPropText($iblockId, $elementId, 'PROP_1394', $arResult);
			atriumCardCharAdd($display, 'PROP_1394', 'Толщина, мм', $thick);
		}
	}
}

if (!function_exists('atriumInjectFilledDetailChars')) {
	function atriumDetailCharSkip($code)
	{
		static $skip = [
			'CML2_LINK' => true, 'CML2_TRAITS' => true, 'CML2_TAXES' => true, 'CML2_BASE_UNIT' => true,
			'CML2_BAR_CODE' => true, 'CML2_ATTRIBUTES' => true, 'CML2_ARTICLE' => true,
			'MORE_PHOTO' => true, 'PHOTO' => true, 'FILES' => true, 'DOCUMENTS' => true,
			'HIT' => true, 'SALE_TEXT' => true, 'BRAND' => true, 'MINIMUM_PRICE' => true, 'MAXIMUM_PRICE' => true,
			'BLOG_POST_ID' => true, 'BLOG_COMMENTS_CNT' => true, 'vote_count' => true, 'vote_sum' => true,
			'rating' => true, 'FORUM_TOPIC_ID' => true, 'FORUM_MESSAGE_CNT' => true,
			'IN_STOCK' => true, 'ASSOCIATED' => true, 'ASSOCIATED_FILTER' => true,
			'EXPANDABLES' => true, 'EXPANDABLES_FILTER' => true, 'LINK_SALE' => true, 'LINK_GOODS' => true,
			'SERVICES' => true, 'PODBORKI' => true, 'POPUP_VIDEO' => true, 'YM_ELEMENT_ID' => true,
			'EXTENDED_REVIEWS_COUNT' => true, 'EXTENDED_REVIEWS_RAITING' => true,
			'PRODUCT_SET' => true, 'PRODUCT_SET_ITEM' => true, 'KITS' => true, 'MAIN_TOP' => true,
			'BNR_TOP' => true, 'HELP_TEXT' => true, 'COLLECTION_HL' => true, 'PROP_1240' => true,
		];
		$code = (string) $code;
		if ($code === '' || isset($skip[$code]) || strpos($code, 'CML2_') === 0) {
			return true;
		}
		return false;
	}

	function atriumDetailCharReadable($text)
	{
		$text = trim(strip_tags((string) $text));
		if ($text === '' || mb_strlen($text) > 240) {
			return false;
		}
		if (preg_match('/^[0-9a-f]{8}-[0-9a-f-]{20,}$/i', $text)) {
			return false;
		}
		if (preg_match('/\.(jpg|jpeg|png|gif|webp|pdf|xml)$/i', $text)) {
			return false;
		}
		return true;
	}

	function atriumInjectFilledDetailChars(array &$arResult)
	{
		$elementId = (int) ($arResult['ID'] ?? 0);
		$iblockId = (int) ($arResult['IBLOCK_ID'] ?? 0);
		if ($elementId <= 0 || $iblockId <= 0 || empty($arResult['PROPERTIES']) || !is_array($arResult['PROPERTIES'])) {
			return;
		}
		if (!isset($arResult['DISPLAY_PROPERTIES']) || !is_array($arResult['DISPLAY_PROPERTIES'])) {
			$arResult['DISPLAY_PROPERTIES'] = [];
		}

		$detail = trim(strip_tags((string) ($arResult['DETAIL_TEXT'] ?? '')));
		if ($detail === '') {
			$preview = trim((string) ($arResult['PREVIEW_TEXT'] ?? ''));
			if ($preview !== '') {
				$arResult['DETAIL_TEXT'] = $arResult['PREVIEW_TEXT'];
			}
		}

		foreach ($arResult['PROPERTIES'] as $code => $prop) {
			if (!is_array($prop) || atriumDetailCharSkip($code)) {
				continue;
			}
			if (isset($arResult['DISPLAY_PROPERTIES'][$code])) {
				continue;
			}
			$type = (string) ($prop['PROPERTY_TYPE'] ?? '');
			$userType = (string) ($prop['USER_TYPE'] ?? '');
			if (!in_array($type, ['S', 'L', 'N'], true) || $userType === 'directory' || $userType === 'HTML') {
				continue;
			}
			$name = trim((string) ($prop['NAME'] ?? ''));
			if ($name === '' || preg_match('/поиск|служебн|\\bxml\\b|ид\\s*товара/ui', $name)) {
				continue;
			}
			if ($name === 'Описание') {
				if ($name === 'Описание' && trim(strip_tags((string) ($arResult['DETAIL_TEXT'] ?? ''))) === '') {
					$text = atriumCardCharPropText($iblockId, $elementId, $code, $arResult);
					if (atriumDetailCharReadable($text)) {
						$arResult['DETAIL_TEXT'] = $text;
					}
				}
				continue;
			}
			$text = atriumCardCharPropText($iblockId, $elementId, $code, $arResult);
			if (!atriumDetailCharReadable($text)) {
				continue;
			}
			atriumCardCharAdd($arResult['DISPLAY_PROPERTIES'], $code, $name, $text);
		}
	}
}
