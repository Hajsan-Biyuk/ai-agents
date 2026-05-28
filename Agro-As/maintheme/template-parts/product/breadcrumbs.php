<?php
/**
 * Product breadcrumbs
 *
 * @package maintheme
 */

$catalog_url = maintheme_product_catalog_url();
?>
<nav class="breadcrumbs" aria-label="Хлебные крошки">
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Главная</a>
	<span class="breadcrumbs__sep">/</span>
	<a href="<?php echo esc_url( $catalog_url ); ?>">Каталог</a>
	<span class="breadcrumbs__sep">/</span>
	<span class="breadcrumbs__current"><?php the_title(); ?></span>
</nav>
