# Паттерн: 404 и «Спасибо» (WP)

**Когда:** любая натяжка на WordPress  
**Эталон:** `Tezis-site/maintheme/`  
**Обновлено:** 2026-06-15

## Обязательно

При каждой натяжке **верстать в стиле сайта** (не оставлять шаблон Underscores):

| Файл | Назначение |
|------|------------|
| `404.php` | Страница не найдена |
| `page-thnx.php` | Template Name: **Спасибо** — редирект CF7 |
| `template-parts/page/service-message.php` | Общая разметка обеих страниц |
| `css/main.css` + `css/media.css` | Стили `.page-mockup` под типографику и цвета темы |

**Не использовать:** дефолтный `404.php` с `get_search_form()`, виджетами и английским текстом.

---

## Разметка (Tezis / корпоративные сайты)

Классы — **из вёрстки проекта**: `title-section`, `title-middle`, `btn-main`, `btn-main--page`, `container`.

```php
<section class="page-mockup page-mockup_error"><!-- или page-mockup_thanks -->
	<div class="container">
		<div class="content-mockup">
			<h1 class="title-section">…</h1>
			<div class="title-middle">…</div>
			<a href="…" class="btn-main btn-main--page">Вернуться на главную</a>
		</div>
	</div>
</section>
```

---

## 404.php

```php
get_header();

get_template_part(
	'template-parts/page/service',
	'message',
	array(
		'modifier' => 'error',
		'title'    => 'Ошибка <span>404</span><br>страница не найдена',
		'descr'    => 'Страница, на которую вы пытаетесь попасть, не существует или была удалена',
		'btn_text' => 'Вернуться на главную',
		'btn_url'  => home_url( '/' ),
	)
);

get_footer();
```

Тексты и акценты (`span` для «404») — под бренд; структура классов — как на главной.

---

## page-thnx.php

```php
/*
Template Name: Спасибо
*/
get_header();

while ( have_posts() ) {
	the_post();

	$descr = trim( wp_strip_all_tags( (string) get_post_field( 'post_content', get_the_ID() ) ) );

	if ( ! maintheme_is_filled( $descr ) ) {
		$descr = 'Наш менеджер свяжется с вами в течение часа';
	}

	get_template_part(
		'template-parts/page/service',
		'message',
		array(
			'modifier' => 'thanks',
			'title'    => 'Спасибо за заявку!',
			'descr'    => $descr,
			'btn_text' => 'Вернуться на главную',
			'btn_url'  => home_url( '/' ),
		)
	);
}

get_footer();
```

Описание можно править контентом страницы в админке. Slug: `spasibo` или `thnx`.

---

## CSS (обязательный минимум)

В `main.css` — блок **в стиле сайта** (фон, типографика, цвет кнопки и акцента 404):

```css
.page-mockup {
	padding: 48px 0 100px;
	background: #f9f6f4; /* или фон секций проекта */
	min-height: calc(100vh - 180px);
}

.page-mockup .content-mockup {
	max-width: 720px;
}

.page-mockup .title-section {
	margin-bottom: 28px;
}

.page-mockup .title-middle {
	font-size: 20px;
	line-height: 140%;
	margin-bottom: 48px;
	max-width: 520px;
}

.page-mockup_error .title-section span {
	color: #4d579f; /* акцент бренда */
}

.page-mockup .btn-main {
	min-width: 240px;
}
```

В `media.css` (≤992px): меньше отступы, кнопка `width: 100%`.

Цвета и шрифты — **снимать с `css/main.css` проекта**, не копировать с другого сайта вслепую.

---

## Связь с CF7

Редирект после отправки — см. [header-footer-wp.md](header-footer-wp.md) (`maintheme_cf7_thanks_redirect` + `page-thnx.php`).

---

## Чеклист @макс

- [ ] `404.php` — `get_template_part( 'template-parts/page/service', 'message', … )`
- [ ] `page-thnx.php` — Template Name: Спасибо
- [ ] `template-parts/page/service-message.php` — классы как в вёрстке
- [ ] Стили `.page-mockup` в `main.css` / `media.css`
- [ ] Страница WP slug `spasibo` + редирект CF7 в `functions.php`
