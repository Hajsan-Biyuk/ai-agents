<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
		<footer class="footer">
			<div class="container">
				<div class="row">
					<div class="col-lg">
						<div class="footer__logo">
							<a href="/" class="logo">
								<img src="<?=SITE_TEMPLATE_PATH?>/img/logo.svg" alt="alt">
							</a>
						</div>
						<div class="footer__title">Реквизиты  <i class="far fa-chevron-down"></i></div>
						<div class="footer__content">
							<div class="requisites">
								<?echo \COption::GetOptionString( "askaron.settings", "UF_REQUISITES");?>
							</div>
						</div>
					</div>
					<div class="col-lg">
						<div class="footer__title">Каталог  <i class="far fa-chevron-down"></i></div>
						<div class="footer__content">
							<ul class="footer__nav">
								<?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/main_head_menu.php"], false, ['HIDE_ICONS' => 'N']);?>
							</ul>
						</div>
					</div>
					<div class="col-lg">
						<div class="footer__title">Покупателям  <i class="far fa-chevron-down"></i></div>
						<div class="footer__content">
							<ul class="footer__nav">
								<?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/footer__nav1.php"], false, ['HIDE_ICONS' => 'N']);?>
							</ul>
						</div>
						<br>
						<div class="footer__title">О компании  <i class="far fa-chevron-down"></i></div>
						<div class="footer__content">
							<ul class="footer__nav">
								<?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/footer__nav2.php"], false, ['HIDE_ICONS' => 'N']);?>								
							</ul>
						</div>
					</div>
					<div class="col-lg-5">
						<div class="footer__title">Контакты  <i class="far fa-chevron-down"></i></div>
						<div class="footer__content">
							<div class="row">
								<div class="col-lg-5">
									<p><a href="tel:<?=preg_replace('/[^0-9+]/', '', \COption::GetOptionString( "askaron.settings", "UF_PHONE"))?>"><strong><?echo \COption::GetOptionString( "askaron.settings", "UF_PHONE");?></strong></a></p>
									<p><a href="tel:<?=preg_replace('/[^0-9+]/', '', \COption::GetOptionString( "askaron.settings", "UF_PHONE1"))?>"><strong><?echo \COption::GetOptionString( "askaron.settings", "UF_PHONE1");?></strong></a></p>
									<p><a href="tel:<?=preg_replace('/[^0-9+]/', '', \COption::GetOptionString( "askaron.settings", "UF_PHONE2"))?>"><strong><?echo \COption::GetOptionString( "askaron.settings", "UF_PHONE2");?></strong></a></p>
									<br>
									<p><a href="mailto:<?echo \COption::GetOptionString( "askaron.settings", "UF_EMAIL");?>"><?echo \COption::GetOptionString( "askaron.settings", "UF_EMAIL");?></a></p>
									<br>
									<ul class="social-list">
										<li><a target="_blank" href="<?echo \COption::GetOptionString("askaron.settings", "UF_WHATSAPP");?>"><img src="<?=SITE_TEMPLATE_PATH?>/img/wp2.svg" alt="alt"></a></li>
										<li><a target="_blank" href="<?echo \COption::GetOptionString("askaron.settings", "UF_TELEGRAM");?>"><img src="<?=SITE_TEMPLATE_PATH?>/img/tg.svg" alt="alt"></a></li>
										<?php if(\COption::GetOptionString("askaron.settings", "UF_VIBER")) { ?>
											<li><a target="_blank" href="<?echo \COption::GetOptionString("askaron.settings", "UF_VIBER");?>"><img src="<?=SITE_TEMPLATE_PATH?>/img/vb.svg" alt="alt"></a></li>
										<?php } ?>
									</ul>
								</div>
								<div class="col-lg-7">
									<?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/main_foot_info.php"], false, ['HIDE_ICONS' => 'N']);?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="footer-bottom">
				<div class="container">
					<div class="row align-items-center">
						<div class="col-lg">
							<a href="/about/privacy-policy/">Политика конфиденциальности</a><br>
							<a href="/about/soglasie/">Согласие на обработку персональных данных</a>
						</div>
						<div class="col-lg">
							<p>г. Краснодар © Copyright  <?=date('Y')?> г.</p>
						</div>
						<div class="col-lg">
							<div class="create">Сделано в <a target="_blank" href="https://sheer82.ru"><img src="<?=SITE_TEMPLATE_PATH?>/img/copy.svg" alt="alt"></a></div>
						</div>
						<div class="col-xl-4 col-lg">
							<a href="#" class="btn_top"><i class="far fa-arrow-up"></i></a>
						</div>
					</div>
				</div>
			</div>
		</footer>
	</div>
	
	
<div style="display: none; width: 375px;" id="opt_price">
	<?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/forms/opt_price.php"], false, ['HIDE_ICONS' => 'N']);?>
</div>

<div style="display: none; width: 375px;" id="create-order-retail">
	<?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/forms/create-order-retail.php"], false, ['HIDE_ICONS' => 'N']);?>
</div>

<div style="display: none; width: 375px;" id="callback_main">
	<?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/forms/callback_main.php"], false, ['HIDE_ICONS' => 'N']);?>
</div>

<div style="display: none; width: 375px;" id="callback_my">
	<?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/forms/callback_my.php"], false, ['HIDE_ICONS' => 'N']);?>
</div>

<div style="display: none; width: 375px;" id="contact_manager">
	<?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/forms/contact_manager.php"], false, ['HIDE_ICONS' => 'N']);?>
</div>

<div style="display: none; width: 800px;" id="user_info">
	<?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/forms/user_info.php"], false, ['HIDE_ICONS' => 'N']);?>
	<?
	if(!isset($_SESSION['show_user_info'])){
		echo '<script>$(document).ready(function(){setTimeout(function(){$.fancybox.open("#user_info")}, 3000)})</script>';
		echo '<style>.fancybox-overlay{background: #fffa;}.fancybox-wrap{top: 190px !important;}</style>';
		$_SESSION['show_user_info'] = 'Y';
	}
	?>
</div>
	
<div class="cookies-modal" style="display: none;">
	<span>Мы используем cookies для быстрой и удобной работы сайта. Продолжая пользоваться сайтом, вы принимаете <a class="cookie-policy-link" href="/about/privacy-policy/">условия обработки персональных данных</a></span>
	<div class="cookies-btns">
		<div class="cookies-btn yes-cookie">Согласен</div>
		<div class="cookies-btn no-cookie">Не согласен</div>
	</div>
	<button type="button" class="cookie-policy-button">✕</button>
</div>
	
<!--[if lt IE 9]>
	<script src="<?=SITE_TEMPLATE_PATH?>/libs/html5shiv/es5-shim.min.js"></script>
	<script src="<?=SITE_TEMPLATE_PATH?>/libs/html5shiv/html5shiv.min.js"></script>
	<script src="<?=SITE_TEMPLATE_PATH?>/libs/html5shiv/html5shiv-printshiv.min.js"></script>
	<script src="<?=SITE_TEMPLATE_PATH?>/libs/respond/respond.min.js"></script>
<![endif]-->
<?php
if (strpos($_SERVER['REQUEST_URI'], '/about/contacts/') !== false) {
?>
<script type="application/ld+json">
{
	"@context": "http://schema.org/",
	"@type": "LocalBusiness",
	"name": "Юг Тойз",
	"image": "https://www.yugtoys.ru/local/templates/ugtoiz/img/logo.svg",
	"priceRange": "₽10-5000",
	"telephone": "8 (499) 325-77-29",
	"url": "https://www.yugtoys.ru/",
	"address": {
		"@type": "PostalAddress",
		"streetAddress": "ул. Новороссийская, 98",
		"addressLocality": "Краснодар",
		"addressRegion": "Краснодарский край",
		"postalCode": "350001",
		"addressCountry": "Russian Federation"
	},
	"openingHoursSpecification": [
		{
			"@type": "OpeningHoursSpecification",
			"dayOfWeek": ["Monday", "Tuesday", "Thursday", "Wednesday", "Friday"],
			"opens": "08:30",
			"closes": "17:30"
		}
	]
}
</script>
<?php } ?>
<!-- Yandex.Metrika counter -->
<!-- /Yandex.Metrika counter -->
<!-- Google Analytics counter --><!-- /Google Analytics counter -->

<?
$canonical_set = false;
$requests_keys = array_keys($_REQUEST);
foreach($requests_keys as $request_key){
	if(mb_ereg_match('PAGEN_', $request_key)){
		$APPLICATION->AddHeadString('<link href="https://' . $_SERVER['SERVER_NAME'] . strtok($_SERVER['REQUEST_URI'], '?').'" rel="canonical" />',true);
		$canonical_set = true;
		break;
	}
}

if(!$canonical_set){
    $curPage = $APPLICATION->GetCurPage(false);
    $canonical = $_SERVER["REQUEST_SCHEME"] . '://' . $_SERVER["SERVER_NAME"] . $curPage;
	$APPLICATION->AddHeadString('<link href="'. $canonical .'" rel="canonical" />',true);
}
?>
</body>
</html>