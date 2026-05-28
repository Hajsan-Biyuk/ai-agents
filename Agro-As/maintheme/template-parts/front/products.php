<?php
/**
 * Front — товары (слайдер)
 *
 * @package maintheme
 */
$t        = get_template_directory_uri();
$sect     = get_field( 'tovary' );
$products = maintheme_get_front_products( $sect );

if ( ! maintheme_section_show( $sect ) ) {
	return;
}
?>
<section class="products" id="products">
	<div class="container-fluid container-main">
		<?php if ( maintheme_is_filled( $sect['zagolovok'] ?? '' ) || maintheme_is_filled( $sect['podzagolovok'] ?? '' ) ) { ?>
			<div class="head-section">
				<?php if ( maintheme_is_filled( $sect['zagolovok'] ?? '' ) ) { ?>
					<h2 class="title-section"><?php echo esc_html( $sect['zagolovok'] ); ?></h2>
				<?php } ?>
				<?php if ( maintheme_is_filled( $sect['podzagolovok'] ?? '' ) ) { ?>
					<p class="descr-section"><?php echo esc_html( $sect['podzagolovok'] ); ?></p>
				<?php } ?>
			</div>
		<?php } ?>
		<?php if ( ! empty( $products ) ) { ?>
			<div class="row align-items-center products__slider-layout">
				<div class="col-auto products__slider-arrow-col products__slider-arrow-col--prev">
					<div class="products__slider-prev">
						<button type="button" class="slick-prev products__slider-arrow products__slider-arrow--prev" aria-label="Предыдущий слайд">
							<span class="products__slider-arrow-icon" aria-hidden="true">
								<img src="<?php echo esc_url( $t ); ?>/img/Arrow_Left_SM.svg" width="24" height="24" alt="">
							</span>
						</button>
					</div>
				</div>
				<div class="col products__slider-main">
					<div class="products__slider">
						<?php foreach ( $products as $product_post ) { ?>
							<div class="products__slide">
								<?php
								$card = maintheme_product_card_data( $product_post->ID );
								get_template_part( 'template-parts/product/card', null, array( 'card' => $card ) );
								?>
							</div>
						<?php } ?>
					</div>
				</div>
				<div class="col-auto products__slider-arrow-col products__slider-arrow-col--next">
					<div class="products__slider-next">
						<button type="button" class="slick-next products__slider-arrow products__slider-arrow--next" aria-label="Следующий слайд">
							<span class="products__slider-arrow-icon" aria-hidden="true">
								<img src="<?php echo esc_url( $t ); ?>/img/Arrow_Left_SM.svg" width="24" height="24" alt="">
							</span>
						</button>
					</div>
				</div>
			</div>
		<?php } ?>
	</div>
</section>
