<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?if($arResult['ITEMS']):?>
	<div class="top_big_one_banner top_big_banners nop <?=($arResult['HAS_CHILD_BANNERS'] ? 'with_childs' : '');?> <?=($arParams['MORE_HEIGHT'] ? 'more_height' : '');?>" style="overflow: hidden;">
		<?/*<div class="wave-background"></div>*/?>
		<?include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/components/aspro/com.banners.max/common_files/slider.php');?>
		<?if($arResult['HAS_SLIDE_BANNERS'] && $arResult['HAS_CHILD_BANNERS']):?>
			<div class="items clearfix">
				<?foreach($arResult['ITEMS'][$arParams['BANNER_TYPE_THEME_CHILD']]['ITEMS'] as $key => $arItem):?>
					<?include('float.php');?>
				<?endforeach;?>
			</div>
		<?endif;?>
		<?/*if($arResult['HAS_CHILD_BANNERS2']):?>
            <div class="items <?=$arParams['SLIDER_VIEW_MOBILE']?><?=($arParams['SLIDER_VIEW_MOBILE'] === 'slider' ? ' swipeignore mobile-overflow' : '')?> c_<?=count($arResult['ITEMS'][$arParams['BANNER_TYPE_THEME_CHILD2']]['ITEMS']);?>">
                <?foreach($arResult['ITEMS'][$arParams['BANNER_TYPE_THEME_CHILD2']]['ITEMS'] as $key => $arItem):?>
                    <?include('float.php');?>
                <?endforeach;?>
            </div>
		<?endif;*/?>
		<?if($arResult['HAS_CHILD_BANNERS2']):?>
			<div class="boxFlexSection desktop-only">
				<div class="flexItem">
					<div class="owl-carousel additional-banners-carousel owl-theme">
						<?foreach($arResult['ITEMS'][$arParams['BANNER_TYPE_THEME_CHILD2']]['ITEMS'] as $key => $arItem):?>
							<?
							if ($arItem["PROPERTIES"]["leftPase"]['VALUE_XML_ID'] != 1) {
								continue;
							}
							// echo "<pre>";
							// 	var_dump($arItem["PROPERTIES"]["leftPase"]['VALUE_XML_ID']); echo "</pre>"; 
								?>
							<?include('float.php');?>
						<?endforeach;?>
					</div>						
				</div>		
				<div class="flexItem">
					<div class="owl-carousel additional-banners-carousel2 owl-theme">
						<?foreach($arResult['ITEMS'][$arParams['BANNER_TYPE_THEME_CHILD2']]['ITEMS'] as $key => $arItem):?>
							<?
							if ($arItem["PROPERTIES"]["leftPase"]['VALUE_XML_ID'] == 1) {
								continue;
							}
								?>								
							<?include('float.php');?>
						<?endforeach;?>
					</div>							
				</div>
			</div>
			<div class="mobile-only additional-banners">
				<div class="owl-carousel additional-banners-carousel-mobile-only owl-theme">
					<?foreach($arResult['ITEMS'][$arParams['BANNER_TYPE_THEME_CHILD2']]['ITEMS'] as $key => $arItem):?>								
						<?include('float.php');?>
					<?endforeach;?>
				</div>
			</div>				
			<?
			if ($_GET['dev'] == 1):?>
				

			<?else:?>
				<!--<div class="owl-carousel additional-banners-carousel owl-theme"> -->
					<?//foreach($arResult['ITEMS'][$arParams['BANNER_TYPE_THEME_CHILD2']]['ITEMS'] as $key => $arItem):?>
						<?//include('float.php');?>
					<?//endforeach;?>
				<!--</div> -->
			<? endif; ?>			
		<?php if(false) {?>
			<?
			if ($_GET['dev'] == 1):?>
				<div class="boxFlexSection">
					<div class="flexItem">
						<div class="owl-carousel additional-banners-carousel-mobile owl-theme">
							<?foreach($arResult['ITEMS'][$arParams['BANNER_TYPE_THEME_CHILD2']]['ITEMS'] as $key => $arItem):?>
								<?include('float_mobile.php');?>
							<?endforeach;?>
						</div>			
					</div>
					<div class="flexItem"></div>		
				</div>
			<?else:?>
				<div class="owl-carousel additional-banners-carousel-mobile owl-theme">
					<?foreach($arResult['ITEMS'][$arParams['BANNER_TYPE_THEME_CHILD2']]['ITEMS'] as $key => $arItem):?>
						<?include('float_mobile.php');?>
					<?endforeach;?>
				</div>
			<?
			endif;
			?>			

		<?php } ?>
		<?endif;?>
	</div>
<?endif;?>
<?$templateData['EMPTY_ITEMS'] = !count($arResult['ITEMS']);?>