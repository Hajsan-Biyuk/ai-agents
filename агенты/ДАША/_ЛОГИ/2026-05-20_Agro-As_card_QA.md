# QA карточки товара Agro-As — 2026-05-20

**Макет:** Figma `Cjr3iBGUSM61BjdELrvECB`, node `93:211` (КАРТОЧКА)  
**Файлы:** `Agro-As/card.html`, `css/main.css`, `css/media.css`, `js/common.js`  
**Проверяла:** @даша (статический аудит + спеки из кэша Figma)

---

## Вердикт

`VERDICT: FAIL` — desktop близко к макету, но есть Major по сетке 1200–1400px, табам и CTA.

`NEXT: invoke verstaller` (fix brief ниже)

---

## Таблица спеков (ключевые элементы)

| Элемент | Свойство | Макет | Код | OK? |
|---------|----------|-------|-----|-----|
| Контейнер | padding | 160px / контент 1600 | `.container-main` | ✅ |
| Крошки | font-size / lh | 14px / 160% | `.breadcrumbs` | ✅ |
| Крошки | margin-bottom | 24px (y 121→167) | 24px | ✅ |
| Галерея | размер | 653×602 | `.product-page__gallery` | ✅ |
| Галерея | border-radius | 20px | 20px | ✅ |
| Фото | object-fit | contain | contain | ✅ |
| Заголовок h1 | font-size / weight | 32px / 600 | `.product-page__title` | ✅ |
| Тег | padding / bg | 6×8 / #F6FEDD | `.product-page__tag` | ✅ |
| Статус | color | #95C12E | `var(--color-status)` | ✅ |
| Цена | font-size / color | 32px / #052D24 | `.product-page__price` | ✅ |
| Блок покупки | bg / radius / pad | #EFF3EB / 20px / 24px | `.product-page__buy` | ✅ |
| Счётчик | pad / radius | 14×20 / 16px | `.product-page__qty` | ✅ |
| Кнопка «Заказать» | radius / bg | 60px / #C9EC5F | `.product-page__order` | ✅ |
| Sticky | top | — | `top: 24px` ≥1200px | ✅ |
| Таб (все) | background | #EFF3EB | неактивный `#fff` | ❌ |
| Таб | pad / radius | 20×30 / 8px | совпадает | ✅ |
| Текст таба | font | 18px / 500 | `.product-page__text` | ✅ |
| «Рекомендуем» | font-size | 48px | `.title-section` | ✅ |
| Шрифты woff2 | HTTP | 200 | manrope/*.woff2 | ✅ |

---

## Critical / Major

### 1. Major — сетка hero на 1200–1400px

С `min-width: 1200px` заданы фиксированные колонки **653px + 382px** + гибкая info (~546px в макете).  
При viewport 1200–1400 контент уже **1200–1200px** (минус padding 40–100px), сумма **> ширины контейнера** → колонки ломаются / перенос buy-блока.

**Файлы:** `css/media.css` (блок `@media (min-width: 1200px)` для `.product-page__gallery-col` / `__buy-col`).

**Fix:** перенести фиксированные ширины на `min-width: 1400px` или `1600px`, либо перейти на `col-xl` в процентах без px-flex на узком xl.

---

### 2. Major — табы: фон неактивных пунктов

В Figma все кнопки табов (Component 14–18) с заливкой **#EFF3EB**.  
В коде неактивные — **#fff**, активный — #EFF3EB (инверсия).

**Файл:** `css/main.css` — `.product-page__tab` / `.product-page__tab.active`.

**Fix:** фон `#EFF3EB` у всех; активный — border, underline, `color`, или более тёмный `#052D24` по макету состояния.

---

### 3. Major — кнопка «Заказать» без сценария

`.product-page__order` — `<button>` без `fancybox` / `#modal-application`.  
Модалки в DOM есть, но CTA не подключён (на главной `.btn-order` та же ситуация).

**Fix:** `href="#modal-application" class="btn-main fancybox"` или обработчик в `common.js` по паттерну проекта.

---

## Minor

4. **Отступ** между статусом и блоком объёма: в макете gap **24px** в колонке, в коде `margin-bottom: 20px` у `.product-page__status` (−4px).  
5. **Mobile-фрейм** карточки в Figma не сверялся — нужен ретест с отдельным `node-id`.  
6. **Порядок блоков на tablet:** при стеке gallery → info (длинные характеристики) → buy — CTA внизу; уточнить по mobile-макету.  
7. **Кнопки «Заказать»** в слайдере «Рекомендуем» без модалки (наследие главной).  
8. **Нет ссылки** с карточек каталога на `card.html` (вне scope вёрстки, но для приёмки страницы полезно).

---

## Что прошло (не ломать без регрессии)

- `body.wrapper__default`, хедер inner через `.wrapper__default .header`
- Bootstrap `row` / `col-*`, **нет `gap` на `.row`**
- `main.css` без `@media`, адаптив в `media.css`
- Один `h1`, семантика, модалки в разметке
- Табы + счётчик + объём — JS в `common.js`
- `tabs-page` — якоря исключены
- Slick на `.products__slider`, dots <992
- `product-card.png`, `product-1.png` — файлы на месте
- Manrope woff2, font-weight 300 для specs — в variable 400–800 (синтетика возможна; на visual — приемлемо)

---

## Verstaller fix brief

1. **media.css:** фикс. 653/382 только с `min-width: 1400px` (или 1600px); на 1200–1399 — одна колонка или % cols.  
2. **main.css:** табы — фон #EFF3EB у всех, активное состояние по Figma.  
3. **card.html + common.js:** «Заказать» → `#modal-application` + `fancybox`.  
4. **main.css:** `.product-page__status { margin-bottom: 24px; }`.  
5. Ретест @даша после правок.

---

*Figma MCP: 0 запросов (спеки из кэша сессии node 93:211).*
