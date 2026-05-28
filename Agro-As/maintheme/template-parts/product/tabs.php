<?php
/**
 * Product tabs
 *
 * @package maintheme
 */

$post_id    = get_the_ID();
$specs      = maintheme_get_product_specs( $post_id );
$primenenie = function_exists( 'get_field' ) ? get_field( 'primenenie', $post_id ) : '';
$reviews    = function_exists( 'get_field' ) ? get_field( 'otzyvy_zaglushka', $post_id ) : '';
$content    = get_post_field( 'post_content', $post_id );
$content    = apply_filters( 'the_content', $content );

$tabs = array();

if ( maintheme_is_filled( $content ) ) {
	$tabs['description'] = 'Описание';
}

if ( maintheme_is_filled( $primenenie ) ) {
	$tabs['application'] = 'Применение';
}

if ( ! empty( $specs ) ) {
	$tabs['characteristics'] = 'Характеристики';
}

if ( maintheme_is_filled( $reviews ) ) {
	$tabs['reviews'] = 'Отзывы';
}

if ( empty( $tabs ) ) {
	return;
}

$tab_keys    = array_keys( $tabs );
$active_tab  = $tab_keys[0];
?>
<div class="product-page__tabs-wrap tabs-page">
	<ul class="product-page__tabs-nav">
		<?php foreach ( $tabs as $tab_id => $tab_label ) { ?>
			<li>
				<button type="button" class="product-page__tab<?php echo $tab_id === $active_tab ? ' active' : ''; ?>" data-tab="<?php echo esc_attr( $tab_id ); ?>"><?php echo esc_html( $tab_label ); ?></button>
			</li>
		<?php } ?>
	</ul>
	<div class="product-page__tabs-panels">
		<?php if ( isset( $tabs['description'] ) ) { ?>
			<div class="product-page__panel<?php echo 'description' === $active_tab ? ' active' : ''; ?>" data-tab="description">
				<div class="product-page__text">
					<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			</div>
		<?php } ?>

		<?php if ( isset( $tabs['application'] ) ) { ?>
			<div class="product-page__panel<?php echo 'application' === $active_tab ? ' active' : ''; ?>" data-tab="application">
				<div class="product-page__text">
					<?php echo wp_kses_post( $primenenie ); ?>
				</div>
			</div>
		<?php } ?>

		<?php if ( isset( $tabs['characteristics'] ) ) { ?>
			<div class="product-page__panel<?php echo 'characteristics' === $active_tab ? ' active' : ''; ?>" data-tab="characteristics">
				<div class="product-page__specs-table product-page__specs-table--full">
					<?php foreach ( $specs as $index => $row ) { ?>
						<div class="product-page__specs-row<?php echo 0 !== $index % 2 ? ' product-page__specs-row--alt' : ''; ?>">
							<?php if ( maintheme_is_filled( $row['nazvanie'] ?? '' ) ) { ?>
								<span class="product-page__specs-name"><?php echo esc_html( $row['nazvanie'] ); ?></span>
							<?php } ?>
							<?php if ( maintheme_is_filled( $row['znachenie'] ?? '' ) ) { ?>
								<span class="product-page__specs-value"><?php echo $row['znachenie']; ?></span>
							<?php } ?>
						</div>
					<?php } ?>
				</div>
			</div>
		<?php } ?>

		<?php if ( isset( $tabs['reviews'] ) ) { ?>
			<div class="product-page__panel<?php echo 'reviews' === $active_tab ? ' active' : ''; ?>" data-tab="reviews">
				<div class="product-page__text">
					<?php echo wp_kses_post( wpautop( $reviews ) ); ?>
				</div>
			</div>
		<?php } ?>
	</div>
</div>
