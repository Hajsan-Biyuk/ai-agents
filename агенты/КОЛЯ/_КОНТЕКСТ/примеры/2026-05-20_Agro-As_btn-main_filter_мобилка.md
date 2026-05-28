# Agro-As — мобильный фильтр каталога `.btn-main_filter` (2026-05-20)

**Проект:** `Agro-As/`  
**Страница:** `category.html`  
**Эталон:** `reference/WithLove (5)/catalog.html` + `js/common.js`  
**Статус:** ✅ реализовано по референсу WithLove

---

## Задача

На каталоге левая колонка с категориями (аккордеон) на **desktop** всегда видна. На **mobile (&lt;992px)** колонка скрыта, вместо неё кнопка «Фильтр» открывает сайдбар через `slideToggle`.

---

## HTML (обязательный порядок)

Кнопка **перед** сайдбаром, внутри той же колонки Bootstrap:

```html
<div class="col-lg-3 catalog-page__sidebar-col">
  <a href="#" class="btn-main btn-main--outline btn-main_filter">
    Фильтр <i class="fal fa-filter" aria-hidden="true"></i>
  </a>
  <aside class="catalog-sidebar">
    <!-- аккордеон catalog-sidebar__item -->
  </aside>
</div>
```

| WithLove (референс) | Agro-As |
|---------------------|---------|
| `.btn-main_border` | `.btn-main--outline` |
| `.sidebar-catalog` | `.catalog-sidebar` |
| `far fa-filter` | `fal fa-filter` (FA Pro в libs) |

---

## CSS

### `main.css`

- `.btn-main_filter { display: none; }` — по умолчанию скрыта (desktop-first в базе).
- `.btn-main--outline` на светлом фоне каталога: **не** светло-зелёный `#c9ec5f` — плохая читаемость. Использовать `#052d24` (рамка + текст), hover `#0a3d32` + `rgba(5, 45, 36, 0.08)`.

### `media.css`

**`max-width: 992px`:**

```css
.btn-main_filter {
  display: flex;
  justify-content: space-between;
  width: 100%;
  margin-bottom: 20px;
  padding-left: 25px;
  padding-right: 25px;
}
.btn-main_filter.active .fal {
  transform: rotate(180deg);
}
.catalog-sidebar {
  display: none;
  padding-bottom: 20px;
}
.catalog-page__sidebar-col {
  margin-bottom: 0; /* не оставлять отступ под скрытый сайдбар */
}
```

**`min-width: 992px`:**

```css
.btn-main_filter {
  display: none !important;
}
.catalog-sidebar {
  display: block !important;
}
.catalog-page__sidebar-col {
  position: sticky;
  top: 24px;
  align-self: flex-start;
  z-index: 2;
}
```

> Sticky-колонка: полный паттерн — `2026-05-21_Agro-As_sticky_sidebar_desktop.md`

> На `.row` **не** добавлять `gap`. Отступы — через Bootstrap gutter и `margin-bottom` у кнопки.

---

## JS (`common.js`)

Паттерн 1:1 с WithLove:

```javascript
$('.btn-main_filter').on('click', function (e) {
  e.preventDefault();
  $(this).toggleClass('active');
  $('.catalog-sidebar').slideToggle(200);
});
```

Аккордеон категорий (`.catalog-sidebar__head`) — **отдельный** обработчик, не смешивать с фильтром.

---

## Поведение по брейкпоинтам

| Ширина | Кнопка «Фильтр» | Сайдбар |
|--------|-----------------|---------|
| ≥992px | скрыта | всегда `display: block` |
| &lt;992px | видна, на всю ширину колонки | скрыт в CSS, открывается по клику |

При ресайзе окна специальной логики нет (как в WithLove): на desktop `!important` возвращает сайдбар в поток.

---

## Чек перед сдачей (@коля)

- [ ] Кнопка с `href="#"` + `preventDefault` в JS
- [ ] Класс `active` на кнопке при открытом сайдбаре
- [ ] Сайдбар на мобилке изначально `display: none` (не только `slideUp` без CSS)
- [ ] На ≥992px кнопка не «мигает» — `display: none !important`
- [ ] `.btn-main--outline` читаем на светлом фоне (`#052d24`)
- [ ] Обновить `?ver=` в `category.html` после правок CSS/JS

---

## Файлы проекта

| Файл | Что |
|------|-----|
| `Agro-As/category.html` | разметка кнопки + сайдбар |
| `Agro-As/css/main.css` | `.btn-main_filter`, `.btn-main--outline`, `.catalog-sidebar*` |
| `Agro-As/css/media.css` | брейкпоинт 992px |
| `Agro-As/js/common.js` | `slideToggle` + аккордеон |

---

*Связано: `референсы.md` → WithLove catalog; лог `2026-05-20_Agro-As_category_filter.md`*
