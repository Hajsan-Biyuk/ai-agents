<?php
/**
 * Footer — каталог product_cat (две колонки).
 *
 * @package maintheme
 */

$columns = maintheme_get_footer_catalog_columns();

if ( empty( $columns[0] ) && empty( $columns[1] ) ) {
	return;
}
?>
<div class="footer__col-title">Каталог</div>
<div class="row footer__catalog">
	<?php foreach ( $columns as $col_items ) { ?>
		<?php if ( empty( $col_items ) ) { ?>
			<?php continue; ?>
		<?php } ?>
		<div class="col-lg-6 col-md-6 footer__catalog-col">
			<?php foreach ( $col_items as $category ) { ?>
				<div class="footer__category">
					<a href="<?php echo esc_url( $category['url'] ); ?>" class="footer__category-title"><?php echo esc_html( $category['title'] ); ?></a>
					<?php if ( ! empty( $category['children'] ) ) { ?>
						<ul class="footer__sub">
							<?php foreach ( $category['children'] as $child ) { ?>
								<li><a href="<?php echo esc_url( $child['url'] ); ?>"><?php echo esc_html( $child['name'] ); ?></a></li>
							<?php } ?>
						</ul>
					<?php } ?>
				</div>
			<?php } ?>
		</div>
	<?php } ?>
</div>
