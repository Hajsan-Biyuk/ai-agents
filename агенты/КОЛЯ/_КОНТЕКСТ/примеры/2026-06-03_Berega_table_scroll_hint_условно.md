# 2026-06-03 — Berega: подсказка скролла таблицы только при переполнении

## Задача

На мобилке (≤992px) полоса «Листайте таблицу вбок» с иконкой не должна показываться на **узких** таблицах, которые целиком помещаются в экран.

## Решение

### 1. JS (`local/templates/berega/js/common.js`)

После `initArticleTableSwipeWrap()` вызывается `initTableScrollHint()`:

- обёртки: `.table-scroll-wrap`, `.ok-uk-table-wrap`, `.zs-dev-table-wrap`, `.yk-table-wrap`, `.wp-block-table` в статьях;
- на ≤992px сравнивается `scrollWidth` и `clientWidth` у **прямого** дочернего `table` (элемент с `overflow-x: auto`);
- при переполнении на обёртку вешается класс **`is-table-scrollable`**;
- пересчёт: `resize`, `load`, смена `matchMedia`, debounce 100 ms, двойной `requestAnimationFrame` после инициализации.

```js
function tableNeedsHorizontalScroll(el) {
  if (!el) return false;
  return el.scrollWidth > el.clientWidth + 1;
}
```

### 2. CSS (`media.css`, блок ≤992px)

Подсказка только у обёртки с классом:

```css
.table-scroll-wrap.is-table-scrollable::before { ... }
```

Если класса нет — таблица в `grid-row: 1`, скругление со всех сторон (`:not(.is-table-scrollable) > table`).

### 3. Разметка статей

Таблицы в `.js-article-body` по-прежнему оборачиваются в `.table-scroll-wrap` скриптом; в каталогах — готовые `*-table-wrap` в HTML.

## Проверка

1. Узкая таблица (2–3 колонки) на ширине 375px — **без** полосы и иконки.
2. Широкая таблица (много колонок) — полоса и анимация стрелок.
3. Поворот экрана / resize — класс снимается/добавляется.
4. Desktop >992px — класс снимается, подсказки нет.

## Файлы

- `Berega/berega.ru/public_html/local/templates/berega/js/common.js`
- `Berega/berega.ru/public_html/local/templates/berega/css/media.css`
