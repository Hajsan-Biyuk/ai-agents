<?php
// Разовый скрипт для «Командной PHP-строки»: инфоблок «Главная страница» (поля редизайна 2026),
// элемент с текущими текстами, картинки дизайна в «Детальную картинку» разделов каталога и этапов.
// Повторный запуск безопасен: ничего не создаёт второй раз и не перезаписывает заполненное.
CModule::IncludeModule('iblock');
$root = $_SERVER['DOCUMENT_ROOT'];
$img = $root . '/bitrix/templates/shop/sk/img/';
$inc = function ($f) use ($root) { $p = $root . '/include/' . $f; return is_file($p) ? trim(file_get_contents($p)) : ''; };
$plain = function ($f) use ($inc) { return trim(strip_tags($inc($f))); };

$ib = CIBlock::GetList(array(), array('CODE' => 'home-page', 'TYPE' => 'info'))->Fetch();
if ($ib) {
	$ibId = (int)$ib['ID'];
	echo "iblock exists $ibId\n";
} else {
	$o = new CIBlock;
	$ibId = $o->Add(array(
		'ACTIVE' => 'Y', 'NAME' => 'Главная страница', 'CODE' => 'home-page', 'IBLOCK_TYPE_ID' => 'info',
		'SITE_ID' => array('s1'), 'SORT' => 10, 'GROUP_ID' => array('2' => 'R'),
		'LIST_PAGE_URL' => '', 'DETAIL_PAGE_URL' => '', 'INDEX_ELEMENT' => 'N', 'WORKFLOW' => 'N',
		'DESCRIPTION' => 'Тексты, кнопки и фон новой главной. Один элемент — «Главная страница».',
	));
	if (!$ibId) { echo 'IBLOCK FAIL ' . $o->LAST_ERROR; return; }
	echo "iblock created $ibId\n";
}

$props = array(
	array('HERO_BADGE', 'Первый экран: плашка над заголовком', 'S'),
	array('HERO_TITLE', 'Первый экран: заголовок', 'S'),
	array('HERO_DESCR', 'Первый экран: подзаголовок', 'S', array('ROW_COUNT' => 3, 'COL_COUNT' => 70)),
	array('HERO_CHIPS', 'Первый экран: плашки-преимущества (по одной в строке)', 'S', array('MULTIPLE' => 'Y', 'MULTIPLE_CNT' => 1)),
	array('HERO_FACTS', 'Первый экран: строка фактов под кнопками', 'S', array('USER_TYPE' => 'HTML')),
	array('HERO_IMAGE', 'Первый экран: фон (горизонтальное фото ~1920×960)', 'F', array('FILE_TYPE' => 'jpg, jpeg, png, webp')),
	array('HERO_BTN_CALC', 'Первый экран: кнопка расчёта', 'S'),
	array('HERO_BTN_CALL', 'Первый экран: кнопка звонка', 'S'),
	array('FORM_TITLE', 'Форма расчёта: заголовок', 'S'),
	array('TOP_MENU', 'Верхнее меню: пункт (в описании — ссылка)', 'S', array('MULTIPLE' => 'Y', 'WITH_DESCRIPTION' => 'Y', 'MULTIPLE_CNT' => 1)),
	array('ABOUT_TITLE', '«Почему мы»: заголовок', 'S'),
	array('ABOUT_TEXT', '«Почему мы»: текст', 'S', array('ROW_COUNT' => 4, 'COL_COUNT' => 70)),
	array('CATALOG_TITLE', 'Каталог разделов: заголовок', 'S'),
	array('TAB_MATERIALS', 'Каталог разделов: вкладка «по материалам»', 'S'),
	array('TAB_BRANDS', 'Каталог разделов: вкладка «по брендам»', 'S'),
	array('STEPS_TITLE', '«Как мы работаем»: заголовок', 'S'),
	array('STEPS_TEXT', '«Как мы работаем»: текст', 'S', array('ROW_COUNT' => 3, 'COL_COUNT' => 70)),
	array('STEPS_BTN', '«Как мы работаем»: кнопка', 'S'),
	array('GOODS_TITLE', 'Товары (Акции/Хит…): заголовок', 'S'),
	array('OBJECTS_TITLE', '«Наши объекты»: заголовок', 'S'),
	array('OBJECTS_TEXT', '«Наши объекты»: текст', 'S', array('ROW_COUNT' => 3, 'COL_COUNT' => 70)),
	array('SEO_TITLE', 'SEO-блок: заголовок h1', 'S', array('USER_TYPE' => 'HTML')),
	array('SEO_TEXT', 'SEO-блок: текст', 'S', array('USER_TYPE' => 'HTML')),
	array('SEO_BTN_CALC', 'SEO-блок: кнопка расчёта', 'S'),
	array('SEO_BTN_PRICE', 'SEO-блок: кнопка прайса', 'S'),
	array('SEO_PRICE_LINK', 'SEO-блок: ссылка кнопки прайса', 'S'),
	array('FOOTER_NOTE', 'Подвал: текст об оферте', 'S', array('ROW_COUNT' => 4, 'COL_COUNT' => 70)),
);
$have = array();
$r = CIBlockProperty::GetList(array(), array('IBLOCK_ID' => $ibId));
while ($p = $r->Fetch()) $have[$p['CODE']] = true;
$sort = 10;
foreach ($props as $p) {
	if (empty($have[$p[0]])) {
		$o = new CIBlockProperty;
		$id = $o->Add(array_merge(array('IBLOCK_ID' => $ibId, 'CODE' => $p[0], 'NAME' => $p[1], 'PROPERTY_TYPE' => $p[2], 'ACTIVE' => 'Y', 'SORT' => $sort), isset($p[3]) ? $p[3] : array()));
		echo ($id ? 'prop ' : 'PROP FAIL ') . $p[0] . ' ' . ($id ?: $o->LAST_ERROR) . "\n";
	}
	$sort += 10;
}

// Значения — текущие тексты сайта.
preg_match_all('~<li[^>]*>\s*(.*?)\s*</li>~s', $inc('sk_hero_chips.php'), $chips);
preg_match_all('~<a[^>]*href="([^"]*)"[^>]*>(.*?)</a>~s', $inc('sk_topmenu.php'), $menu);
$menuVals = array();
foreach ($menu[2] as $i => $t) $menuVals['n' . $i] = array('VALUE' => trim(strip_tags($t)), 'DESCRIPTION' => $menu[1][$i]);
$chipVals = array();
foreach ($chips[1] as $i => $t) $chipVals['n' . $i] = array('VALUE' => trim(strip_tags($t)));
$html = function ($t) { return array('VALUE' => array('TEXT' => $t, 'TYPE' => 'HTML')); };
$values = array(
	'HERO_BADGE' => $plain('sk_hero_badge.php'),
	'HERO_TITLE' => $plain('sk_hero_title.php'),
	'HERO_DESCR' => $plain('sk_hero_descr.php'),
	'HERO_CHIPS' => $chipVals,
	'HERO_FACTS' => $html($inc('sk_hero_facts.php')),
	'HERO_IMAGE' => CFile::MakeFileArray($img . 'hero.webp'),
	'HERO_BTN_CALC' => 'Получить расчёт за 15 минут',
	'HERO_BTN_CALL' => 'Позвонить',
	'FORM_TITLE' => 'Быстрый расчёт стоимости кровли',
	'TOP_MENU' => $menuVals,
	'ABOUT_TITLE' => $plain('stats-title.php'),
	'ABOUT_TEXT' => $plain('stats-text.php'),
	'CATALOG_TITLE' => 'Каталог',
	'TAB_MATERIALS' => 'По материалам',
	'TAB_BRANDS' => 'По брендам',
	'STEPS_TITLE' => $plain('stage-title.php'),
	'STEPS_TEXT' => $plain('stage-text.php'),
	'STEPS_BTN' => 'Получить консультацию',
	'GOODS_TITLE' => 'Каталог',
	'OBJECTS_TITLE' => $plain('sk_objects_title.php'),
	'OBJECTS_TEXT' => $plain('sk_objects_text.php'),
	'SEO_TITLE' => $html($inc('sk_seo_title.php')),
	'SEO_TEXT' => $html($inc('sk_seo_text.php')),
	'SEO_BTN_CALC' => 'Получить расчет кровли',
	'SEO_BTN_PRICE' => 'Скачать прайс',
	'SEO_PRICE_LINK' => '/prays-listy/',
	'FOOTER_NOTE' => 'Обращаем ваше внимание на то, что данный интернет-сайт, а также вся информация о товарах и ценах, предоставленная на нём, носит исключительно информационный характер и ни при каких условиях не является публичной офертой, определяемой положениями Статьи 437 Гражданского кодекса Российской Федерации.',
);
$el = CIBlockElement::GetList(array(), array('IBLOCK_ID' => $ibId), false, array('nTopCount' => 1), array('ID'))->Fetch();
if ($el) {
	echo 'element exists ' . $el['ID'] . "\n";
} else {
	$o = new CIBlockElement;
	$id = $o->Add(array('IBLOCK_ID' => $ibId, 'NAME' => 'Главная страница', 'ACTIVE' => 'Y', 'PROPERTY_VALUES' => $values));
	echo ($id ? "element created $id\n" : 'ELEMENT FAIL ' . $o->LAST_ERROR . "\n");
	foreach ($values as $k => $v) if ($v === '' || $v === array()) echo "  empty: $k\n";
}

// Картинки дизайна → «Детальная картинка» разделов (если пусто) и этапов.
$cats = array('krovelnye-materialy' => 1, 'stenovye-materialy' => 2, 'gidro-i-paroizolyatsiya' => 3, 'teploizolyatsiya' => 4, 'cherdachnye-lestnitsy' => 5, 'mansardnye-okna' => 6, 'ograzhdeniya' => 7, 'vodostochnye-sistemy' => 8, 'sistemy-vodootvedeniya' => 9, 'terrasnaya-doska' => 10);
$r = CIBlockSection::GetList(array(), array('IBLOCK_ID' => 17, 'DEPTH_LEVEL' => 1), false, array('ID', 'CODE', 'DETAIL_PICTURE'));
$bs = new CIBlockSection;
while ($s = $r->Fetch()) {
	if (!$s['DETAIL_PICTURE'] && isset($cats[$s['CODE']])) {
		$ok = $bs->Update($s['ID'], array('DETAIL_PICTURE' => CFile::MakeFileArray($img . 'cat-' . $cats[$s['CODE']] . '.webp')), false, false);
		echo 'section ' . $s['CODE'] . ' ' . ($ok ? 'ok' : 'FAIL ' . $bs->LAST_ERROR) . "\n";
	}
}
$r = CIBlockElement::GetList(array('SORT' => 'ASC', 'ID' => 'ASC'), array('IBLOCK_ID' => 18), false, false, array('ID', 'DETAIL_PICTURE'));
$be = new CIBlockElement;
$i = 0;
while ($e = $r->Fetch()) {
	$i++;
	if (!$e['DETAIL_PICTURE'] && $i <= 4) {
		$ok = $be->Update($e['ID'], array('DETAIL_PICTURE' => CFile::MakeFileArray($img . 'step-' . $i . '.webp')));
		echo 'stage ' . $e['ID'] . ' ' . ($ok ? 'ok' : 'FAIL ' . $be->LAST_ERROR) . "\n";
	}
}
echo "done\n";
