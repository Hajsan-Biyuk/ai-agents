<?php
/**
 * Front — опт
 *
 * @package maintheme
 */
$t    = get_template_directory_uri();
$sect = get_field( 'opt' );

if ( ! maintheme_section_show( $sect ) ) {
	return;
}

$benefits   = ! empty( $sect['preimushhestva'] ) && is_array( $sect['preimushhestva'] ) ? $sect['preimushhestva'] : array();
$benefits_l = array();
$benefits_r = array();

if ( ! empty( $benefits ) ) {
	$half       = (int) ceil( count( $benefits ) / 2 );
	$benefits_l = array_slice( $benefits, 0, $half );
	$benefits_r = array_slice( $benefits, $half );
}

$media = $sect['izobrazhenie'] ?? null;
?>
<section class="wholesale" id="wholesale">
	<div class="container-fluid container-main">
		<div class="row wholesale__inner align-items-stretch">
			<?php if ( maintheme_is_filled( $media ) ) { ?>
				<div class="col-lg-5 col-md-12 wholesale__media">
					<div class="wholesale__media-inner">
						<img src="<?php echo esc_url( maintheme_img_url( $media ) ); ?>" alt="<?php echo esc_attr( maintheme_img_alt( $media ) ); ?>">
					</div>
				</div>
			<?php } ?>
			<div class="col-lg-7 col-md-12 wholesale__panel">
				<div class="wholesale__panel-main">
					<?php if ( maintheme_is_filled( $sect['zagolovok'] ?? '' ) || maintheme_is_filled( $sect['podzagolovok'] ?? '' ) ) { ?>
						<div class="head-section head-section--wholesale">
							<?php if ( maintheme_is_filled( $sect['zagolovok'] ?? '' ) ) { ?>
								<h2 class="title-section"><?php echo esc_html( $sect['zagolovok'] ); ?></h2>
							<?php } ?>
							<?php if ( maintheme_is_filled( $sect['podzagolovok'] ?? '' ) ) { ?>
								<p class="descr-section"><?php echo esc_html( $sect['podzagolovok'] ); ?></p>
							<?php } ?>
						</div>
					<?php } ?>
					<?php if ( ! empty( $sect['tipy'] ) && is_array( $sect['tipy'] ) ) { ?>
						<div class="row wholesale__types">
							<?php foreach ( $sect['tipy'] as $type ) { ?>
								<?php
								$cvet  = $type['cvet'] ?? 'green';
								$title = $type['zagolovok'] ?? '';
								$icon  = $type['ikonka'] ?? null;
								if ( ! maintheme_is_filled( $title ) && ! maintheme_is_filled( $icon ) ) {
									continue;
								}
								?>
								<div class="wholesale__types-col">
									<div class="wholesale-card wholesale-card--<?php echo esc_attr( $cvet ); ?>">
										<?php if ( maintheme_is_filled( $icon ) ) { ?>
											<img src="<?php echo esc_url( maintheme_img_url( $icon ) ); ?>" alt="<?php echo esc_attr( maintheme_img_alt( $icon ) ); ?>" class="wholesale-card__icon">
										<?php } ?>
										<?php if ( maintheme_is_filled( $title ) ) { ?>
											<div class="wholesale-card__title"><?php echo esc_html( $title ); ?></div>
										<?php } ?>
									</div>
								</div>
							<?php } ?>
						</div>
					<?php } ?>
					<?php if ( ! empty( $benefits ) ) { ?>
						<div class="row wholesale__benefits">
							<?php foreach ( array( $benefits_l, $benefits_r ) as $col_items ) { ?>
								<?php if ( empty( $col_items ) ) { continue; } ?>
								<div class="wholesale__benefits-col">
									<?php foreach ( $col_items as $benefit ) { ?>
										<?php if ( ! maintheme_is_filled( $benefit['tekst'] ?? '' ) ) { continue; } ?>
										<div class="wholesale-benefit<?php echo ! empty( $benefit['shirokij'] ) ? ' wholesale-benefit--wide' : ''; ?>">
											<span class="wholesale-benefit__icon"><img src="<?php echo esc_url( $t ); ?>/img/icon-wholesale-check.svg?ver=3.1" alt=""></span>
											<span class="wholesale-benefit__text"><?php echo esc_html( $benefit['tekst'] ); ?></span>
										</div>
									<?php } ?>
								</div>
							<?php } ?>
						</div>
					<?php } ?>
				</div>
				<?php if ( maintheme_is_filled( $sect['knopka_1'] ?? '' ) || maintheme_is_filled( $sect['knopka_2'] ?? '' ) ) { ?>
					<div class="wholesale__actions">
						<?php if ( maintheme_is_filled( $sect['knopka_1'] ?? '' ) ) { ?>
							<a href="#modal-application" class="btn-main btn-main--wholesale fancybox"><?php echo esc_html( $sect['knopka_1'] ); ?> <img src="<?php echo esc_url( $t ); ?>/img/icon-arrow.svg" alt=""></a>
						<?php } ?>
						<?php if ( maintheme_is_filled( $sect['knopka_2'] ?? '' ) ) { ?>
							<a href="#modal-application" class="btn-main btn-main--yellow btn-main--wholesale fancybox"><?php echo esc_html( $sect['knopka_2'] ); ?> <img src="<?php echo esc_url( $t ); ?>/img/icon-arrow.svg" alt=""></a>
						<?php } ?>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
</section>
