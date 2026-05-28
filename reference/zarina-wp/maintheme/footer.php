
		<footer class="footer">
			<div class="container">
				<div class="row">
					<div class="col-lg-2">
						<?php if(is_front_page()){ ?>
							<span class="logo">
								<img src="<?php echo get_template_directory_uri(); ?>/img/logo.svg" alt="alt">
							</span>
						<?php } else { ?>
							<a href="/" class="logo">
								<img src="<?php echo get_template_directory_uri(); ?>/img/logo.svg" alt="alt">
							</a>
						<?php } ?>
					</div>
					<div class="col-lg-3">
						<div class="footer__title">Номера <i class="far fa-chevron-down"></i></div>
						<div class="footer__content">
							<ul class="footer__nav">
								<li><a href="#">Стандарт двухместный</a></li>
								<li><a href="#">Стандарт двухместный с балконом</a></li>
								<li><a href="#">Стандарт трехместный</a></li>
								<li><a href="#">Стандарт трехместный с балконом</a></li>
								<li><a href="#">Стандарт четырехместный</a></li>
								<li><a href="#">Стандарт четырехместный с балконом</a></li>
								<li><a href="#">Семейный</a></li>
								<li><a href="#">Люкс</a></li>
							</ul>
						</div>
					</div>
					<div class="col-lg-2">
						<ul class="footer__nav footer__nav--big">
							<li><a href="/ob-otele/">Об отеле</a></li>
							<li><a href="/infrastruktura/">Инфраструктура</a></li>
							<li><a href="/kontakty/">Контакты</a></li>
						</ul>
					</div>
					<div class="col-lg-3">
						<div class="footer__title">Новости <i class="far fa-chevron-down"></i></div>
						<div class="footer__content">
							<ul class="footer__nav">
								<li><a href="/novosti/">Новости отеля</a></li>								
							</ul>
						</div>
					</div>
					<div class="col-lg-2">
						<div class="footer__location"><?php echo get_field('polnyj_adres', 'option'); ?></div>
						<a href="tel:<?php echo preg_replace("/[^0-9+]/", '', get_field('telefon', 'option')); ?>" class="phone-main"><?php echo get_field('telefon', 'option'); ?></a>
						<?php if(!empty(get_field('max', 'option') || get_field('telegram', 'option'))){ ?>
							<ul class="socials-list">
								<?php if(!empty(get_field('telegram', 'option'))){ ?>
									<li><a href="<?php echo get_field('telegram', 'option'); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/img/tg.svg" alt="alt"></a></li>
								<?php } if(!empty(get_field('max', 'option'))){ ?>
									<li><a href="<?php echo get_field('max', 'option'); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/img/max.svg" alt="alt"></a></li>
								<?php } ?>
							</ul>
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="footer__bottom">
				<div class="container">
					<div class="footer__wrapper">
						<div class="footer__links">
							<a href="/privacy-policy/">Политика конфиденциальности</a>
							<a href="/soglasie-na-obrabotku-personalnyh-dannyh/">Согласие на обработку персональных данных</a>
						</div>
						<div class="copyright">© Copyright <?php echo date('Y'); ?> г.</div>
						<div class="create">Сделано в <a href="https://sheer.ru/" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/img/sheer.svg" alt="alt"></a></div>
					</div>
				</div>
			</div>
		</footer>
		
	</div>

	<div class="cookies-modal">
		<span>Мы используем cookies для быстрой и удобной работы сайта. Продолжая пользоваться сайтом, вы принимаете <a class="cookie-policy-link" href="/privacy-policy/">условия обработки персональных данных</a></span>
		<div class="cookies-btns">
			<div class="cookies-btn yes-cookie">Согласен</div>
			<div class="cookies-btn no-cookie">Не согласен</div>
		</div>
		<button type="button" class="cookie-policy-button">✕</button>
	</div>

	<div id="callback" class="modal-block">
		<div class="modal-wrap">
			<div class="title-form">Получить консультацию</div>
			<?php echo do_shortcode('[contact-form-7 id="2cab662" title="Получить консультацию"]'); ?>		
		</div>
	</div>

	<div id="order-modal" class="modal-block">
		<div class="modal-wrap">
			<div class="title-form">Забронировать</div>
			<?php echo do_shortcode('[contact-form-7 id="1ca4279" title="Забронировать номер"]'); ?>		
		</div>
	</div>

	<script src="<?php echo get_template_directory_uri(); ?>/libs/jquery/jquery-3.6.0.min.js"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/libs/fancybox-master/dist/jquery.fancybox.min.js"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/libs/slick-1.5.9/slick/slick.min.js"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/libs/datetimepicker/jquery.datetimepicker.full.min.js"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/libs/mask/jquery.maskedinput.js"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/js/common.js?ver=1.1"></script>
		<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU"></script>
	<script>
		$(document).ready(function () {
			ymaps.ready(init);

			function init() {
				var myMap = new ymaps.Map('map', {
					center: [44.657197, 34.390693],
					zoom: 16
				}, {
					searchControlProvider: 'yandex#search'
				});

				myPlacemark = new ymaps.Placemark([44.657197, 34.390693], {
				}, {
					iconLayout: 'default#image',
					iconImageHref: '<?php echo get_template_directory_uri(); ?>/img/pin.svg',
					hideIconOnBalloonOpen: false,
					iconImageSize: [18, 24],
					iconImageOffset: [-9, -24],
					balloonOffset: [0, -24]
				});
				myMap.controls.remove('searchControl');
				myMap.behaviors.disable('scrollZoom');
				myMap.geoObjects.add(myPlacemark);
			}
		});
	</script>
	<!-- Yandex.Metrika counter --><!-- /Yandex.Metrika counter -->
	<!-- Google Analytics counter --><!-- /Google Analytics counter -->


<?php wp_footer(); ?>

</body>
</html>
