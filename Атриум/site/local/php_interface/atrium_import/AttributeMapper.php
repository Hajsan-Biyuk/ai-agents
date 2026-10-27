<?php

/**
 * Запись attributes из канонического JSON в свойства каталога Bitrix.
 */
class AtriumAttributeMapper
{
	private $iblockId;
	private $dryRun = true;
	private $config = [];
	private $propertyByCode = [];
	private $propertyByName = [];
	private $enumNormalize = [];
	private $unknown = [];
	private $clearCodes = [];
	private $writtenCodes = [];
	private $colorResolver;

	public function __construct($iblockId, $dryRun = true, callable $colorResolver = null)
	{
		$this->iblockId = (int) $iblockId;
		$this->dryRun = (bool) $dryRun;
		$this->colorResolver = $colorResolver;
		$this->loadConfig();
		$this->buildPropertyIndex();
	}

	public function getUnknown()
	{
		return $this->unknown;
	}

	public function getClearCodes()
	{
		return array_values(array_unique($this->clearCodes));
	}

	public function getSupplierClearCodes($supplier)
	{
		$supplier = (string) $supplier;
		$codes = (array) (($this->config['supplier_clear_codes'] ?? [])[$supplier] ?? []);
		return array_values(array_unique(array_filter(array_map('strval', $codes))));
	}

	public function enableDetailPageShowForWritten()
	{
		if ($this->dryRun) {
			return;
		}
		foreach (array_unique($this->writtenCodes) as $code) {
			$this->enableDetailPageShowByCode((string) $code);
		}
	}

	public function capitalizeTextValue($value)
	{
		if (empty($this->config['capitalize_text_values'])) {
			return $value;
		}
		if (!is_string($value)) {
			return $value;
		}
		$value = trim($value);
		if ($value === '') {
			return $value;
		}
		if (preg_match('/^https?:\/\//i', $value)) {
			return $value;
		}
		if (preg_match('/^\d/', $value)) {
			return $value;
		}
		if (preg_match('/^[\d\s\-–—.,\/]+$/u', $value)) {
			return $value;
		}
		$num = str_replace(',', '.', $value);
		if (is_numeric($num)) {
			return $value;
		}

		$first = mb_substr($value, 0, 1);
		if ($first === mb_strtolower($first) && $first !== mb_strtoupper($first)) {
			return mb_strtoupper($first) . mb_substr($value, 1);
		}
		return $value;
	}

	public function map(array $attributes, array $options = [])
	{
		$this->unknown = [];
		$this->clearCodes = [];
		$this->writtenCodes = [];
		$skipCodes = array_fill_keys((array) ($options['skip_codes'] ?? []), true);
		$supplier = (string) ($options['supplier'] ?? '');
		$props = [];

		foreach ($attributes as $rawName => $rawValue) {
			$name = trim(str_replace(["\r", "\n"], ' ', (string) $rawName));
			$value = $this->normalizeScalar($rawValue);
			if ($name === '' || $value === '') {
				continue;
			}
			if ($this->shouldSkipName($name)) {
				continue;
			}

			$override = $this->overrideForName($name, $supplier);
			if ($this->shouldSkipAttribute($name, $supplier, $override)) {
				$this->markClearByName($name, $override);
				continue;
			}

			$prop = $this->resolveProperty($name, $supplier);
			if (!$prop && !empty($override['code']) && !empty($this->config['auto_create_unknown'])) {
				$prop = $this->ensureProperty($name, (string) $override['code']);
			}
			if (!$prop && !empty($this->config['auto_create_unknown'])) {
				$prop = $this->ensureProperty($name);
			}
			if (!$prop) {
				$this->unknown[] = $name;
				continue;
			}

			$code = (string) $prop['CODE'];
			if (isset($skipCodes[$code])) {
				continue;
			}

			if (!empty($override['skip'])) {
				$this->clearCodes[] = $code;
				continue;
			}

			if (!$this->overrideAppliesToSupplier($override, $supplier)) {
				continue;
			}

			$resolved = $this->resolveValue($prop, $code, $value, $override);
			if ($resolved === null || $resolved === '' || $resolved === []) {
				continue;
			}

			if (!empty($prop['MULTIPLE']) && $prop['MULTIPLE'] === 'Y' && !is_array($resolved)) {
				$props[$code] = array_merge((array) ($props[$code] ?? []), (array) $resolved);
			} else {
				$props[$code] = $resolved;
			}
			$this->writtenCodes[] = $code;
		}

		return $props;
	}

	private function loadConfig()
	{
		$this->config = $this->defaultConfig();
		$path = __DIR__ . '/attributes.yaml';
		if (!is_file($path)) {
			$this->enumNormalize = (array) ($this->config['enum_normalize'] ?? []);
			return;
		}
		if (function_exists('yaml_parse_file')) {
			$parsed = yaml_parse_file($path);
			if (is_array($parsed)) {
				$this->config = array_replace_recursive($this->config, $parsed);
			}
		}
		$this->enumNormalize = (array) ($this->config['enum_normalize'] ?? []);
	}

	private function defaultConfig()
	{
		return [
			// На проде часто нет ext-yaml — дефолт должен совпадать с attributes.yaml
			'auto_create_unknown' => true,
			'capitalize_text_values' => true,
			'skip_name_patterns' => [
				'/^фото\s*\d*/ui',
				'/^unnamed/i',
			],
			'overrides' => [
				'исполнение полотна (переименовать)' => ['code' => 'PROP_1393'],
				'исполнение полотна' => ['code' => 'PROP_1393'],
				'толщина полотна, мм' => ['code' => 'TOLSHINA_PERDENEGO_POLOTNA'],
				'толщина переднего полотна, мм' => ['code' => 'TOLSHINA_PERDENEGO_POLOTNA'],
				'ширина, мм' => ['code' => 'PROP_1403'],
				'размеры с диапазоном регулировок, мм' => ['code' => 'RAZMERY_S_DIAPAZONOM'],
				'особенности (есть множественный выбор)' => ['code' => 'OSOBENNOSTI'],
				'особенности' => ['code' => 'OSOBENNOSTI'],
				'название модели' => ['skip' => true],
				'цвет' => ['code' => 'COLOR_REF2', 'directory' => true],
				'цвет поддона' => ['code' => 'COLOR_REF2', 'directory' => true],
				'размер поддона, мм' => ['code' => 'ATR_RAZMER_PODDONA_MM'],
				'условный размер, мм' => ['code' => 'USLOVNIY_RAZMER'],
				'размер экрана, мм' => ['code' => 'ATR_RAZMER_EKRANA_MM'],
				'размер верхней лейки, мм' => ['code' => 'ATR_RAZMER_LEYKI_MM'],
				'форма' => ['code' => 'PROP_206'],
				'вес (кг)' => ['code' => 'ATRIUM_WEIGHT_KG'],
				'объем м3' => ['code' => 'ATRIUM_VOLUME_M3'],
				'высота уп. мм' => ['code' => 'ATRIUM_PACK_H'],
				'глубина уп. мм' => ['code' => 'ATRIUM_PACK_D'],
				'ширина уп. мм' => ['code' => 'ATRIUM_PACK_W'],
				'модель дд' => ['skip' => true],
				'цвет профиля' => ['code' => 'PROP_1278'],
				'конструкция дверей' => ['code' => 'PROP_1350'],
				'страна производства' => ['code' => 'STRANA_PROIZVODSTVA'],
				'страна производитель' => ['code' => 'STRANA_PROIZVODSTVA'],
				'гарантия' => ['code' => 'GARANTIA'],
				'коллекция' => ['code' => 'PROP_1240'],
				'серия' => ['code' => 'PROP_1240'],
				'покрытие стекла анти капля' => ['code' => 'PROP_2075'],
				'покрытие стекла анти-капля' => ['code' => 'PROP_2075'],
			],
			'supplier_skips' => [
				'dk' => [
					'тип',
					'количество монтажных отверстий',
					'область применения',
					'встраиваемая система',
					'количество единиц в мастер коробке',
					'количество единиц в коробке',
				],
			],
			'supplier_clear_codes' => [
				'dk' => ['PROP_2033', 'PROP_1253', 'PROP_1243', 'PROP_1241', 'PROP_1270'],
			],
			// Глобальные overrides уводят эти колонки в свойства другого фильтра.
			// Для AllerBath пишем в те свойства, которые стоят в фильтре раздела.
			'supplier_overrides' => [
				'allerbath' => [
					'исполнение полотна' => ['code' => 'PROP_1348'],
					'толщина полотна, мм' => ['code' => 'PROP_1349'],
					'страна производства' => ['code' => 'PROP_2084'],
					'гарантия' => ['code' => 'PROP_2120'],
				],
			],
			'enum_normalize' => [
				'PROP_1350' => [
					'неподвижные' => 'Неподвижная',
					'неподвижная' => 'Неподвижная',
				],
			],
		];
	}

	private function buildPropertyIndex()
	{
		$rs = CIBlockProperty::GetList(
			['SORT' => 'ASC', 'ID' => 'ASC'],
			['IBLOCK_ID' => $this->iblockId, 'ACTIVE' => 'Y']
		);
		while ($row = $rs->Fetch()) {
			$code = (string) $row['CODE'];
			if ($code === '') {
				continue;
			}
			$this->propertyByCode[$code] = $row;
			$key = $this->normalizeName((string) $row['NAME']);
			if ($key !== '' && !isset($this->propertyByName[$key])) {
				$this->propertyByName[$key] = $row;
			}
		}
	}

	private function resolveProperty($name, $supplier = '')
	{
		$override = $this->overrideForName($name, $supplier);
		if (!empty($override['code']) && isset($this->propertyByCode[$override['code']])) {
			return $this->propertyByCode[$override['code']];
		}

		$key = $this->normalizeName($name);
		if ($key !== '' && isset($this->propertyByName[$key])) {
			return $this->propertyByName[$key];
		}

		return null;
	}

	private function pickOverride(array $overrides, $key)
	{
		if ($key === '') {
			return null;
		}
		if (isset($overrides[$key])) {
			return (array) $overrides[$key];
		}
		foreach ($overrides as $overrideKey => $value) {
			if ($this->normalizeName((string) $overrideKey) === $key) {
				return (array) $value;
			}
		}
		return null;
	}

	private function overrideForName($name, $supplier = '')
	{
		$key = $this->normalizeName($name);
		if ($supplier !== '') {
			$supplierOverrides = (array) (($this->config['supplier_overrides'] ?? [])[$supplier] ?? []);
			$hit = $this->pickOverride($supplierOverrides, $key);
			if ($hit !== null) {
				return $hit;
			}
		}
		$overrides = (array) ($this->config['overrides'] ?? []);
		if ($key !== '' && isset($overrides[$key])) {
			return (array) $overrides[$key];
		}
		foreach ($overrides as $overrideKey => $value) {
			if ($this->normalizeName($overrideKey) === $key) {
				return (array) $value;
			}
		}
		return [];
	}

	private function shouldSkipName($name)
	{
		foreach ((array) ($this->config['skip_name_patterns'] ?? []) as $pattern) {
			if (@preg_match($pattern, $name)) {
				return true;
			}
		}
		return false;
	}

	private function shouldSkipAttribute($name, $supplier, array $override)
	{
		if (!empty($override['skip'])) {
			return true;
		}
		$key = $this->normalizeName($name);
		$supplierSkips = (array) (($this->config['supplier_skips'] ?? [])[$supplier] ?? []);
		foreach ($supplierSkips as $skipName) {
			if ($this->normalizeName($skipName) === $key) {
				return true;
			}
		}
		return false;
	}

	private function markClearByName($name, array $override)
	{
		if (!empty($override['code']) && isset($this->propertyByCode[$override['code']])) {
			$this->clearCodes[] = (string) $override['code'];
			return;
		}
		$prop = $this->resolveProperty($name);
		if ($prop && !empty($prop['CODE'])) {
			$this->clearCodes[] = (string) $prop['CODE'];
		}
	}

	private function overrideAppliesToSupplier(array $override, $supplier)
	{
		if (empty($override['suppliers']) || !is_array($override['suppliers'])) {
			return true;
		}
		return in_array($supplier, $override['suppliers'], true);
	}

	/**
	 * «60х120» и «60x120» — один размер. Кириллическую х между цифрами пишем как латинскую x.
	 */
	public static function latinSizeSeparator($value)
	{
		return preg_replace('/(?<=\d)[хХ](?=\d)/u', 'x', (string) $value);
	}

	public static function isSizeCmProperty(array $prop)
	{
		$name = trim(preg_replace('/\s+/u', ' ', (string) ($prop['NAME'] ?? '')));
		return mb_strtolower($name) === 'размеры, см';
	}

	private function resolveValue(array $prop, $code, $value, array $override)
	{
		if (self::isSizeCmProperty($prop)) {
			$value = self::latinSizeSeparator($value);
		}

		if (!empty($override['directory']) || ($prop['USER_TYPE'] ?? '') === 'directory') {
			return $this->resolveDirectory($value);
		}

		if (($prop['PROPERTY_TYPE'] ?? '') === 'L') {
			return $this->resolveListValues($prop, $code, $value);
		}

		if (($prop['PROPERTY_TYPE'] ?? '') === 'N') {
			$num = str_replace(',', '.', $value);
			if (is_numeric($num)) {
				return $num + 0;
			}
			return $this->capitalizeTextValue($value);
		}

		return $this->capitalizeTextValue($value);
	}

	private function resolveDirectory($value)
	{
		if (!$this->colorResolver) {
			return null;
		}
		$xmlId = call_user_func($this->colorResolver, $value);
		return $xmlId !== null && $xmlId !== '' ? $xmlId : null;
	}

	private function resolveListValues(array $prop, $code, $value)
	{
		$parts = preg_split('/\s*[,;|]\s*/u', $value) ?: [$value];
		$ids = [];
		foreach ($parts as $part) {
			$part = trim($part);
			if ($part === '') {
				continue;
			}
			$part = $this->normalizeEnumValue($code, $part);
			$enumId = $this->findOrCreateEnum((int) $prop['ID'], $part);
			if ($enumId > 0) {
				$ids[] = $enumId;
			}
		}
		if (empty($ids)) {
			return null;
		}
		if (($prop['MULTIPLE'] ?? '') === 'Y') {
			return $ids;
		}
		return $ids[0];
	}

	private function normalizeEnumValue($code, $value)
	{
		$key = mb_strtolower(trim($value));
		$map = (array) ($this->enumNormalize[$code] ?? []);
		foreach ($map as $from => $to) {
			if (mb_strtolower((string) $from) === $key) {
				return $this->capitalizeTextValue((string) $to);
			}
		}
		return $this->capitalizeTextValue($value);
	}

	private function findOrCreateEnum($propertyId, $value)
	{
		$rs = CIBlockPropertyEnum::GetList([], [
			'PROPERTY_ID' => $propertyId,
			'VALUE' => $value,
		]);
		if ($row = $rs->Fetch()) {
			return (int) $row['ID'];
		}

		$valueLower = mb_strtolower($value);
		$rs = CIBlockPropertyEnum::GetList([], ['PROPERTY_ID' => $propertyId]);
		while ($row = $rs->Fetch()) {
			if (mb_strtolower((string) $row['VALUE']) === $valueLower) {
				return (int) $row['ID'];
			}
		}

		$xmlId = CUtil::translit($value, 'ru', ['replace_space' => '_', 'replace_other' => '_']);
		$rs = CIBlockPropertyEnum::GetList([], [
			'PROPERTY_ID' => $propertyId,
			'XML_ID' => $xmlId,
		]);
		if ($row = $rs->Fetch()) {
			return (int) $row['ID'];
		}

		if ($this->dryRun) {
			return -1;
		}

		return (int) CIBlockPropertyEnum::Add([
			'PROPERTY_ID' => $propertyId,
			'VALUE' => $value,
			'XML_ID' => $xmlId,
		]);
	}

	private function normalizeScalar($value)
	{
		if (is_array($value)) {
			$parts = array_filter(array_map('trim', array_map('strval', $value)));
			return implode(', ', $parts);
		}
		$value = trim((string) $value);
		if ($value === '' || mb_strtolower($value) === 'nan') {
			return '';
		}
		return $value;
	}

	private function normalizeName($name)
	{
		$name = trim(str_replace(["\r", "\n"], ' ', (string) $name));
		$name = preg_replace('/\s+/u', ' ', $name);
		return mb_strtolower($name);
	}

	private function ensureProperty($name, $forcedCode = '')
	{
		$name = trim(str_replace(["\r", "\n"], ' ', (string) $name));
		if ($name === '') {
			return null;
		}

		$key = $this->normalizeName($name);
		if ($key !== '' && isset($this->propertyByName[$key])) {
			return $this->propertyByName[$key];
		}

		$code = $forcedCode !== '' ? strtoupper(preg_replace('/[^A-Z0-9_]/i', '_', $forcedCode)) : $this->propertyCodeForName($name);
		if ($code === '') {
			$code = $this->propertyCodeForName($name);
		}
		if (isset($this->propertyByCode[$code])) {
			return $this->propertyByCode[$code];
		}

		if ($this->dryRun) {
			$row = [
				'ID' => 0,
				'CODE' => $code,
				'NAME' => $name,
				'PROPERTY_TYPE' => $this->guessPropertyType($name),
				'MULTIPLE' => 'N',
				'USER_TYPE' => '',
			];
			$this->propertyByCode[$code] = $row;
			$this->propertyByName[$key] = $row;
			return $row;
		}

		$prop = new CIBlockProperty();
		$propertyType = $this->guessPropertyType($name);
		$fields = [
			'IBLOCK_ID' => $this->iblockId,
			'NAME' => $name,
			'CODE' => $code,
			'ACTIVE' => 'Y',
			'SORT' => 500,
			'PROPERTY_TYPE' => $propertyType,
			'MULTIPLE' => 'N',
			'IS_REQUIRED' => 'N',
			'FILTRABLE' => 'N',
			'SEARCHABLE' => 'N',
		];
		$propertyId = (int) $prop->Add($fields);
		if ($propertyId <= 0) {
			return null;
		}

		$row = [
			'ID' => $propertyId,
			'CODE' => $code,
			'NAME' => $name,
			'PROPERTY_TYPE' => $propertyType,
			'MULTIPLE' => 'N',
			'USER_TYPE' => '',
		];
		$this->propertyByCode[$code] = $row;
		$this->propertyByName[$key] = $row;
		$this->enableDetailPageShowByCode($code);
		return $row;
	}

	public function enableDetailPageShowByCode($code)
	{
		if ($this->dryRun || $code === '') {
			return;
		}
		if (!CModule::IncludeModule('iblock')) {
			return;
		}
		if (!class_exists('\Bitrix\Iblock\Model\PropertyFeature')
			|| !\Bitrix\Iblock\Model\PropertyFeature::isEnabledFeatures()) {
			return;
		}

		$prop = $this->propertyByCode[$code] ?? null;
		if (!$prop || empty($prop['ID'])) {
			$prop = CIBlockProperty::GetList([], [
				'IBLOCK_ID' => $this->iblockId,
				'CODE' => $code,
			])->Fetch();
			if (!$prop) {
				return;
			}
			$this->propertyByCode[$code] = $prop;
		}

		\Bitrix\Iblock\Model\PropertyFeature::setFeatures(
			(int) $prop['ID'],
			[
				[
					'MODULE_ID' => 'iblock',
					'FEATURE_ID' => 'DETAIL_PAGE_SHOW',
					'IS_ENABLED' => 'Y',
				],
			]
		);
	}

	private function propertyCodeForName($name)
	{
		$base = CUtil::translit($name, 'ru', [
			'replace_space' => '_',
			'replace_other' => '_',
		]);
		$base = strtoupper(trim(preg_replace('/_+/', '_', $base), '_'));
		if ($base === '') {
			$base = 'ATTR';
		}
		$code = 'ATR_' . mb_substr($base, 0, 40);
		if (!isset($this->propertyByCode[$code])) {
			return $code;
		}
		for ($i = 2; $i < 100; $i++) {
			$candidate = mb_substr($code, 0, 44) . '_' . $i;
			if (!isset($this->propertyByCode[$candidate])) {
				return $candidate;
			}
		}
		return $code . '_' . time();
	}

	private function guessPropertyType($name)
	{
		if (preg_match('/,\s*(мм|см|м|кг|л\/мин|л\\\\\/мин|°|град)/ui', $name)) {
			return 'N';
		}
		return 'S';
	}
}
