<?php
/**
 * maintheme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package maintheme
 */

if ( ! function_exists( 'maintheme_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function maintheme_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on maintheme, use a find and replace
		 * to change 'maintheme' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'maintheme', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'menu-1' => esc_html__( 'Primary', 'maintheme' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'maintheme_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );
	}
endif;
add_action( 'after_setup_theme', 'maintheme_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function maintheme_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'maintheme_content_width', 640 );
}
add_action( 'after_setup_theme', 'maintheme_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function maintheme_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'maintheme' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'maintheme' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'maintheme_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function maintheme_scripts() {
	wp_enqueue_style( 'maintheme-style', get_stylesheet_uri() );

	wp_enqueue_script( 'maintheme-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'maintheme-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'maintheme_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

function mytheme_add_woocommerce_support() {
	add_theme_support('woocommerce');
}
add_action('after_setup_theme', 'mytheme_add_woocommerce_support');

/**
 * Custom variation fields: Диаметр, Форма, Литраж.
 */
add_action( 'woocommerce_product_after_variable_attributes', 'maintheme_add_custom_variation_fields', 10, 3 );
function maintheme_add_custom_variation_fields( $loop, $variation_data, $variation ) {
	$variation_id = $variation->ID;

	woocommerce_wp_text_input(
		array(
			'id'            => 'maintheme_variation_diameter_' . $variation_id,
			'name'          => 'maintheme_variation_diameter[' . $variation_id . ']',
			'value'         => get_post_meta( $variation_id, '_maintheme_variation_diameter', true ),
			'label'         => 'Диаметр',
			'wrapper_class' => 'form-row form-row-first',
		)
	);

	woocommerce_wp_text_input(
		array(
			'id'            => 'maintheme_variation_shape_' . $variation_id,
			'name'          => 'maintheme_variation_shape[' . $variation_id . ']',
			'value'         => get_post_meta( $variation_id, '_maintheme_variation_shape', true ),
			'label'         => 'Форма',
			'wrapper_class' => 'form-row form-row-last',
		)
	);

	woocommerce_wp_text_input(
		array(
			'id'            => 'maintheme_variation_literage_' . $variation_id,
			'name'          => 'maintheme_variation_literage[' . $variation_id . ']',
			'value'         => get_post_meta( $variation_id, '_maintheme_variation_literage', true ),
			'label'         => 'Литраж',
			'wrapper_class' => 'form-row form-row-full',
		)
	);
}

add_action( 'woocommerce_save_product_variation', 'maintheme_save_custom_variation_fields', 10, 2 );
function maintheme_save_custom_variation_fields( $variation_id, $i ) {
	$diameter = isset( $_POST['maintheme_variation_diameter'][ $variation_id ] )
		? sanitize_text_field( wp_unslash( $_POST['maintheme_variation_diameter'][ $variation_id ] ) )
		: '';
	$shape = isset( $_POST['maintheme_variation_shape'][ $variation_id ] )
		? sanitize_text_field( wp_unslash( $_POST['maintheme_variation_shape'][ $variation_id ] ) )
		: '';
	$literage = isset( $_POST['maintheme_variation_literage'][ $variation_id ] )
		? sanitize_text_field( wp_unslash( $_POST['maintheme_variation_literage'][ $variation_id ] ) )
		: '';

	update_post_meta( $variation_id, '_maintheme_variation_diameter', $diameter );
	update_post_meta( $variation_id, '_maintheme_variation_shape', $shape );
	update_post_meta( $variation_id, '_maintheme_variation_literage', $literage );
}

if( function_exists('acf_add_options_page') ) {	
	acf_add_options_page(array(
		'page_title' 	=> 'Основные настройки',
		'menu_title'	=> 'Настройки темы',
		'menu_slug' 	=> 'theme-general-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));	
}

add_filter( 'excerpt_length', function(){
	return 10;
});

add_filter('excerpt_more', function($more) {
	return '...';
});

function cc_mime_types($mimes) {
$mimes['svg'] = 'image/svg+xml';
return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');


add_action( 'wp_footer', 'mycustom_wp_footer' ); 
function mycustom_wp_footer() { ?>
<script type="text/javascript">
document.addEventListener( 'wpcf7mailsent', function( event ) {  
	(function($){   
        window.location.href = "<?php echo get_site_url();?>/spasibo/";
		$.fancybox.close();
   })(jQuery);
}, false );
</script>
<?php
}


add_action( 'init', 'register_portfolio_post_type' );
function register_portfolio_post_type() {
	// Раздел портфолио - portfolio_cat
	register_taxonomy('portfolio_cat', array('portfolio'), array(
		'label'                 => 'Категория Портфолио', // определяется параметром $labels->name
		'labels'                => array(
			'name'              => 'Категории Портфолио',
			'singular_name'     => 'Категория Портфолио',
			'search_items'      => 'Искать Категорию Портфолио',
			'all_items'         => 'Все Категории Портфолио',
			'parent_item'       => 'Родит. Категория Портфолио',
			'parent_item_colon' => 'Родит. Категория Портфолио:',
			'edit_item'         => 'Ред. Категорию Портфолио',
			'update_item'       => 'Обновить Категорию Портфолио',
			'add_new_item'      => 'Добавить Категорию Портфолио',
			'new_item_name'     => 'Новая Категория Портфолио',
			'menu_name'         => 'Категория Портфолио',
		),
		'description'           => 'Рубрики для Категории Портфолио', // описание таксономии
		'public'                => true,
		'show_in_nav_menus'     => true, // равен аргументу public
		'show_ui'               => true, // равен аргументу public
		'show_tagcloud'         => false, // равен аргументу show_ui
		'hierarchical'          => true,
		'rewrite'               => array('slug'=>'portfolios', 'hierarchical'=>true, 'with_front'=>false, 'feed'=>false ),
		'show_admin_column'     => true, // Позволить или нет авто-создание колонки таксономии в таблице ассоциированного типа записи. (с версии 3.5)
	) );

	// тип записи - Портфолио - portfolio
		register_post_type('portfolio', array(
			'label'               => 'Портфолио',
			'labels'              => array(
				'name'          => 'Портфолио',
				'singular_name' => 'Портфолио',
				'menu_name'     => 'Портфолио', // Архив товаров
				'all_items'     => 'Все Портфолио',
				'add_new'       => 'Добавить Портфолио',
				'add_new_item'  => 'Добавить новое Портфолио',
				'edit'          => 'Редактировать',
				'edit_item'     => 'Редактировать Портфолио',
				'new_item'      => 'Новое Портфолио',
			),
			'description'         => '',
			'public'              => true, //link
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_rest'        => false,
			'rest_base'           => '',
			'show_in_menu'        => true,
			'exclude_from_search' => false,
			'capability_type'     => 'post',
			'map_meta_cap'        => true,
			'menu_icon'           => 'dashicons-admin-home',
			'hierarchical'        => false,
			'rewrite'             => array('with_front'=>false, 'feeds'=>false, 'feed'=>false ),
			'has_archive'         => 'portfolio',
			'query_var'           => true,
			'supports'            => array( 'title', 'editor', 'thumbnail' ),
			'taxonomies'          => array( 'portfolio_cat' ),
		) );
		flush_rewrite_rules();

}

add_action( 'init', 'register_service_post_type' );
function register_service_post_type() {

	// тип записи - Услуга - service
		register_post_type('service', array(
			'label'               => 'Услуга',
			'labels'              => array(
				'name'          => 'Услуга',
				'singular_name' => 'Услуга',
				'menu_name'     => 'Услуга', // Архив товаров
				'all_items'     => 'Все Услуги',
				'add_new'       => 'Добавить Услугу',
				'add_new_item'  => 'Добавить новую Услугу',
				'edit'          => 'Редактировать',
				'edit_item'     => 'Редактировать Услугу',
				'new_item'      => 'Новая Услуга',
			),
			'description'         => '',
			'public'              => true, //link
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_rest'        => false,
			'rest_base'           => '',
			'show_in_menu'        => true,
			'exclude_from_search' => false,
			'capability_type'     => 'post',
			'map_meta_cap'        => true,
			'menu_icon'           => 'dashicons-admin-appearance',
			'hierarchical'        => false,
			'rewrite'             => array('with_front'=>false, 'feeds'=>false, 'feed'=>false ),
			'has_archive'         => 'service',
			'query_var'           => true,
			'supports'            => array( 'title', 'editor', 'thumbnail' ),			
		) );
		flush_rewrite_rules();

}

//пагинация в таксономии
function tax_portf_posts_per_page( $query ) {
    if( is_tax('portfolio_cat') ) {
        $query->set('posts_per_page', 6); //ваше значение
    }
    return $query;
}
add_filter('pre_get_posts', 'tax_portf_posts_per_page');

function set_default_mytax_term( $post_id, $post ) {
	if ( $post->post_type === 'portfolio' ) {
  
	  $terms = wp_get_post_terms( $post_id, 'portfolio_cat' );
  
	  if ( empty( $terms ) ) {
		wp_set_object_terms( $post_id, 'vse', 'portfolio_cat' );
	  }
  
	}
  }
  add_action( 'save_post', 'set_default_mytax_term', 100, 2 );

/**
 * Update cart count via AJAX
 */
add_filter( 'woocommerce_add_to_cart_fragments', 'maintheme_cart_count_fragments', 10, 1 );
function maintheme_cart_count_fragments( $fragments ) {
	ob_start();
	?>
	<span class="cart-count" id="header-cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
	<?php
	$fragments['#header-cart-count'] = ob_get_clean();
	return $fragments;
}

/**
 * Перенос админ-панели вниз страницы
 */
function move_admin_bar_to_bottom() {
    if ( is_admin_bar_showing() ) {
        ?>
        <style type="text/css">
            #wpadminbar {
                top: auto !important;
                bottom: 0;
            }
            #wpadminbar .menupop .ab-sub-wrapper {
                bottom: 32px;
            }
            html {
                margin-top: 0 !important;
                margin-bottom: 32px !important;
            }
            @media screen and (max-width: 782px) {
                #wpadminbar .menupop .ab-sub-wrapper {
                    bottom: 46px;
                }
                html {
                    margin-bottom: 46px !important;
                }
            }
        </style>
        <?php
    }
}
add_action('wp_head', 'move_admin_bar_to_bottom');
add_action('admin_head', 'move_admin_bar_to_bottom');

/**
 * Handle batched AJAX add to cart for Compositions
 */
add_action('wp_ajax_add_composition_to_cart', 'add_composition_to_cart_callback');
add_action('wp_ajax_nopriv_add_composition_to_cart', 'add_composition_to_cart_callback');
function add_composition_to_cart_callback() {
	if ( ! isset( $_POST['items'] ) || ! is_array( $_POST['items'] ) ) {
		wp_send_json_error( array( 'message' => 'No items provided.' ) );
	}

	$items = wc_clean( $_POST['items'] );
	$added_count = 0;

	foreach ( $items as $item ) {
		$product_id   = isset( $item['product_id'] ) ? absint( $item['product_id'] ) : 0;
		$variation_id = isset( $item['variation_id'] ) ? absint( $item['variation_id'] ) : 0;
		$quantity     = isset( $item['quantity'] ) ? absint( $item['quantity'] ) : 1;

		if ( $product_id > 0 ) {
			$variation = array();
			if ( $variation_id > 0 ) {
				$variation_obj = wc_get_product( $variation_id );
				if ( $variation_obj && is_a( $variation_obj, 'WC_Product_Variation' ) ) {
					$variation = $variation_obj->get_attributes();
				}
			}

			$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity, $variation_id, $variation );
			
			if ( $passed_validation ) {
				$cart_item_key = WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation );
				if ( $cart_item_key ) {
					$added_count++;
				}
			}
		}
	}

	if ( $added_count > 0 ) {
		WC_AJAX::get_refreshed_fragments(); // this will send WP JSON response with fragments
	} else {
		wp_send_json_error( array( 'message' => 'Could not add items to cart.' ) );
	}
	
	wp_die();
}

/**
 * Map activity slug → readable label
 */
function maintheme_get_activity_labels() {
	return array(
		'landscaper' => 'Ландшафтный дизайнер',
		'architect'  => 'Архитектор',
		// 'interior'   => 'Дизайнер интерьеров',
		'developer'  => 'Застройщик',
		'nursery'    => 'Владелец питомника или садового центра',
		// 'florist'    => 'Флорист',
		// 'gardener'   => 'Садовод-любитель',
		'other'      => 'Другое',
	);
}

/**
 * Before user is created: set user_login, display_name, nickname from billing_first_name
 */
add_filter( 'woocommerce_new_customer_data', 'maintheme_set_customer_data_from_fields', 10, 1 );
function maintheme_set_customer_data_from_fields( $data ) {
	if ( empty( $_POST['billing_first_name'] ) ) {
		return $data;
	}

	$fio = sanitize_text_field( wp_unslash( $_POST['billing_first_name'] ) );

	// display_name и nickname — из ФИО
	$data['display_name'] = $fio;
	$data['nickname']     = $fio;

	// user_login — пытаемся взять из ФИО (sanitize_user оставляет кириллицу)
	$desired_login = sanitize_user( $fio, true );

	if ( ! empty( $desired_login ) && ! username_exists( $desired_login ) ) {
		$data['user_login'] = $desired_login;
	} elseif ( ! empty( $desired_login ) ) {
		// Если имя уже занято — добавляем цифровой суффикс
		$suffix = 2;
		while ( username_exists( $desired_login . $suffix ) ) {
			$suffix++;
		}
		$data['user_login'] = $desired_login . $suffix;
	}
	// Если sanitize_user вернул пустую строку — оставляем авто-генерацию WooCommerce

	return $data;
}

/**
 * Save extra registration fields (ФИО, телефон, вид деятельности)
 */
add_action( 'woocommerce_created_customer', 'maintheme_save_extra_register_fields', 10, 3 );
function maintheme_save_extra_register_fields( $customer_id, $new_customer_data, $password_generated ) {

	// ФИО → first_name, display_name, nickname
	if ( ! empty( $_POST['billing_first_name'] ) ) {
		$fio = sanitize_text_field( wp_unslash( $_POST['billing_first_name'] ) );
		update_user_meta( $customer_id, 'billing_first_name', $fio );
		update_user_meta( $customer_id, 'first_name', $fio );
		// Обновляем display_name и nickname через wp_update_user
		wp_update_user( array(
			'ID'           => $customer_id,
			'display_name' => $fio,
			'nickname'     => $fio,
		) );
	}

	// Телефон
	if ( ! empty( $_POST['billing_phone'] ) ) {
		update_user_meta( $customer_id, 'billing_phone', sanitize_text_field( wp_unslash( $_POST['billing_phone'] ) ) );
	}

	// Вид деятельности
	$activity_label = '';
	if ( ! empty( $_POST['activity_type'] ) ) {
		$activity        = sanitize_text_field( wp_unslash( $_POST['activity_type'] ) );
		$activity_labels = maintheme_get_activity_labels();
		$activity_label  = isset( $activity_labels[ $activity ] ) ? $activity_labels[ $activity ] : $activity;

		// Если «Другое» — берём текст из доп. поля
		if ( 'other' === $activity && ! empty( $_POST['activity_other'] ) ) {
			$activity_other = sanitize_text_field( wp_unslash( $_POST['activity_other'] ) );
			update_user_meta( $customer_id, 'activity_other', $activity_other );
			$activity_label = $activity_other;
		}

		// Сохраняем slug и читаемое название
		update_user_meta( $customer_id, 'activity_type', $activity );
		update_user_meta( $customer_id, 'activity_type_label', $activity_label );
	}

	// Название организации
	$company = '';
	if ( ! empty( $_POST['billing_company'] ) ) {
		$company = sanitize_text_field( wp_unslash( $_POST['billing_company'] ) );
		update_user_meta( $customer_id, 'billing_company', $company );
	}

	// ИНН
	$inn = '';
	if ( ! empty( $_POST['billing_inn'] ) ) {
		$inn = sanitize_text_field( wp_unslash( $_POST['billing_inn'] ) );
		update_user_meta( $customer_id, 'billing_inn', $inn );
	}

	// Обновляем описание (Биография) — Вид деятельности, Название организации, ИНН через запятую
	$biography_parts = array();
	if ( ! empty( $activity_label ) ) {
		$biography_parts[] = $activity_label;
	}
	if ( ! empty( $company ) ) {
		$biography_parts[] = $company;
	}
	if ( ! empty( $inn ) ) {
		$biography_parts[] = 'ИНН: ' . $inn;
	}

	if ( ! empty( $biography_parts ) ) {
		wp_update_user( array(
			'ID'          => $customer_id,
			'description' => implode( ', ', $biography_parts ),
		) );
	}
}

/**
 * Отправка уведомления администратору о новой регистрации со всеми полями
 */
add_action( 'woocommerce_created_customer', 'maintheme_admin_notification_on_register', 20, 3 );
function maintheme_admin_notification_on_register( $customer_id, $new_customer_data, $password_generated ) {
	$user = get_userdata( $customer_id );
	$to   = get_option( 'admin_email' );
	$subject = 'Новая регистрация на сайте ' . get_bloginfo( 'name' );

	$fio      = get_user_meta( $customer_id, 'billing_first_name', true );
	$phone    = get_user_meta( $customer_id, 'billing_phone', true );
	$activity = get_user_meta( $customer_id, 'activity_type_label', true );
	$company  = get_user_meta( $customer_id, 'billing_company', true );
	$inn      = get_user_meta( $customer_id, 'billing_inn', true );

	$message  = "На сайте зарегистрирован новый пользователь.\n\n";
	$message .= "ФИО: " . ( $fio ? $fio : 'Не указано' ) . "\n";
	$message .= "Email: " . $user->user_email . "\n";
	$message .= "Телефон: " . ( $phone ? $phone : 'Не указано' ) . "\n";
	$message .= "Вид деятельности: " . ( $activity ? $activity : 'Не указано' ) . "\n";
	$message .= "Название организации: " . ( $company ? $company : 'Не указано' ) . "\n";
	$message .= "ИНН: " . ( $inn ? $inn : 'Не указано' ) . "\n";

	wp_mail( $to, $subject, $message );
}

/**
 * Validate extra registration fields
 */
add_filter( 'woocommerce_registration_errors', 'maintheme_validate_extra_register_fields', 10, 3 );
function maintheme_validate_extra_register_fields( $errors, $username, $email ) {
	if ( empty( $_POST['billing_first_name'] ) ) {
		$errors->add( 'billing_first_name_error', '<strong>Ошибка:</strong> Пожалуйста, введите ваше ФИО.' );
	}

	if ( empty( $_POST['billing_phone'] ) ) {
		$errors->add( 'billing_phone_error', '<strong>Ошибка:</strong> Пожалуйста, введите ваш телефон.' );
	}

	if ( empty( $_POST['activity_type'] ) ) {
		$errors->add( 'activity_type_error', '<strong>Ошибка:</strong> Пожалуйста, выберите вид деятельности.' );
	}

	if ( ! empty( $_POST['activity_type'] ) && 'other' === $_POST['activity_type'] && empty( $_POST['activity_other'] ) ) {
		$errors->add( 'activity_other_error', '<strong>Ошибка:</strong> Пожалуйста, укажите вид деятельности.' );
	}

	return $errors;
}


/**
 * PDF Presentation Generation
 */

add_action('wp_ajax_fv_get_presentation', 'fv_get_presentation_ajax_handler');
add_action('wp_ajax_nopriv_fv_get_presentation', 'fv_get_presentation_ajax_handler');

function fv_get_presentation_ajax_handler() {
    $product_id = isset($_REQUEST['product_id']) ? intval($_REQUEST['product_id']) : 0;
    if (!$product_id) {
        wp_send_json_error('Invalid Product ID');
    }

    $pdf_url = fv_generate_product_pdf($product_id);
    if (strpos($pdf_url, 'Error') === 0) {
        wp_send_json_error($pdf_url);
    }

    wp_send_json_success($pdf_url);
}

if (!function_exists('ld_url_to_path')) {
    function ld_url_to_path($url) {
        if (empty($url)) return false;
        $upload_dir = wp_upload_dir();
        if (strpos($url, $upload_dir['baseurl']) === 0) {
            return str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $url);
        }
        $theme_url = get_template_directory_uri();
        if (strpos($url, $theme_url) === 0) {
            return str_replace($theme_url, get_template_directory(), $url);
        }
        return $url;
    }
}

function fv_generate_product_pdf($product_id) {
    // Include FPDF
    $fpdf_path = $_SERVER['DOCUMENT_ROOT'] . '/fpdf/fpdf.php';
    if (!file_exists($fpdf_path)) {
        $fpdf_path = get_template_directory() . '/../pdf_download/fpdf/fpdf.php';
    }
    
    if (file_exists($fpdf_path)) {
        include_once($fpdf_path);
    }

    if (!class_exists('FPDF')) {
        return 'Error: FPDF library not found.';
    }

    $product = wc_get_product($product_id);
    if (!$product) {
        return 'Error: Product not found.';
    }

    // Helper for safe images (skip SVG)
    $get_img_path = function($url) {
        if (!$url) return false;
        $ext = strtolower(pathinfo($url, PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
            return ld_url_to_path($url);
        }
        return false;
    };

    // Helper for cleaning text from HTML tags and entities
    $clean_text = function($text) {
        $text = str_replace(['<br>', '<br/>', '<br />'], "\n", $text);
        $text = strip_tags($text);
        // Replace ruble and nbsp entities FIRST before they become unicode symbols
        $text = str_replace(['&nbsp;', '&#8381;', '&nbsp'], [' ', ' руб.', ' '], $text);
        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
        // Handle unicode nbsp and ruble symbol fallback
        $text = str_replace(["\xC2\xA0", '₽'], [' ', ' руб.'], $text);
        return trim($text);
    };

    // PDF Initialization
    $pdf = new FPDF('P', 'pt', 'A4');
    $pdf->SetMargins(40, 40, 40);
    $pdf->AddPage();
    
    // Fonts
    $font_dir = dirname($fpdf_path) . '/font/';
    if (file_exists($font_dir . 'yst.php')) {
        $pdf->AddFont('yst', '', 'yst.php');
        $pdf->AddFont('ystm', '', 'ystm.php');
    }

    // Logo
    $logo_path = get_template_directory() . '/img/logo.jpg';
    if (file_exists($logo_path)) {
        $pdf->Image($logo_path, 40, 25, 80, 0);
    }

    // SKU
    $pdf->SetXY(40, 75);
    $pdf->SetFont('yst', '', 9);
    $pdf->SetTextColor(150, 150, 150);
    if ($product->get_sku()) {
        $pdf->Write(10, iconv("UTF-8", "CP1251", 'Артикул: ' . $product->get_sku()));
    }

    // Title
    $pdf->SetXY(40, 90);
    $pdf->SetTextColor(63, 94, 41); // #3F5E29
    $pdf->SetFont('ystm', '', 20);
    $title = iconv("UTF-8", "CP1251", $clean_text($product->get_name()));
    $pdf->MultiCell(515, 24, $title, 0, 'L');
    
    $current_y = $pdf->GetY() + 20;

    // --- TWO COLUMN SECTION ---
    
    // Left: Image
    $image_id = $product->get_image_id();
    if ($image_id) {
        $image_url = wp_get_attachment_image_url($image_id, 'large');
        $image_path = $get_img_path($image_url);
        if ($image_path && file_exists($image_path)) {
            $pdf->Image($image_path, 40, $current_y, 240, 0);
            $img_bottom = $pdf->GetY() + 0; 
        }
    }

    // Right: Short Description & Prices
    $pdf->SetXY(300, $current_y);
    $pdf->SetFont('yst', '', 11);
    $pdf->SetTextColor(50, 50, 50);
    $short_desc = $clean_text($product->get_short_description());
    if ($short_desc) {
        $pdf->MultiCell(255, 14, iconv("UTF-8", "CP1251", $short_desc), 0, 'L');
    }
    
    $current_y_right = $pdf->GetY() + 20;
    
    // Prices
    $pdf->SetXY(300, $current_y_right);
    $display_price = wc_get_price_to_display($product);
    if ($display_price > 0 && $product->is_on_sale()) {
        // Old Price
        $pdf->SetFont('yst', '', 12);
        $pdf->SetTextColor(150, 150, 150);
        $old_price_text = $clean_text(wc_price(wc_get_price_to_display($product, array('price' => $product->get_regular_price()))));
        $old_price_text = str_replace(' руб.', '', $old_price_text); // remove rub for old price to keep style
        $old_price_w = $pdf->GetStringWidth(iconv("UTF-8", "CP1251", $old_price_text));
        $pdf->Text(300, $current_y_right + 12, iconv("UTF-8", "CP1251", $old_price_text));
        $pdf->Line(300, $current_y_right + 8, 300 + $old_price_w, $current_y_right + 8);
        
        $current_y_right += 20;
    }
    
    if ($display_price > 0) {
        $pdf->SetXY(300, $current_y_right);
        $pdf->SetFont('ystm', '', 22);
        $pdf->SetTextColor(63, 94, 41);
        $current_price = iconv("UTF-8", "CP1251", $clean_text(wc_get_price_to_display($product)) . ' руб.');
        $pdf->Cell(255, 30, $current_price, 0, 1, 'L');
    }

    $current_y_right = $pdf->GetY() + 10;

    // Variations Section
    if ($product->is_type('variable')) {
        $attributes = $product->get_variation_attributes();
        
        foreach ($attributes as $attribute_name => $options) {
            $pdf->SetXY(300, $current_y_right);
            $pdf->SetFont('ystm', '', 11);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(255, 15, iconv("UTF-8", "CP1251", wc_attribute_label($attribute_name) . ':'), 0, 1, 'L');
            
            $current_y_right = $pdf->GetY() + 3;
            $pdf->SetXY(300, $current_y_right);
            $pdf->SetFont('yst', '', 10);
            $pdf->SetTextColor(100, 100, 100);
            
            $option_names = [];
            foreach ($options as $option) {
                $name = apply_filters('woocommerce_variation_option_name', $option, $option, $attribute_name, $product);
                $option_names[] = $name;
            }
            $pdf->MultiCell(255, 12, iconv("UTF-8", "CP1251", implode(', ', $option_names)), 0, 'L');
            $current_y_right = $pdf->GetY() + 10;
        }
    }

    // Move Y after Image/Info
    $current_y = max($current_y + 260, $pdf->GetY() + 40);
    
    // --- COMPOSITION SECTION (tovary) ---
    $composition_items = get_field('tovary', $product_id);
    if (!empty($composition_items) && is_array($composition_items)) {
        $pdf->Line(40, $current_y - 20, 555, $current_y - 20);
        $pdf->SetXY(40, $current_y);
        $pdf->SetFont('ystm', '', 14);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 20, iconv("UTF-8", "CP1251", 'Состав композиции:'), 0, 1, 'L');
        $current_y = $pdf->GetY() + 10;

        foreach ($composition_items as $comp_item) {
            $child_product = wc_get_product($comp_item->ID);
            if (!$child_product) continue;

            if ($current_y > 750) {
                $pdf->AddPage();
                $current_y = 50;
            }
            
            // Thumbnail
            $child_img_url = wp_get_attachment_image_url($child_product->get_image_id(), 'medium');
            $child_img_path = $get_img_path($child_img_url);
            if ($child_img_path && file_exists($child_img_path)) {
                $pdf->Image($child_img_path, 40, $current_y, 40, 40);
            }
            
            // Title & Info
            $pdf->SetXY(90, $current_y);
            $pdf->SetFont('ystm', '', 10);
            $pdf->SetTextColor(63, 94, 41);
            $pdf->Cell(350, 12, iconv("UTF-8", "CP1251", $child_product->get_name()), 0, 1, 'L');
            
            $pdf->SetXY(90, $pdf->GetY() + 2);
            $pdf->SetFont('yst', '', 9);
            $pdf->SetTextColor(80, 80, 80);
            $child_desc = $clean_text($child_product->get_short_description());
            if ($child_desc) {
                $pdf->MultiCell(350, 10, iconv("UTF-8", "CP1251", $child_desc), 0, 'L');
            }
            
            // Price column
            $child_price = wc_get_price_to_display($child_product);
            if ($child_price > 0) {
                $pdf->SetXY(450, $current_y);
                $pdf->SetFont('ystm', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->Cell(105, 12, iconv("UTF-8", "CP1251", $clean_text($child_price) . ' руб.'), 0, 1, 'R');
            }
            
            $current_y = max($current_y + 45, $pdf->GetY() + 10);
        }
        $current_y += 20;
    }

    $pdf->Line(40, $current_y - 10, 555, $current_y - 10);

    // --- FEATURES SECTION ---
    $features = get_field('harakteristiki', $product_id);
    if ($features) {
        $pdf->SetXY(40, $current_y);
        $pdf->SetFont('ystm', '', 14);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 20, iconv("UTF-8", "CP1251", 'Характеристики:'), 0, 1, 'L');
        $current_y = $pdf->GetY() + 10;

        foreach ($features as $feature) {
            if ($current_y > 750) {
                $pdf->AddPage();
                $current_y = 40;
            }
            
            // Feature Category Header
            $pdf->SetXY(40, $current_y);
            
            // Category Icon
            if (!empty($feature['ikonka_zagolovka'])) {
                $icon_url = $feature['ikonka_zagolovka']['sizes']['thumbnail'];
                $icon_path = $get_img_path($icon_url);
                if ($icon_path && file_exists($icon_path)) {
                    $pdf->Image($icon_path, 40, $current_y, 14, 14);
                    $pdf->SetX(60);
                }
            } else {
                $pdf->SetX(40);
            }
            
            $pdf->SetFont('ystm', '', 11);
            $pdf->SetTextColor(63, 94, 41);
            $pdf->Cell(0, 15, iconv("UTF-8", "CP1251", strtoupper($clean_text($feature['zagolovok']))), 0, 1, 'L');
            $current_y = $pdf->GetY() + 5;
            
            // Values
            if (!empty($feature['znacheniya'])) {
                $pdf->SetFont('yst', '', 10);
                $pdf->SetTextColor(0, 0, 0);
                
                foreach ($feature['znacheniya'] as $z) {
                    if ($current_y > 780) {
                        $pdf->AddPage();
                        $current_y = 40;
                    }
                    $pdf->SetXY(60, $current_y);
                    
                    // Value Icon
                    if (!empty($z['ikonka_znacheniya'])) {
                        $v_icon_url = $z['ikonka_znacheniya']['sizes']['thumbnail'];
                        $v_icon_path = $get_img_path($v_icon_url);
                        if ($v_icon_path && file_exists($v_icon_path)) {
                            $pdf->Image($v_icon_path, 60, $current_y, 10, 10);
                            $pdf->SetX(75);
                        }
                    } else {
                        $pdf->SetX(60);
                    }
                    
                    $pdf->Cell(0, 12, iconv("UTF-8", "CP1251", $clean_text($z['znachenie'])), 0, 1, 'L');
                    $current_y = $pdf->GetY() + 3;
                }
            }
            $current_y += 10;
        }
    }

    // --- CALENDAR (izobrazhenie_tabliczy) ---
    $calendar_img = get_field('izobrazhenie_tabliczy', $product_id);
    if ($calendar_img) {
        $cal_img_url = is_array($calendar_img) ? $calendar_img['sizes']['large'] : $calendar_img;
        $cal_img_path = $get_img_path($cal_img_url);
        if ($cal_img_path && file_exists($cal_img_path)) {
            // Calculate image height to avoid overlap
            $img_meta = getimagesize($cal_img_path);
            $rendered_h = ($img_meta[1] / $img_meta[0]) * 515;

            if ($current_y + $rendered_h > 780) {
                $pdf->AddPage();
                $current_y = 50;
            } else {
                $current_y += 30;
            }
            
            $pdf->Image($cal_img_path, 40, $current_y, 515, 0);
            $current_y += $rendered_h + 30;
            $pdf->SetY($current_y);
        }
    }

    // --- DESCRIPTION SECTION ---
    $description = $clean_text($product->get_description());
    if ($description) {
        if ($current_y > 700) {
            $pdf->AddPage();
            $current_y = 40;
        } else {
            $current_y += 20;
        }
        
        $pdf->SetXY(40, $current_y);
        $pdf->SetFont('ystm', '', 14);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 20, iconv("UTF-8", "CP1251", 'Описание:'), 0, 1, 'L');
        $current_y = $pdf->GetY() + 5;
        
        $pdf->SetXY(40, $current_y);
        $pdf->SetFont('yst', '', 10);
        $pdf->SetTextColor(50, 50, 50);
        $pdf->MultiCell(515, 14, iconv("UTF-8", "CP1251", $description), 0, 'L');
    }


    // Footer
    $pdf->SetY(-40);
    $pdf->SetFont('yst', '', 8);
    $pdf->SetTextColor(150, 150, 150);
    $pdf->Cell(0, 10, iconv("UTF-8", "CP1251", 'Сгенерировано ' . date('d.m.Y') . ' на сайте ' . get_bloginfo('name')), 0, 0, 'C');

    // Output
    $upload_dir = wp_upload_dir();
    $dir = $upload_dir['basedir'] . '/_presentation';
    if (!file_exists($dir)) {
        wp_mkdir_p($dir);
    }
    
    $file_path = $dir . '/presentation_' . $product_id . '.pdf';
    $file_url = $upload_dir['baseurl'] . '/_presentation/presentation_' . $product_id . '.pdf';
    
    try {
        $pdf->Output($file_path, 'F');
    } catch (Exception $e) {
        return 'Error: ' . $e->getMessage();
    }

    return $file_url;
}


/**
 * Изменение количества товаров на странице в каталоге WooCommerce
 */
add_filter( 'loop_shop_per_page', 'new_loop_shop_per_page', 20 );

function new_loop_shop_per_page( $cols ) {
  // $cols содержит текущее количество (по умолчанию 12)
  // Укажите нужное число товаров
  $cols = 12; 
  return $cols;
}

/**
 * Упрощение полей оформления заказа WooCommerce
 * Оставляем только: Имя, Телефон, Email, Адрес
 */

// 1. Убираем обязательность и скрываем адресные поля через woocommerce_default_address_fields
add_filter( 'woocommerce_default_address_fields', 'maintheme_override_address_fields', 9999 );
function maintheme_override_address_fields( $address_fields ) {
    // Делаем ненужные поля необязательными
    $address_fields['last_name']['required']  = false;
    $address_fields['company']['required']    = false;
    $address_fields['country']['required']    = false;
    $address_fields['address_2']['required']  = false;
    $address_fields['city']['required']       = false;
    $address_fields['postcode']['required']   = false;
    $address_fields['state']['required']      = false;

    // Адрес оставляем обязательным
    $address_fields['address_1']['required'] = true;
    $address_fields['address_1']['label']    = 'Адрес (город, улица, дом)';
    $address_fields['address_1']['placeholder'] = 'Введите адрес (Доставка только в Сочи и Адлер)';

    return $address_fields;
}

// 2. Убираем лишние поля через woocommerce_checkout_fields
add_filter( 'woocommerce_checkout_fields', 'maintheme_custom_checkout_fields', 9999 );
function maintheme_custom_checkout_fields( $fields ) {

    // Удаляем ненужные поля биллинга
    $hide = array(
        'billing_last_name',
        'billing_company',
        'billing_country',
        'billing_address_2',
        'billing_city',
        'billing_postcode',
        'billing_state',
    );
    foreach ( $hide as $key ) {
        unset( $fields['billing'][ $key ] );
    }

    // Убираем shipping и комментарии
    unset( $fields['shipping'] );
    unset( $fields['order']['order_comments'] );

    // Настраиваем оставшиеся поля
    if ( isset( $fields['billing']['billing_first_name'] ) ) {
        $fields['billing']['billing_first_name']['label']       = 'Ваше Имя';
        $fields['billing']['billing_first_name']['placeholder'] = 'Имя';
        $fields['billing']['billing_first_name']['priority']    = 10;
    }
    if ( isset( $fields['billing']['billing_phone'] ) ) {
        $fields['billing']['billing_phone']['label']       = 'Ваш телефон';
        $fields['billing']['billing_phone']['placeholder'] = 'Телефон';
        $fields['billing']['billing_phone']['priority']    = 20;
    }
    if ( isset( $fields['billing']['billing_email'] ) ) {
        $fields['billing']['billing_email']['label']       = 'Ваш Email';
        $fields['billing']['billing_email']['placeholder'] = 'Email';
        $fields['billing']['billing_email']['priority']    = 30;
    }
    if ( isset( $fields['billing']['billing_address_1'] ) ) {
        $fields['billing']['billing_address_1']['priority'] = 40;
    }

    return $fields;
}

// 3. CSS-скрытие полей для WooCommerce Blocks Checkout
add_action( 'wp_head', 'maintheme_hide_checkout_fields_css' );
function maintheme_hide_checkout_fields_css() {
    if ( ! function_exists( 'is_checkout' ) || ! is_checkout() ) return;
    ?>
    <style>
        /* === WooCommerce Blocks Checkout: скрываем лишние поля === */

        /* Фамилия */
        .wc-block-components-address-form__last_name { display: none !important; }

        /* Страна */
        .wc-block-components-address-form__country { display: none !important; }

        /* Адрес 2 (кнопка "+" и скрытый инпут) */
        .wc-block-components-address-form__address_2-toggle,
        .wc-block-components-address-form__address_2-hidden-input,
        .wc-block-components-address-form__address_2 { display: none !important; }

        /* Город / населенный пункт */
        .wc-block-components-address-form__city { display: none !important; }

        /* Область / район */
        .wc-block-components-address-form__state { display: none !important; }

        /* Почтовый индекс */
        .wc-block-components-address-form__postcode { display: none !important; }

        /* Чекбокс "Использовать этот адрес для выставления счетов" */
        .wc-block-checkout__use-address-for-billing { display: none !important; }



        /* Карточка адреса (предпросмотр сохранённого адреса) */
        .wc-block-components-address-card-wrapper { display: none !important; }

        /* Имя — делаем на всю ширину (без фамилии рядом) */
        .wc-block-components-address-form__first_name {
            flex-basis: 100% !important;
            max-width: 100% !important;
        }

        /* === Классический чекаут (фоллбэк) === */
        #billing_last_name_field,
        #billing_company_field,
        #billing_country_field,
        #billing_address_2_field,
        #billing_city_field,
        #billing_postcode_field,
        #billing_state_field,
        .woocommerce-shipping-fields,
        #order_comments_field,
        .wc-block-components-address-form__phone label { display: none !important; }
    </style>
    <?php
}


/**
 * Логика доставки для WooCommerce Blocks Checkout:
 * Доставка доступна ТОЛЬКО при вводе "Сочи" или "Адлер" в адресе.
 * Иначе — скрываем блок доставки и показываем сообщение.
 */
add_action( 'wp_footer', 'maintheme_checkout_delivery_script' );
function maintheme_checkout_delivery_script() {
    if ( ! function_exists( 'is_checkout' ) || ! is_checkout() || is_wc_endpoint_url() ) return;
    ?>
    <style>
        .maintheme-no-delivery-notice {
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 8px;
            padding: 12px 16px;
            margin: 16px 0;
            font-size: 14px;
            color: #856404;
            display: none;
        }

        /* В режиме самовывоза скрываем поле адреса независимо от варианта разметки */
        body.maintheme-pickup-selected .wc-block-components-address-form__address_1,
        body.maintheme-pickup-selected .wc-block-components-text-input.wc-block-components-address-form__address_1,
        body.maintheme-pickup-selected #shipping-address_1,
        body.maintheme-pickup-selected #billing-address_1 {
            display: none !important;
        }

        /* Скрываем системное сообщение "Нет опций доставки..." */
        .maintheme-hide-no-shipping-message {
            display: none !important;
        }
    </style>
    <script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {

        var noticeId = 'maintheme-no-delivery-notice';

        function isPickupSelected() {
            // Ваш блок табов: #shipping-method с role="radio".
            var selectedShippingType = document.querySelector('#shipping-method [role="radio"][aria-checked="true"]');
            if (selectedShippingType && selectedShippingType.textContent) {
                return selectedShippingType.textContent.toLowerCase().indexOf('самовывоз') !== -1;
            }

            // Фоллбэк: выбран pickup rate (local_pickup/pickup_location).
            var selectedPickupRate = document.querySelector(
                'input[type="radio"][value*="local_pickup"]:checked,' +
                'input[type="radio"][value*="pickup_location"]:checked,' +
                'input[type="radio"][id*="local_pickup"]:checked,' +
                'input[type="radio"][id*="pickup_location"]:checked'
            );
            return !!selectedPickupRate;
        }

        function checkDelivery() {
            var pickupSelected = isPickupSelected();
            var addressInput = document.getElementById('shipping-address_1') || document.getElementById('billing-address_1');
            var notice = document.getElementById(noticeId);
            var shippingOption = document.getElementById('shipping-option');
            var totalsShippingRows = document.querySelectorAll('.wc-block-components-totals-shipping, .wc-block-components-totals-item--shipping');
            var addressRows = document.querySelectorAll(
                '.wc-block-components-address-form__address_1,' +
                '.wc-block-components-text-input.wc-block-components-address-form__address_1,' +
                '#shipping-address_1,' +
                '#billing-address_1'
            );
            var warningBanners = document.querySelectorAll('.wc-block-components-notice-banner, .wc-block-components-validation-error');
            var shippingMethodRows = document.querySelectorAll(
                '#shipping-option input[type="radio"], #pickup-options input[type="radio"], input[type="radio"][value*="flat_rate"], input[type="radio"][value*="pickup"]'
            );
            var billingAddressRow = document.getElementById('billing-address_1');
            billingAddressRow = billingAddressRow ? billingAddressRow.closest('.wc-block-components-text-input.wc-block-components-address-form__address_1') : null;
            var addressContainer = null;
            if (addressInput) {
                addressContainer = addressInput.closest('.wc-block-components-address-form__address_1') ||
                                   addressInput.closest('.wc-block-components-text-input') ||
                                   addressInput.parentNode;
            } else {
                addressContainer = document.querySelector('.wc-block-components-address-form__address_1');
            }

            if (pickupSelected) {
                document.body.classList.add('maintheme-pickup-selected');
                if (shippingOption) shippingOption.style.display = '';
                if (billingAddressRow && billingAddressRow.style) {
                    billingAddressRow.style.display = 'none';
                }
                shippingMethodRows.forEach(function(radio) {
                    var row = radio.closest('label') || radio.closest('.wc-block-components-radio-control');
                    if (row && row.style) {
                        row.style.display = '';
                    }
                });
                if (addressContainer) addressContainer.style.display = 'none';
                addressRows.forEach(function(row) {
                    if (row && row.style) {
                        row.style.display = 'none';
                    }
                });
                if (notice) notice.style.display = 'none';
                totalsShippingRows.forEach(function(row) { row.style.display = ''; });
                return; // Если самовывоз, дальше доставку не проверяем
            }

            document.body.classList.remove('maintheme-pickup-selected');
            if (billingAddressRow && billingAddressRow.style) {
                billingAddressRow.style.display = '';
            }
            if (addressContainer) addressContainer.style.display = '';
            addressRows.forEach(function(row) {
                if (row && row.style) {
                    row.style.display = '';
                }
            });

            if (!addressInput) {
                if (shippingOption) shippingOption.style.display = 'none';
                totalsShippingRows.forEach(function(row) { row.style.display = 'none'; });
                return;
            }

            var address = addressInput.value.toLowerCase();
            var isSochiAdler = address.indexOf('сочи') !== -1 || address.indexOf('адлер') !== -1;

            warningBanners.forEach(function(banner) {
                if (!banner || !banner.textContent) return;
                var text = banner.textContent.toLowerCase();
                if (text.indexOf('нет опций доставки') !== -1) {
                    banner.classList.add('maintheme-hide-no-shipping-message');
                }
            });

            if (!notice && shippingOption) {
                notice = document.createElement('div');
                notice.id = noticeId;
                notice.className = 'maintheme-no-delivery-notice';
                notice.textContent = 'По данному адресу доставка отсутствует. Доставка осуществляется только в Сочи и Адлер.';
                shippingOption.parentNode.insertBefore(notice, shippingOption.nextSibling);
            }

            if (isSochiAdler) {
                if (shippingOption) shippingOption.style.display = '';
                if (notice) notice.style.display = 'none';
                totalsShippingRows.forEach(function(row) { row.style.display = ''; });
                shippingMethodRows.forEach(function(radio) {
                    var row = radio.closest('label') || radio.closest('.wc-block-components-radio-control');
                    if (row && row.style) {
                        row.style.display = '';
                    }
                });
            } else {
                if (shippingOption) shippingOption.style.display = 'none';
                if (notice) notice.style.display = 'block';
                totalsShippingRows.forEach(function(row) { row.style.display = 'none'; });
                shippingMethodRows.forEach(function(radio) {
                    var value = (radio.value || '').toLowerCase();
                    var id = (radio.id || '').toLowerCase();
                    var row = radio.closest('label') || radio.closest('.wc-block-components-radio-control');
                    var isPickup = value.indexOf('local_pickup') !== -1 || id.indexOf('local_pickup') !== -1 || value.indexOf('pickup_location') !== -1 || id.indexOf('pickup_location') !== -1;
                    if (row && row.style) {
                        row.style.display = isPickup ? '' : 'none';
                    }
                });
            }
        }

        var observer = new MutationObserver(function() {
            var addressInputs = [
                document.getElementById('shipping-address_1'),
                document.getElementById('billing-address_1')
            ];

            addressInputs.forEach(function(addressInput) {
                if (addressInput && !addressInput.dataset.listening) {
                    addressInput.dataset.listening = 'true';
                    addressInput.addEventListener('input', checkDelivery);
                }
            });

            var shippingMethodRadios = document.querySelectorAll(
                '#shipping-option input[type="radio"], #pickup-options input[type="radio"], input[type="radio"][value*="flat_rate"], input[type="radio"][value*="pickup"]'
            );
            shippingMethodRadios.forEach(function(radio) {
                if (!radio.dataset.listening) {
                    radio.dataset.listening = 'true';
                    radio.addEventListener('change', checkDelivery);
                }
            });

            var tabs = document.querySelectorAll('[role="tab"]');
            tabs.forEach(function(tab) {
                if (!tab.dataset.listening) {
                    tab.dataset.listening = 'true';
                    tab.addEventListener('click', function() {
                        setTimeout(checkDelivery, 50);
                    });
                }
            });

            var shippingTypeTabs = document.querySelectorAll('#shipping-method [role="radio"]');
            shippingTypeTabs.forEach(function(tab) {
                if (!tab.dataset.listening) {
                    tab.dataset.listening = 'true';
                    tab.addEventListener('click', function() { setTimeout(checkDelivery, 100); });
                    tab.addEventListener('keyup', function() { setTimeout(checkDelivery, 100); });
                }
            });

            if (!document.body.dataset.deliveryCheckListening) {
                document.body.dataset.deliveryCheckListening = 'true';
                document.body.addEventListener('change', function(event) {
                    var target = event.target;
                    if (target && target.matches('input[type="radio"]')) {
                        checkDelivery();
                    }
                });
                document.body.addEventListener('input', function(event) {
                    var target = event.target;
                    if (target && (target.id === 'shipping-address_1' || target.id === 'billing-address_1')) {
                        checkDelivery();
                    }
                });
            }
            checkDelivery();
        });

        observer.observe(document.body, { childList: true, subtree: true });

        // Начальная проверка
        setTimeout(checkDelivery, 1000);

        // === Маска ввода телефона +7 (XXX) XXX-XX-XX ===
        function applyPhoneMask(input) {
            function mask(event) {
                var keyCode = event.keyCode;
                if ([8, 9, 27, 46].indexOf(keyCode) !== -1 ||
                    (keyCode === 65 && (event.ctrlKey || event.metaKey)) ||
                    (keyCode >= 35 && keyCode <= 40)) {
                    return;
                }

                var val = input.value.replace(/\D/g, '');
                if (val.length > 0 && (val[0] === '8' || val[0] === '7')) {
                    val = val.substring(1);
                }

                var result = '+7';
                if (val.length > 0) {
                    result += ' (' + val.substring(0, 3);
                }
                if (val.length >= 3) {
                    result += ') ' + val.substring(3, 6);
                }
                if (val.length >= 6) {
                    result += '-' + val.substring(6, 8);
                }
                if (val.length >= 8) {
                    result += '-' + val.substring(8, 10);
                }

                input.value = result;

                // Триггерим React-обновление
                var descriptor = Object.getOwnPropertyDescriptor(window.HTMLInputElement.prototype, 'value');
                if (descriptor && descriptor.set) {
                    descriptor.set.call(input, result);
                }
                input.dispatchEvent(new Event('input', { bubbles: true }));
            }

            input.addEventListener('keyup', mask);
            input.addEventListener('focus', function() {
                if (!input.value) {
                    input.value = '+7';
                    var descriptor = Object.getOwnPropertyDescriptor(window.HTMLInputElement.prototype, 'value');
                    if (descriptor && descriptor.set) {
                        descriptor.set.call(input, '+7');
                    }
                    input.dispatchEvent(new Event('input', { bubbles: true }));
                }
            });
        }

        // Наблюдаем за появлением поля телефона
        var phoneObserver = new MutationObserver(function() {
            var phoneInputs = document.querySelectorAll('input[type="tel"], input[id*="phone"]');
            phoneInputs.forEach(function(phoneInput) {
                if (!phoneInput.dataset.masked) {
                    phoneInput.dataset.masked = 'true';
                    phoneInput.setAttribute('placeholder', '+7 (___) ___-__-__');
                    applyPhoneMask(phoneInput);
                }
            });
        });
        phoneObserver.observe(document.body, { childList: true, subtree: true });
    });
    </script>
    <?php
}

/**
 * Dynamic shipping cost for Sochi/Adler by cart amount.
 */
add_filter( 'woocommerce_package_rates', 'maintheme_dynamic_shipping_rates_by_city', 20, 2 );
function maintheme_dynamic_shipping_rates_by_city( $rates, $package ) {
	if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
		return $rates;
	}

	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		return $rates;
	}

	$destination_parts = array();
	$destination_parts[] = isset( $package['destination']['address'] ) ? (string) $package['destination']['address'] : '';
	$destination_parts[] = isset( $package['destination']['address_2'] ) ? (string) $package['destination']['address_2'] : '';
	$destination_parts[] = isset( $package['destination']['city'] ) ? (string) $package['destination']['city'] : '';

	// Фоллбэк: в проекте используется billing address для доставки.
	$destination_parts[] = WC()->customer ? (string) WC()->customer->get_shipping_address() : '';
	$destination_parts[] = WC()->customer ? (string) WC()->customer->get_shipping_city() : '';
	$destination_parts[] = WC()->customer ? (string) WC()->customer->get_billing_address() : '';
	$destination_parts[] = WC()->customer ? (string) WC()->customer->get_billing_city() : '';

	$destination_text = mb_strtolower( trim( implode( ' ', array_filter( $destination_parts ) ) ) );
	$is_sochi = '' !== $destination_text && false !== mb_strpos( $destination_text, 'сочи' );
	$is_adler = '' !== $destination_text && false !== mb_strpos( $destination_text, 'адлер' );

	if ( ! $is_sochi && ! $is_adler ) {
		foreach ( $rates as $rate_id => $rate ) {
			if ( in_array( (string) $rate->method_id, array( 'local_pickup', 'pickup_location' ), true ) ) {
				continue;
			}

			$rates[ $rate_id ]->cost = 0;
			if ( ! empty( $rate->taxes ) ) {
				$tax_rates = WC_Tax::get_shipping_tax_rates();
				$rates[ $rate_id ]->taxes = WC_Tax::calc_shipping_tax( 0, $tax_rates );
			}
		}
		return $rates;
	}

	$target_cost = null;
	$target_label = '';
	$cart_subtotal = (float) WC()->cart->get_cart_contents_total();

	if ( $is_sochi ) {
		$target_cost = $cart_subtotal >= 25000 ? 0 : 3000;
		$target_label = 'Доставка по Сочи';
	} elseif ( $is_adler ) {
		$target_cost = $cart_subtotal >= 50000 ? 0 : 5000;
		$target_label = 'Доставка по Адлеру';
	}

	if ( null === $target_cost ) {
		return $rates;
	}

	foreach ( $rates as $rate_id => $rate ) {
		// Самовывоз не меняем.
		if ( in_array( (string) $rate->method_id, array( 'local_pickup', 'pickup_location' ), true ) ) {
			continue;
		}

		$rates[ $rate_id ]->cost = $target_cost;
		if ( '' !== $target_label ) {
			$rates[ $rate_id ]->label = $target_label;
		}

		if ( ! empty( $rate->taxes ) ) {
			$tax_rates = WC_Tax::get_shipping_tax_rates();
			$rates[ $rate_id ]->taxes = WC_Tax::calc_shipping_tax( $target_cost, $tax_rates );
		}
	}

	return $rates;
}

/**
 * Hide shipping row in cart totals until method selected.
 */
add_filter( 'woocommerce_cart_needs_shipping', 'maintheme_hide_cart_shipping_until_selected', 30 );
function maintheme_hide_cart_shipping_until_selected( $needs_shipping ) {
	if ( ! is_cart() || is_checkout() ) {
		return $needs_shipping;
	}

	if ( ! function_exists( 'WC' ) || ! WC()->session ) {
		return $needs_shipping;
	}

	$chosen_methods = (array) WC()->session->get( 'chosen_shipping_methods', array() );
	$has_selected_method = false;

	foreach ( $chosen_methods as $method_id ) {
		if ( '' !== trim( (string) $method_id ) ) {
			$has_selected_method = true;
			break;
		}
	}

	return $has_selected_method ? $needs_shipping : false;
}

/**
 * Cart Blocks: hide shipping row in cart totals.
 * Shipping option is selected on checkout step.
 */
add_action( 'wp_head', 'maintheme_hide_cart_shipping_block_css', 99 );
function maintheme_hide_cart_shipping_block_css() {
	if ( ! function_exists( 'is_cart' ) || ! is_cart() || is_checkout() ) {
		return;
	}

	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		return;
	}

	$shipping_total = (float) WC()->cart->get_shipping_total();
	$chosen_methods = WC()->session ? (array) WC()->session->get( 'chosen_shipping_methods', array() ) : array();
	$has_selected_method = false;
	foreach ( $chosen_methods as $method_id ) {
		if ( '' !== trim( (string) $method_id ) ) {
			$has_selected_method = true;
			break;
		}
	}

	if ( $shipping_total > 0 || $has_selected_method ) {
		// Показываем блок, если доставка платная или метод уже выбран.
		return;
	}

	?>
	<style>
		.wp-block-woocommerce-cart-order-summary-shipping-block {
			display: none !important;
		}
	</style>
	<?php
}
