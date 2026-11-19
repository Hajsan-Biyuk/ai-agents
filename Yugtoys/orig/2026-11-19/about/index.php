<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Информация о компании Юг Тойз");
?><section class="page-about">
<div class="container">
	<h1 class="title-section">О нас</h1>
	<div class="about-wrapper">
		<div class="about-unit">
			<div class="row">
				<div class="col-lg-7 about-unit__text">
					<div class="about-unit__head">
						 с 1995 г
					</div>
					<p>
						 Компания ЮГ ТОЙЗ работает под брендами «Мир игрушек» и «ЮГ ТОЙЗ» на рынке оптовых продаж детских товаров и игрушек с 1995 г., и является одной из ведущих фирм на Юге России, успешно занимаясь этим бизнесом довольно продолжительное время. Для нашей компании мы импортируем и получаем товар напрямую с заводов-изготовителей.
					</p>
				</div>
				<div class="col-lg-5">
					<div class="about-unit__image">
 <img alt="alt" src="/local/templates/ugtoiz/img/img_about1.png">
					</div>
				</div>
			</div>
		</div>
		<div class="about-unit">
			<div class="row">
				<div class="col-lg-7 about-unit__text">
					<div class="about-unit__head">
						 География закупок
					</div>
					<p>
						 География наших закупок достаточно широка – сегодня ЮГ ТОЙЗ предлагает клиентам обширный ассортимент детских игрушек, спортивных и канцелярских товаров, произведенных в России и Китае. Активная маркетинговая и инновационная политика позволяет довести до российского потребителя все новинки, появляющиеся на мировом рынке игрушек и товаров для детей. Мы поставляем наши товары в Краснодар&nbsp;и Ставрополь.
					</p>
				</div>
				<div class="col-lg-5">
					<div class="about-unit__image">
 <img alt="alt" src="/local/templates/ugtoiz/img/img_about2.png">
					</div>
				</div>
			</div>
		</div>
		<div class="about-unit">
			<div class="row">
				<div class="col-lg-7 about-unit__text">
					<div class="about-unit__head">
						 Тысячи наименований
					</div>
					<p>
						 Наш товар – это многие тысячи наименований игрушек от мягкой игрушки, кукол, солдатиков до аккумуляторных машин, велосипедов и товаров торговой марки Intex. Здесь можно найти всё, что только может пожелать бурное воображение ребенка. Особое внимание уделяется качеству продукции, реализуемой нашей оптовой базой и компанией. Все товары, которые мы представляем, сертифицированы в России, соответствуют требованиям и ГОСТам и абсолютно безопасны для детей.
					</p>
					<p>
						 Так как наша фирма занимается только оптовой торговлей, мы работаем с юридическими лицами и индивидуальными предпринимателями.
					</p>
				</div>
				<div class="col-lg-5">
					<ul class="slider-about">
						<li>
						<div class="slider-about__image">
 <img alt="alt" src="/local/templates/ugtoiz/img/about_slide1.jpg">
						</div>
 </li>
						<li>
						<div class="slider-about__image">
 <img alt="alt" src="/local/templates/ugtoiz/img/about_slide1.jpg">
						</div>
 </li>
						<li>
						<div class="slider-about__image">
 <img alt="alt" src="/local/templates/ugtoiz/img/about_slide1.jpg">
						</div>
 </li>
					</ul>
				</div>
			</div>
		</div>
		<div class="about-unit" id="sertificats">
			<div class="row">
				<div class="col-lg-8">
					<div class="row row_serts">
						<div class="col-md-4">
							<div class="wrapper-sertificat">
								<a href="/upload/medialibrary/bab/9c8vlb79tra57xsdwc9xt2s6bqta1vvg.jpg" class="sertificat-advantage fancybox"> <img alt="alt" src="/upload/medialibrary/bab/9c8vlb79tra57xsdwc9xt2s6bqta1vvg.jpg"> </a> <a href="/upload/medialibrary/bab/9c8vlb79tra57xsdwc9xt2s6bqta1vvg.jpg" class="link-main fancybox">Cертификат соответствия</a>
							</div>
						</div>
						<div class="col-md-4">
							<div class="wrapper-sertificat">
								<a href="/upload/medialibrary/5a8/zn7d2opch0z2bauyi19rrqxv0uzijgb1.jpg" class="sertificat-advantage fancybox"> <img alt="alt" src="/upload/medialibrary/5a8/zn7d2opch0z2bauyi19rrqxv0uzijgb1.jpg"> </a> <a href="/upload/medialibrary/5a8/zn7d2opch0z2bauyi19rrqxv0uzijgb1.jpg" class="link-main fancybox">Cертификат соответствия</a>
							</div>
						</div>
						<div class="col-md-4">
							<div class="wrapper-sertificat">
								<a href="/upload/medialibrary/d72/5ywl6kvg8ym25eg1k93momywd3kgdr9n.jpg" class="sertificat-advantage fancybox"> <img alt="alt" src="/upload/medialibrary/d72/5ywl6kvg8ym25eg1k93momywd3kgdr9n.jpg"> </a> <a href="/upload/medialibrary/d72/5ywl6kvg8ym25eg1k93momywd3kgdr9n.jpg" class="link-main fancybox">Cертификат соответствия</a>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-4">
<?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "file",
		"PATH" => SITE_DIR."include/forms/contact_us.php"
	),
false,
Array(
	'HIDE_ICONS' => 'Y'
)
);?>
				</div>
			</div>
		</div>
	</div>
</div>
 </section><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>