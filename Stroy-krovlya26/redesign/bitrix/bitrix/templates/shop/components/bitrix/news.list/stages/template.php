<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bas\Pict;?>
		<div class="Items">
			<?foreach($arResult["ITEMS"] as $arItem):?>
			<?
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
			?>
			<div id="<?=$this->GetEditAreaId($arItem['ID']);?>" class="Item">
				<div class="Item_Inner">
					<?if($arItem["PREVIEW_PICTURE"]):?>
					<div class="Icon"><img src="<?=Pict::getResizeWebpSrc($arItem['PREVIEW_PICTURE'], 195, 195)?>" width="195" height="179" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>"></div>
					<?endif;?>
					<div class="Desc">
						<div class="Name"><?=$arItem["NAME"]?></div>
						<?if($arItem["PREVIEW_TEXT"]):?>
						<?=$arItem["PREVIEW_TEXT"]?>
						<?endif;?>
					</div>
				</div>
			</div>
			<?endforeach;?>
		</div>