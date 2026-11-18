<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * Новая главная (редизайн 2026).
 * Подключается из /index.php (развилка по SK_NEW), старый вывод главной при флаге не выполняется.
 * Форма расчёта — компонент iblock.element.add.form (инфоблок 15) с параметрами как в /index_inc.php.
 */
global $APPLICATION;
$skList = function ($iblock, $view, $count, array $extra = array()) {
	$GLOBALS['APPLICATION']->IncludeComponent('bitrix:news.list', 'sk', array_merge(array(
		'IBLOCK_TYPE' => '', 'IBLOCK_ID' => $iblock, 'NEWS_COUNT' => $count,
		'SORT_BY1' => 'SORT', 'SORT_ORDER1' => 'ASC', 'SORT_BY2' => 'ID', 'SORT_ORDER2' => 'DESC',
		'FIELD_CODE' => array(), 'PROPERTY_CODE' => array('OBJECT', 'ADRESS'), 'CHECK_DATES' => 'Y',
		'CACHE_TYPE' => 'A', 'CACHE_TIME' => '86400', 'CACHE_GROUPS' => 'N',
		'SET_TITLE' => 'N', 'SET_BROWSER_TITLE' => 'N', 'SET_META_KEYWORDS' => 'N', 'SET_META_DESCRIPTION' => 'N',
		'SET_LAST_MODIFIED' => 'N', 'SET_STATUS_404' => 'N', 'INCLUDE_IBLOCK_INTO_CHAIN' => 'N', 'ADD_SECTIONS_CHAIN' => 'N',
		'DISPLAY_TOP_PAGER' => 'N', 'DISPLAY_BOTTOM_PAGER' => 'N', 'PREVIEW_TRUNCATE_LEN' => '',
		'SK_VIEW' => $view,
	), $extra), false, array('HIDE_ICONS' => 'Y'));
};
$skGoods = function ($sticker) {
	$GLOBALS['skGoodsFilter'] = array('PROPERTY_STIKER_VALUE' => $sticker);
	$GLOBALS['APPLICATION']->IncludeComponent('bitrix:catalog.section', 'sk_goods', array(
		'IBLOCK_TYPE' => 'catalog', 'IBLOCK_ID' => '17', 'SECTION_ID' => '', 'SECTION_CODE' => '',
		'INCLUDE_SUBSECTIONS' => 'Y', 'SHOW_ALL_WO_SECTION' => 'Y', 'FILTER_NAME' => 'skGoodsFilter',
		'ELEMENT_SORT_FIELD' => 'id', 'ELEMENT_SORT_ORDER' => 'desc', 'ELEMENT_SORT_FIELD2' => 'id', 'ELEMENT_SORT_ORDER2' => 'desc',
		'PROPERTY_CODE' => array('BREND', 'MEASURE', 'NALICHIE', 'STIKER', 'OLD_PRICE'),
		'PAGE_ELEMENT_COUNT' => '12', 'PRICE_CODE' => array('BASE'), 'USE_PRICE_COUNT' => 'N', 'PRICE_VAT_INCLUDE' => 'N',
		'CACHE_TYPE' => 'A', 'CACHE_TIME' => '36000', 'CACHE_FILTER' => 'Y', 'CACHE_GROUPS' => 'N',
		'SET_TITLE' => 'N', 'SET_BROWSER_TITLE' => 'N', 'SET_META_KEYWORDS' => 'N', 'SET_META_DESCRIPTION' => 'N',
		'SET_STATUS_404' => 'N', 'ADD_SECTIONS_CHAIN' => 'N', 'DISPLAY_TOP_PAGER' => 'N', 'DISPLAY_BOTTOM_PAGER' => 'N',
		'BROWSER_TITLE' => '-', 'COMPATIBLE_MODE' => 'Y', 'SEF_MODE' => 'N',
	), false, array('HIDE_ICONS' => 'Y'));
};
$tabs = array('sale' => 'Акция', 'recommend' => 'Рекомендуем', 'hit' => 'Хит', 'new' => 'Новинка');
$tabNames = array('sale' => 'Акции', 'recommend' => 'Рекомендуем', 'hit' => 'Хит', 'new' => 'Новинка');
?>
<main class="sk-scope sk-main">

	<section class="sk-hero">
		<img class="sk-hero__bg" src="<?= SK_PATH ?>/img/hero.webp" alt="" width="1672" height="834" fetchpriority="high">
		<div class="sk-container sk-hero__inner">
			<div class="sk-hero__content">
				<div class="sk-hero__top">
					<div class="sk-hero__badge"><img src="<?= SK_PATH ?>/img/ic-badge.svg" alt="" width="24" height="24">СтройКровля · Ставрополь</div>
					<div class="sk-hero__text">
						<p class="sk-hero__title">Кровля под ваш дом — без ошибок в комплектации</p>
						<p class="sk-hero__descr">Подберём материал под климат и бюджет, сравним варианты и соберём полную комплектацию объекта — от покрытия до водостока</p>
					</div>
					<ul class="sk-hero__chips">
						<li class="sk-hero__chip">Экспертный подбор</li>
						<li class="sk-hero__chip">Бесплатный замер</li>
						<li class="sk-hero__chip">Гарантия до 50 лет</li>
					</ul>
				</div>
				<div class="sk-hero__bottom">
					<div class="sk-hero__btns">
						<a class="sk-btn sk-btn--wide sk-js-scroll" href="#sk-calc">Получить расчёт за 15 минут</a>
						<a class="sk-btn sk-btn--glass" href="tel:+78652334041" onclick="if(window.ym)ym(38770565,'reachGoal','Perehod_po_nomeru_telefona');">Позвонить</a>
					</div>
					<p class="sk-hero__facts"><b>16 лет</b> на рынке <b>·</b> <b>150+</b> видов материалов <b>·</b> <b>0 ₽</b> замер по краю</p>
				</div>
			</div>

			<div class="sk-hero__form" id="sk-calc">
				<?php $APPLICATION->IncludeComponent('bitrix:iblock.element.add.form', 'sk_order', array(
					'IBLOCK_TYPE' => 'forms', 'IBLOCK_ID' => '15', 'GROUPS' => array('1', '2'), 'STATUS' => 'ANY', 'STATUS_NEW' => 'N',
					'ELEMENT_ASSOC' => 'CREATED_BY', 'LEVEL_LAST' => 'Y', 'LIST_URL' => '', 'SEF_MODE' => 'N', 'USE_CAPTCHA' => 'N',
					'PROPERTY_CODES' => array('45', '46', '47', '270', 'NAME'), 'PROPERTY_CODES_REQUIRED' => array('47', '270', 'NAME'),
					'CUSTOM_TITLE_NAME' => 'Как к вам обращаться', 'MAX_USER_ENTRIES' => '100000', 'MAX_LEVELS' => '100000', 'MAX_FILE_SIZE' => '0',
					'DEFAULT_INPUT_SIZE' => '30', 'RESIZE_IMAGES' => 'N', 'PREVIEW_TEXT_USE_HTML_EDITOR' => 'N', 'DETAIL_TEXT_USE_HTML_EDITOR' => 'N',
					'USER_MESSAGE_ADD' => 'Спасибо, ваша заявка принята.', 'USER_MESSAGE_EDIT' => '',
				), false, array('HIDE_ICONS' => 'Y')); ?>
			</div>
		</div>
	</section>

	<section class="sk-section sk-about">
		<div class="sk-container sk-about__inner">
			<div class="sk-about__text">
				<h2 class="sk-h2"><?php $APPLICATION->IncludeFile(SITE_DIR . 'include/stats-title.php', array(), array('MODE' => 'text')); ?></h2>
				<div class="sk-about__descr"><p><?php $APPLICATION->IncludeFile(SITE_DIR . 'include/stats-text.php', array(), array('MODE' => 'text')); ?></p></div>
			</div>
			<?php $skList(16, 'stats', 4); ?>
		</div>
	</section>

	<section class="sk-section sk-section--gray sk-catalog sk-js-tabs">
		<div class="sk-container">
			<div class="sk-section__head">
				<h2 class="sk-h3">Каталог</h2>
				<div class="sk-tabs" role="tablist">
					<button class="sk-tabs__btn is-active" type="button" role="tab" aria-selected="true" aria-controls="sk-cat-materials" id="sk-cat-materials-tab">По материалам</button>
					<button class="sk-tabs__btn" type="button" role="tab" aria-selected="false" aria-controls="sk-cat-brands" id="sk-cat-brands-tab">По брендам</button>
				</div>
			</div>
			<div class="sk-tabs__panel is-active" id="sk-cat-materials" role="tabpanel" aria-labelledby="sk-cat-materials-tab">
				<?php $APPLICATION->IncludeComponent('bitrix:catalog.section.list', 'sk_cats', array(
					'IBLOCK_TYPE' => 'catalog', 'IBLOCK_ID' => '17', 'SECTION_ID' => '', 'SECTION_CODE' => '', 'COUNT_ELEMENTS' => 'N',
					'TOP_DEPTH' => '2', 'SECTION_FIELDS' => array(), 'SECTION_USER_FIELDS' => array(), 'SECTION_URL' => '',
					'CACHE_TYPE' => 'A', 'CACHE_TIME' => '86400', 'CACHE_GROUPS' => 'N', 'ADD_SECTIONS_CHAIN' => 'N',
				), false, array('HIDE_ICONS' => 'Y')); ?>
			</div>
			<div class="sk-tabs__panel" id="sk-cat-brands" role="tabpanel" aria-labelledby="sk-cat-brands-tab" hidden>
				<?php $skList(14, 'brands', 19); ?>
			</div>
		</div>
	</section>

	<section class="sk-section sk-section--dark sk-steps">
		<div class="sk-container">
			<div class="sk-steps__head">
				<h2 class="sk-h2"><?php $APPLICATION->IncludeFile(SITE_DIR . 'include/stage-title.php', array(), array('MODE' => 'text')); ?></h2>
				<p class="sk-lead"><?php $APPLICATION->IncludeFile(SITE_DIR . 'include/stage-text.php', array(), array('MODE' => 'text')); ?></p>
			</div>
			<?php $skList(18, 'stages', 4); ?>
			<a class="sk-btn sk-btn--wide" data-fancybox data-type="ajax" data-src="/include/order-call.php" href="javascript:;" onclick="if(window.ym)ym(38770565,'reachGoal','Poluchit_konsultaciyu');">Получить консультацию</a>
		</div>
	</section>

	<section class="sk-section sk-section--gray sk-products sk-js-tabs">
		<div class="sk-container">
			<div class="sk-section__head">
				<h2 class="sk-h3">Каталог</h2>
				<div class="sk-tabs" role="tablist">
					<?php $first = true; foreach ($tabNames as $k => $name): ?>
					<button class="sk-tabs__btn<?= $first ? ' is-active' : '' ?>" type="button" role="tab" aria-selected="<?= $first ? 'true' : 'false' ?>" aria-controls="sk-prod-<?= $k ?>" id="sk-prod-<?= $k ?>-tab"><?= $name ?></button>
					<?php $first = false; endforeach; ?>
				</div>
			</div>
			<?php $first = true; foreach ($tabs as $k => $sticker): ?>
			<div class="sk-tabs__panel<?= $first ? ' is-active' : '' ?>" id="sk-prod-<?= $k ?>" role="tabpanel" aria-labelledby="sk-prod-<?= $k ?>-tab"<?= $first ? '' : ' hidden' ?>>
				<?php $skGoods($sticker); ?>
			</div>
			<?php $first = false; endforeach; ?>
		</div>
	</section>

	<section class="sk-section sk-objects">
		<div class="sk-container">
			<div class="sk-objects__head">
				<h2 class="sk-h2">Наши объекты</h2>
				<p class="sk-lead">За 12 лет работы на рынке кровельных материалов нашей компанией было выполненно более 100 проектов различной сложности, смонтировано более 4 тысяч кв.м. кровли.</p>
			</div>
			<?php $skList(11, 'portfolio', 5); ?>
		</div>
	</section>

	<section class="sk-section sk-section--gray sk-seo">
		<div class="sk-container sk-seo__inner">
			<div class="sk-seo__main">
				<h1 class="sk-h3 sk-seo__title">Кровельные и фасадные материалы <br>в Ставрополе</h1>
				<div class="sk-seo__text">
					<p class="sk-seo__lead">«Строй Кровля» - официальный дистрибьютор ведущих российских и иностранных производителей кровельных материалов.</p>
					<p>Компания «Строй-Кровля», стабильно работающая на рынке предлагает продукцию ведущих мировых производителей кровельных и фасадных материалов. Будучи одним из самых ответственных и надежных дистрибьюторов, мы дорожим своей репутацией и делаем все, чтобы оправдать высокое доверие наших клиентов.</p>
					<p>Основным направлением деятельности компании является продажа кровельных и фасадных материалов оптом и в розницу. Мы делаем акцент на широком ассортименте и достаточных товарных запасах, поддерживая статус надежного поставщика высококачественной продукции!</p>
					<p>Мы будем рады работать с Вами!</p>
				</div>
				<div class="sk-seo__btns">
					<a class="sk-btn sk-btn--wide sk-js-scroll" href="#sk-calc">Получить расчет кровли</a>
					<a class="sk-btn sk-btn--dark" href="/prays-listy/">Скачать прайс <img src="<?= SK_PATH ?>/img/ic-arrow-down.svg" alt="" width="24" height="24"></a>
				</div>
			</div>
			<?php $skList(9, 'promo', 1, array('SORT_BY1' => 'ACTIVE_FROM', 'SORT_ORDER1' => 'DESC', 'FIELD_CODE' => array('ACTIVE_TO'))); ?>
		</div>
	</section>

</main>
<script src="/bitrix/templates/shop/js/jquery.validateform.js?v=20261022a"></script>
