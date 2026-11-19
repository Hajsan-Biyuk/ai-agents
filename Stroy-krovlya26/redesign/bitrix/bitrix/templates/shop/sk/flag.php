<?php
// Редизайн 2026 под флагом: ?sk_preview=1 — включить себе, ?sk_preview=0 — выключить. Для всех — SK_DEFAULT_ON = true.
if (!defined('SK_NEW')) {
	define('SK_DEFAULT_ON', true); // 18.11.2026 включено для всех по команде заказчика; откат — false
	define('SK_V', '11');
	if (isset($_GET['sk_preview']) && ($_GET['sk_preview'] === '1' || $_GET['sk_preview'] === '0')) {
		setcookie('sk_preview', $_GET['sk_preview'], time() + 86400 * 30, '/');
		$_COOKIE['sk_preview'] = $_GET['sk_preview'];
	}
	$skCookie = isset($_COOKIE['sk_preview']) ? (string)$_COOKIE['sk_preview'] : '';
	define('SK_NEW', SK_DEFAULT_ON ? $skCookie !== '0' : $skCookie === '1');
	define('SK_PATH', '/bitrix/templates/shop/sk');
	define('SK_INN', '2636805780'); // ИНН оператора ООО «СТРОЙ КРОВЛЯ» (от заказчика 18.11.2026)
}

function sk_menu($type, $class, $mod = '')
{
	$GLOBALS['APPLICATION']->IncludeComponent('bitrix:menu', 'sk_list', array(
		'ROOT_MENU_TYPE' => $type, 'MAX_LEVEL' => '1', 'CHILD_MENU_TYPE' => '', 'USE_EXT' => 'Y',
		'MENU_CACHE_TYPE' => 'A', 'MENU_CACHE_TIME' => '86400', 'MENU_CACHE_USE_GROUPS' => 'N', 'MENU_CACHE_GET_VARS' => array(),
		'DELAY' => 'N', 'ALLOW_MULTI_SELECT' => 'N', 'SK_CLASS' => $class, 'SK_LIST_MOD' => $mod,
	), false, array('HIDE_ICONS' => 'Y'));
}

/**
 * Поля новой главной из инфоблока «Главная страница» (код home-page, один элемент).
 * Кеш сбрасывается сам при сохранении элемента (тег iblock_id_N). Пустое поле — значение по умолчанию из шаблона.
 */
function sk_home_props()
{
	static $data = null;
	if ($data !== null) return $data;
	$data = array('ID' => 0, 'IBLOCK_ID' => 0, 'P' => array());
	$cache = new CPHPCache();
	if ($cache->InitCache(86400, 'sk_home_' . SITE_ID, '/sk_home')) {
		$data = $cache->GetVars();
	} elseif (CModule::IncludeModule('iblock') && $cache->StartDataCache()) {
		$GLOBALS['CACHE_MANAGER']->StartTagCache('/sk_home');
		$ib = CIBlock::GetList(array(), array('CODE' => 'home-page', 'TYPE' => 'info', 'SITE_ID' => SITE_ID))->Fetch();
		if ($ib) {
			$GLOBALS['CACHE_MANAGER']->RegisterTag('iblock_id_' . $ib['ID']);
			$ob = CIBlockElement::GetList(array('SORT' => 'ASC', 'ID' => 'ASC'), array('IBLOCK_ID' => $ib['ID'], 'ACTIVE' => 'Y'), false, array('nTopCount' => 1))->GetNextElement();
			if ($ob) {
				$f = $ob->GetFields();
				$data = array('ID' => (int)$f['ID'], 'IBLOCK_ID' => (int)$ib['ID'], 'P' => $ob->GetProperties());
			}
		}
		$GLOBALS['CACHE_MANAGER']->EndTagCache();
		$cache->EndDataCache($data);
	}
	return $data;
}

// Текст поля, готовый к выводу: строки экранированы, HTML-поля (визуальный редактор) — как есть от администратора.
function sk_home($code, $default = '')
{
	$d = sk_home_props();
	if (empty($d['P'][$code])) return $default;
	$p = $d['P'][$code];
	if ($p['USER_TYPE'] === 'HTML') {
		$t = isset($p['~VALUE']['TEXT']) ? trim($p['~VALUE']['TEXT']) : '';
	} elseif ($p['PROPERTY_TYPE'] === 'F') {
		$t = $p['VALUE'] ? (string)CFile::GetPath($p['VALUE']) : '';
	} else {
		$t = is_array($p['VALUE']) ? '' : trim((string)$p['VALUE']);
	}
	return $t !== '' ? $t : $default;
}

// Множественное поле: массив array('text' => ..., 'desc' => ...), экранировано.
function sk_home_list($code)
{
	$d = sk_home_props();
	$out = array();
	if (!empty($d['P'][$code]['VALUE']) && is_array($d['P'][$code]['VALUE'])) {
		foreach ($d['P'][$code]['VALUE'] as $i => $v) {
			if (trim($v) === '') continue;
			$out[] = array('text' => $v, 'desc' => isset($d['P'][$code]['DESCRIPTION'][$i]) ? $d['P'][$code]['DESCRIPTION'][$i] : '');
		}
	}
	return $out;
}

// Ссылка «Редактировать главную» в режиме правки для администратора.
function sk_home_edit_button()
{
	global $APPLICATION, $USER;
	$d = sk_home_props();
	if (!$d['ID'] || !is_object($USER) || !$USER->IsAdmin() || !$APPLICATION->GetShowIncludeAreas()) return '';
	return '<a class="sk-home-edit" href="/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=' . $d['IBLOCK_ID'] . '&type=info&ID=' . $d['ID'] . '&lang=ru" target="_blank">✎ Редактировать главную</a>';
}
