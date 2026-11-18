<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

 IncludeTemplateLangFile(__FILE__);

 if(defined("ERROR_404") && ERROR_404 == "Y" && $APPLICATION->GetCurPage(true) !='/404.php') LocalRedirect('/404.php');

 ?>
 <?if ($GLOBALS["APPLICATION"]->GetCurPage() != "/"):?> 
					<?if ($GLOBALS["APPLICATION"]->GetCurPage() == "/catalog/" || $GLOBALS["APPLICATION"]->GetCurPage() == "/cart/" || $GLOBALS["APPLICATION"]->GetCurPage() == "/order.php" || $GLOBALS["APPLICATION"]->GetCurPage() == "/brends/" || $GLOBALS["APPLICATION"]->GetCurPage() == "/kontakty/" || $GLOBALS["APPLICATION"]->GetCurPage() == "/stati/" || $GLOBALS["APPLICATION"]->GetCurPage() == "/nashi-obekty/" || $GLOBALS["APPLICATION"]->GetCurPage() == "/aktsii/"):?> 
					<?else:?> 
							</div>
						</div>
					</div>
					<div class="Clear"></div>
					<?endif;?>
				<?if ($GLOBALS["APPLICATION"]->GetCurPage() != "/kontakty/"):?> 
				</div>
				<?endif;?>
			</div>
			<?endif;?>
		</div>
 </div>
<?if (SK_NEW): include __DIR__ . "/sk/footer.php"; else:?>
 <div class="Footer">
		<div class="Wrapper">
			<div class="FooterInfo Flex">
				<div class="FooterMenu">
					<div class="Items">
						<div class="Item">
							<div class="Item_Inner">
								<div class="Title">Компания</div>
								<?$APPLICATION->IncludeComponent(
									"bitrix:menu", 
									"link-menu", 
									array(
										"ROOT_MENU_TYPE" => "bottom",
										"MAX_LEVEL" => "1",
										"CHILD_MENU_TYPE" => "",
										"USE_EXT" => "Y",
										"MENU_CACHE_TYPE" => "A",
										"MENU_CACHE_TIME" => "86400",
										"MENU_CACHE_USE_GROUPS" => "N",
										"MENU_CACHE_GET_VARS" => array(
										),
										"COMPONENT_TEMPLATE" => "link-menu",
										"DELAY" => "N",
										"ALLOW_MULTI_SELECT" => "N"
									),
									false,
									array(
										"ACTIVE_COMPONENT" => "Y"
									)
								);?> 
							</div>
						</div>
						<div class="Item">
							<div class="Item_Inner">
								<div class="Title">Каталог</div>
								<?$APPLICATION->IncludeComponent(
									"bitrix:menu", 
									"link-menu", 
									array(
										"ROOT_MENU_TYPE" => "catalog-link",
										"MAX_LEVEL" => "1",
										"CHILD_MENU_TYPE" => "",
										"USE_EXT" => "Y",
										"MENU_CACHE_TYPE" => "A",
										"MENU_CACHE_TIME" => "86400",
										"MENU_CACHE_USE_GROUPS" => "N",
										"MENU_CACHE_GET_VARS" => array(
										),
										"COMPONENT_TEMPLATE" => "link-menu",
										"DELAY" => "N",
										"ALLOW_MULTI_SELECT" => "N"
									 ),
 $component, // Добавляем
 array("HIDE_ICONS" => "Y") // Добавляем
);?>
							</div>
						</div>
						<div class="Item">
							<div class="Item_Inner">
								<div class="Title">Производители</div>
								<?$APPLICATION->IncludeComponent(
									"bitrix:menu", 
									"brends-menu", 
									array(
										"ROOT_MENU_TYPE" => "brends",
										"MAX_LEVEL" => "1",
										"CHILD_MENU_TYPE" => "",
										"USE_EXT" => "Y",
										"MENU_CACHE_TYPE" => "A",
										"MENU_CACHE_TIME" => "86400",
										"MENU_CACHE_USE_GROUPS" => "N",
										"MENU_CACHE_GET_VARS" => array(
										),
										"COMPONENT_TEMPLATE" => "brends-menu",
										"DELAY" => "N",
										"ALLOW_MULTI_SELECT" => "N"
									 ),
 $component, // Добавляем
 array("HIDE_ICONS" => "Y") // Добавляем
);?>
							</div>
						</div>
						<div class="Clear"></div>
					</div>
				</div>
				<div class="Contacts ml-auto">
					<div class="Title">Контакты</div>
					<div class="Tel">
						<a href="tel:<?php include $_SERVER['DOCUMENT_ROOT']."/include/tel-open.php";?>" onclick="ym(38770565, 'reachGoal', 'Perehod_po_nomeru_telefona'); return true;">
								<?$APPLICATION->IncludeFile(
									SITE_DIR."include/tel.php",
									Array(),
									Array("MODE"=>"text")
								);?></a></div>
					<div class="Mail"><a href="mailto:<?php include $_SERVER['DOCUMENT_ROOT']."/include/mail.php";?>">
								<?$APPLICATION->IncludeFile(
									SITE_DIR."include/mail.php",
									Array(),
									Array("MODE"=>"text")
								);?></a></div>
					<div class="Adress"><?$APPLICATION->IncludeFile(
									SITE_DIR."include/adress.php",
									Array(),
									Array("MODE"=>"text")
								);?></div>
								<div class="Map"><a href="/kontakty/">Карта проезда</a></div>
					
									<div class="Social">
										<a href="https://www.instagram.com/stroy_krovlya26/" target="_blank" class="Ins"><span>Инстаграм</span></a>
										
									</div>
								
								<div class="Info">Обращаем ваше внимание на то, что данный интернет-сайт, а также вся информация о товарах и ценах, предоставленная на нём, носит исключительно информационный характер и ни при каких условиях не является публичной офертой, определяемой положениями Статьи 437 Гражданского кодекса Российской Федерации.</div>
								
				</div>
			</div>
			<div class="Copyright">
				<div class="Flex Baseline">
					<div class="Copy">
						© 2010—<?echo date("Y")?> «Строй-Кровля» - кровельные материалы.
					</div>
					<div class="Dev">
						<a href="http://artex-studio.ru">Разработка сайта - <span>«Артекс»</span></a>
					</div>
				</div>
			</div>
		</div>
 </div>
<?endif;?>
</div>
<div style="display: none;" id="OrderCall">
<div class="Forms Popup">
	<div class="Title">Заказать звонок</div>
	<div class="PopupBlock">
<?$APPLICATION->IncludeComponent(
								"bitrix:iblock.element.add.form", 
								"order-call", 
								array(
									"COMPONENT_TEMPLATE" => "order-call",
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
									"IBLOCK_ID" => "49",
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
										0 => "271",
										1 => "NAME",
									),
									"PROPERTY_CODES_REQUIRED" => array(
										0 => "271",
										1 => "NAME",
									),
									"RESIZE_IMAGES" => "N",
									"SEF_MODE" => "N",
									"STATUS" => "ANY",
									"STATUS_NEW" => "N",
									"USER_MESSAGE_EDIT" => "",
									"USER_MESSAGE_ADD" => "Спасибо, ваша заявка принята к рассмотрению.",
									"USE_CAPTCHA" => "N"
								),
								false
							);?>
		</div>
	</div>
</div>

<link href="<?=SITE_TEMPLATE_PATH?>/cookie-consent.css" rel="stylesheet" type="text/css">
<?php include $_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . "/cookie-consent.php"; ?>
<script src="<?=SITE_TEMPLATE_PATH?>/js/cookie-consent.js"></script>
<?if (SK_NEW):?><script src="<?=SK_PATH?>/js/sk.js?v=<?=SK_V?>"></script><?endif;?>

</body>
</html>