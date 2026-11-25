<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	define("STATISTIC_SKIP_ACTIVITY_CHECK", "true");
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
}?>
<div class="iq-popular">
	<div class="iq-container">
		<div class="iq-popular-grid">
		<?php
			$arFilter = array(
				"IBLOCK_ID"     => 61,
				"GLOBAL_ACTIVE" => "Y",
				"IBLOCK_ACTIVE" => "Y",
				"UF_POPULAR"    => true
			);

			$arOrder = array("left_margin" => "asc");

			$rsSections = CIBlockSection::GetList($arOrder, $arFilter, false, array(
				"ID",
				"DEPTH_LEVEL",
				"NAME",
				"SECTION_PAGE_URL",
				"PICTURE"
			));

			$ct = 0;
			$shownSections = array(); // массив для хранения ID уже показанных разделов

			while ($arSect = $rsSections->GetNext()) {
				// проверяем, не выводили ли уже этот раздел
				if (in_array($arSect['ID'], $shownSections)) {
					continue;
				}

				$shownSections[] = $arSect['ID']; // добавляем ID в массив

				$ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues(61, $arSect['ID']);
				$IPROPERTY = $ipropValues->getValues();

				$ar_result = CIBlockSection::GetList(
					Array("SORT" => "ASC"),
					Array("IBLOCK_ID" => 61, "ID" => $arSect['ID']),
					false,
					Array("UF_IMAGE_FILE")
				);

				$img_file = $arSect["PICTURE"]; // по умолчанию

				/*if ($res = $ar_result->GetNext()) {
					if (!empty($res['UF_IMAGE_FILE'])) {
						$img_file = $res['UF_IMAGE_FILE'];
					}
				}*/

				// Название раздела
				if ($arSect['NAME'] == 'Для ванной' || $IPROPERTY['SECTION_PAGE_TITLE'] == 'Плитка для ванной') {
					$arSect['NAME'] = str_replace('Для ванной', 'Плитка и керамогранит', $arSect['NAME']);
					$IPROPERTY['SECTION_PAGE_TITLE'] = str_replace('Плитка для ванной', 'Плитка и керамогранит', $IPROPERTY['SECTION_PAGE_TITLE']);
				}
				?>
				<a class="iq-popular-item" href="<?= $arSect['SECTION_PAGE_URL'] ?>">
					<div class="iq-popular-item__picture">
						<img class="iq-popular-item__picture__img" src="<?= CFile::GetPath($img_file); ?>" alt="#">
					</div>
					<div class="iq-popular-item__info">
						<div class="iq-popular-item__info__name"><?= (!empty($IPROPERTY['SECTION_PAGE_TITLE']) ? $IPROPERTY['SECTION_PAGE_TITLE'] : $arSect['NAME']) ?></div>
					</div>
				</a>
			<?php
				$ct++;
			}
		?>			
		</div>
		<a href="/catalog/" class="iq-new-btn-catalog pull-right font_upper btn btn-transparent-border-color btn-sm">Весь каталог</a>
	</div>
</div>
<div class="iq-new-club-designer"></div>
<?/*$APPLICATION->IncludeComponent(
	"aspro:wrapper.block.max", 
	"front_sections_only", 
	array(
		"IBLOCK_TYPE" => "aspro_max_catalog",
		"IBLOCK_ID" => "61",
		"FILTER_NAME" => "arrPopularSections",
		"COMPONENT_TEMPLATE" => "front_sections_only",
		"SECTION_ID" => "",
		"SECTION_CODE" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "Y",
		"CACHE_GROUPS" => "Y",
		"TITLE_BLOCK" => "Популярные категории",
		"TITLE_BLOCK_ALL" => "Весь каталог",
		"ALL_URL" => "catalog/",
		"VIEW_MODE" => "",
		"VIEW_TYPE" => "type2",
		"NO_MARGIN" => "N",
		"FILLED" => "Y",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
);*/?>