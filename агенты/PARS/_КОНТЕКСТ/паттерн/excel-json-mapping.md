# Паттерн — маппинг Excel / JSON → CMS

**Обязательно для @pars:** перед любым импортом построить и показать таблицу маппинга.

---

## 1. Алгоритм

```
1. Прочитать источник (preview 5 строк)
2. Определить ключ upsert (один на товар)
3. Сопоставить колонки → поля CMS
4. Нормализовать значения (trim, цена, slug)
5. Записать normalized JSON
6. Dry-run → отчёт
```

## 2. Ключ upsert (выбрать один)

| Ключ | WordPress | Bitrix | WooCommerce |
|------|-----------|--------|-------------|
| Артикул | ACF / post meta | свойство ARTICLE | `_sku` |
| Slug | `post_name` | `CODE` | slug товара |
| SKU | meta | свойство | `_sku` |
| Внешний ID | meta `external_id` | XML_ID | meta |

**Правило:** один стабильный ключ на проект. Дубликаты ключа в файле — error в dry-run.

## 3. Нормализация

### Текст

- `strip()`, collapse whitespace
- Кавычки «» → по ТЗ или as-is
- HTML в описании — только если CMS ожидает HTML (`post_content`)

### Цена

```python
def parse_price(raw: str) -> float:
    s = str(raw).replace('\u00a0', '').replace(' ', '')
    s = s.replace('₽', '').replace('руб.', '').replace(',', '.')
    return round(float(s), 2)
```

### Slug (транслит)

```python
from slugify import slugify
slug = slugify(title, lowercase=True, max_length=80)
# или: abeliya-abelia-edward-goucher
```

### Категория

- WP: term slug `product_cat` — создать term если нет (dry-run: «will create term»)
- Bitrix: `CATEGORY` → section code (`pp`, `handle`, `bigbag`)

## 4. Шаблон маппинга (документировать в отчёте)

| Колонка Excel | Поле JSON | CMS | Обязательно |
|---------------|-----------|-----|-------------|
| Артикул | `article` | ACF / свойство | да |
| Название | `title` | post_title / NAME | да |
| Цена | `price` | ACF variacii / CPrice | да |
| Категория | `category` | product_cat / IBLOCK_SECTION | да |
| Описание | `description` | post_content | нет |
| Фото URL | `images[]` | ACF galereya / PREVIEW | нет |

## 5. JSON после нормализации

```json
{
  "meta": {
    "source": "products.xlsx",
    "sheet": "Товары",
    "upsert_key": "article",
    "generated_at": "2026-07-03T12:00:00+03:00"
  },
  "products": [
    {
      "article": "PP-55-105-48",
      "code": "pp-55-105-48",
      "title": "Мешок полипропиленовый 55×105/48 гр",
      "category": "pp",
      "price": 14.20,
      "properties": {
        "size": "55×105 см",
        "density": "48 г/м²"
      }
    }
  ]
}
```

Путь: `data/normalized/{project}_{date}.json`

## 6. Excel — чтение (Python)

```python
import pandas as pd

df = pd.read_excel("products.xlsx", sheet_name="Товары", dtype=str)
df = df.dropna(how="all").fillna("")
# preview
print(df.head().to_markdown())
```

Первая строка — заголовки. Если заголовки на строке 2 — `header=1`.

## 7. Конфликты при upsert

| Ситуация | Действие |
|----------|----------|
| Ключ есть на сайте | **update** только полей из файла |
| Ключ есть только в файле | **create** |
| Ключ на сайте, нет в файле | **skip** (не удалять!) |
| Удаление лишних | только с флагом `--purge` / `purge=1` и явным ТЗ |

## 8. Отчёт dry-run

```json
{
  "created": 12,
  "updated": 45,
  "skipped": 3,
  "errors": [
    {"row": 7, "key": "", "message": "empty article"}
  ]
}
```

Сохранять в `data/reports/`.
