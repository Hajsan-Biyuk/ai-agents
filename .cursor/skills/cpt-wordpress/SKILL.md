---
name: cpt-wordpress
description: Registers WordPress custom post types and taxonomies for maintheme projects without WooCommerce. Use when the user asks to create CPT, custom post type, taxonomy, product catalog without WC, or @макс catalog integration.
---

# WordPress CPT + Taxonomy

## Обязательный канон

Перед регистрацией CPT **прочитать и следовать**:

`агенты/МАКС/_КОНТЕКСТ/cpt-wordpress-pattern.md`

Эталон кода: `Agro-As/maintheme/inc/cpt-product.php`

## Краткий алгоритм

1. Создать `maintheme/inc/cpt-{slug}.php`
2. `register_taxonomy()` → `register_post_type()` на хуке `init`
3. Slug'и rewrite — **строго из ТЗ**, `with_front` => false
4. `require` в `functions.php` после `helpers.php`
5. `flush_rewrite_rules()` — **только** на `after_switch_theme`, не на `init`
6. Лог в `агенты/МАКС/_ЛОГИ/`

## Agro-As (product)

| Сущность | slug rewrite | Пример URL |
|----------|--------------|------------|
| CPT `product` | `product` | `/product/abeliya-abelia-edward-goucher/` |
| Taxonomy `product_cat` | `product-category` | `/product-category/rasteniya/` |

Подробности — в каноническом документе выше.
