<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
if (empty($arResult['ITEMS'])) return;
$lamImg = '/local/templates/.default/lam/img/';
?>
<section class="lam-brands" aria-label="Бренды" data-lam-carousel>
	<div class="lam-container lam-brands__in">
		<button class="lam-arrow lam-arrow--white lam-arrow--prev" type="button" data-lam-prev aria-label="Предыдущие бренды"><img src="<?=$lamImg?>ic-chevron-right.svg" alt=""></button>
		<ul class="lam-track lam-brands__track">
			<?php foreach ($arResult['ITEMS'] as $arItem):
				if (empty($arItem['PREVIEW_PICTURE']['SRC'])) continue;
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
				$lamPic = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE'], array('width' => 198, 'height' => 120), BX_RESIZE_IMAGE_PROPORTIONAL, true);
				$lamName = trim($arItem['NAME']);
			?>
				<li class="lam-track__item" id="<?=$this->GetEditAreaId($arItem['ID'])?>"><a class="lam-brand" href="<?=$arItem['DETAIL_PAGE_URL']?>"><img src="<?=$lamPic['src']?>" alt="<?=htmlspecialcharsbx($lamName)?>" width="198" height="120" loading="lazy"></a></li>
			<?php endforeach; ?>
		</ul>
		<button class="lam-arrow lam-arrow--white" type="button" data-lam-next aria-label="Следующие бренды"><img src="<?=$lamImg?>ic-chevron-right.svg" alt=""></button>
	</div>
</section>
