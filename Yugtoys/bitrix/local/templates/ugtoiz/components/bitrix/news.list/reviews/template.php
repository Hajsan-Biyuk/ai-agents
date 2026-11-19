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
?>
<div class="row row_reviews">
<?foreach($arResult["ITEMS"] as $key => $arItem):?>
<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
?>
	<div class="col-lg-6">
		<div class="item-review" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
			<div class="title-block"><?=$arItem["NAME"]?></div>
			<p><?=$arItem["PREVIEW_TEXT"]?></p>
			<? if(!empty($arItem["PREVIEW_PICTURE"]["SRC"])){ ?>
			<div class="item-review__bottom">
				<a href="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" class="btn-simple fancybox">Посмотреть оригинал</a>
			</div>
			<? } ?>
		</div>
	</div>
<?endforeach;?>
</div>
<!--<div class="bottom-button">
	<a href="#" class="btn-main btn-main_simple">больше отзывов здесь</a>
</div>-->