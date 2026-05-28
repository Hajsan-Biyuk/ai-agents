# Agro-As — single-product.php + card.html

**Дата:** 2026-05-25

## Сделано

### Шаблоны
- `single-product.php`
- `template-parts/product/content.php`
- `template-parts/product/breadcrumbs.php`
- `template-parts/product/hero.php`
- `template-parts/product/tabs.php`
- `template-parts/product/recommend.php`
- `template-parts/product/card.php`

### Хелперы (`inc/helpers.php`)
- `maintheme_nav_is_active('catalog')` — single product + taxonomy
- `maintheme_get_product_specs()`
- `maintheme_product_variation_volume_text()`
- `maintheme_product_catalog_url()`
- `maintheme_product_card_data()`

### Поведение
- Галерея: slick при >1 фото
- Вариации: `data-volume`, `data-price` → смена label и `.product-page__price`
- Характеристики preview: 6 строк, `.product-page__specs-all` раскрывает остальные
- Табы только с контентом; описание из `post_content`
- Рекомендуемые: relationship или 6 random из `product_cat`
- Статусы: `--in-stock`, `--out-of-stock`, `--preorder`
- Меню «Каталог» active на карточке товара

### Ассеты
- `main.css?ver=8.1`, `common.js?ver=4.0`
- Синхрон: `Agro-As/css/main.css`, `Agro-As/js/common.js`

## Следующий шаг

- `taxonomy-product_cat.php` + category.html
