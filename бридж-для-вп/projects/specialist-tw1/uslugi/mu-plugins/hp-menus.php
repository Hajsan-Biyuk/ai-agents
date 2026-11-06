<?php
/**
 * Plugin Name: Меню шапки
 * Description: Регистрирует расположение «Шапка: главное меню» в разделе «Внешний вид → Меню».
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('after_setup_theme', function () {
    register_nav_menus(array(
        'hp_header' => 'Шапка: главное меню',
    ));
});
