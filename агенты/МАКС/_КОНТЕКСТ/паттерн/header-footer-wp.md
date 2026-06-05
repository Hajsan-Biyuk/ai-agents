# Паттерн: header.php и footer.php (WP)

**Когда:** новая тема или подключение libs в maintheme  
**Источник:** zarina-wp, Agro-As/maintheme  
**Обновлено:** 2026-06-05

## header.php — порядок CSS

```php
$t = get_template_directory_uri();
?>
<link rel="stylesheet" href="<?php echo esc_url( $t ); ?>/libs/bootstrap/bootstrap-grid.css" />
<link rel="stylesheet" href="<?php echo esc_url( $t ); ?>/libs/fontawesome-free-5.12.0-web/css/all.min.css" />
<link rel="stylesheet" href="<?php echo esc_url( $t ); ?>/libs/fancybox-master/dist/jquery.fancybox.min.css" />
<link rel="stylesheet" href="<?php echo esc_url( $t ); ?>/libs/slick-1.5.9/slick/slick.css" />
<link rel="stylesheet" href="<?php echo esc_url( $t ); ?>/css/fonts.css?ver=1.0" />
<link rel="stylesheet" href="<?php echo esc_url( $t ); ?>/css/main.css?ver=1.0" />
<link rel="stylesheet" href="<?php echo esc_url( $t ); ?>/css/media.css?ver=1.0" />
<?php wp_head(); ?>
```

## ACF Options в шапке

```php
$telefon = maintheme_get_option( 'telefon' );
$pochta  = maintheme_get_option( 'pochta' );
```

Хелпер `maintheme_get_option()` — только заполненные поля, см. `inc/helpers.php`.

## footer.php — скрипты

```php
<script src="<?php echo esc_url( $t ); ?>/libs/jquery/jquery-3.6.0.min.js"></script>
<script src="<?php echo esc_url( $t ); ?>/libs/fancybox-master/dist/jquery.fancybox.min.js"></script>
<script src="<?php echo esc_url( $t ); ?>/libs/slick-1.5.9/slick/slick.min.js"></script>
<script src="<?php echo esc_url( $t ); ?>/libs/mask/jquery.maskedinput.js"></script>
<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU"></script>
<script src="<?php echo esc_url( $t ); ?>/js/common.js?ver=1.0"></script>
<?php wp_footer(); ?>
```

## Модалки в footer.php

Разметка `#modal-callback` — **после** `</div><!-- .wrapper -->`, до скриптов.  
HTML — см. `агенты/КОЛЯ/_КОНТЕКСТ/паттерн/модалки.md`.

## front-page.php

```php
get_header();
get_template_part( 'template-parts/front/billbord' );
get_template_part( 'template-parts/front/advantages' );
// …
get_footer();
```

Секции — только `get_template_part`, без разметки в `front-page.php`.
