<?
// П-3.2: /index.php → / (301). Только GET/HEAD, чтобы не потерять POST; query сохраняем (utm и т. п.)
if (in_array($_SERVER['REQUEST_METHOD'], array('GET', 'HEAD'), true) && preg_match('#^/index\.php(\?.*)?$#', $_SERVER['REQUEST_URI'], $m)) {
	header('Location: https://www.yugtoys.ru/' . (isset($m[1]) ? $m[1] : ''), true, 301);
	exit;
}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Игрушки оптом в Краснодаре - Оптовый магазин детских товаров Юг Тойз");
$APPLICATION->SetPageProperty("description",'Оптовый магазин детских игрушек в Краснодаре - Купить игрушки оптом дешево от поставщика Юг Тойз. Низкие цены. Широкий ассортимент на складе.');
?>
<?
	include $_SERVER["DOCUMENT_ROOT"]."/catalog/_catalog_param.php";
	global $arrFilter;
	$_catalog_param = array_merge($_catalog_param, array("FILTER_NAME" => "arrFilter", "SET_TITLE" => "N", "ELEMENT_SORT_FIELD" => "RAND", "PAGE_ELEMENT_COUNT" => "6",));
?>
<section class="billbord">
	<div class="container">
	<?$APPLICATION->IncludeComponent(
		"bitrix:news.list", 
		"main_slider",
		array(
			"IBLOCK_TYPE" => "sliders",
			"IBLOCK_ID" => "5",
			"NEWS_COUNT" => "10",
			"SORT_BY1" => "SORT",
			"SORT_ORDER1" => "ASC",
			"PROPERTY_CODE" => array("LINK"),
			"SET_TITLE" => "N",
			"SET_BROWSER_TITLE" => "N",
			"CACHE_TYPE" => "N",
			"CACHE_TIME" => "0",
		),
		false,
		array('HIDE_ICONS' => 'Y')
	);?>
	</div>
</section>

<section class="benefits">
	<div class="container">
		<div class="row">
			<?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/benefits.php"], false, ['HIDE_ICONS' => 'Y']);?>
		</div>
	</div>
</section>

<section class="categories">
	<div class="container">
		<h1 class="title-section">Оптовый магазин детских игрушек Юг-тойз в Краснодаре</h1>
		<div class="descr-section">У нас вы найдете всё, что только могут захотеть дети</div>
		<div class="row row_categories">
			<div class="col-lg-6">
				<div class="item-category item-category_1">
					<a href="/catalog/igrushki/" class="item-category__image">
						<img alt="Игрушки" src="/local/templates/ugtoiz/img/category1.png">
					</a>
					<h2><a href="/catalog/igrushki/" class="item-category__title">Игрушки</a></h2>
					<ul>
						<li><a href="/catalog/igrushki/dlya-devochek/">Для девочек</a></li>
						<li><a href="/catalog/igrushki/dlya-malchikov/">Для мальчиков</a></li>
					</ul>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="item-category item-category_2">
					<a href="/catalog/transport/" class="item-category__image">
						<img alt="Детский транспорт" src="/local/templates/ugtoiz/img/category2.png">
					</a>
					<h2><a href="/catalog/transport/" class="item-category__title">Детский транспорт</a></h2>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="row">
					<div class="col-md-6">
						<div class="item-category item-category_3">
							<a href="/catalog/kantstovary/" class="item-category__image">
								<img alt="Канцтовары" src="/local/templates/ugtoiz/img/category3.png">
							</a>
							<h2><a href="/catalog/kantstovary/" class="item-category__title">Канцтовары</a></h2>
						</div>
					</div>
					<div class="col-md-6">
						<div class="item-category item-category_4">
							<a href="/catalog/myagkie-igrushki/" class="item-category__image">
								<img alt="Мягкие игрушки" src="/local/templates/ugtoiz/img/category4.png">
							</a>
							<h2><a href="/catalog/myagkie-igrushki/" class="item-category__title">Мягкие игрушки</a></h2>
						</div>
					</div>
					<div class="col-md-12">
						<div class="item-category item-category_5">
							<a href="/catalog/sporttovary/" class="item-category__image">
								<img alt="Спорттовары" src="/local/templates/ugtoiz/img/category5.png">
							</a>
							<h2><a href="/catalog/sporttovary/" class="item-category__title">Спорттовары</a></h2>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-md-6">
				<div class="item-category item-category_6">
					<a href="/catalog/tovary-dlya-malyshey/" class="item-category__image">
						<img alt="Товары для малышей" src="/local/templates/ugtoiz/img/category6.png">
					</a>
					<h2><a href="/catalog/tovary-dlya-malyshey/" class="item-category__title">Товары для малышей</a></h2>
				</div>
			</div>
			<div class="col-lg-3 col-md-6">
				<div class="item-category item-category_7">
					<a href="/catalog/sezonnye-tovary/" class="item-category__image">
						<img alt="Сезонные товары" src="/local/templates/ugtoiz/img/category7.png">
					</a>
					<a href="/catalog/sezonnye-tovary/" class="item-category__title">Сезонные товары</a>
				</div>
				<div class="item-category item-category_8">
					<a href="/catalog/razvivayushchie-igry/" class="item-category__image">
						<img alt="Развивающие игры" src="/local/templates/ugtoiz/img/category8.png">
					</a>
					<h2><a href="/catalog/razvivayushchie-igry/" class="item-category__title">Развивающие игры</a></h2>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="catalog">
	<div class="container">
		<ul class="tabs">
			<li class="active"><a href="#tab1">ХИТ продаж</a></li>
			<li><a href="#tab2">Новинки</a></li>
			<li><a href="#tab3">Рекомендуем</a></li>
		</ul>
		<div class="tab-container">
			<div class="tab-pane" id="tab1">
				<?$APPLICATION->IncludeComponent("bitrix:catalog.section", "sliders", $_catalog_param, false, ['HIDE_ICONS' => 'Y']);?>
			</div>
			<div class="tab-pane" id="tab2">
				<?$APPLICATION->IncludeComponent("bitrix:catalog.section", "sliders", $_catalog_param, false, ['HIDE_ICONS' => 'Y']);?>
			</div>
			<div class="tab-pane" id="tab3">
				<?$APPLICATION->IncludeComponent("bitrix:catalog.section", "sliders", $_catalog_param, false, ['HIDE_ICONS' => 'Y']);?>
]			</div>
		</div>
	</div>
</section>

<?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/forms/partnership.php"], false, ['HIDE_ICONS' => 'Y']);?>

<section class="popular">
	<div class="container">
		<h2 class="title-section">Популярные детские товары</h2>
		<ul class="tabs">
			<li class="active"><a href="#tab_popular1">детям до года</a></li>
			<li><a href="#tab_popular2">товары на праздники</a></li>
			<li><a href="#tab_popular3">новогодние подарки</a></li>
			<li><a href="#tab_popular4">трендовые игрушки</a></li>
			<li><a href="#tab_popular5">к 23 февраля</a></li>
		</ul>
		<div class="tab-container">
			<div class="tab-pane" id="tab_popular1">
				<?$APPLICATION->IncludeComponent("bitrix:catalog.section", "sliders", $_catalog_param, false, ['HIDE_ICONS' => 'Y']);?>
			</div>
			<div class="tab-pane" id="tab_popular2">
				<?$APPLICATION->IncludeComponent("bitrix:catalog.section", "sliders", $_catalog_param, false, ['HIDE_ICONS' => 'Y']);?>
			</div>
			<div class="tab-pane" id="tab_popular3">
				<?$APPLICATION->IncludeComponent("bitrix:catalog.section", "sliders", $_catalog_param, false, ['HIDE_ICONS' => 'Y']);?>
			</div>
			<div class="tab-pane" id="tab_popular4">
				<?$APPLICATION->IncludeComponent("bitrix:catalog.section", "sliders", $_catalog_param, false, ['HIDE_ICONS' => 'Y']);?>
			</div>
			<div class="tab-pane" id="tab_popular5">
				<?$APPLICATION->IncludeComponent("bitrix:catalog.section", "sliders", $_catalog_param, false, ['HIDE_ICONS' => 'Y']);?>
			</div>
		</div>
	</div>
</section>

<?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/forms/advantages.php"], false, ['HIDE_ICONS' => 'Y']);?>

<?$APPLICATION->IncludeComponent(
	"bitrix:news.list", 
	"main_clients",
	array(
		"IBLOCK_ID" => "6",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "86400", // С-2 (2026-11-19): новый ключ кеша, чтобы применились alt брендов
		"NEWS_COUNT" => "999",
		"SORT_BY1" => "SORT",
		"SORT_ORDER1" => "ASC",
		"PROPERTY_CODE" => array("LINK"),
		"SET_TITLE" => "N",
		"SET_BROWSER_TITLE" => "N",
	),
	false,
	array('HIDE_ICONS' => 'Y')
);?>

<?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/forms/main_faq.php"], false, ['HIDE_ICONS' => 'Y']);?>

<?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/forms/main_contacts.php"], false, ['HIDE_ICONS' => 'Y']);?>

<?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/forms/subscribe.php"], false, ['HIDE_ICONS' => 'Y']);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>