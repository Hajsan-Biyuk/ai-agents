# Паттерн: Agro-As maintheme

**Когда:** любая задача по `Agro-As/maintheme/`  
**Полный канон:** [`../agro-as-wp-pattern.md`](../agro-as-wp-pattern.md)  
**Обновлено:** 2026-06-05

## Правило

Делать **только** по `agro-as-wp-pattern.md`. Этот файл — краткая шпаргалка + ссылки.

## Рабочие пути

| Что | Путь |
|-----|------|
| Тема WP | `Agro-As/maintheme/` |
| Статика | `Agro-As/*.html`, `Agro-As/css/` |
| Хелперы | `inc/helpers.php` |
| CPT товар | `inc/cpt-product.php` |

## Ключевые шаблоны

| Файл | Назначение |
|------|------------|
| `front-page.php` | `get_template_part( 'template-parts/front/*' )` |
| `page-catalog.php` | Каталог CPT |
| `single-product.php` | Карточка товара → `product-single-wp-pattern.md` |
| `page-blog.php` | Template Name: Новости |
| `page-certificates.php` | Template Name: Сертификаты → `acf-page.md` |
| `page-delivery.php` | Доставка и оплата |

## Хелперы (частые)

```php
maintheme_get_option( 'telefon' );
maintheme_is_filled( $value );
maintheme_section_show( $group );
maintheme_catalog_url();
maintheme_news_page_url();
```

## Вёрстка

Классы и HTML — **не менять** при натяжке. Образцы: `агенты/КОЛЯ/_КОНТЕКСТ/паттерн/`.

## Связанные каноны

- CPT: `../cpt-wordpress-pattern.md`
- Товар: `../product-single-wp-pattern.md`
- Каталог: `../product-catalog-wp-pattern.md`
- Корзина: `../basket-wp-pattern.md`
