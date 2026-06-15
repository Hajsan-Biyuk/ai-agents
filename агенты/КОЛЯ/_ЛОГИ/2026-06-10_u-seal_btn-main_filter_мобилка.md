# 2026-06-10 — u-seal: мобильные свёрнутые сайдбары каталога

**Задача:** на мобильной версии (`<992px`) свернуть `.catalog-sidebar` и `.filter-page__sidebar`, раскрывать по клику плавно. Две отдельные кнопки.

**Эталон:** `Madam/maintheme/woocommerce/taxonomy-product-cat.php` — паттерн `.btn-main_filter` + `slideToggle(200)`.

**Сделано:**
- Две кнопки `btn-main btn-main_filter`: «Категории» (`fa-list-ul`) и «Фильтр» (`fa-filter`)
- Каждая кнопка перед своим блоком; JS: `$(this).next().slideToggle(200)`
- CSS: кнопки скрыты на desktop; на mobile сайдбары `display: none`, на ≥992px — `display: block !important`
- Шаблоны: `taxonomy-myproduct_cat.php`, `archive-myproduct.php`, `acf-filter-results.php`

**Зафиксировано в контексте:**
- `агенты/КОЛЯ/_КОНТЕКСТ/примеры/2026-06-10_u-seal_btn-main_filter_два_сайдбара_мобилка.md`
- `агенты/КОЛЯ/_КОНТЕКСТ/паттерн/адаптив.md` → раздел «Каталог: два сайдбара mobile»
