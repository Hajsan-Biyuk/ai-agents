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

<div class="fixed-buttons">
	<a href="/basket/" class="fixed-buttons__btn">
		<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3.33336 5.34501L0.630859 2.64334L1.81003 1.46417L4.51169 4.16667H17.2134C17.3432 4.16666 17.4713 4.19702 17.5874 4.25531C17.7035 4.31361 17.8043 4.39823 17.8819 4.50242C17.9594 4.60661 18.0116 4.72749 18.0341 4.85541C18.0566 4.98333 18.049 5.11475 18.0117 5.23917L16.0117 11.9058C15.9602 12.0776 15.8548 12.2281 15.711 12.3352C15.5671 12.4422 15.3926 12.5 15.2134 12.5H5.00003V14.1667H14.1667V15.8333H4.16669C3.94568 15.8333 3.73372 15.7455 3.57744 15.5893C3.42116 15.433 3.33336 15.221 3.33336 15V5.34501ZM4.58336 19.1667C4.25184 19.1667 3.9339 19.035 3.69948 18.8006C3.46506 18.5661 3.33336 18.2482 3.33336 17.9167C3.33336 17.5852 3.46506 17.2672 3.69948 17.0328C3.9339 16.7984 4.25184 16.6667 4.58336 16.6667C4.91488 16.6667 5.23282 16.7984 5.46724 17.0328C5.70166 17.2672 5.83336 17.5852 5.83336 17.9167C5.83336 18.2482 5.70166 18.5661 5.46724 18.8006C5.23282 19.035 4.91488 19.1667 4.58336 19.1667ZM14.5834 19.1667C14.2518 19.1667 13.9339 19.035 13.6995 18.8006C13.4651 18.5661 13.3334 18.2482 13.3334 17.9167C13.3334 17.5852 13.4651 17.2672 13.6995 17.0328C13.9339 16.7984 14.2518 16.6667 14.5834 16.6667C14.9149 16.6667 15.2328 16.7984 15.4672 17.0328C15.7017 17.2672 15.8334 17.5852 15.8334 17.9167C15.8334 18.2482 15.7017 18.5661 15.4672 18.8006C15.2328 19.035 14.9149 19.1667 14.5834 19.1667Z" fill="#2B5840" /></svg>
		<div class="fixed-buttons__count">1</div>
	</a>
	<a href="/personal/"  data-event="jqm" data-param-type="auth" data-param-backurl="/catalog/plitka/dlya-vannoy/" data-name="auth" class="fixed-buttons__btn">
		<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_359_902)"><path d="M16.6666 18.3333H3.33325V16.6667C3.33325 15.5616 3.77224 14.5018 4.55364 13.7204C5.33504 12.939 6.39485 12.5 7.49992 12.5H12.4999C13.605 12.5 14.6648 12.939 15.4462 13.7204C16.2276 14.5018 16.6666 15.5616 16.6666 16.6667V18.3333ZM9.99992 10.8333C9.34331 10.8333 8.69313 10.704 8.0865 10.4527C7.47987 10.2015 6.92868 9.83315 6.46438 9.36886C6.00009 8.90457 5.63179 8.35337 5.38052 7.74675C5.12925 7.14012 4.99992 6.48994 4.99992 5.83333C4.99992 5.17672 5.12925 4.52654 5.38052 3.91991C5.63179 3.31328 6.00009 2.76209 6.46438 2.29779C6.92868 1.8335 7.47987 1.4652 8.0865 1.21393C8.69313 0.962657 9.34331 0.833328 9.99992 0.833328C11.326 0.833328 12.5978 1.36011 13.5355 2.29779C14.4731 3.23548 14.9999 4.50725 14.9999 5.83333C14.9999 7.15941 14.4731 8.43118 13.5355 9.36886C12.5978 10.3065 11.326 10.8333 9.99992 10.8333Z" fill="#2B5840" /></g><defs><clipPath id="clip0_359_902"><rect width="20" height="20" fill="white" /></clipPath></defs></svg>
	</a>
	<a rel="nofollow" class="fixed-buttons__btn delay" href="javascript:void(0)" data-href="/basket/#delayed">
		<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_359_905)"><path d="M13.7501 2.5C16.2817 2.5 18.3334 4.58333 18.3334 7.5C18.3334 13.3333 12.0834 16.6667 10.0001 17.9167C7.91675 16.6667 1.66675 13.3333 1.66675 7.5C1.66675 4.58333 3.75008 2.5 6.25008 2.5C7.80008 2.5 9.16675 3.33333 10.0001 4.16667C10.8334 3.33333 12.2001 2.5 13.7501 2.5Z" fill="#2B5840" /></g><defs><clipPath id="clip0_359_905"><rect width="20" height="20" fill="white" /></clipPath></defs></svg>
	</a>
	<div class="fixed-buttons__btn" data-event="jqm" data-param-form_id="ASK" data-name="ask">
		<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_359_908)"><path d="M2.50008 2.5H17.5001C17.7211 2.5 17.9331 2.5878 18.0893 2.74408C18.2456 2.90036 18.3334 3.11232 18.3334 3.33333V16.6667C18.3334 16.8877 18.2456 17.0996 18.0893 17.2559C17.9331 17.4122 17.7211 17.5 17.5001 17.5H2.50008C2.27907 17.5 2.06711 17.4122 1.91083 17.2559C1.75455 17.0996 1.66675 16.8877 1.66675 16.6667V3.33333C1.66675 3.11232 1.75455 2.90036 1.91083 2.74408C2.06711 2.5878 2.27907 2.5 2.50008 2.5ZM10.0501 9.73583L4.70675 5.19833L3.62758 6.46833L10.0609 11.9308L16.3784 6.46417L15.2884 5.20333L10.0509 9.73583H10.0501Z" fill="#2B5840" /></g><defs><clipPath id="clip0_359_908"><rect width="20" height="20" fill="white" /></clipPath></defs></svg>
	</div>
	<div class="fixed-buttons__btn" data-event="jqm" data-param-form_id="REVIEW" data-name="review">
		<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_359_911)"><path d="M8.33341 2.5H11.6667C13.4349 2.5 15.1306 3.20238 16.3808 4.45262C17.631 5.70286 18.3334 7.39856 18.3334 9.16667C18.3334 10.9348 17.631 12.6305 16.3808 13.8807C15.1306 15.131 13.4349 15.8333 11.6667 15.8333V18.75C7.50008 17.0833 1.66675 14.5833 1.66675 9.16667C1.66675 7.39856 2.36913 5.70286 3.61937 4.45262C4.86961 3.20238 6.5653 2.5 8.33341 2.5Z" fill="#2B5840" /></g><defs><clipPath id="clip0_359_911"><rect width="20" height="20" fill="white" /></clipPath></defs></svg>
	</div>
</div>

<div class="header-wrapper header-v11 centered">
	<div class="logo_and_menu-row">
		<div class="logo-row">
			<div class="maxwidth-theme">
				<div class="line-header">
					<div class="logo-block text-center nopadding">
						<div class="logotype<?=$logoClass?>">
							<a href="/">
								<img src="<?php echo SITE_TEMPLATE_PATH ?>/images/logo_new2.png" alt="АTRIUM" title="АTRIUM" data-src="">
							</a>
						</div>
					</div>		

					<div class="rating-header">
						<svg width="24" height="16" viewBox="0 0 24 16" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_768_2255)"><path fill-rule="evenodd" clip-rule="evenodd" d="M10.8674 16H8.00931V2.16802H6.73535C4.39792 2.16802 3.17935 3.30623 3.17935 5.01897C3.17935 6.94851 4.02127 7.84824 5.77157 8.99729L7.2117 9.95122L3.07965 16.0217H0L3.72217 10.6016C1.58414 9.09485 0.376648 7.63144 0.376648 5.17073C0.376648 2.09214 2.57007 0 6.72427 0H10.8563V16H10.8674Z" fill="black" /><path d="M22.5763 5.36588L18.1562 5.05151C17.8904 5.02983 17.6688 4.86723 17.5691 4.62875L15.8853 0.617908C15.708 0.205984 15.3203 -0.0108185 14.9326 -0.0108185C14.5449 -0.0108185 14.1571 0.195144 13.9799 0.617908L12.2961 4.63959C12.2628 4.73715 12.1964 4.81303 12.1299 4.87807V15.0244L14.5227 13.5718C14.7443 13.4309 15.0323 13.4309 15.2538 13.5718L19.0314 15.8483C19.2086 15.9567 19.3859 16.0109 19.5631 16.0109C20.1503 16.0109 20.682 15.4472 20.5269 14.7751L19.4856 10.6016C19.4191 10.3415 19.5188 10.0813 19.7182 9.90788L23.1745 7.14365C23.8724 6.53661 23.4736 5.42008 22.5763 5.36588Z" fill="#FFC831" /></g><defs><clipPath id="clip0_768_2255"><rect width="23.5294" height="16" fill="white" /></clipPath></defs></svg>
						5,0
					</div>
					
					<?if($arRegions):?>
							<div class="inline-block pull-left">
								<div class="top-description no-title">
									<?\Aspro\Functions\CAsproMax::showRegionList();?>
								</div>
							</div>
						<?endif;?>

						<div class="time-header">
							<div class="time-header__value">
								<strong>Пн–Пт:</strong> 9:00–19:00 
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16 10L12 14L8 10" stroke="#707073" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
							</div>
							<div class="time-header__dropdown">
								<div><strong>Сб:</strong> c 9:00 до 18:00</div>
								<div><strong>Вс:</strong> с 9:00 до 17:00</div>
							</div>
						</div>

					<div class="line-header__right">
						
						<div class="wrap_icon inner-table-block">
							<div class="header-right-contacts">
								<div class="header-phone">
									
									<div><a rel="nofollow" class="no-decript" href="tel:+79780765559">+7 (978) 076-55-59</a></div>
								</div>
								<?if($bPhone):?>
									<?//CMax::ShowHeaderPhones('no-icons', false, true);?>
								<?endif?>

								<?$callbackExploded = explode(',', $arTheme['SHOW_CALLBACK']['VALUE']);
								if( in_array('HEADER', $callbackExploded) ):?>

								<div>
									<div class="inline-block__">
										<span class="callback-block callback-block--btn animate-load font_upper_xs colored" data-event="jqm" data-param-form_id="CALLBACK" data-name="callback">
											<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M17.4912 13.2102L14.9792 10.6982C14.0821 9.80111 12.557 10.16 12.1981 11.3262C11.929 12.1337 11.0319 12.5822 10.2245 12.4028C8.43021 11.9542 6.00797 9.62168 5.55941 7.73772C5.29027 6.93027 5.82855 6.03314 6.63596 5.76404C7.80222 5.40519 8.16108 3.88007 7.26395 2.98295L4.752 0.470991C4.03429 -0.156997 2.95774 -0.156997 2.32975 0.470991L0.625214 2.17553C-1.07933 3.96978 0.804639 8.72455 5.02113 12.941C9.23763 17.1575 13.9924 19.1313 15.7866 17.337L17.4912 15.6324C18.1192 14.9147 18.1192 13.8382 17.4912 13.2102Z" fill="#2B5840" /></svg>
											<?=GetMessage("CALLBACK")?>
										</span>
									</div>

									<div class="inline-block__">
									
										
									</div>
								</div>


								<?endif;?>
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
					<div class="menu-only menu-wrapper">
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
						<span class="callback-block callback-block--btn animate-load font_upper_xs colored" data-event="jqm" data-param-form_id="13" data-name="ask"><?=GetMessage("CALLBACK_DESIGN")?></span>

						<div class="type-wrap type-wrap--search">
									<?$APPLICATION->IncludeComponent("arturgolubev:search.title", ''/*(isset($arTheme["TYPE_SEARCH"]["VALUE"]) ? $arTheme["TYPE_SEARCH"]["VALUE"] : $arTheme["TYPE_SEARCH"])*/, array(
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
		<hr class="right_border">
	</div>
	<div class="line-row visible-xs"></div>
</div>
<div id="header-fixed-margin"></div>