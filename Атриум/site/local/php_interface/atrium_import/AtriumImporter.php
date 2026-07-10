<?php

/**
 * Атриум — импортёр товаров из канонического JSON.
 * Upsert по свойству CML2_ARTICLE в ИБ каталога (Aspro Max).
 *
 * Паттерн: SlonInstallCatalog (Cursor-sheer/Slon).
 * Dry-run обязателен перед реальным импортом.
 */
class AtriumImporter
{
	/** @var int ИБ «Каталог товаров» */
	private $iblockId;

	/** @var string[] типы цен */
	private $priceBase;
	private $priceOpt;

	private $dryRun = true;
	private $withPhotos = false;

	private $log = [];
	private $errors = [];
	private $created = 0;
	private $updated = 0;
	private $skipped = 0;

	/** @var array кэши */
	private $sectionCache = [];
	private $brandCache = [];
	private $articleIndex = null;
	private $brandIblockId = 0;
	private $priceGroupIds = [];
	private $inStockEnum = null;

	public function __construct(array $config)
	{
		$this->iblockId = (int) ($config['iblock_id'] ?? 0);
		$this->priceBase = (string) ($config['price_base'] ?? 'BASE');
		$this->priceOpt = (string) ($config['price_opt'] ?? 'OPT');
	}

	public function setDryRun($dryRun)
	{
		$this->dryRun = (bool) $dryRun;
	}

	public function setWithPhotos($withPhotos)
	{
		$this->withPhotos = (bool) $withPhotos;
	}

	/**
	 * Создать элемент бренда в связанном ИБ, если его ещё нет (для страницы /info/brands/).
	 * @return int ID бренда или 0
	 */
	public function ensureBrand($brandName)
	{
		if (!CModule::IncludeModule('iblock')) {
			throw new RuntimeException('Модуль iblock не подключен');
		}
		if ($this->brandIblockId <= 0) {
			$this->resolveBrandIblock();
		}
		$wasDry = $this->dryRun;
		$this->dryRun = false;
		$id = $this->resolveBrand($brandName);
		$this->dryRun = $wasDry;
		return $id > 0 ? $id : 0;
	}

	/**
	 * @param array $products массив канонических товаров
	 * @return array отчёт
	 */
	public function run(array $products)
	{
		if (!CModule::IncludeModule('iblock')) {
			throw new RuntimeException('Модуль iblock не подключен');
		}
		if (!CModule::IncludeModule('catalog')) {
			throw new RuntimeException('Модуль catalog не подключен');
		}
		if ($this->iblockId <= 0) {
			throw new RuntimeException('Не задан iblock_id каталога');
		}

		$this->resolveBrandIblock();
		$this->resolvePriceGroups();
		$this->buildArticleIndex();

		foreach ($products as $i => $product) {
			try {
				$this->importProduct($product);
			} catch (Throwable $e) {
				$this->errors[] = [
					'article' => (string) ($product['article'] ?? '#' . $i),
					'message' => $e->getMessage(),
				];
			}
		}

		return $this->report();
	}

	public function report()
	{
		return [
			'dry_run' => $this->dryRun,
			'created' => $this->created,
			'updated' => $this->updated,
			'skipped' => $this->skipped,
			'errors' => $this->errors,
			'log' => array_slice($this->log, 0, 200),
		];
	}

	private function addLog($message)
	{
		$this->log[] = $message;
	}

	// ---------------------------------------------------------------- resolve

	private function resolveBrandIblock()
	{
		$prop = CIBlockProperty::GetList([], [
			'IBLOCK_ID' => $this->iblockId,
			'CODE' => 'BRAND',
		])->Fetch();
		if ($prop && (int) $prop['LINK_IBLOCK_ID'] > 0) {
			$this->brandIblockId = (int) $prop['LINK_IBLOCK_ID'];
			$this->addLog('BRAND → ИБ ' . $this->brandIblockId);
		} else {
			$this->addLog('ВНИМАНИЕ: свойство BRAND не найдено или без LINK_IBLOCK_ID — бренды не будут привязаны');
		}
	}

	private function resolvePriceGroups()
	{
		$rs = CCatalogGroup::GetList();
		while ($row = $rs->Fetch()) {
			$this->priceGroupIds[$row['NAME']] = (int) $row['ID'];
		}
		foreach ([$this->priceBase, $this->priceOpt] as $code) {
			if (!isset($this->priceGroupIds[$code])) {
				$this->addLog('ВНИМАНИЕ: тип цены «' . $code . '» не найден');
			}
		}
	}

	/**
	 * Индекс: CML2_ARTICLE → ELEMENT_ID (один проход по ИБ вместо запроса на товар).
	 */
	private function buildArticleIndex()
	{
		$this->articleIndex = [];
		$rs = CIBlockElement::GetList(
			[],
			['IBLOCK_ID' => $this->iblockId],
			false,
			false,
			['ID', 'PROPERTY_CML2_ARTICLE']
		);
		while ($row = $rs->Fetch()) {
			$article = trim((string) $row['PROPERTY_CML2_ARTICLE_VALUE']);
			if ($article !== '' && !isset($this->articleIndex[$article])) {
				$this->articleIndex[$article] = (int) $row['ID'];
			}
		}
		$this->addLog('Индекс артикулов: ' . count($this->articleIndex));
	}

	private function resolveSection(array $codeChain)
	{
		$key = implode('/', $codeChain);
		if (isset($this->sectionCache[$key])) {
			return $this->sectionCache[$key];
		}
		$parentId = 0;
		$sectionId = 0;
		foreach ($codeChain as $code) {
			$row = CIBlockSection::GetList([], [
				'IBLOCK_ID' => $this->iblockId,
				'CODE' => $code,
				'SECTION_ID' => $parentId,
			], false, ['ID'])->Fetch();
			if (!$row) {
				$this->sectionCache[$key] = 0;
				return 0;
			}
			$sectionId = (int) $row['ID'];
			$parentId = $sectionId;
		}
		$this->sectionCache[$key] = $sectionId;
		return $sectionId;
	}

	private function resolveBrand($brandName)
	{
		$brandName = trim((string) $brandName);
		if ($brandName === '' || $this->brandIblockId <= 0) {
			return 0;
		}
		if (isset($this->brandCache[$brandName])) {
			return $this->brandCache[$brandName];
		}
		$row = CIBlockElement::GetList([], [
			'IBLOCK_ID' => $this->brandIblockId,
			'=NAME' => $brandName,
		], false, false, ['ID', 'DETAIL_TEXT'])->Fetch();

		if ($row) {
			$id = (int) $row['ID'];
			if (
				!$this->dryRun
				&& strlen(trim(strip_tags((string) ($row['DETAIL_TEXT'] ?? '')))) === 0
			) {
				$el = new CIBlockElement();
				if ($el->Update($id, [
					'DETAIL_TEXT' => $brandName,
					'DETAIL_TEXT_TYPE' => 'text',
				])) {
					$this->addLog('Бренд «' . $brandName . '»: добавлен DETAIL_TEXT для ссылки на сайте');
				}
			}
		} elseif ($this->dryRun) {
			$this->addLog('[dry] будет создан бренд «' . $brandName . '»');
			$id = -1; // маркер «будет создан»
		} else {
			$el = new CIBlockElement();
			$id = (int) $el->Add([
				'IBLOCK_ID' => $this->brandIblockId,
				'NAME' => $brandName,
				'CODE' => CUtil::translit($brandName, 'ru', ['replace_space' => '-', 'replace_other' => '-']),
				'ACTIVE' => 'Y',
				'DETAIL_TEXT' => $brandName,
				'DETAIL_TEXT_TYPE' => 'text',
			]);
			if ($id <= 0) {
				$this->addLog('Не удалось создать бренд «' . $brandName . '»: ' . $el->LAST_ERROR);
				$id = 0;
			} else {
				$this->addLog('Создан бренд «' . $brandName . '» (ID ' . $id . ')');
			}
		}
		$this->brandCache[$brandName] = $id;
		return $id;
	}

	// ---------------------------------------------------------------- import

	private function importProduct(array $p)
	{
		$article = trim((string) ($p['article'] ?? ''));
		if ($article === '') {
			$this->skipped++;
			$this->errors[] = ['article' => '', 'message' => 'пустой артикул'];
			return;
		}

		$elementId = isset($this->articleIndex[$article]) ? $this->articleIndex[$article] : 0;
		$isNew = $elementId <= 0;

		$sectionIds = [];
		foreach ((array) ($p['sections'] ?? []) as $chain) {
			$sid = $this->resolveSection((array) $chain);
			if ($sid > 0) {
				$sectionIds[] = $sid;
			} else {
				$this->addLog('Раздел не найден: ' . implode('/', (array) $chain) . ' (артикул ' . $article . ')');
			}
		}
		if ($isNew && empty($sectionIds)) {
			$this->skipped++;
			$this->errors[] = ['article' => $article, 'message' => 'нет валидного раздела для нового товара'];
			return;
		}

		$brandId = $this->resolveBrand($p['brand'] ?? '');

		if ($this->dryRun) {
			$isNew ? $this->created++ : $this->updated++;
			if (count($this->log) < 190) {
				$this->addLog(sprintf(
					'[dry] %s «%s» (%s) → раздел %s, цена %s',
					$isNew ? 'СОЗДАТЬ' : 'ОБНОВИТЬ',
					mb_substr((string) ($p['name'] ?? $article), 0, 60),
					$article,
					implode(',', $sectionIds) ?: '—',
					(string) ($p['price_retail'] ?? '—')
				));
			}
			return;
		}

		$el = new CIBlockElement();
		$fields = [
			'NAME' => (string) ($p['name'] ?? $article),
			'ACTIVE' => 'Y',
		];
		if (!empty($sectionIds)) {
			$fields['IBLOCK_SECTION_ID'] = $sectionIds[0];
			$fields['IBLOCK_SECTION'] = $sectionIds;
		}
		if (!empty($p['description'])) {
			$fields['DETAIL_TEXT'] = (string) $p['description'];
			$fields['DETAIL_TEXT_TYPE'] = 'text';
		}

		if ($isNew) {
			$fields['IBLOCK_ID'] = $this->iblockId;
			$fields['CODE'] = $this->uniqueCode($p, $article);
			$elementId = (int) $el->Add($fields);
			if ($elementId <= 0) {
				throw new RuntimeException('Add: ' . $el->LAST_ERROR);
			}
			$this->articleIndex[$article] = $elementId;
			$this->created++;
		} else {
			if (!$el->Update($elementId, $fields)) {
				throw new RuntimeException('Update: ' . $el->LAST_ERROR);
			}
			$this->updated++;
		}

		// свойства
		$props = ['CML2_ARTICLE' => $article];
		if ($brandId > 0) {
			$props['BRAND'] = $brandId;
		}
		CIBlockElement::SetPropertyValuesEx($elementId, $this->iblockId, $props);

		// товар каталога + количество
		$quantity = (int) ($p['quantity'] ?? 0);
		$productFields = [
			'ID' => $elementId,
			'QUANTITY' => $quantity,
			'AVAILABLE' => !empty($p['in_stock']) ? 'Y' : 'N',
		];
		if (CCatalogProduct::GetByID($elementId)) {
			CCatalogProduct::Update($elementId, $productFields);
		} else {
			CCatalogProduct::Add($productFields);
		}

		// цены
		$this->setPrice($elementId, $this->priceBase, $p['price_retail'] ?? null);
		$this->setPrice($elementId, $this->priceOpt, $p['price_opt'] ?? null);

		// фото (опционально — тяжёлая операция)
		if ($this->withPhotos && !empty($p['photos'])) {
			$this->attachPhotos($elementId, (array) $p['photos'], $isNew);
		}
	}

	private function uniqueCode(array $p, $article)
	{
		$base = CUtil::translit(
			(string) ($p['name'] ?? $article),
			'ru',
			['replace_space' => '-', 'replace_other' => '-']
		);
		$base = trim(mb_substr($base, 0, 80), '-');
		$code = $base;
		$n = 1;
		while (CIBlockElement::GetList([], [
			'IBLOCK_ID' => $this->iblockId,
			'=CODE' => $code,
		], false, false, ['ID'])->Fetch()) {
			$code = $base . '-' . strtolower($article);
			if ($n++ > 1) {
				$code .= '-' . $n;
			}
			if ($n > 5) {
				break;
			}
		}
		return $code;
	}

	private function setPrice($elementId, $groupName, $value)
	{
		if ($value === null || $value === '' || !isset($this->priceGroupIds[$groupName])) {
			return;
		}
		$groupId = $this->priceGroupIds[$groupName];
		$price = (float) $value;
		$fields = [
			'PRODUCT_ID' => $elementId,
			'CATALOG_GROUP_ID' => $groupId,
			'PRICE' => $price,
			'CURRENCY' => 'RUB',
		];
		$existing = CPrice::GetList([], [
			'PRODUCT_ID' => $elementId,
			'CATALOG_GROUP_ID' => $groupId,
		])->Fetch();
		if ($existing) {
			CPrice::Update($existing['ID'], $fields);
		} else {
			CPrice::Add($fields);
		}
	}

	private function attachPhotos($elementId, array $urls, $isNew)
	{
		// Фото добавляем только новым товарам (или при явном обновлении фото),
		// чтобы не плодить дубликаты в MORE_PHOTO при каждом импорте.
		if (!$isNew) {
			return;
		}
		$detailSet = false;
		$morePhoto = [];
		foreach (array_slice($urls, 0, 12) as $url) {
			$file = CFile::MakeFileArray($url);
			if (!is_array($file) || empty($file['tmp_name'])) {
				continue;
			}
			if (!$detailSet) {
				$el = new CIBlockElement();
				$el->Update($elementId, ['DETAIL_PICTURE' => $file]);
				$detailSet = true;
			} else {
				$morePhoto[] = ['VALUE' => $file, 'DESCRIPTION' => ''];
			}
		}
		if ($morePhoto) {
			CIBlockElement::SetPropertyValuesEx($elementId, $this->iblockId, ['MORE_PHOTO' => $morePhoto]);
		}
	}
}
