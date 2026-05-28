<?php
/**
 * Front — контакты
 *
 * @package maintheme
 */
$t        = get_template_directory_uri();
$sect     = get_field( 'kontakty' );
$pochta   = maintheme_get_option( 'pochta' );
$telefon  = maintheme_get_option( 'telefon' );
$telegram = maintheme_get_option( 'telegram' );
$adres    = maintheme_get_option( 'polnyj_adres' );

if ( ! maintheme_section_show( $sect ) ) {
	return;
}

$map_lat  = '45.30861';
$map_lng  = '34.64750';
$map_zoom = 14;

if ( is_array( $sect ) ) {
	if ( maintheme_is_filled( $sect['karta_shirota'] ?? '' ) ) {
		$map_lat = $sect['karta_shirota'];
	}
	if ( maintheme_is_filled( $sect['karta_dolgota'] ?? '' ) ) {
		$map_lng = $sect['karta_dolgota'];
	}
	if ( maintheme_is_filled( $sect['karta_zoom'] ?? '' ) ) {
		$map_zoom = (int) $sect['karta_zoom'];
	}
}
?>
<section class="contacts" id="contacts">
	<div class="container-fluid container-main">
		<div class="row contacts__inner align-items-start">
			<div class="col-lg-5 col-md-12">
				<div class="contacts__info">
					<div class="contacts__content">
						<?php if ( maintheme_is_filled( $sect['zagolovok'] ?? '' ) ) { ?>
							<h2 class="title-section"><?php echo esc_html( $sect['zagolovok'] ); ?></h2>
						<?php } ?>
						<?php if ( $telefon || $telegram || $pochta || $adres ) { ?>
							<div class="contacts__details">
								<?php if ( $telefon || $telegram ) { ?>
									<div class="contacts__phone-row">
										<?php if ( $telefon ) { ?>
											<p class="contacts__phone">Телефон: <strong><a href="tel:<?php echo esc_attr( maintheme_phone_href( $telefon ) ); ?>"><?php echo esc_html( $telefon ); ?></a></strong></p>
										<?php } ?>
										<?php if ( $telegram ) { ?>
											<a href="<?php echo esc_url( $telegram ); ?>" class="btn-telegram btn-telegram--contacts" target="_blank" rel="noopener">
												<img src="<?php echo esc_url( $t ); ?>/img/icon-telegram.svg" alt="Telegram">
											</a>
										<?php } ?>
									</div>
								<?php } ?>
								<?php if ( $pochta || $adres ) { ?>
									<p class="contacts__text">
										<?php if ( $pochta ) { ?>Почта: <strong><?php echo esc_html( $pochta ); ?></strong><?php if ( $adres ) { ?><br><?php } ?><?php } ?>
										<?php if ( $adres ) { ?>Адрес: <strong><?php echo esc_html( $adres ); ?></strong><?php } ?>
									</p>
								<?php } ?>
							</div>
						<?php } ?>
					</div>
					<div class="contacts__actions">
						<a href="#modal-application" class="btn-main btn-main--contacts fancybox">Оставить заявку <img src="<?php echo esc_url( $t ); ?>/img/icon-arrow.svg" alt=""></a>
						<a href="#modal-callback" class="btn-main btn-main--yellow btn-main--contacts fancybox">Заказать звонок <img src="<?php echo esc_url( $t ); ?>/img/icon-arrow.svg" alt=""></a>
					</div>
				</div>
			</div>
			<div class="col-lg-7 col-md-12">
				<div class="contacts__map">
					<div
						id="map"
						data-lat="<?php echo esc_attr( $map_lat ); ?>"
						data-lng="<?php echo esc_attr( $map_lng ); ?>"
						data-zoom="<?php echo esc_attr( $map_zoom ); ?>"
						data-pin="<?php echo esc_url( $t ); ?>/img/icon-map-pin.svg"
					></div>
				</div>
			</div>
		</div>
	</div>
</section>
