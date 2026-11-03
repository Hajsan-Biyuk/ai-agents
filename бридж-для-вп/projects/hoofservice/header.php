<!DOCTYPE html>
<!--[if lt IE 7]><html lang="ru" class="lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if IE 7]><html lang="ru" class="lt-ie9 lt-ie8"><![endif]-->
<!--[if IE 8]><html lang="ru" class="lt-ie9"><![endif]-->
<!--[if gt IE 8]><!-->
<html lang="ru">
<!--<![endif]-->
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta name="format-detection" content="telephone=no" />
	<meta name="yandex-verification" content="ee0a7c39b0d7c75b" />
	<meta name="google-site-verification" content="vDIL3od7qo09-xrGT8_RleaabnYe4wr9SDVhNsin47Q" />
	<!-- <link rel="shortcut icon" href="favicon.png" /> -->
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/libs/bootstrap/bootstrap-grid.css" />
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/libs/fontawesome-free-5.12.0-web/css/all.min.css" />
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/libs/fancybox-master/dist/jquery.fancybox.min.css" />
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/libs/slick-1.5.9/slick/slick.css" />
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/fonts.css" />
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/main.css?ver=1.96" />
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/media.css?ver=1.95" />
	<?php wp_head(); ?>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "LocalBusiness",
  "name": "Копытный Сервис — Ярославль",
  "url": "https://hoofservice.ru/",
  "telephone": "+7-902-222-31-87",
  "email": "uk-hoofservice@mail.ru",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "Волгостроевская наб., 21Б",
    "addressLocality": "Ярославль",
    "addressRegion": "Ярославская область",
    "postalCode": "150036",
    "addressCountry": "RU"
  },
  "geo": {
    "@type": "GeoCoordinates",
    "latitude": 57.747064,
    "longitude": 39.790148
  },
  "openingHoursSpecification": [
    {
      "@type": "OpeningHoursSpecification",
      "dayOfWeek": ["Monday","Tuesday","Wednesday","Thursday","Friday"],
      "opens": "08:00",
      "closes": "17:00"
    }
  ],
  "priceRange": "$$$",
  "paymentAccepted": "Наличные, Безналичный расчет, СБП"
}
</script>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "@id": "https://hoofservice.ru/",
  "name": "Копытный Сервис",
  "legalName": "ООО «Копытный Сервис»",
  "alternateName": "HoofService | Обработка копыт КРС",
  "url": "https://hoofservice.ru/",
  "logo": "https://hoofservice.ru/wp-content/themes/maintheme/img/logo.svg",
  "description": "Производство ортопедического оборудования для КРС и профессиональные услуги по обрезке, лечению и профилактике заболеваний копыт крупного рогатого скота. Работаем по всей России более 25 лет.",
  "email": "uk-hoofservice@mail.ru",
  "telephone": ["+7-902-222-31-87", "+7-929-079-44-07", "+7-902-223-10-75"],
  "areaServed": "Россия",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "Волгостроевская наб., 21Б",
    "addressLocality": "Ярославль",
    "addressRegion": "Ярославская область",
    "postalCode": "150036",
    "addressCountry": "RU"
  },
  "sameAs": [
	"https://vk.com/hoofservice",
    "https://t.me/hoofservice",
    "https://www.youtube.com/@hoofservice"
  ],
  "vatID": "7606136972"
}
</script>
	
	<!-- Roistat Counter Start -->
<script>
(function(w, d, s, h, id) {
    w.roistatProjectId = id; w.roistatHost = h; w.roistatPage = d.location.href; w.roistatReferrer = d.referrer;
    var p = d.location.protocol == "https:" ? "https://" : "http://";
    var u = /^.*roistat_visit=[^;]+(.*)?$/.test(d.cookie) ? "/dist/module.js" : "/api/site/1.0/"+id+"/init?referrer="+encodeURIComponent(d.location.href);
    var js = d.createElement(s); js.charset="UTF-8"; js.async = 1; js.src = p+h+u; var js2 = d.getElementsByTagName(s)[0]; js2.parentNode.insertBefore(js, js2);
})(window, document, 'script', 'cloud.roistat.com', 'c9c2c1452f4addc5c711165d80d92f44');
</script>
<!-- Roistat Counter End -->

</head>
<body>

	<div class="wrapper">

		<header class="header">
			<div class="header__top">
				<div class="container">
					<div class="header__wrapper">
						<span class='sandwich'>
							<span class='sw-topper'></span>
							<span class='sw-bottom'></span>
							<span class='sw-footer'></span>
						</span>
						<div class="logo-wrap">
							<?php if(is_front_page()){ ?>
								<span class="logo">
									<img src="<?php bloginfo('template_directory'); ?>/img/logo.svg" alt="alt">
								</span>
							<?php } else { ?>
								<a href="/" class="logo">
									<img src="<?php bloginfo('template_directory'); ?>/img/logo.svg" alt="alt">
								</a>
							<?php } ?>							
							<div class="logo-descr"><?php bloginfo('description'); ?></div>
						</div>
						<div class="header__location"><?php echo get_field('адрес', 'option'); ?></div>						
						<div class="form-search">
							<?php echo do_shortcode('[wpdreams_ajaxsearchlite]'); ?>						
						</div>
						<div class="header__right">
							<?php
							$kps_socials = array(
								'telegram' => array( 'icon' => 'tg.svg', 'label' => 'Telegram' ),
								'vk'       => array( 'icon' => 'vk.svg', 'label' => 'ВКонтакте' ),
								'youtube'  => array( 'icon' => 'yt.svg', 'label' => 'YouTube' ),
							);
							$kps_social_items = array();
							foreach ( $kps_socials as $kps_social_field => $kps_social ) {
								$kps_social_url = get_field( $kps_social_field, 'option' );
								if ( is_string( $kps_social_url ) && $kps_social_url !== '' ) {
									$kps_social_items[] = array(
										'url'   => $kps_social_url,
										'icon'  => $kps_social['icon'],
										'label' => $kps_social['label'],
									);
								}
							}
							if ( $kps_social_items ) :
							?>
								<ul class="socials-list">
									<?php foreach ( $kps_social_items as $kps_social_item ) : ?>
										<li><a href="<?php echo esc_url( $kps_social_item['url'] ); ?>" target="_blank" rel="noopener noreferrer"><img src="<?php echo esc_url( get_template_directory_uri() . '/img/' . $kps_social_item['icon'] ); ?>" alt="<?php echo esc_attr( $kps_social_item['label'] ); ?>"></a></li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
							<div class="header__contacts">
								<a href="tel:+<?php echo preg_replace("/[^0-9]/", '', get_field('телефон', 'option')); ?>" class="phone-main"><?php echo get_field('телефон', 'option'); ?></a>
								<a href="mailto:<?php echo get_field('почта', 'option'); ?>" class="mail-main"><?php echo get_field('почта', 'option'); ?></a>
							</div>
						</div>
						<a href="tel:+<?php echo preg_replace("/[^0-9]/", '', get_field('телефон', 'option')); ?>" class="phone-mob"><i class="fas fa-phone-alt"></i></a>
					</div>
				</div>
			</div>
			<div class="header__bottom">
				<div class="container">
					<div class="show-mob">
						<div class="form-search">
							<?php echo do_shortcode('[wpdreams_ajaxsearchlite]'); ?>	
						</div>
					</div>
					<ul class="menu">
						<li class="menu__haschild">
							<a href="/oborudovanie/">оборудование</a>
							<div class="open-child"><i class="fas fa-chevron-down"></i></div>
							<ul>
							<?php
								$terms = get_terms(
									array(
										'taxonomy' => 'equipment_cat',
										'hide_empty' => false,		                     
										'parent' => 0
									)
								);
								foreach ($terms as $key => $term) { 
								?>
								<li><a href="<?php echo get_term_link($term); ?>"><?php echo $term->name;?></a></li>
							<?php } ?>							
							</ul>
						</li>
						<li><a href="/services/">услуги по обрезке</a>
						<div class="open-child"><i class="fas fa-chevron-down"></i></div>
						<ul>
							<?php
							wp_nav_menu([
								'menu'            => 'Услуги',
								'menu_class'      => '',
								'container'       => false,
								'items_wrap' => '%3$s',
							]);
							?>
						</ul>
							
						</li>
						<?php
						wp_nav_menu([
							'menu'            => 'Главное меню',
							'menu_class'      => '',
							'container'       => false,
							'items_wrap' => '%3$s',
						]);
						?>
						<li><a href="/novosti/">Новости</a>
							<div class="open-child"><i class="fas fa-chevron-down"></i></div>
							<ul>
								<li><a href="/statiyi/">Статьи</a></li>
								<li><a href="/zhurnaly/">Журнал</a></li>								
							</ul>
						</li>
						<li><a href="/contacts/">Контакты</a></li>
					</ul>
					<div class="show-mob">
						<div class="header__right">
							<?php
							$kps_socials = array(
								'telegram' => array( 'icon' => 'tg.svg', 'label' => 'Telegram' ),
								'vk'       => array( 'icon' => 'vk.svg', 'label' => 'ВКонтакте' ),
								'youtube'  => array( 'icon' => 'yt.svg', 'label' => 'YouTube' ),
							);
							$kps_social_items = array();
							foreach ( $kps_socials as $kps_social_field => $kps_social ) {
								$kps_social_url = get_field( $kps_social_field, 'option' );
								if ( is_string( $kps_social_url ) && $kps_social_url !== '' ) {
									$kps_social_items[] = array(
										'url'   => $kps_social_url,
										'icon'  => $kps_social['icon'],
										'label' => $kps_social['label'],
									);
								}
							}
							if ( $kps_social_items ) :
							?>
								<ul class="socials-list">
									<?php foreach ( $kps_social_items as $kps_social_item ) : ?>
										<li><a href="<?php echo esc_url( $kps_social_item['url'] ); ?>" target="_blank" rel="noopener noreferrer"><img src="<?php echo esc_url( get_template_directory_uri() . '/img/' . $kps_social_item['icon'] ); ?>" alt="<?php echo esc_attr( $kps_social_item['label'] ); ?>"></a></li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
							<div class="header__contacts">
								<a href="tel:+<?php echo preg_replace("/[^0-9]/", '', get_field('телефон', 'option')); ?>" class="phone-main"><?php echo get_field('телефон', 'option'); ?></a>
								<a href="mailto:<?php echo get_field('почта', 'option'); ?>" class="mail-main"><?php echo get_field('почта', 'option'); ?></a>
							</div>
						</div>
						<div class="header__location"><?php echo get_field('адрес', 'option'); ?></div>
						<div class="header__location"><?php echo get_field('режим_работы', 'option'); ?></div>
					</div>
				</div>
			</div>
			<div class="menu-overlay"></div>
		</header>
