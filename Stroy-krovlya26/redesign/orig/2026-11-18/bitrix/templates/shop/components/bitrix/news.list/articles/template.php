<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bas\Pict;
?>
		<?foreach($arResult["ITEMS"] as $arItem):?>
		<?
		$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
		?>
		<div class="Item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
			<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):
				$PICTURE = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], Array('width'=>900, 'height'=>420), BX_RESIZE_IMAGE_EXACT, true);
			?>
			<div class="Image">
				<a href="<?echo $arItem["DETAIL_PAGE_URL"]?>"><img src="<?=Pict::getResizeWebpSrc($arItem['PREVIEW_PICTURE'], 300, 184)?>" width="300" height="184" title="<?=$arItem["NAME"]?>" alt="<?=$arItem["NAME"]?>"></a>
				<?if($arItem["PROPERTIES"]["TYPE"]["VALUE"]=="Статья"):?><span>Статья</span><?elseif($arItem["PROPERTIES"]["TYPE"]["VALUE"]=="Новость"):?><span class="News">Новость</span><?endif;?>
			</div>
			<?endif;?>
			<div class="Name"><a href="<?echo $arItem["DETAIL_PAGE_URL"]?>"><?echo $arItem["NAME"]?></a></div>
			<div class="Anonse"><?=$arItem["PREVIEW_TEXT"]?></div>
		</div>
		<?endforeach;?> 
		<div class="Button"><a href="/stati/" class="Btn"><span>Все записи</span></a></div>
				