<?php
$t        = get_template_directory_uri();
$pochta   = maintheme_get_option( 'pochta' );
$telefon  = maintheme_get_option( 'telefon' );
$telegram = maintheme_get_option( 'telegram' );
$adres    = maintheme_get_option( 'polnyj_adres' );
$politika = maintheme_get_option( 'politika' );
$soglasie = maintheme_get_option( 'soglasie' );
?>
		<footer class="footer">
			<div class="container-fluid container-main">
				<div class="row footer__top align-items-start">
					<div class="col-lg-3 footer__logo-col">
						<?php if ( is_front_page() ) { ?>
							<span class="logo footer__logo">
								<img src="<?php echo esc_url( $t ); ?>/img/logo.svg" alt="АГРО АС">
							</span>
						<?php } else { ?>
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo footer__logo">
								<img src="<?php echo esc_url( $t ); ?>/img/logo.svg" alt="АГРО АС">
							</a>
						<?php } ?>
					</div>
					<div class="col-lg-9 footer__nav-wrap">
						<div class="row align-items-start">
							<div class="col-lg-3 col-md-6 footer__col">
								<ul class="footer__menu">
									<?php
										wp_nav_menu([
											'menu'            => 'Футер меню',
											'menu_class'      => '',
											'container'       => false,
											'items_wrap' => '%3$s',
										]);
									?>
								</ul>
							</div>
							<div class="col-lg-6 col-md-12 footer__col footer__col--catalog">
								<?php get_template_part( 'template-parts/footer/catalog' ); ?>
							</div>
							<div class="col-lg-3 col-md-12 footer__col footer__col--contacts ml-lg-auto">
								<?php if ( $adres || $pochta ) { ?>
									<p class="footer__contacts-text">
										<?php if ( $adres ) { ?>Адрес: <strong><?php echo esc_html( $adres ); ?></strong><br><?php } ?>
										<?php if ( $pochta ) { ?>Почта: <strong><?php echo esc_html( $pochta ); ?></strong><?php } ?>
									</p>
								<?php } ?>
								<div class="footer__phone-row">
									<?php if ( $telefon ) { ?>
										<a href="tel:<?php echo esc_attr( maintheme_phone_href( $telefon ) ); ?>" class="footer__phone"><?php echo esc_html( $telefon ); ?></a>
									<?php } ?>
									<?php if ( $telegram ) { ?>
										<a href="<?php echo esc_url( $telegram ); ?>" class="btn-telegram btn-telegram--footer" target="_blank" rel="noopener">
											<img src="<?php echo esc_url( $t ); ?>/img/icon-telegram.svg" alt="Telegram">
										</a>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="footer__bottom">
					<div class="footer__legal">
						<?php if ( $politika ) { ?>
							<a href="<?php echo esc_url( $politika ); ?>">Политика конфиденциальности</a>
						<?php } else { ?>
							<a href="#">Политика конфиденциальности</a>
						<?php } ?>
						<?php if ( $soglasie ) { ?>
							<a href="<?php echo esc_url( $soglasie ); ?>">Согласие на обработку персональных данных</a>
						<?php } else { ?>
							<a href="#">Согласие на обработку персональных данных</a>
						<?php } ?>
						<span class="footer__copy">© Copyright <?php echo esc_html( date( 'Y' ) ); ?> г.</span>
					</div>
					<div class="footer__made">Сделано в <span class="footer__made-logo"><a href="https://sheer.ru/" target="_blank">Sheer</a></span></div>
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

	<div id="modal-callback" class="modal-block">
		<div class="modal-wrap">
			<div class="title-section">Заказать звонок</div>
			<div class="descr-modal">и наш менеджер свяжется с вами в течение 30 минут</div>
			<?php echo do_shortcode('[contact-form-7 id="b1cea3f" title="Заказать звонок"]'); ?>
		</div>
	</div>

	<div id="modal-application" class="modal-block">
		<div class="modal-wrap">
			<div class="title-section">Оставить заявку</div>
			<div class="descr-modal">и получите индивидуальное коммерческое предложение в течение 30 минут</div>
			<?php echo do_shortcode('[contact-form-7 id="c717a79" title="Оставить заявку"]'); ?>
		</div>
	</div>

	<div id="modal-tiers" class="modal-block">
		<div class="modal-wrap">
			<div class="title-section">Оставить заявку на прайс-лист</div>
			<div class="descr-modal">и получите индивидуальное коммерческое предложение в течение 30 минут</div>
			<?php echo do_shortcode('[contact-form-7 id="a65c770" title="Получить прайс-лист"]'); ?>
		</div>
	</div>

	<div id="modal-ceny" class="modal-block">
		<div class="modal-wrap">
			<div class="title-section">Оставить заявку на цены</div>
			<div class="descr-modal">и получите индивидуальное коммерческое предложение в течение 30 минут</div>
			<?php echo do_shortcode('[contact-form-7 id="4e694e2" title="Узнать цены"]'); ?>
		</div>
	</div>

	<script src="<?php echo esc_url( $t ); ?>/libs/jquery/jquery-3.6.0.min.js"></script>
	<script src="<?php echo esc_url( $t ); ?>/libs/fancybox-master/dist/jquery.fancybox.min.js"></script>
	<script src="<?php echo esc_url( $t ); ?>/libs/slick-1.5.9/slick/slick.min.js"></script>
	<script src="<?php echo esc_url( $t ); ?>/libs/mask/jquery.maskedinput.js"></script>
	<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU"></script>
	<?php wp_footer(); ?>
	<script src="<?php echo esc_url( $t ); ?>/js/common.js?ver=4.9"></script>
</body>
</html>
