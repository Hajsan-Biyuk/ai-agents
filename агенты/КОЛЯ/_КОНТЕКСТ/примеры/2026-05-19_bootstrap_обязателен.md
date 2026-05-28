# Bootstrap обязателен — правило @коля

**Дата:** 2026-05-19  
**Причина:** вёрстка Agro-As без `row` / `col-*` — ошибка; исправлено.

## Правило

Любая страница верстается на **Bootstrap Grid**, подключенном в `<head>`:

```html
<link rel="stylesheet" href="libs/bootstrap/bootstrap-grid.css" />
```

## Минимальная структура секции

```html
<section class="advantages" id="advantages">
  <div class="container-fluid container-main">
    <div class="head-section">
      <h2 class="title-section">Заголовок</h2>
    </div>
    <div class="row">
      <div class="col-lg-4 col-md-6">
        <div class="item-advantage">...</div>
      </div>
    </div>
  </div>
</section>
```

## Запрещено

```html
<!-- Плохо: кастомная flex-сетка без Bootstrap -->
<div class="advantages__grid">
  <div class="item-advantage">...</div>
</div>
```

## Эталоны

- `reference/zarina 3/index.html`
- `туроператор/index.html`
- `Agro-As/index.html` (после правки 2026-05-19)

## Связано

- Gutter/calc на col без ТЗ: `2026-05-20_bootstrap_без_лишних_col.md`
