<?php
/**
 * Wholesale page — условия сотрудничества
 *
 * @package maintheme
 */
$sect = get_field( 'usloviya' );

if ( ! maintheme_section_show( $sect ) ) {
	return;
}
?>
<section class="wholesale-page-tiers">
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
		<?php if ( ! empty( $sect['tarify'] ) && is_array( $sect['tarify'] ) ) { ?>
			<div class="row wholesale-page-tiers__row">
				<?php foreach ( $sect['tarify'] as $tier ) { ?>
					<?php
					$badge      = $tier['nazvanie'] ?? '';
					$badge_cvet = $tier['cvet_badge'] ?? 'cream';
					$skidka     = $tier['skidka'] ?? '';
					$volume     = $tier['objom'] ?? '';
					$btn        = $tier['knopka'] ?? '';
					$btn_link   = $tier['knopka_ssylka'] ?? '';
					$btn_stil   = $tier['knopka_stil'] ?? 'outline';
					$featured   = ! empty( $tier['vydelennyj'] );
					$skidka_acc = ! empty( $tier['skidka_akcent'] );
					$punkty     = ! empty( $tier['punkty'] ) && is_array( $tier['punkty'] ) ? $tier['punkty'] : array();

					if ( ! maintheme_is_filled( $badge ) && ! maintheme_is_filled( $skidka ) && ! maintheme_is_filled( $volume ) && empty( $punkty ) ) {
						continue;
					}
					?>
					<div class="col-lg-4 wholesale-page-tiers__col">
						<div class="wholesale-page-tier<?php echo $featured ? ' wholesale-page-tier--featured' : ''; ?>">
							<?php if ( maintheme_is_filled( $badge ) ) { ?>
								<span class="wholesale-page-tier__badge wholesale-page-tier__badge--<?php echo esc_attr( $badge_cvet ); ?>"><?php echo esc_html( $badge ); ?></span>
							<?php } ?>
							<?php if ( maintheme_is_filled( $skidka ) ) { ?>
								<div class="wholesale-page-tier__discount<?php echo $skidka_acc ? ' wholesale-page-tier__discount--accent' : ''; ?>"><?php echo esc_html( $skidka ); ?></div>
							<?php } ?>
							<?php if ( maintheme_is_filled( $volume ) ) { ?>
								<div class="wholesale-page-tier__volume"><?php echo esc_html( $volume ); ?></div>
							<?php } ?>
							<?php if ( ! empty( $punkty ) ) { ?>
								<ul class="wholesale-page-tier__list">
									<?php foreach ( $punkty as $punkt ) { ?>
										<?php if ( ! maintheme_is_filled( $punkt['tekst'] ?? '' ) ) { continue; } ?>
										<li><?php echo esc_html( $punkt['tekst'] ); ?></li>
									<?php } ?>
								</ul>
							<?php } ?>
							<?php if ( maintheme_is_filled( $btn ) ) { ?>
								<?php
								$btn_class = 'btn-main wholesale-page-tier__btn';
								if ( 'yellow' === $btn_stil ) {
									$btn_class .= ' btn-main--yellow';
								} else {
									$btn_class .= ' btn-main--outline';
								}
								if ( ! maintheme_is_filled( $btn_link ) || '#modal-application' === $btn_link ) {
									$btn_class .= ' fancybox';
								}
								?>
								<a href="<?php echo esc_url( maintheme_is_filled( $btn_link ) ? $btn_link : '#modal-application' ); ?>" class="<?php echo esc_attr( $btn_class ); ?>"><?php echo esc_html( $btn ); ?></a>
							<?php } ?>
						</div>
					</div>
				<?php } ?>
			</div>
		<?php } ?>
	</div>
</section>
