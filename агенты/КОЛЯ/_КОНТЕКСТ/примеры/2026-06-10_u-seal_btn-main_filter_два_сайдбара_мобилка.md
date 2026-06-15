# u-seal — мобильные свёрнутые сайдбары каталога `.btn-main_filter` (2026-06-10)

**Проект:** `u-seal/maintheme/`  
**Страницы:** `taxonomy-myproduct_cat.php`, `archive-myproduct.php`, `acf-filter-results.php`  
**Эталон:** `Madam/maintheme/woocommerce/taxonomy-product-cat.php` + `js/common.js`  
**Статус:** ✅ реализовано

---

## Задача

На страницах каталога два сайдбара:

1. **Категории** — `.catalog-sidebar` → `.catalog-sidebar-card`
2. **Фильтр ACF** — `.filter-page__sidebar` → `acf-filter-form.php`

На **desktop (≥992px)** оба всегда видны. На **mobile (&lt;992px)** каждый блок свёрнут, перед ним отдельная кнопка; по клику — плавное раскрытие через `slideToggle(200)`.

> Отличие от Agro-As / WithLove: **две независимые кнопки**, а не одна на весь сайдбар.

---

## HTML (обязательный порядок)

Кнопка **непосредственно перед** сворачиваемым блоком. Каждая кнопка управляет только **следующим** соседом (`$(this).next()`).

### Категории (колонка `col-lg-3`)

```html
<aside class="catalog-sidebar col-12 col-lg-3 mb-4 mb-lg-0">
  <a href="#" class="btn-main btn-main_filter">Категории <i class="fas fa-list-ul"></i></a>
  <div class="catalog-sidebar-card card">
    <div class="card-header fw-semibold">Категории</div>
    <div class="catalog-categories list-group list-group-flush">
      <!-- wp_list_categories -->
    </div>
  </div>
</aside>
```

### Фильтр (внутри `col-lg-9`, над таблицей)

```html
<a href="#" class="btn-main btn-main_filter">Фильтр <i class="fas fa-filter"></i></a>
<aside class="filter-page__sidebar">
  <?php get_template_part( 'acf-filter-form' ); ?>
</aside>
```

| Madam (референс) | u-seal |
|------------------|--------|
| одна кнопка «Фильтр и коллекция» | две кнопки: «Категории» + «Фильтр» |
| `.sidebar-catalog` | `.catalog-sidebar-card` + `.filter-page__sidebar` |
| `$('.sidebar-catalog').slideToggle()` | `$(this).next().slideToggle()` |
| `far fa-filter` | `fas fa-list-ul` / `fas fa-filter` |

---

## CSS

### `main.css`

```css
.btn-main_filter {
  display: none;
}
```

### `media.css` — `max-width: 992px`

```css
/*catalog: свернутые сайдбары*/
.btn-main_filter {
  height: 48px;
  padding: 0 20px;
  margin-bottom: 15px;
  width: 100%;
  font-size: 15px;
  display: flex;
  justify-content: space-between;
}
.catalog-sidebar .catalog-sidebar-card,
.filter-page__sidebar {
  display: none;
}
```

### `media.css` — `min-width: 992px`

Страховка от inline-стилей jQuery `slideToggle` при ресайзе (как `.footer__content`):

```css
/*catalog*/
.catalog-sidebar .catalog-sidebar-card,
.filter-page__sidebar {
  display: block !important;
}
```

> Кнопки на desktop скрыты через `main.css` (`.btn-main_filter { display: none; }`), отдельный `!important` для кнопок не нужен.

---

## JS (`common.js`)

Паттерн по Madam, но **каждая кнопка — свой блок** (не общий селектор):

```javascript
$(".btn-main_filter").click(function(e) {
  e.preventDefault();
  $(this).toggleClass("active");
  $(this).next().slideToggle(200);
});
```

Обработчик **без** обёртки `if ($(window).width() < 992)` — на desktop кнопки скрыты CSS, клик невозможен.

Аккордеоны внутри фильтра (`.filter-group__toggle`) — **отдельная** логика, не смешивать.

---

## Поведение по брейкпоинтам

| Ширина | Кнопки | Сайдбары |
|--------|--------|----------|
| ≥992px | скрыты | всегда `display: block !important` |
| &lt;992px | видны, на всю ширину | скрыты в CSS, открываются по клику независимо |

---

## Чек перед сдачей (@коля)

- [ ] Две кнопки: «Категории» и «Фильтр» — каждая перед своим блоком
- [ ] `href="#"` + `preventDefault` в JS
- [ ] Класс `active` на кнопке при открытом блоке
- [ ] Сайдбары на мобилке изначально `display: none` в CSS
- [ ] На ≥992px сайдбары `display: block !important`
- [ ] Правки во всех шаблонах, где есть эти блоки (taxonomy + archive + filter-results)
- [ ] Bump `?ver=` в `header.php` / `functions.php` после CSS/JS

---

## Файлы проекта

| Файл | Что |
|------|-----|
| `taxonomy-myproduct_cat.php` | кнопки + разметка каталога категории |
| `archive-myproduct.php` | то же для архива |
| `acf-filter-results.php` | то же для страницы фильтра |
| `css/main.css` | `.btn-main_filter { display: none; }` |
| `css/media.css` | брейкпоинт 992px, show/hide |
| `js/common.js` | `slideToggle` на `$(this).next()` |

---

*Связано: `2026-05-20_Agro-As_btn-main_filter_мобилка.md` (одна кнопка); лог `2026-06-10_u-seal_btn-main_filter_мобилка.md`*
