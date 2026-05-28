<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.4.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Check if the product is a valid WooCommerce product and ensure its visibility before proceeding.
if ( ! is_a( $product, WC_Product::class ) || ! $product->is_visible() ) {
	return;
}
?>
<div class="col-lg-4 col-md-6">
	<div <?php wc_product_class( 'item-card', $product ); ?>>
		<div class="card-action">
			<?php $result = in_array(get_the_ID(), get_user_favorites()); ?>
			<div class="btn-like btn-card btn-card--favorites <?php if($result){ ?>active<?php } ?>"><?php the_favorites_button(); ?></div>
			<a href="#order-1click" class="btn-card btn-card--basket fancybox" data-title="<?php echo esc_attr( $product->get_name() ); ?>">
				<svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_2515_922)"><path d="M21.9994 21.2866L21.1815 6.87822C21.1614 6.52289 20.8677 6.24578 20.512 6.24578H17.6635V3.8899C17.6635 1.74767 15.9209 0.00500488 13.7786 0.00500488C11.6364 0.00500488 9.89375 1.74767 9.89375 3.8899V6.24578H7.18969C6.83392 6.24578 6.54027 6.52289 6.52016 6.87822L5.70224 21.2866C5.6807 21.6728 5.98811 21.995 6.37177 21.995H21.3299C21.7147 21.995 22.0209 21.6711 21.9994 21.2866ZM11.2346 3.8899C11.2346 2.48692 12.3761 1.34586 13.7786 1.34586C15.1812 1.34586 16.3227 2.48692 16.3227 3.8899V6.24578H11.2346V3.8899ZM7.08108 20.6541L7.82302 7.58664H9.89375V9.99257C9.89375 10.3626 10.1941 10.663 10.5642 10.663C10.9347 10.663 11.2346 10.3626 11.2346 9.99257V7.58664H16.3227V9.99257C16.3227 10.3626 16.6226 10.663 16.9931 10.663C17.3632 10.663 17.6635 10.3626 17.6635 9.99257V7.58664H19.8786L20.6201 20.6541H7.08108Z" fill="#3F5E29" /><path d="M4.21466 10.3296H0.670427C0.300172 10.3296 0 10.6298 0 11C0 11.3703 0.300172 11.6704 0.670427 11.6704H4.21466C4.58491 11.6704 4.88509 11.3703 4.88509 11C4.88509 10.6298 4.58491 10.3296 4.21466 10.3296Z" fill="#3F5E29" /><path d="M4.21533 13.6557H2.04543C1.67517 13.6557 1.375 13.9558 1.375 14.3261C1.375 14.6964 1.67517 14.9965 2.04543 14.9965H4.21533C4.58558 14.9965 4.88576 14.6964 4.88576 14.3261C4.88576 13.9558 4.58558 13.6557 4.21533 13.6557Z" fill="#3F5E29" /><path d="M4.21466 16.9818H0.670427C0.300172 16.9818 0 17.2819 0 17.6522C0 18.0224 0.300172 18.3226 0.670427 18.3226H4.21466C4.58491 18.3226 4.88509 18.0224 4.88509 17.6522C4.88509 17.2819 4.58491 16.9818 4.21466 16.9818Z" fill="#3F5E29" /></g><defs><clipPath id="clip0_2515_922"><rect width="22" height="22" fill="white" /></clipPath></defs></svg>
			</a>
		</div>
		<a href="<?php the_permalink(); ?>" class="item-card__image">
			<?php echo $product->get_image('large'); ?>
		</a>
		<div class="item-card__content">
			<div class="item-card__top">
				<div class="item-card__articul"><?php echo $product->get_sku() ? 'Арт.: '.$product->get_sku() : ''; ?></div>
				<div class="availability"><?php echo $product->is_in_stock() ? 'В наличии' : 'Нет в наличии'; ?></div>
			</div>
			<a href="<?php the_permalink(); ?>" class="item-card__title"><?php the_title(); ?></a>
			<?php 
			$composition_items = get_field('tovary', $product->get_id());
			if ( ! empty( $composition_items ) && is_array( $composition_items ) ) : ?>
				<div class="item-card__bottom" style="margin-top:auto">
					<a href="<?php the_permalink(); ?>" class="btn-page">к композиции</a>
				</div>
			<?php else : ?>
			<div class="item-card__prices">
				<?php 
				if ( $product->is_type( 'variable' ) ) {
					$min_regular_price = $product->get_variation_regular_price( 'min', true );
					$min_price         = $product->get_variation_price( 'min', true );

					if ( $min_price < $min_regular_price ) {
						?>
						<div class="price-old"><?php echo wc_price( $min_regular_price ); ?></div>
						<div class="price-main"><?php echo wc_price( $min_price ); ?></div>
						<?php
					} else {
						?>
						<div class="price-main"><?php echo wc_price( $min_price ); ?></div>
						<?php
					}
				} else {
					if ( $product->is_on_sale() ) {
						?>
						<div class="price-old"><?php echo wc_price( $product->get_regular_price() ); ?></div>
						<div class="price-main"><?php echo wc_price( $product->get_sale_price() ); ?></div>
						<?php
					} else {
						?>
						<div class="price-main"><?php echo wc_price( $product->get_price() ); ?></div>
						<?php
					}
				}
				?>
			</div>
			<?php
			if ( $product->is_type( 'variable' ) ) {
				?>
				<div class="item-card__bottom">
					<a href="<?php the_permalink(); ?>" class="btn-page">Подробнее</a>
				</div>
				<?php
			} else {
				?>
				<form class="cart item-card__bottom" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
					<div class="quantity">
						<div class="quantity-button quantity-down"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 12H18" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg></div>
						<input type="number" name="quantity" min="1" step="1" value="1">
						<div class="quantity-button quantity-up"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 12H18" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /><path d="M12 18L12 6" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg></div>
					</div>
					<button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="btn-page">в корзину</button>
				</form>
				<?php
			}
			endif;
			?>
		</div>
	</div>
</div>
