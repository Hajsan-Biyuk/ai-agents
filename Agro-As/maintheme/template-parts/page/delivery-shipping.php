<?php
/**
 * Delivery page — доставка
 *
 * @package maintheme
 */
$sect = get_field( 'dostavka' );

if ( ! maintheme_section_show( $sect ) ) {
	return;
}

$cards = ! empty( $sect['kartochki'] ) && is_array( $sect['kartochki'] ) ? $sect['kartochki'] : array();
?>
<div class="delivery-page__section delivery-page__section--delivery">
	<?php if ( maintheme_is_filled( $sect['zagolovok'] ?? '' ) ) { ?>
		<h2 class="delivery-page__heading"><?php echo esc_html( $sect['zagolovok'] ); ?></h2>
	<?php } ?>
	<?php if ( maintheme_is_filled( $sect['opisanie'] ?? '' ) ) { ?>
		<div class="delivery-page__descr"><?php echo wp_kses_post( $sect['opisanie'] ); ?></div>
	<?php } ?>
	<?php if ( ! empty( $sect['logotipy'] ) && is_array( $sect['logotipy'] ) ) { ?>
		<div class="delivery-page__tk">
			<div class="row delivery-page__tk-row">
				<?php foreach ( $sect['logotipy'] as $logo_item ) { ?>
					<?php
					$logo = $logo_item['logo'] ?? null;
					if ( ! maintheme_is_filled( $logo ) ) {
						continue;
					}
					?>
					<div class="col-6 col-lg-3 delivery-page__tk-col">
						<div class="delivery-page__tk-item">
							<img src="<?php echo esc_url( maintheme_img_url( $logo ) ); ?>" alt="<?php echo esc_attr( maintheme_img_alt( $logo, 'Транспортная компания' ) ); ?>">
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	<?php } ?>
	<?php if ( ! empty( $cards ) ) { ?>
		<div class="row delivery-page__cards-row">
			<?php foreach ( $cards as $card ) { ?>
				<?php
				$icon  = $card['ikonka'] ?? null;
				$title = $card['zagolovok'] ?? '';
				$text  = $card['tekst'] ?? '';
				$cvet  = $card['cvet'] ?? 'cream';
				$tall  = ! empty( $card['vysokaya'] );
				if ( ! maintheme_is_filled( $title ) && ! maintheme_is_filled( $text ) && ! maintheme_is_filled( $icon ) ) {
					continue;
				}
				?>
				<div class="col-lg-6 delivery-page__col">
					<div class="delivery-page__card delivery-page__card--<?php echo esc_attr( $cvet ); ?><?php echo $tall ? ' delivery-page__card--tall' : ''; ?>">
						<?php if ( maintheme_is_filled( $icon ) ) { ?>
							<img src="<?php echo esc_url( maintheme_img_url( $icon ) ); ?>" class="delivery-page__card-icon" width="42" height="42" alt="<?php echo esc_attr( maintheme_img_alt( $icon ) ); ?>">
						<?php } ?>
						<div class="delivery-page__card-body">
							<?php if ( maintheme_is_filled( $title ) ) { ?>
								<h2 class="delivery-page__card-title"><?php echo esc_html( $title ); ?></h2>
							<?php } ?>
							<?php if ( maintheme_is_filled( $text ) ) { ?>
								<div class="delivery-page__card-text">
									<?php echo wp_kses_post( $text ); ?>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>
	<?php } ?>
</div>
