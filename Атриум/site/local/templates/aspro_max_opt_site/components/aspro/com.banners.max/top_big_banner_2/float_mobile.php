<?
$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));?>
<?$isUrl=(strlen($arItem["PROPERTIES"]["URL_STRING"]["VALUE"]) ? true : false);?>
<div class="item normal_block <?=$arItem["PROPERTIES"]["BANNER_SIZE"]["VALUE_XML_ID"];?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
	<div class="item_inner darken-bg-animate">
		<?$arItem["FORMAT_NAME"]=strip_tags($arItem["~NAME"]);?>
		<?if($isUrl):?>
			<a href="<?=$arItem["PROPERTIES"]["URL_STRING"]["VALUE"]?>" class="opacity_block1 dark_block_animate" title="<?=$arItem["FORMAT_NAME"];?>" <?=($arItem["PROPERTIES"]["TARGETS"]["VALUE_XML_ID"] ? "target='".$arItem["PROPERTIES"]["TARGETS"]["VALUE_XML_ID"]."'" : "");?>></a>
		<?endif;?>
		<?if($arItem["PROPERTIES"]["TEXT_POSITION"]["VALUE_XML_ID"] != "image"):?>
			<?$class_position_block = $class_text_block = '';
			if(isset($arItem["PROPERTIES"]["TEXT_POSITION"]) && $arItem["PROPERTIES"]["TEXT_POSITION"]["VALUE_XML_ID"])
				$class_position_block = $arItem["PROPERTIES"]["TEXT_POSITION"]["VALUE_XML_ID"].'_blocks';
			if(isset($arItem["PROPERTIES"]["TEXTCOLOR"]) && $arItem["PROPERTIES"]["TEXTCOLOR"]["VALUE_XML_ID"])
				$class_text_block = $arItem["PROPERTIES"]["TEXTCOLOR"]["VALUE_XML_ID"].'_text';
			?>
		<?endif;?>
		<?php
		$imageSrc = isset($arItem['PROPERTIES']['TABLET_IMAGE']['VALUE']) ? CFile::GetPath($arItem['PROPERTIES']['TABLET_IMAGE']['VALUE']) : $arItem["PREVIEW_PICTURE"]["SRC"];
?>
		<div class="scale_block_animate img_block lazy"
			 style="background-image:url(<?= \Aspro\Functions\CAsproMax::showBlankImg($imageSrc); ?>)"
			 data-src="<?= $imageSrc ?>">
		</div>
	</div>
</div>