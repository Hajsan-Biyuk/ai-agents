# Паттерн — импорт товаров WordPress (CPT + ACF)

**Обязательно для @pars:** импорт в Agro-As и аналоги **без WooCommerce**.

**Канон структуры:** `агенты/МАКС/_КОНТЕКСТ/cpt-wordpress-pattern.md`, `product-single-wp-pattern.md`  
**CPT:** `product` · **Taxonomy:** `product_cat` · **ACF:** `group_agro_as_product.json`

---

## 1. Поля CMS (Agro-As)

| Данные | Куда писать |
|--------|-------------|
| Название | `post_title` |
| Slug | `post_name` |
| Описание | `post_content` (не ACF!) |
| Категория | `wp_set_object_terms( $id, $term, 'product_cat' )` |
| Галерея | ACF `galereya` (array of attachment IDs) |
| Теги на карточке | ACF `tegi` (repeater) |
| Статус наличия | ACF `status_nalichiya` |
| Вариации объёма/цены | ACF `variacii` (repeater: obem, cena) |
| Характеристики | ACF `harakteristiki` (repeater: nazvanie, znachenie) |
| Применение | ACF `primenenie` |
| Рекомендуемые | ACF `rekomenduemye_tovary` (relationship) |

Точные `name` полей — из `acf-json/group_agro_as_product.json`.

## 2. Ключ upsert

**Рекомендация:** `post_name` (slug) или meta `_article` / ACF артикул — зафиксировать в ТЗ.

```php
$existing = get_page_by_path( $slug, OBJECT, 'product' );
// или WP_Query по meta_key article
```

## 3. PHP-импорт (одноразовый скрипт)

Размещение: `maintheme/inc/import-products.php` или `wp-cli` command.  
Запуск: только из CLI / `?key=SECRET` с удалением после импорта.

```php
$post_id = wp_insert_post( [
    'post_type'   => 'product',
    'post_status' => 'publish',
    'post_title'  => $title,
    'post_name'   => $slug,
    'post_content'=> $description,
] );

wp_set_object_terms( $post_id, $category_slug, 'product_cat' );

update_field( 'variacii', $variations, $post_id );
update_field( 'harakteristiki', $specs, $post_id );
```

**Обновление:** `wp_update_post` + `update_field` только для переданных полей.

## 4. WP-CLI (сервер)

```bash
wp post create --post_type=product --post_title="..." --post_name="..." --porcelain
wp post meta update ID _field value
# ACF repeater через PHP надёжнее, чем чистый CLI
```

## 5. Изображения

1. Скачать URL → `wp_upload_bits()` или `media_sideload_image()`
2. ACF gallery — массив attachment ID
3. Featured image — `_thumbnail_id` при необходимости

```php
require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/image.php';
$att_id = media_sideload_image( $url, $post_id, $alt, 'id' );
```

## 6. Python → WP REST

```python
import requests
from requests.auth import HTTPBasicAuth

auth = HTTPBasicAuth("user", "app_password")
r = requests.post(
    f"{site}/wp-json/wp/v2/product",
    json={"title": title, "slug": slug, "status": "publish"},
    auth=auth,
)
```

ACF repeater через REST нестабилен — **предпочитать PHP-скрипт** на сервере.

## 7. Dry-run (PHP)

```php
function pars_dry_run_product( array $row ): string {
    $slug = $row['code'];
    if ( get_page_by_path( $slug, OBJECT, 'product' ) ) {
        return 'update';
    }
    return 'create';
}
```

## 8. После импорта

1. ACF Sync не нужен если только значения полей
2. Проверить `/product/{slug}/` на фронте
3. Permalink flush — только если новые CPT (обычно `@макс`)

## 9. Не делать

- ❌ Менять `acf-json` при импорте значений
- ❌ `esc_html` на контент при импорте — сохранять как в источнике
- ❌ Удалять товары не из файла без `--purge`
