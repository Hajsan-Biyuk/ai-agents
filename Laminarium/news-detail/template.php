<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true ); ?>
<?php
function getReadingTime($text, $wordsPerMinute = 180) {
	$cleanText = strip_tags($text);
	$wordCount = str_word_count($cleanText, 0, 'АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯабвгдеёжзийклмнопрстуфхцчшщъыьэюя');
	$minutes = ceil($wordCount / $wordsPerMinute);
	return max($minutes, 1);
}
?>
<?
/*set array props for component_epilog*/
$templateData = array(
	'PRICE' => $arResult['PROPERTIES'][$arParams["PRICE_PROPERTY"]]['VALUE'],
);

/**/
?>
<?php // Новости /company/news/ — новая вёрстка по макету (lam_article.php)
if (strpos($APPLICATION->GetCurPage(false), SITE_DIR.'company/news/') === 0) { include __DIR__.'/lam_article.php'; return; } ?>
<?php
global $APPLICATION;

// Простой и надёжный вариант: по URL
$pagePath = $APPLICATION->GetCurPage(false);
$isServicePage = (strpos($pagePath, '/services/') === 0);

$itemType = $isServicePage ? 'https://schema.org/Service' : 'https://schema.org/Article';
?>
<div itemscope itemtype="<?=$itemType;?>" class="news_detail_wrapp big item">
   <?php if ($isServicePage): ?>
        <!-- Разметка для Service -->
        <meta itemprop="name" content="<?=$arResult["NAME"];?>" />
        <?php if ($arResult["PREVIEW_TEXT"]): ?>
            <meta itemprop="description" content="<?=$arResult["PREVIEW_TEXT"];?>" />
        <?php endif; ?>
        <meta itemprop="serviceType" content="<?=$arResult["NAME"];?>" />
        <meta itemprop="areaServed" content="Краснодар и Краснодарский край" />
        <meta itemprop="url" content="<?=$APPLICATION->GetCurPage(false);?>" />

    <?php else: ?>
        <!-- Разметка для Article -->
        <meta itemprop="headline" content="<?=$arResult["NAME"];?>" />
        <meta itemprop="name" content="<?=$arResult["NAME"];?>" />
        <?php if ($arResult["PREVIEW_TEXT"]): ?>
            <meta itemprop="description" content="<?=$arResult["PREVIEW_TEXT"];?>" />
        <?php endif; ?>
        <meta itemprop="author" content="Крымов Денис Юрьевич" />
        <?php if (!empty($arResult["PROPERTIES"][$arParams["PERIOD_PROPERTY"]]["VALUE"])): ?>
            <meta itemprop="datePublished"
                  content="<?=$arResult["PROPERTIES"][$arParams["PERIOD_PROPERTY"]]["VALUE"];?>" />
        <?php endif; ?>
    <?php endif; ?>

    <?if( !empty($arResult["DETAIL_PICTURE"])):?>
        <div class="detail_picture_block clearfix">
            <?$img = CFile::ResizeImageGet($arResult["DETAIL_PICTURE"], array( "width" => 270, "height" => 185 ), BX_RESIZE_IMAGE_PROPORTIONAL, true, array() );?>
            <a href="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>" class="fancy">
                <img src="<?=$img["src"]?>"
                     alt="<?=($arResult["DETAIL_PICTURE"]["ALT"] ? $arResult["DETAIL_PICTURE"]["ALT"] : $arResult["NAME"])?>"
                     title="<?=($arResult["DETAIL_PICTURE"]["TITLE"] ? $arResult["DETAIL_PICTURE"]["TITLE"] : $arResult["NAME"])?>"/>
            </a>
        </div>
    <?endif;?>

    <?if($arParams["DISPLAY_DATE"]!="N"):?>
        <?if($arResult["PROPERTIES"][$arParams["PERIOD_PROPERTY"]]["VALUE"] || $arResult["DISPLAY_ACTIVE_FROM"]):?>
            <?php
            $readingTime = getReadingTime($arResult["DETAIL_TEXT"]);
            ?>
            <div class="article-meta">
                <span class="news_date_time_detail date_small">
                    <?=($arResult["PROPERTIES"][$arParams["PERIOD_PROPERTY"]]["VALUE"] ?: $arResult["DISPLAY_ACTIVE_FROM"]);?>
                </span>
                <span class="article-readtime">⏱ <?= $readingTime ?> мин. чтения</span>
            </div>
        <?endif;?>
    <?endif;?>

    <?php if (!empty($arResult["TIMESTAMP_X"])): ?>
        <div class="article-updated-date">
            Информация обновлена: <?= CIBlockFormatProperties::DateFormat("d.m.Y", MakeTimeStamp($arResult["TIMESTAMP_X"])) ?>
        </div>
    <?php endif; ?>

    <?if ($arResult["PREVIEW_TEXT"]):?>
        <div class="<?if ($left_side):?>right_side<?endif;?> margin preview_text"><?=$arResult["PREVIEW_TEXT"];?></div>
    <?endif;?>

    <div id="article-toc" class="article-toc">
      <strong>Содержание:</strong>
      <ul></ul>
    </div>

    <?if ($arResult["DETAIL_TEXT"]):?>
        <div class="detail_text <?=($arResult["DETAIL_PICTURE"] ? "wimg" : "");?>">
            <?php if ($isServicePage): ?>
                <div itemprop="description">
                    <?=$arResult["DETAIL_TEXT"]?>
                </div>
            <?php else: ?>
                <div itemprop="articleBody">
                    <?=$arResult["DETAIL_TEXT"]?>
                </div>
            <?php endif; ?>
		<strong>Автор статьи: </strong><br> <a href="https://www.laminarium.com/info/articles/2023/ekspert_krymov_denis_yurevich/"> Крымов Денис Юрьевич</a> - Эксперт в области синтетических композитных полов и ламината
		</div>
	<?endif;?>
	<?
	$arDisplayProperties = array();
		foreach($arResult["DISPLAY_PROPERTIES"] as $arProp){
			if(!in_array($arProp["CODE"], array($arParams["GALLERY_PROPERTY"], $arParams["LINKED_PRODUCTS_PROPERTY"], "VIDEO", "VIDEO_YOUTUBE"))){
				if((!is_array($arProp["DISPLAY_VALUE"]) && strlen($arProp["DISPLAY_VALUE"])) || (is_array($arProp["DISPLAY_VALUE"]) && implode('', $arProp["DISPLAY_VALUE"]))){
					$arDisplayProperties[] = $arProp;
				}
			}
		}
	?>
	<?if($arDisplayProperties):?>
		<table class="props_list" style="width:100%;">
			<?foreach($arDisplayProperties as $arProp):?>
				<tr>
					<td class="char_name">
						<span <?if($arProp["HINT"] && $arParams["SHOW_HINTS"] == "Y"){?>class="whint"<?}?>><?if($arProp["HINT"] && $arParams["SHOW_HINTS"] == "Y"):?><div class="hint"><span class="icon"><i>?</i></span><div class="tooltip"><?=$arProp["HINT"]?></div></div><?endif;?><?=($arProp["~NAME"] ? $arProp["~NAME"] : $arProp["NAME"]);?></span>
					</td>
					<td class="char_value">
						<span>
							<?if(count($arProp["DISPLAY_VALUE"]) > 1):?>
								<?=implode(', ', $arProp["DISPLAY_VALUE"]);?>
							<?else:?>
								<?=$arProp["DISPLAY_VALUE"];?>
							<?endif;?>
						</span>
					</td>
				</tr>
			<?endforeach;?>
		</table>
	<?endif;?>

	<?if($arParams["SHOW_SERVICES_BLOCK"]!="Y" && $arResult["PROPERTIES"][$arParams["PRICE_PROPERTY"]]["VALUE"]):?>
		<div class="price_block">
			<div class="price"><?=GetMessage("SERVICE_PRICE")?> <?=$arResult["PROPERTIES"][$arParams["PRICE_PROPERTY"]]["VALUE"];?></div>
		</div>
	<?endif;?>

	<div class="clear"></div>
	<?if($arParams["SHOW_GALLERY"]=="Y" && $arResult["PROPERTIES"][$arParams["GALLERY_PROPERTY"]]["VALUE"]){?>
		<div class="row galley">
			<ul class="module-gallery-list">
				<?
					$files = $arResult["PROPERTIES"][$arParams["GALLERY_PROPERTY"]]["VALUE"];
					if(array_key_exists('SRC', $files))
					{
					   $files['SRC'] = '/' . substr($files['SRC'], 1);
					   $files['ID'] = $arResult["PROPERTIES"][$arParams["GALLERY_PROPERTY"]]["VALUE"][0];
					   $files = array($files);
					}
				?>
				<?	foreach($files as $arFile):?>
					<?
						$img = CFile::ResizeImageGet($arFile, array('width'=>230, 'height'=>155), BX_RESIZE_IMAGE_EXACT, true);

						$img_big = CFile::ResizeImageGet($arFile, array('width'=>800, 'height'=>600), BX_RESIZE_IMAGE_PROPORTIONAL, true);
						$img_big = $img_big['src'];
					?>
					<li class="item_block">
						<a href="<?=$img_big;?>" class="fancy" data-fancybox-group="gallery">
							<img src="<?=$img["src"];?>" alt="<?=$arResult["NAME"];?>" title="<?=$arResult["NAME"];?>" />
						</a>
					</li>
				<?endforeach;?>
			</ul>
			<div class="clear"></div>
		</div>
	<?}?>
<br><br><br>
<div class="wrapper_inner">
<div>Рекомендуемые товары по теме: </div>
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
	</div>
	<?/*if ($arParams["SHOW_FAQ_BLOCK"]=="Y"):?>
		<div class="ask_big_block">
			<div class="ask_btn_block">
				<a class="button vbig_btn wides ask_btn"><span><?=GetMessage("ASK_QUESTION")?></span></a>
			</div>
			<div class="description">
				<?$APPLICATION->IncludeFile(SITE_DIR."include/ask_block_detail_description.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("ASK_QUESTION_DETAIL_TEXT"), ));?>
			</div>
		</div>
	<?endif;*/?>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const article = document.querySelector('[itemprop="articleBody"]');
  const tocList = document.querySelector('#article-toc ul');

  if (article && tocList) {
    const headers = article.querySelectorAll('h2');
    headers.forEach((header, index) => {
      const id = 'section-' + index;
      header.id = id;

      const li = document.createElement('li');
      const a = document.createElement('a');
      a.href = '#' + id;
      a.textContent = header.textContent;

      li.appendChild(a);
      tocList.appendChild(li);
    });
  }
});
</script>