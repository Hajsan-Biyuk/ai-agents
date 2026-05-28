# ACF — референс zarina (acf-export-2026-05-25.json)

**Файл:** `Agro-As/acf-export-2026-05-25.json`  
**Проект:** reference/zarina-wp (отель, без WooCommerce)

---

## Группы полей

| Группа | Location | Назначение |
|--------|----------|------------|
| **Основные настройки** | `options_page` → `theme-general-settings` | Глобальные контакты |
| **Главная страница** | `page_type` → `front_page` | Секции главной через tab + group |
| **Об отеле** | `page_template` → `page-about.php` | Баннер, контент, лицензии |
| **Инфраструктура** | `page_template` → `page-infrastructure.php` | Баннер + repeater блоков |
| **Номер** | `post_type` → `catalog` | CPT карточка номера |
| **Статья** | `post_type` → `post` | Подзаголовок |

---

## Options Page — «Основные настройки»

**Slug:** `theme-general-settings` (регистрация в `functions.php` через `acf_add_options_page`)

| name | type | Где в zarina |
|------|------|--------------|
| `telefon` | text | header, footer |
| `pochta` | text | — |
| `adres` | text | header (короткий) |
| `polnyj_adres` | text | footer |
| `telegram` | text | header, footer |
| `max` | text | header, footer |
| `fajl_rekvizity` | file (pdf) | страница контактов |

**В PHP:**

```php
get_field( 'telefon', 'option' );
get_field( 'pochta', 'option' );
get_field( 'polnyj_adres', 'option' );
```

**Телефон в href:**

```php
preg_replace( '/[^0-9+]/', '', get_field( 'telefon', 'option' ) );
```

---

## Паттерны структуры полей

### 1. Tab + Group (главная, «Об отеле»)

```
Tab «Баннер»
Group banner
  ├── izobrazhenie_bannera (image, return array)
  ├── zagolovok (text)
  └── podzagolovok (text)
```

### 2. Repeater «bloki»

```
bloki (repeater, layout table)
  ├── izobrazhenie (image)
  ├── zagolovok (text)
  └── opisanie (textarea, new_lines wpautop)
```

### 3. Group с WYSIWYG

`ob_otele` → `opisanie` (wysiwyg), `chisla` (repeater: chislo + tekst)

### 4. Image return format

Везде **`return_format: array`** → в шаблоне `$img['url']`

### 5. hide_on_screen

На главной и page-about скрыты: `the_content`, `featured_image`

---

## Location rules

| param | value | Когда |
|-------|-------|-------|
| `options_page` | `theme-general-settings` | Options |
| `page_type` | `front_page` | Главная |
| `page_template` | `page-about.php` | Шаблон страницы |
| `post_type` | `catalog` / `post` | CPT / записи |

---

## Agro-As — реализовано (2026-05-25)

**Канон:** см. `_КОНТЕКСТ/agro-as-wp-pattern.md`

| zarina | Agro-As (факт) |
|--------|----------------|
| `pochta` | ✅ вместо `email` |
| `polnyj_adres` | ✅ footer + контакты |
| `max` | не нужен |
| `fajl_rekvizity` | ✅ pdf в Options |
| Группа «Главная» | ✅ 10 секций + `pokazat` |
| `politika`, `soglasie` | ✅ url в Options |

### Типы полей Agro-As

| Поле | Тип | Комментарий |
|------|-----|-------------|
| `knopka_ssylka` | **text** | ссылка кнопки (не url) |
| `ssylka` (карточки) | url | категории, товары |
| `pokazat` | true_false | в каждой секции, default 1 |
| image | array | `maintheme_img_url()` |

---

## Импорт / синхронизация

1. **Ручной импорт:** ACF → Tools → Import → `maintheme/acf-import-all.json`
2. **acf-json в теме:** `Agro-As/maintheme/acf-json/` + фильтры в `functions.php`
3. **Sync в админке:** ACF → Группы полей → Sync (если JSON новее — поле `"modified"`)

### ЗАПРЕЩЕНО

```php
// НЕ делать — создаёт дубликаты групп при каждой загрузке:
add_action( 'acf/init', function() {
    acf_import_field_group( $json );
});
```

### ОБЯЗАТЕЛЬНО в JSON

```json
"modified": 1779779664
```

Bump `modified` при каждом изменении полей в JSON.

```php
add_filter( 'acf/settings/save_json', function () {
    return get_template_directory() . '/acf-json';
} );
add_filter( 'acf/settings/load_json', function ( $paths ) {
    $paths[] = get_template_directory() . '/acf-json';
    return $paths;
} );
```

---

## Файлы для сверки

| Что | Путь |
|-----|------|
| **Канон Agro-As** | `агенты/МАКС/_КОНТЕКСТ/agro-as-wp-pattern.md` |
| Экспорт ACF (zarina) | `Agro-As/acf-export-2026-05-25.json` |
| Тема Agro-As | `Agro-As/maintheme/` |
| ACF JSON | `Agro-As/maintheme/acf-json/` |
| Import bundle | `Agro-As/maintheme/acf-import-all.json` |
| Хелперы | `Agro-As/maintheme/inc/helpers.php` |
