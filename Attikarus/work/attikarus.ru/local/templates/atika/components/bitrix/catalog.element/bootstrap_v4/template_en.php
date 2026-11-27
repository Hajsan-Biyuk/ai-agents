<?
$name = $arResult['PROPERTIES']['TITLE_EN']['VALUE'];

$productPrice = $price['PRINT_RATIO_PRICE'];
if(!empty($arResult['PROPERTIES']['PRICE_UE']['VALUE'])){
	$productPrice = CurrencyFormat(str_replace(',', '.', $arResult['PROPERTIES']['PRICE_UE']['VALUE']), $arResult['PROPERTIES']['PRICE_UE_LABEL']['VALUE_XML_ID']);
}

$ar_res = CIBlockSection::GetList([], ['IBLOCK_ID' => $arParams['IBLOCK_ID'], 'ID' => $arResult['IBLOCK_SECTION_ID']], false, ['*', 'UF_*'], false)->GetNext();

?>
<section class="page-card" id="<?=$itemIds['ID']?>" itemscope itemtype="http://schema.org/Product" product_id="<?=$arResult['ID']?>">
	<div class="container">
		<?//$APPLICATION->IncludeComponent("bitrix:breadcrumb", "bootstrap_v4", ["PATH" => "", "SITE_ID" => "s1", "START_FROM" => "0"]);?>
		<ul class="breadcrumbs" itemprop="http://schema.org/breadcrumb" itemscope="" itemtype="http://schema.org/BreadcrumbList">
			<li class="breadcrumbs__item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
				<a href="/en/" title="Main" itemprop="item">Main</a>
				<meta itemprop="position" content="1">
			</li>
			<li class="breadcrumbs__item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
				<a href="/en/catalog/" title="Catalog" itemprop="item">Catalog</a>
				<meta itemprop="position" content="2">
			</li>
			<? if($ar_res){ ?>
			<li class="breadcrumbs__item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
				<a href="<?=$ar_res['SECTION_PAGE_URL']?>" title="<?=$ar_res['UF_NAME_EN']?>" itemprop="item"><?=$ar_res['UF_NAME_EN']?></a>
				<meta itemprop="position" content="3">
			</li>
			<? } ?>
			<li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem"><span itemprop="item"><?=$name?></span></li>
		</ul>
		<h1 class="title-page"><?=$name?></h1>
		<div class="row">
			<div class="col-lg-1 col-md-2">
				<ul class="slider-nav">
				<?
					if(!empty($actualItem['MORE_PHOTO'])){
						foreach ($actualItem['MORE_PHOTO'] as $key => $photo){
				?>
					<li>
						<div class="slider-nav__item">
							<img src="<?=$photo['SRC']?>" alt="<?=$alt?>">
						</div>
					</li>
				<?
						}
					}
				?>
				</ul>
			</div>
			<div class="col-lg-3 col-md-4 col_for">

				<?php
					// Проверка наличия и значения свойства "HIT_SALE"
					if (!empty($arResult['PROPERTIES']['HIT_SALE']) && $arResult['PROPERTIES']['HIT_SALE']['VALUE'] == 'Y') {
				?>

					<div class="hit-sale">
						<img src="/local/templates/atika/img/hit-sale_eng2.svg">
					</div>

				<?php				
				} 
				?>

				<ul class="slider-for">
				<?
					if(!empty($actualItem['MORE_PHOTO'])){
						foreach ($actualItem['MORE_PHOTO'] as $key => $photo){
				?>
					<li>
						<div class="slider-for__item">
							<img src="<?=$photo['SRC']?>" alt="<?=$alt?>">
						</div>
					</li>
				<?
						}
					}
				?>
				</ul>
			</div>
			<div class="col-lg-4 col-md-6">
				<div class="card-info">
					<div class="card-info__text">
					<? if(!empty($arResult['PROPERTIES']['PREW_EN']['VALUE'])){ ?>
						<p><?=$arResult['PROPERTIES']['PREW_EN']['VALUE']['TEXT']?></p>
					<? } ?>
					</div>
					<div class="card-info__price">
					<? if(!empty($arResult['PROPERTIES']['PRICE_UE']['VALUE'])){ ?>
						<?php if (atikaPriceNegotiable()) { echo 'Price on request'; } else { echo $productPrice.'/'.$arResult['ITEM_MEASURE']['TITLE']; } ?>
					<? } ?>
					</div>
					<div class="card-info__buttons">
						<a href="#buy" class="btn-main btn-main_transparent fancybox" info="<?=htmlspecialchars($name)?>">Order a sample</a>
						<?php if(!empty($arResult['PROPERTIES']['OZON_LINK']['VALUE'])) { ?>
							<a href="<?=$arResult['PROPERTIES']['OZON_LINK']['VALUE']?>" class="btn-main btn-main_transparent" target="_blank">Buy on OZON</a>
						<?php  } ?>
						<a href="#callback" class="btn-main fancybox">Ask a question to the sales department</a>
					</div>
				</div>
			</div>
			<div class="col-lg-4">
				<?$APPLICATION->IncludeFile(SITE_DIR."include/card-sidebar.php", [], ["MODE" => "php"])?>
			</div>
			<div class="col-lg-8">
				<ul class="tabs">
					<li class="active"><a href="#tab_card1">TDS</a></li>
					<? if(!empty($arResult['PROPERTIES']['APPLICATION_EN']['VALUE']) || !empty($arResult['PROPERTIES']['PRODUCTION_VIDEO']['VALUE'])){ ?>
					<li><a href="#tab_card2">Recipe</a></li>
					<? } ?>
					<? if(!empty($arResult['PROPERTIES']['KEEPING_EN']['VALUE']) || !empty($arResult['PROPERTIES']['APPLICATION_VIDEO']['VALUE'])){ ?>
					<li><a href="#tab_card3">Application</a></li>
					<? } ?>
					<? if(!empty($arResult['PROPERTIES']['TRANSPORTATION_EN']['VALUE'])){ ?>
					<li><a href="#tab_card4">Keeping</a></li>
					<? } ?>
					<? if(!empty($arResult['PROPERTIES']['PRECAUTIONS_EN']['VALUE']) || !empty($arResult['PROPERTIES']['USAGE_VIDEO']['VALUE'])){ ?>
					<li><a href="#tab_card5">Usage</a></li>
					<? } ?>
					<? if(!empty($arResult['PROPERTIES']['PACKAGING_EN']['VALUE']) || !empty($arResult['PROPERTIES']['PRESENTATION_VIDEO']['VALUE'])){ ?>
					<li><a href="#tab_card6">Presentation</a></li>
					<? } ?>
					<? if(!empty($arResult['PROPERTIES']['SPECIAL_OFFERS_EN']['VALUE'])){ ?>
					<li><a href="#tab_card7">Special offers</a></li>
					<? } ?>
				</ul>
				<div class="tab-container">
					<div class="tab-pane" id="tab_card1">


						<div class="tab-content-container">
						<div class="video-block">
						<? if(!empty($arResult['PROPERTIES']['PRODUCTION_VIDEO']['VALUE'])){ ?>
							<iframe class="yt_players" width="385" height="230" src="https://www.youtube.com/embed/<?=$arResult['PROPERTIES']['RESIN_VIDEO']['VALUE']?>?rel=0&wmode=Opaque&enablejsapi=1;showinfo=0;controls=0" frameborder="0" allowfullscreen></iframe>
						<?php } ?>
						</div>

						<div class="content-block">
						<? if(!empty($arResult['PROPERTIES']['DESC_EN']['VALUE'])){ ?>
						<div class="text-small">
							<?=$arResult['PROPERTIES']['DESC_EN']['~VALUE']['TEXT']?>
						</div>
						<? } ?>
						<? if(!empty($arResult['PROPERTIES']['FILE_EN']['VALUE'])){ ?>
						<a href="<?=CFile::GetPath($arResult['PROPERTIES']['FILE_EN']['VALUE'])?>" target="_blank" class="link-download">download the product passport <img src="<?=TMPL_URL?>/img/download.svg" alt="alt"></a>
						<? } ?>
						<? if(!empty($arResult['PROPERTIES']['RECIPE_RU']['VALUE'])){ ?>
						<a href="<?=CFile::GetPath($arResult['PROPERTIES']['RECIPE_RU']['VALUE'])?>" target="_blank" class="link-download">Recipe</a>
						<? } ?>
						<? if(!empty($arResult['PROPERTIES']['PRODUCT_PROPERTIES_RU']['VALUE'])){ ?>
						<a href="<?=CFile::GetPath($arResult['PROPERTIES']['PRODUCT_PROPERTIES_RU']['VALUE'])?>" target="_blank" class="link-download">Features</a>
						<? } ?>
						<? if(!empty($arResult['PROPERTIES']['PRODUCT_APPLICATION_RU']['VALUE'])){ ?>
						<a href="<?=CFile::GetPath($arResult['PROPERTIES']['PRODUCT_APPLICATION_RU']['VALUE'])?>" target="_blank" class="link-download">Application</a>
						<? } ?>

						</div>
						</div>
						
					</div>
					<? if(!empty($arResult['PROPERTIES']['APPLICATION_EN']['VALUE']) || !empty($arResult['PROPERTIES']['PRODUCTION_VIDEO']['VALUE'])){ ?>
					<div class="tab-pane" id="tab_card2">

						<div class="tab-content-container">
						<div class="video-block">
						<? if(!empty($arResult['PROPERTIES']['PRODUCTION_VIDEO']['VALUE'])){ ?>
							<iframe class="yt_players" width="385" height="230" src="https://www.youtube.com/embed/<?=$arResult['PROPERTIES']['PRODUCTION_VIDEO']['VALUE']?>?rel=0&wmode=Opaque&enablejsapi=1;showinfo=0;controls=0" frameborder="0" allowfullscreen></iframe>
						<?php } ?>
						</div>

						<div class="content-block">
						<? if(!empty($arResult['PROPERTIES']['APPLICATION_EN']['VALUE'])){ ?>
						<div class="text-small">
							<?=$arResult['PROPERTIES']['APPLICATION_EN']['~VALUE']['TEXT']?>
						</div>
						<? } ?>
						</div>
						</div>

					</div>
					<? } ?>
					<? if(!empty($arResult['PROPERTIES']['KEEPING_EN']['VALUE']) || !empty($arResult['PROPERTIES']['APPLICATION_VIDEO']['VALUE'])){ ?>
					<div class="tab-pane" id="tab_card3">


						<div class="tab-content-container">
						<div class="video-block">
						<? if(!empty($arResult['PROPERTIES']['APPLICATION_VIDEO']['VALUE'])){ ?>
							<iframe class="yt_players" width="385" height="230" src="https://www.youtube.com/embed/<?=$arResult['PROPERTIES']['APPLICATION_VIDEO']['VALUE']?>?rel=0&wmode=Opaque&enablejsapi=1;showinfo=0;controls=0" frameborder="0" allowfullscreen></iframe>
						<?php } ?>
						</div>

						<div class="content-block">
						<? if(!empty($arResult['PROPERTIES']['KEEPING_EN']['VALUE'])){ ?>
						<div class="text-small">
							<?=$arResult['PROPERTIES']['KEEPING_EN']['~VALUE']['TEXT']?>
						</div>
						<? } ?>
						</div>
						</div>

					</div>
					<? } ?>
					<? if(!empty($arResult['PROPERTIES']['TRANSPORTATION_EN']['VALUE']) ){ ?>
					<div class="tab-pane" id="tab_card4">



						<div class="content-block">
						<? if(!empty($arResult['PROPERTIES']['TRANSPORTATION_EN']['VALUE'])){ ?>
						<div class="text-small">
							<?=$arResult['PROPERTIES']['TRANSPORTATION_EN']['~VALUE']['TEXT']?>
						</div>
						<? } ?>
						</div>
						</div>

					</div>
					<? } ?>
					<? if(!empty($arResult['PROPERTIES']['PRECAUTIONS_EN']['VALUE']) || !empty($arResult['PROPERTIES']['USAGE_VIDEO']['VALUE'])){ ?>
					<div class="tab-pane" id="tab_card5">

						<div class="tab-content-container">
						<div class="video-block">
						<? if(!empty($arResult['PROPERTIES']['USAGE_VIDEO']['VALUE'])){ ?>
							<iframe class="yt_players" width="385" height="230" src="https://www.youtube.com/embed/<?=$arResult['PROPERTIES']['USAGE_VIDEO']['VALUE']?>?rel=0&wmode=Opaque&enablejsapi=1;showinfo=0;controls=0" frameborder="0" allowfullscreen></iframe>
						<?php } ?>
						</div>
						
						<div class="content-block">
						<? if(!empty($arResult['PROPERTIES']['PRECAUTIONS_EN']['VALUE'])){ ?>
						<div class="text-small">
							<?=$arResult['PROPERTIES']['PRECAUTIONS_EN']['~VALUE']['TEXT']?>
						</div>
						<? } ?>

						</div>
						</div>

					</div>
					<? } ?>

					<? if(!empty($arResult['PROPERTIES']['PACKAGING_EN']['VALUE']) || !empty($arResult['PROPERTIES']['PRESENTATION_VIDEO']['VALUE'])){ ?>
					<div class="tab-pane" id="tab_card6">

						
						<div class="tab-content-container">
						<div class="video-block">
						<? if(!empty($arResult['PROPERTIES']['PRESENTATION_VIDEO']['VALUE'])){ ?>
							<iframe class="yt_players" width="385" height="230" src="https://www.youtube.com/embed/<?=$arResult['PRESENTATION_VIDEO']['VALUE']?>?rel=0&wmode=Opaque&enablejsapi=1;showinfo=0;controls=0" frameborder="0" allowfullscreen></iframe>
						<?php }?>
						</div>

						<div class="content-block">
						<? if(!empty($arResult['PROPERTIES']['PACKAGING_EN']['VALUE'])){ ?>
						<div class="text-small">
							<?=$arResult['PROPERTIES']['PACKAGING_EN']['~VALUE']['TEXT']?>
						</div>
						<? } ?>
						</div>
						</div>


					</div>
					<? } ?>
					<? if(!empty($arResult['PROPERTIES']['SPECIAL_OFFERS_EN']['VALUE'])){ ?>
					<div class="tab-pane" id="tab_card7">
						<div class="text-small">
							<?=$arResult['PROPERTIES']['SPECIAL_OFFERS_EN']['~VALUE']['TEXT']?>
						</div>
					</div>
					<? } ?>
				</div>
			</div>
		</div>
	</div>
</section>
<meta itemprop="name" content="<?=$name?>" />
<meta itemprop="category" content="<?=$arResult['CATEGORY_PATH']?>" />