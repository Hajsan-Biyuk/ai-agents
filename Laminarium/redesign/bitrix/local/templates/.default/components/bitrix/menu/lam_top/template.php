<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
if (empty($arResult)) return;
?>
<ul class="lam-header__menu">
	<?php foreach ($arResult as $arItem): if ($arItem['DEPTH_LEVEL'] > 1) continue; ?>
		<li<?=$arItem['SELECTED'] ? ' class="is-active"' : ''?>><a href="<?=$arItem['LINK']?>"<?=$arItem['SELECTED'] ? ' aria-current="page"' : ''?>><?=$arItem['TEXT']?></a></li>
	<?php endforeach; ?>
</ul>
