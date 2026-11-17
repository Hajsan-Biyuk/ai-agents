<?php
/**
 * Очистка данных при удалении плагина.
 *
 * @package Telegram_Zayavki
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
	exit;
}

delete_option('telegram_zayavki_settings');
delete_option('telegram_zayavki_offset');
delete_option('telegram_zayavki_last_error');
