<?php
/**
 * ACF repeater variations integration.
 *
 * @package simple-basket
 */

if ( ! function_exists( 'simple_basket_acf_get_variations' ) ) {
	function simple_basket_acf_get_variations( $post_id, $config = null ) {
		if ( ! function_exists( 'get_field' ) ) {
			return array();
		}

		if ( ! is_array( $config ) ) {
			$config = simple_basket_get_integration_config();
		}

		$field = isset( $config['acf_repeater'] ) ? (string) $config['acf_repeater'] : 'variacii';
		$rows  = get_field( $field, (int) $post_id );

		return is_array( $rows ) ? $rows : array();
	}
}

if ( ! function_exists( 'simple_basket_acf_format_volume' ) ) {
	function simple_basket_acf_format_volume( $item, $config = null ) {
		if ( ! is_array( $item ) ) {
			return '';
		}

		if ( ! is_array( $config ) ) {
			$config = simple_basket_get_integration_config();
		}

		$value_key = isset( $config['acf_value'] ) ? (string) $config['acf_value'] : 'znachenie';
		$unit_key  = isset( $config['acf_unit'] ) ? (string) $config['acf_unit'] : 'edinica';
		$value     = trim( (string) ( $item[ $value_key ] ?? '' ) );
		$unit      = trim( (string) ( $item[ $unit_key ] ?? '' ) );

		if ( $value === '' ) {
			return '';
		}

		return $unit !== '' ? $value . ' ' . $unit : $value;
	}
}

if ( ! function_exists( 'simple_basket_acf_get_min_variation_index' ) ) {
	function simple_basket_acf_get_min_variation_index( $post_id ) {
		$config     = simple_basket_get_integration_config();
		$variations = simple_basket_acf_get_variations( $post_id, $config );

		if ( empty( $variations ) || $config['variation_mode'] !== 'acf_repeater' ) {
			return 0;
		}

		$value_key = isset( $config['acf_value'] ) ? (string) $config['acf_value'] : 'znachenie';
		$min_index = 0;
		$min_num   = null;

		foreach ( $variations as $index => $item ) {
			if ( ! is_array( $item ) ) {
				continue;
			}

			$num = simple_basket_parse_number( $item[ $value_key ] ?? '' );

			if ( null === $min_num || $num < $min_num ) {
				$min_num   = $num;
				$min_index = (int) $index;
			}
		}

		return $min_index;
	}
}

if ( ! function_exists( 'simple_basket_acf_build_product_line' ) ) {
	function simple_basket_acf_build_product_line( $post_id, $variation_index, $quantity ) {
		$config = simple_basket_get_integration_config();

		if ( $config['variation_mode'] !== 'acf_repeater' ) {
		 return null;
		}

		if ( ! simple_basket_is_supported_post_type( $post_id ) ) {
			return null;
		}

		$variations = simple_basket_acf_get_variations( $post_id, $config );
		$title      = get_the_title( $post_id );
		$volume     = '';
		$price      = '';
		$price_key  = isset( $config['acf_price'] ) ? (string) $config['acf_price'] : 'cena';
		$separator  = isset( $config['title_suffix_separator'] ) ? (string) $config['title_suffix_separator'] : ' — ';

		if ( ! empty( $variations ) && isset( $variations[ $variation_index ] ) && is_array( $variations[ $variation_index ] ) ) {
			$item   = $variations[ $variation_index ];
			$volume = simple_basket_acf_format_volume( $item, $config );
			$price  = isset( $item[ $price_key ] ) ? trim( (string) $item[ $price_key ] ) : '';
		} elseif ( ! empty( $config['fallback_price_meta'] ) ) {
			$stored = get_post_meta( (int) $post_id, $config['fallback_price_meta'], true );

			if ( $stored !== '' && $stored !== false ) {
				$price = (string) $stored;
			}
		}

		$line_title = $title;

		if ( $volume !== '' ) {
			$line_title .= $separator . $volume;
		}

		return array(
			'post_id'   => (int) $post_id,
			'variation' => (int) $variation_index,
			'title'     => $line_title,
			'price'     => $price,
			'volume'    => $volume,
			'category'  => simple_basket_get_product_category_name( $post_id ),
		);
	}
}

if ( ! function_exists( 'simple_basket_integration_acf_product_line' ) ) {
	function simple_basket_integration_acf_product_line( $line, $post_id, $variation_index, $quantity ) {
		if ( is_array( $line ) && ! empty( $line['title'] ) ) {
			return $line;
		}

		return simple_basket_acf_build_product_line( $post_id, $variation_index, $quantity );
	}
}

add_filter( 'simple_basket_product_line', 'simple_basket_integration_acf_product_line', 10, 4 );
