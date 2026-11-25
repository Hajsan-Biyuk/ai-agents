<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true ); ?>
<?if($arResult['ITEMS']):?>
<div class="section-news__grid">
<?foreach($arResult["ITEMS"] as $arItem){?>
	<?
		$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
	<?if( is_array($arItem["PREVIEW_PICTURE"]) ){?>
	<?
		$preview_picture = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE']['ID'], ['width'=>1200, 'height'=>800], BX_RESIZE_IMAGE_PROPORTIONAL);
		if(empty($preview_picture['src'])) $arItem['PREVIEW_PICTURE']['SRC'] = '/images/no_photo.png';
		else $arItem['PREVIEW_PICTURE']['SRC'] = $preview_picture['src'];
	?>
	<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="section-news__card" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
		<span class="section-news__card-image">
			<img class="section-news__card-img lazy" data-src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($arItem["PREVIEW_PICTURE"]["SRC"]);?>" alt="<?=$arItem["NAME"]?>">
		</span>
		<span class="section-news__card-body">
			<span class="section-news__card-date"><?=FormatDate("d F Y", (!empty($arItem['ACTIVE_FROM']) ? strtotime($arItem['ACTIVE_FROM']) : MakeTimeStamp($arItem["TIMESTAMP_X"])))?></span>
			<span class="section-news__card-title"><?=$arItem["NAME"]?></span>
			<span class="section-news__card-text"><?=TruncateText(strip_tags($arItem["PREVIEW_TEXT"]), 50)?></span>
		</span>
	</a>
	<?}?>
<?}?>
</div>
<?endif;?>