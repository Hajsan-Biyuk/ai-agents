<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/**
 * Детальная страница новости /company/news/ по макету Figma «article-template-complete» (276:15).
 * Подключается из template.php. Поля статьи — свойства инфоблока LAM_* (вкладка «Оформление статьи»).
 */
$props = $arResult['PROPERTIES'];
$lamVal = function ($code) use ($props) {
	return (isset($props[$code]) && !is_array($props[$code]['VALUE'])) ? trim((string)$props[$code]['VALUE']) : '';
};

$tag = $lamVal('LAM_TAG');
$customAuthor = $lamVal('LAM_AUTHOR');
$authorName = $customAuthor ?: 'Крымов Денис Юрьевич';
$authorPost = $lamVal('LAM_AUTHOR_POST') ?: ($customAuthor ? '' : 'Эксперт в области синтетических композитных полов и ламината');
$authorUrl = $lamVal('LAM_AUTHOR_URL') ?: ($customAuthor ? '' : '/info/articles/2023/ekspert_krymov_denis_yurevich/');
$authorPhoto = !empty($props['LAM_AUTHOR_PHOTO']['VALUE'])
	? CFile::ResizeImageGet($props['LAM_AUTHOR_PHOTO']['VALUE'], array('width' => 88, 'height' => 88), BX_RESIZE_IMAGE_EXACT)
	: null;
$authorWords = preg_split('/\s+/u', htmlspecialcharsback($authorName), -1, PREG_SPLIT_NO_EMPTY);
$authorInitials = mb_strtoupper(mb_substr($authorWords[0] ?? '', 0, 1) . mb_substr($authorWords[1] ?? '', 0, 1));

$date = $arResult['PROPERTIES'][$arParams['PERIOD_PROPERTY']]['VALUE'] ?: $arResult['DISPLAY_ACTIVE_FROM'];
$readingTime = getReadingTime($arResult['DETAIL_TEXT']);

$hero = !empty($arResult['DETAIL_PICTURE'])
	? CFile::ResizeImageGet($arResult['DETAIL_PICTURE'], array('width' => 1200, 'height' => 1200), BX_RESIZE_IMAGE_PROPORTIONAL, true)
	: null;
$heroAlt = $arResult['DETAIL_PICTURE']['ALT'] ?: $arResult['NAME'];

// FAQ: описание значения = вопрос, значение = ответ
$faq = array();
if (!empty($props['LAM_FAQ']['VALUE']) && is_array($props['LAM_FAQ']['VALUE'])) {
	foreach ($props['LAM_FAQ']['VALUE'] as $i => $answer) {
		$question = $props['LAM_FAQ']['DESCRIPTION'][$i] ?? '';
		if (trim($answer) === '' || trim($question) === '') continue;
		$faq[] = array(
			'Q' => $question,
			'A' => $answer,
			'Q_RAW' => $props['LAM_FAQ']['~DESCRIPTION'][$i] ?? $question,
			'A_RAW' => $props['LAM_FAQ']['~VALUE'][$i] ?? $answer,
		);
	}
}
$sidebarNews = $arResult['LAM_SIDEBAR_NEWS'] ?? array();
?>
<article class="lam-article" data-id="<?=(int)$arResult['ID']?>" itemscope itemtype="https://schema.org/Article">
	<meta itemprop="headline" content="<?=$arResult['NAME']?>" />
	<meta itemprop="name" content="<?=$arResult['NAME']?>" />
	<?if ($arResult['PREVIEW_TEXT']):?>
		<meta itemprop="description" content="<?=htmlspecialcharsbx(strip_tags($arResult['~PREVIEW_TEXT'] ?? $arResult['PREVIEW_TEXT']))?>" />
	<?endif;?>
	<meta itemprop="author" content="<?=$authorName?>" />
	<?if ($arResult['PROPERTIES'][$arParams['PERIOD_PROPERTY']]['VALUE']):?>
		<meta itemprop="datePublished" content="<?=$arResult['PROPERTIES'][$arParams['PERIOD_PROPERTY']]['VALUE']?>" />
	<?elseif ($arResult['ACTIVE_FROM']):?>
		<meta itemprop="datePublished" content="<?=date('c', MakeTimeStamp($arResult['ACTIVE_FROM']))?>" />
	<?endif;?>
	<?if ($arResult['TIMESTAMP_X']):?>
		<meta itemprop="dateModified" content="<?=date('c', MakeTimeStamp($arResult['TIMESTAMP_X']))?>" />
	<?endif;?>
	<?if ($hero):?>
		<meta itemprop="image" content="<?=$arResult['DETAIL_PICTURE']['SRC']?>" />
	<?endif;?>

	<header class="lam-article__head">
		<?if ($tag):?>
			<span class="lam-article__tag"><?=$tag?></span>
		<?endif;?>
		<div class="lam-article__title-slot" data-lam-title></div>
		<div class="lam-article__meta">
			<span class="lam-avatar" aria-hidden="true">
				<?if ($authorPhoto):?><img src="<?=$authorPhoto['src']?>" alt="" loading="lazy"><?else:?><?=htmlspecialcharsbx($authorInitials)?><?endif;?>
			</span>
			<?if ($authorUrl):?>
				<a class="lam-article__author" href="<?=$authorUrl?>" title="<?=$authorPost?>"><?=$authorName?></a>
			<?else:?>
				<span class="lam-article__author" title="<?=$authorPost?>"><?=$authorName?></span>
			<?endif;?>
			<?if ($date):?>
				<span class="lam-article__dot">·</span>
				<span class="lam-article__muted"><?=$date?></span>
			<?endif;?>
			<span class="lam-article__dot">·</span>
			<span class="lam-article__muted"><?=$readingTime?> мин чтения</span>
		</div>
		<?if ($arResult['TIMESTAMP_X']):?>
			<div class="lam-article__updated">Информация обновлена: <?=CIBlockFormatProperties::DateFormat('d.m.Y', MakeTimeStamp($arResult['TIMESTAMP_X']))?></div>
		<?endif;?>
	</header>

	<div class="lam-article__main">
		<?if ($hero):?>
			<a class="lam-article__hero fancy" href="<?=$arResult['DETAIL_PICTURE']['SRC']?>">
				<img src="<?=$hero['src']?>" alt="<?=$heroAlt?>" title="<?=($arResult['DETAIL_PICTURE']['TITLE'] ?: $arResult['NAME'])?>" width="<?=$hero['width']?>" height="<?=$hero['height']?>">
			</a>
		<?endif;?>

		<nav class="lam-toc" aria-label="Содержание" hidden>
			<div class="lam-toc__title">Содержание</div>
			<ul class="lam-toc__list"></ul>
		</nav>

		<?if ($arResult['PREVIEW_TEXT']):?>
			<div class="lam-article__lead"><?=$arResult['PREVIEW_TEXT']?></div>
		<?endif;?>

		<?if ($arResult['DETAIL_TEXT']):?>
			<div class="lam-article__body" itemprop="articleBody"><?=$arResult['DETAIL_TEXT']?></div>
		<?endif;?>

		<?if ($arParams['SHOW_GALLERY'] == 'Y' && $arResult['PROPERTIES'][$arParams['GALLERY_PROPERTY']]['VALUE']):?>
			<div class="lam-gallery">
				<?foreach ((array)$arResult['PROPERTIES'][$arParams['GALLERY_PROPERTY']]['VALUE'] as $fileId):?>
					<?
					$img = CFile::ResizeImageGet($fileId, array('width' => 400, 'height' => 270), BX_RESIZE_IMAGE_EXACT, true);
					$imgBig = CFile::ResizeImageGet($fileId, array('width' => 1200, 'height' => 900), BX_RESIZE_IMAGE_PROPORTIONAL, true);
					?>
					<a href="<?=$imgBig['src']?>" class="fancy" data-fancybox-group="gallery"><img src="<?=$img['src']?>" alt="<?=$arResult['NAME']?>" loading="lazy"></a>
				<?endforeach;?>
			</div>
		<?endif;?>

		<?if ($faq):?>
			<section class="lam-faq">
				<h2 class="lam-h2">Часто задаваемые вопросы</h2>
				<?foreach ($faq as $i => $item):?>
					<details class="lam-faq__item"<?=($i === 0 ? ' open' : '')?>>
						<summary class="lam-faq__q">
							<span><?=$item['Q']?></span>
							<svg class="lam-faq__icon" width="24" height="24" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 15l-4.243-4.243 1.415-1.414L12 12.172l2.828-2.829 1.415 1.414z" fill="currentColor"/></svg>
						</summary>
						<div class="lam-faq__a"><?=nl2br($item['A'])?></div>
					</details>
				<?endforeach;?>
			</section>
			<script type="application/ld+json"><?=json_encode(array(
				'@context' => 'https://schema.org',
				'@type' => 'FAQPage',
				'mainEntity' => array_map(function ($item) {
					return array(
						'@type' => 'Question',
						'name' => strip_tags($item['Q_RAW']),
						'acceptedAnswer' => array('@type' => 'Answer', 'text' => strip_tags($item['A_RAW'])),
					);
				}, $faq),
			), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG)?></script>
		<?endif;?>

		<section class="lam-products">
			<h2 class="lam-h2">Рекомендуемые товары по теме:</h2>
			<?$APPLICATION->IncludeComponent(
				"aspro:tabs.mshop",
				"main",
				Array(
					"ACTION_VARIABLE" => "action",
					"ADD_SECTIONS_CHAIN" => "N",
					"AJAX_MODE" => "N",
					"AJAX_OPTION_ADDITIONAL" => "",
					"AJAX_OPTION_HISTORY" => "N",
					"AJAX_OPTION_JUMP" => "N",
					"AJAX_OPTION_STYLE" => "Y",
					"BASKET_URL" => "/basket/",
					"BROWSER_TITLE" => "-",
					"CACHE_FILTER" => "Y",
					"CACHE_GROUPS" => "N",
					"CACHE_TIME" => "250000",
					"CACHE_TYPE" => "A",
					"COMPONENT_TEMPLATE" => "main",
					"COMPOSITE_FRAME_MODE" => "A",
					"COMPOSITE_FRAME_TYPE" => "AUTO",
					"CONVERT_CURRENCY" => "N",
					"DETAIL_URL" => "",
					"DISCOUNT_PRICE_CODE" => "",
					"DISPLAY_BOTTOM_PAGER" => "N",
					"DISPLAY_COMPARE" => "Y",
					"DISPLAY_TOP_PAGER" => "N",
					"DISPLAY_WISH_BUTTONS" => "Y",
					"ELEMENT_SORT_FIELD" => "sort",
					"ELEMENT_SORT_FIELD2" => "id",
					"ELEMENT_SORT_ORDER" => "asc",
					"ELEMENT_SORT_ORDER2" => "desc",
					"FILTER_NAME" => "arrFilterProp",
					"HIDE_NOT_AVAILABLE" => "N",
					"IBLOCK_ID" => "17",
					"IBLOCK_TYPE" => "aspro_mshop_catalog",
					"INCLUDE_SUBSECTIONS" => "Y",
					"LINE_ELEMENT_COUNT" => "5",
					"META_DESCRIPTION" => "-",
					"META_KEYWORDS" => "-",
					"OFFERS_CART_PROPERTIES" => array(),
					"OFFERS_FIELD_CODE" => array(0=>"ID",1=>"",),
					"OFFERS_LIMIT" => "10",
					"OFFERS_PROPERTY_CODE" => array(0=>"",1=>"",),
					"OFFERS_SORT_FIELD" => "sort",
					"OFFERS_SORT_FIELD2" => "id",
					"OFFERS_SORT_ORDER" => "asc",
					"OFFERS_SORT_ORDER2" => "desc",
					"PAGER_DESC_NUMBERING" => "N",
					"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
					"PAGER_SHOW_ALL" => "N",
					"PAGER_SHOW_ALWAYS" => "N",
					"PAGER_TEMPLATE" => ".default",
					"PAGER_TITLE" => "Товары",
					"PAGE_ELEMENT_COUNT" => "20",
					"PRICE_CODE" => array(0=>"BASE",),
					"PRICE_VAT_INCLUDE" => "Y",
					"PRODUCT_ID_VARIABLE" => "id",
					"PRODUCT_PROPERTIES" => array(),
					"PRODUCT_PROPS_VARIABLE" => "prop",
					"PRODUCT_QUANTITY_VARIABLE" => "quantity",
					"PROPERTY_CODE" => array(0=>"",1=>"",),
					"SALE_STIKER" => "SALE_TEXT",
					"SECTION_CODE" => "",
					"SECTION_ID" => "",
					"SECTION_ID_VARIABLE" => "SECTION_ID",
					"SECTION_NAME_FILTER" => "",
					"SECTION_SLIDER_FILTER" => "21",
					"SECTION_URL" => "",
					"SECTION_USER_FIELDS" => array(0=>"",1=>"",),
					"SET_STATUS_404" => "N",
					"SET_TITLE" => "N",
					"SHOW_ADD_FAVORITES" => "Y",
					"SHOW_ALL_WO_SECTION" => "Y",
					"SHOW_DISCOUNT_PERCENT" => "Y",
					"SHOW_DISCOUNT_TIME" => "Y",
					"SHOW_MEASURE" => "Y",
					"SHOW_OLD_PRICE" => "Y",
					"SHOW_PRICE_COUNT" => "1",
					"TABS_CODE" => "HIT",
					"USE_PRICE_COUNT" => "N",
					"USE_PRODUCT_QUANTITY" => "N"
				)
			);?>
		</section>
	</div>

	<aside class="lam-article__aside">
		<div class="lam-cta">
			<div class="lam-cta__head">
				<div class="lam-cta__title">Остались вопросы?</div>
				<div class="lam-cta__text">Оставьте заявку и мы свяжемся с вами</div>
			</div>
			<form class="lam-cta__form" novalidate>
				<div class="lam-cta__field">
					<label class="lam-cta__label" for="lam-cta-phone">Ваш телефон</label>
					<input class="lam-cta__input" id="lam-cta-phone" name="phone" type="tel" inputmode="tel" autocomplete="tel" placeholder="+7 (___) ___-__-__" required>
				</div>
				<div class="lam-cta__checks">
					<label class="lam-check">
						<input type="checkbox" name="agree" required>
						<span class="lam-check__box" aria-hidden="true"></span>
						<span>Согласен с <a href="/info/zakon/" target="_blank" rel="noopener">политикой обработки данных</a> ИП Крымов Денис Юрьевич (ИНН 235504804935)</span>
					</label>
					<label class="lam-check">
						<input type="checkbox" name="subscribe">
						<span class="lam-check__box" aria-hidden="true"></span>
						<span>Согласен получать информационные материалы</span>
					</label>
				</div>
				<div class="lam-cta__error" role="alert" hidden></div>
				<button class="lam-cta__btn" type="submit">Перезвоните мне</button>
			</form>
		</div>

		<?if ($sidebarNews):?>
			<div class="lam-side-news">
				<?foreach ($sidebarNews as $item):?>
					<a class="lam-news-card" href="<?=$item['URL']?>">
						<?if ($item['PICTURE']):?>
							<img class="lam-news-card__img" src="<?=$item['PICTURE']?>" alt="<?=$item['NAME']?>" loading="lazy">
						<?endif;?>
						<span class="lam-news-card__body">
							<?if ($item['DATE']):?><span class="lam-news-card__date"><?=$item['DATE']?></span><?endif;?>
							<span class="lam-news-card__title"><?=$item['NAME']?></span>
						</span>
					</a>
				<?endforeach;?>
			</div>
		<?endif;?>
	</aside>
</article>
