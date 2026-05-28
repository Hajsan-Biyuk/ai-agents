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
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="format-detection" content="telephone=no" />
	<link rel="shortcut icon" href="<?php bloginfo('template_directory'); ?>/favicon.png" />
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/libs/bootstrap/bootstrap-grid.css" />
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/libs/fontawesome-free-5.12.0-web/css/all.min.css" />
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/libs/fancybox/jquery.fancybox.css" />
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/libs/slick-1.5.9/slick/slick.css" />
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/libs/slick-1.5.9/slick/slick-theme.css" />
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/fonts.css" />
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/main.css?ver=1.17" />
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/media.css?ver=1.17" />
	<?php wp_head(); ?>
</head>

<body>

	<div class="wrapper">

		<header class="header">
			<div class="container">
				<div class="header__wrapper">
				<?php if(is_front_page()) { ?>
					<span class="logo">
						<img src="<?php bloginfo('template_directory'); ?>/img/logo.svg" alt="alt">
					</span>
				<?php } else { ?>
					<a href="/" class="logo">
						<img src="<?php bloginfo('template_directory'); ?>/img/logo.svg" alt="alt">
					</a>
				<?php } ?>
				<a href="/product-category/rasteniya/" class="header__zeleniy">
						<img src="<?php bloginfo('template_directory'); ?>/img/zeleniy.svg" alt="alt">
					</a>
					<?php 
						wp_nav_menu( [
							'menu'            => 'Главное меню',
							'menu_class'      => 'menu',
							'container'       => false, 
						] );
					?>
					<div class="header__buttons">
						<!-- <a href="#" class="header-btn header-btn--search">
							<img src="<?php bloginfo('template_directory'); ?>/img/search.svg" alt="alt">
						</a> -->
						<div class="header-search header-btn">
							<div class="header-search__form">
								<?php echo do_shortcode('[wpdreams_ajaxsearchlite]'); ?>
							
							</div>
							<div class="open-search header-btn--search"><img src="<?php bloginfo('template_directory'); ?>/img/search.svg" alt="alt"></div>
						</div>
						<a href="/my-account/" class="header-btn">
							<img src="<?php bloginfo('template_directory'); ?>/img/user.svg" alt="alt">
						</a>
						<!-- <a href="#" class="header-btn">
							<img src="<?php bloginfo('template_directory'); ?>/img/favorites.svg" alt="alt">
						</a> -->
						<?php if(!empty(get_user_favorites_count())) { ?>
							<a href="/izbrannoe/" class="list-social__like btn-header header-btn"><img src="<?php bloginfo('template_directory'); ?>/img/favorites.svg" alt="alt">
								<span class="btn-header__value"><?php echo get_user_favorites_count(); ?></span></a>
						<?php } else { ?>
							<a href="/izbrannoe/" class="list-social__like btn-header header-btn"><img src="<?php bloginfo('template_directory'); ?>/img/favorites.svg" alt="alt">
								<span class="btn-header__value">0</span></a>
						<?php } ?>
						<?php /*	
						<a href="/cart/" class="header-btn header-btn--cart">
							<img src="<?php bloginfo('template_directory'); ?>/img/basket.svg" alt="alt">
							<span class="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
						</a> */ ?>
					</div>
					<div class="header__contacts">
						<div class="line-contacts">
						<a href="tel:+<?php echo preg_replace("/[^0-9]/", '', get_field('телефон', 'option')); ?>" class="phone-main"><i class="fas fa-phone-alt"></i><?php echo get_field('телефон', 'option'); ?></a>
						<?php if(get_field('whatsapp', 'option') || get_field('telegram', 'option')) { ?>
							<ul class="social-list">
								<?php if(get_field('whatsapp', 'option')){ ?>
									<li><a href="whatsapp://send/?phone=+<?php echo preg_replace("/[^0-9]/", '', get_field('whatsapp', 'option')); ?>" target="_blank"><i class="fab fa-whatsapp"></i></a></li>
								<?php } if(get_field('telegram', 'option')) { ?>
									<li><a href="<?php echo get_field('telegram', 'option'); ?>" target="_blank"><i class="fab fa-telegram-plane"></i></a></li>
								<?php } ?>
							</ul>
						<?php } ?>
						</div>
					</div>
					<span class='sandwich'>
						<span class='sw-topper'></span>
						<span class='sw-bottom'></span>
						<span class='sw-footer'></span>
					</span>
				</div>
			</div>
			<div class="menu-mobile">
				<?php 
					wp_nav_menu( [
						'menu'            => 'Главное меню',
						'menu_class'      => 'menu',
						'container'       => false, 
					] );
				?>
				<div class="header__contacts">
					<div class="line-contacts">
						<?php if(get_field('whatsapp', 'option') || get_field('telegram', 'option')) { ?>
							<ul class="social-list">
								<?php if(get_field('whatsapp', 'option')){ ?>
									<li><a href="whatsapp://send/?phone=+<?php echo preg_replace("/[^0-9]/", '', get_field('whatsapp', 'option')); ?>" target="_blank"><i class="fab fa-whatsapp"></i></a></li>
								<?php } if(get_field('telegram', 'option')) { ?>
									<li><a href="<?php echo get_field('telegram', 'option'); ?>" target="_blank"><i class="fab fa-telegram-plane"></i></a></li>
								<?php } ?>
							</ul>
						<?php } ?>
						<a href="tel:+<?php echo preg_replace("/[^0-9]/", '', get_field('телефон', 'option')); ?>" class="phone-main"><i class="fas fa-phone-alt"></i><?php echo get_field('телефон', 'option'); ?></a>
					</div>
					<a href="#callback" class="link-simple fancybox">Заказать звонок</a>
				</div>
			</div>
		</header>

