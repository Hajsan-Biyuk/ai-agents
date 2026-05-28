# Agro-As — паттерн карточки товара (single-product)

**Обязательно для @макс:** натяжка `card.html` на CPT `product` — **только по этому документу**.

**Эталон статики:** `Agro-As/card.html`  
**Эталон WP:** `Agro-As/maintheme/single-product.php` + `template-parts/product/`  
**ACF:** `group_agro_as_product.json`  
**CPT:** `_КОНТЕКСТ/cpt-wordpress-pattern.md`

---

## 1. Структура шаблонов

```
maintheme/
├── single-product.php
└── template-parts/product/
    ├── content.php       ← section.product-page
    ├── breadcrumbs.php
    ├── hero.php          ← галерея, теги, статус, объём, specs preview, buy
    ├── tabs.php          ← post_content + ACF табы
    ├── recommend.php     ← слайдер рекомендуемых
    └── card.php          ← item-product (каталог + recommend)
```

**Правила:**
- Без дефолтного контента — `maintheme_is_filled()` перед выводом
- Описание — **`post_content`**, не ACF
- Табы выводить **только если есть контент**

---

## 2. ACF → разметка

| Поле | Блок |
|------|------|
| `galereya` | `.product-page__gallery` |
| `tegi` | `.product-page__tag` |
| `status_nalichiya` | `.product-page__status` + модификатор |
| `nazvanie_bloka_obema` | `.product-page__volume-label` |
| `variacii` | `.product-page__volume-item` + `.product-page__price` |
| `harakteristiki` | preview + таб «Характеристики» |
| `primenenie` | таб «Применение» |
| `otzyvy_zaglushka` | таб «Отзывы» |
| `rekomenduemye_tovary` | `.product-page__recommend-main` |

---

## 3. Галерея + Fancybox (lightbox)

### Разметка (hero.php)

Каждое фото ACF gallery — ссылка + img:

```php
$gallery_group = 'product-gallery-' . $post_id;
```

```html
<div class="product-page__gallery product-page__gallery--slider"><!-- --slider если count > 1 -->
  <div class="product-page__gallery-slide">
    <a href="{full_url}"
       class="product-page__gallery-link fancybox"
       data-fancybox="product-gallery-{post_id}"
       data-caption="{alt}">
      <img src="{full_url}" alt="{alt}">
    </a>
  </div>
</div>
```

| Фото | Поведение на странице | Lightbox |
|------|----------------------|----------|
| 1 | статичный блок | увеличение |
| 2+ | slick `.product-page__gallery--slider` | листание стрелками/свайпом в группе |

### JS (`common.js`)

- `initProductGallerySlider()` — slick на `.product-page__gallery--slider`
- `$('.fancybox').fancybox({ loop: true, ... })` — группа по `data-fancybox`
- Модалки форм (`#modal-application`) — тот же `.fancybox`, href `#...` (inline)

### CSS

```css
.product-page__gallery-link { cursor: zoom-in; }
.product-page__gallery--slider .slick-dots { ... }
```

**Не использовать** отдельный lightbox-плагин — только Fancybox из `libs/fancybox-master/`.

---

## 4. Вариации объёма + цена

Кнопки с data-атрибутами:

```html
<button type="button"
  class="product-page__volume-item active"
  data-volume="10 л"
  data-price="2 284 ₽">10</button>
```

JS при клике:
- `.product-page__volume-label strong` ← `data-volume`
- `.product-page__price` ← `data-price`

PHP: `maintheme_get_product_variations()`, `maintheme_product_variation_volume_text()`.

---

## 5. Характеристики preview (6 строк + toggle)

- Строки **7+** — класс `product-page__specs-row--hidden`
- CSS (**после** `.product-page__specs-row`):

```css
.product-page__specs-row.product-page__specs-row--hidden { display: none; }
.product-page__specs-preview--expanded .product-page__specs-row.product-page__specs-row--hidden { display: flex; }
```

- Кнопка `.product-page__specs-all` — toggle класса `product-page__specs-preview--expanded`
- Текст: «Все характеристики» ↔ «Скрыть» (`data-label-show` / `data-label-hide`)
- Таб «Характеристики» — **полный** repeater, чередование `--alt`

---

## 6. Статус наличия

| ACF value | Label | CSS |
|-----------|-------|-----|
| `v_nalichii` (default) | Есть в наличии | `--in-stock` |
| `net_v_nalichii` | Нет в наличии | `--out-of-stock` |
| `pod_zakaz` | Под заказ | `--preorder` |

Хелперы: `maintheme_product_status_label()`, `maintheme_product_status_class()`.

---

## 7. Рекомендуемые товары

`maintheme_get_recommended_products( $post_id, 6 )`:

1. Если `rekomenduemye_tovary` заполнено — relationship
2. Иначе — **6 random** из той же `product_cat`, без текущего

Карточка: `maintheme_product_card_data()` → `template-parts/product/card.php`.

**Цена в карточке:** meta `maintheme_min_price` (минимум из `variacii`).  
**Сортировка каталога по цене:** meta `_maintheme_min_price` (float).  
Синхронизация: `maintheme_sync_product_min_price_meta()` на `acf/save_post`.

---

## 8. Меню и крошки

- «Каталог» active: `maintheme_nav_is_active('catalog')` → `is_singular('product') || is_tax('product_cat')`
- Крошки: `maintheme_product_catalog_url()` — первая категория или `/catalog/`

---

## 9. Хелперы (inc/helpers.php)

| Функция | Назначение |
|---------|------------|
| `maintheme_get_product_specs()` | Заполненные строки характеристик |
| `maintheme_get_product_variations()` | Repeater + active index |
| `maintheme_product_variation_volume_text()` | «10 л» для label/data |
| `maintheme_product_card_data()` | Данные item-product |
| `maintheme_get_recommended_products()` | Рекомендуемые + fallback |
| `maintheme_product_catalog_url()` | URL «Каталог» в крошках |

---

## 10. Чеклист перед сдачей

- [ ] Разметка 1:1 с `card.html` (классы не менять)
- [ ] Пустые ACF не выводят HTML
- [ ] Галерея: fancybox + slick при >1 фото
- [ ] Specs: 6 строк, toggle кнопки
- [ ] Вариации меняют цену
- [ ] `esc_html` / `esc_url` / `esc_attr`
- [ ] Bump `?ver=` css/js при правках
- [ ] Лог в `агенты/МАКС/_ЛОГИ/`

---

## 11. Связанные файлы

| Документ | Путь |
|----------|------|
| Канон Agro-As | `_КОНТЕКСТ/agro-as-wp-pattern.md` |
| CPT product | `_КОНТЕКСТ/cpt-wordpress-pattern.md` |
| Каталог product_cat | `_КОНТЕКСТ/product-catalog-wp-pattern.md` |
| Лог single | `_ЛОГИ/2026-05-25_agro-as_single-product.md` |
| Лог каталог | `_ЛОГИ/2026-05-25_agro-as_product-catalog.md` |
| Лог ACF | `_ЛОГИ/2026-05-25_agro-as_product_acf.md` |
