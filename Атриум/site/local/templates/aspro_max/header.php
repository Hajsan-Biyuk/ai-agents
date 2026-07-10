<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?if($_GET["debug"] == "y")
	error_reporting(E_ERROR | E_PARSE);
IncludeTemplateLangFile(__FILE__);
global $APPLICATION, $arRegion, $arSite, $arTheme, $bIndexBot, $bIframeMode;
$arSite = CSite::GetByID(SITE_ID)->Fetch();
$htmlClass = ($_REQUEST && isset($_REQUEST['print']) ? 'print' : false);
$bIncludedModule = (\Bitrix\Main\Loader::includeModule("aspro.max"));

$subject = $_SERVER['REQUEST_URI'];
$pattern = '/\/\/+/';
$countReplace = 0;
$replaced_url = preg_replace($pattern, '/', $subject, -1, $countReplace);
if ($countReplace > 0)
	LocalRedirect($replaced_url, false, '301 Moved Permanently');

?>

<?include_once($_SERVER['DOCUMENT_ROOT'].'/redirect.php');?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=LANGUAGE_ID?>" lang="<?=LANGUAGE_ID?>" <?=($htmlClass ? 'class="'.$htmlClass.'"' : '')?> <?=($bIncludedModule ? CMax::getCurrentHtmlClass() : '')?>>
<head>
	<title><?$APPLICATION->ShowTitle()?></title>
<meta name="theme-color" content="#fff">
	<?
		$APPLICATION->ShowMeta("viewport");
		$APPLICATION->ShowMeta("HandheldFriendly");
		$APPLICATION->ShowMeta("SKYPE_TOOLBAR");
		$APPLICATION->ShowMeta("apple-mobile-web-app-status-bar-style");

		/*
		$APPLICATION->ShowMeta('#ffffff', 'theme-color');
		$APPLICATION->ShowMeta('#ffffff', 'msapplication-navbutton-color', true);
		$APPLICATION->ShowMeta('#ffffff', 'apple-mobile-web-app-status-bar-style');
		*/

		//$APPLICATION->ShowMeta('keywords');
		$APPLICATION->ShowMeta('description');
		$APPLICATION->ShowMeta("robots");
		$APPLICATION->ShowCSS(true);
		$APPLICATION->ShowHeadStrings();
		$APPLICATION->ShowHeadScripts();
		
		//$APPLICATION->SetPageProperty('canonical', $arResult["CANONICAL_PAGE_URL"]);
		$APPLICATION->ShowProperty('custom_canonical');
	?>
	<?$APPLICATION->AddHeadString('<script>BX.message('.CUtil::PhpToJSObject( $MESS, false ).')</script>', true);?>
	<?$APPLICATION->SetAdditionalCSS("/local/templates/aspro_max/css/common.css");?>
	<?$APPLICATION->SetAdditionalCSS("/local/templates/aspro_max/css/chosen.min.css");?>
	<?$APPLICATION->SetAdditionalCSS("/local/templates/aspro_max/css/iq.css");?>
	<?$APPLICATION->AddHeadScript('/local/templates/aspro_max/js/magnific.js');?>
	<?$APPLICATION->AddHeadScript('/local/templates/aspro_max/js/chosen.jquery.min.js');?>
	<?$APPLICATION->AddHeadScript('/local/templates/aspro_max/js/slick.js');?>
	<?$APPLICATION->AddHeadScript('/local/templates/aspro_max/js/typed.js-master/lib/typed.js');?>
	<?//$APPLICATION->AddHeadScript('/bitrix/templates/aspro_max/js/app.js');?>
	<?if($bIncludedModule)
		CMax::Start(SITE_ID);?>
	<?include_once(str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DIR.'include/header_include/head.php'));?>
	<meta name="yandex-verification" content="939d39f59fa421e3" />
	<meta name="yandex-verification" content="9963928061876cb4" />
	<meta name="yandex-verification" content="e26a17ce374b1491" />
	<meta name="yandex-verification" content="375c30cbff2871a5" />
	<meta name="google-site-verification" content="i3dQnyiNP9mKdkZ3gNVMco5gJ1WOT3ZzmOVnEaa4H3A" />
	<? if ($APPLICATION->GetCurPage(false) === '/info/'){?>
		<meta name="robots" content="noindex" />
	<?}

	include_once($_SERVER['DOCUMENT_ROOT'].'/metacanonical.php');

	$arr_no_pagination=[
		'/',
		'/company/vozvrat-i-obmen/',
		'/company/sovety-po-vyboru/',
		'/blog/',
		'/company/dlya-dizaynerov/',
		'/company/licenses/',
		'/company/sotrudnichestvo/',
		'/dostavka-i-oplata/',
		'/contacts/',
		'/contacts/stores/',
		'/politika-konfidencialnosti/',
	];

	if( (array_search($APPLICATION->GetCurPage(),$arr_no_pagination)!==false) && $_GET['PAGEN_2']){?>
		<meta name="robots" content="noindex, nofollow" />
	<?}
	?>
	<script src="https://forma.tinkoff.ru/static/onlineScript.js"></script>

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css" />
	<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>
</head>
<?$bIndexBot = $bIncludedModule ? CMax::checkIndexBot() : false;?>
<body class="<?=($bIndexBot ? "wbot" : "");?> site_<?=SITE_ID?> <?=($bIncludedModule ? CMax::getCurrentBodyClass() : '')?> common" id="main" data-site="<?=SITE_DIR?>" itemscope itemtype="https://schema.org/WebPage"><?/*itemscope itemtype="http://schema.org/ImageObject"*/?>
	<link itemprop="url" href="https://atriumceramic.ru<?=$APPLICATION->GetCurPage();?>"/>
	
	<div style="display:none" itemscope itemtype="https://schema.org/WPHeader">
    <span itemprop="name"><?$APPLICATION->ShowTitle()?></span>
    <span itemprop="description"><?$APPLICATION->ShowProperty('description');?></span>
    	<div itemscope itemtype="https://schema.org/ImageObject">
        	<a href="https://<?=$_SERVER['HTTP_HOST'];?>" rel="home">
           		<img itemprop="image" src="https://<?=$_SERVER['HTTP_HOST'];?>/upload/CMax/f13/o7v6x2nqjunh89japns8qsno4uep4fnx.png" alt="Логотип Атриум">
        	</a>
    	</div>
	</div>
	

	<?if(!$bIncludedModule):?>
		<?$APPLICATION->SetTitle(GetMessage("ERROR_INCLUDE_MODULE_ASPRO_MAX_TITLE"));?>
		<center><?$APPLICATION->IncludeFile(SITE_DIR."include/error_include_module.php");?></center></body></html><?die();?>
	<?endif;?>
	<?include_once(str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DIR.'include/header_include/body_top.php'));?>
	<?$arTheme = $APPLICATION->IncludeComponent("aspro:theme.max", ".default", array("COMPONENT_TEMPLATE" => ".default"), false, array("HIDE_ICONS" => "Y"));?>
	<?include_once('defines.php');?>
	<?CMax::SetJSOptions();?>
	<?include_once(str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DIR.'include/header_include/under_wrapper1.php'));?>
	<div class="wrapper1 <?=($isIndex && $isShowIndexLeftBlock ? "with_left_block" : "");?> <?=CMax::getCurrentPageClass();?> <?$APPLICATION->AddBufferContent(array('CMax', 'getCurrentThemeClasses'))?>  ">
		<?include_once(str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DIR.'include/header_include/top_wrapper1.php'));?>
	<?/*<div class="warning-container">
			<div class="container">
				Уважаемые покупатели! В связи с нестабильностью курса валют цены на некоторые товары могут не совпадать!<br> Актуальность цены вы всегда можете уточнить у менеджера по телефону.
			</div>
		</div>*/?>
		<div class="wraps hover_<?=$arTheme["HOVER_TYPE_IMG"]["VALUE"];?>" id="content">
			<?include_once(str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DIR.'include/header_include/top_wraps.php'));?>
			<?if($isIndex):?>
				<?$APPLICATION->ShowViewContent('front_top_big_banner');?>
				<div class="wrapper_inner maxwidth-theme front <?=($isShowIndexLeftBlock ? "" : "wide_page");?> <?=$APPLICATION->ShowViewContent('wrapper_inner_class')?>">
			<?elseif(!$isWidePage):?>
				<div class="wrapper_inner maxwidth-theme <?=($isHideLeftBlock ? "wide_page" : "");?> <?=$APPLICATION->ShowViewContent('wrapper_inner_class')?>">
			<?endif;?>
				<div class="container_inner clearfix <?=$APPLICATION->ShowViewContent('container_inner_class')?>">
				<?if(($isIndex && ($isShowIndexLeftBlock || $bActiveTheme)) || (!$isIndex && !$isHideLeftBlock)):?>
					<div class="right_block <?=(defined("ERROR_404") ? "error_page" : "");?> wide_<?=CMax::ShowPageProps("HIDE_LEFT_BLOCK");?> <?=$APPLICATION->ShowViewContent('right_block_class')?>">
				<?endif;?>
					<div class="middle <?=($is404 ? 'error-page' : '');?> <?=$APPLICATION->ShowViewContent('middle_class')?>">
						<?CMax::get_banners_position('CONTENT_TOP');?>
						<?if(!$isIndex):?>
							<div class="container">
								<?//h1?>
								<?if($isHideLeftBlock && !$isWidePage):?>
									<div class="maxwidth-theme">
								<?endif;?>
						<?endif;?>
						<?CMax::checkRestartBuffer();?>
