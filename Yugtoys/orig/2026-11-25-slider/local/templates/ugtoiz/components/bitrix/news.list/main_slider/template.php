<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
global $USER;
// П-6 / С-2: баннер → раздел каталога и подпись картинки. Свойства LINK в ИБ 5 пока нет — по ID элемента (порядок с 23.11).
// shortcut: при замене картинки/баннера в админке поправить ссылку здесь или завести в ИБ 5 свойство LINK (строка) — оно в приоритете.
$banners = array(
	52 => array('/catalog/novinki/', 'Большой ассортимент новогодних товаров и игрушек 2027'),
	14932 => array('/search/?q=мягкие+игрушки', 'Мягкие игрушки для детей оптом'),
	14931 => array('/catalog/igrushki/dlya-malchikov/', 'Игрушки для мальчиков оптом'),
	22159 => array('/catalog/igrushki/dlya-devochek/', 'Игрушки для девочек оптом'),
	22162 => array('/catalog/razvivayushchie-igry/tovary-dlya-tvorchestva/', 'Алмазная мозаика и картины по номерам оптом'),
	22160 => array('/catalog/razvivayushchie-igry/tovary-dlya-tvorchestva/', 'Алмазная мозаика и картины по номерам оптом'),
);
?>
<ul class="slider-billbord">
<?$shown = array();?>
<?foreach($arResult["ITEMS"] as $key => $arItem):?>
<?
	// дубль баннера с той же картинкой (23.11: 22160 и 22162 с одной картинкой, 22162 отключён в админке) не выводим; деактивировать в ИБ 5 — бриджем нельзя
	if (isset($shown[$arItem["PREVIEW_PICTURE"]["SRC"]])) continue;
	$shown[$arItem["PREVIEW_PICTURE"]["SRC"]] = true;
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	
?>
	<li>
		<div class="item-billbord" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
			<div class="title-big"><?=$arItem["PREVIEW_TEXT"]?></div>
			<div class="descr-big"><?=$arItem["DETAIL_TEXT"]?></div>
			<?
			$link = isset($arItem['PROPERTIES']['LINK']['VALUE']) ? trim((string)$arItem['PROPERTIES']['LINK']['VALUE']) : '';
			if ($link === '' && isset($banners[$arItem['ID']])) $link = $banners[$arItem['ID']][0];
			$alt = isset($banners[$arItem['ID']]) ? $banners[$arItem['ID']][1] : $arItem['NAME'];
			?>
			<a href="#callback_my" class="btn-main btn-main_page fancybox">СВЯЗАТЬСЯ С МЕНЕДЖЕРОМ</a>
			<?=($link !== '' ? '<a href="'.htmlspecialcharsbx($link).'" class="item-billbord__image">' : '<div class="item-billbord__image">')?>
				<img alt="<?=htmlspecialcharsbx($alt)?>" src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>">
			<?=($link !== '' ? '</a>' : '</div>')?>
		</div>
	</li>
<?endforeach;?>
</ul>