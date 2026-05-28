<?php
/**
 * Front — доставка
 *
 * @package maintheme
 */
$t    = get_template_directory_uri();
$sect = get_field( 'dostavka' );

if ( ! maintheme_section_show( $sect ) ) {
	return;
}

$photo = $sect['izobrazhenie'] ?? null;
?>
<section class="delivery" id="delivery">
	<div class="container-fluid container-main">
		<div class="row delivery__inner">
			<?php if ( maintheme_is_filled( $photo ) ) { ?>
				<div class="col-lg-6 col-md-12 delivery__photo">
					<img src="<?php echo esc_url( maintheme_img_url( $photo ) ); ?>" alt="<?php echo esc_attr( maintheme_img_alt( $photo ) ); ?>">
				</div>
			<?php } ?>
			<div class="col-lg-6 col-md-12 delivery__content">
				<?php if ( maintheme_is_filled( $sect['zagolovok'] ?? '' ) ) { ?>
					<h2 class="title-section"><?php echo esc_html( $sect['zagolovok'] ); ?></h2>
				<?php } ?>
				<?php if ( maintheme_is_filled( $sect['podzagolovok'] ?? '' ) ) { ?>
					<p class="descr-section"><?php echo esc_html( $sect['podzagolovok'] ); ?></p>
				<?php } ?>
				<?php if ( ! empty( $sect['preimushhestva'] ) && is_array( $sect['preimushhestva'] ) ) { ?>
					<div class="row delivery__list">
						<?php foreach ( $sect['preimushhestva'] as $item ) { ?>
							<?php if ( ! maintheme_is_filled( $item['tekst'] ?? '' ) ) { continue; } ?>
							<div class="delivery-benefit col-md-6 delivery__col">
								<span class="delivery-benefit__icon"><img src="<?php echo esc_url( $t ); ?>/img/icon-wholesale-check.svg?ver=3.1" alt=""></span>
								<span class="delivery-benefit__text"><?php echo esc_html( $item['tekst'] ); ?></span>
							</div>
						<?php } ?>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
</section>
