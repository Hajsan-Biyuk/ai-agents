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


?>