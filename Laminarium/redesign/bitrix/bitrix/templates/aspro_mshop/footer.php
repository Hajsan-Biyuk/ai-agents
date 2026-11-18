<?php
if (!empty($GLOBALS['LAM_THANKS_PAGE']) && isset($GLOBALS['LAM_THANKS_OB'])) {
	$APPLICATION->SetTitle('Спасибо');
	$APPLICATION->SetPageProperty('title', 'Спасибо за заявку');
	while (ob_get_level() > (int)$GLOBALS['LAM_THANKS_OB']) {
		ob_end_clean();
	}
	$lamThanksFile = $_SERVER['DOCUMENT_ROOT'] . '/include/lam_thanks.php';
	if (is_file($lamThanksFile)) {
		include $lamThanksFile;
	}
}
?>
<?if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") die();?>							<?IncludeTemplateLangFile(__FILE__);?>
							<?if(CSite::InDir(SITE_DIR.'help/') || CSite::InDir(SITE_DIR.'company/') || CSite::InDir(SITE_DIR.'info/')):?>
								</div>
							<?endif;?>
			<?if(!$isFrontPage && !$isContactsPage):?>
							</div>
						</div>
					</section>
				</div>
			<?endif;?>
		</div><?// <div class="wrapper">?>
		
<?if(function_exists("lam_preview") && lam_preview() && is_file($_SERVER["DOCUMENT_ROOT"]."/local/templates/.default/lam/footer.php")):?><?include $_SERVER["DOCUMENT_ROOT"]."/local/templates/.default/lam/footer.php";?><?else:?>
		<footer id="footer" <?=($isFrontPage ? 'class="main"' : '')?>>
			<div class="footer_inner">
				<div class="wrapper_inner">
					<div class="footer_top">
						<div class="wrap_md">
							<div class="iblock sblock">
								<?$APPLICATION->IncludeComponent(
									"bitrix:subscribe.form", 
									"mshop", 
									array(
										"AJAX_MODE" => "N",
										"SHOW_HIDDEN" => "N",
										"ALLOW_ANONYMOUS" => "Y",
										"SHOW_AUTH_LINKS" => "N",
										"CACHE_TYPE" => "A",
										"CACHE_TIME" => "86400",
										"CACHE_NOTES" => "",
										"SET_TITLE" => "N",
										"AJAX_OPTION_JUMP" => "N",
										"AJAX_OPTION_STYLE" => "Y",
										"AJAX_OPTION_HISTORY" => "N",
										"AJAX_OPTION_ADDITIONAL" => "",
										"LK" => "Y",
										"COMPONENT_TEMPLATE" => "mshop",
										"USE_PERSONALIZATION" => "Y",
										"PAGE" => SITE_DIR."personal/subscribe/",
									),
									false
								);?>
							</div>
							<div class="iblock phones">
								<div class="wrap_md">
									<div class="empty_block iblock"></div>
									<div class="phone_block iblock">
										<span class="phone_wrap">
											<span class="icons"></span>
											<span><?$APPLICATION->IncludeFile(SITE_DIR."include/phone.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("PHONE")));?></span>
										</span>
										<span class="order_wrap_btn">
											<span class="callback_btn"><?=GetMessage('CALLBACK')?></span>
										</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="footer_bottom">
						<div class="wrap_md">
							<div class="iblock menu_block">
								<div class="wrap_md">
									<div class="iblock copy_block">
										<div class="copyright">
											<?$APPLICATION->IncludeFile(SITE_DIR."include/copyright.php", Array(), Array("MODE" => "html", "NAME"  => GetMessage("COPYRIGHT")));?>
							

	</div>
										
									</div>
									<div class="iblock all_menu_block">
										<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom_submenu_top", array(
											"ROOT_MENU_TYPE" => "bottom",
											"MENU_CACHE_TYPE" => "Y",
											"MENU_CACHE_TIME" => "86400",
											"MENU_CACHE_USE_GROUPS" => "N",
											"MENU_CACHE_GET_VARS" => array(),
											"MAX_LEVEL" => "1",
											"USE_EXT" => "N",
											"DELAY" => "N",
											"ALLOW_MULTI_SELECT" => "N"
											),false
										);?>
										<div class="wrap_md">
											<div class="iblock submenu_block">
												<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom_submenu", array(
													"ROOT_MENU_TYPE" => "bottom_company",
													"MENU_CACHE_TYPE" => "Y",
													"MENU_CACHE_TIME" => "86400",
													"MENU_CACHE_USE_GROUPS" => "N",
													"MENU_CACHE_GET_VARS" => array(),
													"MAX_LEVEL" => "1",
													"USE_EXT" => "N",
													"DELAY" => "N",
													"ALLOW_MULTI_SELECT" => "N"
													),false
												);?>
											</div>
											<div class="iblock submenu_block">
												<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom_submenu", array(
													"ROOT_MENU_TYPE" => "bottom_info",
													"MENU_CACHE_TYPE" => "Y",
													"MENU_CACHE_TIME" => "86400",
													"MENU_CACHE_USE_GROUPS" => "N",
													"MENU_CACHE_GET_VARS" => array(),
													"MAX_LEVEL" => "1",
													"USE_EXT" => "N",
													"DELAY" => "N",
													"ALLOW_MULTI_SELECT" => "N"
													),false
												);?>
											</div>
											<div class="iblock submenu_block">
												<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom_submenu", array(
													"ROOT_MENU_TYPE" => "bottom_help",
													"MENU_CACHE_TYPE" => "Y",
													"MENU_CACHE_TIME" => "86400",
													"MENU_CACHE_USE_GROUPS" => "N",
													"MENU_CACHE_GET_VARS" => array(),
													"MAX_LEVEL" => "1",
													"USE_EXT" => "N",
													"DELAY" => "N",
													"ALLOW_MULTI_SELECT" => "N"
													),false
												);?>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="iblock social_block">
								<div class="wrap_md">
									<div class="empty_block iblock"></div>
									<div class="social_wrapper iblock">
										<div class="social">
											<?include($_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include/social.info.mshop.default.php');?>
											<br><span  class="pay_system_icons">
											<?$APPLICATION->IncludeFile(SITE_DIR."include/pay_system_icons.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("PHONE")));?>
										</span><br>
										<?/*iframe src="https://yandex.ru/sprav/widget/rating-badge/39526721360" width="150" height="50" style="border: none;"></iframe*/?>
										</div>
									</div>
								</div>
								<div id="bx-composite-banner"></div>
								
							</div>
						</div>
					</div>
					<?$APPLICATION->IncludeFile(SITE_DIR."include/bottom_include1.php", Array(), Array("MODE" => "text", "NAME" => GetMessage("ARBITRARY_1"))); ?>
					<?$APPLICATION->IncludeFile(SITE_DIR."include/bottom_include2.php", Array(), Array("MODE" => "text", "NAME" => GetMessage("ARBITRARY_2"))); ?>
				</div>					
			</div>	
		</footer><?endif;?>		
		<?
		$page = $APPLICATION->GetCurPage(false);
		if($page!="/info/brands/") {
			if(!CSite::inDir(SITE_DIR."index.php")){
				if(strlen($APPLICATION->GetPageProperty('title')) > 1){
					$title = $APPLICATION->GetPageProperty('title');
				} 
				else{
					$title = $APPLICATION->GetTitle();
				}
				$APPLICATION->SetPageProperty("title", $title.(COption::GetOptionString('aspro.mshop', 'HIDE_SITE_NAME_IN_TITLE', '', SITE_ID) == 'Y' ? '' : ' - '.$arSite['SITE_NAME']) );
			} else {
				if(strlen($APPLICATION->GetPageProperty('title')) > 1){
					$title =  $APPLICATION->GetPageProperty('title');
				}
				else{
					$title =  $APPLICATION->GetTitle();
				}
				if(!empty($title)){
					$APPLICATION->SetPageProperty("title", $title);
				}
				else{
					$APPLICATION->SetPageProperty("title", $arSite['SITE_NAME']);
				}
			}
		}?>
		<div id="content_new"></div>
			<?CMShop::footerAction();?>
                            <?//$APPLICATION->IncludeComponent( "abricos:antisovetnik", "", array(), false);?>
<div id='web_copyright'></div>

<div class="cookies-modal" style="display: none;">
	<span>Мы используем cookies для быстрой и удобной работы сайта. Продолжая пользоваться сайтом, вы принимаете <a class="cookie-policy-link" href="/privacy-policy/">условия обработки персональных данных</a></span>
	<div class="cookies-btns">
		<div class="cookies-btn yes-cookie">Согласен</div>
		<div class="cookies-btn no-cookie">Не согласен</div>
	</div>
	<button type="button" class="cookie-policy-button">✕</button>
</div>

<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
   m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

   ym(35747175, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true,
        webvisor:true,
        trackHash:true,
        ecommerce:"dataLayer"
   });
</script>
<!-- /Yandex.Metrika counter --><script src="//code.jivo.ru/widget/hN08W83tOf" async></script>
<div id="notificationModal" style="display:none; position: fixed; bottom: 20px; right: 20px; width: 300px; background: #e69c00; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); z-index: 10000;">
    <p style="font-size: 16px; margin: 0;"><b>Уважаемые друзья!</b><br> С 31.12.24 по 03.01.25 магазины не работают. <br>С 4.01.25 магазины работают в обычном режиме.</p>
    <button id="closeModal" style="position: absolute; top: 10px; right: 10px; background: none; border: none; font-size: 20px; color: #fff; cursor: pointer;">&times;</button>
</div>
<!-- <script src="<?=SITE_TEMPLATE_PATH?>/js/modal.js"></script> -->
<script src="<?=SITE_TEMPLATE_PATH?>/js/thanks-redirect.js?v=1"></script>
</body>
</html>