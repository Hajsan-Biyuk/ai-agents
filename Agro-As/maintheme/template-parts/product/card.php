<?php
/**
 * Product card for sliders and catalog loops.
 *
 * @package maintheme
 */

$card = ( isset( $args['card'] ) && is_array( $args['card'] ) ) ? $args['card'] : maintheme_product_card_data();

if ( ! maintheme_is_filled( $card['title'] ?? '' ) ) {
	return;
}
?>
<div class="item-product">
	<div class="item-product__top">
		<?php if ( maintheme_is_filled( $card['image'] ?? '' ) ) { ?>
			<a href="<?php echo esc_url( $card['url'] ); ?>" class="item-product__image">
				<img src="<?php echo esc_url( $card['image'] ); ?>" alt="<?php echo esc_attr( $card['image_alt'] ); ?>">
			</a>
		<?php } ?>
		<?php if ( maintheme_is_filled( $card['tag'] ?? '' ) ) { ?>
			<span class="item-product__tag"><?php echo esc_html( $card['tag'] ); ?></span>
		<?php } ?>
	</div>
	<div class="item-product__body">
		<a href="<?php echo esc_url( $card['url'] ); ?>" class="item-product__name"><?php echo esc_html( $card['title'] ); ?></a>
		<?php if ( maintheme_is_filled( $card['status'] ?? '' ) || maintheme_is_filled( $card['pack_value'] ?? '' ) ) { ?>
			<div class="item-product__meta">
				<?php if ( maintheme_is_filled( $card['status'] ?? '' ) ) { ?>
					<span class="item-product__status"><?php echo esc_html( $card['status'] ); ?></span>
				<?php } ?>
				<?php if ( maintheme_is_filled( $card['pack_value'] ?? '' ) ) { ?>
					<span class="item-product__pack"><?php echo esc_html( $card['pack_label'] ); ?>: <strong><?php echo esc_html( $card['pack_value'] ); ?></strong></span>
				<?php } ?>
			</div>
		<?php } ?>
		<?php if ( maintheme_is_filled( $card['price'] ?? '' ) ) { ?>
			<div class="item-product__footer">
				<span class="item-product__price"><?php echo esc_html( $card['price'] ); ?></span>
				<button
					type="button"
					class="btn-order js-add-to-cart"
					data-product-id="<?php echo esc_attr( (string) ( $card['id'] ?? get_the_ID() ) ); ?>"
					data-variation="<?php echo esc_attr( (string) ( $card['cart_variation'] ?? 0 ) ); ?>"
					data-quantity="1"
					data-label="В корзину"
				>В корзину</button>
			</div>
		<?php } ?>
	</div>
</div>
