<?php
/**
 * Front — баннер
 *
 * @package maintheme
 */
$t      = get_template_directory_uri();
$banner = get_field( 'banner' );

if ( ! maintheme_section_show( $banner ) ) {
	return;
}

$bg = $banner['izobrazhenie_bannera'] ?? null;
?>
<section class="billbord">
	<?php if ( maintheme_is_filled( $bg ) ) { ?>
		<div class="billbord__bg">
			<img src="<?php echo esc_url( maintheme_img_url( $bg ) ); ?>" alt="<?php echo esc_attr( maintheme_img_alt( $bg ) ); ?>">
		</div>
	<?php } ?>
	<div class="container-fluid container-main">
		<div class="billbord__spacer"></div>
		<?php if ( ! empty( $banner['osobennosti'] ) && is_array( $banner['osobennosti'] ) ) { ?>
			<div class="billbord__features">
				<?php foreach ( $banner['osobennosti'] as $feature ) { ?>
					<?php if ( ! maintheme_is_filled( $feature['tekst'] ?? '' ) ) { continue; } ?>
					<div class="billbord__feature">
						<img src="<?php echo esc_url( $t ); ?>/img/icon-check-hero.svg" alt="">
						<span><?php echo esc_html( $feature['tekst'] ); ?></span>
					</div>
				<?php } ?>
			</div>
		<?php } ?>
		<div class="billbord__main">
			<?php if ( maintheme_is_filled( $banner['zagolovok'] ?? '' ) ) { ?>
				<h1 class="billbord__title"><?php echo wp_kses_post( $banner['zagolovok'] ); ?></h1>
			<?php } ?>
			<?php if ( ! empty( $banner['spisok'] ) && is_array( $banner['spisok'] ) ) { ?>
				<ul class="billbord__list">
					<?php foreach ( $banner['spisok'] as $row ) { ?>
						<?php if ( ! maintheme_is_filled( $row['tekst'] ?? '' ) ) { continue; } ?>
						<li><?php echo esc_html( $row['tekst'] ); ?></li>
					<?php } ?>
				</ul>
			<?php } ?>
		</div>
		<?php
		$has_btn  = maintheme_is_filled( $banner['knopka_ssylka'] ?? '' ) && maintheme_is_filled( $banner['knopka_tekst'] ?? '' );
		$has_promo = maintheme_is_filled( $banner['promo_teg'] ?? '' ) || maintheme_is_filled( $banner['promo_tekst'] ?? '' );
		?>
		<?php if ( $has_btn || $has_promo ) { ?>
			<div class="billbord__bottom">
				<?php if ( $has_btn ) { ?>
					<a href="<?php echo esc_url( $banner['knopka_ssylka'] ); ?>" class="btn-main"><?php echo esc_html( $banner['knopka_tekst'] ); ?> <img src="<?php echo esc_url( $t ); ?>/img/icon-arrow.svg" alt=""></a>
				<?php } ?>
				<?php if ( $has_promo ) { ?>
					<div class="billbord__promo">
						<?php if ( maintheme_is_filled( $banner['promo_teg'] ?? '' ) ) { ?>
							<span class="billbord__promo-tag"><?php echo esc_html( $banner['promo_teg'] ); ?></span>
						<?php } ?>
						<?php if ( maintheme_is_filled( $banner['promo_tekst'] ?? '' ) ) { ?>
							<p class="billbord__promo-text"><?php echo esc_html( $banner['promo_tekst'] ); ?></p>
						<?php } ?>
					</div>
				<?php } ?>
			</div>
		<?php } ?>
	</div>
</section>
