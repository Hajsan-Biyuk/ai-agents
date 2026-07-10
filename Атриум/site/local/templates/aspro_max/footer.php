					<!--start footer-->	<?CMax::checkRestartBuffer();?>
						<?IncludeTemplateLangFile(__FILE__);?>
							<?if(!$isIndex):?>
								<?if($isHideLeftBlock && !$isWidePage):?>
									</div> <?// .maxwidth-theme?>
								<?endif;?>
								</div> <?// .container?>
							<?else:?>
							
							<?endif;?>
							
						</div> <?// .middle?>
					<?//if(($isIndex && $isShowIndexLeftBlock) || (!$isIndex && !$isHideLeftBlock) && !$isBlog):?>
					<?if(($isIndex && ($isShowIndexLeftBlock || $bActiveTheme)) || (!$isIndex && !$isHideLeftBlock)):?>
						</div> <?// .right_block?>
						<?if($APPLICATION->GetProperty("HIDE_LEFT_BLOCK") != "Y" && !defined("ERROR_404")):?>
							<?CMax::ShowPageType('left_block');?>
						<?endif;?>
					<?endif;?>
					</div> <?// .container_inner?>
				<?if($isIndex):?>
					</div>
				<?elseif(!$isWidePage):?>
				<?php 
				    if($_SERVER['REQUEST_URI'] == '/dostavka-i-oplata/')
                        {
                            echo '<div class="pay-method">
            <div class="pay-method-item">
                <div class="pay-method-item__icon">
                    <img src="/images/pay-method-1.png" alt="Наличный расчет">
                </div>
                <div class="pay-method-item__info">
                    <div class="pay-method-item__info__title">Наличный расчет</div>
                    <div class="pay-method-item__info__text">Оплата в офисе компании или курьеру при получении товара</div>
                </div>
            </div>
            <div class="pay-method-item">
                <div class="pay-method-item__icon">
                    <img src="/images/pay-method-2.png" alt="Безналичный расчёт">
                </div>
                <div class="pay-method-item__info">
                    <div class="pay-method-item__info__title">Безналичный расчёт <br> (с НДС или без НДС)</div>
                    <div class="pay-method-item__info__text">Оплата товара по безналичному расчёту. После подтверждения заказа Вам высылается счёт по электронной почте</div>
                </div>
            </div>
            <div class="pay-method-item">
                <div class="pay-method-item__icon">
                    <img src="/images/pay-method-3.png" alt="Оплата кредитной картой">
                </div>
                <div class="pay-method-item__info">
                    <div class="pay-method-item__info__title">Оплата кредитной <br> или дебетовой картой </div>
                    <div class="pay-method-item__info__text">Оплата в офисе компании</div>
                </div>
            </div>
            <div class="pay-method-item">
                <div class="pay-method-item__icon">
                    <img src="/images/pay-method-4.png" alt="Оплата на банковскую карту">
                </div>
                <div class="pay-method-item__info">
                    <div class="pay-method-item__info__title">Оплата на <br> банковскую карту</div>
                    <div class="pay-method-item__info__text">Оплата товара осуществляется переводом на карточку банка по указанным реквизитам</div>
                </div>
            </div>

        </div>';
                    }
				
				?>
					</div> <?// .wrapper_inner?>
				<?endif;?>
			</div> <?// #content?>
			<?CMax::get_banners_position('FOOTER');?>
		</div><?// .wrapper?>

		<footer id="footer">
			<?include_once(str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DIR.'include/footer_include/under_footer.php'));?>
			<?include_once(str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DIR.'include/footer_include/top_footer.php'));?>
		</footer>
		<div class="widget-container">
			<a href="https://t.me/+79780803308" target="_blank" rel="nofollow" title="TG"><img src="/images/telegram-logo-2.png" alt="TG"><span class="hidden">TG</span></a>
			<a href="https://max.ru/u/f9LHodD0cOLg1tfV845Ibyigoat1ltA7tNTSXwqiyzU2xJKspm43pTsqutk" target="_blank" rel="nofollow" title="Max" style="background-color: #532778c9;display: flex;border-radius: 5px;"><img src="/images/ic_wp.svg" alt="max" style="width: 30px;"><span class="hidden">Max</span></a>
			<a href="https://wa.me/79780803308" target="_blank" rel="nofollow" title="WhatsApp"><img src="/images/whatsapp-logo-2.png" alt="WhatsApp"><span class="hidden">WhatsApp</span></a>
		</div>
		<?include_once(str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DIR.'include/footer_include/bottom_footer.php'));?>
		<script src="/bitrix/templates/aspro_max/js/app.js"></script>
		<script>
			function loadScript(src) { 
				var s = document.createElement('script'); 
				s.src = src; 
				document.body.appendChild(s) 
			} 
			var fired = false;        
			window.addEventListener('scroll', () => {
				if (fired === false) {
					fired = true;
					
					setTimeout(() => {
						loadScript('//code.jivo.ru/widget/WQl4BpH5Z6');
						loadScript('/bitrix/templates/aspro_max/js/loaded_timeout.js');                                        
					}, 1000)
				}
			});         
		</script>	
			

		<?if(strpos($APPLICATION->GetCurPage(),'/catalog/') === 0){?>
			<div class="popup_iframe_wrapper popup_iframe_wrappe_share_product" id="share_product_modal" style="display:none">
				<div class="overlay_chareproduct"></div>
				<div class="chare_product_wrapper">
					<div>
						<a href="#" class="close jqmClose"><i class="svg inline  svg-inline-" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"><path data-name="Rounded Rectangle 114 copy 3" class="cccls-1" d="M334.411,138l6.3,6.3a1,1,0,0,1,0,1.414,0.992,0.992,0,0,1-1.408,0l-6.3-6.306-6.3,6.306a1,1,0,0,1-1.409-1.414l6.3-6.3-6.293-6.3a1,1,0,0,1,1.409-1.414l6.3,6.3,6.3-6.3A1,1,0,0,1,340.7,131.7Z" transform="translate(-325 -130)"></path></svg></i></a>
						<div class="basket-share-new-title">
							<h2>Поделиться товаром</h2>
						</div>
						<div class="form_body">
							<div class="form-control basket-share-new-url">
								<label for="PRODUCT_SHARE_URL_5vHZx"><span>Вы можете поделиться товаром с помощью ссылки:</span></label>
								<div class="basket-share-new-copy-url colored_theme_hover_bg-block" data-clipboard-text="https://atriumceramic.ru<?=$APPLICATION->GetCurPage();?>">
									<input type="text" id="PRODUCT_SHARE_URL_5vHZx" name="PRODUCT_SHARE_URL" value="https://atriumceramic.ru<?=$APPLICATION->GetCurPage();?>" readonly="" autocomplete="off">
									<div class="product-share-new-url-icon"><i class="svg inline  svg-inline-copy_url colored_theme_hover_bg-el-svg" aria-hidden="true">
										<svg class="svg" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path fill-rule="evenodd" clip-rule="evenodd" d="M6 2V4V6V10H10H12H14V2H6ZM4 1V4V6V11C4 11.5523 4.44771 12 5 12H10H12H15C15.5523 12 16 11.5523 16 11V1C16 0.447715 15.5523 0 15 0H5C4.44772 0 4 0.447715 4 1ZM12 13H10V14H2V6H3V4H1C0.447715 4 0 4.44771 0 5V15C0 15.5523 0.447715 16 1 16H11C11.5523 16 12 15.5523 12 15V13Z" fill="#B8B8B8"></path></svg>
										</i>
									</div>
								</div>
								<div class="text-block">Нажмите на ссылку, чтобы скопировать</div>
							</div>
							<div class="social_block basket-share-new-socials">
								<div class="soc-avt">
									<div class="title">Поделиться в соц.сетях</div><div class="row clearfix">
										<script type="text/javascript" src="//yastatic.net/share2/share.js" async="async" data-charset="utf-8"></script>
										<div class="ya-share2 yashare-auto-init hover-block__item-wrapper" data-services="vkontakte,odnoklassniki,twitter"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?}?>
		<div class="cookies-modal" style="display: none;">
			<span>Мы используем cookies для быстрой и удобной работы сайта. Продолжая пользоваться сайтом, вы принимаете <a class="cookie-policy-link" href="/politika-konfidencialnosti/">условия обработки персональных данных</a></span>
			<div class="cookies-btns">
				<div class="cookies-btn yes-cookie">Согласен</div>
				<div class="cookies-btn no-cookie">Не согласен</div>
			</div>
			<button type="button" class="cookie-policy-button">✕</button>
		</div>
	</body>
</html>