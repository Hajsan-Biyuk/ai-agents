<?php
/**
 * Delivery page — оплата
 *
 * @package maintheme
 */
$sect = get_field( 'oplata' );

if ( ! maintheme_section_show( $sect ) ) {
	return;
}

$cards      = ! empty( $sect['kartochki'] ) && is_array( $sect['kartochki'] ) ? $sect['kartochki'] : array();
$first_card = ! empty( $cards ) ? array_shift( $cards ) : null;
?>
<div class="delivery-page__section delivery-page__section--payment">
	<?php if ( maintheme_is_filled( $sect['zagolovok'] ?? '' ) ) { ?>
		<h1 class="delivery-page__heading"><?php echo esc_html( $sect['zagolovok'] ); ?></h1>
	<?php } ?>
	<div class="delivery-page__block">
		<?php if ( maintheme_is_filled( $sect['intro'] ?? '' ) || $first_card ) { ?>
			<div class="row align-items-center delivery-page__row">
				<?php if ( maintheme_is_filled( $sect['intro'] ?? '' ) ) { ?>
					<div class="col-lg-6 delivery-page__col">
						<div class="delivery-page__intro">
							<?php echo wp_kses_post( $sect['intro'] ); ?>
						</div>
					</div>
				<?php } ?>
				<?php if ( $first_card ) { ?>
					<?php
					$icon  = $first_card['ikonka'] ?? null;
					$title = $first_card['zagolovok'] ?? '';
					$text  = $first_card['tekst'] ?? '';
					$cvet  = $first_card['cvet'] ?? 'cream';
					$tall  = ! empty( $first_card['vysokaya'] );
					if ( ! maintheme_is_filled( $title ) && ! maintheme_is_filled( $text ) && ! maintheme_is_filled( $icon ) ) {
						$first_card = null;
					}
					?>
					<?php if ( $first_card ) { ?>
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
				<?php } ?>
			</div>
		<?php } ?>
		<?php if ( ! empty( $cards ) ) { ?>
			<div class="row delivery-page__row">
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
</div>
