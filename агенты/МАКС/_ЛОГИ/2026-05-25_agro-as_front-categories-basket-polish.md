# Agro-As — категории на главной + доработки корзины и UI

**Дата:** 2026-05-25

---

## 1. Секция «Категории» (главная)

### ACF
- На термине `product_cat`: поле **`izobrazhenie_kategorii`** (image)

### Код
- `maintheme_get_front_product_categories()`
- `maintheme_get_front_category_card_sizes()` — сетка по количеству
- `maintheme_get_front_category_col_class()`
- `template-parts/front/categories.php` — без repeater `elementy`

### Поведение
- Родители с картинкой → карточка на главной
- Название → ссылка на категорию
- Дети → список ссылок на подкатегории

**Док:** `_КОНТЕКСТ/front-categories-wp-pattern.md`

---

## 2. Корзина (Simple Basket UI)

- Счётчик qty ± / AJAX update, без кнопки Update
- Иконка удаления строки, пересчёт «Итого»
- Стили `.basket-page`, форма как `.item-form`
- Комментарий: `col-md-6`
- `toAjaxResponse()` — totals для JS

**Фиксы qty:**
- Убран двойной ± (конфликт `common.js` и `simple-basket.js`)
- Скрыты spinner у `.basket-qty-input`

---

## 3. Добавление в корзину

- **Вариации:** fix jQuery `.data()` cache — читать `.attr('data-variation')` + активная `.product-page__volume-item.active`
- **Кнопка:** убрана пульсация на «В корзину» (осталась на иконке `.btn-basket`)

---

## 4. Прочее

- Письма: `wp_mail_from_name` → **«Агро Ас»** (`functions.php`)
- Хедер: `.btn-basket` — белый круг (`border-radius: 50%`, `#fff`)

---

## 5. Ассеты (актуальные версии)

| Файл | ver |
|------|-----|
| main.css | 9.2 |
| media.css | 6.0 |
| common.js | 5.0 |
| simple-basket.js | 0.5 (enqueue) |

---

## 6. Деплой-пакет (ключевые пути)

```
maintheme/inc/helpers.php
maintheme/template-parts/front/categories.php
maintheme/functions.php
maintheme/js/common.js
maintheme/css/main.css
maintheme/css/media.css
maintheme/header.php
maintheme/footer.php
maintheme/img/icon-basket-remove.svg
plugins/simple-basket/  (classes, ajax-api, js, integrations)
```
