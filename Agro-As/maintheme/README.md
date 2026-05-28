# maintheme — рабочая папка темы WordPress

Здесь собирается тема WP при интеграции готовой вёрстки от @коля.

## Эталоны

| Проект | Путь | Когда использовать |
|--------|------|-------------------|
| **natyajka** | `reference/natyajka/maintheme/` | WooCommerce, интернет-магазин |
| **zarina-wp** | `reference/zarina-wp/maintheme/` | Корпоративный сайт, отель, CPT `catalog`, ACF, без WC |

## Паттерн натяжки (по zarina-wp)

### 1. Каркас

```
maintheme/
├── style.css              # Theme Name, Text Domain
├── functions.php          # setup, ACF Options, хуки
├── header.php / footer.php
├── front-page.php
├── page-*.php             # Template Name в комментарии
├── single-*.php           # CPT / post
├── css/ js/ img/ fonts/ libs/
├── inc/                   # template-tags, customizer
└── template-parts/        # переиспользуемые секции
```

### 2. Ассеты — явное подключение (не только wp_enqueue)

**header.php** — порядок как в статике:

1. `libs/bootstrap/bootstrap-grid.css`
2. fontawesome → fancybox → datetimepicker → slick
3. `css/fonts.css` → `css/main.css?ver=` → `css/media.css?ver=`
4. `<?php wp_head(); ?>`

**footer.php** — jQuery, libs, `js/common.js?ver=`, затем `<?php wp_footer(); ?>`.

Пути:

```php
<?php echo get_template_directory_uri(); ?>/img/logo.svg
```

### 3. Разбивка HTML

| Статика | WP-шаблон |
|---------|-----------|
| `<head>` + header | `header.php` |
| footer + модалки + scripts | `footer.php` |
| Главная | `front-page.php` |
| Внутренние страницы | `page-{slug}.php` с `Template Name:` |
| CPT карточка | `single-{post_type}.php` |
| Блок-секция | `template-parts/sect_*.php` + `get_template_part()` |

**Правило:** классы и разметку из вёрстки **не менять** — только PHP-обвязка.

### 4. Динамика

- **Логотип:** `is_front_page()` → `<span class="logo">` + `logo.svg` / иначе `<a class="logo logo--inner">` + `logo-inner.svg`
- **Шапка:** главная → `header default`; внутренние → `header` + `body.wrapper__default`
- **Active меню:** `maintheme_nav_class_attr()` + `is_page_template( 'page-*.php' )`
- **Контакты:** ACF Options — `get_field('telefon', 'option')`, соцсети
- **Телефон в href:** `preg_replace("/[^0-9+]/", '', ...)`
- **Контент страниц:** ACF-группы (`get_field('banner')`, repeater)
- **CPT-циклы:** `WP_Query` с `post_type => 'catalog'`
- **Формы:** `do_shortcode('[contact-form-7 ...]')` в модалках footer
- **Wrapper:** `body.wrapper__default` на внутренних — фильтр `body_class` в `functions.php`

### 5. functions.php

- `maintheme_setup()` — title-tag, thumbnails, menus
- `acf_add_options_page()` — «Настройки темы»
- SVG mime types
- Кастомная логика — префикс `maintheme_`

### 6. Чеклист после интеграции

- [ ] `wp_head()` / `wp_footer()` на месте
- [ ] Пути к ассетам через `get_template_directory_uri()`
- [ ] ACF Options заполнены
- [ ] Меню назначены в админке
- [ ] Визуал 1:1 со статикой
- [ ] Нет PHP Notice при `WP_DEBUG`

## Workflow

```
1. @коля → index.html, css/, js/, img/, fonts/, libs/
2. @макс → копирует ассеты в maintheme/
3. Разрезает HTML → header.php, footer.php, page-*.php
4. Подключает ACF, меню, CPT, формы
5. Проверка на стенде WP
```

## Пример вызова

```
@макс Внедри вёрстку из Agro-As/ в maintheme/ по образцу reference/zarina-wp
```
