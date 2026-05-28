# Agro-As — taxonomy-product_cat + category.html

**Дата:** 2026-05-25

## Сделано

### Шаблоны
- `taxonomy-product_cat.php`
- `template-parts/catalog/content.php`
- `template-parts/catalog/breadcrumbs.php`
- `template-parts/catalog/sidebar.php`
- `template-parts/catalog/pagination.php`

### Хелперы (`inc/helpers.php`)
- `maintheme_catalog_url()`
- `maintheme_get_catalog_sort()` / `maintheme_get_catalog_sort_query_args()`
- `maintheme_product_cat_sidebar_is_open()`
- `maintheme_catalog_page_link()`
- `maintheme_catalog_pagination()`

### Query (`functions.php`)
- `pre_get_posts`: `product`, 12 на страницу, сортировка по GET `sort`

### Поведение
- Сайдбар: все top-level `product_cat` + подкатегории, `--open` у родителя, `a.active` у текущей
- Сортировка: default / price / date / alpha → reload с `?sort=`, сброс пагинации
- Пагинация: `.catalog-page__pager` (prev, nums, next)
- Крошки с родительской категорией
- Сетка через `template-parts/product/card.php`
- Меню «Каталог» → `maintheme_catalog_url()`

### Документация
- `_КОНТЕКСТ/product-catalog-wp-pattern.md`

### Ассеты
- `main.css?ver=8.5`, `common.js?ver=4.3`
- Синхрон: `Agro-As/css/main.css`, `Agro-As/js/common.js`

## Следующий шаг

- Страница `/catalog/` или редирект на первую категорию
- Пересохранить товары в админке для meta `_maintheme_min_price` (сортировка по цене)
- Футер: динамические ссылки категорий
