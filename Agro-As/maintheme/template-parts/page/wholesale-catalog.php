<?php
/**
 * Wholesale page — каталог
 *
 * @package maintheme
 */
$sect = get_field( 'katalog' );

if ( ! maintheme_section_show( $sect ) ) {
	return;
}
?>
<section class="wholesale-page-catalog">
	<div class="container-fluid container-main">
		<?php if ( maintheme_is_filled( $sect['zagolovok'] ?? '' ) || maintheme_is_filled( $sect['opisanie'] ?? '' ) ) { ?>
			<div class="head-section">
				<?php if ( maintheme_is_filled( $sect['zagolovok'] ?? '' ) ) { ?>
					<h2 class="title-section"><?php echo esc_html( $sect['zagolovok'] ); ?></h2>
				<?php } ?>
				<?php if ( maintheme_is_filled( $sect['opisanie'] ?? '' ) ) { ?>
					<p class="descr-section"><?php echo esc_html( $sect['opisanie'] ); ?></p>
				<?php } ?>
			</div>
		<?php } ?>
		<?php if ( ! empty( $sect['kategorii'] ) && is_array( $sect['kategorii'] ) ) { ?>
			<div class="row wholesale-page-catalog__row">
				<?php foreach ( $sect['kategorii'] as $cat ) { ?>
					<?php
					$title    = $cat['zagolovok'] ?? '';
					$items    = ! empty( $cat['pozicii'] ) && is_array( $cat['pozicii'] ) ? $cat['pozicii'] : array();
					$link_txt = $cat['tekst_ssylki'] ?? '';

					if ( ! maintheme_is_filled( $title ) && empty( $items ) ) {
						continue;
					}
					?>
					<div class="col-lg-4 col-md-6 wholesale-page-catalog__col">
						<div class="wholesale-page-catalog-card">
							<?php if ( maintheme_is_filled( $title ) ) { ?>
								<h3 class="wholesale-page-catalog-card__title"><?php echo esc_html( $title ); ?></h3>
							<?php } ?>
							<?php if ( ! empty( $items ) ) { ?>
								<ul class="wholesale-page-catalog-card__list">
									<?php foreach ( $items as $item ) { ?>
										<?php if ( ! maintheme_is_filled( $item['nazvanie'] ?? '' ) ) { continue; } ?>
										<li><?php echo esc_html( $item['nazvanie'] ); ?></li>
									<?php } ?>
								</ul>
							<?php } ?>
							<?php if ( maintheme_is_filled( $link_txt ) ) { ?>
								<a href="#modal-application" class="wholesale-page-catalog-card__link fancybox"><?php echo esc_html( $link_txt ); ?></a>
							<?php } ?>
						</div>
					</div>
				<?php } ?>
			</div>
		<?php } ?>
	</div>
</section>
