<?php
/**
 * Wholesale page — шаги
 *
 * @package maintheme
 */
$t    = get_template_directory_uri();
$sect = get_field( 'shagi' );

if ( ! maintheme_section_show( $sect ) ) {
	return;
}
?>
<section class="wholesale-page-steps">
	<div class="container-fluid container-main">
		<?php if ( maintheme_is_filled( $sect['zagolovok'] ?? '' ) || maintheme_is_filled( $sect['opisanie'] ?? '' ) ) { ?>
			<div class="head-section head-section--light">
				<?php if ( maintheme_is_filled( $sect['zagolovok'] ?? '' ) ) { ?>
					<h2 class="title-section"><?php echo esc_html( $sect['zagolovok'] ); ?></h2>
				<?php } ?>
				<?php if ( maintheme_is_filled( $sect['opisanie'] ?? '' ) ) { ?>
					<p class="descr-section"><?php echo esc_html( $sect['opisanie'] ); ?></p>
				<?php } ?>
			</div>
		<?php } ?>
		<?php if ( ! empty( $sect['elementy'] ) && is_array( $sect['elementy'] ) ) { ?>
			<div class="row wholesale-page-steps__row">
				<?php foreach ( $sect['elementy'] as $index => $step ) { ?>
					<?php
					$title = $step['zagolovok'] ?? '';
					$text  = $step['tekst'] ?? '';
					if ( ! maintheme_is_filled( $title ) && ! maintheme_is_filled( $text ) ) {
						continue;
					}
					?>
					<div class="col-lg-3 col-md-6 wholesale-page-steps__col">
						<div class="wholesale-page-step">
							<div class="wholesale-page-step__head">
								<span class="wholesale-page-step__num"><?php echo esc_html( sprintf( '%02d', $index + 1 ) ); ?></span>
								<img src="<?php echo esc_url( $t ); ?>/img/Arrow_Left_MD.svg" alt="" class="wholesale-page-step__arrow">
							</div>
							<?php if ( maintheme_is_filled( $title ) ) { ?>
								<div class="wholesale-page-step__title"><?php echo esc_html( $title ); ?></div>
							<?php } ?>
							<?php if ( maintheme_is_filled( $text ) ) { ?>
								<p class="wholesale-page-step__text"><?php echo esc_html( $text ); ?></p>
							<?php } ?>
						</div>
					</div>
				<?php } ?>
			</div>
		<?php } ?>
	</div>
</section>
