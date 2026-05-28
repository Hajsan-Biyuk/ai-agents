<?php
/**
 * Agro As — functions and definitions
 *
 * @package maintheme
 */

require get_template_directory() . '/inc/helpers.php';
require get_template_directory() . '/inc/cpt-product.php';

$basket_plugin = dirname( get_template_directory() ) . '/plugins/simple-basket/simple-basket.php';

if ( file_exists( $basket_plugin ) && ! class_exists( 'SimpleBasketOrder' ) ) {
	require $basket_plugin;
}

if ( ! function_exists( 'maintheme_setup' ) ) :
	function maintheme_setup() {
		load_theme_textdomain( 'maintheme', get_template_directory() . '/languages' );

		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );

		register_nav_menus(
			array(
				'header-menu' => esc_html__( 'Главное меню', 'maintheme' ),
				'footer-menu' => esc_html__( 'Футер меню', 'maintheme' ),
			)
		);

		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'maintheme_setup' );

function maintheme_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'maintheme_content_width', 1200 );
}
add_action( 'after_setup_theme', 'maintheme_content_width', 0 );

/**
 * ACF Options и JSON — только после инициализации ACF.
 */
function maintheme_acf_init() {
	if ( ! function_exists( 'acf_add_options_page' ) ) {
		return;
	}

	acf_add_options_page(
		array(
			'page_title' => 'Основные настройки',
			'menu_title' => 'Настройки темы',
			'menu_slug'  => 'theme-general-settings',
			'capability' => 'edit_posts',
			'redirect'   => false,
		)
	);
}
add_action( 'acf/init', 'maintheme_acf_init' );

add_filter(
	'acf/settings/save_json',
	function () {
		return get_template_directory() . '/acf-json';
	}
);

add_filter(
	'acf/settings/load_json',
	function ( $paths ) {
		$paths[] = get_template_directory() . '/acf-json';
		return $paths;
	}
);

/**
 * SVG в медиабиблиотеке.
 */
function maintheme_mime_types( $mimes ) {
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
}
add_filter( 'upload_mimes', 'maintheme_mime_types' );

add_filter( 'wp_check_filetype_and_ext', 'maintheme_fix_svg_mime_type', 10, 5 );

function maintheme_fix_svg_mime_type( $data, $file, $filename, $mimes, $real_mime = '' ) {
	if ( version_compare( $GLOBALS['wp_version'], '5.1.0', '>=' ) ) {
		$is_svg = in_array( $real_mime, array( 'image/svg', 'image/svg+xml' ), true );
	} else {
		$is_svg = ( '.svg' === strtolower( substr( $filename, -4 ) ) );
	}

	if ( $is_svg ) {
		if ( current_user_can( 'manage_options' ) ) {
			$data['ext']  = 'svg';
			$data['type'] = 'image/svg+xml';
		} else {
			$data['ext']  = false;
			$data['type'] = false;
		}
	}

	return $data;
}

add_filter(
	'excerpt_length',
	function () {
		return 30;
	}
);

add_filter(
	'excerpt_more',
	function ( $more ) {
		return '...';
	}
);

/**
 * Внутренние страницы — body.wrapper__default (шапка как в статике).
 */
add_filter(
	'body_class',
	function ( $classes ) {
		if ( ! is_front_page() ) {
			$classes[] = 'wrapper__default';
		}

		return $classes;
	}
);

/**
 * Мин. цена товара в meta при сохранении ACF.
 */
add_action(
	'acf/save_post',
	function ( $post_id ) {
		maintheme_sync_product_min_price_meta( $post_id );
		maintheme_sync_product_stock_meta( $post_id );
	},
	20
);

/**
 * Каталог product_cat: количество, сортировка.
 */
function maintheme_pre_get_posts_catalog( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( ! $query->is_tax( 'product_cat' ) ) {
		return;
	}

	$query->set( 'post_type', 'product' );
	$query->set( 'posts_per_page', 12 );

	foreach ( maintheme_get_catalog_sort_query_args() as $key => $value ) {
		$query->set( $key, $value );
	}
}
add_action( 'pre_get_posts', 'maintheme_pre_get_posts_catalog' );

/**
 * Настройки Simple Basket по умолчанию.
 */
function maintheme_basket_setup_defaults() {
	if ( ! function_exists( 'update_option' ) ) {
		return;
	}

	if ( ! get_option( 'simple_basket_currency_symbol' ) ) {
		update_option( 'simple_basket_currency_symbol', '₽' );
	}

	$basket_url = maintheme_basket_url();

	if ( $basket_url && ! get_option( 'simple_basket_order_page' ) ) {
		update_option( 'simple_basket_order_page', $basket_url );
	}
}
add_action( 'after_setup_theme', 'maintheme_basket_setup_defaults', 20 );

/**
 * Имя отправителя в письмах WordPress (заказы, уведомления).
 */
function maintheme_mail_from_name( $name ) {
	return 'Агро Ас';
}
add_filter( 'wp_mail_from_name', 'maintheme_mail_from_name' );

/**
 * Редирект на «Спасибо» после успешной отправки Contact Form 7.
 */
function maintheme_cf7_thanks_redirect_script() {
	if ( is_admin() ) {
		return;
	}

	$thanks_url = maintheme_thanks_page_url();
	?>
<script type="text/javascript">
document.addEventListener( 'wpcf7mailsent', function () {
	(function ($) {
		window.location.href = <?php echo wp_json_encode( $thanks_url ); ?>;
		if ($.fancybox) {
			$.fancybox.close();
		}
	})(jQuery);
}, false);
</script>
	<?php
}
add_action( 'wp_footer', 'maintheme_cf7_thanks_redirect_script' );
