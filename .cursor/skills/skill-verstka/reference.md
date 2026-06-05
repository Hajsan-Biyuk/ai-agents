# Справочник: сетка, типичные ошибки, примеры

Читать при спорных случаях с Bootstrap или адаптивом.

## Bootstrap: отступы без gap на .row

**Нельзя:**

```css
.row {
  gap: 20px; /* запрещено для .row */
}
```

**Можно:** gutter через padding колонок (Bootstrap по умолчанию) или margin у элементов **внутри** колонки, не на `.row`.

Для flex/grid-контейнеров **без** класса `.row` правило про `gap` не действует — следовать паттернам проекта.

## Типовая разметка секции

```html
<section class="block-name">
  <div class="container">
    <div class="row">
      <div class="col-lg-6">...</div>
      <div class="col-lg-6">...</div>
    </div>
  </div>
</section>
```

## Breakpoints (ориентир Bootstrap 5)

| Класс | min-width |
|-------|-----------|
| sm | 576px |
| md | 768px |
| lg | 992px |
| xl | 1200px |
| xxl | 1400px |

В `media.css` дублировать значения из макета; при расхождении — **макет важнее**.

## Типичные ошибки

| Ошибка | Как правильно |
|--------|----------------|
| `gap` на `.row` | Gutter Bootstrap или margin внутри колонок |
| rem/em «на глаз» | px по макету, если проект на px |
| Абсолютные пути в прод-коде | Относительные от корня шаблона/страницы |
| Все стили в одном файле | `main.css` + отдельный `media.css` |
| Инлайн-стили из Figma | Классы в CSS |
| Забытые hover/focus | Состояния из макета |
| Лишние div-обёртки | Минимальная валидная разметка |

## fonts.css (шаблон)

```css
@font-face {
  font-family: 'Montserrat';
  src: url('../fonts/Montserrat-Regular.woff2') format('woff2');
  font-weight: 400;
  font-style: normal;
  font-display: swap;
}
```

## media.css (шаблон)

```css
@media (max-width: 991px) {
  .block-name__title {
    font-size: 28px;
  }
}
```

## common.js (шаблон)

```javascript
$(function () {
  $('.js-menu-toggle').on('click', function () {
    $('.header').toggleClass('is-open');
  });
});
```

## Согласование с User Rules в Cursor

При конфликте приоритет:

1. Явная инструкция пользователя в текущем чате
2. Правила проекта (`.cursor/rules`, README шаблона)
3. Этот skill + глобальные User Rules
4. Общие best practices
