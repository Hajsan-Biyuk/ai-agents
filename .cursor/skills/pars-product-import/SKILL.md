---
name: pars-product-import
description: Imports and updates products from Excel and JSON files, parses supplier websites, upserts to WordPress CPT/ACF, WooCommerce, or Bitrix catalog. Use when the user asks for product import, Excel catalog, price update, supplier parsing, or @pars tasks.
---

# Pars — Product Import & Supplier Parsing

## Обязательный канон

Перед импортом **прочитать и следовать**:

1. `агенты/PARS/РУКОВОДИТЕЛЬ.md`
2. `агенты/PARS/_КОНТЕКСТ/чек-лист_импорта.md`
3. Паттерн CMS:
   - WP: `агенты/PARS/_КОНТЕКСТ/паттерн/import-wp-products.md`
   - Bitrix: `агенты/PARS/_КОНТЕКСТ/паттерн/import-bitrix-catalog.md`
4. Маппинг: `агенты/PARS/_КОНТЕКСТ/паттерн/excel-json-mapping.md`
5. Парсинг: `агенты/PARS/_КОНТЕКСТ/паттерн/supplier-parsing.md`

## Краткий алгоритм

1. Уточнить CMS, источник, ключ upsert (article / CODE / SKU / slug)
2. Preview источника + таблица маппинга
3. Нормализация → `data/normalized/{project}_{date}.json`
4. **Dry-run** → отчёт created/updated/skipped/errors
5. Реальный импорт — **только по явному запросу**
6. Проверка админка + фронт
7. Лог в `агенты/PARS/_ЛОГИ/`

## Эталоны в репо

| CMS | Файл |
|-----|------|
| Bitrix | `Slon/Slon-site/local/php_interface/slon_install_catalog.php` |
| Bitrix data | `Slon/.../includes/data/catalog_products.php` |
| WP структура | `агенты/МАКС/_КОНТЕКСТ/product-single-wp-pattern.md` |

## Парсинг поставщика

Bright Data MCP: `search_engine` → `scrape_as_markdown` / `scrape_batch` → merge по правилам `supplier-parsing.md`.

## Bitrix / Aspro Max — бренды

При автосоздании брендов в ИБ (свойство `BRAND`) **обязательно** `DETAIL_TEXT` — иначе на `/info/brands/` нет ссылки (`HIDE_LINK_WHEN_NO_DETAIL=Y`). Поставщик прайса ≠ бренд. Подробнее: `агенты/PARS/_ЛОГИ/2026-07-10_atrium_brand_links.md`, паттерн `import-bitrix-catalog.md` §8.

## Не делать

- CPT/ACF/шаблоны — зона `@макс`
- Импорт без dry-run
- Удаление товаров без `--purge` / явного ТЗ
- Создавать бренд из имени поставщика без явного ТЗ
