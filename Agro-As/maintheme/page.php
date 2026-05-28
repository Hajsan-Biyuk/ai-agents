<?php
/**
 * Default page template.
 *
 * @package maintheme
 */

get_header();

while ( have_posts() ) {
	the_post();
	get_template_part( 'template-parts/page/content' );
}

get_footer();
