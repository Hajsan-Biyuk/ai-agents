<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bas\Pict;?>
<?if($arResult["ITEMS"]):?> 
<div id="CarouselSale" class="owl-carousel"> 
	<?foreach($arResult["ITEMS"] as $cell=>$arElement):
	?>
	<?
	$this->AddEditAction($arElement['ID'], $arElement['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arElement['ID'], $arElement['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));
	?>
	<div class="Item" id="<?=$this->GetEditAreaId($arElement['ID']);?>">
		<div class="Item_Inner">
						<div class="Image">
							<?if (is_array($arElement["PROPERTIES"]["STIKER"]["VALUE_XML_ID"])):?>
							<div class="Stiker">
								<?foreach($arElement["PROPERTIES"]["STIKER"]["VALUE_XML_ID"] as $key=>$class){?>
								<span class="<?=$class?>"><?=$arElement["PROPERTIES"]["STIKER"]["VALUE"][$key]?></span>
								<?}?>
							</div>
							<?endif;?>
							<?if($arElement["PREVIEW_PICTURE"]):
								$PICTURE = CFile::ResizeImageGet($arElement["PREVIEW_PICTURE"], Array('width'=>235, 'height'=>160), BX_RESIZE_IMAGE_EXACT, true);
							?> 
							<a href="<?=$arElement["DETAIL_PAGE_URL"]?>"><img src="<?=Pict::getResizeWebpSrc($arElement['PREVIEW_PICTURE'], 235, 160)?>" width="235" height="200" alt="<?=$arElement["NAME"]?>" title="<?=$arElement["NAME"]?>"></a>
							<?endif?>
						</div>
						<?if($arElement["PROPERTIES"]["BREND"]["VALUE"]):?> 
						<div class="Brend">
							<?
							$res = CIBlockElement::GetByID($arElement["PROPERTIES"]["BREND"]["VALUE"]);
							if($ar_res = $res->GetNext())
							 echo "<a href=".$ar_res['DETAIL_PAGE_URL'].">".$ar_res['NAME']."</a>";
							?>
							</div>
						<?endif?>
						<div class="Name"><a href="<?=$arElement["DETAIL_PAGE_URL"]?>"><?=$arElement["NAME"]?></a></div>		
						<div class="buys">		
							<div class="PriceBlock Price<?$res = CIBlockElement::GetByID($arElement["ID"]);
										if($ar_res = $res->GetNext())
										{
										 echo $ar_res['IBLOCK_SECTION_ID'];
										}
									?>">							
								<?if (empty($arElement['STARTSHOP']['OFFERS'])):?>
 <?$priceValue = trim((string)$arElement['STARTSHOP']['PRICES']['MINIMAL']['PRINT_VALUE']);?>
<?if($priceValue !== ''):?>
<div class="Price">
	<span><?=$priceValue?></span> 
	<?if($arElement["PROPERTIES"]["MEASURE"]["VALUE"]):?>&#1088;&#1091;&#1073;. /<?=$arElement["PROPERTIES"]["MEASURE"]["~VALUE"]?><?endif?>
</div>
<?else:?>
<div class="Price"><span>&#1094;&#1077;&#1085;&#1072; &#1087;&#1086; &#1079;&#1072;&#1087;&#1088;&#1086;&#1089;&#1091;</span></div>
<?endif;?>
												<?if($priceValue !== '' && $arElement["PROPERTIES"]["OLD_PRICE"]["VALUE"]):?>
												<div class="OldPrice">
													<span><?=$arElement["PROPERTIES"]["OLD_PRICE"]["VALUE"]?></span> руб. <?if($arElement["PROPERTIES"]["MEASURE"]["VALUE"]):?>/<?=$arElement["PROPERTIES"]["MEASURE"]["~VALUE"]?><?endif?>
												</div>
												<?endif?>
 <?else:?>
 <?$arPrice = CStartShopToolsIBlock::GetOffersMinPrice($arElement);?>
 <?if (!empty($arPrice)):?>
 <div class="Price"><?=GetMessage("FROM")?><span><?=$arPrice['PRINT_VALUE']?></span>
										<?if($arElement["PROPERTIES"]["MEASURE"]["VALUE"]):?>руб. /<?=$arElement["PROPERTIES"]["MEASURE"]["~VALUE"]?><?endif?>
									</div>
 <?endif;?>
 <?unset($arPrice);?>
 <?endif;?>	
							</div>
							<?if($arElement["PROPERTIES"]["NALICHIE"]["VALUE"]):?>
							<div class="Availability"><?if($arElement["PROPERTIES"]["NALICHIE"]["VALUE_ENUM_ID"]=="30"):?><div class="available"><?else:?><div class="unavailable"><?endif?><?=$arElement["PROPERTIES"]["NALICHIE"]["VALUE"]?></div></div>
							<?endif?>	
						</div>
		</div>
	</div>
	<?endforeach; // foreach($arResult["ITEMS"] as $arElement):?>
</div> 
<?endif;?>