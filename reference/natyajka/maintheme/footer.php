<footer class="footer">
			<div class="container">
				<div class="row">
					<div class="col-lg">
					<?php if(is_front_page()) { ?>
						<span class="logo">
							<img src="<?php bloginfo('template_directory'); ?>/img/logo2.svg" alt="alt">
						</span>
					<?php } else { ?>
						<a href="/" class="logo">
							<img src="<?php bloginfo('template_directory'); ?>/img/logo2.svg" alt="alt">
						</a>
					<?php } ?>
					</div>
					<div class="col-lg">
						<?php 
							wp_nav_menu( [
								'menu'            => 'Футер меню',
								'menu_class'      => 'footer__nav footer__nav_big',
								'container'       => false, 
							] );
						?>												
					</div>
					<?php
						$paged = get_query_var('paged') ?: 1;
						$post_serv = array(
							'post_type' => 'service',
							'post_status' => 'publish',       
							'paged' => $paged,
							'posts_per_page' => -1,
							'orderby' => array(
										'sort' => 'ASC',
										'date' => 'DESC',
									),
							);
							$itemServ = new WP_Query($post_serv);
							if ( $itemServ->have_posts() ) { ?> 
					<div class="col-lg">
						<div class="footer__title">услуги <i class="fal fa-chevron-down"></i></div>
						<div class="footer__content">
							<ul class="footer__nav">
							<?php while ( $itemServ->have_posts() ) {                  
                				$itemServ->the_post(); ?>
								<li><a href="<?php echo get_the_permalink();?>"><?php the_title(); ?></a></li>
							<?php } ?>								
							</ul>
						</div>
					</div>	
					<?php }	wp_reset_postdata(); // сброс ?>				
					<div class="col-xl col-lg-3">
						<div class="footer__contacts">
							<div class="line-contacts">
								<a href="tel:+<?php echo preg_replace("/[^0-9]/", '', get_field('телефон', 'option')); ?>" class="phone-main"><?php echo get_field('телефон', 'option'); ?></a>
								<?php if(get_field('whatsapp', 'option') || get_field('telegram', 'option') || get_field('max', 'option')) { ?>
									<ul class="social-list">
										<?php if(get_field('whatsapp', 'option')){ ?>
											<li><a href="whatsapp://send/?phone=+<?php echo preg_replace("/[^0-9]/", '', get_field('whatsapp', 'option')); ?>" target="_blank"><i class="fab fa-whatsapp"></i></a></li>
										<?php } if(get_field('telegram', 'option')) { ?>
											<li><a href="<?php echo get_field('telegram', 'option'); ?>" target="_blank"><i class="fab fa-telegram-plane"></i></a></li>
										<?php } if(get_field('max', 'option')) { ?>
											<li><a href="<?php echo get_field('max', 'option'); ?>" target="_blank"><img class="icon-soc" src="<?php bloginfo('template_directory'); ?>/img/max.svg" alt="alt"></a></li>
										<?php }  ?>
									</ul>
								<?php } ?>
							</div>
							<p><strong>Адрес:</strong> <br><?php echo get_field('адрес', 'option'); ?></p>
							<br>
							<p><strong>Режим работы:</strong> <br><?php echo get_field('режим_работы', 'option'); ?></p>
							<br>
							<p><strong>E-mail:</strong> <br><a href="mailto:<?php echo get_field('почта', 'option'); ?>"><?php echo get_field('почта', 'option'); ?></a></p>
						</div>
					</div>
				</div>
			</div>
			<div class="footer-bottom">
				<div class="container">
					<div class="row align-items-center">
						<div class="col-lg">
							<a href="/privacy-policy/">Политика конфиденциальности </a><br>
							<a href="/dogovor-oferty/">Договор оферты </a><br>
							<a href="/soglasie-na-obrabotku-personalnyh-dannyh/">Согласие на обработку персональных данных</a>
						</div>
						<div class="col-lg">
							<p>г. Симферополь © Copyright  <?php echo date('Y'); ?> г.</p>
						</div>
						<div class="col-lg">
							<div class="create">Сделано в <a href="https://sheer82.ru/" target="_blank"><img src="<?php bloginfo('template_directory'); ?>/img/copy.svg" alt="alt"></a></div>
						</div>
						<div class="col-xl-4 col-lg">
							<div class="btn_top"><i class="fal fa-long-arrow-up"></i></div>
						</div>
					</div>
				</div>
			</div>
		</footer>
		
		<div class="cookies-modal">
    		<span>Мы используем cookies для быстрой и удобной работы сайта. Продолжая пользоваться сайтом, вы принимаете <a class="cookie-policy-link" href="/privacy-policy/">условия обработки персональных данных</a></span>
    		<div class="cookies-btns">
    			<div class="cookies-btn yes-cookie">Согласен</div>
    			<div class="cookies-btn no-cookie">Не согласен</div>
    		</div>
    		<button type="button" class="cookie-policy-button">✕</button>
    	</div>

		<div class="modal-wr" id="callback">
			<div class="block-form">
				<div class="title-small">Заказать звонок</div>	
			<?php echo do_shortcode('[contact-form-7 id="6558802" title="Заказать звонок"]'); ?>
		    </div>
	    </div>

		<div class="modal-wr" id="order-1click">
			<div class="block-form">
				<div class="title-small">Купить в 1 клик</div>	
			<?php echo do_shortcode('[contact-form-7 id="4f9a89c" title="Купить в 1 клик"]'); ?>
		    </div>
	    </div>
		
	</div>

	<!--[if lt IE 9]>
	<script src="libs/html5shiv/es5-shim.min.js"></script>
	<script src="libs/html5shiv/html5shiv.min.js"></script>
	<script src="libs/html5shiv/html5shiv-printshiv.min.js"></script>
	<script src="libs/respond/respond.min.js"></script>
<![endif]-->
<script src="<?php bloginfo('template_directory'); ?>/libs/jquery/jquery-3.6.0.min.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/libs/jquery-mousewheel/jquery.mousewheel.min.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/libs/fancybox/jquery.fancybox.pack.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/libs/slick-1.5.9/slick/slick.min.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/libs/scroll2id/PageScroll2id.min.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/libs/mask/jquery.maskedinput.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/libs/jQueryFormStyler-master/jquery.formstyler.min.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/common.js?ver=1.126"></script>
<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU"></script>
<script>
	$(document).ready(function() {
		ymaps.ready(init);

		function init() {
			var myMap = new ymaps.Map('map', {
				center: [43.685131, 39.675920],
				zoom: 10
			}, {
				searchControlProvider: 'yandex#search'
			});		

			// Первая метка
			myPlacemark1 = new ymaps.Placemark([43.6837329,39.6783302], {}, {
				iconLayout: 'default#image',
				iconImageHref: '/wp-content/themes/maintheme/img/pin.svg',
				hideIconOnBalloonOpen: false,
				iconImageSize: [30, 40],
				iconImageOffset: [-15, -40],
				balloonOffset: [0, -40]
			});

			// Вторая метка
			myPlacemark2 = new ymaps.Placemark([43.566977, 39.7631], {}, {
				iconLayout: 'default#image',
				iconImageHref: '/wp-content/themes/maintheme/img/pin.svg',
				hideIconOnBalloonOpen: false,
				iconImageSize: [30, 40],
				iconImageOffset: [-15, -40],
				balloonOffset: [0, -40]
			});
			
			myMap.controls.remove('searchControl');
			myMap.behaviors.disable('scrollZoom');
			myMap.geoObjects.add(myPlacemark1);
			myMap.geoObjects.add(myPlacemark2);
		}
	});
</script>
<!-- Yandex.Metrika counter --><!-- /Yandex.Metrika counter -->
<!-- Google Analytics counter --><!-- /Google Analytics counter -->

<?php $paged = get_query_var('paged') ?: 1; 
	 $posts = array(                 
		'orderby'     => 'date',
		'order'       => 'DESC',
		'post_type'   => 'portfolio',
		'posts_per_page' => 6,
		'paged' => $paged,
	//'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
	);
	$wp_query = new WP_Query($posts);
	if ( $wp_query->have_posts() ) {  ?>
<script>
	$(document).ready(function () {
		ymaps.ready(init);

		function init() {
			var myMap = new ymaps.Map('map_portfolio', {
				center: [43.585472,39.723098],
				zoom: 9
			}, {
				searchControlProvider: 'yandex#search'
			}),

			MyIconContentLayout = ymaps.templateLayoutFactory.createClass(
				'<div style="color: #fff; font-size: 16px; font-weight: 500; width: 40px; height: 50px; text-aling: center; display: flex; aling-items: center; 	justify-content: center; padding-top: 7px">$[properties.iconContent]</div>'
				),
			
			<?php $k_addr=0;
			 while ( $wp_query->have_posts() ) {                  
                	$wp_query->the_post(); ?>			
			myPlacemark<?php echo $k_addr+1; ?> = new ymaps.Placemark([<?php if(get_field('координаты_на_карте')) { echo get_field('координаты_на_карте'); } else { echo '43.585472,39.723098'; } ?>],{
				balloonContent: '<div class="baloon-content"><a href="<?php echo get_the_permalink();?>"><div class="row"><div class="col-map-img"><?php if(has_post_thumbnail()) { ?><img src="<?php the_post_thumbnail_url('thumbnail'); ?>" alt="alt"><?php } elseif(get_field('галерея_изображений')) { ?><img src="<?php echo get_field('галерея_изображений')['0']['sizes']['thumbnail']; ?>" alt="alt"><?php } ?></div><div class="col-map-txt"><?php the_title(); ?></div></div></a></div>',
			},  {
				iconLayout: 'default#imageWithContent',
				iconImageHref: '/wp-content/themes/maintheme/img/pin.svg',
				hideIconOnBalloonOpen: false,
				iconImageSize: [36, 46],
				iconImageOffset: [-18, -46],
				balloonOffset: [ 0, -46 ]
			});
			<?php $k_addr++; } ?>

			myMap.controls.remove('searchControl');
			myMap.behaviors.disable('scrollZoom');
			<?php $k_addr=0;
			 while ( $wp_query->have_posts() ) {                  
                	$wp_query->the_post(); ?>
				myMap.geoObjects.add(myPlacemark<?php echo $k_addr+1; ?>);
			<?php $k_addr++; } ?>

			$( ".item-location" ).click(function() {
				$(".item-location").removeClass("active");
				$(this).addClass("active");
			});
			<?php $k_addr=0;
			 while ( $wp_query->have_posts() ) {                  
                	$wp_query->the_post();?>
			$( ".location_<?php echo $k_addr+1; ?>" ).click(function() {
				myPlacemark<?php echo $k_addr+1; ?>.balloon.open();
				myMap.setCenter([<?php if(get_field('координаты_на_карте')) { echo get_field('координаты_на_карте'); } else { echo '43.585472,39.723098'; } ?>]);
			});
			<?php $k_addr++; } ?>
			
		}
	});
</script>
<?php } wp_reset_postdata(); // сброс ?>


<?php wp_footer(); ?>

</body>
</html>
