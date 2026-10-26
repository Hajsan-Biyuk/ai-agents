<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

if (!function_exists('atriumTuneCatalogFilter')) {
	function atriumTuneCatalogFilter(array &$arResult, $sectionId)
	{
		$sectionId = (int) $sectionId;
		if ($sectionId <= 0 || empty($arResult['ITEMS']) || !\Bitrix\Main\Loader::includeModule('iblock')) {
			return;
		}

		$iblockId = (int) ($arResult['IBLOCK_ID'] ?? 61);
		if ($iblockId <= 0) {
			$iblockId = 61;
		}

		$codes = [];
		$nav = CIBlockSection::GetNavChain($iblockId, $sectionId, ['ID', 'CODE']);
		while ($row = $nav->Fetch()) {
			$codes[] = (string) ($row['CODE'] ?? '');
		}

		$isSanteh = in_array('santekhnika', $codes, true);
		$isShower = false;
		foreach ($codes as $code) {
			if ($code === 'dushevye-dveri-ograzhdeniya-boksy' || strpos($code, 'dushev') === 0) {
				$isShower = true;
				break;
			}
		}

		$hasProfile = false;
		foreach ($arResult['ITEMS'] as $item) {
			$code = strtolower((string) ($item['CODE'] ?? ''));
			$name = trim((string) ($item['NAME'] ?? ''));
			if ($code === 'prop_1393' || $name === 'Цвет профиля') {
				$hasProfile = true;
				break;
			}
		}

		foreach ($arResult['ITEMS'] as $key => $item) {
			$code = strtolower((string) ($item['CODE'] ?? ''));
			$name = trim((string) ($item['NAME'] ?? ''));
			if ($isSanteh && ($code === 'producttype' || $name === 'Тип товара')) {
				unset($arResult['ITEMS'][$key]);
				continue;
			}
			if ($isShower && $hasProfile && $code === 'color_ref2') {
				unset($arResult['ITEMS'][$key]);
			}
		}
	}
}
