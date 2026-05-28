<?php
/**
 * Front — преимущества
 *
 * @package maintheme
 */
$sect = get_field( 'preimushhestva' );

if ( ! maintheme_section_show( $sect ) ) {
	return;
}
?>
<section class="advantages" id="advantages">
	<div class="container-fluid container-main">
		<?php if ( maintheme_is_filled( $sect['zagolovok'] ?? '' ) ) { ?>
			<div class="head-section">
				<h2 class="title-section"><?php echo esc_html( $sect['zagolovok'] ); ?></h2>
			</div>
		<?php } ?>
		<?php if ( ! empty( $sect['elementy'] ) && is_array( $sect['elementy'] ) ) { ?>
			<div class="row advantages__row">
				<?php foreach ( $sect['elementy'] as $item ) { ?>
					<?php
					$tip   = $item['tip'] ?? '';
					$cvet  = $item['cvet'] ?? 'cream';
					$col   = in_array( $tip, array( 'foto_shirokaya', 'kartochka_shirokaya' ), true ) ? 'advantages__col-wide' : 'advantages__col-square';
					$img   = $item['izobrazhenie'] ?? null;
					$icon  = $item['ikonka'] ?? null;
					$title = $item['zagolovok'] ?? '';
					$text  = $item['tekst'] ?? '';

					if ( in_array( $tip, array( 'foto', 'foto_shirokaya' ), true ) ) {
						if ( ! maintheme_is_filled( $img ) ) {
							continue;
						}
					} elseif ( ! maintheme_is_filled( $title ) && ! maintheme_is_filled( $text ) ) {
						continue;
					}
					?>
					<div class="col-12 col-md-6 <?php echo esc_attr( $col ); ?>">
						<?php if ( in_array( $tip, array( 'foto', 'foto_shirokaya' ), true ) ) { ?>
							<div class="item-advantage__photo">
								<img src="<?php echo esc_url( maintheme_img_url( $img ) ); ?>" alt="<?php echo esc_attr( maintheme_img_alt( $img ) ); ?>">
							</div>
						<?php } else { ?>
							<div class="item-advantage item-advantage--<?php echo esc_attr( $cvet ); ?><?php echo ( 'kartochka_shirokaya' === $tip ) ? ' item-advantage--wide' : ''; ?>">
								<?php if ( maintheme_is_filled( $icon ) ) { ?>
									<img src="<?php echo esc_url( maintheme_img_url( $icon ) ); ?>" alt="<?php echo esc_attr( maintheme_img_alt( $icon ) ); ?>" class="item-advantage__icon">
								<?php } ?>
								<div>
									<?php if ( maintheme_is_filled( $title ) ) { ?>
										<div class="item-advantage__title"><?php echo esc_html( $title ); ?></div>
									<?php } ?>
									<?php if ( maintheme_is_filled( $text ) ) { ?>
										<p class="item-advantage__text"><?php echo esc_html( $text ); ?></p>
									<?php } ?>
								</div>
							</div>
						<?php } ?>
					</div>
				<?php } ?>
			</div>
		<?php } ?>
	</div>
</section>
