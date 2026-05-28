<!DOCTYPE html>
<!--[if lt IE 7]><html lang="ru" class="lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if IE 7]><html lang="ru" class="lt-ie9 lt-ie8"><![endif]-->
<!--[if IE 8]><html lang="ru" class="lt-ie9"><![endif]-->
<!--[if gt IE 8]><!-->
<html lang="ru">
<!--<![endif]-->
<head>
	<meta charset="utf-8" />
	<title><?php wp_title(); ?></title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta name="format-detection" content="telephone=no" />
	<!-- <link rel="shortcut icon" href="favicon.png" /> -->
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/libs/bootstrap/bootstrap-grid.css" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/libs/fontawesome-free-5.12.0-web/css/all.min.css" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/libs/fancybox-master/dist/jquery.fancybox.min.css" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/libs/datetimepicker/jquery.datetimepicker.min.css" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/libs/slick-1.5.9/slick/slick.css" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/fonts.css" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/main.css?ver=1.1" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/media.css?ver=1.1" />
	<?php wp_head(); ?>
</head>
<body>

	<div class="wrapper <?php if(!is_front_page()){ ?>wrapper--page<?php } ?>">

		<header class="header default">
			<div class="container-fluid">
				<div class="header__wrapper">
					<div class="sandwich">
						<div class="sw-topper"></div>
						<div class="sw-bottom"></div>
						<div class="sw-footer"></div>
					</div>
					<div class="header__left">
						<?php if(is_front_page()){ ?>
							<span class="logo">
								<img src="<?php echo get_template_directory_uri(); ?>/img/logo_header.svg" alt="alt">
							</span>
						<?php } else { ?>
							<a href="/" class="logo">
								<img src="<?php echo get_template_directory_uri(); ?>/img/logo_header.svg" alt="alt">
							</a>
						<?php } ?>
						<div class="header__location"><?php echo get_field('adres', 'option'); ?></div>
					</div>
					<div class="header__buttons">
						<a href="#" class="btn-main btn-main--light">Отель</a>
						<a href="#" class="btn-main btn-main--light">Гостевой дом</a>
					</div>
					<nav class="menu">
						<ul class="menu__list">
							<li><a href="/ob-otele/">Об отеле</a></li>
							<li><a href="/nomera-i-tseny/">Номера</a></li>
							<li><a href="/infrastruktura/">Инфраструктура</a></li>
							<li><a href="/novosti/">Новости</a></li>
							<li><a href="/kontakty/">Контакты</a></li>
						</ul>
					</nav>
					<a href="#" class="btn-main btn-main--light">Забронировать</a>
					<div class="header__contacts">
						<a href="tel:<?php echo preg_replace("/[^0-9+]/", '', get_field('telefon', 'option')); ?>" class="phone-main"><?php echo get_field('telefon', 'option'); ?></a>
						<a href="#callback" class="link-page fancybox">Позвоните мне</a>
					</div>
					<?php if(!empty(get_field('max', 'option') || get_field('telegram', 'option'))){ ?>
						<ul class="socials-list">
							<?php if(!empty(get_field('telegram', 'option'))){ ?>
								<li><a href="<?php echo get_field('telegram', 'option'); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/img/tg.svg" alt="alt"></a></li>
							<?php } if(!empty(get_field('max', 'option'))){ ?>
								<li><a href="<?php echo get_field('max', 'option'); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/img/max.svg" alt="alt"></a></li>
							<?php } ?>
						</ul>
					<?php } ?>
					<a href="tel:<?php echo preg_replace("/[^0-9+]/", '', get_field('telefon', 'option')); ?>" class="phone-mobile"><i class="fal fa-phone-alt"></i></a>
				</div>
			</div>
			<div class="menu-mobile">
				<div class="header__buttons">
					<a href="#" class="btn-main btn-main--light">Отель</a>
					<a href="#" class="btn-main btn-main--light">Гостевой дом</a>
				</div>
				<nav class="menu">
					<ul class="menu__list">
						<li><a href="/ob-otele/">Об отеле</a></li>
						<li><a href="/nomera-i-tseny/">Номера</a></li>
						<li><a href="/infrastruktura/">Инфраструктура</a></li>
						<li><a href="/novosti/">Новости</a></li>
						<li><a href="/kontakty/">Контакты</a></li>
					</ul>
				</nav>
				<a href="#" class="btn-main">Забронировать</a>
				<div class="header__contacts">
					<a href="tel:<?php echo preg_replace("/[^0-9+]/", '', get_field('telefon', 'option')); ?>" class="phone-main"><?php echo get_field('telefon', 'option'); ?></a>
					<a href="#callback" class="link-page fancybox">Позвоните мне</a>
				</div>
				<?php if(!empty(get_field('max', 'option') || get_field('telegram', 'option'))){ ?>
					<ul class="socials-list">
						<?php if(!empty(get_field('telegram', 'option'))){ ?>
							<li><a href="<?php echo get_field('telegram', 'option'); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/img/tg.svg" alt="alt"></a></li>
						<?php } if(!empty(get_field('max', 'option'))){ ?>
							<li><a href="<?php echo get_field('max', 'option'); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/img/max.svg" alt="alt"></a></li>
						<?php } ?>
					</ul>
				<?php } ?>
				<div class="header__location"><?php echo get_field('adres', 'option'); ?></div>
			</div>
			<div class="menu-overlay"></div>
		</header>

