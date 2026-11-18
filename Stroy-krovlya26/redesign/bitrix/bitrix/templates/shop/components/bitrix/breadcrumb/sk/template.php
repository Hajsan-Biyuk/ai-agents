<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
// Хлебные крошки редизайна 2026 (логика как в шаблоне nav). Отложенная функция — вернуть строку.
$curPage = $GLOBALS['APPLICATION']->GetCurPage(false);
if ($curPage != SITE_DIR && (empty($arResult) || $curPage != $arResult[count($arResult) - 1]['LINK'])) {
	$arResult[] = array('TITLE' => htmlspecialcharsback($GLOBALS['APPLICATION']->GetTitle(false, true)), 'LINK' => $curPage);
}
if (empty($arResult)) return '';
$s = '<nav aria-label="Хлебные крошки"><ol class="sk-breadcrumbs"><li class="sk-breadcrumbs__item"><a class="sk-breadcrumbs__link" href="' . SITE_DIR . '">Главная</a></li>';
$last = count($arResult) - 1;
foreach ($arResult as $i => $item) {
	$title = htmlspecialcharsex($item['TITLE']);
	$s .= $item['LINK'] != '' && $i < $last
		? '<li class="sk-breadcrumbs__item"><a class="sk-breadcrumbs__link" href="' . $item['LINK'] . '">' . $title . '</a></li>'
		: '<li class="sk-breadcrumbs__item" aria-current="page">' . $title . '</li>';
}
return $s . '</ol></nav>';
