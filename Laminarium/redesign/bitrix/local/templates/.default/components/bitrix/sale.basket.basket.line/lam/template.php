<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponentTemplate $this */
$cartId = 'bx_basket' . $this->randString();
$arParams['cartId'] = $cartId;
?>
<div id="<?=$cartId?>" class="lam-header__tools"><?php require __DIR__ . '/ajax_template.php'; ?></div>
<script>
(function () {
	var cart = new LamSmallCart({
		cartId: '<?=$cartId?>',
		siteId: '<?=SITE_ID?>',
		ajaxPath: '<?=CUtil::JSEscape($componentPath)?>/ajax.php',
		templateName: '<?=CUtil::JSEscape($templateName)?>',
		arParams: <?=CUtil::PhpToJSObject($arParams)?>
	});
	cart.activate();
})();
</script>
