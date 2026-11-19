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
<section class="clients">
	<div class="container">
		<h2 class="title-section">Мы являемся дилерами ведущих производителей товаров для детей</h2>
		<div class="row row_clients">
		<?foreach($arResult["ITEMS"] as $key => $arItem):?>
		<?
			if(!empty($arItem['PREVIEW_PICTURE']['SRC'])){
				$preview_picture = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE']['ID'], ['width'=>300, 'height'=>300], BX_RESIZE_IMAGE_PROPORTIONAL);
				$arItem['PREVIEW_PICTURE']['SRC'] = $preview_picture['src'];
			}
			if(empty($arItem['PREVIEW_PICTURE']['SRC'])) $arItem['PREVIEW_PICTURE']['SRC'] = TMPL_URL . '/images/no_photo.jpg';
		
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
		?>
			<div class="col-lg-2 col-md-4 col-6">
				<div class="item-client">
					<img alt="бренды" src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>">
				</div>
			</div>
		<?endforeach;?>
		</div>
	</div>
</section>