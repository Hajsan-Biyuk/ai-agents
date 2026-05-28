<?php
/**
 * Front — FAQ и партнёры
 *
 * @package maintheme
 */
$t       = get_template_directory_uri();
$faq     = get_field( 'faq' );
$partner = get_field( 'partnery' );

$show_faq  = maintheme_section_show( $faq );
$show_part = maintheme_section_show( $partner );

if ( ! $show_faq && ! $show_part ) {
	return;
}
?>
<section class="faq-partners" id="faq">
	<div class="container-fluid container-main">
		<div class="row faq-partners__inner">
			<?php if ( $show_faq ) { ?>
				<div class="col-lg col-md-12 faq faq-partners__col-faq">
					<?php if ( maintheme_is_filled( $faq['zagolovok'] ?? '' ) ) { ?>
						<h2 class="title-section"><?php echo esc_html( $faq['zagolovok'] ); ?></h2>
					<?php } ?>
					<?php if ( ! empty( $faq['voprosy'] ) && is_array( $faq['voprosy'] ) ) { ?>
						<?php
						$faq_index = 0;
						foreach ( $faq['voprosy'] as $item ) {
							if ( ! maintheme_is_filled( $item['vopros'] ?? '' ) || ! maintheme_is_filled( $item['otvet'] ?? '' ) ) {
								continue;
							}
							?>
							<div class="item-question<?php echo 0 === $faq_index ? ' active' : ''; ?>">
								<div class="item-question__head">
									<div class="item-question__title"><?php echo esc_html( $item['vopros'] ); ?></div>
									<div class="item-question__toggle">
										<img src="<?php echo esc_url( $t ); ?>/img/icon-faq-plus.svg" alt="" class="icon-plus">
										<img src="<?php echo esc_url( $t ); ?>/img/icon-faq-minus.svg" alt="" class="icon-minus">
									</div>
								</div>
								<div class="item-question__answer"><?php echo esc_html( $item['otvet'] ); ?></div>
							</div>
							<?php
							++$faq_index;
						}
						?>
					<?php } ?>
				</div>
			<?php } ?>
			<?php if ( $show_part ) { ?>
				<div class="col-lg col-md-12 partners faq-partners__col-partners">
					<?php if ( maintheme_is_filled( $partner['zagolovok'] ?? '' ) ) { ?>
						<h2 class="title-section"><?php echo esc_html( $partner['zagolovok'] ); ?></h2>
					<?php } ?>
					<?php if ( ! empty( $partner['logotipy'] ) && is_array( $partner['logotipy'] ) ) { ?>
						<div class="partners__box">
							<div class="partners__list">
								<?php foreach ( $partner['logotipy'] as $logo ) { ?>
									<?php
									$img = $logo['izobrazhenie'] ?? null;
									if ( ! maintheme_is_filled( $img ) ) {
										continue;
									}
									?>
									<div class="partners__item">
										<img src="<?php echo esc_url( maintheme_img_url( $img ) ); ?>" alt="<?php echo esc_attr( maintheme_img_alt( $img, $logo['alt'] ?? '' ) ); ?>">
									</div>
								<?php } ?>
							</div>
						</div>
					<?php } ?>
				</div>
			<?php } ?>
		</div>
	</div>
</section>
