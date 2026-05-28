# 2026-05-27 — Agro-As: слайдер товаров (главная + «Рекомендуем»)

## Где

| Блок | Разметка | JS |
|------|----------|-----|
| Главная, секция `.products` | `template-parts/front/products.php` | `initFrontProductsSlider()` |
| Карточка товара, `.product-page__recommend` | `template-parts/product/recommend.php` | `initProductPageRecommendSlider()` |

Общая логика: **`initProductsCardsSlider()`** в `Agro-As/maintheme/js/common.js`.

Карточка: `maintheme_product_card_data()` → `template-parts/product/card.php`.

---

## Отступы между карточками

**Только на `.products__slide`**, не на `.slick-slide`:

```css
.products__slide {
	box-sizing: border-box;
	padding-left: 8px;
	padding-right: 8px;
}
```

- **Без** отрицательных `margin` у `.products__slider`
- **Без** padding у `.slick-slide`
- Отступ **внутри** ширины ячейки — слайды **не расширяются**

---

## Ширина карточки

```css
.item-product {
	max-width: 382px;
}

.products__slider:not(.slick-initialized) .item-product,
.products__slider.slick-initialized .item-product {
	width: 100%;
	max-width: 382px;
}
```

**Запрещено:** `.products__slider.slick-initialized .item-product { max-width: none; }` — карточки растягиваются на всю колонку Slick.

---

## Slick vs flex-сетка

**Slick включается только если товаров больше, чем видно на текущем breakpoint** (`productsSliderNeedsNav`).

| Ширина | Колонок | Slick, если товаров |
|--------|---------|---------------------|
| ≥1201px | 4 | > 4 |
| 949–1200 | 3 | > 3 |
| 576–948 | 2 | > 2 |
| ≤575 | 1 | > 1 |

**Если листать нечего** — Slick **не инициализируется**, работает flex-сетка:

| Ширина | `.products__slide` |
|--------|---------------------|
| ≥1201px | `flex: 0 0 25%` |
| 949–1200 | `33.333333%` |
| 576–948 | `50%` |
| ≤575 | `100%` |

**Почему:** при `slidesToShow: Math.min(2, count)` и 2 товарах Slick даёт две колонки по **50% экрана**, а карточка 382px — огромный зазор между карточками.

---

## Стрелки

- Класс на layout: **`products-slider-layout--scrollable`** (только когда `needsNav`)
- Колонки стрелок скрыты по умолчанию, показываются через этот класс
- Внешние кнопки: `.slick-prev` / `.slick-next` в `.products__slider-prev` / `-next` (главная) или `.product-page__recommend-prev` / `-next`
- Dots — с **992px** и ниже, только если `slideCount > slidesToShow`

---

## Разметка (главная)

```html
<div class="row align-items-center products__slider-layout">
	<div class="col-auto products__slider-arrow-col products__slider-arrow-col--prev">…</div>
	<div class="col products__slider-main">
		<div class="products__slider">
			<div class="products__slide">… card.php …</div>
		</div>
	</div>
	<div class="col-auto products__slider-arrow-col products__slider-arrow-col--next">…</div>
</div>
```

---

## Антипаттерны

1. Padding на `.slick-slide` + negative margin на `.products__slider` «для gutter»
2. `max-width: none` на `.item-product` в слайдере
3. Slick при `slideCount ≤ slidesToShow` — карточки разъезжаются
4. `slidesToShow: Math.min(N, slideCount)` когда все товары помещаются — лишняя ширина колонок

---

## Файлы (WP-тема)

- `Agro-As/maintheme/js/common.js` — `getProductsSliderSlidesToShow`, `productsSliderNeedsNav`, `initProductsCardsSlider`
- `Agro-As/maintheme/css/main.css` — `.products__slide`, стрелки, `.item-product` в слайдере
- `Agro-As/maintheme/css/media.css` — flex-колонки для `:not(.slick-initialized)`

## Результат

Оценка: ✅ Отлично (после итераций: отступы на slide, стрелки по смыслу, fix растягивания и зазора при 2 товарах)
