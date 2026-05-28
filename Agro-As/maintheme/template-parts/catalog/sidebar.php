<?php
/**
 * Catalog sidebar with product categories tree.
 *
 * @package maintheme
 */

$current = isset( $args['term'] ) ? $args['term'] : get_queried_object();

if ( ! ( $current instanceof WP_Term ) ) {
	return;
}

$parents = get_terms(
	array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => false,
		'parent'     => 0,
		'orderby'    => 'name',
		'order'      => 'ASC',
	)
);

if ( is_wp_error( $parents ) || empty( $parents ) ) {
	return;
}
?>
<aside class="catalog-sidebar">
	<?php
	foreach ( $parents as $parent ) {
		$children = get_terms(
			array(
				'taxonomy'   => 'product_cat',
				'hide_empty' => false,
				'parent'     => (int) $parent->term_id,
				'orderby'    => 'name',
				'order'      => 'ASC',
			)
		);

		if ( is_wp_error( $children ) ) {
			$children = array();
		}

		$has_children   = ! empty( $children );
		$parent_link    = get_term_link( $parent );
		$is_open        = $has_children && maintheme_product_cat_sidebar_is_open( $parent, $current );
		$is_parent_active = (int) $current->term_id === (int) $parent->term_id;
		$classes        = array( 'catalog-sidebar__item' );

		if ( $is_open ) {
			$classes[] = 'catalog-sidebar__item--open';
		}

		if ( is_wp_error( $parent_link ) ) {
			continue;
		}

		$name_classes = array( 'catalog-sidebar__name' );

		if ( $is_parent_active ) {
			$name_classes[] = 'active';
		}
		?>
		<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
			<div class="catalog-sidebar__head">
				<a href="<?php echo esc_url( $parent_link ); ?>" class="<?php echo esc_attr( implode( ' ', $name_classes ) ); ?>"><?php echo esc_html( $parent->name ); ?></a>
				<?php if ( $has_children ) { ?>
					<button type="button" class="catalog-sidebar__toggle" aria-expanded="<?php echo $is_open ? 'true' : 'false'; ?>" aria-label="<?php echo esc_attr( 'Подкатегории: ' . $parent->name ); ?>">
						<i class="fal fa-chevron-down" aria-hidden="true"></i>
					</button>
				<?php } ?>
			</div>
			<?php if ( $has_children ) { ?>
				<div class="catalog-sidebar__body">
					<ul class="catalog-sidebar__sub">
						<?php
						foreach ( $children as $child ) {
							$link = get_term_link( $child );

							if ( is_wp_error( $link ) ) {
								continue;
							}

							$is_active = (int) $current->term_id === (int) $child->term_id;
							?>
							<li>
								<a href="<?php echo esc_url( $link ); ?>"<?php echo $is_active ? ' class="active"' : ''; ?>><?php echo esc_html( $child->name ); ?></a>
							</li>
							<?php
						}
						?>
					</ul>
				</div>
			<?php } ?>
		</div>
		<?php
	}
	?>
</aside>
