<?php
/**
 * Секция категорий (главная + landing каталога).
 *
 * @package maintheme
 *
 * @var array $args {
 *   @type string $context front|page
 *   @type string $title   Заголовок (для context page).
 *   @type string $subtitle Подзаголовок (для context page).
 * }
 */
$context  = isset( $args['context'] ) ? (string) $args['context'] : 'front';
$title    = '';
$subtitle = '';
$heading  = 'h2';

if ( 'front' === $context ) {
	$sect = get_field( 'kategorii' );

	if ( ! maintheme_section_show( $sect ) ) {
		return;
	}

	$title    = $sect['zagolovok'] ?? '';
	$subtitle = $sect['podzagolovok'] ?? '';
} else {
	$title    = isset( $args['title'] ) ? (string) $args['title'] : get_the_title();
	$subtitle = isset( $args['subtitle'] ) ? (string) $args['subtitle'] : '';
	$heading  = 'h1';
}

$categories = maintheme_get_front_product_categories();

if ( 'front' === $context && empty( $categories ) ) {
	return;
}
?>
<section class="categories<?php echo 'page' === $context ? ' categories--page' : ''; ?>" id="categories">
	<div class="container-fluid container-main">
		<?php if ( maintheme_is_filled( $title ) || maintheme_is_filled( $subtitle ) ) { ?>
			<div class="head-section">
				<?php if ( maintheme_is_filled( $title ) ) { ?>
					<<?php echo esc_attr( $heading ); ?> class="title-section"><?php echo esc_html( $title ); ?></<?php echo esc_attr( $heading ); ?>>
				<?php } ?>
				<?php if ( maintheme_is_filled( $subtitle ) ) { ?>
					<p class="descr-section"><?php echo esc_html( $subtitle ); ?></p>
				<?php } ?>
			</div>
		<?php } ?>
		<?php if ( ! empty( $categories ) ) { ?>
			<div class="row categories__row">
				<?php foreach ( $categories as $item ) { ?>
					<?php
					$card_title = $item['title'] ?? '';
					$img        = $item['image'] ?? null;
					$url        = $item['url'] ?? '';
					$size       = $item['size'] ?? 'sm';
					$col        = $item['col'] ?? 'col-lg-4';
					$children   = $item['children'] ?? array();

					if ( ! maintheme_is_filled( $card_title ) && ! maintheme_is_filled( $img ) ) {
						continue;
					}
					?>
					<div class="<?php echo esc_attr( $col ); ?> col-md-12">
						<div class="item-category item-category--<?php echo esc_attr( $size ); ?>">
							<?php if ( maintheme_is_filled( $img ) ) { ?>
								<div class="item-category__bg">
									<img src="<?php echo esc_url( maintheme_img_url( $img ) ); ?>" alt="<?php echo esc_attr( maintheme_img_alt( $img, $card_title ) ); ?>">
								</div>
							<?php } ?>
							<div class="item-category__content">
								<?php if ( maintheme_is_filled( $card_title ) && maintheme_is_filled( $url ) ) { ?>
									<a href="<?php echo esc_url( $url ); ?>" class="item-category__title"><?php echo esc_html( $card_title ); ?></a>
								<?php } elseif ( maintheme_is_filled( $card_title ) ) { ?>
									<div class="item-category__title"><?php echo esc_html( $card_title ); ?></div>
								<?php } ?>
								<?php if ( ! empty( $children ) ) { ?>
									<p class="item-category__list">
										<?php
										$child_links = array();

										foreach ( $children as $child ) {
											if ( empty( $child['name'] ) || empty( $child['url'] ) ) {
												continue;
											}

											$child_links[] = '<a href="' . esc_url( $child['url'] ) . '" class="item-category__sub-link">' . esc_html( $child['name'] ) . '</a>';
										}

										echo wp_kses_post( implode( '<br>', $child_links ) );
										?>
									</p>
								<?php } ?>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
		<?php } elseif ( 'page' === $context ) { ?>
			<p class="categories__empty">Категории пока не добавлены.</p>
		<?php } ?>
	</div>
</section>
