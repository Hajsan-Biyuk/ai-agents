# Agro-As — паттерн каталога (taxonomy-product_cat)

**Обязательно для @макс:** натяжка `category.html` на таксономию `product_cat` — **только по этому документу**.

**Эталон статики:** `Agro-As/category.html`  
**Эталон WP:** `Agro-As/maintheme/taxonomy-product_cat.php` + `template-parts/catalog/`  
**CPT:** `_КОНТЕКСТ/cpt-wordpress-pattern.md`  
**Карточка:** `_КОНТЕКСТ/product-single-wp-pattern.md` → `template-parts/product/card.php`

---

## 1. Структура шаблонов

```
maintheme/
├── taxonomy-product_cat.php
└── template-parts/catalog/
    ├── content.php       ← section.catalog-page
    ├── breadcrumbs.php
    ├── sidebar.php       ← дерево product_cat
    └── pagination.php
```

**Правила:**
- H1 — имя текущего term
- Сетка товаров — `template-parts/product/card.php` в `.catalog-page__grid`
- Без дефолтного контента — только опубликованные `product`

---

## 2. Сайдбар

| Условие | Класс / разметка |
|---------|------------------|
| Родитель активной категории | `.catalog-sidebar__item--open` |
| Текущая подкатегория | `.catalog-sidebar__sub a.active` |
| Нет дочерних term | один пункт-ссылка на сам parent |

Дерево: top-level `product_cat` → children через `get_terms( parent => … )`.

JS (`common.js`): аккордеон `.catalog-sidebar__head`, мобильный `.btn-main_filter`.

---

## 3. Сортировка

Select `#catalog-sort` → GET `?sort=`:

| value | orderby |
|-------|---------|
| `default` | `date DESC` |
| `price` | `meta_value_num` по `_maintheme_min_price ASC` |
| `date` | `date DESC` |
| `alpha` | `title ASC` |
| `stock` | `meta_value_num` по `_maintheme_stock_order ASC` |

- Хук `pre_get_posts` на `is_tax( 'product_cat' )`
- `posts_per_page` = **12**
- При смене sort — reload без `/page/N/` (сброс на 1-ю страницу)

Meta `_maintheme_min_price` / `maintheme_min_price` — при сохранении ACF (`maintheme_sync_product_min_price_meta`).

Meta `_maintheme_stock_order` — при сохранении ACF (`maintheme_sync_product_stock_meta`):

| `status_nalichiya` | order |
|--------------------|-------|
| `v_nalichii` | 1 |
| `pod_zakaz` | 2 |
| `net_v_nalichii` | 3 |

---

## 4. Пагинация

Разметка `.catalog-page__pager` как в `category.html`:

- prev / nums / next
- URL через `maintheme_catalog_page_link()` — сохраняет `?sort=`

---

## 5. Крошки

`Главная / Каталог / [Родитель] / Текущая категория`

- «Каталог» → `maintheme_catalog_url()` (страница `catalog`, иначе первая top-level категория)

---

## 6. Меню

`maintheme_nav_is_active( 'catalog' )` — `is_singular( 'product' ) || is_tax( 'product_cat' )`.

---

## 7. Ассеты

- `main.css?ver=8.5` — `.catalog-sidebar__sub a.active`
- `common.js?ver=4.3` — `#catalog-sort` change handler

Синхрон статики: `Agro-As/css/main.css`, `Agro-As/js/common.js`.

---

## 8. Не в scope

- Landing `/catalog/` без term (отдельная страница — опционально)
- Фильтры кроме категорий в сайдбаре
