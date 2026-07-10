<?
use Bitrix\Main\Loader,
	Bitrix\Main\ModuleManager;
?>
<?
$arParams['OID'] = 0;
if ($oidParam = $arParams["SKU_DETAIL_ID"]) {
	$context=\Bitrix\Main\Context::getCurrent();
	$request=$context->getRequest();
	if ($oid = $request->getQuery($oidParam)) {
		$arParams['OID'] = $oid;
	}
}
?>
<div class="main-catalog-wrapper details js_wrapper_items maxwidth-theme" >
<h1></h1>
	<div class="section-content-wrapper <?CMax::ShowPageProps("WITH_LEFT_BLOCK")?>">
		<?CMax::AddMeta(
			array(
				'og:description' => $arElement['PREVIEW_TEXT'],
				'og:image' => (($arElement['PREVIEW_PICTURE'] || $arElement['DETAIL_PICTURE']) ? CFile::GetPath(($arElement['PREVIEW_PICTURE'] ? $arElement['PREVIEW_PICTURE'] : $arElement['DETAIL_PICTURE'])) : false),
			)
		);?>
		<?$sViewElementTemplate = ($arParams["ELEMENT_TYPE_VIEW"] == "FROM_MODULE" ? $arTheme["CATALOG_PAGE_DETAIL"]["VALUE"] : $arParams["ELEMENT_TYPE_VIEW"]);?>
		<?$hide_left_block = ($arTheme["LEFT_BLOCK_CATALOG_DETAIL"]["VALUE"] == "Y" ? "N" : "Y");

		//set offer view type
		$typeTmpDetail = $typeTmpPictureDetail = 0;
		if($arSection['UF_ELEMENT_DETAIL'])
			$typeTmpDetail = $arSection['UF_ELEMENT_DETAIL'];
		if($arSection['UF_PICTURE_RATIO'])
			$typeTmpPictureDetail = $arSection['UF_PICTURE_RATIO'];

		if(!$typeTmpDetail || !$typeTmpPictureDetail){
			if($arSection["DEPTH_LEVEL"] > 2){
				$sectionParent = CMaxCache::CIBlockSection_GetList(array('CACHE' => array("MULTI" =>"N", "TAG" => CMaxCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), array('GLOBAL_ACTIVE' => 'Y', "ID" => $arSection["IBLOCK_SECTION_ID"], "IBLOCK_ID" => $arParams["IBLOCK_ID"]), false, array("ID", "IBLOCK_ID", "NAME", "UF_ELEMENT_DETAIL", "UF_PICTURE_RATIO"));
				if($sectionParent['UF_ELEMENT_DETAIL'] && !$typeTmpDetail){
					$typeTmpDetail = $sectionParent['UF_ELEMENT_DETAIL'];
				}
				if($sectionParent['UF_PICTURE_RATIO'] && !$typeTmpPictureDetail){
					$typeTmpPictureDetail = $sectionParent['UF_PICTURE_RATIO'];
				}

				if(!$typeTmpDetail || !$typeTmpPictureDetail){
					$sectionRoot = CMaxCache::CIBlockSection_GetList(array('CACHE' => array("MULTI" =>"N", "TAG" => CMaxCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), array('GLOBAL_ACTIVE' => 'Y', "<=LEFT_BORDER" => $arSection["LEFT_MARGIN"], ">=RIGHT_BORDER" => $arSection["RIGHT_MARGIN"], "DEPTH_LEVEL" => 1, "IBLOCK_ID" => $arParams["IBLOCK_ID"]), false, array("ID", "IBLOCK_ID", "NAME", "UF_ELEMENT_DETAIL", "UF_PICTURE_RATIO"));
					if($sectionRoot['UF_ELEMENT_DETAIL'] && !$typeTmpDetail){
						$typeTmpDetail = $sectionRoot['UF_ELEMENT_DETAIL'];
					}
					if($sectionRoot['UF_PICTURE_RATIO'] && !$typeTmpPictureDetail){
						$typeTmpPictureDetail = $sectionRoot['UF_PICTURE_RATIO'];
					}
				}
			}
			else{
				$sectionRoot = CMaxCache::CIBlockSection_GetList(array('CACHE' => array("MULTI" =>"N", "TAG" => CMaxCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), array('GLOBAL_ACTIVE' => 'Y', "<=LEFT_BORDER" => $arSection["LEFT_MARGIN"], ">=RIGHT_BORDER" => $arSection["RIGHT_MARGIN"], "DEPTH_LEVEL" => 1, "IBLOCK_ID" => $arParams["IBLOCK_ID"]), false, array("ID", "IBLOCK_ID", "NAME", "UF_ELEMENT_DETAIL", "UF_PICTURE_RATIO"));
				if($sectionRoot['UF_ELEMENT_DETAIL'] && !$typeTmpDetail){
					$typeTmpDetail = $sectionRoot['UF_ELEMENT_DETAIL'];
				}
				if($sectionRoot['UF_PICTURE_RATIO'] && !$typeTmpPictureDetail){
					$typeTmpPictureDetail = $sectionRoot['UF_PICTURE_RATIO'];
				}
			}
		}

		if($typeTmpDetail){
			$rsTypes = CUserFieldEnum::GetList(array(), array("ID" => $typeTmpDetail));
			if($arType = $rsTypes->GetNext()){
				$typeDetail = $arType['XML_ID'];
			}
			if($typeDetail){
				$sViewElementTemplate = $typeDetail;
			}
		}
		if($typeTmpPictureDetail){
			$rsTypes = CUserFieldEnum::GetList(array(), array("ID" => $typeTmpPictureDetail));
			if($arType = $rsTypes->GetNext()){
				$typePictureDetail = $arType['XML_ID'];
			}
			if($typePictureDetail){
				$sViewPictureDetail = $typePictureDetail;
			}
		}

        /* set force CATALOG_PAGE_DETAIL from theme */
        if (
			class_exists('\Aspro\Functions\CAsproMaxCustom') &&
			method_exists('\Aspro\Functions\CAsproMaxCustom', 'setPageDetail')
		) {
			$sViewElementTemplate = \Aspro\Functions\CAsproMaxCustom::setPageDetail($sViewElementTemplate);
		}
        /* */
		?>

		<?if($arParams["USE_SHARE"] == "Y" && $arElement):?>
			<?$this->SetViewTarget('product_share');?>
				<?\Aspro\Functions\CAsproMax::showShareBlock('top')?>
			<?$this->EndViewTarget();?>
		<?endif;?>

		<?$isWideBlock = (isset($arParams["DIR_PARAMS"]["HIDE_LEFT_BLOCK"]) ? $arParams["DIR_PARAMS"]["HIDE_LEFT_BLOCK"] : "");?>
		<?if($arParams['AJAX_MODE'] == 'Y' && strpos($_SERVER['REQUEST_URI'], 'bxajaxid') !== false):?>
			<script type="text/javascript">
				setStatusButton();
			</script>
		<?endif;?>
		<?$sViewBigDataTemplate = ($arParams["BIGDATA_NORMAL"] ? $arParams["BIGDATA_NORMAL"] : "bigdata_1" );?>
		<?$sViewBigDataExtTemplate = ($arParams["BIGDATA_EXT"] ? $arParams["BIGDATA_EXT"] : "bigdata_2" );?>

		<?
		$arParams["DETAIL_EXPANDABLES_TITLE"] = ($arParams["DETAIL_EXPANDABLES_TITLE"] ? $arParams["DETAIL_EXPANDABLES_TITLE"] : GetMessage("EXPANDABLES_TITLE"));
		$arParams["DETAIL_ASSOCIATED_TITLE"] = ($arParams["DETAIL_ASSOCIATED_TITLE"] ? $arParams["DETAIL_ASSOCIATED_TITLE"] : GetMessage("ASSOCIATED_TITLE"));

		$arTab = $arAllValues = $arSimilar = $arAssociated = $arNeedSelect = array();
		$bSimilar = $bAccessories = $bBigData = false;

		// cross sales for product
		$oCrossSales = new \Aspro\Max\CrossSales($arElement['ID'], $arParams);
		$arRules = $oCrossSales->getRules();
		//echo '<pre>';print_r($arRules); echo '</pre>';
		// accessories goods from cross sales
		if($arRules['EXPANDABLES'])
		{
			$arExpValues = $oCrossSales->getItems('EXPANDABLES');
		}
		else
		{
			$arNeedSelect[] = 'PROPERTY_EXPANDABLES_FILTER';
			$arNeedSelect[] = 'PROPERTY_EXPANDABLES';
		}

		// similar goods from cross sales
		if($arRules['ASSOCIATED'])
		{
			$arAssociated = $oCrossSales->getItems('ASSOCIATED');
		}
		else
		{
			$arNeedSelect[] = 'PROPERTY_ASSOCIATED_FILTER';
			$arNeedSelect[] = 'PROPERTY_ASSOCIATED';
		}

		if(!$arRules['EXPANDABLES'])
		{
			// accessories goods from property with type filter
			if($arElement['PROPERTY_EXPANDABLES_FILTER_VALUE'])
			{
				$cond = new CMaxCondition();
				try{
					$arTmpExp = \Bitrix\Main\Web\Json::decode($arElement['PROPERTY_EXPANDABLES_FILTER_VALUE']);
					$arExpandablesFilter = $cond->parseCondition($arTmpExp, $arParams);
				}
				catch(\Exception $e){
					$arExpandablesFilter = array();
				}
				unset($cond);
			}

			// accessories goods from property with type link
			if(!$arElement['PROPERTY_EXPANDABLES_FILTER_VALUE'] || !$arTmpExp || !$arTmpExp['CHILDREN'])
			{
				if($arExpValues = $arElement['PROPERTY_EXPANDABLES_VALUE'])
				{
					$arAllValues['EXPANDABLES'] = $arExpValues;
				}
			}
		}
		if($bAccessories = $arExpValues || ($arElement['PROPERTY_EXPANDABLES_FILTER_VALUE'] && $arTmpExp['CHILDREN']))
		{
			if($arExpValues)
			{
				$arAllValues['EXPANDABLES'] = $arExpValues;
			}
			else
			{
				$arTab['EXPANDABLES']['FILTER'] = $arExpandablesFilter;
			}
		}
		
		if(!$arRules['ASSOCIATED'])
		{
			// similar goods from property with type filter
			if($arElement['PROPERTY_ASSOCIATED_FILTER_VALUE'])
			{
				$cond = new CMaxCondition();
				try{
					$arTmpAssoc = \Bitrix\Main\Web\Json::decode($arElement['PROPERTY_ASSOCIATED_FILTER_VALUE']);
					$arAssociatedFilter = $cond->parseCondition($arTmpAssoc, $arParams);
				}
				catch(\Exception $e){
					$arAssociatedFilter = array();
				}
				unset($cond);
			}

			// similar goods from property with type link
			
			if(!$arElement['PROPERTY_ASSOCIATED_FILTER_VALUE'] || !$arTmpAssoc || !$arTmpAssoc['CHILDREN'])
			{
				if($arAssociated = $arElement['PROPERTY_ASSOCIATED_VALUE'])
				{
					$arAllValues['ASSOCIATED'] = $arAssociated;
				}
			}
		}
		if($bSimilar = $arAssociated || ($arElement['PROPERTY_ASSOCIATED_FILTER_VALUE'] && $arTmpAssoc['CHILDREN']))
		{
			if($arAssociated)
			{
				$arAllValues['ASSOCIATED'] = $arAssociated;
			}
			else
			{
				$arTab['ASSOCIATED']['FILTER'] = $arAssociatedFilter;
			}
		}

		global $arBigData;
		$arBigData = array(
			'SECTION_ID' => $arElement['IBLOCK_SECTION_ID'],
			'BIGDATA_SHOW_FROM_SECTION' => $arParams['BIGDATA_SHOW_FROM_SECTION'],
		);

		if(($arParams["LINKED_ELEMENT_TAB_SORT_FIELD"] == "REGION_PRICE" || $arParams["LINKED_ELEMENT_TAB_SORT_FIELD2"] == "REGION_PRICE")
			&& $arParams["SORT_REGION_PRICE"]) {
			$arPriceSort = [];
			global $arRegion;
			if ($arRegion) {
				if (!$arRegion["PROPERTY_SORT_REGION_PRICE_VALUE"] || $arRegion["PROPERTY_SORT_REGION_PRICE_VALUE"] == "component") {
					$price = CCatalogGroup::GetList(array(), array("NAME" => $arParams["SORT_REGION_PRICE"]), false, false, array("ID", "NAME"))->GetNext();
					$arPriceSort = array("CATALOG_PRICE_".$price["ID"]);
				} else {
					$arPriceSort = array("CATALOG_PRICE_".$arRegion["PROPERTY_SORT_REGION_PRICE_VALUE"]);
				}
			}
			if ($arPriceSort) {
				if ($arParams["LINKED_ELEMENT_TAB_SORT_FIELD"] == "REGION_PRICE") {
					$arParams["LINKED_ELEMENT_TAB_SORT_FIELD"] = $arPriceSort[0];
				}
				if ($arParams["LINKED_ELEMENT_TAB_SORT_FIELD2"] == "REGION_PRICE") {
					$arParams["LINKED_ELEMENT_TAB_SORT_FIELD2"] = $arPriceSort[0];
				}
			}
		}

		?>

		<div class="product-container catalog_detail js-notice-block detail<?=($isWideBlock == "Y" ? " fixed_wrapper" : "");?> <?=$sViewElementTemplate;?> clearfix" itemscope itemtype="http://schema.org/Product">
			<?@include_once('page_blocks/'.$sViewElementTemplate.'.php');?>
		</div>

		<?
		/*set title for current offer */
		global $currentOfferTitle;
		if(isset($currentOfferTitle) && $arTheme['CHANGE_TITLE_ITEM_DETAIL']['VALUE'] === "Y"){
			$APPLICATION->SetTitle($currentOfferTitle["CURRENT_OFFER_TITLE"]);
			$APPLICATION->SetPageProperty("title", $currentOfferTitle["CURRENT_OFFER_WINDOW_TITLE"]);
		}
		?>

		<?CMax::checkBreadcrumbsChain($arParams, $arSection, $arElement);?>

		<?
		
		/*fix title after ajax form start*/
		$arAdditionalData = $arNavParams = array();

		$postfix = '';
		global $arSite;
		if(\Bitrix\Main\Config\Option::get("aspro.max", "HIDE_SITE_NAME_TITLE", "N")=="N")
			$postfix = ' - '.$arSite['SITE_NAME'];

		$arAdditionalData['TITLE'] = htmlspecialcharsback($APPLICATION->GetTitle());
		$arAdditionalData['WINDOW_TITLE'] = htmlspecialcharsback($APPLICATION->GetTitle('title').$postfix);

		// dirty hack: try to get breadcrumb call params
		for ($i = 0, $cnt = count($APPLICATION->buffer_content_type); $i < $cnt; $i++){
			if ($APPLICATION->buffer_content_type[$i]['F'][1] == 'GetNavChain'){
				$arNavParams = $APPLICATION->buffer_content_type[$i]['P'];
			}
		}
		if ($arNavParams){
			$arAdditionalData['NAV_CHAIN'] = $APPLICATION->GetNavChain($arNavParams[0], $arNavParams[1], $arNavParams[2], $arNavParams[3], $arNavParams[4]);
		}
		?>
		<script type="text/javascript">
			if(!$('.js_seo_title').length)
				$('<span class="js_seo_title" style="display:none;"></span>').appendTo($('body'));
			BX.addCustomEvent(window, "onAjaxSuccess", function(e){
				var arAjaxPageData = <?=CUtil::PhpToJSObject($arAdditionalData, true, true, true);?>;

				//set title from offers
				if(typeof ItemObj == 'object' && Object.keys(ItemObj).length)
				{
					if('TITLE' in ItemObj && ItemObj.TITLE)
					{
						arAjaxPageData.TITLE = ItemObj.TITLE;
						arAjaxPageData.WINDOW_TITLE = ItemObj.WINDOW_TITLE;
					}
				}

				if (arAjaxPageData.TITLE)
					$('h1').html(arAjaxPageData.TITLE);
				if (arAjaxPageData.WINDOW_TITLE || arAjaxPageData.TITLE)
				{
					$('.js_seo_title').html(arAjaxPageData.WINDOW_TITLE || arAjaxPageData.TITLE); //seo fix for spec symbol
					BX.ajax.UpdateWindowTitle($('.js_seo_title').html());
				}

				if (arAjaxPageData.NAV_CHAIN)
					BX.ajax.UpdatePageNavChain(arAjaxPageData.NAV_CHAIN);
				$('.catalog_detail input[data-sid="PRODUCT_NAME"]').attr('value', $('h1').html());
			});
		</script>
		<?/*fix title after ajax form end*/?>
		<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.history.js');?>
	</div>
	<?$bShowLeftBlock = ($APPLICATION->GetProperty("HIDE_LEFT_BLOCK") != "Y" && !defined("ERROR_404") && !($arTheme['HEADER_TYPE']['VALUE'] == 28 || $arTheme['HEADER_TYPE']['VALUE'] == 29));?>
	<?if($bShowLeftBlock):?>
		<p>fff</p>
		<?CMax::ShowPageType('left_block');?>
	<?endif;?>


	<a href="#" class="banner-sale">
	<span class="banner-sale__title">Сочные скидки! <span>до -60% на сантехнику и товары для дома</span></span>
	<span class="banner-sale__image">
		<img src="<?php echo SITE_TEMPLATE_PATH ?>/images/banner_sale1.jpg" alt="alt">
	</span>
</a>
</div>




<?
$collection_property = CIBlockElement::GetProperty($arParams['IBLOCK_ID'], $arResult['VARIABLES']['ELEMENT_ID'], array("sort" => "asc"), Array("CODE"=>"PROP_1240"));
if($result_collection_property = $collection_property->Fetch())
    $coll_param = IntVal($result_collection_property["VALUE"]);


$collection_property_size = CIBlockElement::GetProperty($arParams['IBLOCK_ID'], $arResult['VARIABLES']['ELEMENT_ID'], array("sort" => "asc"), Array("CODE"=>"PROP_2065"));
if($result_collection_property_size = $collection_property_size->Fetch())
	$coll_param_size = $result_collection_property_size["VALUE"];	

	
$arFilter = Array("IBLOCK_ID" => $arParams['IBLOCK_ID'], "ID" => $arResult['VARIABLES']['ELEMENT_ID']);
$res = CIBlockElement::GetList(Array(), $arFilter); // с помощью метода CIBlockElement::GetList вытаскиваем все значения из нужного элемента
if ($ob = $res->GetNextElement()){; // переходим к след элементу, если такой есть
	$arProps = $ob->GetProperties(); // свойства элемента

	$v = $arProps['PROP_1402'];
	$s = $arProps['PROP_1403'];	
}	

$haystack = $arResult['VARIABLES']['SECTION_CODE_PATH'];
$needle   = 'plitka';
$pos      = strripos($haystack, $needle);
?>


<? if(!empty($coll_param)):?>
	<div class="iq-container__">

		<div class="card-related">

			<div class="card-tab-title">
				<div class="card-tab-title__text">Товары из этой коллекции</div>
				<!-- <div class="card-tab-title__counter">184</div>	-->					
			</div>
			<?
			global $collectionFilter;
			$collectionFilter = array('PROPERTY_PROP_1240' => $coll_param);
			?>
			<?$APPLICATION->IncludeComponent(
				"bitrix:catalog.section",
				"catalog_collection",
				Array(
					"ACTION_VARIABLE" => "action",
					"ADD_PICT_PROP" => "MORE_PHOTO",
					"ADD_PROPERTIES_TO_BASKET" => "Y",
					"ADD_SECTIONS_CHAIN" => "N",
					"ADD_TO_BASKET_ACTION" => "ADD",
					"AJAX_MODE" => "N",
					"AJAX_OPTION_ADDITIONAL" => "",
					"AJAX_OPTION_HISTORY" => "N",
					"AJAX_OPTION_JUMP" => "N",
					"AJAX_OPTION_STYLE" => "Y",
					"BACKGROUND_IMAGE" => "UF_BACKGROUND_IMAGE",
					"BASKET_URL" => "/personal/basket.php",
					"BRAND_PROPERTY" => "BRAND_REF",
					"BROWSER_TITLE" => "-",
					"CACHE_FILTER" => "N",
					"CACHE_GROUPS" => "Y",
					"CACHE_TIME" => "36000000",
					"CACHE_TYPE" => "A",
					"COMPATIBLE_MODE" => "Y",
					"CONVERT_CURRENCY" => "Y",
					"CURRENCY_ID" => "RUB",
					"CUSTOM_FILTER" => "",
					"DATA_LAYER_NAME" => "dataLayer",
					"DETAIL_URL" => "",
					"DISABLE_INIT_JS_IN_COMPONENT" => "N",
					"DISCOUNT_PERCENT_POSITION" => "bottom-right",
					"DISPLAY_BOTTOM_PAGER" => "N",
					"DISPLAY_TOP_PAGER" => "N",
					"ELEMENT_SORT_FIELD" => "sort",
					"ELEMENT_SORT_FIELD2" => "id",
					"ELEMENT_SORT_ORDER" => "asc",
					"ELEMENT_SORT_ORDER2" => "desc",
					"ENLARGE_PRODUCT" => "PROP",
					"ENLARGE_PROP" => "NEWPRODUCT",
					"FILTER_NAME" => "collectionFilter",
					"HIDE_NOT_AVAILABLE" => "N",
					"HIDE_NOT_AVAILABLE_OFFERS" => "N",
					"IBLOCK_ID" => $arParams['IBLOCK_ID'],
					"IBLOCK_TYPE" => "collections",
					"INCLUDE_SUBSECTIONS" => "Y",
					"LABEL_PROP" => array("NEWPRODUCT"),
					"LABEL_PROP_MOBILE" => array(),
					"LABEL_PROP_POSITION" => "top-left",
					"LAZY_LOAD" => "Y",
					"LINE_ELEMENT_COUNT" => "4",
					"LOAD_ON_SCROLL" => "N",
					"MESSAGE_404" => "",
					"MESS_BTN_ADD_TO_BASKET" => "В корзину",
					"MESS_BTN_BUY" => "Купить",
					"MESS_BTN_DETAIL" => "Подробнее",
					"MESS_BTN_LAZY_LOAD" => "Показать ещё",
					"MESS_BTN_SUBSCRIBE" => "Подписаться",
					"MESS_NOT_AVAILABLE" => "Нет в наличии",
					"META_DESCRIPTION" => "-",
					"META_KEYWORDS" => "-",
					"OFFERS_CART_PROPERTIES" => array("ARTNUMBER","COLOR_REF","SIZES_SHOES","SIZES_CLOTHES"),
					"OFFERS_FIELD_CODE" => array("",""),
					"OFFERS_LIMIT" => "5",
					"OFFERS_PROPERTY_CODE" => array("COLOR_REF","SIZES_SHOES","SIZES_CLOTHES",""),
					"OFFERS_SORT_FIELD" => "sort",
					"OFFERS_SORT_FIELD2" => "id",
					"OFFERS_SORT_ORDER" => "asc",
					"OFFERS_SORT_ORDER2" => "desc",
					"OFFER_ADD_PICT_PROP" => "MORE_PHOTO",
					"OFFER_TREE_PROPS" => array("COLOR_REF","SIZES_SHOES","SIZES_CLOTHES"),
					"PAGER_BASE_LINK_ENABLE" => "N",
					"PAGER_DESC_NUMBERING" => "N",
					"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
					"PAGER_SHOW_ALL" => "N",
					"PAGER_SHOW_ALWAYS" => "N",
					"PAGER_TEMPLATE" => ".default",
					"PAGER_TITLE" => "Товары",
					"PAGE_ELEMENT_COUNT" => "4",
					"PARTIAL_PRODUCT_PROPERTIES" => "N",
					"PRICE_CODE" => array("BASE"),
					"PRICE_VAT_INCLUDE" => "Y",
					"PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons,compare",
					"PRODUCT_DISPLAY_MODE" => "Y",
					"PRODUCT_ID_VARIABLE" => "id",
					"PRODUCT_PROPERTIES" => array("NEWPRODUCT","MATERIAL"),
					"PRODUCT_PROPS_VARIABLE" => "prop",
					"PRODUCT_QUANTITY_VARIABLE" => "",
					"PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':true}]",
					"PRODUCT_SUBSCRIPTION" => "Y",
					"PROPERTY_CODE" => array("NEWPRODUCT",""),
					"PROPERTY_CODE_MOBILE" => array(),
					"RCM_PROD_ID" => $_REQUEST["PRODUCT_ID"],
					"RCM_TYPE" => "personal",
					"SECTION_CODE" => "",
					"SECTION_ID" => "",
					"SECTION_ID_VARIABLE" => "SECTION_ID",
					"SECTION_URL" => "",
					"SECTION_USER_FIELDS" => array("",""),
					"SEF_MODE" => "N",
					"SET_BROWSER_TITLE" => "N",
					"SET_LAST_MODIFIED" => "N",
					"SET_META_DESCRIPTION" => "N",
					"SET_META_KEYWORDS" => "N",
					"SET_STATUS_404" => "N",
					"SET_TITLE" => "N",
					"SHOW_404" => "N",
					"SHOW_ALL_WO_SECTION" => "N",
					"SHOW_CLOSE_POPUP" => "N",
					"SHOW_DISCOUNT_PERCENT" => "Y",
					"SHOW_FROM_SECTION" => "N",
					"SHOW_MAX_QUANTITY" => "N",
					"SHOW_OLD_PRICE" => "N",
					"SHOW_PRICE_COUNT" => "1",
					"SHOW_SLIDER" => "Y",
					"SLIDER_INTERVAL" => "3000",
					"SLIDER_PROGRESS" => "N",
					"TEMPLATE_THEME" => "blue",
					"USE_ENHANCED_ECOMMERCE" => "Y",
					"USE_MAIN_ELEMENT_SECTION" => "N",
					"USE_PRICE_COUNT" => "N",
					"USE_PRODUCT_QUANTITY" => "N"
				)
			);?>
			
			<div class="btn_wrapper">
				<?
					$iblockID = 73;
					$q = \Bitrix\Iblock\ElementTable::getList(array(
						"select" => array("ID", 'IBLOCK_ID', 'NAME', 'CODE', 'IBLOCK_ID', 'IBLOCK_SECTION_ID', 'DETAIL_PAGE_URL' => 'IBLOCK.DETAIL_PAGE_URL'),
						"filter" => array("IBLOCK_ID" => $iblockID),
					));
					$url = '';
					while($arItem = $q->fetch())
					{


						$qProps = \CIBlockElement::getProperty($arItem['IBLOCK_ID'], $arItem['ID'], ['sort'=>'asc'], ['=ACTIVE'=>'Y']);
						while($arProperty = $qProps->Fetch())
						{
							if($arProperty['CODE'] == 'collection_connect' && $arProperty['VALUE'] == $coll_param)
							{
								$page_url = CIBlock::ReplaceDetailUrl($arItem['DETAIL_PAGE_URL'], $arItem, false, 'E');
								// print_r($page_url);								
								// $url = '/collections/plitka/'.$arItem['ID'].'/';

								$url = $page_url;
							}
						}
					}
					unset($iblockID, $q, $arItem, $qProps, $arProperty);
					//echo "<pre style='color:#02f702; background:#414354;'>";print_r($url);echo "</pre>";
				?>
				<a href="/<?=$url;?>">Больше товаров</a>
			</div>
			
		</div>
	</div>



<?endif;?>



<?
if ($pos !== false):

	if(!empty($coll_param_size) || ($v > 0 && $s > 0)): 

		global $arrFilterRelated;

		if($coll_param_size)
			$arrFilterRelated = array('PROPERTY_PROP_2065' => $coll_param_size);

		if($v > 0 && $s > 0)
			$arrFilterRelated = array('PROPERTY_PROP_1402' => $v, 'PROPERTY_PROP_1403' => $s, '!ID' =>  $arResult['VARIABLES']['ELEMENT_ID']);		
		?>
<?
	global $collectionFilter;
	$collectionFilter = array('PROPERTY_PROP_1240' => $coll_param);
?>
<?
	$filterUrl = $arResult['VARIABLES']['SECTION_CODE_PATH'];
	$filter_last_part = basename($filterUrl);
?>
<!-- $arrFilterRelated -->
		<div class="iq-container__">

			<div class="card-related">

				<div class="card-tab-title">
					<div class="card-tab-title__text">Похожие товары</div>							
				</div>
	<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section", 
	"catalog_block1", 
	array(
		"ACTION_VARIABLE" => "action",
		"ADD_PICT_PROP" => "MORE_PHOTO",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"ADD_SECTIONS_CHAIN" => "N",
		"ADD_TO_BASKET_ACTION" => "ADD",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"BACKGROUND_IMAGE" => "UF_BACKGROUND_IMAGE",
		"BASKET_URL" => "/personal/basket.php",
		"BRAND_PROPERTY" => "BRAND_REF",
		"BROWSER_TITLE" => "-",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"COMPATIBLE_MODE" => "Y",
		"CONVERT_CURRENCY" => "Y",
		"CURRENCY_ID" => "RUB",
		"CUSTOM_FILTER" => "{\"CLASS_ID\":\"CondGroup\",\"DATA\":{\"All\":\"AND\",\"True\":\"True\"},\"CHILDREN\":[]}",
		"DATA_LAYER_NAME" => "dataLayer",
		"DETAIL_URL" => "",
		"DISABLE_INIT_JS_IN_COMPONENT" => "N",
		"DISCOUNT_PERCENT_POSITION" => "bottom-right",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"DISPLAY_TOP_PAGER" => "N",
		"ELEMENT_SORT_FIELD" => "sort",
		"ELEMENT_SORT_FIELD2" => "id",
		"ELEMENT_SORT_ORDER" => "asc",
		"ELEMENT_SORT_ORDER2" => "desc",
		"ENLARGE_PRODUCT" => "PROP",
		"ENLARGE_PROP" => "NEWPRODUCT",
		"FILTER_NAME" => "",
		"HIDE_NOT_AVAILABLE" => "N",
		"HIDE_NOT_AVAILABLE_OFFERS" => "N",
		"IBLOCK_ID" => "61",
		"IBLOCK_TYPE" => "aspro_max_catalog",
		"INCLUDE_SUBSECTIONS" => "Y",
		"LABEL_PROP" => array(
			0 => "NEWPRODUCT",
		),
		"LABEL_PROP_MOBILE" => "",
		"LABEL_PROP_POSITION" => "top-left",
		"LAZY_LOAD" => "Y",
		"LINE_ELEMENT_COUNT" => "5",
		"LOAD_ON_SCROLL" => "N",
		"MESSAGE_404" => "",
		"MESS_BTN_ADD_TO_BASKET" => "В корзину",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_BTN_LAZY_LOAD" => "Показать ещё",
		"MESS_BTN_SUBSCRIBE" => "Подписаться",
		"MESS_NOT_AVAILABLE" => "Нет в наличии",
		"META_DESCRIPTION" => "-",
		"META_KEYWORDS" => "-",
		"OFFERS_CART_PROPERTIES" => array(
			0 => "ARTNUMBER",
			1 => "COLOR_REF",
			2 => "SIZES_SHOES",
			3 => "SIZES_CLOTHES",
		),
		"OFFERS_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"OFFERS_LIMIT" => "5",
		"OFFERS_PROPERTY_CODE" => array(
			0 => "COLOR_REF",
			1 => "SIZES_SHOES",
			2 => "SIZES_CLOTHES",
			3 => "",
		),
		"OFFERS_SORT_FIELD" => "sort",
		"OFFERS_SORT_FIELD2" => "id",
		"OFFERS_SORT_ORDER" => "asc",
		"OFFERS_SORT_ORDER2" => "desc",
		"OFFER_ADD_PICT_PROP" => "MORE_PHOTO",
		"OFFER_TREE_PROPS" => array(
			0 => "COLOR_REF",
			1 => "SIZES_SHOES",
			2 => "SIZES_CLOTHES",
		),
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "Товары",
		"PAGE_ELEMENT_COUNT" => "5",
		"PARTIAL_PRODUCT_PROPERTIES" => "Y",
		"PRICE_CODE" => array(
			0 => "BASE",
		),
		"PRICE_VAT_INCLUDE" => "Y",
		"PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons,compare",
		"PRODUCT_DISPLAY_MODE" => "Y",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRODUCT_PROPERTIES" => array(
			0 => "NEWPRODUCT",
			1 => "MATERIAL",
		),
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PRODUCT_QUANTITY_VARIABLE" => "",
		"PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':true}]",
		"PRODUCT_SUBSCRIPTION" => "Y",
		"PROPERTY_CODE" => array(
			0 => "NEWPRODUCT",
			1 => "",
		),
		"PROPERTY_CODE_MOBILE" => "",
		"RCM_PROD_ID" => $_REQUEST["PRODUCT_ID"],
		"RCM_TYPE" => "personal",
		"SECTION_CODE" => $filter_last_part,
		"SECTION_ID" => $section["ID"],
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"SECTION_URL" => "",
		"SECTION_USER_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"SEF_MODE" => "N",
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SHOW_ALL_WO_SECTION" => "N",
		"SHOW_CLOSE_POPUP" => "N",
		"SHOW_DISCOUNT_PERCENT" => "Y",
		"SHOW_FROM_SECTION" => "N",
		"SHOW_MAX_QUANTITY" => "N",
		"SHOW_OLD_PRICE" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"SHOW_SLIDER" => "Y",
		"SLIDER_INTERVAL" => "3000",
		"SLIDER_PROGRESS" => "N",
		"TEMPLATE_THEME" => "blue",
		"USE_ENHANCED_ECOMMERCE" => "Y",
		"USE_MAIN_ELEMENT_SECTION" => "N",
		"USE_PRICE_COUNT" => "N",
		"USE_PRODUCT_QUANTITY" => "N",
		"COMPONENT_TEMPLATE" => "catalog_block1",
		"SHOW_RATING" => "Y",
		"DISPLAY_COMPARE" => "N"
	),
	false
);?>		

			</div>
		</div>	
		<?
	endif;
endif;?>
