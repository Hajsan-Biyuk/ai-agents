<!--start footer--> <?CMax::checkRestartBuffer();?> <?IncludeTemplateLangFile(__FILE__);?> <?if(!$isIndex):?> <?if($isHideLeftBlock && !$isWidePage):?> <?// .maxwidth-theme?> <?endif;?> <?// .container?> <?else:?> <?CMax::ShowPageType('indexblocks');?> <?endif;?> <?CMax::get_banners_position('CONTENT_BOTTOM');?> <?// .middle?> <?//if(($isIndex && $isShowIndexLeftBlock) || (!$isIndex && !$isHideLeftBlock) && !$isBlog):?> <?if(($isIndex && ($isShowIndexLeftBlock || $bActiveTheme)) || (!$isIndex && !$isHideLeftBlock)):?> <?// .right_block?> <?if($APPLICATION->GetProperty("HIDE_LEFT_BLOCK") != "Y" && !defined("ERROR_404")):?> <?CMax::ShowPageType('left_block');?> <?endif;?> <?endif;?> <?// .container_inner?> <?if($isIndex):?> <?elseif(!$isWidePage):?> <?php 
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
				
				?> <?// .wrapper_inner?> <?endif;?> <?// #content?> <?CMax::get_banners_position('FOOTER');?> <?// .wrapper?> <footer id="footer">
<?include_once(str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DIR.'include/footer_include/under_footer.php'));?> <?include_once(str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DIR.'include/footer_include/top_footer.php'));?> </footer>
<div class="widget-container">
 <a href="https://wa.me/79780803308" target="_blank" rel="noopener nofollow" title="WhatsApp"><img src="/images/whatsapp-logo-2.png" alt=""><span class="hidden">WhatsApp</span></a>
	<?//<a href="viber://chat?number=+79780803308" target="_blank" rel="noopener nofollow" title="Viber"><img src="/images/viber-logo-2.png" alt=""><span class="hidden">viber</span></a>?> <a href="https://t.me/+79780803308" target="_blank" rel="noopener nofollow" title="TG"><img src="/images/telegram-logo-2.png" alt=""><span class="hidden">TG</span></a>
</div>
 <?include_once(str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DIR.'include/footer_include/bottom_footer.php'));?> <script src="/bitrix/templates/aspro_max_opt_site/js/app.js"></script> <script>
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
						loadScript('/bitrix/templates/aspro_max_opt_site/js/loaded_timeout.js');                                        
					}, 1000)
				}
			});         
		</script> <?if(strpos($APPLICATION->GetCurPage(),'/catalog/') === 0){?>
<div class="popup_iframe_wrapper popup_iframe_wrappe_share_product" id="share_product_modal" style="display:none">
	<div class="overlay_chareproduct">
	</div>
	<div class="chare_product_wrapper">
		<div>
 <a href="#" class="close jqmClose"><i class="svg inline svg-inline-" aria-hidden="true"></i></a>
			<div class="basket-share-new-title">
				<h2>Поделиться товаром</h2>
			</div>
			<div class="form_body">
				<div class="form-control basket-share-new-url">
 <label for="PRODUCT_SHARE_URL_5vHZx">Вы можете поделиться товаром с помощью ссылки:</label>
					<div class="basket-share-new-copy-url colored_theme_hover_bg-block" data-clipboard-text="https://atriumceramic.ru&lt;span id=" title="Код PHP: &lt;?=$APPLICATION-&gt;GetCurPage();?&gt;">
						<span class="bxhtmled-surrogate-inner"><span class="bxhtmled-right-side-item-icon"></span><span class="bxhtmled-comp-lable" unselectable="on" spellcheck="false">Код PHP</span></span>"&gt; <input type="text" id="PRODUCT_SHARE_URL_5vHZx" name="PRODUCT_SHARE_URL" value="https://atriumceramic.ru<?=$APPLICATION->GetCurPage();?>" readonly="" autocomplete="off">
						<div class="product-share-new-url-icon">
							<i class="svg inline svg-inline-copy_url colored_theme_hover_bg-el-svg" aria-hidden="true"> </i>
						</div>
					</div>
					<div class="text-block">
						Нажмите на ссылку, чтобы скопировать
					</div>
				</div>
				<div class="social_block basket-share-new-socials">
					<div class="soc-avt">
						<div class="title">
							Поделиться в соц.сетях
						</div>
						<div class="row clearfix">
							 <script type="text/javascript" src="//yastatic.net/share2/share.js" async="async" data-charset="utf-8"></script>
							<div class="ya-share2 yashare-auto-init hover-block__item-wrapper" data-services="vkontakte,odnoklassniki,twitter">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		 <?}?>
	</div>
</div>