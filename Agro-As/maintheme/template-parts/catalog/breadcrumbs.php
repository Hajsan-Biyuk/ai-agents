<?php
/**
 * Catalog category breadcrumbs.
 *
 * @package maintheme
 */

$term = isset( $args['term'] ) ? $args['term'] : get_queried_object();

if ( ! ( $term instanceof WP_Term ) ) {
	return;
}

$catalog_url = maintheme_catalog_url();
$parent      = null;

if ( $term->parent ) {
	$parent = get_term( (int) $term->parent, 'product_cat' );

	if ( is_wp_error( $parent ) || ! ( $parent instanceof WP_Term ) ) {
		$parent = null;
	}
}
?>
<nav class="breadcrumbs" aria-label="Хлебные крошки">
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Главная</a>
	<span class="breadcrumbs__sep">/</span>
	<a href="<?php echo esc_url( $catalog_url ); ?>">Каталог</a>
	<?php if ( $parent ) { ?>
		<span class="breadcrumbs__sep">/</span>
		<a href="<?php echo esc_url( get_term_link( $parent ) ); ?>"><?php echo esc_html( $parent->name ); ?></a>
	<?php } ?>
	<span class="breadcrumbs__sep">/</span>
	<span class="breadcrumbs__current"><?php echo esc_html( $term->name ); ?></span>
</nav>
