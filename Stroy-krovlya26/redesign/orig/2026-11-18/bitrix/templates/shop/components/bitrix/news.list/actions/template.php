<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bas\Pict;?>
	<div id="Banner" class="owl-carousel">
		<?foreach($arResult["ITEMS"] as $arItem):?>
		<?
		$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
		?>
		<div class="Item">
			<div class="Item_Inner" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
				<a href="<?echo $arItem["PROPERTIES"]["LINK"]["VALUE"]?>">
				<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
				<img src="<?=Pict::getResizeWebpSrc($arItem['PREVIEW_PICTURE'], 1200, 315)?>" width="1200" height="315" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>">
				<?endif;?>
				</a>
			</div>
		</div>
		<?endforeach;?> 
	</div>
				