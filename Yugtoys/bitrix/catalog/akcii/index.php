<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Акционные товары оптом — Юг Тойз");
$APPLICATION->SetPageProperty('description', 'Акционные товары в оптовой компании «Юг Тойз»: детские игрушки и товары для дома по специальной цене.');
?>
<section class="page-delivery">
	<div class="container">
		<h1 class="title-section">Акционные товары</h1>
		<div class="catalog">
		<?
			// товары, у которых в админке в поле «Маркер» (MARKER) выбрано «АКЦИЯ» — значение списка ID 83
			$akciiFilter = array('PROPERTY_MARKER' => 83);
			CModule::IncludeModule('iblock');
			$akciiCount = (int)CIBlockElement::GetList(array(), array_merge($akciiFilter, array('IBLOCK_ID' => CATALOG, 'ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y')), array());
			if($akciiCount == 0){
		?>
			<div class="akcii-empty">
				<p>Сейчас акционных товаров нет. Загляните позже или посмотрите весь каталог.</p>
				<a href="/catalog/" class="btn-main btn-main_page">Перейти в каталог</a>
			</div>
		<?
			}
			else{
				include $_SERVER["DOCUMENT_ROOT"]."/catalog/_catalog_param.php";
				$GLOBALS['arrAkcii'] = $akciiFilter;
				$_catalog_param = array_merge($_catalog_param, array("FILTER_NAME" => "arrAkcii", "SET_TITLE" => "N", "SHOW_ALL_WO_SECTION" => "Y", "SET_STATUS_404" => "N", "ADD_SECTIONS_CHAIN" => "N"));
				$APPLICATION->IncludeComponent(
					"bitrix:catalog.section",
					"bootstrap_v4",
					$_catalog_param,
					false
				);
			}
		?>
		</div>
	</div>
</section>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
