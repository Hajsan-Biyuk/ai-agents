# 2026-05-19 — WordPress + WooCommerce (эталон natyajka)

## Задача

Внедрить готовую HTML/CSS/JS-вёрстку в тему WordPress с каталогом на WooCommerce. Эталон проекта — `reference/natyajka/maintheme/`.

## Решение (паттерн)

### 1. Каркас темы

- Создать тему `maintheme` (или имя проекта) со `style.css` (Theme Name, Text Domain).
- Скопировать из вёрстки: `css/`, `js/`, `img/`, `fonts/`, `libs/`.
- Разбить `index.html` → `header.php`, `footer.php`, `front-page.php`.

### 2. header.php

- Оставить порядок CSS как в статике (bootstrap-grid → libs → fonts → main → media).
- Вставить `<?php wp_head(); ?>` перед `</head>`.
- Логотип: `is_front_page()` → `<span>` / `<a>`.
- Меню: `wp_nav_menu(['menu' => 'Главное меню', ...])`.
- Контакты: `get_field('телефон', 'option')`, соцсети из ACF Options.
- Пути: `bloginfo('template_directory')`.

### 3. functions.php

```php
add_theme_support('woocommerce');
register_nav_menus([...]);
// Хуки вариаций, AJAX, admin CSS — по образцу natyajka
```

### 4. WooCommerce

- Папка `woocommerce/` в теме.
- Переопределить `content-single-product.php` под вёрстку карточки.
- Кастомные поля вариаций через `woocommerce_product_after_variable_attributes`.

### 5. footer.php

- `wp_nav_menu` для футера.
- `WP_Query` для CPT `service` (если есть в проекте).
- `wp_footer()` перед `</body>`.

### 6. Проверка

- [ ] Главная и внутренние страницы
- [ ] Каталог и карточка товара
- [ ] Корзина / checkout
- [ ] Меню в админке назначены
- [ ] ACF Options заполнены
- [ ] Нет PHP Notice при `WP_DEBUG`

## Что сработало

- **Не менять классы** из вёрстки Коли — только обёртка PHP.
- **Явные link/script в header/footer** — как в natyajka, плюс `wp_head`/`wp_footer`.
- **Логика в functions.php**, разметка в шаблонах.
- Сверка с `reference/natyajka` перед изобретением новых хуков.

## Файлы эталона

| Компонент | Путь |
|-----------|------|
| Header | `reference/natyajka/maintheme/header.php` |
| Footer | `reference/natyajka/maintheme/footer.php` |
| Functions | `reference/natyajka/maintheme/functions.php` |
| Single product | `reference/natyajka/maintheme/woocommerce/content-single-product.php` |

## Пример вызова

```
@макс Внедри вёрстку из туроператор/ в WordPress + WooCommerce по образцу reference/natyajka
```
