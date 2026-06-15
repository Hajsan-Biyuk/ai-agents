# Внедрение JS-калькулятора (виджет-модалка) в тему WP — LIM-LINK

Оценка заказчика: ✅ Отлично. Проект: lim-lnk.ru, тема `maintheme`, калькулятор стоимости НК.

## Паттерн: статичный jQuery-виджет → WordPress

Источник: вёрстка от @коля (`nk-calculator/`: index.html + css + js/common.js).

### Карта переноса

| Статика | Тема WP |
|---|---|
| разметка модалки из `index.html` | `template-parts/nk-calculator.php` → `get_template_part()` |
| `js/common.js` виджета | отдельный `js/nk-calculator.js`, подключение в `footer.php` после `common.js` |
| `css/main.css` виджета | в конец `css/main.css` темы с комментарием-шапкой |
| `css/media.css` виджета | в конец `css/media.css` темы с комментарием-шапкой |
| демо-блок запуска | `page-calculator.php` (`Template Name:`, выбор вручную) с кнопкой `.btn-main.js-nk-calc-open` |

### Ключевые решения

1. **Guard в JS:** скрипт подключён глобально в footer, поэтому первая строка — выход, если `$('#nk-calc-overlay').length === 0`.
2. **Передача результата в CF7:** новая модалка `#order-calc` в `footer.php` по образцу существующих (`#callback`), поле `[textarea your-title]`. JS: сводка → `$('#order-calc').find('[name="your-title"]').val(summary)` → `$.fancybox.open({src:'#order-calc', type:'inline'})`. Id формы CF7 — заглушка `XXXXXXX` + TODO, заказчик создаёт форму сам.
3. **Адаптация стилей под сайт:** цвета (зелёный → акцент темы `#b90504`, фоны `#f5f3f4`, бордеры `#d3d3d3`), шрифты (заголовки Oswald uppercase, текст Roboto Flex), скругления как у форм темы (3px), кнопки в стиле `.btn-main`. Классы `nk-calc-*` не переименовывать.
4. **Грабли — глобальный сброс инпутов:** в теме `input { -webkit-appearance: none }` прячет нативные radio/checkbox → точечно вернуть `appearance: auto; accent-color: <акцент>` внутри виджета.
5. **`is-hidden` не объявлять глобально** в теме: скоупить (`.nk-calc-overlay .is-hidden`), чтобы не задеть чужие классы.
6. **Инлайн-стили из статики** заменять модификаторами (`style="margin-bottom:12px"` → `.nk-calc-choice--in-subblock`).
7. **Бамп `?ver=`** у main.css/media.css в `header.php` после правок.
8. **Ссылки в виджете** сверять со slug'ами реальных страниц (в статике `/kontakty/`, в теме `page-contacts.php` → `/contacts/`); подтвердить у заказчика.

## Файлы результата

- `LIM-LINK/maintheme/js/nk-calculator.js`
- `LIM-LINK/maintheme/template-parts/nk-calculator.php`
- `LIM-LINK/maintheme/page-calculator.php`
- правки: `footer.php`, `header.php`, `css/main.css`, `css/media.css`

Полный лог: `_ЛОГИ/2026-06-10_lim-link_nk-calculator.md`
