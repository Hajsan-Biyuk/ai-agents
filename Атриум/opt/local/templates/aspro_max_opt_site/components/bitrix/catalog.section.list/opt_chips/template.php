<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** Подразделы над списком товаров: плашка 60px-картинка + название (Figma 181-3639). */
$this->setFrameMode(true);
if (empty($arResult['SECTIONS'])) return;?>
<div class="opt-chips">
	<?foreach ($arResult['SECTIONS'] as $arSection):
		$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], CIBlock::GetArrayByID($arSection['IBLOCK_ID'], 'SECTION_EDIT'));?>
		<a class="opt-chips__item" href="<?=$arSection['SECTION_PAGE_URL']?>" id="<?=$this->GetEditAreaId($arSection['ID'])?>">
			<span class="opt-chips__img"><?if($arSection['OPT_IMAGE']):?><img class="lazy" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($arSection['OPT_IMAGE'])?>" data-src="<?=$arSection['OPT_IMAGE']?>" alt=""><?endif;?></span>
			<span class="opt-chips__name"><?=$arSection['NAME']?></span>
		</a>
	<?endforeach;?>
</div>
