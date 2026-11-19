<?php
// Редизайн 2026 под флагом: ?sk_preview=1 — включить себе, ?sk_preview=0 — выключить. Для всех — SK_DEFAULT_ON = true.
if (!defined('SK_NEW')) {
	define('SK_DEFAULT_ON', true); // 18.11.2026 включено для всех по команде заказчика; откат — false
	define('SK_V', '8');
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
