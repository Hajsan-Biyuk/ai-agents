<?php
/**
 * Thank you page template.
 *
 * @package maintheme
 *
 * Template Name: Спасибо
 */

get_header();

while ( have_posts() ) {
	the_post();

	$descr = trim( wp_strip_all_tags( (string) get_post_field( 'post_content', get_the_ID() ) ) );

	if ( ! maintheme_is_filled( $descr ) ) {
		$descr = 'Наш менеджер свяжется с вами в течение часа';
	}

	get_template_part(
		'template-parts/page/service',
		'message',
		array(
			'modifier'   => 'thanks',
			'breadcrumb' => get_the_title(),
			'title'      => get_the_title(),
			'descr'      => $descr,
			'btn_text'   => 'Вернуться на главную',
			'btn_url'    => home_url( '/' ),
		)
	);
}

get_footer();
