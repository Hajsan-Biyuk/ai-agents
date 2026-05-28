<?php
/**
 * Cart page template.
 *
 * @package maintheme
 *
 * Template Name: Корзина
 */

get_header();
?>
<section class="catalog-page basket-page">
	<div class="container-fluid container-main">
		<h1 class="catalog-page__title title-section"><?php the_title(); ?></h1>
		<div class="basket-page__content">
			<?php echo do_shortcode( '[basket-order-form]' ); ?>
		</div>
	</div>
</section>
<?php
get_footer();
