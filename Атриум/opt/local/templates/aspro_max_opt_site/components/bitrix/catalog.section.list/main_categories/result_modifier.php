<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
// Картинка плашки: картинка раздела, а если её нет — фото первого товара раздела (у разделов опта картинок нет).
foreach ($arResult['SECTIONS'] as &$arSection) {
	$fileId = (int)($arSection['PICTURE']['ID'] ?? $arSection['PICTURE'] ?? 0);
	if (!$fileId) {
		$rs = CIBlockElement::GetList(
			['SORT' => 'ASC', 'ID' => 'DESC'],
			['IBLOCK_ID' => $arParams['IBLOCK_ID'], 'SECTION_ID' => $arSection['ID'], 'INCLUDE_SUBSECTIONS' => 'Y', 'ACTIVE' => 'Y',
				['LOGIC' => 'OR', '!PREVIEW_PICTURE' => false, '!DETAIL_PICTURE' => false]],
			false, ['nTopCount' => 1], ['ID', 'PREVIEW_PICTURE', 'DETAIL_PICTURE']
		);
		if ($el = $rs->Fetch()) {
			$fileId = (int)($el['PREVIEW_PICTURE'] ?: $el['DETAIL_PICTURE']);
		}
	}
	$arSection['OPT_IMAGE'] = '';
	if ($fileId) {
		$img = CFile::ResizeImageGet($fileId, ['width' => 452, 'height' => 306], BX_RESIZE_IMAGE_PROPORTIONAL);
		$arSection['OPT_IMAGE'] = $img['src'] ?? '';
	}
}
unset($arSection);

// порядок плашек — по полю «Сортировка» раздела (отмеченные разделы бывают разных уровней, дерево их перемешивает)
usort($arResult['SECTIONS'], fn($a, $b) => [(int)$a['SORT'], $a['NAME']] <=> [(int)$b['SORT'], $b['NAME']]);
