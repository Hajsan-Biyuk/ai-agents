# Prompotok — ACF лендинг таксономии catalog_cat

**Дата:** 2026-06-08

## Сделано

- `acf-json/group_pl_catalog_landing.json` — группа полей для `catalog_cat`
- `acf-json/acf-import-all.json` — для импорта одним файлом
- `taxonomy-catalog_cat.php` — проверка `landing`, ветка лендинг / каталог по умолчанию
- `template-parts/taxonomy/landing.php` — натяжка секций лендинга
- `template-parts/taxonomy/catalog-default.php` — вынесена стандартная сетка товаров

## Поля

- `landing` (true_false UI) — главный переключатель
- Вкладки: Hero, Преимущества, Типы присоединения, Форма, Популярные модели, Статьи, Поставка, SEO, Документы, Видео, FAQ
- Форма: `forma.shortcode` (text)
- Модели: `populyarnye_modeli.tovary` (relationship → catalog)
- Статьи: `statii.zapisi` (relationship → post)
- Видео: `video_otzyvy.otzyvy[].video` (file mp4)

## Импорт

ACF → Tools → Import → `acf-import-all.json`  
**functions.php не менялся** — load/save json не подключались.

## Логика шаблона

```php
$is_landing = (bool) get_field( 'landing', 'catalog_cat_' . $term_id );
```

- Лендинг: sidebar + `landing.php`, без нижней `sect-form`
- По умолчанию: h1 + сетка товаров + `sect-form`
