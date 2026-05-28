<?php
defined( 'ABSPATH' ) || exit;

/**
 * Карточка товара: одно фото или слайдер (главное + галерея).
 *
 * Ожидает в области видимости: WC_Product $product,
 * array $maintheme_card_slider_ids, bool $maintheme_card_has_gallery_slider.
 */

$fb_group = 'product-gallery-' . (int) $product->get_id();

if ( ! empty( $maintheme_card_has_gallery_slider ) && ! empty( $maintheme_card_slider_ids ) ) :
	?>
	<div class="slider-card-gallery-outer">
		<div class="slider-card-gallery-view">
			<div class="slider-card-gallery js-slider-card-gallery">
		<?php
		foreach ( $maintheme_card_slider_ids as $attach_id ) :
			$attach_id = (int) $attach_id;
			if ( $attach_id < 1 ) {
				continue;
			}
			$img_url = wp_get_attachment_image_url( $attach_id, 'full' );
			if ( ! $img_url ) {
				continue;
			}
			?>
			<div class="slider-card-gallery__slide">
				<a href="<?php echo esc_url( $img_url ); ?>" class="image-card fancybox" data-fancybox-group="<?php echo esc_attr( $fb_group ); ?>">
					<?php echo wp_get_attachment_image( $attach_id, 'full' ); ?>
				</a>
			</div>
			<?php
		endforeach;
		?>
			</div>
		</div>
		<div class="slider-card-gallery-dots-wrap"></div>
	</div>
	<?php
else :
	$image_id = $product->get_image_id();
	if ( $image_id ) {
		$image_url = wp_get_attachment_image_url( $image_id, 'full' );
		echo '<a href="' . esc_url( $image_url ) . '" class="image-card fancybox" data-fancybox-group="' . esc_attr( $fb_group ) . '">';
		echo wp_get_attachment_image( $image_id, 'full' );
		echo '</a>';
	} else {
		echo '<div class="image-card">' . sprintf( '<img src="%s" alt="%s" />', esc_url( wc_placeholder_img_src( 'woocommerce_single' ) ), esc_html__( 'Awaiting product image', 'woocommerce' ) ) . '</div>';
	}
endif;
