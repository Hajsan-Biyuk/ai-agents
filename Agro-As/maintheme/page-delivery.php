<?php
/*
Template Name: Доставка и оплата
*/
get_header();
?>
<section class="delivery-page">
	<div class="container-fluid container-main">
		<nav class="breadcrumbs" aria-label="Хлебные крошки">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Главная</a>
			<span class="breadcrumbs__sep">/</span>
			<span class="breadcrumbs__current"><?php the_title(); ?></span>
		</nav>
		<?php
		get_template_part( 'template-parts/page/delivery', 'payment' );
		get_template_part( 'template-parts/page/delivery', 'shipping' );
		?>
	</div>
</section>
<?php
get_footer();
