<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?
global $arTheme;
?>

<div class="footer-v5">
	<div class="footer-inner shorten">
		<div class="footer_top">
			<div class="maxwidth-theme">
				<div class="row">
					<div class="col-md-4">
						<div class="info">
							<?if(\Bitrix\Main\Loader::includeModule('subscribe') && $arTheme['HIDE_SUBSCRIBE']['VALUE'] != 'Y'):?>
								<div class="subscribe_button">
									<span class="btn" data-event="jqm" data-param-id="subscribe" data-param-type="subscribe" data-name="subscribe"><?=GetMessage('SUBSCRIBE_TITLE')?>
									<?=CMax::showIconSvg('subscribe', SITE_TEMPLATE_PATH.'/images/svg/subscribe_small_footer.svg')?></span>
								</div>
							<?endif;?>
							<div>
								<div class="confidentiality">
									<?=CMax::showIconSvg('privacy_policy', SITE_TEMPLATE_PATH.'/images/svg/privacy_policy.svg')?>
									<?$APPLICATION->IncludeFile(SITE_DIR."include/footer/confidentiality.php", Array(), Array(
											"MODE" => "php",
											"NAME" => "onfidentiality",
											"TEMPLATE" => "include_area.php",
										)
									);?>
								</div>
							</div>
							<div class="inf_dan">
								<div class="dan_item">
									<div class="dan_name">ОГРН: </div><div class="dan_text">1149102108616</div>
								</div>
								<div class="dan_item">
									<div class="dan_name">ИНН: </div><div class="dan_text">9102052660</div>
								</div>
								<div class="dan_item">
									<div class="dan_name">КПП: </div><div class="dan_text">910201001</div>
								</div>
							</div>
						</div>
						<div class="footer-bottom-line">
								
						<div class="footer-rating">
							<img src="<?php echo SITE_TEMPLATE_PATH ?>/images/yandex_rating_widget.png" alt="alt">
						</div>

						<div class="footer-bottom__item pays">
											<?$APPLICATION->IncludeFile(SITE_DIR."include/footer/copy/pay_system_icons.php", Array(), Array(
													"MODE" => "php",
													"NAME" => "onfidentiality",
													"TEMPLATE" => "include_area.php",
												)
											);?>
										</div>
						</div>

					</div>
					<div class="wrapper col-md-5 bottom_menu_block">
						<div class="first_bottom_menu">

						<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
								array(
									"COMPONENT_TEMPLATE" => ".default",
									"PATH" => SITE_DIR."include/footer/menu/menu_bottom4.php",
									"AREA_FILE_SHOW" => "file",
									"AREA_FILE_SUFFIX" => "",
									"AREA_FILE_RECURSIVE" => "Y",
									"EDIT_TEMPLATE" => "include_area.php"
								),
								false, array("HIDE_ICONS" => "Y")
							);?>

	
						</div>
						<div class="two_bottom_menu">
							<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
								array(
									"COMPONENT_TEMPLATE" => ".default",
									"PATH" => SITE_DIR."include/footer/menu/menu_bottom5.php",
									"AREA_FILE_SHOW" => "file",
									"AREA_FILE_SUFFIX" => "",
									"AREA_FILE_RECURSIVE" => "Y",
									"EDIT_TEMPLATE" => "include_area.php"
								),
								false, array("HIDE_ICONS" => "Y")
							);?>
						</div>

					</div>
					<div class="col-md-3 contact-block">
						<div class="info">
							<div class="row">

							

								<div class="col-md-12 col-sm-12">
									<div class="phone blocks">
										<div class="inline-block">
											<div class="phone with_dropdown white sm">
												<div class="wrap">
													
												<div>
													<i class="svg inline  svg-inline-phone" aria-hidden="true">
													<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 16 16"><path fill="currentColor" d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.6 17.6 0 0 0 4.168 6.608a17.6 17.6 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.68.68 0 0 0-.58-.122l-2.19.547a1.75 1.75 0 0 1-1.657-.459L5.482 8.062a1.75 1.75 0 0 1-.46-1.657l.548-2.19a.68.68 0 0 0-.122-.58zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42a18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877z"/></svg>

													</i>

													<a rel="nofollow" href="tel:+79780803308">+7 (978) 080-33-08</a>
												</div>
											</div>
											<div class="dropdown "><div class="wrap scrollblock"><div class="more_phone"><a rel="nofollow" class="no-decript" href="tel:+79780803308">+7 (978) 080-33-08</a></div><div class="more_phone"><a rel="nofollow" class="no-decript" href="tel:+79780765559">+7 (978) 076-55-59</a></div></div></div><i class="svg inline  svg-inline-down" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="5" height="3" viewBox="0 0 5 3"><path class="cls-1" d="M250,80h5l-2.5,3Z" transform="translate(-250 -80)"></path></svg></i>							</div>
											<?//CMax::ShowHeaderPhones('white sm', true);?>
										</div>
										<?$callbackExploded = explode(',', $arTheme['SHOW_CALLBACK']['VALUE']);
										if( in_array('FOOTER', $callbackExploded) ):?>
											<div class="inline-block callback_wrap">
												<span class="callback-block animate-load colored" data-event="jqm" data-param-form_id="CALLBACK" data-name="callback"><?=GetMessage("CALLBACK")?></span>
											</div>
										<?endif;?>
									</div>
								</div>
								<div class="col-md-12 col-sm-12">
									<div class="social-block">
										<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
											array(
												"COMPONENT_TEMPLATE" => ".default",
												"PATH" => SITE_DIR."include/footer/social.info.php",
												"AREA_FILE_SHOW" => "file",
												"AREA_FILE_SUFFIX" => "",
												"AREA_FILE_RECURSIVE" => "Y",
												"EDIT_TEMPLATE" => "include_area.php"
											),
											false, array("HIDE_ICONS" => "Y")
										);?>
									</div>	
								</div>	

								<div class="col-md-12 col-sm-12">
									<div class="footer-address">
										<?$APPLICATION->AddHeadString('<style>.footer-v5 .cls-marker,.footer-v5  .cls-marker3 {fill: #fff;}.footer-v5 .cls-marker,.footer-v5  .cls-marker2 {fill-rule: evenodd;}.footer-v5 .cls-marker {opacity: 0.5;}.footer-v5 .cls-marker2 {fill: #247ee3;}</style>',true)?>
											<svg width="34" height="24" viewBox="0 0 46 57"><defs></defs><path data-name="Ellipse 275 copy" class="cls-marker" d="M142.976,200.433L143,200.469s-7.05,5.826-10,10.375c-2.263,3.489-2.974,6.153-5,6.156s-2.737-2.667-5-6.156c-2.95-4.549-10-10.375-10-10.375l0.024-.036A23,23,0,1,1,142.976,200.433Z" transform="translate(-105 -160)"></path><path data-name="Ellipse 253 copy 4" class="" d="M140,198.971L140,199s-6.362,5.91-8.092,8.456C128.351,212.69,128,215,128,215s-0.307-2.084-3.826-7.448C121.8,203.935,116,199,116,199l0-.029A20,20,0,1,1,140,198.971Z" transform="translate(-105 -160)"></path><circle data-name="Ellipse 254 copy 5" class="cls-marker" cx="23" cy="23" r="12"></circle></svg>

											<?=CMax::showAddress('address blocks')?>
											
										<?php
										/*
										$host = $_SERVER['HTTP_HOST'];

										if (strpos($host, 'yalta.atriumceramic.ru') !== false): ?>
											<div class="address-text">
												Офис: Республика Крым, г. Ялта, ул. Васильева, зд. 16, офис 202
											</div>
										<?php else: ?>
											<div class="address-text">
												295050, Республика Крым, г. Симферополь, ул. Кечкеметская, 27, этаж 1
											</div>
										<?php endif; 
										*/?>
									</div>
									
								</div>

								<div class="col-md-12 col-sm-12">
									<?=CMax::showEmail('email blocks')?>
								</div>
								<div class="col-md-12 col-sm-12">
									<div class="timework-footer">
										<svg xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512">
											<path fill="#949494" d="M464 256A208 208 0 1 1 48 256a208 208 0 1 1 416 0zM0 256a256 256 0 1 0 512 0A256 256 0 1 0 0 256zM232 120V256c0 8 4 15.5 10.7 20l96 64c11 7.4 25.9 4.4 33.3-6.7s4.4-25.9-6.7-33.3L280 243.2V120c0-13.3-10.7-24-24-24s-24 10.7-24 24z"/>
										</svg>
										<div class="timework-footer-text">
											<span>Пн – Пт: с 9:00 до 19:00</span>
											<span>Сб: c 9:00 до 18:00</span>
											<span>Вс: с 9:00 до 17:00</span>
										</div>
									</div>	
								</div>							
									
															
							</div>
						</div>
					</div>
					
				</div>
			</div>
		</div>
	<div class="footer__bottom">
			<div class="maxwidth-theme">
				<div class="footer__line">
					<div class="footer__links">
						<a href="#">Политика конфиденциальности</a>
						<a href="#">Согласие на обработку персональных данных</a>
					</div>
					<div class="copyright">© Copyright 2026 г.</div>
					<div class="create">Сделано в <a href="#" target="_blank"><img src="<?php echo SITE_TEMPLATE_PATH ?>/images/sheer.svg" alt="alt"></a></div>
				</div>
			</div>
		</div>		
	</div>

</div>