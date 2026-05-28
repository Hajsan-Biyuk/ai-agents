# Agro-As — CPT product + taxonomy product_cat

**Дата:** 2026-05-25  
**Задача:** зарегистрировать кастомный тип «Товар» и категории для будущей натяжки card.html / category.html.

## Сделано

- `Agro-As/maintheme/inc/cpt-product.php`
- Подключение в `functions.php`

## Параметры

| Сущность | slug | Пример URL |
|----------|------|------------|
| CPT `product` | `product` | `/product/abeliya-abelia-edward-goucher/` |
| Taxonomy `product_cat` | `product-category` | `/product-category/rasteniya/` |

- `has_archive` у `product` — `false` (общий каталог — отдельно, через таксономию или страницу)
- `supports`: title, editor, thumbnail
- `with_front` => false для обоих rewrite
- `flush_rewrite_rules()` — только на `after_switch_theme` (не на каждом `init`)

## После деплоя

1. Настройки → Постоянные ссылки → Сохранить (если URL не открываются)
2. Далее: ACF-поля для карточки товара

## Следующий шаг

- ACF `group_agro_as_product` → поля по card.html
- Шаблоны `single-product.php`, `taxonomy-product_cat.php`
