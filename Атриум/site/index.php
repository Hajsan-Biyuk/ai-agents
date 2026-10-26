<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");


use Bitrix\Main\Page\Asset;


Asset::getInstance()->addCss("/local/templates/aspro_max/vendor/css/carousel/swiper/swiper-bundle.min.css");
Asset::getInstance()->addCss("/local/templates/aspro_max/vendor/css/carousel/owl/owl.carousel.min.css");

Asset::getInstance()->addCss("/local/templates/aspro_max/js/countdown/jquery.countdown.css");


Asset::getInstance()->addJs("/local/templates/aspro_max/vendor/js/carousel/swiper/swiper-bundle.min.js");
Asset::getInstance()->addJs("/local/templates/aspro_max/vendor/js/carousel/owl/owl.carousel.min.js");

Asset::getInstance()->addJs("/local/templates/aspro_max/js/countdown/jquery.plugin.js");
Asset::getInstance()->addJs("/local/templates/aspro_max/js/countdown/jquery.countdown.min.js");
Asset::getInstance()->addJs("/local/templates/aspro_max/js/countdown/jquery.countdown-ru.js");

Asset::getInstance()->addJs("/local/templates/aspro_max/js/mask/jquery.maskedinput.js");

$APPLICATION->SetPageProperty("title", "Плитка и сантехника в Симферополе — купить в ATRIUM");
$APPLICATION->SetPageProperty("description", "Купить плитку, керамогранит и сантехнику в Симферополе: ванны, мебель для ванной, смесители. Салон ATRIUM, цены, доставка по Крыму.");
?>
<div itemscope="" itemtype="http://schema.org/LocalBusiness" style="display:none;">
    <p itemprop="name">
        АТРИУМ
    </p>
    <p itemprop="description">
        Оптово-розничная компания, которая более 10 лет реализует мебель, сантехнику и отделочные материалы для ванной комнаты. Мы напрямую сотрудничаем с заводами-производителями и являемся официальным дилером таких уважаемых брендов как AVANTI, RAVAK, FIXSEN и т.д. Крепкие партнерские отношения с производителями позволили оптимизировать цены – наш товар доступен клиентам с любым доступным бюджетом. Разноплановый ассортимент от эконом до премиум класса сделал магазин АТРИУМ привлекательным для всех сегментов целевой аудитории. А возможность доставки в каждый регион России позволил значительно расшить клиентскую базу.
    </p>
    <a itemprop="email" href="mailto:atriumceramica@yandex.ru">atriumceramica@yandex.ru</a> 
    <img src="https://atriumceramic.ru/upload/CMax/576/a3ct5wwthm2r4las3hnfz22yxjvq6uid.svg" itemprop="logo image" alt="Атриум" title="атриум" style="display:none;">
    <span itemprop="openingHours" content="Mo-Fri 9:00−19:00 Sat 9:00−18:00 Sun 9:00−17:00">Пн – Пт: с 9:00 до 19:00 Сб: с 9:00 до 18:00 Вс: с 9:00 до 17:00</span> 
    <span itemprop="faxNumber">+7 (978) 080-33-08</span>
    <div itemprop="address" itemscope="" itemtype="http://schema.org/PostalAddress">
        <span itemprop="postalCode">295050</span> <span itemprop="addressCountry">Россия</span> <span itemprop="addressLocality">Симферополь</span>, <span itemprop="streetAddress">Кечкеметская, 27, этаж 1</span>
    </div>
</div>

	<section class="section-billbord">
		<div class="maxwidth-theme">
		<?
		global $arrFilter;
		$arrFilter['SECTION_ID'] = 202;
		$APPLICATION->IncludeComponent(
			"bitrix:news.list",
			"main_slider",
			Array(
				"FIELD_CODE" => Array("NAME","PREVIEW_TEXT","PREVIEW_PICTURE","DETAIL_TEXT","DETAIL_PICTURE",""),
				"IBLOCK_ID" => "62",
				"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
				"NEWS_COUNT" => "10",
				"PROPERTY_CODE" => array("*"),
				"FILTER_NAME" => "arrFilter",
				"SET_BROWSER_TITLE" => "N",
				"SET_TITLE" => "N",
				"SORT_BY1" => "SORT",
				"SORT_ORDER1" => "ASC",
				"CACHE_TYPE" => "N",
				"CACHE_TIME" => "0",
				"CACHE_FILTER" => "N",
				"CACHE_GROUPS" => "N"
			),
			false,
			Array(
				'HIDE_ICONS' => 'N'
			)
		);
		$arrFilter = [];
		?>
		</div>
	</section>

	<section class="benefits">
		<div class="maxwidth-theme">
			<div class="benefits__grid">
				<a target="_blank" href="https://yandex.ru/maps/org/atrium/1233813108/reviews/?ll=34.103435%2C44.975355&z=16" class="benefits__item">
					<div class="benefits__icon">
						<img src="<?php echo SITE_TEMPLATE_PATH ?>/images/ic_benefit1.svg" alt="alt">
					</div>
					<div class="benefits__title">5,0 оценка <br>на Яндексе</div>
				</a>
				<a href="#" class="benefits__item" data-event="jqm" data-param-form_id="13" data-name="ask">
					<div class="benefits__icon">
						<img src="<?php echo SITE_TEMPLATE_PATH ?>/images/ic_benefit2.svg" alt="alt">
					</div>
					<div class="benefits__title">3D Дизайн Вашего помещения</div>
				</a>
				<a href="/catalog/" class="benefits__item">
					<div class="benefits__icon">
						<img src="<?php echo SITE_TEMPLATE_PATH ?>/images/ic_benefit3.svg" alt="alt">
					</div>
					<div class="benefits__title">>10 000 товаров в наличии</div>
				</a>
				<a href="/dostavka-i-oplata/" class="benefits__item">
					<div class="benefits__icon">
						<img src="<?php echo SITE_TEMPLATE_PATH ?>/images/ic_benefit4.svg" alt="alt">
					</div>
					<div class="benefits__title">Быстрая доставка и удобный самовывоз</div>
				</a>
			</div>
		</div>
	</section>

	<?
		global $arrFilter;
		$arrFilter['UF_SHOW_MAIN'] = true;
		$sectionListParams = array(
			"IBLOCK_ID" => 61,
			"FILTER_NAME" => "arrFilter",
			"SECTION_URL" => "/catalog/#SECTION_CODE_PATH#/",
			"SECTION_USER_FIELDS" => ["UF_*"],
		);
		$APPLICATION->IncludeComponent(
			"bitrix:catalog.section.list",
			"main_categories",
			$sectionListParams,
			false,
			array("HIDE_ICONS" => "Y")
		);
		$arrFilter = [];
	?>
	
	<section class="section-catalog">
		<div class="maxwidth-theme">
			<div class="head-section">
				<div class="head-section__left">
					<h2 class="title-section">Акции осени 2026</h2>
				</div>
				<a href="/catalog/aktsii/" class="link-page">Смотреть все акции</a>
			</div>
			<?
				include $_SERVER["DOCUMENT_ROOT"]."/catalog/_catalog_param.php";
				global $arrFilter;
				$arrFilter = [];
				$_catalog_param = array_merge($_catalog_param, array(
					"FILTER_NAME" => "arrFilter",
					"SET_TITLE" => "N", "SET_BROWSER_TITLE" => "N", "SET_META_DESCRIPTION" => "N", "SET_META_KEYWORDS" => "N",
					"PAGE_ELEMENT_COUNT" => 20,
					"SECTION_CODE" => "aktsii",
					"INCLUDE_SUBSECTIONS" => "Y",
					"ELEMENT_SORT_FIELD" => "DATE_CREATE",
					"ELEMENT_SORT_ORDER" => "DESC",
					"ELEMENT_SORT_FIELD2" => "ID",
					"ELEMENT_SORT_ORDER2" => "DESC",
				));
				$APPLICATION->IncludeComponent(
					"bitrix:catalog.section",
					"swiper-slide-product",
					$_catalog_param,
					false,
					array('HIDE_ICONS' => 'Y')
				);
				$arrFilter = [];
			?>
		</div>
	</section>

	<section class="section-banners">
		<div class="maxwidth-theme">
			<?$APPLICATION->IncludeComponent(
				"bitrix:news.list",
				"front-banners-sale",
				Array(
					"FIELD_CODE" => Array("NAME","PREVIEW_TEXT","PREVIEW_PICTURE","DETAIL_TEXT","DETAIL_PICTURE",""),
					"IBLOCK_ID" => "80",
					"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
					"NEWS_COUNT" => "2",
					"PROPERTY_CODE" => array("*"),
					"SET_BROWSER_TITLE" => "N",
					"SET_TITLE" => "N",
					"SORT_BY1" => "SORT",
					"SORT_ORDER1" => "ASC"
				),
				false,
				Array(
					'HIDE_ICONS' => 'N'
				)
			);?>
		</div>
	</section>

	<section class="section-brands">
		<div class="maxwidth-theme">
			<div class="head-section">
				<div class="head-section__left">
					<h2 class="title-section">Бренды</h2>
				</div>
				<a href="/info/brands/" class="link-page">Смотреть все бренды</a>
			</div>
			<?$APPLICATION->IncludeComponent(
				"bitrix:news.list",
				"front-brands-slider",
				Array(
					"FIELD_CODE" => Array("NAME","PREVIEW_TEXT","PREVIEW_PICTURE","DETAIL_TEXT","DETAIL_PICTURE",""),
					"IBLOCK_ID" => "68",
					"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
					"NEWS_COUNT" => "20",
					"PROPERTY_CODE" => array("*"),
					"SET_BROWSER_TITLE" => "N",
					"SET_TITLE" => "N",
					"CACHE_TYPE" => "N",
					"SORT_BY1" => "SORT",
					"SORT_ORDER1" => "ASC"
				),
				false,
				Array(
					'HIDE_ICONS' => 'N'
				)
			);?>
		</div>
	</section>

	<section class="section-catalog">
		<div class="maxwidth-theme">
			<div class="head-section">
				<div class="head-section__left">
					<h2 class="title-section">ТОП самых покупаемых товаров</h2>
					<?/*?><div class="descr-section">Рейтинг по спросу в салоне и онлайн — ориентир для быстрого выбора. Цены и наличие уточняйте в карточке товара</div><?*/?>
				</div>
				<a href="/catalog/top/" class="link-page">Все товары в каталоге</a>
			</div>
			<?
				include $_SERVER["DOCUMENT_ROOT"]."/catalog/_catalog_param.php";
				global $arrFilter;
				$arrFilter = [];
				$_catalog_param = array_merge($_catalog_param, array(
					"FILTER_NAME" => "arrFilter",
					"SET_TITLE" => "N",
					"SET_BROWSER_TITLE" => "N",
					"SET_META_DESCRIPTION" => "N",
					"SET_META_KEYWORDS" => "N",
					"PAGE_ELEMENT_COUNT" => 20,
					"SECTION_ID" => "1104",
					"SECTION_CODE" => "top",
					"INCLUDE_SUBSECTIONS" => "Y",
					"ELEMENT_SORT_FIELD" => "DATE_CREATE",
					"ELEMENT_SORT_ORDER" => "DESC",
					"ELEMENT_SORT_FIELD2" => "ID",
					"ELEMENT_SORT_ORDER2" => "DESC",
				));
				$APPLICATION->IncludeComponent(
					"bitrix:catalog.section",
					"swiper-slide-product",
					$_catalog_param,
					false,
					array('HIDE_ICONS' => 'Y')
				);
				$arrFilter = [];
			?>
		</div>
	</section>

	<section class="section-callback">
		<a href="/designers/" class="head-main">
			<div class="head-main__name">атриум</div>
			<h2 class="head-main__title">
				<svg width="44" height="44" viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M21.0851 26.8033C20.3499 27.5384 19.3104 27.7309 18.3773 27.4596C18.3168 27.5696 18.2618 27.6814 18.1683 27.7749L9.09328 36.8499C8.82561 37.1176 8.47361 37.2533 8.12161 37.2533C7.76961 37.2533 7.41761 37.1194 7.14995 36.8499C6.61278 36.3128 6.61278 35.4419 7.14995 34.9048L16.2249 25.8298C16.3184 25.7363 16.4303 25.6813 16.5403 25.6208C16.2689 24.6894 16.4633 23.6481 17.1966 22.9129C18.2709 21.8386 20.0126 21.8386 21.0851 22.9129C22.1576 23.9873 22.1594 25.7308 21.0851 26.8033Z" fill="#023420" /><path d="M37.6141 14.1644L29.8353 6.38553C27.8718 4.42203 24.6726 4.4202 22.6926 6.39836C21.7429 7.3462 21.2204 8.60753 21.2204 9.95136C21.2204 10.076 21.2479 10.1952 21.2571 10.318L10.0994 17.0115C8.88575 17.7412 8.02959 18.9567 7.75092 20.3464L4.98992 34.1569C4.71859 35.5117 5.14025 36.905 6.11742 37.8822C6.90025 38.665 7.94892 39.0904 9.03059 39.0904C9.30009 39.0904 9.57325 39.0647 9.84275 39.0097L23.6533 36.2487C25.0429 35.97 26.2566 35.1139 26.9863 33.8984L33.6798 22.7425C33.8026 22.7517 33.9218 22.7792 34.0464 22.7792C35.3884 22.7792 36.6498 22.2567 37.6123 21.2942C39.5776 19.3289 39.5794 16.1297 37.6141 14.1644ZM24.6304 32.483C24.2986 33.0349 23.7468 33.4254 23.1143 33.5519L9.30375 36.3129C8.83992 36.4027 8.39075 36.267 8.06075 35.937C7.73075 35.6052 7.59325 35.1542 7.68492 34.694L10.4459 20.8854C10.5724 20.2529 10.9629 19.701 11.5148 19.3692L22.2306 12.9415C22.3754 13.134 22.5184 13.3302 22.6926 13.5025L30.4971 21.307C30.6713 21.4812 30.8656 21.6242 31.0581 21.769L24.6304 32.483ZM35.6561 19.3619C34.7981 20.2217 33.3021 20.2217 32.4423 19.3619L24.6378 11.5574C24.2088 11.1284 23.9723 10.5582 23.9723 9.95136C23.9723 9.34453 24.2088 8.77253 24.6506 8.3307C25.0979 7.88336 25.6846 7.66153 26.2713 7.66153C26.8579 7.66153 27.4446 7.8852 27.8919 8.3307L35.6708 16.1095C36.5636 17.0024 36.5636 18.458 35.6579 19.3637L35.6561 19.3619Z" fill="#023420" /></svg>
				клуб дизайнеров и архитекторов
				<svg width="44" height="44" viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M38.6668 16.555L32.945 10.8332C32.1768 10.065 31.1135 9.625 30.0282 9.625H12.375V5.5C12.375 4.741 11.759 4.125 11 4.125C10.241 4.125 9.625 4.741 9.625 5.5V9.625H5.5C4.741 9.625 4.125 10.241 4.125 11C4.125 11.759 4.741 12.375 5.5 12.375H9.625V35.75C9.625 38.0233 11.4748 39.875 13.75 39.875H35.75C38.0252 39.875 39.875 38.0233 39.875 35.75V19.4718C39.875 18.3865 39.435 17.3232 38.6668 16.555ZM36.7217 18.5002C36.806 18.5845 36.8573 18.6908 36.916 18.7917H32.0833C31.3243 18.7917 30.7083 18.1757 30.7083 17.4167V12.584C30.8092 12.6427 30.9173 12.694 30.9998 12.7783L36.7217 18.5002ZM35.75 37.125H13.75C12.991 37.125 12.375 36.5072 12.375 35.75V12.375H27.9583V17.4167C27.9583 19.6918 29.8082 21.5417 32.0833 21.5417H37.125V35.75C37.125 36.5072 36.509 37.125 35.75 37.125Z" fill="#023420" /></svg>
			</h2>
			<div class="head-main__name">атриум</div>
		</a>
		<div class="maxwidth-theme">
			<div class="section-callback__grid">
				<div class="section-callback__content">
					<h2 class="title-section">Посмотрите на свою ванную до покупки — закажите 3D-дизайн</h2>
					<p>Спланируем расстановку сантехники и мебели, подберём отделку и аксессуары под ваш метраж и стиль. Вы получите понятную визуализацию и список позиций для заказа — меньше сомнений и переделок на ремонте.</p>
					<ul>
						<li>Подбор под ваш бюджет и площадь</li>
						<li>Учёт коммуникаций и привычек семьи</li>
						<li>Связка с ассортиментом АТРИУМ — закажете то, что увидели на эскизе</li>
					</ul>
					<a href="#" class="link-page">Задать вопрос по услуге</a>
				</div>
				<div class="form-page">
					<div class="form-page__picture">
						<img src="<?php echo SITE_TEMPLATE_PATH ?>/images/img_callback.png" alt="alt">
					</div>
					<div class="form-page__title">Заявка на 3D-дизайн</div>
					<div class="form-page__descr">Оставьте контакты и кратко опишите задачу — специалист перезвонит, уточнит метраж и пожелания</div>
					<?$APPLICATION->IncludeComponent(
						"bitrix:form",
						"inline_13",
						Array(
							"AJAX_MODE" => "N",
							"AJAX_OPTION_ADDITIONAL" => "",
							"AJAX_OPTION_HISTORY" => "N",
							"AJAX_OPTION_JUMP" => "N",
							"AJAX_OPTION_STYLE" => "Y",
							"CACHE_TIME" => "3600",
							"CACHE_TYPE" => "A",
							"CHAIN_ITEM_LINK" => "",
							"CHAIN_ITEM_TEXT" => "",
							"COMPONENT_TEMPLATE" => "inline",
							"EDIT_ADDITIONAL" => "N",
							"EDIT_STATUS" => "Y",
							"IGNORE_CUSTOM_TEMPLATE" => "N",
							"NAME_TEMPLATE" => "",
							"NOT_SHOW_FILTER" => array(0=>"",1=>"",),
							"NOT_SHOW_TABLE" => array(0=>"",1=>"",),
							"RESULT_ID" => $_REQUEST['RESULT_ID'],
							"SEF_MODE" => "N",
							"SHOW_ADDITIONAL" => "N",
							"SHOW_ANSWER_VALUE" => "N",
							"SHOW_EDIT_PAGE" => "Y",
							"SHOW_LIST_PAGE" => "Y",
							"SHOW_STATUS" => "Y",
							"SHOW_VIEW_PAGE" => "Y",
							"START_PAGE" => "new",
							"SUCCESS_URL" => "",
							"USE_EXTENDED_ERRORS" => "N",
							"VARIABLE_ALIASES" => array("action"=>"action",),
							"WEB_FORM_ID" => "13"
						)
					);?>
				</div>
			</div>
		</div>
	</section>

	<section class="section-about">
		<div class="maxwidth-theme">
			<div class="section-about__grid">
				<div class="section-about__content">
					<h2 class="title-section">АТРИУМ — опт, розница и салон в одном месте</h2>
					<p>Мы более 10 лет продаём мебель, сантехнику и отделочные материалы для ванной комнаты и смежных задач. Сотрудничаем с заводами напрямую, поэтому предлагаем честные цены и разный уровень бюджета — от доступных решений до премиум-сегмента. </p>
					<p>Заказывайте с доставкой по Крыму или приезжайте в салон: посмотрите образцы плитки, сантехники, дверей и напольных покрытий в Симферополе.</p>
					<h3 class="title-middle">Салон АТРИУМ в Симферополе</h3>
					<p>Полноценное пространство с выставкой — удобно сравнить фактуры и комплектацию до покупки.</p>
					<div class="section-about__buttons">
						<a href="#" class="btn-main btn-main--page" data-event="jqm" data-param-form_id="FEEDBACK" data-name="callback">Оставить заявку</a>
						<a href="#" class="btn-main" data-event="jqm" data-param-form_id="CALLBACK" data-name="callback">Позвонить в салон</a>
					</div>
					<a href="https://www.youtube.com/embed/E8d_JvMpoY4" class="video-btn" data-fancybox>
						<svg width="55" height="55" viewBox="0 0 55 55" fill="none" xmlns="http://www.w3.org/2000/svg"><mask id="mask0_537_2191" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="0" y="0" width="55" height="55"><path d="M54.5675 0H0V54.5675H54.5675V0Z" fill="white" /></mask><g mask="url(#mask0_537_2191)"><path d="M27.2837 54.5675C42.3525 54.5675 54.5675 42.3525 54.5675 27.2837C54.5675 12.2154 42.3525 0 27.2837 0C12.2154 0 0 12.2154 0 27.2837C0 42.3525 12.2154 54.5675 27.2837 54.5675Z" fill="#2B5840" /><path d="M35.7549 25.3652L24.2892 17.503C23.7744 17.1286 23.2128 16.9414 22.7916 16.9414C21.9024 16.9414 21.3877 17.6434 21.3877 18.7665V35.7547C21.3877 36.9243 21.9024 37.58 22.7448 37.58C23.2128 37.58 23.7276 37.3927 24.2424 37.018L35.7076 29.1559C36.457 28.6411 36.8781 27.986 36.8781 27.2371C36.8781 26.4883 36.5034 25.88 35.7549 25.3652Z" fill="white" /></g></svg>
					</a>
				</div>
				<?$ElementID = $APPLICATION->IncludeComponent(
					"bitrix:news.detail",
					"ruletka",
					Array(
						"CACHE_TYPE" => "N",
						"DISPLAY_DATE" => "Y",
						"DISPLAY_NAME" => "Y",
						"DISPLAY_PICTURE" => "Y",
						"DISPLAY_PREVIEW_TEXT" => "Y",
						"IBLOCK_TYPE" => "news",
						"IBLOCK_ID" => 51,
						"ELEMENT_ID" => 758,
						"PROPERTY_CODE" => ["*"],
						"SET_TITLE" => "N",
						"ADD_SECTIONS_CHAIN" => "N",
						"ACTIVE_DATE_FORMAT" => "d.m.Y",
						"CACHE_TYPE" => "N",
						"CACHE_TIME" => "36000000",
						"CACHE_GROUPS" => "Y",
						"ADD_ELEMENT_CHAIN" => "N",
						"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
					),
					false,
					['HIDE_ICONS' => 'Y']
				);?>
			</div>
		</div>
	</section>
	
	<section class="section-news">
		<div class="maxwidth-theme">
			<div class="section-news__head">
				<h2 class="title-section">Новости</h2>
				<p class="descr-section section-news__descr">Следите за обновлениями, акциями и событиями салона АТРИУМ</p>
			</div>
			<?$APPLICATION->IncludeComponent(
				"bitrix:news.list",
				"front-news",
				Array(
					"FIELD_CODE" => Array("NAME","PREVIEW_TEXT","PREVIEW_PICTURE","DETAIL_TEXT","DETAIL_PICTURE",""),
					"IBLOCK_ID" => "1",
					"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
					"NEWS_COUNT" => "4",
					"PROPERTY_CODE" => array("*"),
					"SET_BROWSER_TITLE" => "N",
					"SET_TITLE" => "N",
					"SORT_BY1" => "ACTIVE_FROM",
					"SORT_ORDER1" => "DESC"
				),
				false,
				Array(
					'HIDE_ICONS' => 'N'
				)
			);?>
		</div>
	</section>
	
	<section class="section-contacts">
		<div class="maxwidth-theme">
			<div class="section-contacts__grid">
				<div class="section-contacts__content">
					<h2 class="title-section">Контакты</h2>
					<p>Позвоните, напишите или приезжайте в салон — поможем с подбором и заказом</p>
					<div class="title-middle">Связь с АТРИУМ</div>
					<p>
						<strong>Адрес:</strong> Республика Крым, г. Симферополь, ул. Кечкеметская, 27, этаж 1
						<br>
						<strong>Режим:</strong> Пн–Пт 9:00–19:00 · Сб 9:00–18:00 · Вс 9:00–17:00
					</p>
					<p>
						<strong>Телефоны:</strong>
						<a href="tel:+79780803308"> +7 (978) 080-33-08</a>
						<a href="tel:+79780765559">, +7 (978) 076-55-59</a>
					</p>
					<p>
						<strong>Email:</strong>
						<a href="mailto:atriumceramica@yandex.ru"> atriumceramica@yandex.ru</a>
					</p>
				</div>
				<div id="map-contacts"></div>
			</div>
		</div>
	</section>

	<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU"></script>
	<script>
		$(document).ready(function () {

			$(".input-phone").mask("+7 (999) 999-99-99");

			
			ymaps.ready(init);

			function init() {
				var myMap = new ymaps.Map('map-contacts', {
					center: [44.975355, 34.103435],
					zoom: 15
				}, {
					searchControlProvider: 'yandex#search'
				});

				myPlacemark = new ymaps.Placemark([44.975355, 34.103435], {
				}, {
					iconLayout: 'default#image',
					iconImageHref: '<?php echo SITE_TEMPLATE_PATH ?>/images/ic_pin.svg',
					hideIconOnBalloonOpen: false,
					iconImageSize: [59, 59],
					iconImageOffset: [-30, -59],
					balloonOffset: [0, -59]
				});
				myMap.controls.remove('searchControl');
				myMap.behaviors.disable('scrollZoom');
				myMap.geoObjects.add(myPlacemark);
			}
		});
	</script>

<script>BX.setJSList(["\/local\/templates\/aspro_max\/js\/magnific.js","\/local\/templates\/aspro_max\/js\/chosen.jquery.min.js","\/local\/templates\/aspro_max\/js\/slick.js","\/local\/templates\/aspro_max\/js\/typed.js-master\/lib\/typed.js","\/local\/templates\/aspro_max\/js\/observer.js","\/local\/templates\/aspro_max\/js\/jquery.actual.min.js","\/local\/templates\/aspro_max\/js\/jqModal.js","\/local\/templates\/aspro_max\/vendor\/js\/bootstrap.js","\/local\/templates\/aspro_max\/vendor\/js\/jquery.appear.js","\/local\/templates\/aspro_max\/vendor\/js\/ripple.js","\/local\/templates\/aspro_max\/vendor\/js\/velocity\/velocity.js","\/local\/templates\/aspro_max\/vendor\/js\/velocity\/velocity.ui.js","\/local\/templates\/aspro_max\/js\/browser.js","\/local\/templates\/aspro_max\/js\/jquery.uniform.min.js","\/local\/templates\/aspro_max\/vendor\/js\/sticky-sidebar.js","\/local\/templates\/aspro_max\/js\/jquery.validate.min.js","\/local\/templates\/aspro_max\/js\/jquery.inputmask.bundle.min.js","\/local\/templates\/aspro_max\/js\/jquery.easing.1.3.js","\/local\/templates\/aspro_max\/js\/equalize.min.js","\/local\/templates\/aspro_max\/js\/jquery.alphanumeric.js","\/local\/templates\/aspro_max\/js\/jquery.cookie.js","\/local\/templates\/aspro_max\/js\/jquery.plugin.min.js","\/local\/templates\/aspro_max\/js\/jquery.countdown.min.js","\/local\/templates\/aspro_max\/js\/jquery.countdown-ru.js","\/local\/templates\/aspro_max\/js\/jquery.ikSelect.js","\/local\/templates\/aspro_max\/js\/jquery.mobile.custom.touch.min.js","\/local\/templates\/aspro_max\/js\/jquery.dotdotdot.js","\/local\/templates\/aspro_max\/js\/rating_likes.js","\/local\/templates\/aspro_max\/js\/buy_services.js","\/local\/templates\/aspro_max\/js\/mobile.js","\/local\/templates\/aspro_max\/js\/jquery.mousewheel.min.js","\/local\/templates\/aspro_max\/js\/jquery.mCustomScrollbar.js","\/local\/templates\/aspro_max\/js\/scrollTabs.js","\/local\/templates\/aspro_max\/js\/main.js","\/local\/templates\/aspro_max\/js\/blocks\/blocks.js","\/bitrix\/components\/bitrix\/search.title\/script.js","\/local\/templates\/aspro_max\/components\/bitrix\/search.title\/mega_menu\/script.js","\/local\/templates\/aspro_max\/components\/bitrix\/news.list\/banners\/script.js","\/local\/templates\/aspro_max\/components\/bitrix\/menu\/top\/script.js","\/bitrix\/components\/arturgolubev\/search.title\/script.js","\/local\/templates\/aspro_max\/components\/arturgolubev\/search.title\/.default\/script.js","\/local\/templates\/aspro_max\/js\/slider.swiper.min.js","\/local\/templates\/aspro_max\/vendor\/js\/carousel\/swiper\/swiper-bundle.min.js","\/bitrix\/components\/aspro\/com.banners.max\/common_files\/js\/script.min.js","\/local\/templates\/aspro_max\/vendor\/js\/carousel\/owl\/owl.carousel.min.js","\/local\/templates\/aspro_max\/components\/bitrix\/news.list\/front_news\/script.js","\/bitrix\/components\/aspro\/marketing.popup.max\/templates\/.default\/script.js","\/local\/templates\/aspro_max\/components\/bitrix\/search.title\/fixed\/script.js","\/local\/templates\/aspro_max\/js\/custom.js","\/local\/templates\/aspro_max\/components\/aspro\/regionality.list.max\/popup_regions\/script.js"]);</script>
<script>BX.setCSSList(["\/local\/templates\/aspro_max\/css\/common.css","\/local\/templates\/aspro_max\/css\/chosen.min.css","\/local\/templates\/aspro_max\/css\/iq.css","\/local\/templates\/aspro_max\/css\/fonts\/montserrat\/css\/montserrat.min.css","\/local\/templates\/aspro_max\/css\/blocks\/dark-light-theme.css","\/local\/templates\/aspro_max\/css\/colored.css","\/local\/templates\/aspro_max\/vendor\/css\/bootstrap.css","\/local\/templates\/aspro_max\/css\/styles.css","\/local\/templates\/aspro_max\/css\/blocks\/blocks.css","\/local\/templates\/aspro_max\/css\/blocks\/common.blocks\/counter-state\/counter-state.css","\/local\/templates\/aspro_max\/css\/banners.css","\/local\/templates\/aspro_max\/css\/menu.css","\/local\/templates\/aspro_max\/css\/jquery.mCustomScrollbar.min.css","\/local\/templates\/aspro_max\/vendor\/css\/ripple.css","\/local\/templates\/aspro_max\/css\/left_block_main_page.css","\/local\/templates\/aspro_max\/css\/stores.css","\/local\/templates\/aspro_max\/css\/yandex_map.css","\/local\/templates\/aspro_max\/css\/buy_services.css","\/local\/templates\/aspro_max\/css\/header_fixed.css","\/local\/templates\/aspro_max\/ajax\/ajax.css","\/local\/templates\/aspro_max\/components\/arturgolubev\/search.title\/.default\/style.css","\/local\/templates\/aspro_max\/components\/aspro\/com.banners.max\/top_big_banner_2\/style.css","\/local\/templates\/aspro_max\/vendor\/css\/carousel\/swiper\/swiper-bundle.min.css","\/local\/templates\/aspro_max\/css\/slider.swiper.min.css","\/local\/templates\/aspro_max\/css\/main_slider.min.css","\/local\/templates\/aspro_max\/components\/bitrix\/news.list\/front_brands_slider\/style.css","\/local\/templates\/aspro_max\/vendor\/css\/carousel\/owl\/owl.carousel.min.css","\/local\/templates\/aspro_max\/vendor\/css\/carousel\/owl\/owl.theme.default.min.css","\/local\/templates\/aspro_max\/components\/bitrix\/news.list\/front_sale2\/style.css","\/local\/templates\/aspro_max\/components\/bitrix\/news.list\/front_news\/style.css","\/local\/templates\/aspro_max\/css\/footer.css","\/bitrix\/components\/aspro\/marketing.popup.max\/templates\/.default\/style.css","\/local\/templates\/aspro_max\/components\/bitrix\/search.title\/fixed\/style.css","\/local\/templates\/aspro_max\/styles.css","\/local\/templates\/aspro_max\/template_styles.css","\/local\/templates\/aspro_max\/css\/header.css","\/local\/templates\/aspro_max\/css\/media.css","\/local\/templates\/aspro_max\/css\/h1-normal.css","\/local\/templates\/aspro_max\/themes\/custom_s1\/theme.css","\/local\/templates\/aspro_max\/css\/widths\/width-3.css","\/local\/templates\/aspro_max\/css\/fonts\/font-10.css","\/local\/templates\/aspro_max\/css\/custom.css","\/local\/templates\/aspro_max\/components\/bitrix\/catalog.compare.list\/compare_top\/style.css"]);</script>



<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>