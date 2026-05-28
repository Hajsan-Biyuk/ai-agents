<?php
/**
 * Service message page — 404, спасибо.
 *
 * @package maintheme
 *
 * @var array $args {
 *   @type string $modifier   Модификатор секции (404, thanks).
 *   @type string $breadcrumb Текущий пункт крошек.
 *   @type string $title      Заголовок (допускается <br>).
 *   @type string $descr      Подзаголовок.
 *   @type string $btn_text   Текст кнопки.
 *   @type string $btn_url    URL кнопки.
 * }
 */

$modifier   = isset( $args['modifier'] ) ? (string) $args['modifier'] : '';
$breadcrumb = isset( $args['breadcrumb'] ) ? (string) $args['breadcrumb'] : '';
$title      = isset( $args['title'] ) ? (string) $args['title'] : '';
$descr      = isset( $args['descr'] ) ? (string) $args['descr'] : '';
$btn_text   = isset( $args['btn_text'] ) ? (string) $args['btn_text'] : '';
$btn_url    = isset( $args['btn_url'] ) ? (string) $args['btn_url'] : home_url( '/' );

$section_class = 'service-page';

if ( maintheme_is_filled( $modifier ) ) {
	$section_class .= ' service-page--' . sanitize_html_class( $modifier );
}
?>
<section class="<?php echo esc_attr( $section_class ); ?>">
	<div class="container-fluid container-main">
		<nav class="breadcrumbs" aria-label="Хлебные крошки">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Главная</a>
			<?php if ( maintheme_is_filled( $breadcrumb ) ) { ?>
				<span class="breadcrumbs__sep">/</span>
				<span class="breadcrumbs__current"><?php echo esc_html( $breadcrumb ); ?></span>
			<?php } ?>
		</nav>
		<div class="service-page__inner">
			<?php if ( maintheme_is_filled( $title ) ) { ?>
				<h1 class="service-page__title title-section"><?php echo wp_kses( $title, array( 'br' => array() ) ); ?></h1>
			<?php } ?>
			<?php if ( maintheme_is_filled( $descr ) ) { ?>
				<p class="service-page__descr descr-section"><?php echo esc_html( $descr ); ?></p>
			<?php } ?>
			<?php if ( maintheme_is_filled( $btn_text ) && maintheme_is_filled( $btn_url ) ) { ?>
				<div class="service-page__actions">
					<a href="<?php echo esc_url( $btn_url ); ?>" class="btn-main"><?php echo esc_html( $btn_text ); ?></a>
				</div>
			<?php } ?>
		</div>
	</div>
</section>
