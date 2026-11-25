<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

use Bitrix\Main\Page\Asset;
use Bitrix\Main\Config\Option;
Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/vendor/css/carousel/swiper/swiper-bundle.min.css");
Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/vendor/js/carousel/swiper/swiper-bundle.min.js");

$APPLICATION->SetPageProperty("viewed_show", "Y");
$APPLICATION->SetTitle("АТРИУМ — оптовые поставки сантехники, плитки и керамогранита");
$APPLICATION->SetPageProperty("title", "АТРИУМ опт — сантехника, плитка и керамогранит оптом в Крыму");
$APPLICATION->SetPageProperty("description", "Оптовые поставки сантехники, плитки, керамогранита и мебели для ванной в Крыму. Прямые поставки от производителей, конкурентные оптовые цены, доставка по Крыму. АТРИУМ — больше 10 лет на рынке.");
global $USER;
?>
<div itemscope itemtype="http://schema.org/LocalBusiness" style="display:none;">
	<p itemprop="name">АТРИУМ</p>
	<p itemprop="description">Оптовые поставки сантехники, плитки и керамогранита в Крыму.</p>
	<meta itemprop="telephone" content="+7 (978) 770-35-30">
	<meta itemprop="telephone" content="+7 (978) 800-55-56">
	<a itemprop="email" href="mailto:optplitka-crimea@yandex.ru">optplitka-crimea@yandex.ru</a>
	<span itemprop="openingHours" content="Mo-Fr 09:00-19:00">Пн–Пт 9:00–19:00</span>
	<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
		<span itemprop="addressCountry">Россия</span>
		<span itemprop="addressLocality">Симферополь</span>
		<span itemprop="streetAddress">ул. Вилара, 47</span>
	</div>
</div>

<?/* ===== 1. Баннер-слайдер (ТЗ п.4) + Товар дня (ТЗ п.5) ===== */?>
<section class="opt-billbord">
	<div class="maxwidth-theme">
		<div class="opt-billbord__grid">
			<div class="opt-billbord__slider">
				<?
				global $arOptSliderFilter;
				$arOptSliderFilter = ['SECTION_ID' => [202, 201]]; // «Основные» и «Добавочные» баннеры
				$APPLICATION->IncludeComponent("bitrix:news.list", "main_slider", [
					"IBLOCK_TYPE" => "aspro_max_adv",
					"IBLOCK_ID" => "62",
					"NEWS_COUNT" => "20",
					"FILTER_NAME" => "arOptSliderFilter",
					"FIELD_CODE" => ["NAME", "PREVIEW_PICTURE"],
					"PROPERTY_CODE" => ["URL_STRING", "TABLET_IMAGE"],
					"SORT_BY1" => "SORT", "SORT_ORDER1" => "ASC",
					"SORT_BY2" => "ID", "SORT_ORDER2" => "DESC",
					"CHECK_DATES" => "Y",
					"SET_TITLE" => "N", "SET_BROWSER_TITLE" => "N", "SET_META_KEYWORDS" => "N", "SET_META_DESCRIPTION" => "N",
					"INCLUDE_IBLOCK_INTO_CHAIN" => "N", "ADD_SECTIONS_CHAIN" => "N",
					"CACHE_TYPE" => "A", "CACHE_TIME" => "3600", "CACHE_FILTER" => "Y", "CACHE_GROUPS" => "N",
					"DISPLAY_TOP_PAGER" => "N", "DISPLAY_BOTTOM_PAGER" => "N",
				], false, ['HIDE_ICONS' => 'N']);
				$arOptSliderFilter = [];
				?>
			</div>
			<?ob_start();
				include $_SERVER["DOCUMENT_ROOT"]."/catalog/_catalog_param.php";
				global $arOptDayFilter;
				$arOptDayFilter = ['PROPERTY_TOVAR_DNYA_VALUE' => 'Да'];
				$APPLICATION->IncludeComponent("bitrix:catalog.section", "tovar-dnya", array_merge($_catalog_param, [
					"FILTER_NAME" => "arOptDayFilter",
					"PAGE_ELEMENT_COUNT" => 1,
					"ELEMENT_SORT_FIELD" => "TIMESTAMP_X",
					"ELEMENT_SORT_ORDER" => "DESC",
				]), false, ['HIDE_ICONS' => 'Y']);
				$arOptDayFilter = [];
				$dayHtml = trim(ob_get_clean());
			?>
			<?if($dayHtml !== ''):?><div class="opt-billbord__day"><?=$dayHtml?></div><?endif;?>
		</div>
	</div>
</section>

<?/* ===== 2. Плашки категорий (ТЗ п.3) ===== */?>
<?
global $arOptCatsFilter;
$arOptCatsFilter = ['DEPTH_LEVEL' => 2];
// плашки — разделы любого уровня с флажком «Показывать на главной (опт)» (UF_OPT_MAIN); ни одного не отмечено — все разделы 2-го уровня
if (CModule::IncludeModule('iblock') && CIBlockSection::GetList([], ['IBLOCK_ID' => 80, 'ACTIVE' => 'Y', 'UF_OPT_MAIN' => 1], false, ['ID'], ['nTopCount' => 1])->Fetch()) {
	$arOptCatsFilter = ['UF_OPT_MAIN' => 1];
}
$APPLICATION->IncludeComponent("bitrix:catalog.section.list", "main_categories", [
	"IBLOCK_TYPE" => "1c_catalog",
	"IBLOCK_ID" => "80",
	"SECTION_ID" => "",
	"TOP_DEPTH" => "10", // отмеченный раздел может быть любого уровня
	"FILTER_NAME" => "arOptCatsFilter",
	"SECTION_URL" => "/catalog/#SECTION_CODE_PATH#/",
	"SECTION_FIELDS" => ["NAME", "PICTURE", "SORT"],
	"SECTION_USER_FIELDS" => ["UF_OPT_MAIN_NAME"], // «Название на главной (опт)»
	"COUNT_ELEMENTS" => "N",
	"ADD_SECTIONS_CHAIN" => "N",
	"CACHE_TYPE" => "A", "CACHE_TIME" => "36000", "CACHE_GROUPS" => "N", "CACHE_FILTER" => "Y",
], false, ["HIDE_ICONS" => "N"]);
$arOptCatsFilter = [];
?>

<?/* ===== 3. Бренды (ТЗ п.6) ===== */?>
<section class="section-brands opt-section">
	<div class="maxwidth-theme">
		<div class="head-section">
			<div class="head-section__left">
				<h2 class="title-section">Бренды</h2>
			</div>
			<a href="/info/brands/" class="link-page">Смотреть все бренды</a>
		</div>
		<?$APPLICATION->IncludeComponent("bitrix:news.list", "front-brands-slider", [
			"IBLOCK_TYPE" => "aspro_max_content",
			"IBLOCK_ID" => "68",
			"NEWS_COUNT" => "100",
			"FIELD_CODE" => ["NAME", "PREVIEW_PICTURE"],
			"PROPERTY_CODE" => [],
			"SORT_BY1" => "SORT", "SORT_ORDER1" => "ASC",
			"SORT_BY2" => "NAME", "SORT_ORDER2" => "ASC",
			"SET_TITLE" => "N", "SET_BROWSER_TITLE" => "N", "SET_META_KEYWORDS" => "N", "SET_META_DESCRIPTION" => "N",
			"INCLUDE_IBLOCK_INTO_CHAIN" => "N", "ADD_SECTIONS_CHAIN" => "N",
			"CACHE_TYPE" => "A", "CACHE_TIME" => "36000", "CACHE_GROUPS" => "N",
			"DISPLAY_TOP_PAGER" => "N", "DISPLAY_BOTTOM_PAGER" => "N",
		], false, ['HIDE_ICONS' => 'N']);?>
	</div>
</section>

<?/* ===== 4. Новинки (ТЗ п.7): товары с флажком «Новинка (опт)» (как /novinki/), от новых к старым ===== */?>
<section class="section-catalog opt-section">
	<div class="maxwidth-theme">
		<div class="head-section">
			<div class="head-section__left">
				<h2 class="title-section">Новинки</h2>
			</div>
			<a href="/novinki/" class="link-page">Все товары в каталоге</a>
		</div>
		<?
		include $_SERVER["DOCUMENT_ROOT"]."/catalog/_catalog_param.php";
		global $arOptNewFilter;
		$arOptNewFilter = OptNewFilter();
		$APPLICATION->IncludeComponent("bitrix:catalog.section", "swiper-slide-product", array_merge($_catalog_param, [
			"FILTER_NAME" => "arOptNewFilter",
			"PAGE_ELEMENT_COUNT" => 12,
			"ELEMENT_SORT_FIELD" => "DATE_CREATE",
			"ELEMENT_SORT_ORDER" => "DESC",
			"ELEMENT_SORT_FIELD2" => "ID",
			"ELEMENT_SORT_ORDER2" => "DESC",
		]), false, ['HIDE_ICONS' => 'Y']);
		?>
	</div>
</section>

<?/* ===== 5. О компании + регистрация (ТЗ п.8) ===== */?>
<section class="opt-about">
	<div class="maxwidth-theme">
		<div class="opt-about__grid">
			<div class="opt-about__text">
				<?$APPLICATION->IncludeFile(SITE_DIR."include/mainpage/opt_about.php", [], ["MODE" => "html", "NAME" => "О компании (главная)"]);?>
			</div>
			<?if(!$USER->IsAuthorized()):?>
				<div class="opt-register">
					<div class="opt-register__title">Регистрация новых клиентов</div>
					<div class="opt-register__descr">Зарегистрируйтесь, чтобы использовать все возможности личного кабинета</div>
					<?
					$arShowFields = ["LOGIN", "LAST_NAME", "NAME", "SECOND_NAME", "EMAIL", "PERSONAL_PHONE"];
					$arRequiredFields = ["NAME"];
					if (Option::get('main', 'new_user_phone_required', 'N', SITE_ID) == 'Y') $arRequiredFields[] = "PERSONAL_PHONE";
					if (Option::get('main', 'new_user_email_required', 'N', SITE_ID) == 'Y') $arRequiredFields[] = "EMAIL";
					$APPLICATION->IncludeComponent("bitrix:main.register", "main", [
						"USER_PROPERTY_NAME" => "",
						"SHOW_FIELDS" => $arShowFields,
						"REQUIRED_FIELDS" => $arRequiredFields,
						"AUTH" => "Y",
						"USE_BACKURL" => "Y",
						"SUCCESS_PAGE" => "",
						"SET_TITLE" => "N",
						"USER_PROPERTY" => [],
					]);?>
					<script>
					// подписи и плейсхолдеры полей по макету
					(function(){
						var f = document.querySelector('.opt-register form'); if(!f) return;
						var map = {'REGISTER[NAME]':['Фамилия Имя Отчество','Введите ФИО'], 'REGISTER[EMAIL]':['Ваша почта','Введите почту'], 'REGISTER[PERSONAL_PHONE]':['Телефон','+7 (___)___-__-__'], 'REGISTER[PASSWORD]':['Пароль*','******'], 'REGISTER[CONFIRM_PASSWORD]':['Подтверждение пароля*','******'], 'captcha_word':['Введите код*','******']};
						Object.keys(map).forEach(function(n){
							var i = f.querySelector('[name="'+n+'"]'); if(!i) return;
							i.placeholder = map[n][1];
							var row = i.closest('.form-control'), l = row && row.querySelector('label span');
							if(l) l.textContent = map[n][0];
						});
					})();
					</script>
				</div>
			<?endif;?>
		</div>
	</div>
</section>

<?/* ===== 6. Новости ===== */?>
<section class="section-news opt-section">
	<div class="maxwidth-theme">
		<div class="section-news__head">
			<h2 class="title-section">Новости</h2>
			<p class="descr-section section-news__descr">Следите за обновлениями, акциями и событиями компании АТРИУМ</p>
		</div>
		<?$APPLICATION->IncludeComponent("bitrix:news.list", "front-news", [
			"IBLOCK_TYPE" => "news",
			"IBLOCK_ID" => "1",
			"NEWS_COUNT" => "4",
			"FIELD_CODE" => ["NAME", "PREVIEW_TEXT", "PREVIEW_PICTURE", "DATE_ACTIVE_FROM"],
			"PROPERTY_CODE" => [],
			"SORT_BY1" => "ACTIVE_FROM", "SORT_ORDER1" => "DESC",
			"SORT_BY2" => "ID", "SORT_ORDER2" => "DESC",
			"DETAIL_URL" => "/news/#ELEMENT_CODE#/",
			"ACTIVE_DATE_FORMAT" => "d.m.Y",
			"SET_TITLE" => "N", "SET_BROWSER_TITLE" => "N", "SET_META_KEYWORDS" => "N", "SET_META_DESCRIPTION" => "N",
			"INCLUDE_IBLOCK_INTO_CHAIN" => "N", "ADD_SECTIONS_CHAIN" => "N",
			"CACHE_TYPE" => "A", "CACHE_TIME" => "3600", "CACHE_GROUPS" => "N",
			"DISPLAY_TOP_PAGER" => "N", "DISPLAY_BOTTOM_PAGER" => "N",
		], false, ['HIDE_ICONS' => 'N']);?>
	</div>
</section>

<?/* ===== 7. Контакты + фото (ТЗ п.9) ===== */?>
<section class="opt-contacts">
	<div class="maxwidth-theme">
		<div class="opt-contacts__grid">
			<div class="opt-contacts__text">
				<?$APPLICATION->IncludeFile(SITE_DIR."include/mainpage/opt_contacts.php", [], ["MODE" => "html", "NAME" => "Контакты (главная)"]);?>
			</div>
			<div class="opt-contacts__photo">
				<img class="lazy" src="<?=\Aspro\Functions\CAsproMax::showBlankImg('/images/opt-main/contacts.jpg')?>" data-src="/images/opt-main/contacts.jpg" alt="Офис и склад АТРИУМ">
			</div>
		</div>
	</div>
</section>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
