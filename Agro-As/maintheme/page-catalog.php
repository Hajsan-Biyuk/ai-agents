<?php
/**
 * Catalog landing — сетка категорий product_cat.
 *
 * @package maintheme
 *
 * Template Name: Каталог
 */

get_header();

while ( have_posts() ) {
	the_post();

	get_template_part(
		'template-parts/shared/categories-section',
		null,
		array(
			'context'  => 'page',
			'title'    => get_the_title(),
			'subtitle' => has_excerpt() ? get_the_excerpt() : '',
		)
	);
}

get_footer();
