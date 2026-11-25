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
<div class="swiper swiper--billbord">
	<div class="swiper-wrapper">
	<?foreach($arResult["ITEMS"] as $key => $arItem):?>
	<?
		$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
			<?
			// мобильная картинка — свойство «Картинка для <768px» (TABLET_IMAGE) у баннера
			$mobileId = $arItem['PROPERTIES']['TABLET_IMAGE']['VALUE'] ?? '';
			if (is_array($mobileId)) {
				$mobileId = reset($mobileId);
			}
			$arMobile = $mobileId ? CFile::GetFileArray($mobileId) : false;
			$mobileSrc = $arMobile['SRC'] ?? '';
			// высота баннера на телефоне — по пропорциям первой мобильной картинки (custom.css, --opt-mob-ratio)
			if ($mobileSrc && empty($mobileRatio) && $arMobile['WIDTH'] > 0 && $arMobile['HEIGHT'] > 0) {
				$mobileRatio = (int)$arMobile['WIDTH'].' / '.(int)$arMobile['HEIGHT'];
			}
			?>
		<div class="swiper-slide<?=($mobileSrc ? ' has-mobile' : '')?>">
			<a href="<?=$arItem['PROPERTIES']['URL_STRING']['VALUE']?>" class="swiper--billbord__slide" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
				<img class="billbord-img billbord-img--desktop" src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="<?=htmlspecialcharsbx($arItem['NAME'])?>">
				<?if($mobileSrc):?>
					<img class="billbord-img billbord-img--mobile" src="<?=$mobileSrc?>" alt="<?=htmlspecialcharsbx($arItem['NAME'])?>">
				<?endif;?>
			</a>
		</div>
	<?endforeach;?>
	</div>
	<?if(!empty($mobileRatio)):?><style>.opt-billbord__slider{--opt-mob-ratio: <?=$mobileRatio?>;}</style><?endif;?>
	<div class="btn-swipe swiper-button-prev"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_2004_321)"><path d="M10.828 12L15.778 16.95L14.364 18.364L8 12L14.364 5.63599L15.778 7.04999L10.828 12Z" fill="#2B5840" /></g><defs><clipPath id="clip0_2004_321"><rect width="24" height="24" fill="white" /></clipPath></defs></svg></div>
	<div class="btn-swipe swiper-button-next"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_2004_1768)"><path d="M13.172 12L8.222 16.95L9.636 18.364L16 12L9.636 5.63599L8.222 7.04999L13.172 12Z" fill="#2B5840" /></g><defs><clipPath id="clip0_2004_1768"><rect width="24" height="24" fill="white" transform="matrix(-1 0 0 1 24 0)" /></clipPath></defs></svg></div>
</div>