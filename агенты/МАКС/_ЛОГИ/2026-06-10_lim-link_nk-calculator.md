# 2026-06-10 — LIM-LINK: внедрение калькулятора стоимости НК

## Задача

Внедрить виджет калькулятора из `LIM-LINK/nk-calculator/` (вёрстка от @коля) в тему `LIM-LINK/maintheme/` (lim-lnk.ru). Скрипты — отдельным файлом, стили — в конец существующих CSS с комментариями, визуал — в стиле сайта. Шаблон страницы «Калькулятор» с общим хедером/футером (выбор шаблона вручную). Заявка — через новую CF7 «Рассчитать стоимость» (#order-calc), весь расчёт передаётся в поле `name="your-title"` (textarea).

## Созданные файлы

- `maintheme/js/nk-calculator.js` — логика калькулятора (jQuery, перенос из статики). Отличия от статики:
  - guard: не инициализируется на страницах без `#nk-calc-overlay`;
  - `submitRequest()`: сводка расчёта пишется в `#order-calc [name="your-title"]`, модалка калькулятора закрывается, `#order-calc` открывается через `$.fancybox.open({src:'#order-calc', type:'inline'})`;
  - удалены mailto-заглушка и скрытая форма `#nk-calc-request-form`.
- `maintheme/template-parts/nk-calculator.php` — разметка модалки (классы `nk-calc-*` сохранены 1:1, инлайн-стиль заменён классом `nk-calc-choice--in-subblock`, ссылка результатов ведёт на `/contacts/`).
- `maintheme/page-calculator.php` — `Template Name: Калькулятор`, структура как `page.php` (breadcrumbs, `title-section`, `the_content`) + кнопка `.btn-main.js-nk-calc-open` + `get_template_part('template-parts/nk-calculator')`.

## Изменённые файлы

- `maintheme/footer.php`:
  - добавлена модалка `#order-calc` (по образцу `#callback` / `#order-cases`), шорткод CF7 с заглушкой id `XXXXXXX` + TODO;
  - подключён `js/nk-calculator.js?ver=1.0` после `common.js`.
- `maintheme/css/main.css` — в конец блок `/* Калькулятор стоимости НК */`: стили адаптированы под сайт (акцент `#b90504` вместо зелёного, фон `#f5f3f4`, границы `#d3d3d3`, radius 3px у полей, кнопки без скруглений в стиле `.btn-main` / Oswald, заголовки Oswald uppercase). Для radio/checkbox внутри `.nk-calc-choice` возвращён `appearance: auto` (глобально в теме сброшен).
- `maintheme/css/media.css` — в конец адаптив калькулятора (992px / 480px).
- `maintheme/header.php` — бамп `?ver=`: main.css → 1.126, media.css → 1.124.

## Что сделать в админке

1. Создать страницу, назначить шаблон «Калькулятор».
2. Создать форму CF7 «Рассчитать стоимость» с полем `[textarea your-title]` (сводка многострочная) и подставить реальный id в `footer.php` вместо `XXXXXXX`.

## Грабли

- Глобальный сброс `input { appearance: none }` в теме прячет нативные radio/checkbox — для виджетов с нативными контролами возвращать `appearance: auto` точечно.
- В `common.js` `[name=your-title]` для `#order-cases` ставится `readonly` — для `#order-calc` readonly не ставим, селектор там привязан к `a[href="#order-cases"]`, конфликта нет.
