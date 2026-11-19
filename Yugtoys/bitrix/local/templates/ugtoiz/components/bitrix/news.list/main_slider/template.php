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
// П-6 / С-2: баннер → раздел каталога и подпись картинки. Свойства LINK в ИБ 5 пока нет — по ID элемента.
// shortcut: при замене баннера ссылку добавить сюда или завести в ИБ 5 свойство LINK (строка) — оно в приоритете.
$banners = array(
	52 => array('', 'Большой ассортимент новогодних товаров и игрушек 2027'),
	14931 => array('/catalog/myagkie-igrushki/', 'Мягкие игрушки для детей оптом'),
	14932 => array('/catalog/razvivayushchie-igry/tovary-dlya-tvorchestva/', 'Алмазная мозаика и картины по номерам оптом'),
);
?>
<ul class="slider-billbord">
<?foreach($arResult["ITEMS"] as $key => $arItem):?>
<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	
	if(isMobile()){
		if(!empty($arItem['PROPERTIES']['IMG_768']['VALUE'])){
			$arItem["PREVIEW_PICTURE"]["SRC"] = CFile::GetPath($arItem['PROPERTIES']['IMG_768']['VALUE']);
		}
	}
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
			<? if($link !== ''){ ?>
			<a href="<?=htmlspecialcharsbx($link)?>" class="btn-main btn-main_page">СМОТРЕТЬ ТОВАРЫ</a>
			<? }elseif(true || $USER->IsAuthorized()){ ?>
			<a href="#callback_my" class="btn-main btn-main_page fancybox">СВЯЗАТЬСЯ С МЕНЕДЖЕРОМ</a>
			<? }else{ ?>
			<a href="#opt_price" class="btn-main btn-main_page fancybox">ЗАПРОСИТЬ ОПТОВЫЙ ПРАЙС</a>
			<? } ?>
			<?=($link !== '' ? '<a href="'.htmlspecialcharsbx($link).'" class="item-billbord__image">' : '<div class="item-billbord__image">')?>
				<img alt="<?=htmlspecialcharsbx($alt)?>" src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>">
			<?=($link !== '' ? '</a>' : '</div>')?>
		</div>
	</li>
<?endforeach;?>
</ul>