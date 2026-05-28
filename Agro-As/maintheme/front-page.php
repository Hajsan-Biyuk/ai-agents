<?php
/**
 * Front page template
 *
 * @package maintheme
 */
get_header();

get_template_part( 'template-parts/front/billbord' );
get_template_part( 'template-parts/front/advantages' );
get_template_part( 'template-parts/front/categories' );
get_template_part( 'template-parts/front/products' );
get_template_part( 'template-parts/front/wholesale' );
get_template_part( 'template-parts/front/steps' );
get_template_part( 'template-parts/front/delivery' );
get_template_part( 'template-parts/front/faq-partners' );
get_template_part( 'template-parts/front/contacts' );

get_footer();
