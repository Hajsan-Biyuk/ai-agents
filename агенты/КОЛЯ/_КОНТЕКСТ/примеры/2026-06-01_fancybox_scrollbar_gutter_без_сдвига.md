# 2026-06-01 — Fancybox: без сдвига страницы при открытии модалки

## Симптом

При клике на `.fancybox` (модалки «Заказать звонок», `#callback`, `#modal-callback` и т.д.) страница **дёргается влево**; при закрытии возвращается.

## Причина

1. Fancybox 3 по умолчанию: `hideScrollbar: true` → в `<head>` вставляется стиль  
   `.compensate-for-scrollbar { margin-right: Npx }` (ширина скроллбара).
2. Если на `html` уже есть **`scrollbar-gutter: stable`** (фиксация прыжка при переходах / блокировке скролла), получается **двойная компенсация** → визуальный сдвиг влево.

## Решение (применять на всех новых проектах с Fancybox)

### 1. CSS — резерв под скроллбар на `html`

```css
html {
  overflow-x: hidden;
  scrollbar-gutter: stable;
}
```

Файл: `css/main.css` (в начале, после reset).

### 2. JS — отключить компенсацию Fancybox

**Глобально** (до `$(document).ready`):

```js
if (typeof jQuery !== 'undefined' && jQuery.fancybox) {
  jQuery.fancybox.defaults.hideScrollbar = false;
}
```

**Инициализация** `$(".fancybox").fancybox({ ... })`:

```js
$(".fancybox").fancybox({
  autoFocus: false,
  backFocus: false,
  hideScrollbar: false,
  afterShow: function (instance, current) {
    if (current && current.$content) {
      current.$content.find(".input-phone").mask("+7 (999) 999-99-99");
    }
  },
  afterClose: function () {
    $("#fancybox-style-noscroll").remove();
    $("body").css("padding-right", "");
  }
});
```

Прокрутка фона при открытой модалке по-прежнему блокируется: `body.compensate-for-scrollbar { overflow: hidden }` (стили Fancybox). Место под полосу прокрутки держит `scrollbar-gutter` на `html`.

### 3. CSS — страховка (app.css или main.css)

На случай старого кэша / других скриптов:

```css
body.compensate-for-scrollbar {
  margin-right: 0 !important;
  padding-right: 0 !important;
}
.page .header.default.compensate-for-scrollbar {
  margin-right: 0 !important;
  padding-right: 0 !important;
}
```

Для фиксированной шапки — селектор под проект (`.header`, `.hero-header`).

## Когда подключать

- Подключаете **Fancybox** для модалок с формами или галереи.
- На сайте есть **вертикальный скролл** и фиксированный header.
- Жалобы на «прыжок» / сдвиг влево при открытии модалки.

## Эталон реализации

| Что | Путь |
|-----|------|
| Berega (проверено) | `Berega/berega.ru/public_html/local/templates/berega/js/common.js` |
| Berega CSS | `css/main.css` (`html`), `css/app.css` (страховка) |
| Базовая модалка | `примеры/2026-05-19_модалка_заказать_звонок.md` |

## Не путать с

- **Прыжок при смене страницы** — тоже лечится `scrollbar-gutter: stable` на `html`, без Fancybox.
- **Якоря** — не трогать ссылки с классом `.fancybox` в обработчике скролла.

## Результат

Оценка: ✅ Berega, июнь 2026
