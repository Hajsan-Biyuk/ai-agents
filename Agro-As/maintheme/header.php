<?php
$t        = get_template_directory_uri();
$pochta   = maintheme_get_option( 'pochta' );
$telefon  = maintheme_get_option( 'telefon' );
$telegram = maintheme_get_option( 'telegram' );
$is_front = is_front_page();

$header_class = $is_front ? 'header default' : 'header';
$logo_class   = $is_front ? 'logo' : 'logo logo--inner';
$logo_src     = $is_front ? 'logo.svg' : 'logo-inner.svg';

?>
<!DOCTYPE html>
<!--[if lt IE 7]><html lang="ru" class="lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if IE 7]><html lang="ru" class="lt-ie9 lt-ie8"><![endif]-->
<!--[if IE 8]><html lang="ru" class="lt-ie9"><![endif]-->
<!--[if gt IE 8]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta name="format-detection" content="telephone=no" />
	<link rel="stylesheet" href="<?php echo esc_url( $t ); ?>/libs/bootstrap/bootstrap-grid.css" />
	<link rel="stylesheet" href="<?php echo esc_url( $t ); ?>/libs/fontawesome-free-5.12.0-web/css/all.min.css" />
	<link rel="stylesheet" href="<?php echo esc_url( $t ); ?>/libs/fancybox-master/dist/jquery.fancybox.min.css" />
	<link rel="stylesheet" href="<?php echo esc_url( $t ); ?>/libs/slick-1.5.9/slick/slick.css" />
	<link rel="stylesheet" href="<?php echo esc_url( $t ); ?>/css/fonts.css?ver=1.0" />
	<link rel="stylesheet" href="<?php echo esc_url( $t ); ?>/css/main.css?ver=9.4" />
	<link rel="stylesheet" href="<?php echo esc_url( $t ); ?>/css/media.css?ver=7.7" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

	<div class="wrapper">

		<header class="<?php echo esc_attr( $header_class ); ?>">
			<div class="container-fluid container-main">
				<div class="row align-items-center header__row">
					<div class="col-auto header__logo-col">
						<?php if ( $is_front ) { ?>
							<span class="<?php echo esc_attr( $logo_class ); ?>">
								<img src="<?php echo esc_url( $t ); ?>/img/<?php echo esc_attr( $logo_src ); ?>" alt="АГРО АС">
							</span>
						<?php } else { ?>
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="<?php echo esc_attr( $logo_class ); ?>">
								<img src="<?php echo esc_url( $t ); ?>/img/<?php echo esc_attr( $logo_src ); ?>" alt="АГРО АС">
							</a>
						<?php } ?>
					</div>
					<div class="col header__nav-col d-none d-lg-block">
						<nav class="menu-pills">
							<ul class="menu-pills__list">
								<?php
									wp_nav_menu([
										'menu'            => 'Главное меню',
										'menu_class'      => '',
										'container'       => false,
										'items_wrap' => '%3$s',
									]);
								?>
							</ul>
						</nav>
					</div>
					<div class="col-lg-auto d-none d-lg-flex align-items-center header__contacts">
						<?php if ( $pochta ) { ?>
							<a href="mailto:<?php echo esc_attr( $pochta ); ?>" class="header__email"><?php echo esc_html( $pochta ); ?></a>
						<?php } ?>
						<?php if ( $telefon ) { ?>
							<a href="tel:<?php echo esc_attr( maintheme_phone_href( $telefon ) ); ?>" class="phone-main phone-main--header"><?php echo esc_html( $telefon ); ?></a>
						<?php } ?>
						<?php if ( $telegram ) { ?>
							<a href="<?php echo esc_url( $telegram ); ?>" class="btn-telegram" target="_blank" rel="noopener">
								<img src="<?php echo esc_url( $t ); ?>/img/icon-telegram.svg" alt="Telegram">
							</a>
						<?php } ?>
						<a href="<?php echo esc_url( maintheme_basket_url() ); ?>" class="btn-basket" aria-label="Корзина">
							<img src="<?php echo esc_url( $t ); ?>/img/btn_basket.svg" width="24" height="24" alt="">
							<span class="simple-basket-count">0</span>
						</a>
					</div>
					<div class="col-auto header__bar">
						<?php if ( $telefon ) { ?>
							<a href="tel:<?php echo esc_attr( maintheme_phone_href( $telefon ) ); ?>" class="phone-mobile"><i class="fal fa-phone-alt"></i></a>
						<?php } ?>
						<a href="<?php echo esc_url( maintheme_basket_url() ); ?>" class="btn-basket d-lg-none" aria-label="Корзина">
							<img src="<?php echo esc_url( $t ); ?>/img/btn_basket.svg" width="24" height="24" alt="">
							<span class="simple-basket-count">0</span>
						</a>
						<div class="sandwich">
							<div class="sw-topper"></div>
							<div class="sw-bottom"></div>
							<div class="sw-footer"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="menu-mobile">
				<nav class="menu-pills">
					<ul class="menu-pills__list">
						<?php foreach ( $nav_items as $item ) { ?>
							<li>
								<a href="<?php echo esc_url( $item['href'] ); ?>"<?php echo maintheme_nav_class_attr( $item['class'], $item['active'] ); ?>><?php echo esc_html( $item['label'] ); ?></a>
							</li>
						<?php } ?>
					</ul>
				</nav>
				<div class="header__contacts">
					<?php if ( $pochta ) { ?>
						<a href="mailto:<?php echo esc_attr( $pochta ); ?>" class="header__email"><?php echo esc_html( $pochta ); ?></a>
					<?php } ?>
					<div class="header__phone-wrap">
						<?php if ( $telefon ) { ?>
							<a href="tel:<?php echo esc_attr( maintheme_phone_href( $telefon ) ); ?>" class="phone-main"><?php echo esc_html( $telefon ); ?></a>
						<?php } ?>
						<?php if ( $telegram ) { ?>
							<a href="<?php echo esc_url( $telegram ); ?>" class="btn-telegram" target="_blank" rel="noopener">
								<img src="<?php echo esc_url( $t ); ?>/img/icon-telegram.svg" alt="Telegram">
							</a>
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="menu-overlay"></div>
		</header>
