<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Акции");
$APPLICATION->SetPageProperty("HIDE_LEFT_BLOCK", "Y");
$APPLICATION->SetPageProperty("title", "Акции — АТРИУМ опт");
$APPLICATION->SetPageProperty("description", "Акционные товары оптового каталога АТРИУМ: сантехника, плитка, керамогранит и мебель для ванной.");

// Акции опта = товары с флажком «Акция (опт)» (свойство OPT_SALE). Вид — как «Распродажа» на рознице.
// Не раздел каталога: обмен с 1С выключает чужие разделы и перезаписывает привязки (см. /novinki/).
include $_SERVER["DOCUMENT_ROOT"]."/catalog/_catalog_param.php";
\Bitrix\Main\Loader::includeModule('iblock');

global $arOptSalePrefilter, $arOptSaleSections, $MAX_SMART_FILTER;
$arOptSalePrefilter = ['PROPERTY_OPT_SALE_VALUE' => 'Да'];

// категории верхнего уровня, где есть акционные товары
$arTop = [];
$rs = CIBlockSection::GetList(['LEFT_MARGIN' => 'ASC'], ['IBLOCK_ID' => 80, 'DEPTH_LEVEL' => 1, 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y'], false, ['ID', 'NAME']);
while ($s = $rs->Fetch()) {
	$cnt = (int)CIBlockElement::GetList([], ['IBLOCK_ID' => 80, 'ACTIVE' => 'Y', 'SECTION_ID' => $s['ID'], 'INCLUDE_SUBSECTIONS' => 'Y'] + $arOptSalePrefilter, []);
	if ($cnt) $arTop[(int)$s['ID']] = $s['NAME'];
}
$sectionId = (int)($_GET['section'] ?? 0);
if (!isset($arTop[$sectionId])) $sectionId = 0;
$arOptSaleSections = ['ID' => array_keys($arTop) ?: [-1]];

// сортировка: как в каталоге
$arSorts = ['SORT' => ['SHOWS', 'Популярное'], 'NAME' => ['NAME', 'По алфавиту'], 'PRICE' => ['PRICE', 'По цене']];
$sort = isset($arSorts[$_GET['sort'] ?? '']) ? $_GET['sort'] : 'SORT';
$order = ($_GET['order'] ?? 'desc') === 'asc' ? 'asc' : 'desc';
$sortField = $arSorts[$sort][0];
if ($sortField === 'PRICE') {
	$sortField = 'SHOWS';
	if (\Bitrix\Main\Loader::includeModule('catalog') && ($pt = CCatalogGroup::GetList([], ['NAME' => $PRICE_CODE[0]])->Fetch())) $sortField = 'CATALOG_PRICE_' . $pt['ID'];
}
$sortUrl = function ($s, $o) use ($sectionId) {
	return '/sale/?' . http_build_query(array_filter(['section' => $sectionId ?: null, 'sort' => $s, 'order' => $o]));
};
?>
<div class="topic__heading iq-catalog-title opt-catalog-title">
	<h1 id="pagetitle"><?$APPLICATION->ShowTitle(false)?></h1>
</div>
<?if($arTop):?>
	<div class="section-block">
		<?$APPLICATION->IncludeComponent("bitrix:catalog.section.list", "opt_chips", [
			"IBLOCK_TYPE" => "1c_catalog",
			"IBLOCK_ID" => 80,
			"SECTION_ID" => 0,
			"TOP_DEPTH" => 1,
			"FILTER_NAME" => "arOptSaleSections",
			"SECTION_URL" => "/sale/?section=#SECTION_ID#",
			"SECTION_FIELDS" => ["ID", "NAME", "CODE", "PICTURE"],
			"COUNT_ELEMENTS" => "N",
			"ADD_SECTIONS_CHAIN" => "N",
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => 3600,
			"CACHE_FILTER" => "Y",
			"CACHE_GROUPS" => "N",
		], false, ['HIDE_ICONS' => 'Y']);?>
	</div>
<?endif;?>

<div class="main-catalog-wrapper clearfix iq-catalog-grid opt-sale">
	<div class="iq-side left_block sticky-sidebar">
		<div class="iq-side__mobile">Закрыть фильтр</div>
		<div class="sticky-sidebar__inner">
			<?if($arTop):?>
				<ul class="opt-sale__sections">
					<li><a href="/sale/" class="<?=$sectionId ? 'dark_link' : 'colored_theme_text'?>">Акции</a>
						<ul>
							<?foreach($arTop as $id => $name):?>
								<li><a href="/sale/?section=<?=$id?>" class="<?=$sectionId == $id ? 'colored_theme_text' : 'dark_link'?>"><?=htmlspecialcharsbx($name)?></a></li>
							<?endforeach;?>
						</ul>
					</li>
				</ul>
			<?endif;?>
			<?$APPLICATION->IncludeComponent("bitrix:catalog.smart.filter", "main_ajax", [
				"IBLOCK_TYPE" => "1c_catalog",
				"IBLOCK_ID" => 80,
				"SECTION_ID" => $sectionId,
				"FILTER_NAME" => "MAX_SMART_FILTER",
				"PREFILTER_NAME" => "arOptSalePrefilter",
				"PRICE_CODE" => $PRICE_CODE,
				"HIDE_NOT_AVAILABLE" => "N",
				"CACHE_TYPE" => "A",
				"CACHE_TIME" => 36000000,
				"CACHE_GROUPS" => "Y",
				"SAVE_IN_SESSION" => "N",
				"XML_EXPORT" => "N",
				"SEF_MODE" => "N",
				"DISPLAY_ELEMENT_COUNT" => "Y",
				"INSTANT_RELOAD" => "N",
				"CONVERT_CURRENCY" => "N",
				"VIEW_MODE" => "VERTICAL",
				"SHOW_HINTS" => "Y",
				"PAGER_PARAMS_NAME" => "arrPager",
			], false, ['HIDE_ICONS' => 'Y']);?>
		</div>
	</div>

	<?
	// фильтр списка = флажок акции + выбранное в умном фильтре
	$GLOBALS['arOptSaleList'] = array_merge($arOptSalePrefilter, is_array($MAX_SMART_FILTER) ? $MAX_SMART_FILTER : []);
	$optSaleCount = (int)CIBlockElement::GetList([], ['IBLOCK_ID' => 80, 'ACTIVE' => 'Y'] + ($sectionId ? ['SECTION_ID' => $sectionId, 'INCLUDE_SUBSECTIONS' => 'Y'] : []) + $GLOBALS['arOptSaleList'], []);
	?>
	<div class="iq-catalog-main section-content-wrapper with-leftblock">
		<div class="section-top-catalog-control iq-catalog-head">
			<div class="iq-catalog-count-product">Товаров <span>( <span class="iq-catalog-title__counter"><?=$optSaleCount?></span> )</span></div>
			<div class="iq-catalog-head__sort">
				<div class="filter-panel sort_header view_block">
					<div class="filter-panel__sort hidden-xs">
						<div class="sort-select">
							<div class="dropdown-select">
								<div class="dropdown-select__title font_xs darken">
									<span class="wrapper-sort"><span class="iq-catalog-head__sort__value"><?=$arSorts[$sort][1]?> (<?=$order === 'asc' ? 'возрастание' : 'убывание'?>)</span></span>
									<?=CMax::showIconSvg("down", SITE_TEMPLATE_PATH.'/images/svg/trianglearrow_down.svg', '', '')?>
								</div>
								<div class="dropdown-select__list dropdown-menu-wrapper" role="menu">
									<div class="dropdown-menu-inner rounded3">
										<?foreach($arSorts as $code => $s) foreach(['desc' => 'убывание', 'asc' => 'возрастание'] as $o => $oName):?>
											<div class="dropdown-select__list-item font_xs">
												<?if($code === $sort && $o === $order):?>
													<span class="dropdown-select__list-link dropdown-select__list-link--current"><span><?=$s[1]?> (<?=$oName?>)</span></span>
												<?else:?>
													<a href="<?=htmlspecialcharsbx($sortUrl($code, $o))?>" class="dropdown-select__list-link darken" rel="nofollow"><span><?=$s[1]?> (<?=$oName?>)</span></a>
												<?endif;?>
											</div>
										<?endforeach;?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="iq-catalog-side-toggle">Открыть фильтры</div>
		</div>

		<?$APPLICATION->IncludeComponent("bitrix:catalog.section", "catalog_block", array_merge($_catalog_param, [
			"SECTION_ID" => $sectionId ?: "",
			"SHOW_ALL_WO_SECTION" => "Y",
			"INCLUDE_SUBSECTIONS" => "Y",
			"FILTER_NAME" => "arOptSaleList",
			"ELEMENT_SORT_FIELD" => $sortField,
			"ELEMENT_SORT_ORDER" => $order,
			"ELEMENT_SORT_FIELD2" => "ID",
			"ELEMENT_SORT_ORDER2" => "DESC",
			"PAGE_ELEMENT_COUNT" => 24,
			"LINE_ELEMENT_COUNT" => 4,
			"DISPLAY_TYPE" => "block",
			"IS_CATALOG_PAGE" => "Y",
			"PROPERTY_CODE" => ["HIT", "BRAND", "CML2_ARTICLE"],
			"ADD_PICT_PROP" => "MORE_PHOTO",
			"SHOW_GALLERY" => "Y",
			"MAX_GALLERY_ITEMS" => "5",
			"STIKERS_PROP" => "HIT",
			"SALE_STIKER" => "SALE_TEXT",
			"SHOW_ARTICLE_SKU" => "Y",
			"SHOW_QUANTITY" => "Y",
			"SHOW_MEASURE" => "Y",
			"SHOW_QUANTITY_COUNT" => "Y",
			"DISPLAY_WISH_BUTTONS" => "Y",
			"DISPLAY_COMPARE" => CMax::GetFrontParametrValue('CATALOG_COMPARE'),
			"DEFAULT_COUNT" => "1",
			"SHOW_ONE_CLICK_BUY" => "N",
			"SHOW_DISCOUNT_PERCENT_NUMBER" => "Y",
			"TYPE_SKU" => CMax::GetFrontParametrValue('TYPE_SKU'),
			"TYPE_VIEW_BASKET_BTN" => CMax::GetFrontParametrValue('TYPE_VIEW_BASKET_BTN'),
			"TYPE_VIEW_CATALOG_LIST" => CMax::GetFrontParametrValue('TYPE_VIEW_CATALOG_LIST'),
			"USE_FAST_VIEW" => CMax::GetFrontParametrValue('USE_FAST_VIEW_PAGE_DETAIL'),
			"SET_TITLE" => "N",
			"MESSAGE_404" => "",
			"DISPLAY_BOTTOM_PAGER" => "Y",
			"PAGER_TEMPLATE" => "main",
			"PAGER_SHOW_ALWAYS" => "N",
			"PAGER_TITLE" => "Товары",
			"CACHE_FILTER" => "Y",
		]), false, ['HIDE_ICONS' => 'Y']);?>
		<?if(!$arTop):?>
			<p class="opt-sale__empty">Сейчас акционных товаров нет. Загляните позже или посмотрите <a href="/catalog/">весь каталог</a>.</p>
		<?endif;?>
	</div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
