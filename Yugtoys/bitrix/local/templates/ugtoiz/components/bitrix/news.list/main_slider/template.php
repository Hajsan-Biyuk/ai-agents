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
			<? if(true || $USER->IsAuthorized()){ ?>
			<a href="#callback_my" class="btn-main btn-main_page fancybox">СВЯЗАТЬСЯ С МЕНЕДЖЕРОМ</a>
			<? }else{ ?>
			<a href="#opt_price" class="btn-main btn-main_page fancybox">ЗАПРОСИТЬ ОПТОВЫЙ ПРАЙС</a>
			<? } ?>
			<div class="item-billbord__image">
				<img alt="alt" src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>">
			</div>
		</div>
	</li>
<?endforeach;?>
</ul>