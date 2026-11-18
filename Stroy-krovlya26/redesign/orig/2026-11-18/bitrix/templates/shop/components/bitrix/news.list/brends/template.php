<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bas\Pict;?>
	<div class="Brends">
		<div class="Items">
			<?foreach($arResult["ITEMS"] as $arItem):?>
			<?
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
			?>
			<div id="<?=$this->GetEditAreaId($arItem['ID']);?>" class="Item">
				<div class="Item_Inner">
					<a href="<?echo $arItem["DETAIL_PAGE_URL"]?>">
						<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):
							$PICTURE = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], Array('width'=>824, 'height'=>441), BX_RESIZE_IMAGE_EXACT, true);
						?>
						<img src="<?=Pict::getResizeWebpSrc($arItem['PREVIEW_PICTURE'], 125, 88)?>" width="125" height="88" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>">
						<?endif;?>
						<span><?=$arItem["NAME"]?></span>
					</a>
				</div>
			</div>
			<?endforeach;?>
		</div>
	</div>