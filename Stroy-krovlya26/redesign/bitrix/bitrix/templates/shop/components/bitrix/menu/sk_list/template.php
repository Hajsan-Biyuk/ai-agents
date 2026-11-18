<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/** Простой список ссылок для новой шапки/подвала. SK_CLASS — БЭМ-блок (sk-menu, sk-footer). */
if (empty($arResult)) return;
$c = preg_replace('/[^a-z0-9_-]/i', '', isset($arParams['SK_CLASS']) ? $arParams['SK_CLASS'] : 'sk-menu');
?>
<ul class="<?= $c ?>__list<?= !empty($arParams['SK_LIST_MOD']) ? ' ' . $c . '__list--' . preg_replace('/[^a-z0-9_-]/i', '', $arParams['SK_LIST_MOD']) : '' ?>">
<?php foreach ($arResult as $item): if ($item['DEPTH_LEVEL'] > 1) continue; ?>
	<li class="<?= $c ?>__item"><a class="<?= $c ?>__link<?= $item['SELECTED'] ? ' is-active' : '' ?>" href="<?= htmlspecialcharsbx($item['LINK']) ?>"><?= $item['TEXT'] ?></a></li>
<?php endforeach; ?>
</ul>
