<footer>
    <div class="container">
        <div class="footer__wrapper">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-xs-12">
					<div class="footer__logo">

					<?php if (is_front_page() || is_home()) : ?>
						<span class="logo">
							<img alt="<?php bloginfo('name'); ?>"
								title="<?php bloginfo('name'); ?>"
								class="trans img-fluid"
								src="<?php echo get_template_directory_uri(); ?>/images/new-logo.png" />
						</span>
					<?php else : ?>
						<a href="<?php echo esc_url(home_url('/')); ?>" rel="home" class="logo">
							<img alt="<?php bloginfo('name'); ?>"
								title="<?php bloginfo('name'); ?>"
								class="trans img-fluid"
								src="<?php echo get_template_directory_uri(); ?>/images/new-logo.png" />
						</a>
					<?php endif; ?>
						<p>Кровельные материалы от<br /> производителя в Крыму</p>
						<p>Подпишитесь на наши социальные сети:</p>
						<div class="row">
							<div class="col-sm-4">
								<a href="https://vk.com/pnk_crimea" target="_blank" rel="noopener noreferrer nofollow"><img src="https://pnk.com.ru/wp-content/themes/pnk-adaptiv/images/icons/vk-icon.png" alt="Вконтакте ПНК" /></a>
							</div>
<!-- 							<div class="col-sm-4">
								<a href="https://t.me/pnkcrimea" target="_blank" rel="noopener noreferrer nofollow"><img src="https://pnk.com.ru/wp-content/themes/pnk-adaptiv/images/icons/tg-icon.png" alt="телеграм ПНК" /></a>
							</div> -->
						</div>
				</div>
                </div>
                <div class="col-lg-3 col-md-6  col-xs-6 footer__catalog">
                    <div class="cat-footer">Каталог</div>
                    <ul class='footer__list'>
                        <?php
                        wp_nav_menu([
                            'menu'            => 'Каталог меню',
                            'menu_class'      => '',
                            'container'       => false,
                            'items_wrap' => '%3$s',
                        ]);
                        ?>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 col-xs-6 footer__company">
                    <div class="cat-footer">Компания</div> 
                    <ul class='footer__list'>
                        <?php
                        wp_nav_menu([
                            'menu'            => 'Новое главное меню',
                            'menu_class'      => '',
                            'container'       => false,
                            'items_wrap' => '%3$s',
                        ]);
                        ?>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 col-xs-12">
                    <div class="footer__contacts">
						<p>Общество с ограниченной ответственностью "Профнастил-Крым" (ООО «ПНК»)<br />
						ИНН 9102003991<br />
							ОГРН 1149102005150</p>
						<p>
							<b>Юридический адрес</b> <br>
						295051 <br>
                        Респ. Крым, Симферополь,<br />
                            ул. Генерала Телегина, 16-А</p>
                        <a class="active" href="tel:+79787877391"><span>+7 (978) 787-73-91</span></a>
                        <a class='footer__email' href='mailto:pnt_crimea@mail.ru'>e-mail: pnt_crimea@mail.ru</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer__line"></div>
    <div class="container">
        <div class="footer__authors">
            <p><a href="/privacy-policy/">Политика конфиденциальности</a></p>
			<p><a href="/soglasie-na-obrabotku-personalnyh-dannyh/">Согласие на обработку персональных данных</a></p>
            <p>© Copyright <?php echo date('Y'); ?> г</p>
            <p class="footer__sheer">
                Сделано в
                <a target="_blank" href="https://sheer.ru"><img src="<?php echo get_template_directory_uri(); ?>/images/sheer.svg" alt="sheer" /></a>
            </p>
        </div>
    </div>
	
	<div class="cookies-modal">
		<span>Мы используем cookies для быстрой и удобной работы сайта. Продолжая пользоваться сайтом, вы принимаете <a class="cookie-policy-link" href="/privacy-policy/">условия обработки персональных данных</a></span>
		<div class="cookies-btns">
			<div class="cookies-btn yes-cookie">Согласен</div>
			<div class="cookies-btn no-cookie">Не согласен</div>
		</div>
		<button type="button" class="cookie-policy-button">✕</button>
	</div>

    <div style="display: none">
        <div id="order" class="popup">
            <div class="title">Заказать товар</div>
            <div class="text">Мы перезвоним вам в ближайшее время</div>
            <?
            echo do_shortcode('[contact-form-7 id="1742" title="Заказать"]');
            ?>
        </div>
    
    <div id="callback" class="popup">
        <div class="title">Заказать звонок</div>
        <?
        echo do_shortcode('[contact-form-7 id="1743" title="Заказать звонок"]');
        ?>
    </div>

    <div id="callback-consultation" class="popup">
        <div class="title">Получить консультацию</div>
        <?
        echo do_shortcode('[contact-form-7 id="06598ff" title="Получить консультацию"]');
        ?>
    </div>

    <div id="get-miscalculation" class="popup">
        <div class="title">
            <p>Заполните заявку </p>
            <p>и наш менеджер с вами свяжется</p>
        </div>
        <?
        echo do_shortcode('[contact-form-7 id="1909" title="Получить просчёт"]');
        ?>
    </div>

    <div id="testimonial" class="popup" title="<?php the_title(); ?>">
        <div class="title">
            <p>Оставить отзыв: <?php the_title(); ?></p>
        </div>
        <?php echo do_shortcode('[contact-form-7 id="1982" title="Оставить отзыв"]'); ?>
    </div>

    <div id="down-gibkaya" class="popup">
        <div class="title">
            <p>Заполните заявку </p>
        </div>
        <?
        echo do_shortcode('[contact-form-7 id="1937" title="Прайс битумная"]');
        ?>
    </div>
    <div id="down-vodostok" class="popup">
        <div class="title">
            <p>Заполните заявку </p>
        </div>
        <?
        echo do_shortcode('[contact-form-7 id="1938" title="Прайс водосток"]');
        ?>
    </div>

    <div id="akcii-modal" class="akcii-modal">
        <img src="<?= get_template_directory_uri() ?>/images/modal-akcii.png" alt="Акции ПНК">
    </div>

    <div id="modal-price" class="popup">
        <div class="title-form__price">Ваш прайс-лист уже готов</div>
		<div class="desc-form__price">Укажите контакты и получите его на email</div>
        <?
        echo do_shortcode('[contact-form-7 id="2178" title="Скачать прайс"]');
        ?>
    </div>


</div>
    <!-- <div class="wr">
    <div class="row top-div col-sm-3">
        <div class="hidden-sm-up phone">
            <div class="icon"></div>
                <div class="">
                    <span>г. Симферополь</span><br>
                    <a href="tel:+79787877381"><span>+7 (978) 78-77-381</span></a><br>
                </div>
        </div>
            
        <div class="hidden-sm-up phone">
            <div class="icon"></div>
                <div class="">
                   <span>г. Севастополь</span><br>
                    <a href="tel:+79787877351"><span>+7 (978) 78-77-351</span></a><br>
                </div>
        </div>
        <div class="address hidden-xs-down">
            <div class="icon"></div>
                <div class="">
                   <span>г. Симферополь,</span><br>
                   <span>ул. Генерала Телегина, 16 а</span>
                </div>
        </div>
        <div class="phone hidden-xs-down">
           <div class="icon"></div>
           <div class="">
               <div>
                   <a href="tel:+79787877391"><span>+7 (978) 78-77-391</span></a>
               </div>
           </div>
        </div>
        <div class="address hidden-xs-down">
            <div class="icon"></div>
            <div class="">
               <span>г. Севастополь,</span><br>
               <span>Камышовое шоссе, 14</span>
            </div>
        </div>
        <div class="phone hidden-xs-down">
            <div class="icon"></div>
            <div class="">
                <div>
                   <a href="tel:+79787877351"><span>+7 (978) 78-77-351</span></a>
                </div>
            </div>
        </div>
    </div>

    <div id="social-footer" class="col-sm-1">
       
        <div class="vk"><a href="https://vk.com/pnk_crimea" target="_blank" class="vk" rel="nofollow noreferrer noopener"></a></div>
        <div class="ok"><a href="https://ok.ru/group/54046579425490" target="_blank" class="ok" rel="nofollow noreferrer noopener"></a></div>

        <div class="tg"><a href="https://t.me/pnkkrym" target="_blank" class="tg" rel="nofollow noreferrer noopener"></a></div>
        
    </div>
    <div class="container-fluid col-sm-3 footer-cat">
        <!-- PHP removed: dead footers123 menu inside HTML comment (PHP 8.4 fatal) -->
    </div>

    <div class="footer-form col-xs-4">
        <div class="title"><p>Заказать бесплатную консультацию</p></div>
        <!-- PHP removed: CF7 1910 inside HTML comment -->                    
    </div>

</div> -->
    <script src="<?php echo esc_url(get_template_directory_uri()); ?>/js/jquery-1.11.3.min.js"></script>
    <script src="<?php echo esc_url(get_template_directory_uri()); ?>/js/tether.min.js"></script><!-- Tether for Bootstrap -->




    <script src="<?php echo esc_url(get_template_directory_uri()); ?>/js/bootstrap.min.js"></script>
    <!-- <script src="<?php echo esc_url(get_template_directory_uri()); ?>/js/jquery.fancybox.js"></script> -->
    <script src="<?php echo esc_url(get_template_directory_uri()); ?>/js/wow.min.js"></script>
    <script type="text/javascript" src="<?php echo esc_url(get_template_directory_uri()); ?>/js/owl.carousel.min.js"></script>
    <script type="text/javascript" src="<?php echo esc_url(get_template_directory_uri()); ?>/js/slick.js"></script>
    <script type="text/javascript" src="<?php echo esc_url(get_template_directory_uri()); ?>/js/slick.min.js"></script>
    <script src="<?php bloginfo('template_directory'); ?>/js/mask/jquery.maskedinput.js"></script>
    <script src="<?php echo esc_url(get_template_directory_uri()); ?>/js/custom.js"></script>
    <script>
        new WOW().init();
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-130581426-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-130581426-1');
    </script>

    <!-- Yandex.Metrika counter -->
    <script type="text/javascript">
        (function(d, w, c) {
            (w[c] = w[c] || []).push(function() {
                try {
                    w.yaCounter51432934 = new Ya.Metrika2({
                        id: 51432934,
                        clickmap: true,
                        trackLinks: true,
                        accurateTrackBounce: true,
                        webvisor: true
                    });
                } catch (e) {}
            });

            var n = d.getElementsByTagName("script")[0],
                s = d.createElement("script"),
                f = function() {
                    n.parentNode.insertBefore(s, n);
                };
            s.type = "text/javascript";
            s.async = true;
            s.src = "https://mc.yandex.ru/metrika/tag.js";

            if (w.opera == "[object Opera]") {
                d.addEventListener("DOMContentLoaded", f, false);
            } else {
                f();
            }
        })(document, window, "yandex_metrika_callbacks2");
    </script>
    <noscript>
        <div><img src="https://mc.yandex.ru/watch/51432934" style="position:absolute; left:-9999px;" alt="<?php wp_title(''); ?>" /></div>
    </noscript>
    <!-- /Yandex.Metrika counter -->




    <!-- <a href="https://sheer82.ru/" rel="nofollow" target="_blank" class="sheer">Сделано в <img src="<?= get_template_directory_uri() ?>/images/sheer.svg" width="50" alt="sheer" title="sheer"></a> -->


</footer>

<script src="https://use.fontawesome.com/03cea99969.js"></script>

<!-- Bootstrap core CSS -->



<a href="#" id="to-top"></a>
<div class="overlay"></div>

<div id="sidenav" class="footer-sidenav">
    <div class="search">
        <?php set_query_var('search_form_suffix', '-sidenav'); get_search_form(); ?>
        <div class="clearfix"></div>
    </div>
    <br>
    <?php wp_nav_menu(array('theme_location' => 'primary', 'container' => false, 'menu_class' => "menu nav", "menu" => 28)); ?>
</div>

<script type="text/javascript">
    $(function() {
        if (!(/iPad|iPhone|iPod/.test(navigator.userAgent))) return
        $(document.head).append(
            '<style>*{cursor:pointer;-webkit-tap-highlight-color:rgba(0,0,0,0)}</style>'
        )
        $(window).on('gesturestart touchmove', function(evt) {
            if (evt.originalEvent.scale !== 1) {
                evt.originalEvent.preventDefault()
                document.body.style.transform = 'scale(1)'
            }
        })
    })
</script>
<script src="<?php bloginfo('stylesheet_directory'); ?>/ratings/rating.js"></script>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/ratings/rating.css">

<script>
    $('.myslider').slick({
        autoplay: false,
        autoplaySpeed: 6000,
        prevArrow: '<button type="button" class=" slide-prev"></button>',
        nextArrow: '<button type="button" class=" slide-next"></button>',
    });
</script>
<?php wp_footer(); ?>
</body>

</html>