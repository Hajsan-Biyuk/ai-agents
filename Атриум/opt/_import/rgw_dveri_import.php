<?php
/**
 * Импорт пачки RGW-дверей в инфоблок 80 опта (раздел 1154 «Душевые двери»). Запуск — tools/run_php_once.py,
 * пачка подставляется вместо __BATCH__ (base64 JSON из rgw_data.json). Повторный запуск безопасен:
 * товар ищется по ID совпавшего товара 1С (match) или по XML_ID rgw-site-<артикул>.
 * Цены: 10 BASE = РРЦ, 11 OPT = ОПТ. Фото: только если у товара их ещё нет.
 * Все товары помечаются OPT_LOCK_1C — обмен 1С их не выключает (init_opt_lock1c.php).
 */
@set_time_limit(115);
ini_set('display_errors', 1);
CModule::IncludeModule('iblock');
CModule::IncludeModule('catalog');

const RGW_IBLOCK = 80;
const RGW_SECTION = 1154;
const RGW_CODES = ['TOLSHCHINA_POLOTNA_MM', 'ISPOLNENIE_POLOTNA', 'SHIRINA_MM', 'RAZMERY_S_DIAPAZONOM_REGULIROVOK_MM', 'TSVET_PROFILYA', 'POVERKHNOST_PROFILYA', 'OSOBENNOSTI', 'VYSOTA_MM', 'KLASS_ZASHCHITY_GERMETICHNOSTI', 'PODDON_V_KOMPLEKTE', 'KONSTRUKTSIYA_DVEREY', 'POKRYTIE_ANTI_KAPLYA', 'STIL', 'REGULIRUEMYY_PROFIL', 'ORIENTATSIYA_USTANOVKI', 'RESURS_EKSPLUATATSII', 'KOLLEKTSIYA_1', 'STRANA_PROIZVODSTVA', 'GARANTIYA_1', 'MATERIAL_POLOTNA', 'MATERIAL_PROFILYA', 'RUCHKA'];
$batch = json_decode(base64_decode('__BATCH__'), true);
$lockEnum = (int)CIBlockPropertyEnum::GetList([], ['IBLOCK_ID' => RGW_IBLOCK, 'CODE' => 'OPT_LOCK_1C'])->Fetch()['ID'];
if (!$lockEnum) die("ERR no OPT_LOCK_1C\n");

function rgwFile($url)
{
	$f = CFile::MakeFileArray($url);
	if (!$f || empty($f['size']) || strpos((string)$f['type'], 'image/') !== 0) return false;
	$f['MODULE_ID'] = 'iblock';
	return $f;
}

function rgwPrice($productId, $groupId, $price)
{
	$row = \Bitrix\Catalog\Model\Price::getList(['filter' => ['PRODUCT_ID' => $productId, 'CATALOG_GROUP_ID' => $groupId], 'select' => ['ID']])->fetch();
	$data = ['PRODUCT_ID' => $productId, 'CATALOG_GROUP_ID' => $groupId, 'PRICE' => $price, 'CURRENCY' => 'RUB'];
	$res = $row ? \Bitrix\Catalog\Model\Price::update($row['ID'], $data) : \Bitrix\Catalog\Model\Price::add($data);
	return $res->isSuccess() ? '' : implode(';', $res->getErrorMessages());
}

$el = new CIBlockElement();
foreach ($batch as $r) {
	$xml = 'rgw-site-' . $r['art'];
	$id = (int)$r['match'];
	if (!$id && ($x = CIBlockElement::GetList([], ['IBLOCK_ID' => RGW_IBLOCK, '=XML_ID' => $xml], false, ['nTopCount' => 1], ['ID'])->Fetch())) $id = (int)$x['ID'];
	$cur = $id ? CIBlockElement::GetList([], ['IBLOCK_ID' => RGW_IBLOCK, 'ID' => $id], false, false, ['ID', 'DETAIL_PICTURE'])->Fetch() : false;
	if ($id && !$cur) { echo "ERR {$r['art']} id $id not in iblock\n"; continue; }

	$fields = ['NAME' => $r['name'], 'ACTIVE' => 'Y', 'MODIFIED_BY' => 1];
	if (!$cur || !$cur['DETAIL_PICTURE']) {
		if ($pic = rgwFile($r['img'][0])) $fields['DETAIL_PICTURE'] = $pic;
	}
	if (!$cur) {
		$code = CUtil::translit($r['name'] . ' ' . $r['art'], 'ru', ['max_len' => 200, 'replace_space' => '_', 'replace_other' => '_', 'delete_repeat_replace' => true]);
		$id = $el->Add($fields + ['IBLOCK_ID' => RGW_IBLOCK, 'IBLOCK_SECTION_ID' => RGW_SECTION, 'XML_ID' => $xml, 'CODE' => $code, 'CREATED_BY' => 1]);
		if (!$id) { echo "ERR add {$r['art']}: {$el->LAST_ERROR}\n"; continue; }
		$mode = 'new';
	} else $mode = $r['match'] ? 'upd1c' : 'upd';

	// свойства (без затирания остальных свойств товара 1С)
	$props = $r['props'] + ['OPT_LOCK_1C' => $lockEnum, 'BREND_1' => 'rgw', 'ARTIKUL_PROIZVODITELYA' => $r['art']];
	foreach (RGW_CODES as $c) if (!isset($props[$c])) $props[$c] = false; // пустая ячейка файла — стираем старое значение 1С
	if ($mode === 'new') $props['CML2_ARTICLE'] = $r['art'];
	$hasMore = CIBlockElement::GetProperty(RGW_IBLOCK, $id, [], ['CODE' => 'MORE_PHOTO'])->Fetch();
	if (empty($hasMore['VALUE'])) {
		$more = [];
		foreach (array_slice($r['img'], 1) as $i => $u) if ($f = rgwFile($u)) $more['n' . $i] = ['VALUE' => $f, 'DESCRIPTION' => ''];
		if ($more) $props['MORE_PHOTO'] = $more;
	}
	CIBlockElement::SetPropertyValuesEx($id, RGW_IBLOCK, $props);

	// поля после свойств: Update запускает раскладку FLT_ (init_filter_split.php) по уже записанным свойствам
	if ($mode !== 'new' && !$el->Update($id, $fields)) { echo "ERR upd {$r['art']}: {$el->LAST_ERROR}\n"; continue; }
	if ($mode === 'new') $el->Update($id, ['MODIFIED_BY' => 1]);

	if (!CCatalogProduct::GetByID($id)) CCatalogProduct::Add(['ID' => $id, 'TYPE' => 1, 'QUANTITY' => 0, 'MEASURE' => 5, 'VAT_ID' => 5, 'VAT_INCLUDED' => 'Y']);
	$err = rgwPrice($id, 10, $r['rrc']) . rgwPrice($id, 11, $r['opt']);
	echo "$mode {$r['art']} id=$id imgs=" . (isset($fields['DETAIL_PICTURE']) ? 1 : 0) . '+' . count($props['MORE_PHOTO'] ?? []) . ($err ? " PRICE_ERR $err" : '') . "\n";
}
