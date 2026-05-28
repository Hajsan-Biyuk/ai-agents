<?php
/**
 * Universal product line API for Simple Basket.
 *
 * @package simple-basket
 */

if ( ! function_exists( 'simple_basket_get_integration_config' ) ) {
	/**
	 * Integration config (override via filter simple_basket_integration_config).
	 */
	function simple_basket_get_integration_config() {
		$defaults = array(
			'post_types'              => array(),
			'variation_mode'          => 'none',
			'acf_repeater'            => 'variacii',
			'acf_value'               => 'znachenie',
			'acf_unit'                => 'edinica',
			'acf_price'               => 'cena',
			'acf_default'             => 'po_umolchaniyu',
			'taxonomy'                => '',
			'fallback_price_meta'     => '',
			'title_suffix_separator'  => ' — ',
			'include_post_type_label' => false,
		);

		return apply_filters( 'simple_basket_integration_config', $defaults );
	}
}

if ( ! function_exists( 'simple_basket_parse_number' ) ) {
	function simple_basket_parse_number( $text ) {
		if ( ! is_string( $text ) && ! is_numeric( $text ) ) {
		 return 0.0;
		}

		$normalized = preg_replace( '/[^\d.,]/', '', str_replace( ' ', '', (string) $text ) );
		$normalized = str_replace( ',', '.', $normalized );

		if ( $normalized === '' ) {
			return 0.0;
		}

		return (float) $normalized;
	}
}

if ( ! function_exists( 'simple_basket_is_supported_post_type' ) ) {
	function simple_basket_is_supported_post_type( $post_id ) {
		$post_id = (int) $post_id;
		$config  = simple_basket_get_integration_config();
		$types   = isset( $config['post_types'] ) ? (array) $config['post_types'] : array();

		if ( empty( $types ) ) {
			return true;
		}

		return in_array( get_post_type( $post_id ), $types, true );
	}
}

if ( ! function_exists( 'simple_basket_get_product_category_name' ) ) {
	function simple_basket_get_product_category_name( $post_id ) {
		$post_id  = (int) $post_id;
		$config   = simple_basket_get_integration_config();
		$taxonomy = isset( $config['taxonomy'] ) ? (string) $config['taxonomy'] : '';

		if ( $taxonomy !== '' ) {
			$terms = get_the_terms( $post_id, $taxonomy );

			if ( $terms && ! is_wp_error( $terms ) && ! empty( $terms[0]->name ) ) {
				return $terms[0]->name;
			}
		}

		$taxonomies = get_object_taxonomies( get_post_type( $post_id ) );

		foreach ( $taxonomies as $tax ) {
			if ( false !== strpos( $tax, 'tag' ) ) {
				continue;
			}

			$terms = get_the_terms( $post_id, $tax );

			if ( $terms && ! is_wp_error( $terms ) && ! empty( $terms[0]->name ) ) {
				return $terms[0]->name;
			}
		}

		return '';
	}
}

if ( ! function_exists( 'simple_basket_normalize_product_line' ) ) {
	/**
	 * Normalize line contract for cart operations.
	 */
	function simple_basket_normalize_product_line( $line, $post_id, $variation_index, $quantity ) {
		if ( ! is_array( $line ) ) {
			return null;
		}

		$post_id         = (int) $post_id;
		$variation_index = (int) $variation_index;
		$quantity        = max( 1, (int) $quantity );

		$line['post_id']   = isset( $line['post_id'] ) ? (int) $line['post_id'] : $post_id;
		$line['variation'] = isset( $line['variation'] ) ? (int) $line['variation'] : $variation_index;
		$line['quantity']  = isset( $line['quantity'] ) ? max( 1, (int) $line['quantity'] ) : $quantity;
		$line['title']     = isset( $line['title'] ) ? trim( (string) $line['title'] ) : '';
		$line['price']     = isset( $line['price'] ) ? (string) $line['price'] : '';
		$line['volume']    = isset( $line['volume'] ) ? trim( (string) $line['volume'] ) : '';
		$line['category']  = isset( $line['category'] ) ? (string) $line['category'] : '';

		if ( empty( $line['cart_key'] ) ) {
			$line['cart_key'] = SimpleBasketOrder::makeCartKey( $line['post_id'], $line['variation'] );
		}

		if ( $line['title'] === '' ) {
			return null;
		}

		return $line;
	}
}

if ( ! function_exists( 'simple_basket_build_simple_meta_line' ) ) {
	/**
	 * Legacy line: single price meta + optional post type label in title.
	 */
	function simple_basket_build_simple_meta_line( $post_id, $variation_index = 0, $quantity = 1 ) {
		$post_id = (int) $post_id;
		$product = get_post( $post_id );

		if ( ! $product ) {
			return null;
		}

		$config = simple_basket_get_integration_config();
		$title  = get_the_title( $post_id );

		if ( ! empty( $config['include_post_type_label'] ) ) {
			$post_type_object = get_post_type_object( $product->post_type );
			$post_type_title  = ( $post_type_object && isset( $post_type_object->labels->singular_name ) ) ? $post_type_object->labels->singular_name : $product->post_type;
			$title            = $post_type_title . ': ' . $title;
		}

		$price_meta = get_option( 'simple_basket_catalog_price', 'Price' );
		$price      = simple_basket_custom_fields( $post_id, $price_meta );

		if ( $price === '' && ! empty( $config['fallback_price_meta'] ) ) {
			$stored = get_post_meta( $post_id, $config['fallback_price_meta'], true );

			if ( $stored !== '' && $stored !== false ) {
				$price = (string) $stored;
			}
		}

		return simple_basket_normalize_product_line(
			array(
				'post_id'   => $post_id,
				'variation' => (int) $variation_index,
				'title'     => $title,
				'price'     => $price,
				'volume'    => '',
				'category'  => simple_basket_get_product_category_name( $post_id ),
			),
			$post_id,
			$variation_index,
			$quantity
		);
	}
}

if ( ! function_exists( 'simple_basket_get_product_line' ) ) {
	/**
	 * Resolve cart line via integrations + fallback.
	 *
	 * Filter: simple_basket_product_line (null, post_id, variation_index, quantity)
	 */
	function simple_basket_get_product_line( $post_id = 0, $variation_index = 0, $quantity = 1 ) {
		$post_id = (int) $post_id;

		if ( ! $post_id || ! get_post( $post_id ) ) {
			return null;
		}

		if ( ! simple_basket_is_supported_post_type( $post_id ) ) {
			return null;
		}

		$variation_index = (int) $variation_index;
		$quantity        = max( 1, (int) $quantity );

		$line = apply_filters( 'simple_basket_product_line', null, $post_id, $variation_index, $quantity );

		if ( ! is_array( $line ) || empty( $line['title'] ) ) {
			$line = simple_basket_build_simple_meta_line( $post_id, $variation_index, $quantity );
		}

		return simple_basket_normalize_product_line( $line, $post_id, $variation_index, $quantity );
	}
}

if ( ! function_exists( 'simple_basket_get_min_variation_index' ) ) {
	/**
	 * Minimum variation index (filter + ACF fallback).
	 */
	function simple_basket_get_min_variation_index( $post_id = 0 ) {
		$post_id = (int) $post_id;

		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}

		$filtered = apply_filters( 'simple_basket_min_variation_index', null, $post_id );

		if ( $filtered !== null ) {
			return max( 0, (int) $filtered );
		}

		if ( function_exists( 'simple_basket_acf_get_min_variation_index' ) ) {
			return simple_basket_acf_get_min_variation_index( $post_id );
		}

		return 0;
	}
}

if ( ! function_exists( 'simple_basket_get_request_cart_key' ) ) {
	function simple_basket_get_request_cart_key( $request ) {
		if ( ! empty( $request['cart_key'] ) ) {
			return sanitize_text_field( wp_unslash( $request['cart_key'] ) );
		}

		if ( ! isset( $request['id'] ) ) {
			return '';
		}

		$id        = (int) $request['id'];
		$variation = isset( $request['variation'] ) ? (int) $request['variation'] : 0;
		$line      = simple_basket_get_product_line( $id, $variation, 1 );

		if ( is_array( $line ) && ! empty( $line['cart_key'] ) ) {
			return $line['cart_key'];
		}

		return SimpleBasketOrder::makeCartKey( $id, $variation );
	}
}

if ( ! function_exists( 'simple_basket_add_product_to_cart' ) ) {
	/**
	 * Add product line to session cart.
	 */
	function simple_basket_add_product_to_cart( $post_id, $variation_index = 0, $quantity = 1 ) {
		$line = simple_basket_get_product_line( $post_id, $variation_index, $quantity );

		if ( ! is_array( $line ) ) {
			return false;
		}

		$config   = simple_basket_get_integration_config();
		$services = array();

		if ( empty( $config['variation_mode'] ) || $config['variation_mode'] === 'none' ) {
			$services = simple_basket_get_product_services( $post_id );
		}

		$services = apply_filters( 'simple_basket_product_services', $services, $post_id, $line );

		$basket = SimpleBasketOrder::create();

		$basket->add(
			$line['post_id'],
			$line['title'],
			$line['price'],
			$line['category'],
			$services,
			$line['quantity'],
			$line['variation'],
			$line['volume']
		);

		return true;
	}
}
