# Паттерн: header.php и footer.php (WP)

**Когда:** новая тема или подключение libs в maintheme  
**Источник:** zarina-wp, Agro-As/maintheme  
**Обновлено:** 2026-06-15

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

## Cookies-баннер в footer.php

**После** `</div><!-- .wrapper -->`, **до** модалок CF7:

```php
$cookie_policy = $politika ? maintheme_link_attr( $politika ) : home_url( '/privacy-policy/' );
?>
<div class="cookies-modal">
	<span>Мы используем cookies для быстрой и удобной работы сайта. Продолжая пользоваться сайтом, вы принимаете <a class="cookie-policy-link" href="<?php echo esc_url( $cookie_policy ); ?>">условия обработки персональных данных</a></span>
	<div class="cookies-btns">
		<div class="cookies-btn yes-cookie">Согласен</div>
		<div class="cookies-btn no-cookie">Не согласен</div>
	</div>
	<button type="button" class="cookie-policy-button">✕</button>
</div>
```

Стили — **обязательный блок в конце `css/main.css`** при каждой натяжке на WP (cookies + CF7). Не пропускать, даже если в статике их не было.

## css/main.css — cookies и CF7 (обязательно)

Добавить **в конец** `css/main.css` (или проверить наличие):

```css
.cookies-modal {
	display: none;
	position: fixed;
	z-index: 1000000000;
	bottom: 0px;
	left: 0px;
	transform: translateX(-50%);
	margin: 0px -50% 15px 50%;
	padding: 9px 43px 9px 14px;
	font-size: 12px;
	font-family: Dodo, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
	font-weight: 500;
	text-align: left;
	border-radius: 4px;
	background-color: rgba(255, 255, 255, 0.95);
	pointer-events: auto;
	max-width: 1280px;
	box-shadow: rgba(6, 5, 50, 0.1) 0px 6px 20px;
	color: #5c5c5c;
}

.cookies-modal a {
	text-decoration: none;
	cursor: pointer;
	outline: none;
	font-family: Dodo, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
	font-weight: 600;
	color: #052d24;
}

.cookies-modal .cookie-policy-button {
	margin: 0px;
	padding: 0px;
	background: none;
	border: none;
	font: inherit;
	cursor: pointer;
	position: absolute;
	top: 9px;
	right: 7px;
	display: inline-block;
	width: 28px;
	color: rgb(92, 99, 112);
	text-align: center;
}

.cookies-modal .cookies-btns {
	display: flex;
	margin-left: auto;
	margin-right: 0;
	width: max-content;
}

.cookies-modal .cookies-btns .cookies-btn {
	border: 1px solid #052d24;
	color: #052d24;
	padding: 5px 10px;
	margin-left: 10px;
	border-radius: 10px;
	cursor: pointer;
	transition: .3s;
}

.cookies-modal .cookies-btns .cookies-btn.yes-cookie {
	margin-left: 0px;
	color: #fff;
	background-color: #052d24;
}

.cookies-modal .cookies-btns .cookies-btn:hover {
	opacity: 0.8;
}

form .btn-main {
	cursor: pointer;
}

div.wpcf7-validation-errors {
	border: 0px;
	font-size: 0px;
	opacity: 0;
	position: absolute;
}

span.wpcf7-not-valid-tip {
	color: #f00;
	font-size: 14px !important;
	font-weight: normal;
	display: block;
	position: relative;
	top: -10px;
	display: none !important;
}

input.wpcf7-form-control.wpcf7-text.wpcf7-validates-as-required.input-form.wpcf7-not-valid,
textarea.wpcf7-form-control.wpcf7-textarea.wpcf7-validates-as-required.input-form.wpcf7-not-valid {
	border: 1px solid #ff0000 !important;
	background-color: #dedede;
}

form .wpcf7-response-output {
	display: none !important;
}

div.wpcf7-mail-sent-ok {
	font-size: 0px;
	border: 0px !important;
	width: 100%;
	padding: 0px;
	margin: 0px;
	position: absolute;
	top: 17px;
}
```

По умолчанию `.cookies-modal { display: none }`; показ — через JS (см. common.js выше).

## common.js — IP формы и cookies (обязательно)

В **начале** `$(document).ready` в `js/common.js`:

```js
$.getJSON('https://api.ipify.org/?format=json', function (e) {
	$('form').find('[name=your-ip]').val(e.ip).attr('readonly', true);
});

if (localStorage.getItem('show_cookies') === null) {
	$('.cookies-modal').show();
	$('.cookies-modal .cookie-policy-button, .cookies-modal .yes-cookie').click(function () {
		$('.cookies-modal').hide();
		localStorage.setItem('show_cookies', true);
	});
	$('.cookies-modal .no-cookie').click(function () {
		window.location.href = 'https://ya.ru/';
	});
}
```

**CF7:** в формах скрытое поле `[hidden your-ip]` (name=`your-ip`). IP подставляется автоматически.

**Порядок скриптов в footer.php:** jQuery → плагины → `<?php wp_footer(); ?>` → `common.js?ver=…` (common **после** wp_footer, как в Agro-As).

## functions.php — редирект CF7 на «Спасибо» (обязательно)

После каждой натяжки добавить в `functions.php` + хелпер в `inc/helpers.php`.

**Хелпер** `maintheme_thanks_page_url()` — страница с шаблоном `page-thnx.php`, иначе slug `spasibo` / `thnx`, иначе `home_url( '/spasibo/' )`.

**Хук:**

```php
function maintheme_cf7_thanks_redirect() {
	if ( is_admin() ) {
		return;
	}

	$thanks_url = maintheme_thanks_page_url();
	?>
<script type="text/javascript">
document.addEventListener( 'wpcf7mailsent', function () {
	(function ($) {
		window.location.href = <?php echo wp_json_encode( $thanks_url ); ?>;
		if ($.fancybox) {
			$.fancybox.close();
		}
	})(jQuery);
}, false );
</script>
	<?php
}
add_action( 'wp_footer', 'maintheme_cf7_thanks_redirect' );
```

**WP:** страница «Спасибо» со slug `spasibo` (или `thnx`) и шаблоном `page-thnx.php`.

## 404.php и page-thnx.php (обязательно)

**Всегда** верстать в стиле сайта — не оставлять Underscores. Подробно: [service-pages-wp.md](service-pages-wp.md).

- `404.php` + `page-thnx.php` (Template Name: **Спасибо**)
- `template-parts/page/service-message.php` — общая разметка
- CSS `.page-mockup` в `main.css` / `media.css` — типографика и цвета **этого** проекта
- Эталон: `Tezis-site/maintheme/`

## front-page.php

```php
get_header();
get_template_part( 'template-parts/front/billbord' );
get_template_part( 'template-parts/front/advantages' );
// …
get_footer();
```

Секции — только `get_template_part`, без разметки в `front-page.php`.
