<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
// Баннер «Сочные скидки» в карточке товара опта (ТЗ п.11).
// Включить/выключить: Контент → Реклама → «Реклама - Баннеры картинками» (ID 46) → элемент с кодом opt-card-sale → «Активность».
// Название = крупный заголовок, анонс = подзаголовок (пусто — только картинка), картинка анонса = фон, свойство «Ссылка» = переход.
if (!\Bitrix\Main\Loader::includeModule('iblock')) return;
$arBanner = CIBlockElement::GetList([], ['IBLOCK_ID' => 46, 'CODE' => 'opt-card-sale', 'ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y'], false, ['nTopCount' => 1], ['ID', 'NAME', 'PREVIEW_TEXT', 'PREVIEW_PICTURE', 'PROPERTY_URL'])->GetNext();
if (!$arBanner) return;
$src = $arBanner['PREVIEW_PICTURE'] ? CFile::GetPath($arBanner['PREVIEW_PICTURE']) : '';
$url = trim((string)$arBanner['PROPERTY_URL_VALUE']);
$tag = $url !== '' ? 'a' : 'div';
?>
<<?=$tag?> <?=$url !== '' ? 'href="'.htmlspecialcharsbx($url).'"' : ''?> class="banner-sale opt-card-banner">
	<?if ($src):?><span class="banner-sale__image"><img src="<?=$src?>" alt="<?=$arBanner['NAME']?>"></span><?endif;?>
	<?if (trim(strip_tags($arBanner['~PREVIEW_TEXT'])) !== ''):?>
		<span class="banner-sale__title"><?=$arBanner['NAME']?><span><?=strip_tags($arBanner['~PREVIEW_TEXT'])?></span></span>
	<?endif;?>
</<?=$tag?>>
