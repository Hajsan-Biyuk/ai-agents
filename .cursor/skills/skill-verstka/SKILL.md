---
name: skill-verstka
description: Правила pixel-perfect вёрстки современных сайтов на HTML, CSS, Bootstrap, jQuery и Figma. Обязательный pipeline — verstaller (вёрстка) → tester (проверка) → при багах снова verstaller. Содержит эталонный стек (zarina-стиль): подключение libs/fonts/main/media с ?ver=, cross-browser нормализация, фиксированная шапка, формы, slick, fancybox, desktop-first брейкпоинты 1800/1730/1400/1200/992. Использовать при любых задачах вёрстки, Figma → код, main.css/media.css, Bootstrap, адаптиве, common.js — когда пользователь просит сверстать, сверстать по макету, HTML/CSS, лендинг, мобильную версию или прикрепляет skill-verstka.
---

# Skill-verstka — правила вёрстки

Старший front-end: HTML, CSS, JavaScript, jQuery. Цель — **pixel-perfect** макет, чистый поддерживаемый код, минимальный diff.

## Когда применять

- Новая страница, секция, компонент, лендинг
- Figma → код, правки по скрину/макету
- Bootstrap-разметка, `media.css`, интерактив в `common.js`
- Проект уже имеет структуру — **сначала** смотреть существующие шаблоны и повторять их

## Pipeline субагентов (обязателен)

Любую задачу на **вёрстку** (HTML, CSS, Bootstrap-сетка, `media.css`, `common.js`, перенос из Figma) выполняет **только** субагент **verstaller** (`~/.cursor/agents/verstaller.md`). Родительский агент **не пишет вёрстку сам** — делегирует через Task с `subagent_type="verstaller"`.

После завершения **verstaller** родитель **обязательно** запускает **tester** (`~/.cursor/agents/tester.md`) на **всю** затронутую вёрстку (все изменённые страницы/секции, breakpoints, ассеты, интерактив).

| Шаг | Кто | Действие |
|-----|-----|----------|
| 1 | **verstaller** | Вёрстка по этому skill и макету |
| 2 | **tester** | Полная проверка результата (см. чеклист tester для layout) |
| 3a | **tester** → `VERDICT: PASS` | Сообщить пользователю итог |
| 3b | **tester** → `VERDICT: FAIL` | Передать **verstaller** fix brief из отчёта tester |
| 4 | **verstaller** | Исправления только по brief |
| 5 | **tester** | Повторная проверка того же scope (регрессия + исходные баги) |

Повторять шаги **4 → 5**, пока tester не выдаст **PASS** или **BLOCKED** (лимит итераций — в `tester.md`).

**Родительскому агенту запрещено:**
- считать вёрстку готовой без **PASS** от tester;
- править HTML/CSS вёрстки в обход verstaller (кроме явной просьбы пользователя «без субагентов»);
- пропускать tester после verstaller.

**В prompt для verstaller** передавать: scope, макет/Figma, файлы проекта, требование pixel-perfect, правила этого skill и **явное требование соблюдать раздел «Figma MCP: лимиты API»** (без лишних запросов к макету).

**В prompt для tester** передавать: что сделал verstaller, список файлов, acceptance criteria, breakpoints для проверки, ссылку на макет при наличии.

## Глобальные правила Cursor (обязательны)

Эти правила **не отменяются** локальными договорённостями проекта, если пользователь явно не сказал иное.

### Коммуникация и процесс

- Отвечать пользователю **на русском**.
- Сложную задачу: краткий план (секции, сетка, типографика, состояния) → затем полная реализация.
- Менять **только** то, что нужно для задачи; без «приборки» соседнего кода.
- Перед правками **читать** окружающий код: имена, отступы, BEM/префиксы, порядок подключений.
- Доставлять **завершённый** результат: без пустых `TODO`, если не просили заглушку.

### Запреты и ограничения

- **Запрещено** добавлять `gap` в CSS для Bootstrap-блоков с классом `.row`.
- **Запрещено** `import` / `export` типов (в TS-проектах — без type-only импортов).
- Не создавать markdown-файлы, которые пользователь не просил.

### Качество кода

- DRY, читаемость важнее микрооптимизаций.
- Переиспользовать существующие утилиты и компоненты проекта.
- UI: единые отступы, иерархия, hover/focus/active по макету.

## Структура файлов (greenfield)

Если в проекте нет своей схемы — использовать:

```
css/
  fonts.css    — @font-face
  main.css     — база и компоненты (значения в px)
  media.css    — только media queries
fonts/         — файлы шрифтов с макета
js/
  common.js    — скрипты на jQuery
img/           — экспортированная графика
index.html     — или страница по задаче
```

Подключение в HTML: `fonts.css` → `main.css` → `media.css` (или как в проекте).

## Стек

| Область | Правило |
|--------|---------|
| Сетка | **Bootstrap** (container / row / col) |
| CSS | **px**, если проект не задаёт иное |
| JS | **jQuery** в `js/common.js`, делегирование событий |
| HTML | Семантика, доступность, осмысленные классы |

## Pixel-perfect и Figma

1. Снять с макета: отступы, размеры, цвета, шрифты, line-height, радиусы, тени, breakpoints.
2. **Скачать** графику из макета; при работе с Figma MCP — абсолютные пути при экспорте, в коде проекта — **относительные**.
3. Сверстать **1:1**: сетка, типографика, отступы, цвета, размеры.
4. Адаптив — в `media.css`; desktop-first или mobile-first — как в макете или в проекте.
5. Если есть Figma MCP — брать спеки из файла, не угадывать.

## Figma MCP: лимиты API (verstaller — строго обязательно)

**verstaller** при обращении к макету Figma **обязан** минимизировать число MCP-вызовов. Превышение лимита Figma API блокирует вёрстку — это критический сбой pipeline.

### Запрещено

- Вызывать `get_figma_data` **без** `nodeId` по всему файлу, если в ссылке/ТЗ указан фрейм или `node-id` — всегда запрашивать **только нужный узел**.
- Передавать параметр `depth`, если пользователь **явно** не просил углубить обход дерева (по умолчанию depth **не использовать**).
- Делать **повторные** запросы тех же `fileKey` + `nodeId`, если ответ уже есть в контексте сессии — сначала искать в истории/tool-результатах.
- Дёргать Figma **параллельно** пачкой из 5+ вызовов «на всякий случай» — только **последовательно** и по плану.
- Вызывать `use_figma` для чтения спеков (отступы, цвета, шрифты), если достаточно **Framelink** `get_figma_data` / уже скачанных данных.
- В цикле «запрос → не хватило поля → ещё запрос» по каждому дочернему узлу — сначала поднять scope до **родительского фрейма секции**, один раз снять дерево, дальше работать из кэша.
- При ответе **429 / rate limit / too many requests** — бесконечно ретраить; после первого лимита **остановиться**, сообщить родителю `BLOCKED: Figma rate limit`, продолжать только по уже полученным данным или после паузы и **одного** повторного узкого запроса.

### Обязательный порядок работы

1. **План до API:** scope (страница / секция / компонент), список нужных спеков и ассетов, `fileKey` и `nodeId` из URL.
2. **Один обзорный запрос** на секцию: `get_figma_data` с `fileKey` + `nodeId` корневого фрейма задачи (Desktop / Mobile — отдельные nodeId, не весь файл).
3. **Зафиксировать в заметках** токены: цвета, типографика, отступы, размеры блоков — не перечитывать макет ради одного `padding`.
4. **Ассеты пакетом:** один `download_figma_images` со **всем** списком `nodes` за проход (разумный батч до ~15–20 файлов); при большем числе — 2–3 батча по секциям, не по одной иконке.
5. **Дочерние узлы** — только если в первом ответе нет нужных данных; запрос с **конкретным** `nodeId` проблемного блока, не повтор всего экрана.
6. **Самопроверка перед новым вызовом:** «Можно ответить из уже полученного JSON?» — если да, **новый запрос запрещён**.

### Лимиты на сессию (ориентир)

| Действие | Норма на задачу |
|----------|------------------|
| `get_figma_data` | 1–3 (обзор секции + при необходимости mobile / вложенный блок) |
| `download_figma_images` | 1–3 батча |
| `use_figma` | 0, пока не нужна запись в файл; для вёрстки почти всегда **0** |

Превышение без обоснования в отчёте verstaller = нарушение skill.

### В ответе verstaller родителю

Кратко указать: сколько было Figma MCP-вызовов и по каким `nodeId` (чтобы tester/родитель видели экономию запросов).

## Bootstrap

- Сетка через `.container` / `.row` / `.col-*`.
- Отступы между колонками — **gutter Bootstrap** (`padding` колонок, отрицательные margin у `.row`), **не** `gap` на `.row`.
- Утилиты Bootstrap — по необходимости; не дублировать их кастомным CSS без причины.
- Лишние обёртки не добавлять.

## CSS

- Базовые и компонентные стили — `main.css`.
- Все `@media` — только `media.css`.
- Имена классов — в стиле проекта (BEM, префикс блока и т.д.).
- Состояния `:hover`, `:focus`, `:active` — если есть в дизайне.
- Не смешивать единицы без необходимости; по умолчанию **px**.

## HTML

- Семантические теги: `header`, `nav`, `main`, `section`, `article`, `footer` где уместно.
- `alt` у изображений, ссылки и кнопки — по смыслу макета.
- Формы: связанные `label`, типы полей, `required` по ТЗ.

## JavaScript (jQuery)

- Вся логика вёрстки/интерактива страницы — `js/common.js`.
- Подключить jQuery и `common.js` в HTML, если ещё не подключены.
- Короткие обработчики; для динамики — делегирование `$(document).on(...)`.

## Ассеты

- Имена файлов: `hero-bg.jpg`, `icon-phone.svg` и т.п.
- Шрифты: файлы в `fonts/`, объявления в `css/fonts.css`.
- Проверить пути после переноса из Figma.

## Интеграция с существующим проектом (например Bitrix)

- Пути шаблона, `header.php` / `footer.php`, компоненты — **как в репозитории**.
- Не ломать PHP-обвязку; править только нужные `template.php`, CSS, JS.
- Сохранять соглашения Bitrix и локального шаблона (`local/templates/...`).

## Workflow

**Родительский агент (оркестрация):**

```
1. Делегировать вёрстку → verstaller (scope + макет + skill-verstka)
2. После ответа verstaller → tester (полная проверка вёрстки)
3. FAIL → verstaller (fix brief от tester) → tester снова
4. PASS → отчёт пользователю
```

**Внутри verstaller (реализация):**

```
1. Анализ    — scope, breakpoints, список ассетов; Figma: план запросов (см. «Figma MCP: лимиты API»)
2. Figma     — минимум MCP-вызовов: один обзор секции → кэш спеков → батч ассетов
3. HTML      — скелет + Bootstrap
4. CSS       — main.css, затем media.css
5. JS        — только нужное в common.js
6. Self-check — чеклист ниже (до передачи tester)
```

## Чеклист verstaller (self-check до tester)

- [ ] Figma MCP: не больше 1–3 `get_figma_data`, ассеты батчем; без запроса всего файла и без `depth` без нужды
- [ ] Отступы, шрифты, цвета совпадают с макетом
- [ ] На `.row` нет `gap` в CSS
- [ ] `media.css` покрывает нужные breakpoints
- [ ] Шрифты и картинки — корректные относительные пути
- [ ] jQuery и `common.js` подключены
- [ ] Нет лишних файлов и рефакторинга вне задачи

Финальное **«готово»** для пользователя — только после **VERDICT: PASS** от **tester**.

## Формат ответа (задачи на вёрстку)

1. Краткий план (если задача нетривиальная).
2. Пути файлов и содержимое / точечные diff.
3. Что открыть в браузере для проверки.

## Эталонный стек подключений (по образцу zarina 3)

### `<head>` — порядок и meta

```html
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="utf-8" />
  <title>Заголовок</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <meta name="format-detection" content="telephone=no" />
  <link rel="shortcut icon" href="favicon.png" />

  <link rel="stylesheet" href="libs/bootstrap/bootstrap-grid.css" />
  <link rel="stylesheet" href="libs/fontawesome-free-5.12.0-web/css/all.min.css" />
  <link rel="stylesheet" href="libs/fancybox-master/dist/jquery.fancybox.min.css" />
  <link rel="stylesheet" href="libs/datetimepicker/jquery.datetimepicker.min.css" />
  <link rel="stylesheet" href="libs/slick-1.5.9/slick/slick.css" />

  <link rel="stylesheet" href="css/fonts.css" />
  <link rel="stylesheet" href="css/main.css?ver=1.6" />
  <link rel="stylesheet" href="css/media.css?ver=1.6" />
</head>
```

Правила:

- Сначала **CSS библиотек** (bootstrap-grid, fontawesome, fancybox, datetimepicker, slick), затем **свои** (`fonts.css` → `main.css` → `media.css`).
- К своим CSS добавлять `?ver=` для **сброса кеша** при правках.
- `viewport` с `maximum-scale=1` — стандарт для отелей/лендингов, чтобы избежать «зума» формы на iOS.
- `format-detection: telephone=no` — отключить авто-подсветку телефонов в iOS Safari.

### Скрипты — в конце `<body>`

```html
<script src="libs/jquery/jquery-3.6.0.min.js"></script>
<script src="libs/fancybox-master/dist/jquery.fancybox.min.js"></script>
<script src="libs/slick-1.5.9/slick/slick.min.js"></script>
<script src="libs/datetimepicker/jquery.datetimepicker.full.min.js"></script>
<script src="libs/mask/jquery.maskedinput.js"></script>
<script src="js/common.js?ver=1.6"></script>
```

Правила:

- **jQuery — всегда первым**, до всех плагинов.
- Плагины (`fancybox`, `slick`, `datetimepicker`, `mask`) — между jQuery и `common.js`.
- `common.js?ver=…` — последним.
- Счётчики Метрики/GA — после `common.js`, заглушками в HTML-комментариях.

## Cross-browser нормализация (обязательный фундамент)

В `main.css` в самом начале:

```css
*::-webkit-input-placeholder { color: #4c4d48; opacity: 1; }
*:-moz-placeholder            { color: #4c4d48; opacity: 1; }
*::-moz-placeholder           { color: #4c4d48; opacity: 1; }
*:-ms-input-placeholder       { color: #4c4d48; opacity: 1; }

body { overflow-x: hidden; }

input,
button,
textarea,
input.text,
input[type="text"],
input[type="button"],
input[type="submit"] {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
}

*:link, *:visited, *:hover, *:focus, *:active {
  outline: 0;
  -webkit-tap-highlight-color: transparent;
}

a, button, input, img { transition: all 0.3s; }

h1, h2, h3, h4, h5, h6 { padding: 0; margin: 0; font-weight: normal; }
ul { padding: 0; margin: 0; }
ul li { list-style-type: none; }
img { display: block; }
p { padding: 0; margin: 0; }
form { margin: 0; }
```

Чек-лист кроссбраузерности:

- [ ] **Плейсхолдеры**: 4 префикса (webkit/moz/moz/ms).
- [ ] **iOS Safari**: убран дефолтный вид `input/button` через `-webkit-appearance: none`.
- [ ] **Тап-хайлайт** на iOS: `-webkit-tap-highlight-color: transparent`.
- [ ] **Зум input на iOS** (фокус): `font-size` у input в мобильных стилях **не меньше 14px** (лучше 16px).
- [ ] **Сглаживание шрифта** в иконках/тонком тексте: `-webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale;`.
- [ ] **Скролл-баг** при `position: fixed` на iOS — учитывать `overflow-x: hidden` на `body`.
- [ ] **Backdrop-filter** (`blur(...)`) дублировать `-webkit-backdrop-filter` для Safari.
- [ ] **Flex/Grid**: на старых сборках полезны префиксы `-webkit-flex` (по образцу проекта).
- [ ] **`object-fit` на `<img>`** в IE11 — заменять `background-image` у обёртки, если нужна поддержка.

## Header: фиксация по скролл и мобильное меню

Структура HTML (упрощённо):

```html
<header class="header default">
  <div class="container-fluid">
    <div class="header__wrapper">
      <div class="sandwich">
        <div class="sw-topper"></div>
        <div class="sw-bottom"></div>
        <div class="sw-footer"></div>
      </div>
      <!-- header__left, header__buttons, .menu, .header__contacts, .socials-list -->
      <a href="tel:..." class="phone-mobile"><i class="fal fa-phone-alt"></i></a>
    </div>
  </div>
  <div class="menu-mobile"><!-- дублирующая навигация --></div>
  <div class="menu-overlay"></div>
</header>
```

Поведение в `common.js`:

```js
var $menu = $('.header');
$(window).scroll(function () {
  if ($(this).scrollTop() > 0 && $menu.hasClass('default')) {
    $menu.removeClass('default').addClass('fixed');
  } else if ($(this).scrollTop() <= 0 && $menu.hasClass('fixed')) {
    $menu.removeClass('fixed').addClass('default');
  }
});

$('.sandwich').click(function () {
  if ($('.menu-mobile').is(':hidden')) {
    $('.menu-mobile').slideDown(200);
    $('.sandwich').addClass('active');
    $('body').addClass('body--menu');
    $('.menu-overlay').fadeIn(200);
  } else {
    $('.menu-mobile').slideUp(200);
    $('.sandwich').removeClass('active');
    $('body').removeClass('body--menu');
    $('.menu-overlay').fadeOut(200);
  }
});

$('.menu-overlay').click(function () {
  $('.menu-mobile').slideUp(200);
  $('.sandwich').removeClass('active');
  $('body').removeClass('body--menu');
  $('.menu-overlay').fadeOut(200);
});
```

Правила:

- Шапка `position: fixed; top: 0; z-index: 500;` — поверх billbord-секции.
- Два состояния: `.header.default` (прозрачная над hero) и `.header.fixed` (белая, плотный padding).
- При открытом меню — `body.body--menu { overflow: hidden }`.
- **Mobile-меню — отдельный блок** `.menu-mobile`, не клонируется JS-ом, а заранее в HTML; на мобильных скрыт класс `.menu`, видна `.menu-mobile` через sandwich.
- `phone-mobile` — иконка-кнопка с `<a href="tel:...">`, видна только на узких экранах.
- Sandwich: 3 div'а (`sw-topper / sw-bottom / sw-footer`), активное состояние `.sandwich.active` — крестик через `transform: rotate(±45deg)` и `opacity: 0` среднего.

## Формы обратной связи и бронирования

### form-booking (наложенная на hero)

- Контейнер `position: absolute; bottom: 0; left/right: 0;` поверх `.billbord` с `backdrop-filter: blur(12.5px)`.
- Поля `.item-form` с `.item-form__title` (label) + `input`.
- `input` единого вида: `height: 56px; border-radius: 60px; padding: 0 16px; background: #fff; border: none;`.
- Фоновые иконки внутри input через `background-image` + `padding-left: 48px` (календарь, гости).
- Submit — кнопка `.btn-main` той же высоты, `align-self: flex-end` в линии.
- На `max-width: 992px` блок становится `position: relative;` и строится в столбец (`display: block`), submit на всю ширину.

### consultation (имя + телефон + согласие)

```html
<form>
  <div class="consultation__form">
    <div class="consultation__form-title">Менеджеры свяжутся с вами...</div>
    <div class="consultation__form-line">
      <div class="item-form">
        <input type="text" placeholder="Имя" required>
      </div>
      <div class="item-form">
        <input type="tel" placeholder="Телефон"
               class="input-phone" inputmode="numeric" required>
      </div>
      <input type="submit" value="Забронировать" class="btn-main">
    </div>
  </div>
  <div class="checkbox">
    <label>
      <input type="checkbox" checked required />
      <span>Я даю согласие... <a href="#">Политика</a>.</span>
    </label>
  </div>
</form>
```

Правила:

- `type="tel"` + `inputmode="numeric"` — мобильная клавиатура с цифрами.
- Маска через `jquery.maskedinput`: `$(".input-phone").mask("+7 (999) 999-99-99");`.
- Чекбокс согласия — кастом через `appearance: none` + `::before` с FontAwesome (`content: "\f00c"`).
- Submit — тот же `.btn-main`, чтобы кнопки в формах и в шапке выглядели одинаково.
- Сетка: `display: grid; grid-template-columns: 1fr 1fr 0.9fr; gap: 18px;` — **не `.row`**, поэтому `gap` разрешён.
- На мобильном — `display: block`, кнопка на всю ширину.

### Даты заезда/выезда

```js
$('#date-start').datetimepicker({
  dayOfWeekStart: 1,
  format: 'd.m.Y',
  formatDate: 'd.m.Y',
  timepicker: false,
  closeOnDateSelect: true,
  scrollMonth: false,
  scrollInput: false,
  onSelectDate: function (date) {
    $('#date-end').datetimepicker({ minDate: date });
  }
});

$('#date-end').datetimepicker({ /* те же опции */, minDate: 0 });
$.datetimepicker.setLocale('ru');
```

## Слайдеры (slick)

- Базовый сетап: `arrows`, `dots`, `infinite`, `touchThreshold: 1000`, кастомные стрелки через `prevArrow/nextArrow` с FontAwesome.
- `responsive: [{ breakpoint: 992, settings: { slidesToShow: 1 } }]` — переключение на 1 слайд на мобилке.
- Счётчик слайдов — обновлять в `init` + `afterChange` (см. `updateSliderCount` в `common.js`).
- Несколько одинаковых слайдеров — оборачивать `$('.slider-wrapper').each(...)` и брать `appendArrows: $controls` внутри обёртки, чтобы стрелки были у каждого свои.

## Адаптив: desktop-first брейкпоинты

Эталонные точки (по zarina 3):

| Брейкпоинт | Что меняем |
|------------|-------------|
| `max-width: 1800px` | Чуть ужимаются паддинги, gap, logo |
| `max-width: 1730px` | Глобальное уменьшение `font-size` (16→13), пересчёт всех размеров шапки/hero/form-booking |
| `max-width: 1400px` | Средний десктоп / ноутбук |
| `max-width: 1200px` | Планшет-альбом, перестройка крупных секций |
| `max-width: 992px`  | **Мобильная версия**: sandwich, скрытое десктоп-меню, формы в столбец, footer-аккордеон |

Правила:

- В каждом брейкпоинте **пересчитывать значения пропорционально** макету, а не «на глаз».
- Все `@media` — только в `media.css`. В `main.css` медиа-запросов нет.
- На `992px` и ниже:
  - Скрывать десктоп-навигацию: `.header__wrapper .menu, .header__contacts, .btn-main` → `display: none`.
  - Показывать `.sandwich`, `.phone-mobile`, `.menu-mobile`.
  - Минимальный размер шрифта input/textarea — **14px+**, иначе iOS зумит при фокусе.
  - Footer-аккордеон: `.footer__title` click → `.footer__content slideToggle()`, инициализация по `$(window).width() < 992`.

## ScrollUp / ScrollDown

```js
$('.scroll-up').click(function () {
  $('body, html').animate({ scrollTop: 0 }, 800);
  return false;
});

var topPos = $('.header').height();
$('.scroll-down').click(function () {
  $('html, body').animate({
    scrollTop: $('.catalog').offset().top - topPos
  }, 800);
});
```

- Кнопку «вверх» — `position: fixed; right: …; bottom: …; z-index`, скрывать на мобильном.
- При скролле к секции учитывать высоту фиксированной шапки.

## Fancybox (попапы)

```js
$('.fancybox').fancybox({
  autoFocus: false,
  backFocus: false,
});
```

- `autoFocus: false` — чтобы не «прыгал» скролл к первому input.
- `backFocus: false` — чтобы при закрытии не возвращался фокус на триггер (иначе на iOS появится «прыжок»).

## Запреты и приоритеты (вспомним)

- **Не** ставить `gap` на `.row` (Bootstrap-сетка).
- На flex/grid-блоках **без класса `.row`** (`.menu__list`, `.header__left`, `.consultation__form-line`) — `gap` **разрешён** и используется в эталоне.
- Не использовать `import`/`export` типов.
- Все размеры — в `px`, если в проекте не задано иное.
- В мобильных стилях не опускать `font-size` форм ниже 14px.

## Обновлённый чек-лист перед сдачей

- [ ] Отступы, шрифты, цвета — 1:1 макет
- [ ] На `.row` нет `gap`
- [ ] `media.css` покрывает 1800 / 1730 / 1400 / 1200 / 992
- [ ] Шрифты подключены через `fonts.css` + `local()` + `woff2/woff/ttf`
- [ ] Картинки и иконки — относительные пути
- [ ] jQuery → плагины → `common.js` в конце `<body>`
- [ ] `?ver=` у своих CSS/JS для сброса кеша
- [ ] meta `viewport`, `format-detection`, `X-UA-Compatible` на месте
- [ ] Плейсхолдеры кроссбраузерно (4 префикса)
- [ ] `-webkit-appearance: none` на input/button
- [ ] `-webkit-tap-highlight-color: transparent`
- [ ] `backdrop-filter` дублирован `-webkit-backdrop-filter`
- [ ] iOS не зумит формы (`font-size >= 14px` у input на мобильном)
- [ ] Header: `.default` ↔ `.fixed` по скроллу
- [ ] Sandwich + `menu-mobile` + `menu-overlay` + `body--menu` (overflow:hidden)
- [ ] Маска телефона `+7 (999) 999-99-99` на `.input-phone`
- [ ] `type="tel"` + `inputmode="numeric"` у телефонных полей
- [ ] Чекбокс согласия — кастомный через `::before` (FontAwesome)
- [ ] DatetimePicker с `setLocale('ru')` и связкой `minDate`
- [ ] Slick: кастомные стрелки FA, `responsive` до 1 слайда на 992
- [ ] Fancybox: `autoFocus:false`, `backFocus:false`
- [ ] Footer-аккордеон работает только при `width < 992`
- [ ] Ответ пользователю — на русском

## Дополнительно

- Расширенный чеклист и примеры сетки: [reference.md](reference.md)
