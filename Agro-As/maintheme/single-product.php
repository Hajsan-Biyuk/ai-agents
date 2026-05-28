<?php
/**
 * Single product template
 *
 * @package maintheme
 */

get_header();

while ( have_posts() ) {
	the_post();
	get_template_part( 'template-parts/product/content' );
}

get_footer();
