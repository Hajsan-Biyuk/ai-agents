# Agro-As — секция «Категории» на главной (product_cat)

**Шаблон:** `maintheme/template-parts/shared/categories-section.php`  
**Подключение:**
- Главная: `template-parts/front/categories.php` (`context` = `front`, ACF `kategorii`)
- Landing каталога: `page-catalog.php` (`context` = `page`, title/excerpt страницы)

**Хелперы:** `inc/helpers.php`

---

## 1. Источник данных

| Было | Стало |
|------|--------|
| ACF repeater `kategorii` → `elementy` (ручные карточки) | Авто из таксономии **`product_cat`** |

**ACF на главной (`kategorii`)** — только:
- `pokazat` — показ секции
- `zagolovok`, `podzagolovok` — заголовок блока

**ACF на термине `product_cat`:**
- `izobrazhenie_kategorii` (image, return array) — **обязательно** для вывода родительской категории на главной

---

## 2. Логика выборки

```php
$categories = maintheme_get_front_product_categories();
```

1. Родительские термины `product_cat` (`parent = 0`), `orderby name ASC`
2. Пропуск без `izobrazhenie_kategorii`
3. Дочерние термины того же `product_cat` → список ссылок
4. Ссылки: `get_term_link( $term )`

---

## 3. Сетка карточек по количеству

`maintheme_get_front_category_card_sizes( $count )` + `maintheme_get_front_category_col_class( $size )`

| Кол-во | Размеры | Bootstrap |
|--------|---------|-----------|
| 1 | lg | col-lg-6 |
| 2 | md, md | col-lg-6 |
| 3 | sm × 3 | col-lg-4 |
| 4 | md × 4 | col-lg-6 (2×2) |
| 5 | sm×3 + md + lg | как index.html |
| 6, 9… | sm (кратно 3) | col-lg-4 |
| 7, 8… | 3×sm + пары md, остаток lg | см. хелпер |

Классы карточки: `item-category item-category--{sm|md|lg}`

---

## 4. Разметка ссылок

- **Родитель:** `<a class="item-category__title" href="term_link">`
- **Подкатегории:** `<a class="item-category__sub-link">` в `.item-category__list` (отдельные ссылки, не вложенные в `<a>`)

Карточка — `<div class="item-category">`, не обёртка-ссылка на всю площадь.

---

## 5. Админка

1. Товары → Категории → родительская категория
2. Заполнить **Изображение категории** (`izobrazhenie_kategorii`)
3. Создать подкатегории (дети) — выводятся списком ссылок

4. **WP:** страница slug `catalog`, шаблон **Каталог** (`page-catalog.php`) — для пункта меню «Каталог»

Группу ACF для `product_cat` синхронизировать в `acf-json/` (Sync в админке).

---

## 6. Связанные документы

- Каталог: `_КОНТЕКСТ/product-catalog-wp-pattern.md`
- CPT: `_КОНТЕКСТ/cpt-wordpress-pattern.md`
- Лог: `_ЛОГИ/2026-05-25_agro-as_front-categories-basket-polish.md`
