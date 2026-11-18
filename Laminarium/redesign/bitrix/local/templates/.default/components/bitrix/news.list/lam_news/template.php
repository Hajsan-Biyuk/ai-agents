<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
if (empty($arResult['ITEMS'])) return;
?>
<section class="lam-section lam-news">
	<div class="lam-container">
		<div class="lam-head lam-head--row">
			<h2 class="lam-title"><?=htmlspecialcharsbx($arParams['TITLE_BLOCK'] ?: 'Новости компании')?></h2>
			<a class="lam-pill" href="<?=SITE_DIR . $arParams['ALL_URL']?>">Все новости</a>
		</div>
		<ul class="lam-news__grid">
			<?php foreach ($arResult['ITEMS'] as $arItem):
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
				$lamPicSrc = !empty($arItem['PREVIEW_PICTURE']) ? $arItem['PREVIEW_PICTURE'] : (!empty($arItem['DETAIL_PICTURE']) ? $arItem['DETAIL_PICTURE'] : null);
				$lamPic = $lamPicSrc ? CFile::ResizeImageGet($lamPicSrc, array('width' => 520, 'height' => 520), BX_RESIZE_IMAGE_EXACT, true) : null;
				$lamTs = MakeTimeStamp($arItem['ACTIVE_FROM'] ?: $arItem['DATE_CREATE']);
			?>
				<li id="<?=$this->GetEditAreaId($arItem['ID'])?>">
					<article class="lam-news-card">
						<?php if ($lamPic): ?>
							<a class="lam-news-card__image" href="<?=$arItem['DETAIL_PAGE_URL']?>" tabindex="-1" aria-hidden="true"><img src="<?=$lamPic['src']?>" alt="" width="520" height="520" loading="lazy"></a>
						<?php endif; ?>
						<div class="lam-news-card__body">
							<?php if ($arItem['DISPLAY_ACTIVE_FROM']): ?>
								<time class="lam-news-card__date" datetime="<?=date('Y-m-d', $lamTs)?>"><?=$arItem['DISPLAY_ACTIVE_FROM']?></time>
							<?php endif; ?>
							<a class="lam-news-card__title" href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=$arItem['NAME']?></a>
							<?php if ($arItem['PREVIEW_TEXT']): ?>
								<p class="lam-news-card__text"><?=strip_tags($arItem['PREVIEW_TEXT'])?></p>
							<?php endif; ?>
						</div>
					</article>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
