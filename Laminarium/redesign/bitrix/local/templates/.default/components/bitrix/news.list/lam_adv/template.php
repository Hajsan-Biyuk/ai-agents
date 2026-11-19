<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * Слайды главного баннера (инфоблок lam_hero_slides).
 * shortcut: папка называется lam_adv, т.к. бридж не создаёт папки (lam_adv была пустой) — переименовать в lam_hero_slider при доступе к хостингу.
 * Нет слайдов — выводится прежний статичный баннер из областей inc_hero.php / inc_hero_photo.php.
 */
global $APPLICATION;
$lamDir = '/local/templates/.default/lam';
?>
<div class="lam-hero__slides">
<?php if (empty($arResult['ITEMS'])): ?>
	<div class="lam-hero__slide is-active">
		<div class="lam-hero__art" aria-hidden="true"><?php $APPLICATION->IncludeFile($lamDir . '/inc_hero_photo.php', array(), array('MODE' => 'html', 'NAME' => 'фото баннера')); ?></div>
		<div class="lam-hero__content"><?php $APPLICATION->IncludeFile($lamDir . '/inc_hero.php', array(), array('MODE' => 'html', 'NAME' => 'баннер')); ?></div>
	</div>
<?php endif; ?>
<?php foreach ($arResult['ITEMS'] as $i => $arItem):
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => 'Удалить слайд?'));
	$p = $arItem['PROPERTIES'];
	$label = trim((string)$p['LABEL']['VALUE']);
	$color = $p['LABEL_COLOR']['VALUE_XML_ID'] === 'yellow' ? 'yellow' : 'cyan';
	$btnText = trim((string)$p['BUTTON_TEXT']['VALUE']);
	$btnLink = trim((string)$p['BUTTON_LINK']['VALUE']) ?: '/catalog/';
	$pic = !empty($arItem['PREVIEW_PICTURE']) ? CFile::ResizeImageGet($arItem['PREVIEW_PICTURE'], array('width' => 2264, 'height' => 1030), BX_RESIZE_IMAGE_PROPORTIONAL, true) : null;
	$text = $arItem['PREVIEW_TEXT_TYPE'] === 'html' ? $arItem['PREVIEW_TEXT'] : nl2br($arItem['PREVIEW_TEXT']);
?>
	<div class="lam-hero__slide<?=$i === 0 ? ' is-active' : ''?>" id="<?=$this->GetEditAreaId($arItem['ID'])?>"<?=$i === 0 ? '' : ' aria-hidden="true" inert'?>>
		<div class="lam-hero__art" aria-hidden="true">
			<?php if ($pic): ?><img class="lam-hero__photo" src="<?=$pic['src']?>" alt="" width="1122" height="515"<?=$i === 0 ? ' fetchpriority="high"' : ' loading="lazy"'?>><?php endif; ?>
		</div>
		<div class="lam-hero__content">
			<?php if ($label !== ''): ?><span class="lam-label lam-label--<?=$color?>"><?=htmlspecialcharsbx($label)?></span><?php endif; ?>
			<p class="lam-hero__title"><?=$arItem['NAME']?></p>
			<?php if (trim(strip_tags($text)) !== ''): ?><p class="lam-hero__text"><?=$text?></p><?php endif; ?>
			<?php if ($btnText !== ''): ?><a class="lam-btn lam-hero__btn" href="<?=htmlspecialcharsbx($btnLink)?>"><?=htmlspecialcharsbx($btnText)?></a><?php endif; ?>
		</div>
	</div>
<?php endforeach; ?>
</div>
<?php if (count($arResult['ITEMS']) > 1): ?>
<div class="lam-hero__dots" role="group" aria-label="Слайды баннера">
	<?php foreach ($arResult['ITEMS'] as $i => $arItem): ?>
	<button class="lam-hero__dot<?=$i === 0 ? ' is-active' : ''?>" type="button" aria-label="Слайд <?=$i + 1?>"<?=$i === 0 ? ' aria-current="true"' : ''?>></button>
	<?php endforeach; ?>
</div>
<?php endif; ?>
