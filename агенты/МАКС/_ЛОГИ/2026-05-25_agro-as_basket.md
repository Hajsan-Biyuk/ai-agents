# Agro-As — Simple Basket + variacii

**Дата:** 2026-05-25

## Сделано

### Плагин `Agro-As/plugins/simple-basket/`
- Ключ позиции `{post_id}_{variation_index}`
- AJAX `add`: `variation`, `quo`
- `SimpleBasketOrder`: POST_ID, VARIATION, VOLUME в сессии
- `update`/`remove` по `cart_key`
- JS: расширен `SimpleBasket.add(id, variation, quo)`

### Тема
- Подключение плагина в `functions.php`
- `page-basket.php` + `[basket-order-form]`
- Хелперы: `maintheme_get_basket_line_data()`, `maintheme_get_product_min_variation_index()`, `maintheme_basket_url()`
- `single-product`: «В корзину» + qty + активная вариация
- `card.php`: «В корзину» + min variation
- Хедер: `.btn-basket` + `.simple-basket-count` (desktop + mobile)
- `common.js`: `.js-add-to-cart`, feedback «Добавлено» 1 сек + pulse
- CSS: `.btn-basket`, `.btn-cart--added`
- `wp_footer()` перед `common.js`

### Документация
- `_КОНТЕКСТ/basket-wp-pattern.md`

## На стороне WP

1. Создать страницу «Корзина» (slug `korzina`), шаблон **Корзина**
2. В админке Simple Basket указать URL корзины, валюта **₽**

## Ассеты

- `main.css?ver=8.7`, `common.js?ver=4.6`

---

## v0.4 — универсализация плагина

### API (`inc/product-line.php`)
- `simple_basket_get_integration_config()` — filter `simple_basket_integration_config`
- `simple_basket_get_product_line()` — filter `simple_basket_product_line` + fallback meta
- `simple_basket_get_min_variation_index()` — filter `simple_basket_min_variation_index`
- `simple_basket_add_product_to_cart()` — единая точка add (AJAX + GET)

### Интеграции
- `integrations/acf-repeater.php` — ACF repeater вариации
- `integrations/simple-meta.php` — legacy одна цена + `include_post_type_label`
- `integrations/agro-as.php` — авто-config для темы Agro-As

### Тема
- `maintheme_get_basket_line_data()` → обёртка над API плагина
- Бизнес-логика вариаций убрана из `ajax-api.php`

### JS
- `simple-basket.js`: skip toggle для `.js-add-to-cart` (нет двойного срабатывания)
- Док: `basket-wp-pattern.md` обновлён под фильтры/API

---

## UI корзины + фиксы (2026-05-25, вечер)

- Страница корзины: qty AJAX, удаление, стили, без Update
- Fix ±2 в qty, spinner input, вариации в add (jQuery `.data` cache)
- Отправитель писем «Агро Ас», btn-basket белый круг
- См. `_ЛОГИ/2026-05-25_agro-as_front-categories-basket-polish.md`
