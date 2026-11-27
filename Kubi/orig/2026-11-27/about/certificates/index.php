<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Сертификаты");
?>
<section class="page-about">
	<div class="container">
		 <?$APPLICATION->IncludeComponent("bitrix:breadcrumb", "bootstrap_v4", ["PATH" => "", "SITE_ID" => "s1", "START_FROM" => "0"]);?>
		<div class="row">
			<div class="col-lg-3">
				<div class="sidebar-nav-wrapper">
					<div class="btn-sidebar"><i class="fas fa-chevron-down"></i></div>
					 <?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/support-sidebar-nav-2.php"]);?>
				</div>
			</div>
			<div class="col-lg-9">
				<h1 class="title-section title-section_top">Сертификаты</h1>
				<?$APPLICATION->IncludeComponent(
					"bitrix:news.list",
					"documentation",
					Array(
						"FIELD_CODE" => ["NAME","PREVIEW_TEXT","PREVIEW_PICTURE","DETAIL_PICTURE"],
						"IBLOCK_ID" => "52",
						"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
						"NEWS_COUNT" => "999",
						"PROPERTY_CODE" => array("*"),
						"SET_BROWSER_TITLE" => "N",
						"SET_TITLE" => "N",
						"SORT_BY1" => "SORT",
						"SORT_ORDER1" => "ASC"
					),
				false,
				Array(
					'HIDE_ICONS' => 'Y'
				));?>
			</div>
		</div>
	</div>
</section>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>