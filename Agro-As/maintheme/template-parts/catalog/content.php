<?php
/**
 * Catalog category page shell.
 *
 * @package maintheme
 */

$term = get_queried_object();

if ( ! ( $term instanceof WP_Term ) || 'product_cat' !== $term->taxonomy ) {
	return;
}

$sort = maintheme_get_catalog_sort();
?>
<section class="catalog-page">
	<div class="container-fluid container-main">
		<?php
		get_template_part(
			'template-parts/catalog/breadcrumbs',
			null,
			array(
				'term' => $term,
			)
		);
		?>

		<h1 class="catalog-page__title title-section"><?php echo esc_html( $term->name ); ?></h1>

		<div class="row catalog-page__layout">
			<div class="col-lg-3 catalog-page__sidebar-col">
				<a href="#" class="btn-main btn-main--outline btn-main_filter">Фильтр <i class="fal fa-filter" aria-hidden="true"></i></a>
				<?php
				get_template_part(
					'template-parts/catalog/sidebar',
					null,
					array(
						'term' => $term,
					)
				);
				?>
			</div>
			<div class="col-lg-9 catalog-page__content-col">
				<div class="catalog-page__sort">
					<div class="catalog-page__sort-field">
						<select id="catalog-sort" class="catalog-page__sort-select" name="sort" aria-label="Сортировка">
							<option value="default"<?php selected( $sort, 'default' ); ?>>По умолчанию</option>
							<option value="price"<?php selected( $sort, 'price' ); ?>>По цене</option>
							<option value="date"<?php selected( $sort, 'date' ); ?>>По дате</option>
							<option value="alpha"<?php selected( $sort, 'alpha' ); ?>>По алфавиту</option>
							<option value="stock"<?php selected( $sort, 'stock' ); ?>>По наличию</option>
						</select>
						<span class="catalog-page__sort-icon" aria-hidden="true"><i class="fal fa-chevron-down"></i></span>
					</div>
				</div>

				<?php if ( have_posts() ) { ?>
					<div class="row catalog-page__grid">
						<?php
						while ( have_posts() ) {
							the_post();
							?>
							<div class="col-xl-4 col-lg-4 col-md-6 catalog-page__col">
								<?php get_template_part( 'template-parts/product/card' ); ?>
							</div>
							<?php
						}
						?>
					</div>
					<?php get_template_part( 'template-parts/catalog/pagination' ); ?>
				<?php } else { ?>
					<p class="catalog-page__empty">В этой категории пока нет товаров.</p>
				<?php } ?>
			</div>
		</div>
	</div>
</section>
