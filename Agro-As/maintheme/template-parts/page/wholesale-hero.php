<?php
/**
 * Wholesale page — hero
 *
 * @package maintheme
 */
$t    = get_template_directory_uri();
$sect = get_field( 'geroj' );

if ( ! maintheme_section_show( $sect ) ) {
	return;
}

$bg = $sect['izobrazhenie_fona'] ?? null;
?>
<section class="wholesale-page-hero">
	<?php if ( maintheme_is_filled( $bg ) ) { ?>
		<div class="wholesale-page-hero__bg">
			<img src="<?php echo esc_url( maintheme_img_url( $bg ) ); ?>" alt="<?php echo esc_attr( maintheme_img_alt( $bg ) ); ?>">
		</div>
	<?php } ?>
	<div class="container-fluid container-main">
		<nav class="breadcrumbs breadcrumbs--hero" aria-label="Хлебные крошки">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Главная</a>
			<span class="breadcrumbs__sep">/</span>
			<span class="breadcrumbs__current"><?php the_title(); ?></span>
		</nav>
		<div class="row wholesale-page-hero__head align-items-start">
			<div class="col-lg-7 wholesale-page-hero__content">
				<?php if ( maintheme_is_filled( $sect['zagolovok'] ?? '' ) ) { ?>
					<h1 class="wholesale-page-hero__title"><?php echo esc_html( $sect['zagolovok'] ); ?></h1>
				<?php } ?>
				<?php if ( maintheme_is_filled( $sect['opisanie'] ?? '' ) ) { ?>
					<p class="wholesale-page-hero__descr"><?php echo esc_html( $sect['opisanie'] ); ?></p>
				<?php } ?>
			</div>
			<?php if ( ! empty( $sect['statistika'] ) && is_array( $sect['statistika'] ) ) { ?>
				<div class="col-lg-auto wholesale-page-hero__stats-col">
					<div class="wholesale-page-hero__stats">
						<?php foreach ( $sect['statistika'] as $stat ) { ?>
							<?php
							$value = $stat['znachenie'] ?? '';
							$label = $stat['podpis'] ?? '';
							if ( ! maintheme_is_filled( $value ) && ! maintheme_is_filled( $label ) ) {
								continue;
							}
							?>
							<div class="wholesale-page-hero__stat">
								<?php if ( maintheme_is_filled( $value ) ) { ?>
									<div class="wholesale-page-hero__stat-value"><?php echo esc_html( $value ); ?></div>
								<?php } ?>
								<?php if ( maintheme_is_filled( $label ) ) { ?>
									<p class="wholesale-page-hero__stat-label"><?php echo esc_html( $label ); ?></p>
								<?php } ?>
							</div>
						<?php } ?>
					</div>
				</div>
			<?php } ?>
		</div>
		<?php
		$btn1      = $sect['knopka_1'] ?? '';
		$btn1_link = $sect['knopka_1_ssylka'] ?? '';
		$btn2      = $sect['knopka_2'] ?? '';
		$btn2_link = $sect['knopka_2_ssylka'] ?? '';
		if ( maintheme_is_filled( $btn1 ) || maintheme_is_filled( $btn2 ) ) {
			?>
			<div class="wholesale-page-hero__actions">
				<?php if ( maintheme_is_filled( $btn1 ) ) { ?>
					<a href="<?php echo esc_url( maintheme_is_filled( $btn1_link ) ? $btn1_link : '#modal-application' ); ?>" class="btn-main<?php echo ( ! maintheme_is_filled( $btn1_link ) || '#modal-application' === $btn1_link ) ? ' fancybox' : ''; ?>"><?php echo esc_html( $btn1 ); ?></a>
				<?php } ?>
				<?php if ( maintheme_is_filled( $btn2 ) ) { ?>
					<a href="<?php echo esc_url( maintheme_is_filled( $btn2_link ) ? $btn2_link : '#' ); ?>" class="btn-main btn-main--yellow"><?php echo esc_html( $btn2 ); ?></a>
				<?php } ?>
			</div>
		<?php } ?>
	</div>
</section>
