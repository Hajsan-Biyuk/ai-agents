<?php
/**
 * Agro-As defaults for Simple Basket (ACF variacii on CPT product).
 *
 * @package simple-basket
 */

if ( ! function_exists( 'simple_basket_integration_agro_as_config' ) ) {
	function simple_basket_integration_agro_as_config( $config ) {
		if ( ! empty( $config['post_types'] ) ) {
			return $config;
		}

		$theme_dir = get_template_directory();

		if ( ! file_exists( $theme_dir . '/inc/cpt-product.php' ) ) {
			return $config;
		}

		return array_merge(
			$config,
			array(
				'post_types'             => array( 'product' ),
				'variation_mode'         => 'acf_repeater',
				'acf_repeater'           => 'variacii',
				'acf_value'              => 'znachenie',
				'acf_unit'               => 'edinica',
				'acf_price'              => 'cena',
				'acf_default'            => 'po_umolchaniyu',
				'taxonomy'               => 'product_cat',
				'fallback_price_meta'    => 'maintheme_min_price',
				'title_suffix_separator' => ' — ',
				'include_post_type_label' => false,
			)
		);
	}
}

add_filter( 'simple_basket_integration_config', 'simple_basket_integration_agro_as_config', 5 );
