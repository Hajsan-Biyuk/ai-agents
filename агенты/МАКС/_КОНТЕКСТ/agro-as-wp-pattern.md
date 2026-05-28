# Agro-As — канонический паттерн натяжки WP (без WooCommerce)

**Обязательно для @макс:** все задачи по Agro-As и аналогичным корпоративным сайтам без WC делать **только по этому документу**.

**Тема:** `Agro-As/maintheme/`  
**Статика:** `Agro-As/` (index.html, css/, js/, img/, fonts/, libs/)  
**Эталон ACF:** `reference/zarina-wp` + `acf-zarina-reference.md`  
**Эталон WP без WC:** `reference/zarina-wp/maintheme/`

---

## 1. Структура темы

```
Agro-As/maintheme/
├── style.css
├── functions.php          ← setup, ACF Options, acf-json (БЕЗ автоимпорта!)
├── inc/helpers.php        ← хелперы (подключается первой строкой functions.php)
├── inc/cpt-product.php    ← CPT product + taxonomy product_cat
├── header.php / footer.php
├── front-page.php         ← только get_template_part секций
├── index.php
├── acf-json/
│   ├── group_agro_as_front_page.json   ← key: group_agro_as_front_page
│   ├── group_agro_as_options.json      ← key: group_agro_as_options
│   ├── group_agro_as_wholesale.json    ← page-wholesale.php
│   ├── group_agro_as_delivery.json     ← page-delivery.php
│   ├── group_agro_as_contacts.json     ← page-contacts.php
│   └── group_agro_as_product.json      ← post_type product
├── acf-import-all.json    ← ручной импорт: ACF → Tools → Import
├── page-wholesale.php / page-delivery.php / page-contacts.php / page-catalog.php / page-basket.php
├── single-product.php
├── taxonomy-product_cat.php
├── template-parts/product/   ← card.html
├── template-parts/shared/
│   └── categories-section.php
├── template-parts/front/
│   ├── billbord.php
│   ├── advantages.php
│   ├── categories.php
│   ├── products.php
│   ├── wholesale.php
│   ├── steps.php
│   ├── delivery.php
│   ├── faq-partners.php
│   └── contacts.php
├── css/ js/ img/ fonts/ libs/
```

**Не использовать:** `Agro-As/functions.php` в корне (старый Underscores).

---

## 2. ACF Options

**Регистрация:** хук `acf/init`, slug `theme-general-settings`, меню «Настройки темы».

**Группа:** `group_agro_as_options` → файл `acf-json/group_agro_as_options.json`

| name | type | Где |
|------|------|-----|
| `telefon` | text | header, footer, контакты |
| `pochta` | email | header, footer (не `email`!) |
| `adres` | text | короткий адрес |
| `polnyj_adres` | textarea | footer, секция контактов |
| `telegram` | url | header, footer |
| `politika` | url | footer |
| `soglasie` | url | footer |
| `fajl_rekvizity` | file (pdf) | опционально |

**PHP:**

```php
maintheme_get_option( 'telefon' );
maintheme_get_option( 'pochta' ); // fallback на legacy email в helpers
maintheme_phone_href( $phone );
```

---

## 3. ACF главная страница

**Location:** `page_type` = `front_page`  
**Группа:** `group_agro_as_front_page`

Каждая секция: **Tab → Group** с полем `pokazat` (true_false, default 1, ui on/off).

| Tab | Group name | Файл шаблона |
|-----|------------|--------------|
| Баннер | `banner` | billbord.php |
| Преимущества | `preimushhestva` | advantages.php |
| Категории | `kategorii` | categories.php — **только заголовок секции**; карточки из `product_cat` + ACF `izobrazhenie_kategorii` на термине (см. `front-categories-wp-pattern.md`) |
| Товары | `tovary` | products.php |
| Опт | `opt` | wholesale.php |
| Как начать | `shagi` | steps.php |
| Доставка | `dostavka` | delivery.php |
| FAQ | `faq` | faq-partners.php |
| Партнёры | `partnery` | faq-partners.php |
| Контакты | `kontakty` | contacts.php |

**Изображения:** везде `return_format: array` → `maintheme_img_url()`, `maintheme_img_alt()`.

**Ссылки кнопок:** поле `knopka_ssylka` — тип **text** (не url).  
**Ссылки карточек:** `ssylka` — тип **url**.

**Без дефолтов:** в JSON нет `default_value` для контента. Нет `inc/front-defaults.php`.

---

## 4. PHP-паттерн секции (обязательный)

```php
$t    = get_template_directory_uri();
$sect = get_field( 'banner' );

if ( ! maintheme_section_show( $sect ) ) {
    return;
}

// каждое поле перед выводом:
if ( maintheme_is_filled( $sect['zagolovok'] ?? '' ) ) {
    echo esc_html( $sect['zagolovok'] );
}

// картинка:
$url = maintheme_img_url( $sect['izobrazhenie'] ?? null );
if ( maintheme_is_filled( $url ) ) {
    echo '<img src="' . esc_url( $url ) . '" alt="' . esc_attr( maintheme_img_alt( $sect['izobrazhenie'], '' ) ) . '">';
}
```

**Хелперы** (`inc/helpers.php`):

| Функция | Назначение |
|---------|------------|
| `maintheme_section_show( $group )` | `! empty( $group['pokazat'] )` |
| `maintheme_is_filled( $value )` | поле не пустое |
| `maintheme_get_option( $field )` | ACF Options без fallback-текстов |
| `maintheme_phone_href( $phone )` | tel: href |
| `maintheme_img_url( $image )` | URL из ACF array |
| `maintheme_img_alt( $image, $fallback )` | alt из ACF array |
| `maintheme_get_front_product_categories()` | карточки категорий на главной (product_cat) |
| `maintheme_catalog_url()` | ссылка «Каталог» в меню |
| `maintheme_basket_url()` | URL страницы корзины |

**front-page.php** — только подключение частей, **без** проверки `pokazat` (проверка внутри каждой части).

**faq-partners.php** — две группы отдельно:

```php
$show_faq  = maintheme_section_show( $faq );
$show_part = maintheme_section_show( $partner );
if ( ! $show_faq && ! $show_part ) {
    return;
}
```

---

## 5. ACF JSON — синхронизация (строго!)

### В functions.php — только это:

```php
add_action( 'acf/init', 'maintheme_acf_init' ); // Options Page

add_filter( 'acf/settings/save_json', fn() => get_template_directory() . '/acf-json' );
add_filter( 'acf/settings/load_json', function ( $paths ) {
    $paths[] = get_template_directory() . '/acf-json';
    return $paths;
} );
```

### ЗАПРЕЩЕНО

- ❌ `acf_import_field_group()` на каждом `acf/init` — создаёт дубликаты групп
- ❌ Сравнение `$existing['modified']` через `acf_get_field_group()` — поле не возвращается, импорт срабатывает бесконечно

### ОБЯЗАТЕЛЬНО

- ✅ В каждом JSON-файле поле `"modified": <unix_timestamp>` — bump при изменении полей
- ✅ Имя файла = `{group_key}.json` (например `group_agro_as_options.json`)
- ✅ Синхронизация: **ACF → Группы полей → Sync** (или Import `acf-import-all.json`)
- ✅ Удалить старые дубликаты групп с другими keys (из zarina/отеля: `group_69ba975f98cf9` и т.п.)

### После деплоя

1. Залить `maintheme/` целиком
2. ACF → Sync или Import `acf-import-all.json`
3. Удалить лишние дубликаты «Основные настройки» / «Главная страница»
4. Сохранить главную страницу в админке (чтобы записались `pokazat`)

---

## 6. functions.php — порядок подключения

```php
require get_template_directory() . '/inc/helpers.php'; // ПЕРВОЙ строкой
```

ACF Options — только внутри `acf/init`, не раньше.

---

## 7. Подключение ассетов

Как zarina/natyajka: явные `<link>` в header, jQuery + libs + common.js в footer, `wp_head()` / `wp_footer()`.

Пути: `get_template_directory_uri()` / `bloginfo('template_directory')`.

---

## 7.1. Шапка — главная vs внутренние

**Эталон статики:** `index.html` (главная) vs `contacts.html` / `wholesale.html` (внутренние)

| | Главная (`is_front_page()`) | Внутренние |
|---|---|---|
| `body` | без `wrapper__default` | `wrapper__default` (фильтр `body_class`) |
| `header` | `header default` | `header` (без `default`) |
| Логотип | `<span class="logo">` + `logo.svg` | `<a class="logo logo--inner">` + `logo-inner.svg` |
| Scroll → fixed | да (`common.js`, класс `fixed`) | да (`body.wrapper__default` + `fixed`, padding-top на `.wrapper`) |

**PHP:**

```php
// functions.php
add_filter( 'body_class', fn( $c ) => is_front_page() ? $c : array_merge( $c, [ 'wrapper__default' ] ) );

// header.php — ветка is_front_page() для header class и logo
// active пункт меню: maintheme_nav_class_attr(), is_page_template( 'page-*.php' )
```

**Не использовать:** `wrapper--page` на `.wrapper` — стили внутренних страниц идут через `body.wrapper__default`.

---

## 8. Чеклист перед сдачей

- [ ] Секция скрывается при `pokazat = 0`
- [ ] Пустые поля не выводят HTML
- [ ] Нет fallback из статики
- [ ] JSON с `modified`, без автоимпорта в PHP
- [ ] `esc_html`, `esc_url`, `esc_attr` на выводе
- [ ] Лог в `агенты/МАКС/_ЛОГИ/`

---

## 9. Внутренние страницы

**Location:** `page_template` → `page-wholesale.php` / `page-delivery.php` / `page-contacts.php` / **`page-catalog.php`**

| Шаблон | ACF group | template-parts |
|--------|-----------|----------------|
| Оптовым клиентам | `group_agro_as_wholesale` | page/wholesale-* |
| Доставка и оплата | `group_agro_as_delivery` | page/delivery-* |
| Контакты | `group_agro_as_contacts` | page/contacts.php |
| **Каталог (landing)** | — (заголовок = title страницы, excerpt = подзаголовок) | `shared/categories-section.php` |

Контакты (телефон, почта, адрес, telegram, politika, soglasie) — из **Options**, не дублировать в page ACF.

---

## 10. Backlog

- `wp_nav_menu()` вместо хардкода меню
- WooCommerce не нужен
- ACF `izobrazhenie_kategorii` для `product_cat` — синхронизировать в `acf-json/` если создано только в админке

**Каталог landing:** `page-catalog.php` → шаблон **Каталог**, slug `catalog`, секция `template-parts/shared/categories-section.php`

**Корзина:** `plugins/simple-basket/` → `_КОНТЕКСТ/basket-wp-pattern.md`  
**CPT product:** `inc/cpt-product.php` → `_КОНТЕКСТ/cpt-wordpress-pattern.md`  
**ACF product:** `group_agro_as_product.json`  
**Карточка товара:** `single-product.php` → `_КОНТЕКСТ/product-single-wp-pattern.md`  
**Категории главной:** `categories.php` → `_КОНТЕКСТ/front-categories-wp-pattern.md`

---

## 11. Связанные файлы

| Документ | Путь |
|----------|------|
| ACF zarina | `_КОНТЕКСТ/acf-zarina-reference.md` |
| CPT WordPress | `_КОНТЕКСТ/cpt-wordpress-pattern.md` |
| Карточка товара | `_КОНТЕКСТ/product-single-wp-pattern.md` |
| Каталог product_cat | `_КОНТЕКСТ/product-catalog-wp-pattern.md` |
| Корзина Simple Basket | `_КОНТЕКСТ/basket-wp-pattern.md` |
| Категории на главной | `_КОНТЕКСТ/front-categories-wp-pattern.md` |
| Референсы CMS | `_КОНТЕКСТ/референсы.md` |
| Лог pokazat | `_ЛОГИ/2026-05-25_pokazat_pattern.md` |
| Лог ACF sync fix | `_ЛОГИ/2026-05-25_acf_sync_fixes.md` |
| Лог категории + корзина UI | `_ЛОГИ/2026-05-25_agro-as_front-categories-basket-polish.md` |
