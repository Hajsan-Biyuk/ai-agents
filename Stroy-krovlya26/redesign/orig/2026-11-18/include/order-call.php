<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?php require_once $_SERVER['DOCUMENT_ROOT'].'/include/sk_telegram_leads.php'; sk_telegram_leads_boot(); ?>
<?php require_once $_SERVER['DOCUMENT_ROOT'].'/include/sk_recaptcha.php'; sk_recaptcha_boot(); sk_recaptcha_print_assets(); ?>
<div class="Forms Popup">
	<div class="Title">Заказать расчет</div>
	<div class="PopupBlock">
<?$APPLICATION->IncludeComponent(
								"bitrix:iblock.element.add.form", 
								"order", 
								array(
									"COMPONENT_TEMPLATE" => "order",
									"CUSTOM_TITLE_DATE_ACTIVE_FROM" => "",
									"CUSTOM_TITLE_DATE_ACTIVE_TO" => "",
									"CUSTOM_TITLE_DETAIL_PICTURE" => "",
									"CUSTOM_TITLE_DETAIL_TEXT" => "",
									"CUSTOM_TITLE_IBLOCK_SECTION" => "",
									"CUSTOM_TITLE_NAME" => "Имя",
									"CUSTOM_TITLE_PREVIEW_PICTURE" => "",
									"CUSTOM_TITLE_PREVIEW_TEXT" => "",
									"CUSTOM_TITLE_TAGS" => "",
									"DEFAULT_INPUT_SIZE" => "30",
									"DETAIL_TEXT_USE_HTML_EDITOR" => "N",
									"ELEMENT_ASSOC" => "CREATED_BY",
									"GROUPS" => array(
										0 => "1",
										1 => "2",
									),
									"IBLOCK_ID" => "15",
									"IBLOCK_TYPE" => "forms",
									"LEVEL_LAST" => "Y",
									"LIST_URL" => "",
									"AJAX_MODE" => "Y",
									"AJAX_OPTION_SHADOW" => "N",
									"AJAX_OPTION_JUMP" => "N",
									"AJAX_OPTION_STYLE" => "Y",
									"AJAX_OPTION_HISTORY" => "N",
									"USER_CONSENT" => "Y",
									"USER_CONSENT_ID" => "1",
									"USER_CONSENT_IS_CHECKED" => "Y",
									"USER_CONSENT_IS_LOADED" => "N",
									"MAX_FILE_SIZE" => "0",
									"MAX_LEVELS" => "100000",
									"MAX_USER_ENTRIES" => "100000",
									"PREVIEW_TEXT_USE_HTML_EDITOR" => "N",
									"PROPERTY_CODES" => array(
										0 => "45",
										1 => "46",
										2 => "47",
										3 => "NAME",
									),
									"PROPERTY_CODES_REQUIRED" => array(
										0 => "47",
										1 => "NAME",
									),
									"RESIZE_IMAGES" => "N",
									"SEF_MODE" => "N",
									"STATUS" => "ANY",
									"STATUS_NEW" => "N",
									"USER_MESSAGE_EDIT" => "",
									"USER_MESSAGE_ADD" => "Спасибо, ваша заявка принята к рассмотрению.",
									"USE_CAPTCHA" => "Y"
								),
								false
							);?>
		</div>
	</div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>