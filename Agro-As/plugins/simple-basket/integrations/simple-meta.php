<?php
/**
 * Simple meta price integration defaults for legacy sites.
 *
 * @package simple-basket
 */

if ( ! function_exists( 'simple_basket_integration_simple_meta_config' ) ) {
	function simple_basket_integration_simple_meta_config( $config ) {
		if ( ! empty( $config['post_types'] ) ) {
			return $config;
		}

		$config['include_post_type_label'] = true;

		return $config;
	}
}

add_filter( 'simple_basket_integration_config', 'simple_basket_integration_simple_meta_config', 100 );
