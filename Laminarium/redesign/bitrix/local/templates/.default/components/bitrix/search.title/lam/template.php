<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/** Поиск в новой шапке. Живой поиск — скрипт и выдача шаблона Аспро «catalog». */
$this->setFrameMode(true);
$lamAsproTpl = '/bitrix/templates/aspro_mshop/components/bitrix/search.title/catalog';
$this->addExternalJs($lamAsproTpl . '/script.js');

$INPUT_ID = trim($arParams['~INPUT_ID']) ?: 'lam-header-q';
$CONTAINER_ID = trim($arParams['~CONTAINER_ID']) ?: 'lam-header-search';
?>
<form class="lam-header__search" id="<?=htmlspecialcharsbx($CONTAINER_ID)?>" action="<?=$arResult['FORM_ACTION']?>" method="get" role="search">
	<label class="lam-visually-hidden" for="<?=htmlspecialcharsbx($INPUT_ID)?>">Поиск по каталогу</label>
	<input class="lam-header__search-input" id="<?=htmlspecialcharsbx($INPUT_ID)?>" type="search" name="q" placeholder="Поиск" autocomplete="off" maxlength="50">
	<button class="lam-header__search-btn" type="submit" aria-label="Найти"><img src="/local/templates/.default/lam/img/ic-search.svg" alt="" width="20" height="20"></button>
</form>
<script>
BX.ready(function () {
	if (typeof JCTitleSearch2 === 'function') {
		new JCTitleSearch2({
			'AJAX_PAGE': '<?=CUtil::JSEscape(POST_FORM_ACTION_URI)?>',
			'CONTAINER_ID': '<?=CUtil::JSEscape($CONTAINER_ID)?>',
			'INPUT_ID': '<?=CUtil::JSEscape($INPUT_ID)?>',
			'INPUT_ID_TMP': '<?=CUtil::JSEscape($INPUT_ID)?>',
			'MIN_QUERY_LEN': 2
		});
	}
});
</script>
