# Pars — Product Automatic Refresh System

AI-агент для **автоматического обновления и добавления товаров** на сайт из Excel/JSON и **парсинга данных с сайтов поставщиков**.

**Расшифровка:** Product Automatic Refresh System — Система автоматического обновления товаров.

## Вызов

В чате Cursor:

```
@pars [ваша задача]
```

## Что делает

- Импорт и обновление товаров из **Excel** (`.xlsx`, `.xls`) и **JSON**
- Сопоставление колонок/полей с CMS (WordPress CPT+ACF, WooCommerce, 1C-Bitrix каталог)
- **Upsert** по ключу: артикул, SKU, `CODE`, slug — создать новые, обновить существующие
- Парсинг карточек товаров с **сайтов поставщиков** (цена, наличие, характеристики, фото)
- Генерация промежуточных JSON/PHP-массивов для импорта
- Dry-run, отчёт: создано / обновлено / пропущено / ошибки
- Python-скрипты и PHP-импортёры в `local/tools/` (по образцу Slon)

## Стек

Python 3 · openpyxl / pandas · requests · BeautifulSoup · WP-CLI / REST · Bitrix API · Bright Data MCP (парсинг)

## Правила

- Правила агента: `.cursor/rules/pars.mdc`
- Руководитель: `агенты/PARS/РУКОВОДИТЕЛЬ.md`
- Стек: `агенты/PARS/_КОНТЕКСТ/СТЕК.md`
- Чек-лист: `агенты/PARS/_КОНТЕКСТ/чек-лист_импорта.md`
- Паттерны: `агенты/PARS/_КОНТЕКСТ/паттерн/`

## Структура агента

```
агенты/PARS/
├── README.md
├── РУКОВОДИТЕЛЬ.md
├── БАЗА_СМЫСЛОВ.md
├── СИСТЕМНЫЙ_ПРОМПТ.md
├── _КОНТЕКСТ/
│   ├── СТЕК.md
│   ├── референсы.md
│   ├── чек-лист_импорта.md
│   ├── паттерн/
│   │   ├── README.md
│   │   ├── excel-json-mapping.md
│   │   ├── import-wp-products.md
│   │   ├── import-bitrix-catalog.md
│   │   └── supplier-parsing.md
│   └── примеры/
└── _ЛОГИ/
```

## Рабочие проекты

| Проект | Путь | CMS | Эталон импорта |
|--------|------|-----|----------------|
| Agro-As | `Agro-As/maintheme/` | WP CPT `product` + ACF | `@макс` паттерны |
| Slon | `Slon/Slon-site/` | 1C-Bitrix каталог | `SlonInstallCatalog` |
| natyajka | `reference/natyajka/maintheme/` | WooCommerce | WC REST / CSV |

## Связь с командой

| Этап | Агент |
|------|--------|
| Структура CPT, ACF, шаблоны карточки | **@макс** |
| Импорт/обновление товаров из файлов и парсинг | **@pars** |
| Проверка карточки на сайте после импорта | **@даша** (вёрстка) / ручная QA |

**Типичный pipeline:**

1. `@макс` — CPT, ACF, шаблон `single-product.php`
2. `@pars` — импорт из Excel + дополнение с сайта поставщика
3. Проверка каталога в админке и на фронте

## Примеры вызова

```
@pars Импортируй товары из products.xlsx в Agro-As — upsert по артикулу
@pars Обнови цены из supplier_feed.json, dry-run сначала
@pars Спарси характеристики с https://supplier.ru/catalog/item-123 и добавь в JSON
@pars Сгенерируй catalog_products.php для Slon из Excel прайса
```

## Тестовые задачи

1. **Простая:** `@pars Покажи маппинг колонок Excel → ACF для Agro-As`
2. **Типичная:** `@pars Импорт 50 товаров из products.json в WP — dry-run + отчёт`
3. **Сложная:** `@pars Excel + парсинг поставщика → upsert в Bitrix каталог Slon`

---

> Агент создан по методологии Denis Pleada — курс «CURSOR: Экзоскелет для ума»
