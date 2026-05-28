<?php
/**
 * Contacts page
 *
 * @package maintheme
 */
$t        = get_template_directory_uri();
$sect     = get_field( 'kontakty' );
$pochta   = maintheme_get_option( 'pochta' );
$telefon  = maintheme_get_option( 'telefon' );
$telegram = maintheme_get_option( 'telegram' );
$adres    = maintheme_get_option( 'polnyj_adres' );
$politika = maintheme_get_option( 'politika' );
$soglasie = maintheme_get_option( 'soglasie' );

if ( ! maintheme_section_show( $sect ) ) {
	return;
}

$map_lat  = '45.30861';
$map_lng  = '34.64750';
$map_zoom = 14;

if ( maintheme_is_filled( $sect['karta_shirota'] ?? '' ) ) {
	$map_lat = $sect['karta_shirota'];
}
if ( maintheme_is_filled( $sect['karta_dolgota'] ?? '' ) ) {
	$map_lng = $sect['karta_dolgota'];
}
if ( maintheme_is_filled( $sect['karta_zoom'] ?? '' ) ) {
	$map_zoom = (int) $sect['karta_zoom'];
}

$rekvizity = $sect['rekvizity'] ?? '';
?>
<section class="contacts-page">
	<div class="container-fluid container-main">
		<?php if ( maintheme_is_filled( $sect['zagolovok'] ?? '' ) ) { ?>
			<h1 class="contacts-page__title title-section"><?php echo esc_html( $sect['zagolovok'] ); ?></h1>
		<?php } ?>
		<div class="contacts-page__body">
			<div class="row contacts-page__cards">
				<?php if ( $adres || $telefon || $telegram ) { ?>
					<div class="col-12 col-md-6 col-lg-6 col-xl-3 contacts-page__col">
						<div class="contacts-page__card contacts-page__card--address">
							<?php if ( $adres ) { ?>
								<div class="contacts-page__group">
									<p class="contacts-page__label">Адрес:</p>
									<p class="contacts-page__value"><?php echo nl2br( esc_html( $adres ) ); ?></p>
								</div>
							<?php } ?>
							<?php if ( $telefon || $telegram ) { ?>
								<div class="contacts-page__phone-row">
									<?php if ( $telefon ) { ?>
										<div class="contacts-page__group contacts-page__group--phone">
											<p class="contacts-page__label">Телефон:</p>
											<p class="contacts-page__value contacts-page__value--phone"><a href="tel:<?php echo esc_attr( maintheme_phone_href( $telefon ) ); ?>"><?php echo esc_html( $telefon ); ?></a></p>
										</div>
									<?php } ?>
									<?php if ( $telegram ) { ?>
										<a href="<?php echo esc_url( $telegram ); ?>" class="btn-telegram btn-telegram--contacts-page" target="_blank" rel="noopener">
											<img src="<?php echo esc_url( $t ); ?>/img/icon-telegram.svg" alt="Telegram">
										</a>
									<?php } ?>
								</div>
							<?php } ?>
						</div>
					</div>
				<?php } ?>
				<?php if ( maintheme_is_filled( $rekvizity ) || $pochta ) { ?>
					<div class="col-12 col-md-6 col-lg-6 col-xl-3 contacts-page__col">
						<div class="contacts-page__card contacts-page__card--details">
							<?php if ( maintheme_is_filled( $rekvizity ) ) { ?>
								<div class="contacts-page__group">
									<p class="contacts-page__label">Реквизиты:</p>
									<div class="contacts-page__value contacts-page__value--requisites"><?php echo wp_kses_post( $rekvizity ); ?></div>
								</div>
							<?php } ?>
							<?php if ( $pochta ) { ?>
								<div class="contacts-page__group">
									<p class="contacts-page__label">Почта:</p>
									<p class="contacts-page__value contacts-page__value--email"><a href="mailto:<?php echo esc_attr( $pochta ); ?>"><?php echo esc_html( $pochta ); ?></a></p>
								</div>
							<?php } ?>
						</div>
					</div>
				<?php } ?>
				<div class="col-12 col-lg-12 col-xl-6 contacts-page__col">
					<div class="contacts-page__card contacts-page__card--form">
						<p class="contacts-page__label">Обратная связь</p>
						<form action="#" method="post">
							<div class="row align-items-stretch">
								<div class="col-12 col-md-6">
									<div class="item-form">
										<input type="text" name="name" placeholder="Имя" required>
									</div>
									<div class="item-form">
										<input type="email" name="email" placeholder="Почта" required>
									</div>
								</div>
								<div class="col-12 col-md-6">
									<div class="item-form item-form--message">
										<textarea name="message" placeholder="Сообщение" required></textarea>
									</div>
								</div>
							</div>
							<div class="row align-items-start">
								<div class="col-12 col-lg">
									<div class="checkbox">
										<label>
											<input type="checkbox" required checked>
											<span>
												Подтверждаю, что ознакомлен(а) с условиями
												<?php if ( $politika ) { ?>
													<a href="<?php echo esc_url( $politika ); ?>">Политики конфиденциальности</a>
												<?php } else { ?>
													Политики конфиденциальности
												<?php } ?>
												<?php if ( $politika && $soglasie ) { ?>
													и
												<?php } ?>
												<?php if ( $soglasie ) { ?>
													<a href="<?php echo esc_url( $soglasie ); ?>">Политики в области обработки персональных данных</a>, даю свое <a href="<?php echo esc_url( $soglasie ); ?>">согласие на обработку персональных данных</a>
												<?php } ?>
											</span>
										</label>
									</div>
								</div>
								<div class="col-12 col-lg-auto">
									<button type="submit" class="btn-main btn-main--yellow">Отправить</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="contacts-page__map">
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
</section>
