# Ретест карточки Agro-As — 2026-05-20

**После fix brief @коля**  
**Файлы:** `card.html`, `css/main.css?ver=6.3`, `css/media.css?ver=5.8`, `js/common.js?ver=3.8`

---

## Вердикт

`VERDICT: PASS` — все Major из первичного QA закрыты.

---

## Проверка fix brief

| # | Задача | Статус | Доказательство |
|---|--------|--------|----------------|
| 1 | Сетка 1200–1400px | ✅ | `col-xl-5/4/3` в HTML; фикс. 653/382 только `@media (min-width: 1400px)` |
| 2 | Табы #EFF3EB | ✅ | `.product-page__tab` → `background: var(--color-green-bg)`; active — `inset box-shadow` |
| 3 | «Заказать» → модалка | ✅ | `<a href="#modal-application" class="btn-main fancybox">` |
| 4 | Отступ статуса 24px | ✅ | `.product-page__status { margin-bottom: 24px; }` |

---

## Регрессия

- `body.wrapper__default`, inner-header — без изменений ✅
- Нет `gap` на `.row` ✅
- Галерея 653×602, contain — без изменений ✅
- Sticky buy с 1400px ✅
- Табы / счётчик / объём в `common.js` ✅
- Slick «Рекомендуем» ✅
- Шрифты woff2 на месте ✅

---

## Minor (не блокируют PASS)

1. Mobile-фрейм Figma не сверялся — отдельный ретест при появлении node-id.  
2. `.btn-order` в слайдере «Рекомендуем» без модалки (как на главной).  
3. Активный таб: в статичном Figma все вкладки одинаковые; в коде active с `inset` border — допустимое UX-отличие.

---

## Итог pipeline

```
@коля (fix) → @даша (ретест) → PASS ✅
```
