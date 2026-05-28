<?php
/**
 * CPT product и таксономия product_cat.
 *
 * @package maintheme
 */

if ( ! function_exists( 'maintheme_register_product_post_type' ) ) {
	/**
	 * Регистрация типа записи «Товар» и категорий.
	 */
	function maintheme_register_product_post_type() {
		register_taxonomy(
			'product_cat',
			array( 'product' ),
			array(
				'label'             => 'Категории товаров',
				'labels'            => array(
					'name'              => 'Категории товаров',
					'singular_name'     => 'Категория товаров',
					'search_items'      => 'Искать категорию',
					'all_items'         => 'Все категории',
					'parent_item'       => 'Родительская категория',
					'parent_item_colon' => 'Родительская категория:',
					'edit_item'         => 'Редактировать категорию',
					'update_item'       => 'Обновить категорию',
					'add_new_item'      => 'Добавить категорию',
					'new_item_name'     => 'Новая категория',
					'menu_name'         => 'Категории',
				),
				'public'            => true,
				'hierarchical'      => true,
				'show_ui'           => true,
				'show_admin_column' => true,
				'show_in_nav_menus' => true,
				'show_tagcloud'     => false,
				'show_in_rest'      => false,
				'rewrite'           => array(
					'slug'         => 'product-category',
					'hierarchical' => true,
					'with_front'   => false,
				),
			)
		);

		register_post_type(
			'product',
			array(
				'label'               => 'Товар',
				'labels'              => array(
					'name'          => 'Товары',
					'singular_name' => 'Товар',
					'menu_name'     => 'Товары',
					'all_items'     => 'Все товары',
					'add_new'       => 'Добавить товар',
					'add_new_item'  => 'Добавить новый товар',
					'edit_item'     => 'Редактировать товар',
					'new_item'      => 'Новый товар',
					'view_item'     => 'Просмотреть товар',
					'search_items'  => 'Искать товары',
					'not_found'     => 'Товары не найдены',
				),
				'public'              => true,
				'publicly_queryable'  => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_rest'        => false,
				'exclude_from_search' => false,
				'capability_type'     => 'post',
				'map_meta_cap'        => true,
				'menu_icon'           => 'dashicons-cart',
				'hierarchical'        => false,
				'has_archive'         => false,
				'query_var'           => true,
				'rewrite'             => array(
					'slug'       => 'product',
					'with_front' => false,
					'feeds'      => false,
				),
				'supports'            => array( 'title', 'editor', 'thumbnail' ),
				'taxonomies'          => array( 'product_cat' ),
			)
		);
	}
}
add_action( 'init', 'maintheme_register_product_post_type' );

if ( ! function_exists( 'maintheme_product_rewrite_flush' ) ) {
	/**
	 * Сброс правил ЧПУ при активации темы.
	 */
	function maintheme_product_rewrite_flush() {
		maintheme_register_product_post_type();
		flush_rewrite_rules();
	}
}
add_action( 'after_switch_theme', 'maintheme_product_rewrite_flush' );
