<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package maintheme
 */

get_header();
?>
<section class="page-catalog">
	<div class="container">		
		<ul class="breadcrumbs">
			<li><a href="/">Главная</a></li>		
			<li>Поиск</li>
		</ul>
		<h1 class="title-section">Результат поиска: <span class="search-page"><?php echo get_search_query(); ?></span></h1>	
			
				
				<div class="row row--main">
			<?php if ( have_posts() ) : ?>
							<?php
					/* Start the Loop */
					while ( have_posts() ) :
						the_post(); ?>					
						<?php include('woocommerce/content-product.php'); ?>					
				<?php endwhile; ?>	
				<?php else : ?>
								Ничего не найдено;
						<?php endif; ?>			
				</div>	
		</div>
	</div>
</section>
<?php
get_footer();

