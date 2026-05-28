# Agro-As — шапка главная vs внутренние

**Дата:** 2026-05-25

## Проблема

На всех страницах WP была одна шапка (`header default` + `logo.svg`), без стилей внутренних страниц из статики.

## Сделано

### `header.php`
- Главная: `header default`, `logo.svg`, логотип в `<span>`
- Внутренние: `header`, `logo logo--inner`, `logo-inner.svg`, логотип в `<a>`
- Меню вынесено в массив `$nav_items` (desktop + mobile)
- `active` на текущей странице через `maintheme_nav_class_attr()`

### `functions.php`
- Фильтр `body_class`: на внутренних добавляется `wrapper__default`

### `inc/helpers.php`
- `maintheme_nav_is_active()` — wholesale / delivery / contacts по `page_template`
- `maintheme_nav_class_attr()` — `btn-catalog`, `active`

### Убрано
- `wrapper--page` на `.wrapper` (не было в CSS, дублировало intent)

## CSS

Без изменений — используются существующие правила `.wrapper__default .header` в `main.css`.

## Обновление 2026-05-25 — fixed при скролле на внутренних

- `js/common.js` — `setHeaderFixed()` для `body.wrapper__default`, padding-top на `.wrapper`
- `css/main.css` — `.wrapper__default .header.fixed` (тёмная шапка как на главной)

## Документация

- `агенты/МАКС/_КОНТЕКСТ/agro-as-wp-pattern.md` §7.1
