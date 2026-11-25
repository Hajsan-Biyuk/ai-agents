<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
// Картинка плашки подраздела (Figma 181-3639): 1) картинка раздела; 2) иконка раздела из розницы
// (images/opt-sections/<CODE>.*, у разделов опта своих картинок нет); 3) фото первого товара раздела или подразделов глубже.
$iconDir = '/local/templates/aspro_max_opt_site/images/opt-sections/';
foreach ($arResult['SECTIONS'] as &$arSection) {
	$arSection['OPT_IMAGE'] = '';
	$fileId = (int)($arSection['PICTURE']['ID'] ?? $arSection['PICTURE'] ?? 0);
	if (!$fileId && $arSection['CODE'] !== '') {
		foreach (['png', 'jpg', 'jpeg', 'webp'] as $ext) {
			if (is_file($_SERVER['DOCUMENT_ROOT'].$iconDir.$arSection['CODE'].'.'.$ext)) {
				$arSection['OPT_IMAGE'] = $iconDir.$arSection['CODE'].'.'.$ext;
				break;
			}
		}
		if ($arSection['OPT_IMAGE']) continue;
	}
	if (!$fileId) {
		$el = CIBlockElement::GetList(
			['SORT' => 'ASC', 'ID' => 'DESC'],
			['IBLOCK_ID' => $arParams['IBLOCK_ID'], 'SECTION_ID' => $arSection['ID'], 'INCLUDE_SUBSECTIONS' => 'Y', 'ACTIVE' => 'Y',
				['LOGIC' => 'OR', '!PREVIEW_PICTURE' => false, '!DETAIL_PICTURE' => false]],
			false, ['nTopCount' => 1], ['ID', 'PREVIEW_PICTURE', 'DETAIL_PICTURE']
		)->Fetch();
		if ($el) $fileId = (int)($el['PREVIEW_PICTURE'] ?: $el['DETAIL_PICTURE']);
	}
	$img = $fileId ? CFile::ResizeImageGet($fileId, ['width' => 120, 'height' => 120], BX_RESIZE_IMAGE_PROPORTIONAL) : null;
	$arSection['OPT_IMAGE'] = $img['src'] ?? '';
}
unset($arSection);
