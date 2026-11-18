<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
// LAM: предпросмотр редизайна (?lam_preview=1). Без флага шаблон работает как раньше.
$lamFlagFile = $_SERVER["DOCUMENT_ROOT"]."/local/templates/.default/lam/flag.php";
if(is_file($lamFlagFile)){ include_once $lamFlagFile; }
$lamPreview = function_exists("lam_preview") && lam_preview() && is_file($_SERVER["DOCUMENT_ROOT"]."/local/templates/.default/lam/header.php");
if($lamPreview && class_exists("\\Bitrix\\Main\\Composite\\Engine") && method_exists("\\Bitrix\\Main\\Composite\\Engine", "setEnable")){ \Bitrix\Main\Composite\Engine::setEnable(false); }
if($GET["debug"] == "y"){
	error_reporting(E_ERROR | E_PARSE);
}
//IncludeTemplateLangFile("/bitrix/templates/aspro_mshop/header.php");
IncludeTemplateLangFile(__FILE__);
//print GetMessage("COUNTDOWN_YEAR0");
//IncludeTemplateLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/templates/aspro_mshop/lang/ru/header.php");
global $APPLICATION, $HIDE_CATALOG_MULTILEVEL, $TEMPLATE_OPTIONS, $arSite;
$arSite = CSite::GetByID(SITE_ID)->Fetch();
$htmlClass = ($_REQUEST && isset($_REQUEST['print']) ? 'print' : false);

$page = $APPLICATION->GetCurPage(false);
$lamThanksPath = parse_url((string)($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH);
$lamThanksPath = rtrim((string)$lamThanksPath, '/') ?: '/';
if ($lamThanksPath === '/thanks') {
	CHTTP::SetStatus('200 OK');
	$APPLICATION->SetTitle('Спасибо');
	$APPLICATION->SetPageProperty('title', 'Спасибо за заявку');
	$APPLICATION->SetPageProperty('robots', 'noindex, nofollow');
	$GLOBALS['LAM_THANKS_PAGE'] = true;
}
?>
<!DOCTYPE html>
<html xml:lang="<?=LANGUAGE_ID?>" lang="<?=LANGUAGE_ID?>" xmlns="http://www.w3.org/1999/xhtml" <?=($htmlClass ? 'class="'.$htmlClass.'"' : '')?>>
<head>
	<title><?$APPLICATION->ShowTitle()?></title>
    <?//$APPLICATION->IncludeComponent( "abricos:antisovetnik", "", array(), false);?>
		
	<?$APPLICATION->ShowMeta("viewport");?>
	<?$APPLICATION->ShowMeta("HandheldFriendly");?>
	<?$APPLICATION->ShowMeta("apple-mobile-web-app-capable", "yes");?>
	<?$APPLICATION->ShowMeta("apple-mobile-web-app-status-bar-style");?>
	<?$APPLICATION->ShowMeta("SKYPE_TOOLBAR");?>
	<?$APPLICATION->ShowHead();?>

    <?if(!CSite::InDir('/catalog/')) {
		$APPLICATION->AddHeadString('<link rel="canonical" href="' . $APPLICATION->GetCurDir() . '" />', true);
    } else if($page=="/catalog/") {
		$APPLICATION->AddHeadString('<link rel="canonical" href="' . $APPLICATION->GetCurDir() . '" />', true);
	} else if($page=="/catalog/compare.php") {
		$APPLICATION->AddHeadString('<link rel="canonical" href="' . $APPLICATION->GetCurDir() . 'compare.php" />', true);
	}?>
	<?$APPLICATION->AddHeadString('<script>BX.message('.CUtil::PhpToJSObject( $MESS, false ).')</script>', true);?>
	<?if(CModule::IncludeModule("aspro.mshop")) {CMShop::Start(SITE_ID);}?>
	<?if(class_exists('\Aspro\Functions\CAsproMshopReCaptcha') && \Aspro\Functions\CAsproMshopReCaptcha::getVersion() == '3'):?>
	<style>.captcha-row{display:none!important}</style>
	<script id="recaptchaApiLoader" src="https://www.google.com/recaptcha/api.js?hl=ru&onload=onLoadRenderRecaptcha&render=<?=htmlspecialcharsbx(\Aspro\Functions\CAsproMshopReCaptcha::getPublicKey())?>" async></script>
	<style>.grecaptcha-badge{visibility:visible!important;opacity:1!important;z-index:2147483646!important}</style>
	<script>
	document.addEventListener('click', function(e){
		var btn = e.target && e.target.closest ? e.target.closest('form [name="web_form_submit"]') : null;
		if(!btn || !window.asproRecaptcha || String(window.asproRecaptcha.ver) !== '3') return;
		var form = btn.form;
		if(!form || !form.querySelector('.g-recaptcha, [name="captcha_sid"]')) return;
		if(form.getAttribute('data-rc3-ready') === '1'){
			form.removeAttribute('data-rc3-ready');
			return;
		}
		if(!window.grecaptcha) return;
		e.preventDefault();
		e.stopPropagation();
		grecaptcha.ready(function(){
			grecaptcha.execute(window.asproRecaptcha.key, {action: 'feedback'}).then(function(token){
				var fields = form.querySelectorAll('[name="g-recaptcha-response"]');
				if(!fields.length){
					var ta = document.createElement('textarea');
					ta.name = 'g-recaptcha-response';
					ta.style.display = 'none';
					form.appendChild(ta);
					fields = [ta];
				}
				for(var i = 0; i < fields.length; i++) fields[i].value = token;
				form.setAttribute('data-rc3-ready', '1');
				btn.click();
			});
		});
	}, true);
	</script>
	<?endif;?>
	<!--[if gte IE 9]><style type="text/css">.basket_button, .button30, .icon {filter: none;}</style><![endif]-->
	<?$bIndexBot = (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && strpos($_SERVER['HTTP_USER_AGENT'], 'Lighthouse') !== false);?>
<?if(!$bIndexBot):?><link href='<?=CMain::IsHTTPS() ? 'https' : 'http'?>://fonts.googleapis.com/css?family=Ubuntu:400,500,700,400italic&subset=latin,cyrillic' rel='stylesheet'><?endif;?>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "Organization",
      "@id": "https://www.laminarium.com/#organization",
      "name": "Ламинариум",
      "url": "https://www.laminarium.com/",
      "logo": {
        "@type": "ImageObject",
        "url": "https://www.laminarium.com/upload/aspro.mshop/42e/42e4e74b4bcf5e8c607a72bc2c789b65.svg"
      },
      "image": "https://www.laminarium.com/upload/aspro.mshop/42e/42e4e74b4bcf5e8c607a72bc2c789b65.svg",
      "email": "mail@laminarium.com",
      "telephone": "+7 (918) 022-79-99"
    },
    {
      "@type": "WebSite",
      "@id": "https://www.laminarium.com/#website",
      "url": "https://www.laminarium.com/",
      "name": "Ламинариум",
      "publisher": {
        "@id": "https://www.laminarium.com/#organization"
      }
    }
  ]
}
</script>
</head>
	<body class="<?=($bIndexBot ? "wbot" : "");?>" id="main">
		<div id="panel"><?$APPLICATION->ShowPanel();?></div>
		<?if(!CModule::IncludeModule("aspro.mshop")){?><center><?$APPLICATION->IncludeFile(SITE_DIR."include/error_include_module.php");?></center></body></html><?die();?><?}?>
		<?$APPLICATION->IncludeComponent("aspro:theme.mshop", ".default", array("COMPONENT_TEMPLATE" => ".default"), false);?>
		<?CMShop::SetJSOptions();?>
				<?if($TEMPLATE_OPTIONS["MOBILE_FILTER_COMPACT"]["CURRENT_VALUE"] === "Y"):?>
				    <div id="mobilefilter" class="visible-xs visible-sm scrollbar-filter"></div>
				<?endif;?>
		<?$isFrontPage = CSite::InDir(SITE_DIR.'index.php');?>
		<?$isContactsPage = CSite::InDir(SITE_DIR.'contacts/');?>
		<?$isBasketPage=CSite::InDir(SITE_DIR.'basket/');?>
		<div class="wrapper <?=CMShop::getCurrentThemeClasses();?> <?=($TEMPLATE_OPTIONS["HEAD"]["CURRENT_MENU_COLOR"] != "none" ? "has_menu" : "");?> h_color_<?=$TEMPLATE_OPTIONS["HEAD"]["CURRENT_HEAD_COLOR"];?> m_color_<?=$TEMPLATE_OPTIONS["HEAD"]["CURRENT_MENU_COLOR"];?> <?=($isFrontPage ? "front_page" : "");?> basket_<?=strToLower($TEMPLATE_OPTIONS["BASKET"]["CURRENT_VALUE"]);?> head_<?=strToLower($TEMPLATE_OPTIONS["HEAD"]["CURRENT_VALUE"]);?> banner_<?=strToLower($TEMPLATE_OPTIONS["BANNER_WIDTH"]["CURRENT_VALUE"]);?>">
<?if($lamPreview):?><?include $_SERVER["DOCUMENT_ROOT"]."/local/templates/.default/lam/header.php";?><?else:?>
			<div class="header_wrap <?=strtolower($TEMPLATE_OPTIONS["HEAD_COLOR"]["CURRENT_VALUE"])?> fvv_header_wrap">
				<? if(true || GetIP() == '146.158.101.13'){ ?>
				<? include __DIR__ . '/header_new.php'; ?>
				<? }else{ ?>
				<div class="top-h-row">
					<div class="wrapper_inner">
						<div class="content_menu">
							<?$APPLICATION->IncludeComponent("bitrix:menu", "top_content_row", array(
								"ROOT_MENU_TYPE" => $TEMPLATE_OPTIONS["HEAD"]["CURRENT_MENU"],
								"MENU_CACHE_TYPE" => "Y",
								"MENU_CACHE_TIME" => "86400",
								"MENU_CACHE_USE_GROUPS" => "N",
								"MENU_CACHE_GET_VARS" => array(),
								"MAX_LEVEL" => "1",
								"CHILD_MENU_TYPE" => "left",
								"USE_EXT" => "N",
								"DELAY" => "N",
								"ALLOW_MULTI_SELECT" => "N",
								),false
							);?>
						</div>
						<div class="phones">
							<span class="phone_wrap">
								<span class="icons"></span>
								<span class="phone_text">
									<?$APPLICATION->IncludeFile(SITE_DIR."include/phone.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("PHONE")));?>
								</span>
							</span>
							<span class="order_wrap_btn">
								<?if( \Bitrix\Main\Config\Option::get("aspro.mshop", "SHOW_CALLBACK", "Y") != "N"):?>
                                    <span class="callback_btn"><?=GetMessage("CALLBACK")?></span>
                                <?endif;?>
							</span>
						</div>
						<div class="h-user-block" id="personal_block">
							<div class="form_mobile_block"><div class="search_middle_block"><?include($_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include/search.title.catalog3.php');?></div></div>
							<?$APPLICATION->IncludeComponent("bitrix:system.auth.form", "top", array(
								"REGISTER_URL" => SITE_DIR."auth/registration/",
								"FORGOT_PASSWORD_URL" => SITE_DIR."auth/forgot-password/",
								"PROFILE_URL" => SITE_DIR."personal/",
								"SHOW_ERRORS" => "Y"
								)
							);?>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
				<header id="header">
					<div class="wrapper_inner">
						<div class="notify">В связи с изменениями курса рубля актуальную стоимость товаров необходимо уточнять у менеджера по телефону или в магазине.</div>
						<table class="middle-h-row"><tr>
							<td class="logo_wrapp">
								<div class="logo nofill_<?=strtolower(\Bitrix\Main\Config\Option::get('aspro.mshop', 'NO_LOGO_BG', 'N'));?>">
									<?CMShop::ShowLogo();?>
								</div>
							</td>
							<td  class="center_block">
								<div class="main-nav">
									<?include($_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include/menu.top_general_multilevel.php');?>
								</div>

								<div class="middle_phone">
									<div class="phones">
										<span class="phone_wrap">
											<span class="icons"></span>
											<span class="phone_text">
												<?$APPLICATION->IncludeFile(SITE_DIR."include/phone.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("PHONE")));?>
											</span>
										</span>
                                        <div class="header-address">
                                            <?$APPLICATION->IncludeFile(SITE_DIR."include/header-address.php", Array(), Array("MODE" => "html"));?>
                                        </div>
										<span class="order_wrap_btn">
											<?if( \Bitrix\Main\Config\Option::get("aspro.mshop", "SHOW_CALLBACK", "Y") != "N"):?>
											<span class="callback_btn"><?=GetMessage("CALLBACK")?></span>
											<?endif;?>
											<span class="head-socials-icons">
												<div class="socials-icon wasapp"><a target="_blank"  rel="noopener noreferrer nofollow" href="https://wa.me/792984283743"></a></div>
												<div class="socials-icon telega"><a rel="noopener noreferrer nofollow" target="_blank" href="http://t.me/792984283743"></a></div>
											</span>
										</span>
									</div>
								</div>
								<div class="search">
									<?include($_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include/search.title.catalog.php');?>
								</div>
								<div class="fv-sale-btn-wrap">
									<?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/fv-sale-btn.php"], false, ['HIDE_ICONS' => 'N']);?>
								</div>
							</td>
							<td class="basket_wrapp custom_basket_class <?=CMShop::getCurrentPageClass()?>">
								<div class="wrapp_all_icons">
										<div class="header-compare-block icon_block iblock" id="compare_line">
											<?include($_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include/catalog.compare.list.compare_top.php');?>
										</div>
										<div class="header-cart" id="basket_line">
											<?Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("header-cart");?>
											<?//CSaleBasket::UpdateBasketPrices(CSaleBasket::GetBasketUserID(), SITE_ID);?>
											<?if($TEMPLATE_OPTIONS["BASKET"]["CURRENT_VALUE"] === "FLY" && CMshop::getShowBasket()):?>
												<script type="text/javascript">
												$(document).ready(function(){
													$.ajax({
														url: arMShopOptions["SITE_DIR"] + "ajax/basket_fly.php",
														type: "post",
														success: function(html){
															$("#basket_line").append(html);
														}
													});
												});
												</script>
											<?else:?>
												<?if(CMshop::getShowBasket()):?><?$APPLICATION->IncludeFile(SITE_DIR."include/basket_top.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("BASKET_TOP")));?><?endif;?>
											<?endif;?>
											<?Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("header-cart", "");?>
										</div>
									</div>
									<div class="clearfix"></div>
							</td>
						</tr></table>
					</div>
					<div class="catalog_menu">
						<div class="wrapper_inner">
							<div class="wrapper_middle_menu">
								
									<?if(!$HIDE_CATALOG_MULTILEVEL):?>
										<?include($_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include/menu.top_catalog_multilevel.php');?>
									<?endif;?>
							</div>
						</div>
					</div>
				</header>
				<? } ?>
				<?if($TEMPLATE_OPTIONS["HEAD_FIXED_CHECK"]["CURRENT_VALUE"] == "HEAD_FIXED_1"):?>
					<div id="headerfixed" class="<?=$TEMPLATE_OPTIONS["HEAD"]["CURRENT_HEAD_COLOR"]?>">
						<?CMShop::ShowPageType("header_fixed");?>
					</div>
				<?endif;?>
				<?if($TEMPLATE_OPTIONS["HEAD_MOBILE_CHECK"]["CURRENT_VALUE"] == "Y"):?>
					<div id="headerfixed_mobile" class="<?=$TEMPLATE_OPTIONS["HEAD"]["CURRENT_HEAD_COLOR"]?>">
						<?CMShop::ShowPageType("header_fixed_mobile");?>
					</div>
				<?endif;?>
			</div>
<?endif;?>
			<?//var_dump($isFrontPage);?>
			<?if(!$isFrontPage):?>
				<div class="wrapper_inner">
					<section class="middle">
						<div class="container">
							<div id="navigation"><?$APPLICATION->IncludeComponent(
	"bitrix:breadcrumb", 
	"mshop", 
	array(
		"START_FROM" => "0",
		"PATH" => "",
		"SITE_ID" => "s1",
		"SHOW_SUBSECTIONS" => "N",
		"COMPONENT_TEMPLATE" => "mshop"
	),
	false
);?>
							</div><?$APPLICATION->ShowViewContent('section_bnr_content');?>
			<!--title_content-->
			<h1 id="pagetitle"><?=$APPLICATION->ShowTitle(false);?><?$APPLICATION->ShowViewContent('add_h1_aac');?></h1>
			<!--end-title_content-->
				<?if($isContactsPage):?>
						</div>
					</section>
				</div>
				<?else:?>
							<div id="content">
							<?if(
								(CSite::InDir(SITE_DIR.'help/') || CSite::InDir(SITE_DIR.'company/') || CSite::InDir(SITE_DIR.'info/'))
								&& !CSite::InDir(SITE_DIR.'company/partners/')
							):?>
								<?$APPLICATION->ShowViewContent('detail_filter');?>
								<div class="left_block">
									<?$APPLICATION->IncludeComponent("bitrix:menu", "left_menu", array(
										"CACHE_SELECTED_ITEMS" => "Y",
										"ROOT_MENU_TYPE" => "left",
										"MENU_CACHE_TYPE" => "A",
										"MENU_CACHE_TIME" => "3600000",
										"MENU_CACHE_USE_GROUPS" => "N",
										"MENU_CACHE_GET_VARS" => "",
										"MAX_LEVEL" => "1",
										"CHILD_MENU_TYPE" => "left",
										"USE_EXT" => "Y",
										"DELAY" => "N",
										"ALLOW_MULTI_SELECT" => "N" ),
										false, array( "ACTIVE_COMPONENT" => "Y" )
									);?>
								</div>
								<div class="right_block">
							<?endif;?>
				<?endif;?>
			<?endif;?>
								<?if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") $APPLICATION->RestartBuffer();?>
<?php if (!empty($GLOBALS['LAM_THANKS_PAGE'])) { $GLOBALS['LAM_THANKS_OB'] = ob_get_level(); ob_start(); } ?>