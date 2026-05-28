<?php
/**
 * Front — как начать
 *
 * @package maintheme
 */
$sect = get_field( 'shagi' );

if ( ! maintheme_section_show( $sect ) ) {
	return;
}

$photo = $sect['izobrazhenie'] ?? null;
?>
<section class="steps" id="steps">
	<div class="container-fluid container-main">
		<div class="row steps__inner">
			<div class="col-lg-6 col-md-12 steps__left">
				<?php if ( maintheme_is_filled( $sect['zagolovok'] ?? '' ) ) { ?>
					<h2 class="title-section"><?php echo esc_html( $sect['zagolovok'] ); ?></h2>
				<?php } ?>
				<?php if ( ! empty( $sect['elementy'] ) && is_array( $sect['elementy'] ) ) { ?>
					<div class="steps__list">
						<?php foreach ( $sect['elementy'] as $index => $step ) { ?>
							<?php if ( ! maintheme_is_filled( $step['tekst'] ?? '' ) ) { continue; } ?>
							<div class="item-step">
								<div class="item-step__num"><?php echo esc_html( sprintf( '%02d', $index + 1 ) ); ?></div>
								<p class="item-step__text"><?php echo esc_html( $step['tekst'] ); ?></p>
							</div>
						<?php } ?>
					</div>
				<?php } ?>
			</div>
			<?php if ( maintheme_is_filled( $photo ) ) { ?>
				<div class="col-lg-6 col-md-12 steps__photo">
					<img src="<?php echo esc_url( maintheme_img_url( $photo ) ); ?>" alt="<?php echo esc_attr( maintheme_img_alt( $photo ) ); ?>">
				</div>
			<?php } ?>
		</div>
	</div>
</section>
