<!DOCTYPE html>
<!--[if lt IE 7]><html lang="ru" class="lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if IE 7]><html lang="ru" class="lt-ie9 lt-ie8"><![endif]-->
<!--[if IE 8]><html lang="ru" class="lt-ie9"><![endif]-->
<!--[if gt IE 8]><!-->
<html lang="ru">
<!--<![endif]-->
<head>
	<meta charset="utf-8" />
	<meta name="yandex-verification" content="5efcdc1fd0930ef4" />
	<meta name="google-site-verification" content="yYZjcYt_tvtGVtNrCHopDolQbss1GxfyUfzIjM4Jljo" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="format-detection" content="telephone=no" />
	<link rel="shortcut icon" href="https://u-seal.ru/wp-content/uploads/2024/04/fav-7.png" />
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/libs/bootstrap/bootstrap-grid.css" />
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/libs/fontawesome-free-5.12.0-web/css/all.min.css" />
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/libs/fancybox-master/dist/jquery.fancybox.min.css" />
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/libs/slick-1.5.9/slick/slick.css" />
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/libs/slick-1.5.9/slick/slick-theme.css" />
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/fonts.css" />
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/main.css?ver=2.211" />
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/media.css?ver=2.11" />
	<?php
if ( is_front_page() ) : ?>
    <script type="application/ld+json">
    {
        "@context": "http://schema.org/",
        "@type": "Organization",
        "name": "Ю-Cеал",
        "logo": "https://u-seal.ru/wp-content/themes/maintheme/img/logo.svg",
        "url": "https://u-seal.ru",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "ул. Степана Злобина, 31, корпус 2",
            "addressLocality": "Уфа",
            "addressRegion": "Республика Башкортостан",
            "postalCode": "450022",
            "addressCountry": "Russian Federation"
        },
        "sameAs": []
    }
    </script>
<?php endif; ?>
	<?php wp_head(); ?>
</head>
<body>

	<div class="wrapper">

		<header class="header">
			<div class="container">
				<div class="header__wrapper">
					<span class='sandwich'>
						<span class='sw-topper'></span>
						<span class='sw-bottom'></span>
						<span class='sw-footer'></span>
					</span>
					<?php if(is_front_page()){ ?>
						<span class="logo">
							<img src="<?php bloginfo('template_directory'); ?>/img/logo.svg" alt="alt">
						</span>
					<?php } else { ?>
						<a href="/" class="logo">
							<img src="<?php bloginfo('template_directory'); ?>/img/logo.svg" alt="alt">
						</a>
					<?php } ?>
					<?php 
						wp_nav_menu( [
							'menu'            => 'Главное меню',
							'menu_class'      => 'menu',
							'container'       => false, 							
						] );
					?>	
					<div class="header-search">
						<div class="header-search__form">
							<?php echo do_shortcode('[wpdreams_ajaxsearchlite]'); ?>
							<!-- <form>
								<input type="text" placeholder="Поиск" required="required">
								<button class="btn-search"><i class="fal fa-search"></i></button>
							</form> -->
						</div>
						<div class="open-search"><i class="fal fa-search"></i></div>
					</div>										
					<div class="header__location"><?php echo get_field('адрес', 'option'); ?></div>
					<?php if(get_field('whatsapp', 'option') || get_field('telegram', 'option') || get_field('max', 'option') ){ ?>
						<ul class="list-socials">
						<?php if(get_field('whatsapp', 'option')){ ?>
							<li><a href="https://api.whatsapp.com/send/?phone=<?php echo preg_replace("/[^0-9]/", '', get_field('whatsapp', 'option')); ?>" target="_blank" rel="nofollow"><img src="<?php bloginfo('template_directory'); ?>/img/wp.svg" alt="alt"></a></li>
						<?php } if(get_field('telegram', 'option')){ ?>	
							<li><a href="<?php echo get_field('telegram', 'option'); ?>" target="_blank" rel="nofollow"><img src="<?php bloginfo('template_directory'); ?>/img/tg.svg" alt="alt"></a></li>
						<?php } if(get_field('max', 'option')){ ?>	
							<li><a href="<?php echo get_field('max', 'option'); ?>" target="_blank" rel="nofollow"><img src="<?php bloginfo('template_directory'); ?>/img/max.svg" alt="alt"></a></li>
						<?php } ?>
						</ul>
					<?php } ?>
					<a href="tel:+<?php echo preg_replace("/[^0-9]/", '', get_field('телефон', 'option')); ?>" class="btn-main btn-main_page"><i class="fas fa-phone-alt"></i><?php echo get_field('телефон', 'option'); ?></a>
					<a href="/korzina-zakaza/" class="btn-basket">
						<img src="<?php echo bloginfo('template_url'); ?>/img/btn_basket.svg" alt="alt">
						<span class="simple-basket-count btn-fixed__value"><?php simple_basket_items_count(); ?></span>							
					</a>
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
				<div class="search-main">						
					<?php echo do_shortcode('[wpdreams_ajaxsearchlite]'); ?>
				</div>
				<div class="header__location"><?php echo get_field('адрес', 'option'); ?></div>
				<?php if(get_field('whatsapp', 'option') || get_field('telegram', 'option') || get_field('max', 'option') ){ ?>
					<ul class="list-socials">
					<?php if(get_field('whatsapp', 'option')){ ?>
						<li><a href="https://api.whatsapp.com/send/?phone=<?php echo preg_replace("/[^0-9]/", '', get_field('whatsapp', 'option')); ?>" target="_blank" rel="nofollow"><img src="<?php bloginfo('template_directory'); ?>/img/wp.svg" alt="alt"></a></li>
					<?php } if(get_field('telegram', 'option')){ ?>	
						<li><a href="<?php echo get_field('telegram', 'option'); ?>" target="_blank" rel="nofollow"><img src="<?php bloginfo('template_directory'); ?>/img/tg.svg" alt="alt"></a></li>
					<?php } if(get_field('max', 'option')){ ?>	
							<li><a href="<?php echo get_field('max', 'option'); ?>" target="_blank" rel="nofollow"><img src="<?php bloginfo('template_directory'); ?>/img/max.svg" alt="alt"></a></li>
						<?php } ?>
					</ul>
				<?php } ?>
				<a href="tel:+<?php echo preg_replace("/[^0-9]/", '', get_field('телефон', 'option')); ?>" class="btn-main btn-main_page"><?php echo get_field('телефон', 'option'); ?></a>
			</div>
			<div class="menu-overlay"></div>
		</header>