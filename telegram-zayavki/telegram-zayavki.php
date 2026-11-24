<?php
/**
 * Plugin Name: Telegram заявки
 * Description: Отправляет заявки Contact Form 7 и заказы WooCommerce в группу Telegram.
 * Version: 1.2.2
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Author: Sheer
 * Text Domain: telegram-zayavki
 *
 * @package Telegram_Zayavki
 */

if (!defined('ABSPATH')) {
	exit;
}

define('TZ_VERSION', '1.2.2');
define('TZ_FILE', __FILE__);
define('TZ_DIR', plugin_dir_path(__FILE__));
define('TZ_URL', plugin_dir_url(__FILE__));

require_once TZ_DIR . 'includes/class-settings.php';
require_once TZ_DIR . 'includes/class-telegram.php';
require_once TZ_DIR . 'includes/class-messages.php';
require_once TZ_DIR . 'includes/class-source.php';
require_once TZ_DIR . 'includes/class-admin.php';
require_once TZ_DIR . 'includes/class-cf7.php';
require_once TZ_DIR . 'includes/class-woocommerce.php';

/**
 * Точка входа плагина.
 */
function tz_boot() {
	TZ_Admin::instance()->hooks();
	TZ_Source::hooks();

	if (function_exists('wpcf7')) {
		TZ_CF7::instance()->hooks();
	}

	if (class_exists('WooCommerce')) {
		TZ_Woo::instance()->hooks();
	}
}
add_action('plugins_loaded', 'tz_boot');

/**
 * Ссылка «Настройки» в списке плагинов.
 *
 * @param array<int, string> $links Ссылки плагина.
 * @return array<int, string>
 */
function tz_plugin_action_links($links) {
	$url = admin_url('admin.php?page=telegram-zayavki');
	array_unshift($links, '<a href="' . esc_url($url) . '">Настройки</a>');
	return $links;
}
add_filter('plugin_action_links_' . plugin_basename(TZ_FILE), 'tz_plugin_action_links');
