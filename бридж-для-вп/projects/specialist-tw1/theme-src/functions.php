<?php


add_filter("show_admin_bar", "__return_false");




define("THEME_ROOT", get_template_directory_uri());

define("CSS_DIR", THEME_ROOT . "/css");
define("JS_DIR", THEME_ROOT . "/js");
define("IMG_DIR", THEME_ROOT . "/images");


add_action( 'wp_enqueue_scripts', 'theme_name_scripts' );

function theme_name_scripts() {

    wp_enqueue_style("main-style", get_stylesheet_uri(), array(), filemtime(get_stylesheet_directory() . "/style.css"));
    wp_enqueue_style("slick-style", CSS_DIR . "/slick.css");
    wp_enqueue_style("animate-style", CSS_DIR . "/animate.css");
    wp_enqueue_style("fancybox-style", CSS_DIR . "/jquery.fancybox.min.css");

    

    wp_enqueue_script("jquery-script", JS_DIR . "/jquery-3.3.1.min.js"); // jquery-3.3.1.slim.min.js
    wp_enqueue_script("wow-script", JS_DIR . "/wow.min.js");
    wp_enqueue_script("slick-script", JS_DIR . "/slick.min.js");
    wp_enqueue_script("fancybox-script", JS_DIR . "/jquery.fancybox.min.js");
    wp_enqueue_script("main-script", JS_DIR . "/main.js");
    
}



add_action('acf/init', 'spec_header_footer_options');
function spec_header_footer_options() {
    if (!function_exists('acf_add_options_page')) {
        return;
    }
    acf_add_options_page(array(
        'page_title' => 'Шапка и подвал',
        'menu_title' => 'Шапка и подвал',
        'menu_slug' => 'hp-header-footer',
        'capability' => 'edit_posts',
        'redirect' => false,
        'position' => 61,
        'icon_url' => 'dashicons-admin-customizer',
    ));
}

add_action("wp_enqueue_scripts", "spec_home_assets", 30);
function spec_home_assets() {
    if (!is_front_page()) {
        return;
    }
    $dir = get_stylesheet_directory() . "/home";
    $uri = get_stylesheet_directory_uri() . "/home";
    wp_enqueue_style("hp-fonts", $uri . "/css/fonts.css", array("main-style"), filemtime($dir . "/css/fonts.css"));
    wp_enqueue_style("hp-main", $uri . "/css/main.css", array("hp-fonts"), filemtime($dir . "/css/main.css"));
    wp_enqueue_style("hp-media", $uri . "/css/media.css", array("hp-main"), filemtime($dir . "/css/media.css"));
    wp_enqueue_script("hp-home", $uri . "/js/common.js", array("jquery-script"), filemtime($dir . "/js/common.js"), true);
}

?>