<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/** Содержимое .lam-header__tools: вход, сравнение, избранное, корзина. Перерисовывается по OnBasketChange. */
global $USER;
$lamImg = '/local/templates/.default/lam/img/';

$lamCompareCount = 0;
if (!empty($_SESSION['CATALOG_COMPARE_LIST']) && is_array($_SESSION['CATALOG_COMPARE_LIST'])) {
	foreach ($_SESSION['CATALOG_COMPARE_LIST'] as $lamCmp) {
		if (!empty($lamCmp['ITEMS']) && is_array($lamCmp['ITEMS'])) {
			$lamCompareCount += count($lamCmp['ITEMS']);
		}
	}
}
$lamDelayCount = (!empty($arResult['CATEGORIES']['DELAY']) && is_array($arResult['CATEGORIES']['DELAY'])) ? count($arResult['CATEGORIES']['DELAY']) : 0;
$lamNum = (int)$arResult['NUM_PRODUCTS'];
$lamAuth = is_object($USER) && $USER->IsAuthorized();
?>
<a class="lam-header__login" href="<?=$lamAuth ? $arParams['PATH_TO_PERSONAL'] : $arParams['PATH_TO_REGISTER']?>"><img src="<?=$lamImg?>ic-user.svg" alt="" width="24" height="24"><?=$lamAuth ? 'Кабинет' : 'Войти'?></a>
<a class="lam-header__tool lam-header__tool--compare" href="<?=SITE_DIR?>catalog/compare.php"><img src="<?=$lamImg?>ic-compare.svg" alt="" width="24" height="24"><span class="lam-header__tool-text">Сравнение<?=$lamCompareCount ? ', товаров: ' . $lamCompareCount : ''?></span><?php if ($lamCompareCount): ?><span class="lam-header__cart-count"><?=$lamCompareCount?></span><?php endif; ?></a>
<a class="lam-header__tool lam-header__tool--fav" href="<?=$arParams['PATH_TO_BASKET']?>#tab_DelDelCanBuy"><img src="<?=$lamImg?>ic-heart.svg" alt="" width="24" height="24"><span class="lam-header__tool-text">Избранное<?=$lamDelayCount ? ', товаров: ' . $lamDelayCount : ''?></span><?php if ($lamDelayCount): ?><span class="lam-header__cart-count"><?=$lamDelayCount?></span><?php endif; ?></a>
<a class="lam-header__cart" href="<?=$arParams['PATH_TO_BASKET']?>"><img src="<?=$lamImg?>ic-cart.svg" alt="" width="24" height="24"><span class="lam-visually-hidden">Корзина, товаров:</span><span class="lam-header__cart-count"><?=$lamNum?></span></a>
