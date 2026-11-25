<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<div class="main-catalog-wrapper">
	<div class="section-content-wrapper">
		<div class="catalog_section_list row items margin0 flexbox type_sections_6">
		<?foreach($arResult["ITEMS"] as $key => $arItem):?>
		<?
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
			$SECTION = CIBlockSection::GetByID($arItem['PROPERTIES']['SECTION']['VALUE'])->GetNext();
			
			$preview_picture = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE']['ID'], ['width' => 300, 'height' => 300], BX_RESIZE_IMAGE_PROPORTIONAL, false, $arWaterMark);
			if(empty($preview_picture['src'])) $arItem['PREVIEW_PICTURE']['SRC'] = '/local/templates/aspro_max_opt_site/images/svg/noimage_product.svg';
			else $arItem['PREVIEW_PICTURE']['SRC'] = $preview_picture['src'];
			
			$activeElements = CIBlockSection::GetSectionElementsCount($SECTION['ID'], Array("CNT_ACTIVE" => "Y"));
		?>
			<div class="item_block lg col-lg-20 col-md-4 col-xs-6">
				<div class="section_item item bordered box-shadow" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
					<table class="section_item_inner">
						<tbody>
							<tr>
								<td class="image">
									<a href="<?=$SECTION['SECTION_PAGE_URL']?>" class="thumb">
										<img class="img-responsive lazyloaded" data-src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="<?=$arItem['NAME']?>" title="<?=$arItem['NAME']?>">
									</a>
								</td>
								<td class="section_info toggle">
									<ul>
										<li class="name text-center">
											<a href="<?=$SECTION['SECTION_PAGE_URL']?>" class="dark_link"><span class="font_md"><?=$arItem['NAME']?></span></a>
											<span class="element-count2 muted font_xs"><?=num_word($activeElements, array('товар', 'товара', 'товаров'))?> </span>
										</li>
									</ul>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		<?endforeach;?>
		</div>
	</div>
</div>