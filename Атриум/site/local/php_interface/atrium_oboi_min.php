<?php

/**
 * Обои: в корзину не меньше 6 штук.
 * Шаг после минимума остаётся 1.
 */

const ATRIUM_OBOI_MIN_QTY = 6;
const ATRIUM_OBOI_IBLOCK_ID = 61;
const ATRIUM_OBOI_SECTION_CODE = 'oboi';

function atriumOboiSectionIds(): array
{
	static $ids = null;
	if ($ids !== null) {
		return $ids;
	}

	$ids = [];
	if (!\CModule::IncludeModule('iblock')) {
		return $ids;
	}

	$cache = \Bitrix\Main\Data\Cache::createInstance();
	$cacheId = 'atrium_oboi_sections_v1';
	$cacheDir = '/atrium/oboi';
	if ($cache->initCache(86400, $cacheId, $cacheDir)) {
		$vars = $cache->getVars();
		$ids = is_array($vars['ids'] ?? null) ? $vars['ids'] : [];
		return $ids;
	}

	if (!$cache->startDataCache()) {
		return $ids;
	}

	$section = \CIBlockSection::GetList(
		[],
		['IBLOCK_ID' => ATRIUM_OBOI_IBLOCK_ID, '=CODE' => ATRIUM_OBOI_SECTION_CODE],
		false,
		['ID', 'LEFT_MARGIN', 'RIGHT_MARGIN']
	)->Fetch();

	if ($section) {
		$rs = \CIBlockSection::GetList(
			[],
			[
				'IBLOCK_ID' => ATRIUM_OBOI_IBLOCK_ID,
				'>=LEFT_MARGIN' => $section['LEFT_MARGIN'],
				'<=RIGHT_MARGIN' => $section['RIGHT_MARGIN'],
			],
			false,
			['ID']
		);
		while ($row = $rs->Fetch()) {
			$ids[] = (int)$row['ID'];
		}
	}

	$ids = array_values(array_unique($ids));
	$cache->endDataCache(['ids' => $ids]);

	return $ids;
}

function atriumIsOboiProduct(int $productId): bool
{
	static $cache = [];
	if ($productId <= 0) {
		return false;
	}
	if (array_key_exists($productId, $cache)) {
		return $cache[$productId];
	}

	$sectionIds = atriumOboiSectionIds();
	if (!$sectionIds) {
		return $cache[$productId] = false;
	}

	$elementId = $productId;
	if (\CModule::IncludeModule('catalog')) {
		$info = \CCatalogSku::GetProductInfo($productId);
		if (is_array($info) && !empty($info['ID'])) {
			$elementId = (int)$info['ID'];
			if (array_key_exists($elementId, $cache)) {
				return $cache[$productId] = $cache[$elementId];
			}
		}
	}

	$ok = false;
	$groups = \CIBlockElement::GetElementGroups($elementId, true, ['ID']);
	while ($group = $groups->Fetch()) {
		if (in_array((int)$group['ID'], $sectionIds, true)) {
			$ok = true;
			break;
		}
	}

	$cache[$elementId] = $ok;
	$cache[$productId] = $ok;

	return $ok;
}

function atriumOboiPatchQuantityAttrs(string $html): string
{
	$min = ATRIUM_OBOI_MIN_QTY;

	return preg_replace_callback(
		'/data-quantity="(\d+(?:[.,]\d+)?)"/',
		static function (array $match) use ($min): string {
			$value = (float)str_replace(',', '.', $match[1]);
			if ($value < $min) {
				return 'data-quantity="' . $min . '"';
			}

			return $match[0];
		},
		$html
	);
}

function atriumOboiPatchCounterHtml(string $html): string
{
	$min = ATRIUM_OBOI_MIN_QTY;
	if (!preg_match('/\bdata-min="/', $html)) {
		$html = preg_replace('/<span class="minus\b/', '<span data-min="' . $min . '" class="minus', $html, 1);
	}

	$replaced = 0;
	$html = preg_replace_callback(
		'/\bvalue="(\d+(?:[.,]\d+)?)"/',
		static function (array $match) use ($min, &$replaced): string {
			if ($replaced > 0) {
				return $match[0];
			}
			$replaced++;
			$value = (float)str_replace(',', '.', $match[1]);
			if ($value < $min) {
				return 'value="' . $min . '"';
			}

			return $match[0];
		},
		$html
	);

	return $html;
}

function atriumOboiApplyBasketData(?array &$data, int $productId): void
{
	if (!$data || !atriumIsOboiProduct($productId)) {
		return;
	}

	$min = ATRIUM_OBOI_MIN_QTY;
	$current = isset($data['MIN_QUANTITY_BUY']) ? (float)$data['MIN_QUANTITY_BUY'] : 1;
	if ($current < $min) {
		$data['MIN_QUANTITY_BUY'] = $min;
	}
	if (!empty($data['HTML']) && is_string($data['HTML'])) {
		$data['HTML'] = atriumOboiPatchQuantityAttrs($data['HTML']);
	}
	$data['OBOI_MIN'] = true;
}

function atriumOboiRaiseBasketItem($item): void
{
	if (!is_object($item) || !method_exists($item, 'getProductId') || !method_exists($item, 'getQuantity')) {
		return;
	}
	if (method_exists($item, 'isBundleChild') && $item->isBundleChild()) {
		return;
	}
	if (method_exists($item, 'getField') && $item->getField('DELAY') === 'Y') {
		return;
	}
	if (!atriumIsOboiProduct((int)$item->getProductId())) {
		return;
	}
	if ((float)$item->getQuantity() < ATRIUM_OBOI_MIN_QTY) {
		$item->setField('QUANTITY', ATRIUM_OBOI_MIN_QTY);
	}
}

function atriumOboiOnBasketItemBeforeSaved(\Bitrix\Main\Event $event): void
{
	static $guard = false;
	if ($guard) {
		return;
	}
	$guard = true;
	try {
		atriumOboiRaiseBasketItem($event->getParameter('ENTITY'));
	} finally {
		$guard = false;
	}
}

function atriumOboiNormalizeOpenBasket(): bool
{
	if (PHP_SAPI === 'cli') {
		return false;
	}
	if (defined('ADMIN_SECTION') && ADMIN_SECTION === true) {
		return false;
	}
	if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET') {
		return false;
	}
	if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
		return false;
	}
	$path = (string)parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
	if (!preg_match('#/(basket|order)(?:/|$)#', $path)) {
		return false;
	}
	if (!\CModule::IncludeModule('sale')) {
		return false;
	}

	$basket = \Bitrix\Sale\Basket::loadItemsForFUser(\Bitrix\Sale\Fuser::getId(), SITE_ID);
	$changed = false;
	foreach ($basket as $item) {
		$before = (float)$item->getQuantity();
		atriumOboiRaiseBasketItem($item);
		if ((float)$item->getQuantity() !== $before) {
			$changed = true;
		}
	}
	if (!$changed) {
		unset($_SESSION['ATRIUM_OBOI_REDIRECT']);
		return false;
	}
	if (!empty($_SESSION['ATRIUM_OBOI_REDIRECT'])) {
		unset($_SESSION['ATRIUM_OBOI_REDIRECT']);
		return false;
	}

	$basket->save();
	$_SESSION['ATRIUM_OBOI_REDIRECT'] = 1;

	return true;
}

function atriumOboiOnBeforeProlog(): void
{
	if (!atriumOboiNormalizeOpenBasket()) {
		return;
	}
	LocalRedirect($_SERVER['REQUEST_URI']);
}

\Bitrix\Main\EventManager::getInstance()->addEventHandler(
	'sale',
	'OnSaleBasketItemBeforeSaved',
	'atriumOboiOnBasketItemBeforeSaved'
);
AddEventHandler('main', 'OnBeforeProlog', 'atriumOboiOnBeforeProlog');
