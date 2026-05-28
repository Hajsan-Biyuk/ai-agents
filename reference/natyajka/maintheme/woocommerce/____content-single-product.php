<?php
defined( 'ABSPATH' ) || exit;

global $product;

$variation_attribute_name   = '';
$variation_attribute_label  = '';
$variation_attribute_values = array();
$variation_attributes_data  = array();
$variation_options_data     = array();
$default_variation_value    = '';

if ( $product->is_type( 'variable' ) ) {
	$sort_variations_by_admin_order = function( $variable_product, $variations ) {
		$children_ids = array_values( array_map( 'intval', (array) $variable_product->get_children() ) );
		if ( empty( $children_ids ) || empty( $variations ) ) {
			return $variations;
		}

		$order_map = array_flip( $children_ids );
		usort(
			$variations,
			function( $a, $b ) use ( $order_map ) {
				$a_id = isset( $a['variation_id'] ) ? (int) $a['variation_id'] : 0;
				$b_id = isset( $b['variation_id'] ) ? (int) $b['variation_id'] : 0;

				$a_pos = isset( $order_map[ $a_id ] ) ? (int) $order_map[ $a_id ] : PHP_INT_MAX;
				$b_pos = isset( $order_map[ $b_id ] ) ? (int) $order_map[ $b_id ] : PHP_INT_MAX;

				if ( $a_pos === $b_pos ) {
					return $a_id <=> $b_id;
				}

				return $a_pos <=> $b_pos;
			}
		);

		return $variations;
	};

	$variation_attributes = $product->get_variation_attributes();
	if ( ! empty( $variation_attributes ) ) {
		$variation_attribute_name = (string) array_key_first( $variation_attributes );
		$variation_attribute_values = array();
		$variation_attribute_label = wc_attribute_label( $variation_attribute_name );

		foreach ( $variation_attributes as $attribute_name => $attribute_values ) {
			$attribute_values = array_values( array_filter( (array) $attribute_values ) );
			if ( empty( $attribute_values ) ) {
				continue;
			}

			$variation_attributes_data[] = array(
				'name'   => $attribute_name,
				'label'  => wc_attribute_label( $attribute_name ),
				'values' => $attribute_values,
			);
		}
	}

	if ( $variation_attribute_name ) {
		$default_attributes = $product->get_default_attributes();
		$default_variation_value = isset( $default_attributes[ $variation_attribute_name ] ) ? (string) $default_attributes[ $variation_attribute_name ] : '';
		$available_variations = $sort_variations_by_admin_order( $product, $product->get_available_variations() );
		$variation_attribute_key = 'attribute_' . $variation_attribute_name;

		foreach ( $available_variations as $available_variation ) {
			if ( empty( $available_variation['attributes'][ $variation_attribute_key ] ) ) {
				continue;
			}

			$option_value = (string) $available_variation['attributes'][ $variation_attribute_key ];
			if ( ! in_array( $option_value, $variation_attribute_values, true ) ) {
				$variation_attribute_values[] = $option_value;
			}
			if ( isset( $variation_options_data[ $option_value ] ) ) {
				continue;
			}

			$variation_obj = wc_get_product( $available_variation['variation_id'] );
			$variation_options_data[ $option_value ] = array(
				'variation_id'       => (int) $available_variation['variation_id'],
				'display_price'      => (float) $available_variation['display_price'],
				'display_regular'    => (float) $available_variation['display_regular_price'],
				'price_html'         => wc_price( (float) $available_variation['display_price'] ),
				'regular_price_html' => wc_price( (float) $available_variation['display_regular_price'] ),
				'sku'                => (string) $variation_obj->get_sku(),
				'diameter'           => get_post_meta( (int) $available_variation['variation_id'], '_maintheme_variation_diameter', true ),
				'shape'              => get_post_meta( (int) $available_variation['variation_id'], '_maintheme_variation_shape', true ),
				'literage'           => get_post_meta( (int) $available_variation['variation_id'], '_maintheme_variation_literage', true ),
				'stock_quantity'     => $variation_obj->managing_stock() ? $variation_obj->get_stock_quantity() : '',
				'is_in_stock'        => $variation_obj->is_in_stock(),
			);
		}

		if ( ! $default_variation_value || ! isset( $variation_options_data[ $default_variation_value ] ) ) {
			$default_variation_value = (string) array_key_first( $variation_options_data );
		}
	}
}

do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>
<section class="page-card">
			<div class="container">
				<?php
				ob_start();
				woocommerce_breadcrumb( array(
					'wrap_before' => '<ul class="breadcrumbs">',
					'wrap_after'  => '</ul>',
					'before'      => '<li>',
					'after'       => '</li>',
					'delimiter'   => ''
				) );
				$breadcrumb = ob_get_clean();
				if ( $breadcrumb ) echo $breadcrumb;
				?>
				<div class="row">
					<?php 
					$composition_items = get_field('tovary');
					
					// Проверяем наличие композиций с данным товаром заранее
					$current_product_id = $product->get_id();
					$composition_args = array(
						'post_type'      => 'product',
						'post_status'    => 'publish',
						'posts_per_page' => 10,
						'meta_query'     => array(
							array(
								'key'     => 'tovary',
								'value'   => '"' . $current_product_id . '"',
								'compare' => 'LIKE'
							)
						)
					);
					$composition_query = new WP_Query( $composition_args );
					$has_compositions = $composition_query->have_posts();
					
					if ( ! empty( $composition_items ) && is_array( $composition_items ) ) : ?>
					
					<!-- COMPOSITION LAYOUT -->
					<div class="col-lg-6">
						<div class="card-image-wrap">
							<?php 
							$image_id = $product->get_image_id();
							if ( $image_id ) {
								$image_url = wp_get_attachment_image_url( $image_id, 'full' );
								echo '<a href="' . esc_url( $image_url ) . '" class="image-card fancybox">';
								echo wp_get_attachment_image( $image_id, 'full' );
								echo '</a>';
							} else {
								echo '<div class="image-card">' . sprintf( '<img src="%s" alt="%s" />', esc_url( wc_placeholder_img_src( 'woocommerce_single' ) ), esc_html__( 'Awaiting product image', 'woocommerce' ) ) . '</div>';
							}
							?>
							<div class="card-action">							
								<?php $result = in_array(get_the_ID(), get_user_favorites()); ?>
                  				<div class="btn-like btn-card btn-card--favorites <?php if($result){ ?>active<?php } ?>"><?php the_favorites_button(); ?></div>
							</div>
							<?php if ( is_user_logged_in() && ! empty( get_field( '3d_model' ) ) ) { ?>
								<a href="<?php echo esc_url( get_field( '3d_model' ) ); ?>" class="btn-light" target="_blank" rel="noopener noreferrer">
									скачать 3Д модель
									<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5 17.5H15" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /><path d="M10 2.5L10 14.1667" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /><path d="M14.1673 10L10.0007 14.1667L5.83398 10" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
								</a>
							<?php } ?>
						</div>
					</div>
					<div class="col-lg-6">
						<?php 
						foreach ( $composition_items as $comp_item ) {
							$child_product = wc_get_product( $comp_item->ID );
							if ( ! $child_product ) continue;
							
							$child_img = wp_get_attachment_image_url( $child_product->get_image_id(), 'medium' );
							if ( ! $child_img ) $child_img = wc_placeholder_img_src();
						?>
						<div class="unit-composition js-unit-composition" data-product-id="<?php echo esc_attr($child_product->get_id()); ?>">
							<a href="<?php echo esc_url($child_product->get_permalink()); ?>" class="unit-composition__image">
								<img src="<?php echo esc_url($child_img); ?>" alt="<?php echo esc_attr($child_product->get_name()); ?>">
							</a>
							<div class="unit-composition__content">
								<a href="<?php echo esc_url($child_product->get_permalink()); ?>" class="unit-composition__title"><?php echo esc_html($child_product->get_name()); ?></a>
								<div class="unit-composition__text"><?php echo apply_filters('woocommerce_short_description', $child_product->get_short_description()); ?></div>
								
								<?php if ( $child_product->is_type('variable') ) : 
									$available_variations = $child_product->get_available_variations();
									if ( ! empty( $available_variations ) ) :
										$unit_variation_label = '';
										if ( ! empty( $available_variations[0]['attributes'] ) ) {
											$unit_first_attr_key = (string) array_key_first( $available_variations[0]['attributes'] );
											if ( 0 === strpos( $unit_first_attr_key, 'attribute_' ) ) {
												$unit_tax_name = str_replace( 'attribute_', '', $unit_first_attr_key );
												$unit_variation_label = wc_attribute_label( $unit_tax_name );
											}
										}
								?>
								<?php if ( $unit_variation_label ) : ?>
								<div class="properties__title"><?php echo esc_html( $unit_variation_label ); ?>:</div>
								<?php endif; ?>
								<div class="properties__values properties__values--unit js-unit-variations">
									<?php 
									$first = true;
									foreach ( $available_variations as $var ) {
										$attr_val = '';
										foreach ( $var['attributes'] as $key => $v ) {
											$attr_val = $v;
											$tax = str_replace('attribute_', '', $key);
											if ( taxonomy_exists($tax) ) {
												$term = get_term_by('slug', $v, $tax);
												if ( $term ) $attr_val = $term->name;
											}
											break;
										}
									?>
									<div class="property__value <?php echo $first ? 'active' : ''; ?> js-unit-v" 
										data-variation-id="<?php echo esc_attr($var['variation_id']); ?>"
										data-price="<?php echo esc_attr($var['display_price']); ?>"
										data-price-html="<?php echo esc_attr(wc_price($var['display_price'])); ?>">
										<div class="properties__icon"></div>
										<?php echo esc_html($attr_val); ?>
									</div>
									<?php $first = false; } ?>
								</div>
								<?php endif; endif; ?>
							</div>
							
							<div class="unit-composition__info">
								<?php 
								$start_price = $child_product->get_price();
								if ( $child_product->is_type('variable') && !empty($available_variations) ) {
									$start_price = $available_variations[0]['display_price'];
								}
								?>
								<div class="price-main js-unit-price"><?php echo wc_price($start_price); ?></div>
								<div class="unit-composition__buttons">
									<div class="btn-like btn-card btn-card--favorites <?php if(in_array($child_product->get_id(), get_user_favorites())){ ?>active<?php } ?>">
										<?php the_favorites_button($child_product->get_id()); ?>
									</div>
									<a href="#" class="btn-card btn-card--basket js-unit-add-cart">
										<svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_2515_922)"><path d="M21.9994 21.2866L21.1815 6.87822C21.1614 6.52289 20.8677 6.24578 20.512 6.24578H17.6635V3.8899C17.6635 1.74767 15.9209 0.00500488 13.7786 0.00500488C11.6364 0.00500488 9.89375 1.74767 9.89375 3.8899V6.24578H7.18969C6.83392 6.24578 6.54027 6.52289 6.52016 6.87822L5.70224 21.2866C5.6807 21.6728 5.98811 21.995 6.37177 21.995H21.3299C21.7147 21.995 22.0209 21.6711 21.9994 21.2866ZM11.2346 3.8899C11.2346 2.48692 12.3761 1.34586 13.7786 1.34586C15.1812 1.34586 16.3227 2.48692 16.3227 3.8899V6.24578H11.2346V3.8899ZM7.08108 20.6541L7.82302 7.58664H9.89375V9.99257C9.89375 10.3626 10.1941 10.663 10.5642 10.663C10.9347 10.663 11.2346 10.3626 11.2346 9.99257V7.58664H16.3227V9.99257C16.3227 10.3626 16.6226 10.663 16.9931 10.663C17.3632 10.663 17.6635 10.3626 17.6635 9.99257V7.58664H19.8786L20.6201 20.6541H7.08108Z" fill="#3F5E29" /><path d="M4.21466 10.3296H0.670427C0.300172 10.3296 0 10.6298 0 11C0 11.3703 0.300172 11.6704 0.670427 11.6704H4.21466C4.58491 11.6704 4.88509 11.3703 4.88509 11C4.88509 10.6298 4.58491 10.3296 4.21466 10.3296Z" fill="#3F5E29" /><path d="M4.21533 13.6557H2.04543C1.67517 13.6557 1.375 13.9558 1.375 14.3261C1.375 14.6964 1.67517 14.9965 2.04543 14.9965H4.21533C4.58558 14.9965 4.88576 14.6964 4.88576 14.3261C4.88576 13.9558 4.58558 13.6557 4.21533 13.6557Z" fill="#3F5E29" /><path d="M4.21466 16.9818H0.670427C0.300172 16.9818 0 17.2819 0 17.6522C0 18.0224 0.300172 18.3226 0.670427 18.3226H4.21466C4.58491 18.3226 4.88509 18.0224 4.88509 17.6522C4.88509 17.2819 4.58491 16.9818 4.21466 16.9818Z" fill="#3F5E29" /></g><defs><clipPath id="clip0_2515_922"><rect width="22" height="22" fill="white" /></clipPath></defs></svg>
									</a>
								</div>
							</div>
						</div>
						<?php } ?>
						<div class="card-button">
							<a href="#" class="btn-page add-composition-btn">купить всю композицию</a>
						</div>
					</div>

					<?php else : ?>
					<!-- STANDARD SINGLE PRODUCT LAYOUT -->
					<div class="col-lg-5">
						<div class="card-image-wrap">
							<?php 
							$image_id = $product->get_image_id();
							if ( $image_id ) {
								$image_url = wp_get_attachment_image_url( $image_id, 'full' );
								echo '<a href="' . esc_url( $image_url ) . '" class="image-card fancybox">';
								echo wp_get_attachment_image( $image_id, 'full' );
								echo '</a>';
							} else {
								echo '<div class="image-card">' . sprintf( '<img src="%s" alt="%s" />', esc_url( wc_placeholder_img_src( 'woocommerce_single' ) ), esc_html__( 'Awaiting product image', 'woocommerce' ) ) . '</div>';
							}
							?>
							<div class="card-action">							
								<?php $result = in_array(get_the_ID(), get_user_favorites()); ?>
                  				<div class="btn-like btn-card btn-card--favorites <?php if($result){ ?>active<?php } ?>"><?php the_favorites_button(); ?></div>
							</div>
							<?php if ( is_user_logged_in() && ! empty( get_field( '3d_model' ) ) ) { ?>
								<a href="<?php echo esc_url( get_field( '3d_model' ) ); ?>" class="btn-light" target="_blank" rel="noopener noreferrer">
									скачать 3Д модель
									<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5 17.5H15" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /><path d="M10 2.5L10 14.1667" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /><path d="M14.1673 10L10.0007 14.1667L5.83398 10" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
								</a>
							<?php } ?>
						</div>
					</div>
					<div class="col-lg-4">
						<?php if ( $product->is_type( 'variable' ) ) : ?>
							<?php
							$default_variation_sku = isset( $variation_options_data[ $default_variation_value ]['sku'] ) ? (string) $variation_options_data[ $default_variation_value ]['sku'] : '';
							$product_sku_fallback = (string) $product->get_sku();
							$default_sku_for_view = $default_variation_sku ? $default_variation_sku : $product_sku_fallback;
							?>
							<div class="articul-card"<?php echo $default_sku_for_view ? '' : ' style="display:none;"'; ?>>Арт.: <span class="js-product-sku"><?php echo esc_html( $default_sku_for_view ); ?></span></div>
						<?php elseif ( $product->get_sku() ) : ?>
							<div class="articul-card">Арт.: <?php echo esc_html( $product->get_sku() ); ?></div>
						<?php endif; ?>
						<h1 class="title-card"><?php echo esc_html( $product->get_name() ); ?></h1>
						<div class="text-card">
							<?php echo apply_filters( 'woocommerce_short_description', $product->get_short_description() ); ?>
						</div>
						<div class="properties">
							<div class="row">
								<?php if ( $product->is_type( 'variable' ) && ! empty( $variation_attributes_data ) ) : ?>
								<div class="col-7">									
										<?php foreach ( $variation_attributes_data as $attribute_index => $attribute_data ) : ?>
											<div class="properties__item">
												<div class="properties__title"><?php echo esc_html( $attribute_data['label'] ); ?>:</div>
												<div class="properties__values">
													<?php
													$is_main_attribute = $attribute_data['name'] === $variation_attribute_name;
													$attribute_values_for_output = $is_main_attribute ? $variation_attribute_values : $attribute_data['values'];
													foreach ( $attribute_values_for_output as $value_index => $attribute_value ) :
													?>
														<?php
														$attribute_term = get_term_by( 'slug', $attribute_value, $attribute_data['name'] );
														$attribute_text = $attribute_term ? $attribute_term->name : $attribute_value;
														$is_active_value = $is_main_attribute && ( $attribute_value === $default_variation_value || ( ! $default_variation_value && 0 === $value_index ) );
														?>
														<div class="property__value<?php echo $is_active_value ? ' active' : ''; ?>"<?php echo $is_main_attribute ? ' data-variation-option="' . esc_attr( $attribute_value ) . '"' : ''; ?>><?php echo esc_html( $attribute_text ); ?></div>
													<?php endforeach; ?>
												</div>
											</div>
										<?php endforeach; ?>											
								</div>
								<?php endif; ?>
								<div class="col-5">
									<?php if ( $product->is_type( 'variable' ) ) : ?>
										<div class="properties__item property-diameter" style="display: none;">
											<div class="properties__title">Диаметр:</div>
											<div class="properties__text"></div>
										</div>
										<div class="properties__item property-shape" style="display: none;">
											<div class="properties__title">Форма:</div>
											<div class="properties__text"></div>
										</div>
										<div class="properties__item property-literage" style="display: none;">
											<div class="properties__title">Литраж:</div>
											<div class="properties__text"></div>
										</div>
									<?php else : 
										$dimensions = $product->get_dimensions( false );
										if ( ! empty( array_filter( $dimensions ) ) ) {
											if ( ! empty( $dimensions['length'] ) ) : ?>
												<div class="properties__item">
													<div class="properties__title">Габариты (Длина):</div>
													<div class="properties__text"><?php echo esc_html( $dimensions['length'] ); ?> см</div>
												</div>
											<?php endif;
											if ( ! empty( $dimensions['width'] ) ) : ?>
												<div class="properties__item">
													<div class="properties__title">Габариты (Ширина):</div>
													<div class="properties__text"><?php echo esc_html( $dimensions['width'] ); ?> см</div>
												</div>
											<?php endif;
											if ( ! empty( $dimensions['height'] ) ) : ?>
												<div class="properties__item">
													<div class="properties__title">Габариты (Высота):</div>
													<div class="properties__text"><?php echo esc_html( $dimensions['height'] ); ?> см</div>
												</div>
											<?php endif;
										} elseif(!empty(get_field('kratkaya_harakteristika'))){
											foreach (get_field('kratkaya_harakteristika') as $v) { ?>								
											<div class="properties__item">
												<div class="properties__title"><?php echo $v['zagolovok']; ?></div>
												<div class="properties__text"><?php echo $v['znachenie']; ?></div>
											</div>
										<?php }
										} ?>
									<?php endif; ?>									
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-3">
						<div class="card-info">
							<div class="card-info__top">
								<div class="availability"><?php echo $product->is_in_stock() ? 'В наличии' : 'Нет в наличии'; ?></div>
								<?php if ( $product->managing_stock() && $product->get_stock_quantity() !== null && $product->get_stock_quantity() !== '' ) : ?>
									<div class="card-info__amount"><?php echo esc_html( $product->get_stock_quantity() ); ?> шт</div>
								<?php else : ?>
									<div class="card-info__amount" style="display: none;"></div>
								<?php endif; ?>
							</div>
							<div class="card-info__prices">
								<?php if ( $product->is_type( 'variable' ) ) : ?>
									<?php
									$default_display_regular = isset( $variation_options_data[ $default_variation_value ] ) ? (float) $variation_options_data[ $default_variation_value ]['display_regular'] : 0;
									$default_display_price   = isset( $variation_options_data[ $default_variation_value ] ) ? (float) $variation_options_data[ $default_variation_value ]['display_price'] : 0;
									$has_default_sale        = $default_display_regular > 0 && $default_display_price > 0 && $default_display_price < $default_display_regular;
									$default_main_price      = $default_display_price > 0 ? $default_display_price : (float) $product->get_variation_price( 'min' );
									?>
									<div class="price-old"<?php echo $has_default_sale ? '' : ' style="display:none;"'; ?>><?php echo $has_default_sale ? wc_price( $default_display_regular ) : ''; ?></div>
									<div class="price-main"><?php echo wc_price( $default_main_price ); ?></div>
								<?php else : ?>
									<?php if ( $product->is_on_sale() ) : ?>
										<div class="price-old"><?php echo wc_price( wc_get_price_to_display( $product, array( 'price' => $product->get_regular_price() ) ) ); ?></div>
										<div class="price-main"><?php echo wc_price( wc_get_price_to_display( $product ) ); ?></div>
									<?php else : ?>
										<div class="price-main"><?php echo wc_price( wc_get_price_to_display( $product ) ); ?></div>
									<?php endif; ?>
								<?php endif; ?>
							</div>
							<?php if ( $product->is_type( 'variable' ) ) : ?>
							<form class="cart custom-variation-cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
								<div class="quantity">
									<div class="quantity-button quantity-down"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 12H18" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg></div>
									<input type="number" name="quantity" min="1" step="1" value="1">
									<div class="quantity-button quantity-up"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 12H18" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /><path d="M12 18L12 6" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg></div>
								</div>
								<input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>">
								<input type="hidden" name="product_id" value="<?php echo esc_attr( $product->get_id() ); ?>">
								<input type="hidden" name="variation_id" value="<?php echo esc_attr( isset( $variation_options_data[ $default_variation_value ] ) ? $variation_options_data[ $default_variation_value ]['variation_id'] : 0 ); ?>">
								<input type="hidden" name="<?php echo esc_attr( 'attribute_' . $variation_attribute_name ); ?>" value="<?php echo esc_attr( $default_variation_value ); ?>">
								<div class="card-info__buttons">
									<a href="#order-1click" class="btn-page btn-page--main fancybox btn-1click" data-title="<?php echo esc_attr( $product->get_name() ); ?><?php echo $default_variation_value ? ' ' . esc_attr( $default_variation_value ) : ''; ?>">купить в 1 клик</a>
									<button type="submit" class="btn-page">в корзину</button>
								</div>
							</form>
							<?php else : ?>
							<form class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
								<div class="quantity">
									<div class="quantity-button quantity-down"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 12H18" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg></div>
									<input type="number" name="quantity" min="1" step="1" value="1">
									<div class="quantity-button quantity-up"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 12H18" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /><path d="M12 18L12 6" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg></div>
								</div>
								<div class="card-info__buttons">
									<a href="#order-1click" class="btn-page btn-page--main fancybox" data-title="<?php the_title(); ?>">купить в 1 клик</a>
									<button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="btn-page">в корзину</button>
								</div>
							</form>
							<?php endif; ?>
						</div>
					</div>
					<?php endif; ?>
				</div>
				<div class="row">
					<div class="col-xl-5">
						<ul class="tabs tabs--card">
							<li class="active"><a href="#tab_card1">описание</a></li>
							<li><a href="#tab_card2">доставка и оплата</a></li>
						</ul>
						<div class="tab-container">
							<div class="tab-pane" id="tab_card1">
								<?php echo apply_filters( 'the_content', $product->get_description() ); ?>
								<?php if(!empty(get_field('harakteristiki'))){ ?>
									<div class="features">
										<?php foreach (get_field('harakteristiki') as $v_char) { ?>									
											<div class="features__item">
												<div class="features__title">
													<?php if(!empty($v_char['ikonka_zagolovka'])){ ?>
														<img src="<?php echo $v_char['ikonka_zagolovka']['sizes']['thumbnail']; ?>" class="icon-img" alt="alt">
													<?php } ?>
													 <?php echo $v_char['zagolovok']; ?>
												</div>
												<?php if(!empty($v_char['znacheniya'])){ ?>
													<div class="features__info">
														<?php foreach ($v_char['znacheniya'] as $v_z) { ?>
															<div class="features__value">
																<?php if(!empty($v_z['ikonka_znacheniya'])){ ?>
																	<img src="<?php echo $v_z['ikonka_znacheniya']['sizes']['thumbnail']; ?>" class="icon-img" alt="alt">
																<?php } ?>
																<?php echo $v_z['znachenie']; ?>
															</div>
														<?php } ?>														
													</div>
												<?php } ?>
											</div>
										<?php } ?>										
									</div>
								<?php }	?>
									<?php
									$calendar_data = get_field( 'kalendar_dekorativnosti' );
									$calendar_rows = ( ! empty( $calendar_data['kalendar'] ) && is_array( $calendar_data['kalendar'] ) ) ? $calendar_data['kalendar'] : array();
									$calendar_title = ! empty( $calendar_data['zagolovok'] ) ? $calendar_data['zagolovok'] : 'Календарь декоративности';
									$calendar_image = get_field( 'izobrazhenie_tabliczy' );
									?>
									<?php if ( ! empty( $calendar_rows ) || ! empty( $calendar_image ) ) : ?>
										<div class="calendar">
											<?php if ( ! empty( $calendar_rows ) ) : ?>
												<div class="calendar__title"><?php echo esc_html( $calendar_title ); ?></div>
												<div class="calendar__table">
													<table>
														<thead>
															<tr>
																<th></th>
																<th>Янв</th>
																<th>Февр</th>
																<th>Март</th>
																<th>Апр</th>
																<th>Май</th>
																<th>Июнь</th>
																<th>Июль</th>
																<th>Авг</th>
																<th>Сент</th>
																<th>Окт</th>
																<th>Нояб</th>
																<th>Дек</th>
															</tr>
														</thead>
														<tbody>
															<?php foreach ( $calendar_rows as $calendar_row ) : ?>
																<?php
																$row_label = isset( $calendar_row['lejbl'] ) ? (string) $calendar_row['lejbl'] : '';
																$months = ( ! empty( $calendar_row['mesyaczy'] ) && is_array( $calendar_row['mesyaczy'] ) ) ? $calendar_row['mesyaczy'] : array();
																?>
																<tr>
																	<td><?php echo esc_html( $row_label ); ?></td>
																	<?php for ( $month_index = 0; $month_index < 12; $month_index++ ) : ?>
																		<?php
																		$month_color_raw = isset( $months[ $month_index ]['czvet'] ) ? (string) $months[ $month_index ]['czvet'] : '';
																		$month_color = strtolower( trim( $month_color_raw ) );
																		$inline_style = '';
																		if ( $month_color ) {
																			$inline_style = 'background: ' . $month_color . ';';
																			if ( '#ffffff' === $month_color ) {
																				$inline_style .= ' border: 1px #7a9c52 solid;';
																			}
																		}
																		?>
																		<td>
																			<div class="calendar__value"<?php echo $inline_style ? ' style="' . esc_attr( $inline_style ) . '"' : ''; ?>></div>
																		</td>
																	<?php endfor; ?>
																</tr>
															<?php endforeach; ?>
														</tbody>
													</table>
												</div>
											<?php endif; ?>
											<?php if ( ! empty( $calendar_image ) ) : ?>
												<?php
												$calendar_image_url = '';
												if ( is_array( $calendar_image ) && ! empty( $calendar_image['sizes']['large'] ) ) {
													$calendar_image_url = $calendar_image['sizes']['large'];
												} elseif ( is_string( $calendar_image ) ) {
													$calendar_image_url = $calendar_image;
												}
												?>
												<?php if ( $calendar_image_url ) : ?>
													<img src="<?php echo esc_url( $calendar_image_url ); ?>" alt="alt">
												<?php endif; ?>
											<?php endif; ?>
										</div>
									<?php endif; ?>
								
								<div class="button-row">
									<a href="#" class="btn-page btn-page--card fv-get-presentation" product_id="<?php the_ID(); ?>">
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 12L12 18" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /><path d="M15 16L12 18L9 16" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /><path d="M18 21H6C5.44772 21 5 20.5523 5 20L5 4C5 3.44772 5.44772 3 6 3L13.5631 3C13.8416 3 14.1076 3.11619 14.2968 3.32059L18.7338 8.11246C18.9049 8.29731 19 8.53995 19 8.79187L19 20C19 20.5523 18.5523 21 18 21Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /><path d="M19 9L14 9C13.4477 9 13 8.55228 13 8L13 3" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg>
										Сформировать PDF
									</a>
									<?php if(!empty(get_field('ssylka_na_rutub_videoobzor'))){ ?>
										<a href="https://rutube.ru/play/embed/<?php echo get_field('ssylka_na_rutub_videoobzor'); ?>" class="btn-page btn-page--card item-team__play fancybox-rutube">Видеообзор <i class="fas fa-play"></i></a>
									<?php } ?>
								</div>
							</div>
							<div class="tab-pane" id="tab_card2">
								<?php echo get_field('dostavka_i_oplata', 'option'); ?>
							</div>
						</div>
					</div>
					<?php if ( $has_compositions ) : ?>
					<div class="col-xl-7">
						<div class="head-composition">
							<h2 class="title-card">готовые композиции:</h2>
							<a href="/product-category/gotovye-kompozitsii/" class="btn-light">Все композиции</a>
						</div>
						<div class="slider-composition">
							<?php
							if ( $composition_query->have_posts() ) :
								while ( $composition_query->have_posts() ) : $composition_query->the_post();
									$comp_img_url = get_the_post_thumbnail_url( get_the_ID(), 'large' );
									if ( ! $comp_img_url ) {
										$comp_img_url = wc_placeholder_img_src();
									}
									?>
									<div>
										<a href="<?php the_permalink(); ?>" class="item-composition">
											<img src="<?php echo esc_url( $comp_img_url ); ?>" alt="<?php the_title_attribute(); ?>">
										</a>
									</div>
									<?php
								endwhile;
								wp_reset_postdata();
							endif;
							?>
						</div>
					</div>
					<?php endif; ?>
				</div>
			</div>
		</section>
</div>
<?php if ( $product->is_type( 'variable' ) && $variation_attribute_name && ! empty( $variation_options_data ) ) : ?>
<script>
jQuery(function($){
	var $productNode = $('#product-<?php echo esc_js( get_the_ID() ); ?>');
	var variationOptions = <?php echo wp_json_encode( $variation_options_data ); ?>;
	var attributeFieldName = '<?php echo esc_js( 'attribute_' . $variation_attribute_name ); ?>';
	var $form = $productNode.find('form.custom-variation-cart');
	var $customOptions = $productNode.find('.property__value[data-variation-option]');
	var $variationIdInput = $form.find('input[name="variation_id"]');
	var $attributeInput = $form.find('input[name="' + attributeFieldName + '"]');
	var $priceOld = $productNode.find('.card-info__prices .price-old');
	var $priceMain = $productNode.find('.card-info__prices .price-main');
	var $rowDiameter = $productNode.find('.property-diameter');
	var $valDiameter = $rowDiameter.find('.properties__text');
	var $rowShape = $productNode.find('.property-shape');
	var $valShape = $rowShape.find('.properties__text');
	var $rowLiterage = $productNode.find('.property-literage');
	var $valLiterage = $rowLiterage.find('.properties__text');
	var $btn1click = $productNode.find('.btn-1click');
	var $availability = $productNode.find('.card-info__top .availability');
	var $amount = $productNode.find('.card-info__top .card-info__amount');
	var $skuBlock = $productNode.find('.articul-card');
	var $skuValue = $skuBlock.find('.js-product-sku');
	var productTitle = '<?php echo esc_js( $product->get_name() ); ?>';
	var productSkuFallback = '<?php echo esc_js( (string) $product->get_sku() ); ?>';
	var defaultValue = '<?php echo esc_js( $default_variation_value ); ?>';

	function setActiveOption(selectedValue) {
		$customOptions.removeClass('active');
		$customOptions.filter('[data-variation-option="' + selectedValue + '"]').addClass('active');
	}

	function updateVariation(selectedValue) {
		if (!selectedValue || !variationOptions[selectedValue]) {
			return;
		}

		var variationData = variationOptions[selectedValue];
		var regularPrice = parseFloat(variationData.display_regular);
		var salePrice = parseFloat(variationData.display_price);

		$variationIdInput.val(variationData.variation_id || 0);
		$attributeInput.val(selectedValue);
		setActiveOption(selectedValue);

		if (!isNaN(regularPrice) && !isNaN(salePrice) && regularPrice > 0 && salePrice < regularPrice) {
			$priceOld.html(variationData.regular_price_html).show();
			$priceMain.html(variationData.price_html);
		} else if (!isNaN(salePrice) && salePrice > 0) {
			$priceOld.hide().empty();
			$priceMain.html(variationData.price_html);
		}

		if (variationData.diameter) {
			$valDiameter.text(variationData.diameter);
			$rowDiameter.show();
		} else {
			$rowDiameter.hide();
		}
		if (variationData.shape) {
			$valShape.text(variationData.shape);
			$rowShape.show();
		} else {
			$rowShape.hide();
		}
		if (variationData.literage) {
			$valLiterage.text(variationData.literage);
			$rowLiterage.show();
		} else {
			$rowLiterage.hide();
		}

		if (variationData.stock_quantity !== '' && variationData.stock_quantity !== null) {
			$amount.text(variationData.stock_quantity + ' шт').show();
		} else {
			$amount.hide().empty();
		}

		if (variationData.is_in_stock) {
			$availability.text('В наличии');
		} else {
			$availability.text('Нет в наличии');
		}

		var variationSku = variationData.sku ? String(variationData.sku) : '';
		var skuToShow = variationSku || productSkuFallback;
		if (skuToShow) {
			$skuValue.text(skuToShow);
			$skuBlock.show();
		} else {
			$skuBlock.hide();
		}

		$btn1click.attr('data-title', productTitle + ' ' + selectedValue);
	}

	$customOptions.on('click', function() {
		updateVariation($(this).data('variation-option'));
	});

	$form.on('submit', function(event) {
		if (!$variationIdInput.val() || parseInt($variationIdInput.val(), 10) <= 0) {
			event.preventDefault();
		}
	});

	if (defaultValue && variationOptions[defaultValue]) {
		updateVariation(defaultValue);
	}
});
</script>
<?php endif; ?>
<?php do_action( 'woocommerce_after_single_product' ); ?>
