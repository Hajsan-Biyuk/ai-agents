<?php
/**
 * Recommended products block
 *
 * @package maintheme
 */

$t        = get_template_directory_uri();
$products = maintheme_get_recommended_products( get_the_ID(), 6 );

if ( empty( $products ) ) {
	return;
}
?>
<div class="product-page__recommend">
	<div class="row align-items-center product-page__recommend-layout">
		<div class="col-auto product-page__recommend-arrow-col product-page__recommend-arrow-col--prev">
			<div class="product-page__recommend-prev">
				<button type="button" class="slick-prev product-page__recommend-arrow product-page__recommend-arrow--prev" aria-label="Предыдущий слайд">
					<span class="product-page__recommend-arrow-icon" aria-hidden="true">
						<img src="<?php echo esc_url( $t ); ?>/img/Arrow_Left_SM.svg" width="24" height="24" alt="">
					</span>
				</button>
			</div>
		</div>
		<div class="col product-page__recommend-main">
			<h2 class="title-section product-page__recommend-title">Рекомендуем</h2>
			<div class="products__slider products__slider--recommend">
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
		<div class="col-auto product-page__recommend-arrow-col product-page__recommend-arrow-col--next">
			<div class="product-page__recommend-next">
				<button type="button" class="slick-next product-page__recommend-arrow product-page__recommend-arrow--next" aria-label="Следующий слайд">
					<span class="product-page__recommend-arrow-icon" aria-hidden="true">
						<img src="<?php echo esc_url( $t ); ?>/img/Arrow_Left_SM.svg" width="24" height="24" alt="">
					</span>
				</button>
			</div>
		</div>
	</div>
</div>
