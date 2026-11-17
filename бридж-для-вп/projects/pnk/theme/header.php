<!DOCTYPE html>
<html lang="ru">

<head>
	<meta name="yandex-verification" content="9d1b9b4a9c340947" />
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="format-detection" content="telephone=no">

	<meta name='wmail-verification' content='4f619f845397204e082214f4b8452a98' />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="google-site-verification" content="Z-ZHYyal_Qcqvo5XGJCjS404aO2EUMf65h-eoKXN0i4" />
	<meta name="yandex-verification" content="2ec04919c75a3f8e" />
	<meta name="yandex-verification" content="747047715b0539c9" />

	<meta name="cmsmagazine" content="23ba5200e2f0bd6a899aefc6f270df43" />
	<meta name="cmsmagazine" content="ea31f6a65476505a5d82b1eb394a5136" />
	<meta name="google-site-verification" content="XkkjX-KUCT7zCDEEcItYkWN7p-Quu6DblHCncJOQdm0" />
	<meta name="google-site-verification" content="jxZLTkZiRMgdxVMnr7SjE5rYz4t5CclreXXSP5hHjMQ" />
	<meta name="google-site-verification" content="jHWDR7F9trLkbDqXgMSUjAmtPxsh3QfXYjcwTHUvOAI" />

	<meta name="yandex-verification" content="9d1b9b4a9c340947" />

	<title><?php wp_title(''); ?></title>

<link href="<?php echo esc_url(get_template_directory_uri()); ?>/css/slick.css" rel="stylesheet">
<link href="<?php echo esc_url(get_template_directory_uri()); ?>/css/slick-theme.css" rel="stylesheet"><!-- Tether for Bootstrap -->
<link href="<?php echo esc_url(get_template_directory_uri()); ?>/css/bootstrap.css" rel="stylesheet">
<link href="<?php echo esc_url(get_template_directory_uri()); ?>/css/owl.carousel.min.css" rel="stylesheet">
<link href="<?php echo esc_url(get_template_directory_uri()); ?>/css/jquery.fancybox.css" rel="stylesheet">
<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/libs/fontawesome-free-5.12.0-web/css/all.min.css" />
<!-- Custom styles for this template -->
<link href="<?php echo esc_url(get_template_directory_uri()); ?>/css/animate.css" rel="stylesheet">



	<link href="https://fonts.googleapis.com/css2?family=Onest:wght@100..900&display=swap" rel="stylesheet">

	<link href="<?php echo esc_url(get_template_directory_uri()); ?>/style.css?ver=1.044" rel="stylesheet">

	<?php wp_head(); ?>
</head>
<!-- http://pnk.com.ru/wp-content/plugins/the-preloader/images/preloader.GIF -->

<body <?php body_class(); ?> style="overflow-y: hidden;">
	<!-- <div id="wptime-plugin-preloader"></div> -->
	<header class="header">
		<div class="container">
			<div class="header-wrap">
				<div class="header-logo">
	<?php if ( is_front_page() || is_home() ) : ?>
		<span class="logo">
			<img
				alt="<?php bloginfo('name'); ?>"
				title="<?php bloginfo('name'); ?>"
				class="trans img-fluid"
				src="<?php echo get_template_directory_uri(); ?>/images/logo.png">
		</span>
	<?php else : ?>
		<a href="<?php echo esc_url( home_url('/') ); ?>" rel="home" class="logo">
			<img
				alt="<?php bloginfo('name'); ?>"
				title="<?php bloginfo('name'); ?>"
				class="trans img-fluid"
				src="<?php echo get_template_directory_uri(); ?>/images/logo.png">
		</a>
	<?php endif; ?>
	<a href="#" class="menu-trigger"></a>
</div>
				<div class="header-center">
					<nav class="header-menu">
						<?php wp_nav_menu(array('theme_location' => 'primary', 'container' => false, 'menu_class' => "menu nav")); ?>
					</nav>
				</div>
				<div class="header-info">
					<div class="location-block">
						<!-- <div class="location-block__value">г. Симферополь, ул. Генерала Телегина, 16/A</div>
						<div class="location-block__list" style="display: none;">
							<div><a class="show" href="#">г. Симферополь, ул. Генерала Телегина, 16/A</a></div>
							<div><a href="#">г. Севастополь, Камышовое шоссе, 14</a></div>
						</div> -->
						<div class="mobile_geolocation">
							<?php include_once(ABSPATH . '/_geolocation/modals-mobile.php'); ?>
						</div>
					</div>
					<div class="phone">
						<div class="phones-wr">
							<a class="active" href="tel:+79787877391"><span>+7 (978) 787-73-91</span></a>
						</div>
					</div>
					<div class="btn_wr callback_header">
						<a href="#callback" class="btn fancybox">Заказать звонок</a>
					</div>
				</div>
			</div>

			<div class="topHeader">
							<p>Кровельные материалы от производителя в Крыму</p>
				<a href='mailto:pnt_crimea@mail.ru'>e-mail: pnt_crimea@mail.ru</a>
			</div>
			<div class="mainHeader">
				<a href="/" class="logo wow zoomIn">
					<img alt="<?php bloginfo('name'); ?>"
						title="<?php bloginfo('name'); ?>"
						class="trans img-fluid"
						src="<?php echo get_template_directory_uri(); ?>/images/new-logo.png">
				</a>
				<div class="location-block">
					<!-- <div class="location-block__value">г. Симферополь,<br /> ул. Генерала Телегина, 16/A</div>
					<div class="location-block__list" style="display: none;">
						<div><a class="show" href="#">г. Симферополь,<br /> ул. Генерала Телегина, 16/A</a></div>
						<div><a href="#">г. Севастополь,<br /> Камышовое шоссе, 14</a></div>
					</div> -->
					<?php include_once(ABSPATH . '/_geolocation/modals.php'); ?>
					<p>ул. Генерала Телегина, 16/A</p>
				</div>
				<div class="search">
					<?php get_search_form(); ?>
				</div>
				<div class="mainHeader__contacts">
					<div class="phones-wr">
						<a class="active" href="tel:+79787877391"><span>+7 (978) 787-73-91</span></a>
					</div>
					<?php if ($max_link = get_field('max_link', 'option')) : ?>
					<a class="mainHeader__max" href="<?php echo esc_url($max_link); ?>" target="_blank" rel="noopener noreferrer nofollow"><img src="<?php echo get_template_directory_uri(); ?>/images/icons/max.svg" width="44" height="44" alt="MAX ПНК"></a>
					<?php endif; ?>
					<div class="btn_wr callback_header">
						<a href="#callback" class="btn__main fancybox">Заказать звонок</a>
					</div>
				</div>
			</div>
			<div class="navHeader">
				<a class="btn__catalog">
					<img src='<?php echo get_template_directory_uri(); ?>/images/burger.svg' alt='icon' />
					<p>Каталог</p>
				</a>
				<ul class='catalog__menu'>
				<?php
						wp_nav_menu([
							'menu'            => 'Каталог меню',
							'menu_class'      => '',
							'container'       => false,
							'items_wrap' => '%3$s',
						]);
						?>
				</ul>
				<ul class='navHeader__menu'>
				<?php
						wp_nav_menu([
							'menu'            => 'Новое главное меню',
							'menu_class'      => '',
							'container'       => false,
							'items_wrap' => '%3$s',
						]);
						?>
				</ul>
			</div>


		</div>
	</header>