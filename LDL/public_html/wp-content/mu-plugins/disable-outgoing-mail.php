<?php
/**
 * Plugin Name: LDL — без исходящей почты
 * Description: На ldl.sheer82.ru wp_mail ничего не отправляет. На других доменах не действует.
 */

add_filter(
	'pre_wp_mail',
	static function ( $short_circuit ) {
		$host = wp_parse_url( home_url(), PHP_URL_HOST );
		if ( ! is_string( $host ) || strtolower( $host ) !== 'ldl.sheer82.ru' ) {
			return $short_circuit;
		}
		return true;
	},
	1
);
