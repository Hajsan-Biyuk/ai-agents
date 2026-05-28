# Agro-As — sticky-колонка (сайдбар / блок покупки) на desktop (2026-05-21)

**Проект:** `Agro-As/`  
**Страницы:** `category.html` (`.catalog-sidebar`), `card.html` (`.product-page__buy`)  
**Статус:** ✅ утверждено пользователем — применять для похожих двухколоночных layout

---

## Когда использовать

Боковая колонка Bootstrap **рядом с длинным контентом** (список товаров, табы, текст) должна **оставаться в viewport при скролле** — не уезжать вверх.

Типичные блоки:

| Страница | Sticky-колонка | Внутренний блок |
|----------|----------------|-----------------|
| `category.html` | `.catalog-page__sidebar-col` | `.catalog-sidebar` |
| `card.html` | `.product-page__buy-col` | `.product-page__buy` |

---

## HTML (обязательно)

Sticky вешается на **колонку** `col-*`, не на внутренний `aside` / `div`:

```html
<div class="row catalog-page__layout">
  <div class="col-lg-3 catalog-page__sidebar-col">
    <a href="#" class="btn-main btn-main--outline btn-main_filter">…</a>
    <aside class="catalog-sidebar">…</aside>
  </div>
  <div class="col-lg-9 catalog-page__content-col">…</div>
</div>
```

- Сетка: только Bootstrap `row` + `col-*`, **без `gap` на `.row`**
- На mobile (&lt;992px) в той же колонке — кнопка «Фильтр» + скрытый сайдбар (см. `2026-05-20_Agro-As_btn-main_filter_мобилка.md`)

---

## CSS — только `media.css`

**Не** добавлять `@media` для sticky в `main.css`.

### Базовый паттерн (копировать)

```css
@media (min-width: 992px) {
  .catalog-page__sidebar-col {
    position: sticky;
    top: 24px;
    align-self: flex-start;
    z-index: 2;
  }
}
```

| Свойство | Значение | Зачем |
|----------|----------|--------|
| `position: sticky` | — | липнет при скролле |
| `top: 24px` | 24px | отступ от верха viewport (как buy-блок на карточке) |
| `align-self: flex-start` | — | **критично:** без этого col растягивается на высоту row и sticky не работает |
| `z-index: 2` | 2 | поверх соседнего контента при пересечении |

### Брейкпоинты Agro-As

| Блок | Sticky с | Sticky выкл. |
|------|----------|--------------|
| **Каталог** `.catalog-page__sidebar-col` | **`min-width: 992px`** | **`max-width: 991px`** (сайдбар скрыт, фильтр-кнопка) |
| **Карточка** `.product-page__buy-col` | `min-width: 1200px` | `max-width: 1199px` → `position: static` |

> Каталог: sticky **с 992px**, потому что `col-lg-*` и видимый сайдбар начинаются с lg.  
> Карточка: sticky **с 1200px** — там buy-колонка в трёхколоночном hero; на узком xl buy уходит в поток.

### Ошибки (не повторять)

| Ошибка | Правильно |
|--------|-----------|
| `position: sticky` на `.catalog-sidebar` внутри col | sticky на **`.catalog-page__sidebar-col`** |
| Sticky с `min-width: 1200px` на каталоге | каталог: **`min-width: 992px`** |
| Без `align-self: flex-start` | всегда добавлять |
| `@media` для sticky в `main.css` | только **`media.css`** |
| `height: 100%` на sticky-блоке без нужды | высота по контенту; sticky на col |

---

## Связь с mobile-фильтром каталога

На **&lt;992px** sticky **не нужен** — сайдбар скрыт (`display: none`), открывается `.btn-main_filter` + `slideToggle`.

Правила в одном блоке `@media (min-width: 992px)`:

```css
.btn-main_filter { display: none !important; }
.catalog-sidebar { display: block !important; }
.catalog-page__sidebar-col {
  position: sticky;
  top: 24px;
  align-self: flex-start;
  z-index: 2;
}
```

---

## JS

Для sticky **не требуется** JS. Аккордеон сайдбара и фильтр — отдельно в `common.js`.

---

## Чек перед сдачей (@коля)

- [ ] Sticky на **col-обёртке**, не на внутреннем блоке
- [ ] `align-self: flex-start` + `top: 24px`
- [ ] Брейкпоинт согласован с видимостью колонки (каталог ≥992, карточка ≥1200)
- [ ] На mobile sticky не мешает (каталог: сайдбар скрыт; карточка: `position: static`)
- [ ] Правки только в `media.css`, `?ver=` в HTML страницы

---

## Файлы проекта

| Файл | Что |
|------|-----|
| `Agro-As/category.html` | `catalog-page__sidebar-col` + `catalog-sidebar` |
| `Agro-As/card.html` | `product-page__buy-col` |
| `Agro-As/css/media.css` | sticky @992 (каталог), @1200 + static @1199 (карточка) |

---

*Связано: `2026-05-20_Agro-As_btn-main_filter_мобилка.md`, лог `2026-05-21_Agro-As_sticky_sidebar.md`*
