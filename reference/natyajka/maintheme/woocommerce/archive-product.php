<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.6.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

?>
<section class="page-catalog">
	<div class="container">
		<?php
		/**
		 * Hook: woocommerce_before_main_content.
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 * @hooked WC_Structured_Data::generate_website_data() - 30
		 */
		remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
		do_action( 'woocommerce_before_main_content' );
		?>

		<ul class="breadcrumbs">
			<li><a href="<?php echo home_url('/'); ?>">Главная</a></li>
			<li>Каталог</li>
		</ul>

		<div class="row">
			<div class="col-lg-3">
				<h1 class="title-catalog">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14 18C14 17.4477 14.4477 17 15 17C15.5523 17 16 17.4477 16 18C16 18.5523 15.5523 19 15 19C14.4477 19 14 18.5523 14 18Z" stroke="#7A9C52" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /><path d="M8 18C8 17.4477 8.44772 17 9 17C9.55228 17 10 17.4477 10 18C10 18.5523 9.55228 19 9 19C8.44772 19 8 18.5523 8 18Z" stroke="#7A9C52" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /><path d="M14 12C14 11.4477 14.4477 11 15 11C15.5523 11 16 11.4477 16 12C16 12.5523 15.5523 13 15 13C14.4477 13 14 12.5523 14 12Z" stroke="#7A9C52" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /><path d="M8 12C8 11.4477 8.44772 11 9 11C9.55228 11 10 11.4477 10 12C10 12.5523 9.55228 13 9 13C8.44772 13 8 12.5523 8 12Z" stroke="#7A9C52" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /><path d="M14 6C14 5.44772 14.4477 5 15 5C15.5523 5 16 5.44772 16 6C16 6.55228 15.5523 7 15 7C14.4477 7 14 6.55228 14 6Z" stroke="#7A9C52" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /><path d="M8 6C8 5.44772 8.44772 5 9 5C9.55228 5 10 5.44772 10 6C10 6.55228 9.55228 7 9 7C8.44772 7 8 6.55228 8 6Z" stroke="#7A9C52" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
					каталог 
					<i class="far fa-filter"></i>
				</h1>
				<ul class="nav-catalog">
					<?php 
					wp_list_categories(array(
						'taxonomy'   => 'product_cat',
						'title_li'   => '',
						'hide_empty' => false,
					));
					?>
				</ul>
			</div>
			
			<div class="col-lg-9">
				<div class="search-page">
					<?php echo do_shortcode('[wpdreams_ajaxsearchlite]'); ?>
					<!-- <form>
						<input type="text" placeholder="Поиск" required>
						<button class="search-page__btn"></button>
					</form> -->
				</div>
				
				<?php
				if ( woocommerce_product_loop() ) {

					// Isolate notices from the flex layout
					remove_action( 'woocommerce_before_shop_loop', 'woocommerce_output_all_notices', 10 );
					woocommerce_output_all_notices();
					?>
					
					<div class="catalog-controls">
						<?php
						/**
						 * Hook: woocommerce_before_shop_loop.
						 *
						 * @hooked woocommerce_result_count - 20
						 * @hooked woocommerce_catalog_ordering - 30
						 */
						do_action( 'woocommerce_before_shop_loop' );
						?>
					</div>
					
					<div class="row row--main">
						<?php
						if ( wc_get_loop_prop( 'total' ) ) {
							while ( have_posts() ) {
								the_post();

								/**
								 * Hook: woocommerce_shop_loop.
								 */
								do_action( 'woocommerce_shop_loop' );

								wc_get_template_part( 'content', 'product' );
							}
						}
						?>
					</div>
					
					<?php
					/**
					 * We output our custom pagination instead of standard WC pagination
					 */
					?>
					<div class="pager">
						<?php 
						echo paginate_links( array(
							'prev_text' => '<i class="far fa-long-arrow-left"></i>',
							'next_text' => '<i class="far fa-long-arrow-right"></i>',
						)); 
						
						// Fire the after_shop_loop hook, but prevent standard WC pagination from duplicating ours.
						remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );
						do_action( 'woocommerce_after_shop_loop' );
						?>
					</div>

					<?php
				} else {
					/**
					 * Hook: woocommerce_no_products_found.
					 *
					 * @hooked wc_no_products_found - 10
					 */
					do_action( 'woocommerce_no_products_found' );
				}

				/**
				 * Hook: woocommerce_after_main_content.
				 *
				 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
				 */
				remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
				do_action( 'woocommerce_after_main_content' );
				?>
			</div>
		</div>
	</div>
</section>
<?php

get_footer( 'shop' );
