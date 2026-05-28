# WordPress — паттерн регистрации CPT и таксономии

**Обязательно для @макс:** при задачах «создай кастомный тип», «CPT», «таксономия», «каталог без WooCommerce» — делать **только по этому документу**.

**Эталон в проекте:** `Agro-As/maintheme/inc/cpt-product.php`  
**Референс (старый стиль, не копировать flush на init):** `reference/natyajka/maintheme/functions.php` → `register_portfolio_post_type`

---

## 1. Когда применять

- Каталог / карточка товара **без WooCommerce**
- Любой CPT с рубриками (portfolio, service, catalog, product и т.д.)
- Перед ACF-полями и шаблонами — сначала CPT + taxonomy, потом поля и `single-*.php`

---

## 2. Структура файлов

```
maintheme/
├── functions.php              ← require inc/cpt-*.php после helpers.php
└── inc/
    └── cpt-{slug}.php         ← одна сущность = один файл
```

**Не регистрировать CPT прямо в `functions.php`** — только `require`.

**Порядок в `functions.php`:**

```php
require get_template_directory() . '/inc/helpers.php';
require get_template_directory() . '/inc/cpt-product.php';
```

---

## 3. Именование

| Элемент | Правило | Пример Agro-As |
|---------|---------|----------------|
| Файл | `inc/cpt-{post_type}.php` | `cpt-product.php` |
| Функция | `{prefix}_register_{post_type}_post_type` | `maintheme_register_product_post_type` |
| Flush | `{prefix}_{post_type}_rewrite_flush` | `maintheme_product_rewrite_flush` |
| Префикс | префикс темы (`maintheme_`) | — |
| Post type slug | латиница, snake_case | `product` |
| Taxonomy slug | `{post_type}_cat` или по задаче | `product_cat` |

Оборачивать функции в `if ( ! function_exists( ... ) )`.

---

## 4. Порядок регистрации

1. **Сначала** `register_taxonomy()` (если есть)
2. **Затем** `register_post_type()` с `'taxonomies' => array( '...' )`
3. Хук: `add_action( 'init', '{prefix}_register_{post_type}_post_type' );`

---

## 5. Обязательные параметры

### Таксономия (категории)

```php
register_taxonomy(
    'product_cat',
    array( 'product' ),
    array(
        'public'            => true,
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud'     => false,
        'show_in_rest'      => false,
        'rewrite'           => array(
            'slug'         => 'product-category', // slug из ТЗ
            'hierarchical' => true,
            'with_front'   => false,
        ),
        // labels — полный набор на русском
    )
);
```

### CPT

```php
register_post_type(
    'product',
    array(
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_rest'        => false,
        'exclude_from_search' => false,
        'capability_type'     => 'post',
        'map_meta_cap'        => true,
        'hierarchical'        => false,
        'has_archive'         => false, // true только если нужен архив /slug/
        'query_var'           => true,
        'rewrite'             => array(
            'slug'       => 'product', // slug из ТЗ
            'with_front' => false,
            'feeds'      => false,
        ),
        'supports'            => array( 'title', 'editor', 'thumbnail' ),
        'taxonomies'          => array( 'product_cat' ),
        'menu_icon'           => 'dashicons-cart',
        // labels — полный набор на русском
    )
);
```

### Slug'и — только из ТЗ

| URL | rewrite slug |
|-----|--------------|
| `/product/abeliya-abelia-edward-goucher/` | `product` |
| `/product-category/rasteniya/` | `product-category` |

**Всегда** `'with_front' => false` — без префикса из «Постоянных ссылок».

---

## 6. Rewrite rules — ЗАПРЕЩЕНО / ОБЯЗАТЕЛЬНО

### ❌ ЗАПРЕЩЕНО

```php
// НЕ вызывать flush_rewrite_rules() внутри init / register_* на каждой загрузке!
add_action( 'init', function () {
    register_post_type( ... );
    flush_rewrite_rules(); // ← ломает производительность
} );
```

### ✅ ОБЯЗАТЕЛЬНО

```php
function maintheme_product_rewrite_flush() {
    maintheme_register_product_post_type();
    flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'maintheme_product_rewrite_flush' );
```

**После деплоя:** Настройки → Постоянные ссылки → **Сохранить** (если 404).

---

## 7. Agro-As — эталон product

**Файл:** `Agro-As/maintheme/inc/cpt-product.php`

- CPT `product`, taxonomy `product_cat`
- `has_archive` => `false` (общий каталог — отдельно: taxonomy или page)
- `supports`: title, editor, thumbnail
- Следующий шаг: ACF + `single-product.php` + `taxonomy-product_cat.php` по `card.html` / `category.html`

---

## 8. Шаблоны (следующий этап, не в CPT-задаче)

| Файл | Назначение |
|------|------------|
| `single-{post_type}.php` | Карточка (`single-product.php`) |
| `taxonomy-{taxonomy}.php` | Категория (`taxonomy-product_cat.php`) |
| `archive-{post_type}.php` | Архив CPT (если `has_archive` => true) |

Разметку брать из статики, классы не менять.

---

## 9. Чеклист перед сдачей

- [ ] CPT и taxonomy в отдельном `inc/cpt-*.php`
- [ ] Подключено в `functions.php` после `helpers.php`
- [ ] Slug'и совпадают с ТЗ
- [ ] `with_front` => false
- [ ] Нет `flush_rewrite_rules()` на `init`
- [ ] Есть flush на `after_switch_theme`
- [ ] Labels на русском
- [ ] `php -l` без ошибок
- [ ] Лог в `агенты/МАКС/_ЛОГИ/`

---

## 10. Связанные файлы

| Документ | Путь |
|----------|------|
| Канон Agro-As | `_КОНТЕКСТ/agro-as-wp-pattern.md` |
| Лог product CPT | `_ЛОГИ/2026-05-25_agro-as_product_cpt.md` |
| Эталон код | `Agro-As/maintheme/inc/cpt-product.php` |
