<?php
/**
 * Product hero: gallery, info, buy
 *
 * @package maintheme
 */

$t = get_template_directory_uri();

$post_id    = get_the_ID();
$gallery    = function_exists( 'get_field' ) ? get_field( 'galereya', $post_id ) : array();
$gallery    = is_array( $gallery ) ? array_values( array_filter( $gallery ) ) : array();
$tags       = function_exists( 'get_field' ) ? get_field( 'tegi', $post_id ) : array();
$status_key = function_exists( 'get_field' ) ? get_field( 'status_nalichiya', $post_id ) : 'v_nalichii';
$volume_label = function_exists( 'get_field' ) ? get_field( 'nazvanie_bloka_obema', $post_id ) : '';
$variations = maintheme_get_product_variations( $post_id );
$specs      = maintheme_get_product_specs( $post_id );
$specs_count = count( $specs );

$active_variation = null;
$active_volume    = '';
$active_price     = '';

if ( ! empty( $variations['items'] ) && null !== $variations['active'] ) {
	$active_variation = $variations['items'][ $variations['active'] ];
	$active_volume    = maintheme_product_variation_volume_text( $active_variation );
	$active_price     = $active_variation['cena'] ?? '';
}

$status_class = maintheme_product_status_class( $status_key );
$status_text  = maintheme_product_status_label( $status_key );
?>
<div class="row align-items-start product-page__hero">
	<?php if ( ! empty( $gallery ) ) { ?>
		<div class="col-12 col-xl-5 product-page__gallery-col">
			<div class="product-page__gallery<?php echo count( $gallery ) > 1 ? ' product-page__gallery--slider' : ''; ?>">
				<?php
				$gallery_group = 'product-gallery-' . $post_id;

				foreach ( $gallery as $image ) {
					$img_url   = maintheme_img_url( $image );
					$img_alt   = maintheme_img_alt( $image, get_the_title() );

					if ( ! maintheme_is_filled( $img_url ) ) {
						continue;
					}
					?>
					<div class="product-page__gallery-slide">
						<a
							href="<?php echo esc_url( $img_url ); ?>"
							class="product-page__gallery-link fancybox"
							data-fancybox="<?php echo esc_attr( $gallery_group ); ?>"
							data-caption="<?php echo esc_attr( $img_alt ); ?>"
						>
							<img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $img_alt ); ?>">
						</a>
					</div>
				<?php } ?>
			</div>
		</div>
	<?php } ?>

	<div class="col-12 col-xl-4 product-page__info-col">
		<?php if ( is_array( $tags ) && ! empty( $tags ) ) { ?>
			<div class="product-page__tags">
				<?php
				foreach ( $tags as $tag ) {
					if ( ! is_array( $tag ) || ! maintheme_is_filled( $tag['tekst'] ?? '' ) ) {
						continue;
					}
					?>
					<span class="product-page__tag"><?php echo esc_html( $tag['tekst'] ); ?></span>
				<?php } ?>
			</div>
		<?php } ?>

		<h1 class="product-page__title"><?php the_title(); ?></h1>

		<div class="product-page__status <?php echo esc_attr( $status_class ); ?>">
			<span class="product-page__status-dot"></span>
			<?php echo esc_html( $status_text ); ?>
		</div>

		<?php if ( ! empty( $variations['items'] ) ) { ?>
			<div class="product-page__volume">
				<div class="row">
					<?php if ( maintheme_is_filled( $volume_label ) || maintheme_is_filled( $active_volume ) ) { ?>
						<div class="col-12">
							<p class="product-page__volume-label">
								<?php if ( maintheme_is_filled( $volume_label ) ) { ?>
									<?php echo esc_html( rtrim( trim( $volume_label ), ':' ) ); ?>:
								<?php } ?>
								<?php if ( maintheme_is_filled( $active_volume ) ) { ?>
									<strong><?php echo esc_html( $active_volume ); ?></strong>
								<?php } ?>
							</p>
						</div>
					<?php } ?>
					<div class="col-12">
						<div class="product-page__volume-list">
							<?php foreach ( $variations['items'] as $index => $item ) { ?>
								<?php
								if ( ! is_array( $item ) || ! maintheme_is_filled( $item['znachenie'] ?? '' ) ) {
									continue;
								}

								$btn_class   = 'product-page__volume-item';
								$volume_text = maintheme_product_variation_volume_text( $item );
								$price_text  = $item['cena'] ?? '';

								if ( (int) $index === (int) $variations['active'] ) {
									$btn_class .= ' active';
								}
								?>
								<button
									type="button"
									class="<?php echo esc_attr( $btn_class ); ?>"
									data-volume="<?php echo esc_attr( $volume_text ); ?>"
									data-variation-index="<?php echo esc_attr( (string) $index ); ?>"
									<?php if ( maintheme_is_filled( $price_text ) ) { ?>
										data-price="<?php echo esc_attr( $price_text ); ?>"
									<?php } ?>
								><?php echo esc_html( $item['znachenie'] ); ?></button>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>

		<?php if ( $specs_count > 0 ) { ?>
			<div class="product-page__specs-preview">
				<div class="product-page__specs-table">
					<?php
					foreach ( $specs as $index => $row ) {
						$row_class = 'product-page__specs-row';

						if ( $index >= 6 ) {
							$row_class .= ' product-page__specs-row--hidden';
						}
						?>
						<div class="<?php echo esc_attr( $row_class ); ?>">
							<?php if ( maintheme_is_filled( $row['nazvanie'] ?? '' ) ) { ?>
								<span class="product-page__specs-name"><?php echo esc_html( $row['nazvanie'] ); ?></span>
							<?php } ?>
							<?php if ( maintheme_is_filled( $row['znachenie'] ?? '' ) ) { ?>
								<span class="product-page__specs-value"><?php echo $row['znachenie']; ?></span>
							<?php } ?>
						</div>
					<?php } ?>
				</div>
				<?php if ( $specs_count > 6 ) { ?>
					<button type="button" class="product-page__specs-all" data-label-show="Все характеристики" data-label-hide="Скрыть">Все характеристики</button>
				<?php } ?>
			</div>
		<?php } ?>
	</div>

	<div class="col-12 col-xl-3 product-page__buy-col">
		<aside class="product-page__buy">
			<div class="row align-items-center no-gutters product-page__buy-top">
				<?php if ( maintheme_is_filled( $active_price ) ) { ?>
					<div class="col-auto product-page__price-col">
						<div class="product-page__price"><?php echo esc_html( $active_price ); ?></div>
					</div>
				<?php } ?>
				<div class="col product-page__qty-col">
					<div class="product-page__qty">
						<button type="button" class="product-page__qty-btn product-page__qty-btn--minus" aria-label="Уменьшить количество">
							<img src="<?php echo esc_url( $t ); ?>/img/icon-qty-minus.svg" width="24" height="24" alt="">
						</button>
						<input type="text" class="product-page__qty-input" value="1" readonly inputmode="numeric" aria-label="Количество">
						<button type="button" class="product-page__qty-btn product-page__qty-btn--plus" aria-label="Увеличить количество">
							<img src="<?php echo esc_url( $t ); ?>/img/icon-qty-plus.svg" width="24" height="24" alt="">
						</button>
					</div>
				</div>
			</div>
			<button
				type="button"
				class="btn-main product-page__order js-add-to-cart"
				data-product-id="<?php echo esc_attr( (string) $post_id ); ?>"
				data-variation="<?php echo esc_attr( (string) ( null !== $variations['active'] ? $variations['active'] : 0 ) ); ?>"
				data-quantity="1"
				data-label="В корзину"
			>В корзину</button>
		</aside>
	</div>
</div>
