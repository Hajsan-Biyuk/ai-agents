<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
// Редизайн 2026: попап «Заказать товар» (инфоблок 50). В эпилоге — чтобы форма обрабатывалась и при кеше карточки.
?>
<div class="sk-scope sk-modal" id="sk-order-product" style="display:none">
	<?php $APPLICATION->IncludeComponent('bitrix:iblock.element.add.form', 'sk_order_product', array(
		'IBLOCK_TYPE' => 'forms', 'IBLOCK_ID' => '50', 'GROUPS' => array('1', '2'), 'STATUS' => 'ANY', 'STATUS_NEW' => 'N',
		'ELEMENT_ASSOC' => 'CREATED_BY', 'LEVEL_LAST' => 'Y', 'LIST_URL' => '', 'SEF_MODE' => 'N', 'USE_CAPTCHA' => 'N',
		'PROPERTY_CODES' => array('273', '274', 'NAME'), 'PROPERTY_CODES_REQUIRED' => array('273', '274', 'NAME'),
		'MAX_USER_ENTRIES' => '100000', 'MAX_LEVELS' => '100000', 'MAX_FILE_SIZE' => '0', 'DEFAULT_INPUT_SIZE' => '30', 'RESIZE_IMAGES' => 'N',
		'PREVIEW_TEXT_USE_HTML_EDITOR' => 'N', 'DETAIL_TEXT_USE_HTML_EDITOR' => 'N',
		'USER_MESSAGE_ADD' => 'Спасибо, ваша заявка принята.', 'USER_MESSAGE_EDIT' => '',
	), false, array('HIDE_ICONS' => 'Y')); ?>
</div>
