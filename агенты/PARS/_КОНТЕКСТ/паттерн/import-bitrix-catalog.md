# Паттерн — импорт каталога 1C-Bitrix

**Обязательно для @pars:** upsert по образцу **SlonInstallCatalog**.

**Эталон:** `Slon/Slon-site/local/php_interface/slon_install_catalog.php`  
**Данные:** `local/templates/slon/includes/data/catalog_products.php`  
**Запуск:** `local/tools/slon_import_catalog.php`

---

## 1. Структура данных (PHP-массив)

```php
return [
    [
        'CODE' => 'pp-55-105-48',       // ключ upsert
        'CATEGORY' => 'pp',             // секция
        'ARTICLE' => 'PP-55-105-48',
        'TITLE' => 'Мешок полипропиленовый ...',
        'PRICE' => '14,20 ₽',
        'SIZE' => '55×105 см',
        // ... свойства каталога
        'MIN_QTY' => 500,
        'PACK_STEP' => 100,
    ],
];
```

**@pars может:** сгенерировать этот PHP из Excel/JSON Python-скриптом.

## 2. Upsert-логика (из SlonInstallCatalog)

```
1. resolveCatalogIblockId()
2. ensureSections(), ensureProperties(), ensurePriceGroups()
3. foreach product:
   - findElementIdByCode(CODE)
   - Update или Add CIBlockElement
   - SetPropertyValuesEx
   - setPrices (BASE, WHOLESALE_*)
4. optional purgeForeign — деактивировать не из списка
```

## 3. Генерация catalog_products.php из JSON

```python
import json

products = json.load(open("normalized/slon.json"))["products"]
lines = ["<?php", "return ["]
for p in products:
    lines.append("\t[")
    for k, v in p.items():
        if isinstance(v, str):
            lines.append(f"\t\t'{k}' => {repr(v)},")
        else:
            lines.append(f"\t\t'{k}' => {v},")
    lines.append("\t],")
lines.append("];")
Path("catalog_products.php").write_text("\n".join(lines), encoding="utf-8")
```

## 4. Dry-run

Класс `SlonInstallCatalog` поддерживает `setDryRun(true)` — лог без записи в БД.

```
/local/tools/slon_import_catalog.php?dry=1
```

## 5. Excel → Bitrix (pipeline)

```
products.xlsx
  → pandas normalize
  → data/normalized/slon_2026-07-03.json
  → generate catalog_products.php
  → slon_import_catalog.php?dry=1
  → (по запросу) без dry
```

## 6. Свойства и цены

| Код свойства | Тип | Пример |
|--------------|-----|--------|
| ARTICLE | S | PP-55-105-48 |
| SIZE | S | 55×105 см |
| MIN_QTY | N | 500 |

| Тип цены | Код |
|----------|-----|
| Розница | BASE |
| Опт 500 | WHOLESALE_500 |
| Опт 5000 | WHOLESALE_5000 |

Оптовые цены могут считаться от BASE (92% / 85%) — см. `resolveWholesalePrice()` в эталоне.

## 7. После импорта

1. Настройки → Производительность → Очистить кеш
2. `/catalog/` — фильтр и карточки
3. Удалить `local/tools/slon_import_catalog.php` с прода (напомнить)

## 8. Бренды (Aspro Max) — ссылка в листинге

**Симптом:** на `/info/brands/` бренд без `<a href>`, класс `wti`, детальная `/info/brands/{code}/` при этом открывается.

**Причина:** шаблон `news.list/items-list` скрывает ссылку при пустом `DETAIL_TEXT`, если `HIDE_LINK_WHEN_NO_DETAIL=Y`.

**Правило при создании бренда в импортёре:**

```php
$el->Add([
    'IBLOCK_ID' => $brandIblockId,
    'NAME' => $brandName,
    'CODE' => CUtil::translit($brandName, 'ru', ['replace_space' => '-', 'replace_other' => '-']),
    'ACTIVE' => 'Y',
    'DETAIL_TEXT' => $brandName,      // обязательно для ссылки в листинге
    'DETAIL_TEXT_TYPE' => 'text',
]);
```

Для уже созданных без текста — `local/tools/import/fix_brand_links.php` (токен `X-Import-Token`). Эталон: `Атриум/site/local/tools/import/fix_brand_links.php`, лог `агенты/PARS/_ЛОГИ/2026-07-10_atrium_brand_links.md`.

**Не путать:** поставщик прайса (santehcentr, rgw) ≠ бренд каталога. Бренд поставщика создавать только по явному ТЗ.

## 9. Новый проект Bitrix

Если нет `SlonInstallCatalog` — **сначала @макс** (IB, свойства, компоненты), затем @pars копирует паттерн под новый IB.
