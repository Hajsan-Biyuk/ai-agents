# Паттерн — парсинг сайта поставщика

**Обязательно для @pars:** дополнять локальный каталог данными с внешнего сайта без ручного копирования.

---

## 1. Когда парсить

| Задача | Источник |
|--------|----------|
| В Excel нет описания / фото | Карточка на сайте поставщика |
| Актуальная цена / наличие | Страница товара или API |
| Полный прайс | Каталог / sitemap / Excel на сайте |

**Не парсить** если есть официальный JSON/Excel/API — приоритет у файлов.

## 2. MCP для парсинга (Cursor)

### Bright Data (основной в репо)

| Сценарий | Tool |
|----------|------|
| Найти URL карточки по артикулу | `search_engine` |
| Статическая HTML-страница | `scrape_as_markdown` или `scrape_as_html` |
| До 10 URL | `scrape_batch` |
| SPA, lazy-load, anti-bot | `scraping_browser_*` |
| JSON из markdown | `extract` |

### Firecrawl MCP (подключён глобально)

Конфиг: `~/.cursor/mcp.json` · шаблон: `.cursor/mcp.example.json`

| Сценарий | Tool |
|----------|------|
| Одна страница → markdown | `firecrawl_scrape` |
| Список URL | `firecrawl_batch_scrape` |
| Поиск по вебу | `firecrawl_search` |
| Раздел сайта | `firecrawl_map` → `firecrawl_crawl` / batch |
| JSON по схеме (цены, specs) | `firecrawl_extract` |
| Multi-source research | `firecrawl_agent` |

**Ключ:** [firecrawl.dev](https://firecrawl.dev) → free 1000 кредитов/мес → вставить `fc-…` в `FIRECRAWL_API_KEY` в `~/.cursor/mcp.json` (не коммитить).

**Выбор:** публичная страница → Firecrawl или Bright Data; логин/ЛК (AltaCera API) → Playwright MCP / Bright Data browser.

**Не** скрейпить Google/Bing URL напрямую — search через MCP.

## 3. Pipeline

```
1. Вход: article / название + base_url поставщика
2. Найти URL карточки (search или шаблон URL)
3. scrape → markdown/html
4. extract или BS4 → { title, price, specs, images[] }
5. Merge с локальной записью (не затирать поля без ТЗ)
6. normalized JSON → dry-run → upsert
```

## 4. Merge-правила

| Поле локальное | Поле с сайта | Действие по умолчанию |
|----------------|--------------|------------------------|
| title | title | не перезаписывать если локальное заполнено |
| price | price | **обновить** если `refresh_prices=true` |
| description | description | заполнить только если локальное пустое |
| images | images | дополнить, не удалять локальные |
| specs | specs | merge по ключу nazvanie |

Флаги задаёт пользователь в ТЗ.

## 5. BeautifulSoup (после html scrape)

```python
from bs4 import BeautifulSoup

soup = BeautifulSoup(html, "lxml")
title = soup.select_one("h1.product-title")
price = soup.select_one("[itemprop=price]")
specs = {}
for row in soup.select(".specs-table tr"):
    k, v = row.select("td")
    specs[k.get_text(strip=True)] = v.get_text(strip=True)
```

Селекторы — **уникальны для каждого поставщика** → сохранять в `scripts/pars/suppliers/{name}.py`.

## 6. Extract (AI из markdown)

Промпт для MCP `extract`:

```
Из markdown карточки товара извлеки JSON:
{ "title", "price": number, "currency", "availability",
  "description", "images": ["url"], "specs": {"key": "value"} }
Артикул должен быть: {article}
```

## 7. Этика и лимиты

- Уважать robots.txt и частоту запросов (пауза 1–2 с между карточками)
- Не обходить login без credentials пользователя
- Фиксировать `parsed_at` и `source_url` в meta JSON
- Hotlink изображений — только по ТЗ; иначе скачивать локально

## 8. Отчёт парсинга

```json
{
  "article": "ABC-123",
  "source_url": "https://supplier.ru/item/abc-123",
  "parsed_at": "2026-07-03T15:00:00+03:00",
  "fields_updated": ["price", "specs.density"],
  "fields_skipped": ["title"],
  "errors": []
}
```

## 9. Пример вызова

```
@pars Спарси цену и характеристики для артикулов из missing_specs.json
  с https://supplier.example/catalog/ — merge specs, обновить price
```
