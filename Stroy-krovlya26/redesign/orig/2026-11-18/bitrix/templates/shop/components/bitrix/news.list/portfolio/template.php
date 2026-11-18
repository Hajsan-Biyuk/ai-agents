<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bas\Pict;?>
	<div class="Items">
		<?
		$item=1;
		foreach($arResult["ITEMS"] as $arItem):?>
		<?
		$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
		?>
		<div class="Item<?if($item=1):?> First<?else:?> Last<?endif;?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
			<a href="<?echo $arItem["DETAIL_PAGE_URL"]?>">
				<span class="Opacity">
					<span class="Desc">
						<span class="Name"><?echo $arItem["NAME"]?></span>
						<?if($item==1):?>
						<span class="Anonse">
							<?=$arItem["PREVIEW_TEXT"]?>
						</span>
						<?endif;?>
						<span class="InfoText">
							<?if($arItem['PROPERTIES']['OBJECT']['VALUE']):?>
							<span class="Info"><?=$arItem['PROPERTIES']['OBJECT']['VALUE']?></span>
							<?endif;?>
							<?if($arItem['PROPERTIES']['ADRESS']['VALUE']):?>
							<span class="City"><?=$arItem['PROPERTIES']['ADRESS']['VALUE']?></span>
							<?endif;?>
						</span>
					</span>
				</span>
				<?if($item=1):?>
					<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):
						$PICTURE = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], Array('width'=>900, 'height'=>700), BX_RESIZE_IMAGE_EXACT, true);
					?>
					<img src="<?=Pict::getResizeWebpSrc($arItem['PREVIEW_PICTURE'], 600, 447)?>" width="600" height="447" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>">
					<?endif;?>
				<?else:?>
					<img src="<?=Pict::getResizeWebpSrc($arItem['PREVIEW_PICTURE'], 250, 250)?>" width="250" height="250" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>">
				<?endif;?>
			</a>
		</div>
		<?
		$item++;
		endforeach;?>
	</div>