<?php
/**
 * Разовый установщик: инфоблок «Слайды на главной» + свойства + 2 слайда.
 * Бридж не умеет создавать инфоблоки, поэтому так. После запуска файл перезаписывается заглушкой.
 * Запуск: /local/templates/.default/lam/install_hero_slider.php?t=<ключ>
 */
define('NO_KEEP_STATISTIC', true);
define('NO_AGENT_CHECK', true);
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

if (!hash_equals('__TOKEN__', (string)($_GET['t'] ?? ''))) {
    http_response_code(404);
    die();
}
header('Content-Type: application/json; charset=utf-8');
CModule::IncludeModule('iblock');

$out = array();
$code = 'lam_hero_slides';
$img = $_SERVER['DOCUMENT_ROOT'] . '/local/templates/.default/lam/img/';

$ex = CIBlock::GetList(array(), array('CODE' => $code, 'CHECK_PERMISSIONS' => 'N'))->Fetch();
if ($ex) {
    die(json_encode(array('exists' => true, 'IBLOCK_ID' => (int)$ex['ID']), JSON_UNESCAPED_UNICODE));
}
if (!is_file($img . 'hero-photo-ny.webp')) {
    die(json_encode(array('error' => 'нет img/hero-photo-ny.webp — загрузите картинку нового слайда'), JSON_UNESCAPED_UNICODE));
}

$ib = new CIBlock();
$iblockId = $ib->Add(array(
    'IBLOCK_TYPE_ID' => 'aspro_mshop_adv',
    'LID' => array('s1'),
    'CODE' => $code,
    'NAME' => 'Слайды на главной',
    'ACTIVE' => 'Y',
    'SORT' => 50,
    'INDEX_ELEMENT' => 'N',
    'INDEX_SECTION' => 'N',
    'LIST_PAGE_URL' => '',
    'DETAIL_PAGE_URL' => '',
    'SECTION_PAGE_URL' => '',
    'GROUP_ID' => array('2' => 'R'),
    'DESCRIPTION_TYPE' => 'text',
    'DESCRIPTION' => "Слайды баннера на главной (новый дизайн).\n"
        . "Название — заголовок слайда. Описание для анонса — текст под заголовком.\n"
        . "Картинка для анонса — фото справа, 2264×1030 px (webp/jpg), важное держать в правой части.\n"
        . "Сортировка — порядок, даты активности — когда слайд показывать.",
    'ELEMENT_NAME' => 'Слайд',
    'ELEMENTS_NAME' => 'Слайды',
    'ELEMENT_ADD' => 'Добавить слайд',
    'ELEMENT_EDIT' => 'Изменить слайд',
    'ELEMENT_DELETE' => 'Удалить слайд',
));
if (!$iblockId) {
    die(json_encode(array('error' => 'CIBlock::Add: ' . $ib->LAST_ERROR), JSON_UNESCAPED_UNICODE));
}
$out['IBLOCK_ID'] = (int)$iblockId;

$props = array(
    array('CODE' => 'LABEL', 'NAME' => 'Плашка над заголовком', 'PROPERTY_TYPE' => 'S', 'SORT' => 100,
        'HINT' => 'Например: «Новогодняя акция до 31.12.26». Пусто — без плашки.'),
    array('CODE' => 'LABEL_COLOR', 'NAME' => 'Цвет плашки', 'PROPERTY_TYPE' => 'L', 'LIST_TYPE' => 'L', 'SORT' => 110,
        'VALUES' => array(
            array('VALUE' => 'Голубая', 'XML_ID' => 'cyan', 'DEF' => 'Y', 'SORT' => 100),
            array('VALUE' => 'Жёлтая (акция)', 'XML_ID' => 'yellow', 'DEF' => 'N', 'SORT' => 200),
        )),
    array('CODE' => 'BUTTON_TEXT', 'NAME' => 'Текст кнопки', 'PROPERTY_TYPE' => 'S', 'SORT' => 200,
        'DEFAULT_VALUE' => 'Выбрать покрытие', 'HINT' => 'Пусто — без кнопки.'),
    array('CODE' => 'BUTTON_LINK', 'NAME' => 'Ссылка кнопки', 'PROPERTY_TYPE' => 'S', 'SORT' => 210,
        'DEFAULT_VALUE' => '/catalog/', 'HINT' => 'Например: /catalog/ или /catalog/laminat/'),
);
$enum = array();
foreach ($props as $p) {
    $bp = new CIBlockProperty();
    $pid = $bp->Add($p + array('IBLOCK_ID' => $iblockId, 'ACTIVE' => 'Y'));
    $out['props'][$p['CODE']] = $pid ?: ('ERROR ' . $bp->LAST_ERROR);
}
$res = CIBlockPropertyEnum::GetList(array(), array('IBLOCK_ID' => $iblockId, 'CODE' => 'LABEL_COLOR'));
while ($e = $res->Fetch()) {
    $enum[$e['XML_ID']] = (int)$e['ID'];
}

$slides = array(
    array('NAME' => 'Идеальный пол — основа дома', 'SORT' => 100, 'ACTIVE_TO' => '',
        'TEXT' => 'Ламинат и напольные покрытия<br>в наличии по реальным ценам',
        'PIC' => 'hero-photo.webp', 'LABEL' => 'Популярные оттенки', 'COLOR' => 'cyan'),
    array('NAME' => 'Скидка 6% на весь ассортимент', 'SORT' => 200, 'ACTIVE_TO' => '31.12.2026 23:59:59',
        'TEXT' => 'Акция действует до Нового года:<br>успейте выбрать то, что давно хотели',
        'PIC' => 'hero-photo-ny.webp', 'LABEL' => 'Новогодняя акция до 31.12.26', 'COLOR' => 'yellow'),
);
foreach ($slides as $s) {
    $el = new CIBlockElement();
    $id = $el->Add(array(
        'IBLOCK_ID' => $iblockId,
        'NAME' => $s['NAME'],
        'ACTIVE' => 'Y',
        'SORT' => $s['SORT'],
        'ACTIVE_TO' => $s['ACTIVE_TO'],
        'PREVIEW_TEXT' => $s['TEXT'],
        'PREVIEW_TEXT_TYPE' => 'html',
        'PREVIEW_PICTURE' => CFile::MakeFileArray($img . $s['PIC']),
        'PROPERTY_VALUES' => array(
            'LABEL' => $s['LABEL'],
            'LABEL_COLOR' => $enum[$s['COLOR']] ?? false,
            'BUTTON_TEXT' => 'Выбрать покрытие',
            'BUTTON_LINK' => '/catalog/',
        ),
    ));
    $out['elements'][$s['NAME']] = $id ?: ('ERROR ' . $el->LAST_ERROR);
}

echo json_encode($out, JSON_UNESCAPED_UNICODE);
