<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?
global $arTheme, $arRegion, $bLongHeader3;
$arRegions = CMaxRegionality::getRegions();
if($arRegion)
	$bPhone = ($arRegion['PHONES'] ? true : false);
else
	$bPhone = ((int)$arTheme['HEADER_PHONES'] ? true : false);
$logoClass = ($arTheme['COLORED_LOGO']['VALUE'] !== 'Y' ? '' : ' colored');
$bLongHeader3 = true;
?>
<div class="header-wrapper header-v11 centered">
	<div class="logo_and_menu-row">
		<div class="logo-row">
			<div class="maxwidth-theme">
				<div class="row">
					<div class="logo-block col-md-2 text-center nopadding">
						<div class="logotype<?=$logoClass?>">
							<a href="/">
								<img src="/upload/CMax/68c/cq5pgacl358d11zz9bc23vz02242slue.png" alt="АTRIUM" title="АTRIUM" data-src="">
							</a>
						</div>
					</div>					
					<div class="col-lg-5 col-md-7 col-sm-3">
						<?if($arRegions):?>
							<div class="inline-block pull-left">
								<div class="top-description no-title">
									<?\Aspro\Functions\CAsproMax::showRegionList();?>
								</div>
							</div>
						<?endif;?>
						<div class="wrap_icon inner-table-block">
							<div class="phone-block block2">
								<div class="header-phone">
									<div><a rel="nofollow" href="tel:+79788005556">+7(978)800-55-56</a></div>
									<div><a rel="nofollow" href="tel:+79787703530">+7(978)770-35-30</a></div>
								</div>
								<?if($bPhone):?>
									<?//CMax::ShowHeaderPhones('no-icons', false, true);?>
								<?endif?>

								<?$callbackExploded = explode(',', $arTheme['SHOW_CALLBACK']['VALUE']);
								if( in_array('HEADER', $callbackExploded) ):?>

								<div>
									<div class="inline-block__">
										<span class="callback-block animate-load font_upper_xs colored" data-event="jqm" data-param-form_id="CALLBACK" data-name="callback"><?=GetMessage("CALLBACK")?></span>
									</div>

									<!--<div class="inline-block__">
									
										<span class="callback-block animate-load font_upper_xs colored" data-event="jqm" data-param-form_id="13" data-name="ask"><?=GetMessage("CALLBACK_DESIGN")?></span>
									</div> -->
								</div>


								<?endif;?>
							</div>
						</div>
					</div>


					<div class="right_wrap col-md-3 col-lg-5 pull-right wb">
						<div class="right-icons">
							<div class="pull-right">
								<?=CMax::ShowBasketWithCompareLink('', 'big', '', 'wrap_icon wrap_basket baskets');?>
							</div>

							<div class="pull-right">
								<div class="wrap_icon inner-table-block person">
									<?=CMax::showCabinetLink(true, true, 'big');?>
								</div>
							</div>

							<!--<div class="pull-right">
								<div class="wrap_icon">
									<button class="top-btn inline-search-show">
										<?=CMax::showIconSvg("search", SITE_TEMPLATE_PATH."/images/svg/Search.svg");?>
										<span class="title"><?=GetMessage("CT_BST_SEARCH_BUTTON")?></span>
									</button>
								</div>
							</div> -->
								<div class="type-wrap">
									<?$APPLICATION->IncludeComponent("bitrix:search.title", 'bootstrap_v4'/*(isset($arTheme["TYPE_SEARCH"]["VALUE"]) ? $arTheme["TYPE_SEARCH"]["VALUE"] : $arTheme["TYPE_SEARCH"])*/, array(
											"CATEGORY_0" => array("iblock_aspro_max_catalog"),
											"CATEGORY_0_TITLE" => "ALL",
											"CATEGORY_0_iblock_aspro_max_catalog" => array("61", "58"),
											"CATEGORY_OTHERS_TITLE" => "OTHER",
											"CHECK_DATES" => "Y",
											"COMPOSITE_FRAME_MODE" => "A",
											"COMPOSITE_FRAME_TYPE" => "AUTO",
											"CONTAINER_ID" => "title-search",
											"CONVERT_CURRENCY" => "N",
											"INPUT_ID" => "title-search-input",
											"NUM_CATEGORIES" => "1",
											"ORDER" => "date",
											"PAGE" => CMax::GetFrontParametrValue("CATALOG_PAGE_URL"),
											"PREVIEW_HEIGHT" => "38",
											"PREVIEW_TRUNCATE_LEN" => "50",
											"PREVIEW_WIDTH" => "38",
											"PRICE_CODE" => array(0=>"BASE",),
											"PRICE_VAT_INCLUDE" => "Y",
											"SHOW_ANOUNCE" => "N",
											"SHOW_INPUT" => "Y",
											"SHOW_OTHERS" => "N",
											"SHOW_PREVIEW" => "Y",
											"TOP_COUNT" => "10",
											"USE_LANGUAGE_GUESS" => "Y",
										),
										false, array("HIDE_ICONS" => "Y")
									);?>

								</div>								

						</div>
					</div>
				</div>
			</div>
		</div><?// class=logo-row?>
	</div>
	<?
	// var_dump(SITE_DIR."include/menu/menu.".($arTheme["HEADER_TYPE"]["LIST"][$arTheme["HEADER_TYPE"]["VALUE"]]["ADDITIONAL_OPTIONS"]["MENU_HEADER_TYPE"]["VALUE"] == "Y" ? "top_catalog_wide" : "top").".php");
	?>
	<div class="menu-row middle-block bglight">
		<hr class="left_border">
		<div class="maxwidth-theme">
			<div class="row">
				<div class="col-md-12">
					<div class="menu-only">
						<nav class="mega-menu sliced">
							<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
								array(
									"COMPONENT_TEMPLATE" => ".default",
									"PATH" => SITE_DIR."include/menu/menu.".($arTheme["HEADER_TYPE"]["LIST"][$arTheme["HEADER_TYPE"]["VALUE"]]["ADDITIONAL_OPTIONS"]["MENU_HEADER_TYPE"]["VALUE"] == "Y" ? "top_catalog_wide" : "top").".php",
									"AREA_FILE_SHOW" => "file",
									"AREA_FILE_SUFFIX" => "",
									"AREA_FILE_RECURSIVE" => "Y",
									"EDIT_TEMPLATE" => "include_area.php"
								),
								false, array("HIDE_ICONS" => "Y")
							);?>
							
						</nav>
					</div>
				</div>
			</div>
		</div>
		<hr class="right_border">
	</div>
	<div class="line-row visible-xs"></div>
</div>
<div id="header-fixed-margin"></div>