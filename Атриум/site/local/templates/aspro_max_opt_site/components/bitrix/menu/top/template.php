<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?$this->setFrameMode(true);?>
<?
global $arTheme;
$iVisibleItemsMenu = ($arTheme['MAX_VISIBLE_ITEMS_MENU']['VALUE'] ? $arTheme['MAX_VISIBLE_ITEMS_MENU']['VALUE'] : 10);
$MENU_TYPE = $arTheme['MEGA_MENU_TYPE']['VALUE'];
$bManyItemsMenu = ($MENU_TYPE == '4');
// var_dump($MENU_TYPE);
// var_dump($bManyItemsMenu);
$arParams["MAX_LEVEL"] = 3;
?>
<?if($arResult):?>
	<?if (!function_exists('showSubItemss')) {
		function showSubItemss($arParams = [
			'HAS_PICTURE' => false,
			'HAS_ICON' => false,
			'WIDE_MENU' => false,
			'SHOW_CHILDS' => false,
			'VISIBLE_ITEMS_MENU' => 0,
			'MAX_LEVEL' => 0,
			'ITEM' => [],
		]){?>

			<?if ($arParams['DETAIL_BUTTON']):?>
				<div>
					<div class="subitems-wrapper-button">
						<a href="<?=$arParams['DETAIL_BUTTON_LINK']?>" class="catalog-menu-section-menu__btn"><?=$arParams['DETAIL_BUTTON']?></a>
						<i class="svg inline  svg-inline-right" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="8" height="8" viewBox="0 0 3 5"><path data-name="Rectangle 4 copy" class="cls-1" d="M203,84V79l3,2.5Z" transform="translate(-203 -79)"></path></svg></i>
					</div>
				</div>
			<?endif;?>			
			<?
			//var_dump($arParams['DETAIL_BUTTON']);
			?>	
			<?
			// var_dump($arParams['ITEM']['DEPTH_LEVEL']);
			// var_dump($arParams['DETAIL_BUTTON']);

			if($arParams['HAS_PICTURE'] && $arParams['WIDE_MENU']):
				$arSubItemImg = ( (isset($arParams['ITEM']['PARAMS']['SECTION_ICON'])) ? $arParams['ITEM']['PARAMS']['SECTION_ICON'] : $arParams['ITEM']['PARAMS']['PICTURE'] );
				$arImg = CFile::ResizeImageGet($arSubItemImg, array('width' => 60, 'height' => 60), BX_RESIZE_IMAGE_PROPORTIONAL_ALT);

				if(is_array($arImg)):?>
					<div class="menu_img <?=$arParams['HAS_ICON'] ? 'icon' : ''?>">
						<a href="<?=$arParams['ITEM']["LINK"]?>" class="noborder img_link colored_theme_svg">
							<?if(strpos($arImg["src"], ".svg") !== false):?>
								<?=CMax::showIconSvg("cat_icons light-ignore", $arImg["src"]);?>
							<?else:?>
								<img class="lazy" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($arImg["src"]);?>" data-src="<?=$arImg["src"]?>" alt="<?=$arParams['ITEM']["TEXT"]?>" title="<?=$arParams['ITEM']["TEXT"]?>" />
							<?endif;?>
						</a>
					</div>
				<?endif;?>
			<?endif;?>
			<?if($arParams['ITEM']["LINK"]):?>
				<a href="<?=$arParams['ITEM']["LINK"]?>" title="<?=$arParams['ITEM']["TEXT"]?>">
			<?endif;?>
			<span class="name <?=$arParams['WIDE_MENU'] ? 'option-font-bold' : ''?>"><?=$arParams['ITEM']["TEXT"]?></span><?=($arParams['ITEM']["CHILD"] && $arParams['SHOW_CHILDS'] ? CMax::showIconSvg("right light-ignore", SITE_TEMPLATE_PATH.'/images/svg/trianglearrow_right.svg', '', '') : '')?>
			<?if($arParams['ITEM']["LINK"]):?>
				</a>
			<?endif;?>

			<?if($arParams['ITEM']["CHILD"] && $arParams['SHOW_CHILDS']):?>
				<?$iCountChilds = count($arParams['ITEM']["CHILD"]);?>
				<?
				// echo '<pre>';
				// print_r($arParams['ITEM']['DEPTH_LEVEL']);
				// echo '</pre>';
				?>
				<ul class="dropdown-menu<?=($arParams['ITEM']['DEPTH_LEVEL'] > 2 ? '-list' : '')?> toggle_menu level-<?=$arParams['ITEM']['DEPTH_LEVEL']?>">
					<?foreach($arParams['ITEM']["CHILD"] as $key => $arSubSubItem):?>
						<?$arParams['SHOW_CHILDS'] = $arParams["MAX_LEVEL"] > 3;?>
						<li class="menu-item <?=(++$key > $arParams['VISIBLE_ITEMS_MENU'] ? 'collapsed' : '');?> <?=($arSubSubItem["CHILD"] && $arParams['SHOW_CHILDS'] ? "dropdown-submenu dropdown-submenu-wrapper" : "")?> <?=($arSubSubItem["SELECTED"] ? "active" : "")?>">
							<a href="<?=$arSubSubItem["LINK"]?>" title="<?=$arSubSubItem["TEXT"]?>"><span class="name"><?=$arSubSubItem["TEXT"]?></span></a>
							<?if($arSubSubItem["CHILD"] && $arParams['SHOW_CHILDS']):?>
								<ul class="dropdown-menu">
									<?foreach($arSubSubItem["CHILD"] as $arSubSubSubItem):?>
										<li class="menu-item <?=($arSubSubSubItem["SELECTED"] ? "active" : "")?>">
											<a href="<?=$arSubSubSubItem["LINK"]?>" title="<?=$arSubSubSubItem["TEXT"]?>"><span class="name"><?=$arSubSubSubItem["TEXT"]?></span></a>
										</li>
									<?endforeach;?>
								</ul>

							<?endif;?>
						</li>
					<?endforeach;?>
					<?if($iCountChilds > $arParams['VISIBLE_ITEMS_MENU'] && $arParams['WIDE_MENU']):?>
						<li><span class="colored_theme_hover_text more_items with_dropdown"><?=\Bitrix\Main\Localization\Loc::getMessage("S_MORE_ITEMS").' '.($iCountChilds-$arParams['VISIBLE_ITEMS_MENU']);?></span></li>
					<?endif;?>
				</ul>
			<?endif;?>
		<?}?>
	<?}?>
	<div class="table-menu" itemscope="" itemtype="https://schema.org/SiteNavigationElement">
		<table itemprop="about" itemscope="" itemtype="https://schema.org/ItemList">
			<tr>
				<?
				// echo '<pre>';
				// 		print_r($arResult);
				// 		echo '</pre>';
				?>
				<?foreach($arResult as $arItem):?>
					<?$bShowChilds = $arParams["MAX_LEVEL"] > 1;

					if(!$arItem['TEXT']) continue;
					
					$bWideMenu = (isset($arItem['PARAMS']['CLASS']) && strpos($arItem['PARAMS']['CLASS'], 'wide_menu') !== false);

					if (isset($arItem['PARAMS']['CLASS']) && strpos($arItem['PARAMS']['CLASS'], 'wide_menu_catalog') !== false ) {
						$bWideMenu = true;
					}
					$arItem['bManyItemsMenu'] = $bManyItemsMenu;
					if(!$bWideMenu) {
						$arItem['bManyItemsMenu'] = false;
					}	
									
					// wide_menu_catalog
					?>

					<td class="menu-item unvisible <?=($arItem["CHILD"] ? "dropdown" : "")?> <?=(isset($arItem["PARAMS"]["CLASS"]) ? $arItem["PARAMS"]["CLASS"] : "");?>  <?=($arItem["SELECTED"] ? "active" : "")?>" itemprop="itemListElement" itemscope ="" itemtype="https://schema.org/ItemList">
						<div class="wrap">
							<a class="<?=($arItem["CHILD"] && $bShowChilds ? "dropdown-toggle" : "")?>" href="<?=$arItem["LINK"]?>" itemprop="url">
								<div>
									<div class="close-menu">
										<i class="svg inline  svg-inline-" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"><path data-name="Rounded Rectangle 114 copy 3" class="cccls-1" d="M334.411,138l6.3,6.3a1,1,0,0,1,0,1.414,0.992,0.992,0,0,1-1.408,0l-6.3-6.306-6.3,6.306a1,1,0,0,1-1.409-1.414l6.3-6.3-6.293-6.3a1,1,0,0,1,1.409-1.414l6.3,6.3,6.3-6.3A1,1,0,0,1,340.7,131.7Z" transform="translate(-325 -130)"></path></svg></i>
									</div>
									<?if(isset($arItem["PARAMS"]["ICON"]) && $arItem["PARAMS"]["ICON"]):?>
										<?=CMax::showIconSvg($arItem["PARAMS"]["ICON"], SITE_TEMPLATE_PATH.'/images/svg/'.$arItem["PARAMS"]["ICON"].'.svg', '', '');?>
									<?endif;?>
									<?=$arItem["TEXT"]?>

									<?if(isset($arItem["PARAMS"]["CLASS"]) && strpos($arItem["PARAMS"]["CLASS"], "catalog") !== false):?>
										<?=CMax::showIconSvg("down", SITE_TEMPLATE_PATH.'/images/svg/trianglearrow_down.svg', '', '');?>
									<?endif;?>
								</div>
							</a>
							<meta itemprop="name" content="<?=$arItem["TEXT"]?>" />

							<?if($arItem["CHILD"] && $bShowChilds):?>
								<?$bRightSide = ($arItem["BANNERS"] || $arResult['BRANDS']) && $bWideMenu;?>
								<span class="tail"></span>
								<div class="level-<?=$arItem['DEPTH_LEVEL']?> dropdown-menu <?=$bRightSide ? 'with_right_block' : ''?> <?=$arItem['bManyItemsMenu'] ? 'long-menu-items' : ''?> <?=$arTheme['SHOW_RIGHT_SIDE']['DEPENDENT_PARAMS']['RIGHT_CONTENT']['VALUE']?>">
									<?if($arItem['bManyItemsMenu']):?>
										<div class="menu-navigation">
											<div class="menu-navigation__sections-wrapper">
												<div class="customScrollbar scrollblock">
													<div class="menu-navigation__sections">
														<?foreach($arItem["CHILD"] as $arChild):?>
															<div class="menu-navigation__sections-item<?=($arChild['SELECTED'] ? " active" : "");?>">
																<?$bShowImg = ((isset($arChild['PARAMS']['PICTURE']) && $arChild['PARAMS']['PICTURE'] || (isset($arChild['PARAMS']['SECTION_ICON']))) /*&& $arTheme['LEFT_BLOCK_CATALOG_ICONS']['VALUE'] == 'Y' */);
																$bIcon = (isset($arChild['PARAMS']['SECTION_ICON'])) && $arChild['PARAMS']['SECTION_ICON'];?>
																	<? //var_dump($arChild['PARAMS']['UFBUTTON']); ?>
														
																<a
																	href="<?=$arChild['LINK']?>"
																	class="menu-navigation__sections-item-link <?=($arChild["SELECTED"] ? "colored_theme_text" : "dark_link")?> <?=($bShowImg ? " menu-navigation__sections-item-link--image" : "");?><?=($arChild['CHILD'] ? " menu-navigation__sections-item-dropdown" : "");?>"
																>
																	<?if($arChild['CHILD']):?>
																		<?=CMax::showIconSvg("right", SITE_TEMPLATE_PATH.'/images/svg/trianglearrow_right.svg', '', '');?>
																	<?endif;?>
																	<?if($bShowImg){?>
																		<span class="image colored_theme_svg ">
																			<?$imageID = ((isset($arChild['PARAMS']['SECTION_ICON'])) ? $arChild['PARAMS']['SECTION_ICON'] : $arChild['PARAMS']['PICTURE']);?>
																			<?$image = CFile::GetPath($imageID);?>
																			<?if(strpos($image, ".svg") !== false):?>
																				<?=CMax::showIconSvg("cat_icons light-ignore", $image);?>
																			<?else:?>
																				<img class="lazy" data-src="<?=$image;?>" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($image);?>" alt="<?=$arChild["NAME"];?>" title="<?=$arChild["NAME"]?>" />
																			<?endif;?>
																		</span>
																	<?}?>
																	<span class="name"><?=$arChild['TEXT'];?></span>
																</a>
																<?
																/*if ($USER->IsAdmin()){
																?>
																<?
																if($arChild['TEXT'] == 'Сантехника'){?>
																<div class="chut">
																<?foreach ($arItem['CHILD'][1]['CHILD'] as $key => $value) {?>
																	<a href="<?=$value['LINK']?>" class="menu-navigation__sections-item-link font_xs <?=($value["SELECTED"] ? "colored_theme_text" : "dark_link")?> <?=($bShowImg ? " menu-navigation__sections-item-link--image" : "");?><?=($value['CHILD'] ? " menu-navigation__sections-item-dropdown" : "");?>">
																	<?if($arChild['CHILD']):?>
																		<?=CMax::showIconSvg("right", SITE_TEMPLATE_PATH.'/images/svg/trianglearrow_right.svg', '', '');?>
																	<?endif;?>
																	<span class="name"><?=$value['TEXT']?></span>
																</a>
																<?	}//foreach?>
																</div>
																<?}//if
																?>
																<?}*/?>
															</div>
														<?endforeach;?>
													</div>
												</div>
											</div>
											<div class="menu-navigation__content">
												<div class="close-item-absolute">
													<a href="#">														
														<i class="svg inline  svg-inline-" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"><path data-name="Rounded Rectangle 114 copy 3" class="cccls-1" d="M334.411,138l6.3,6.3a1,1,0,0,1,0,1.414,0.992,0.992,0,0,1-1.408,0l-6.3-6.306-6.3,6.306a1,1,0,0,1-1.409-1.414l6.3-6.3-6.293-6.3a1,1,0,0,1,1.409-1.414l6.3,6.3,6.3-6.3A1,1,0,0,1,340.7,131.7Z" transform="translate(-325 -130)"></path></svg></i>													
													</a>
												</div>
									<?endif;?>
									<div class="customScrollbar <?=($bWideMenu ? 'scrollblock scrollblock--thick' : '');?>">
										<?if($bRightSide && !$arItem['bManyItemsMenu']):?>
											<div class="right-side">
												<div class="right-content">
													<?if($arItem["BANNERS"]):?>
														<?
														if($GLOBALS['arRegionLink']) {
															$GLOBALS['rightBannersFilter'] = array_merge( $GLOBALS['arRegionLink'], array('ID' => $arItem["BANNERS"]) );
														} else {
															$GLOBALS['rightBannersFilter'] = array('ID' => $arItem["BANNERS"]);
														}
														$APPLICATION->IncludeComponent(
															"bitrix:news.list",
															"banners",
															array(
																"IBLOCK_TYPE" => "aspro_max_adv",
																"IBLOCK_ID" => CMaxCache::$arIBlocks[SITE_ID]["aspro_max_adv"]["aspro_max_banners_inner"][0],
																"PAGE"		=> $APPLICATION->GetCurPage(),
																'MENU_BANNER' => true,
																//'MENU_LINK' => $arItem['link'],
																"NEWS_COUNT" => "100",
																"SHOW_ALL_ELEMENTS" => 'Y',
																"SORT_BY1" => "NAME",
																"SORT_ORDER1" => "ASC",
																"SORT_BY2" => "ID",
																"SORT_ORDER2" => "ASC",
																"FIELD_CODE" => array(
																	0 => "NAME",
																	2 => "PREVIEW_PICTURE",
																),
																"PROPERTY_CODE" => array(
																	0 => "LINK",
																	1 => "TARGET",
																	2 => "BGCOLOR",
																	3 => "SHOW_SECTION",
																	4 => "SHOW_PAGE",
																	5 => "HIDDEN_XS",
																	6 => "HIDDEN_SM",
																	7 => "POSITION",
																	8 => "SIZING",
																),
																"CHECK_DATES" => "Y",
																"FILTER_NAME" => "rightBannersFilter",
																"DETAIL_URL" => "",
																"AJAX_MODE" => "N",
																"AJAX_OPTION_JUMP" => "N",
																"AJAX_OPTION_STYLE" => "Y",
																"AJAX_OPTION_HISTORY" => "N",
																"CACHE_TYPE" => "A",
																"CACHE_TIME" => "3600000",
																"CACHE_FILTER" => "Y",
																"CACHE_GROUPS" => "N",
																"PREVIEW_TRUNCATE_LEN" => "150",
																"ACTIVE_DATE_FORMAT" => "d.m.Y",
																"SET_TITLE" => "N",
																"SET_STATUS_404" => "N",
																"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
																"ADD_SECTIONS_CHAIN" => "N",
																"HIDE_LINK_WHEN_NO_DETAIL" => "N",
																"PARENT_SECTION" => "",
																"PARENT_SECTION_CODE" => "",
																"INCLUDE_SUBSECTIONS" => "Y",
																"PAGER_TEMPLATE" => ".default",
																"DISPLAY_TOP_PAGER" => "N",
																"DISPLAY_BOTTOM_PAGER" => "N",
																"PAGER_TITLE" => "",
																"PAGER_SHOW_ALWAYS" => "N",
																"PAGER_DESC_NUMBERING" => "N",
																"PAGER_DESC_NUMBERING_CACHE_TIME" => "3600000",
																"PAGER_SHOW_ALL" => "N",
																"AJAX_OPTION_ADDITIONAL" => "",
																"SHOW_DETAIL_LINK" => "N",
																"SET_BROWSER_TITLE" => "N",
																"SET_META_KEYWORDS" => "N",
																"SET_META_DESCRIPTION" => "N",
																"COMPONENT_TEMPLATE" => "banners",
																"SET_LAST_MODIFIED" => "N",
																"COMPOSITE_FRAME_MODE" => "A",
																"COMPOSITE_FRAME_TYPE" => "AUTO",
																"PAGER_BASE_LINK_ENABLE" => "N",
																"SHOW_404" => "N",
																"MESSAGE_404" => ""
															),
															false, array('ACTIVE_COMPONENT' => 'Y', 'HIDE_ICONS' => 'Y')
														);
														?>
													<?elseif($arResult['BRANDS']):?>
														<div class="brands-wrapper">
															<?foreach ($arResult['BRANDS'] as $brand):?>
																<div class="brand-wrapper">
																	<?if($brand['DETAIL_PAGE_URL']):?>
																		<a href="<?=$brand['DETAIL_PAGE_URL']?>">
																	<?endif;?>
																		<img src="<?=CFile::GetPath($brand['PREVIEW_PICTURE'])?>" alt="<?=$brand['NAME']?>" title="<?=$brand['NAME']?>" />
																	<?if($brand['DETAIL_PAGE_URL']):?>
																		</a>
																	<?endif;?>
																</div>
															<?endforeach;?>
														</div>
													<?endif;?>
												</div>
											</div>
										<?endif;?>

										<ul class="menu-wrapper <?='menu-type-'.$MENU_TYPE?>" >
											<?foreach($arItem["CHILD"] as $arSubItem):?>
												<?if($MENU_TYPE == 2 && $bWideMenu):?>
													<?
													$bHasPicture = ( (isset($arSubItem['PARAMS']['PICTURE']) && $arSubItem['PARAMS']['PICTURE'] || (isset($arSubItem['PARAMS']['SECTION_ICON'])) ) && $arTheme['SHOW_CATALOG_SECTIONS_ICONS']['VALUE'] == 'Y');
													$bIcon = (isset($arSubItem['PARAMS']['SECTION_ICON'])) && $arSubItem['PARAMS']['SECTION_ICON'];
													?>
													<li class="<?=($arSubItem["SELECTED"] ? "active" : "")?> <?=$bIcon ? 'icon' : ''?> <?=($bHasPicture ? "has_img" : "")?>">
														<?if($bHasPicture && $bWideMenu):
															$arSubItemImg = ( (isset($arSubItem['PARAMS']['SECTION_ICON'])) ? $arSubItem['PARAMS']['SECTION_ICON'] : $arSubItem['PARAMS']['PICTURE'] );
															$arImg = CFile::ResizeImageGet($arSubItemImg, array('width' => 80, 'height' => 80), BX_RESIZE_IMAGE_PROPORTIONAL);
															if(is_array($arImg)):?>
																<div class="menu_img <?=$bIcon ? 'icon' : ''?>">
																	<a href="<?=$arSubItem["LINK"]?>" class="noborder colored_theme_svg">
																		<?if(strpos($arImg["src"], ".svg") !== false):?>
																			<?=CMax::showIconSvg("cat_icons light-ignore", $arImg["src"]);?>
																		<?else:?>
																			<img class="lazy" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($arImg["src"]);?>" data-src="<?=$arImg["src"]?>" alt="<?=$arSubItem["TEXT"]?>" title="<?=$arSubItem["TEXT"]?>" />
																		<?endif;?>
																	</a>
																</div>
															<?endif;?>
														<?endif;?>
														<a href="<?=$arSubItem["LINK"]?>" title="<?=$arSubItem["TEXT"]?>"><span class="name option-font-bold"><?=$arSubItem["TEXT"]?></span></a>
													</li>
												<?else: // type 1?>
													<?
													$bShowChilds = $arParams["MAX_LEVEL"] > 2;?>
													<?
													$bHasPicture = ( (isset($arSubItem['PARAMS']['PICTURE']) && $arSubItem['PARAMS']['PICTURE'] || (isset($arSubItem['PARAMS']['SECTION_ICON'])) ) && $arTheme['SHOW_CATALOG_SECTIONS_ICONS']['VALUE'] == 'Y');
													$bIcon = (isset($arSubItem['PARAMS']['SECTION_ICON'])) && $arSubItem['PARAMS']['SECTION_ICON'];
													?>
													<li class="level-menu-<?=($arSubItem["DEPTH_LEVEL"])?> <?=($arSubItem["CHILD"] && $bShowChilds ? "dropdown-submenu" : "")?> <?=$bIcon ? 'icon' : ''?> <?=($arSubItem["SELECTED"] ? "active" : "")?> <?=($bHasPicture ? "has_img" : "")?> parent-items">
														<?if($arItem['bManyItemsMenu']):?>
															<div class="flexbox flex-reverse">
																<?$bRightBanners = ($arSubItem["PARAMS"]["BANNERS"] && $arTheme['SHOW_RIGHT_SIDE']['DEPENDENT_PARAMS']['RIGHT_CONTENT']['VALUE'] == 'BANNER')?>
																<?$bRightBrands = ($arSubItem["PARAMS"]["BRANDS"] && $arSubItem["BRANDS"] && $arTheme['SHOW_RIGHT_SIDE']['DEPENDENT_PARAMS']['RIGHT_CONTENT']['VALUE'] == 'BRANDS')?>
																<?$bRightSide = (($bRightBanners || $bRightBrands) && $arTheme['SHOW_RIGHT_SIDE']['VALUE'] == 'Y');?>
																<?if($bRightSide):?>
																	<div class="right-side">
																		<div class="right-content">
																			<?if($bRightBanners):?>
																				<?
																				if($GLOBALS['arRegionLink']) {
																					$GLOBALS['rightBannersFilter'] = array_merge( $GLOBALS['arRegionLink'], array('ID' => $arSubItem["PARAMS"]["BANNERS"]) );
																				} else {
																					$GLOBALS['rightBannersFilter'] = array('ID' => $arSubItem["PARAMS"]["BANNERS"]);
																				}
																				$APPLICATION->IncludeComponent(
																					"bitrix:news.list",
																					"banners",
																					array(
																						"IBLOCK_TYPE" => "aspro_max_adv",
																						"IBLOCK_ID" => CMaxCache::$arIBlocks[SITE_ID]["aspro_max_adv"]["aspro_max_banners_inner"][0],
																						"PAGE"		=> $APPLICATION->GetCurPage(),
																						'MENU_BANNER' => true,
																						'SHOW_ALL_ELEMENTS' => 'N',
																						//'MENU_LINK' => $arItem['link'],
																						"NEWS_COUNT" => "100",
																						"SORT_BY1" => "NAME",
																						"SORT_ORDER1" => "ASC",
																						"SORT_BY2" => "ID",
																						"SORT_ORDER2" => "ASC",
																						"FIELD_CODE" => array(
																							0 => "NAME",
																							2 => "PREVIEW_PICTURE",
																						),
																						"PROPERTY_CODE" => array(
																							0 => "LINK",
																							1 => "TARGET",
																							2 => "BGCOLOR",
																							3 => "SHOW_SECTION",
																							4 => "SHOW_PAGE",
																							5 => "HIDDEN_XS",
																							6 => "HIDDEN_SM",
																							7 => "POSITION",
																							8 => "SIZING",
																						),
																						"CHECK_DATES" => "Y",
																						"FILTER_NAME" => "rightBannersFilter",
																						"DETAIL_URL" => "",
																						"AJAX_MODE" => "N",
																						"AJAX_OPTION_JUMP" => "N",
																						"AJAX_OPTION_STYLE" => "Y",
																						"AJAX_OPTION_HISTORY" => "N",
																						"CACHE_TYPE" => "A",
																						"CACHE_TIME" => "3600000",
																						"CACHE_FILTER" => "Y",
																						"CACHE_GROUPS" => "N",
																						"PREVIEW_TRUNCATE_LEN" => "150",
																						"ACTIVE_DATE_FORMAT" => "d.m.Y",
																						"SET_TITLE" => "N",
																						"SET_STATUS_404" => "N",
																						"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
																						"ADD_SECTIONS_CHAIN" => "N",
																						"HIDE_LINK_WHEN_NO_DETAIL" => "N",
																						"PARENT_SECTION" => "",
																						"PARENT_SECTION_CODE" => "",
																						"INCLUDE_SUBSECTIONS" => "Y",
																						"PAGER_TEMPLATE" => ".default",
																						"DISPLAY_TOP_PAGER" => "N",
																						"DISPLAY_BOTTOM_PAGER" => "N",
																						"PAGER_TITLE" => "",
																						"PAGER_SHOW_ALWAYS" => "N",
																						"PAGER_DESC_NUMBERING" => "N",
																						"PAGER_DESC_NUMBERING_CACHE_TIME" => "3600000",
																						"PAGER_SHOW_ALL" => "N",
																						"AJAX_OPTION_ADDITIONAL" => "",
																						"SHOW_DETAIL_LINK" => "N",
																						"SET_BROWSER_TITLE" => "N",
																						"SET_META_KEYWORDS" => "N",
																						"SET_META_DESCRIPTION" => "N",
																						"COMPONENT_TEMPLATE" => "banners",
																						"SET_LAST_MODIFIED" => "N",
																						"COMPOSITE_FRAME_MODE" => "A",
																						"COMPOSITE_FRAME_TYPE" => "AUTO",
																						"PAGER_BASE_LINK_ENABLE" => "N",
																						"SHOW_404" => "N",
																						"MESSAGE_404" => ""
																					),
																					false, array('ACTIVE_COMPONENT' => 'Y', 'HIDE_ICONS' => 'Y')
																				);
																				?>
																			<?elseif($bRightBrands):?>
																				<div class="brands-wrapper">
																					<?foreach ($arSubItem["BRANDS"] as $brand):?>
																						<div class="brand-wrapper">
																							<?if($brand['DETAIL_PAGE_URL']):?>
																								<a href="<?=$brand['DETAIL_PAGE_URL']?>">
																							<?endif;?>
																								<img src="<?=CFile::GetPath($brand['PREVIEW_PICTURE'])?>" alt="<?=$brand['NAME']?>" title="<?=$brand['NAME']?>" />
																							<?if($brand['DETAIL_PAGE_URL']):?>
																								</a>
																							<?endif;?>
																						</div>
																					<?endforeach;?>
																				</div>
																			<?endif;?>
																		</div>
																	</div>
																<?endif;?>
																<!-- Тут сделать группировки -->
																<div class="subitems-wrapper">
																	<?//var_dump($arSubItem);?>
																
																	<?//var_dump($arSubItem['PARAMS']['UFBUTTON']);?>
																	<ul class="menu-wrapper" >
																		<?$ckey = 0;?>
																		<?foreach($arSubItem["CHILD"] as $arSubItem2):?>

																			<?
																			$bHasPicture = ( (isset($arSubItem2['PARAMS']['PICTURE']) && $arSubItem2['PARAMS']['PICTURE'] || (isset($arSubItem2['PARAMS']['SECTION_ICON'])) ) && $arTheme['SHOW_CATALOG_SECTIONS_ICONS']['VALUE'] == 'Y');
																			$bIcon = (isset($arSubItem2['PARAMS']['SECTION_ICON'])) && $arSubItem2['PARAMS']['SECTION_ICON'];
																			
																			?>
																			<li class="<?=($arSubItem2["CHILD"] && $bShowChilds ? "dropdown-submenu dropdown-submenu-".$arSubItem2['DEPTH_LEVEL'] : "")?> <?=$bIcon ? 'icon' : ''?> <?=($arSubItem2["SELECTED"] ? "active" : "")?> <?=($bHasPicture ? "has_img" : "")?>">
																				
																				<?if($ckey == 0):?>
																					<?=showSubItemss([
																						'DETAIL_BUTTON' => $arSubItem['PARAMS']['UFBUTTON'],
																						'DETAIL_BUTTON_LINK' => $arSubItem['LINK'],
																						// 'DEPTH_LEVEL' => ,
																						'HAS_PICTURE' => $bHasPicture,
																						'HAS_ICON' => $bIcon,
																						'WIDE_MENU' => true,
																						'SHOW_CHILDS' => $bShowChilds,
																						'VISIBLE_ITEMS_MENU' => $iVisibleItemsMenu,
																						'ITEM' => $arSubItem2,
																						'MAX_LEVEL' => $arParams["MAX_LEVEL"]
																					]);?>
																				<?else:?>
																					<?=showSubItemss([
																						//'DETAIL_BUTTON' => $arSubItem['PARAMS']['UFBUTTON'],
																						// 'DEPTH_LEVEL' => ,
																						'HAS_PICTURE' => $bHasPicture,
																						'HAS_ICON' => $bIcon,
																						'WIDE_MENU' => true,
																						'SHOW_CHILDS' => $bShowChilds,
																						'VISIBLE_ITEMS_MENU' => $iVisibleItemsMenu,
																						'ITEM' => $arSubItem2,
																						'MAX_LEVEL' => $arParams["MAX_LEVEL"]
																					]);?>
																				<?endif;?>																				

																			</li>
																			<?$ckey++;?>
																		<?endforeach;?>
																	</ul>
																</div>
															</div>
														<?else:?>
															<?=showSubItemss([
																'HAS_PICTURE' => $bHasPicture,
																'HAS_ICON' => $bIcon,
																'WIDE_MENU' => $bWideMenu,
																'SHOW_CHILDS' => $bShowChilds,
																'VISIBLE_ITEMS_MENU' => $iVisibleItemsMenu,
																'ITEM' => $arSubItem,
																'MAX_LEVEL' => $arParams["MAX_LEVEL"]
															]);?>
														<?endif;?>
													</li>
												<?endif;?>
											<?endforeach;?>
										</ul>
									</div>
									<?if($arItem['bManyItemsMenu']):?>
											</div>
										</div>
									<?endif;?>
								</div>
							<?endif;?>
						</div>
					</td>
				<?endforeach;?>

				<td class="menu-item dropdown js-dropdown nosave unvisible" itemprop="itemListElement" itemscope ="" itemtype="https://schema.org/ItemList">
					<div class="wrap">
						<a class="dropdown-toggle more-items" href="#" itemprop="url">
							<span><?=\Bitrix\Main\Localization\Loc::getMessage("S_MORE_ITEMS");?></span>
						</a>
						<span class="tail"></span>
						<ul class="dropdown-menu"></ul>
					</div>
				</td>

			</tr>
		</table>
	</div>
	<script data-skip-moving="true">
		CheckTopMenuPadding();
		CheckTopMenuOncePadding();
		CheckTopMenuDotted();
	</script>
<?endif;?>