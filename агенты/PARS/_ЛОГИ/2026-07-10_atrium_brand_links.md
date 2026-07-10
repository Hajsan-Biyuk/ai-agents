# 2026-07-10 — Атриум — ссылка бренда без DETAIL_TEXT (Aspro Max)

**Запрос:** на `/info/brands/` у «Artkera Group» нет ссылки — только текст, `class="wti"`, заглушка `noimage_brand.svg`.

**Сайт:** https://atriumceramic.sheer26.ru · Bitrix + Aspro Max · ИБ брендов **68**

---

## Симптом

| Признак | Значение |
|---------|----------|
| Элемент | Artkera Group, ID 79923 |
| Детальная страница | `/info/brands/artkera-group/` — **HTTP 200**, страница есть |
| В листинге | нет `<a href=…>`, класс `wti` (without text/image link) |
| У соседних брендов (Alma Ceramica, AltaCera) | ссылки есть |

## Причина

Шаблон Aspro Max `news.list/items-list/template.php` (стр. 72):

```php
$bDetailLink = $arParams['SHOW_DETAIL_LINK'] != 'N'
  && (!strlen($arItem['DETAIL_TEXT'])
    ? ($arParams['HIDE_LINK_WHEN_NO_DETAIL'] !== 'Y' && $arParams['HIDE_LINK_WHEN_NO_DETAIL'] != 1)
    : true);
```

При `HIDE_LINK_WHEN_NO_DETAIL=Y` (типично для раздела partners/brands) ссылка **скрывается**, если `DETAIL_TEXT` пустой.

Импортёр `AtriumImporter::resolveBrand()` создавал бренд только с `NAME`, `CODE`, `ACTIVE` — без `DETAIL_TEXT`.

**Важно:** поставщик ≠ бренд. «Сантехцентр» — поставщик, бренд для него создавать не нужно. «Artkera Group» — реальный бренд из прайса Арткеры.

---

## Решение

### 1. Одноразовый фикс на сервере

`local/tools/import/fix_brand_links.php` — POST + `X-Import-Token`, тело `{"names":["Artkera Group"]}` или пусто = все бренды ИБ без DETAIL_TEXT.

Ответ: `{"fixed":["Artkera Group"],"skipped":[],"brand_iblock_id":68}`

### 2. Постоянный фикс в импортёре

`AtriumImporter.php` → `resolveBrand()`:

- при **создании** бренда: `DETAIL_TEXT` = `NAME`, `DETAIL_TEXT_TYPE` = `text`;
- при **нахождении** существующего с пустым `DETAIL_TEXT` — дописать то же (не dry-run).

### 3. Проверка после фикса

На `/info/brands/` появились:

```html
<a href="/info/brands/artkera-group/">Artkera Group</a>
```

Класс `wti` может остаться из‑за отсутствия логотипа — это отдельно от ссылки.

---

## Команды (для повтора)

```bash
# все бренды без DETAIL_TEXT
curl -sS -X POST "https://atriumceramic.sheer26.ru/local/tools/import/fix_brand_links.php" \
  -H "Content-Type: application/json" \
  -H "X-Import-Token: $ATRIUM_IMPORT_TOKEN" \
  -d '{}'

# один бренд
curl -sS -X POST "…/fix_brand_links.php" \
  -H "Content-Type: application/json" \
  -H "X-Import-Token: $ATRIUM_IMPORT_TOKEN" \
  -d '{"names":["Artkera Group"]}'
```

Деплой: `Атриум/site/local/php_interface/atrium_import/AtriumImporter.php`, `…/tools/import/fix_brand_links.php` → SFTP `atriumceramic.sheer26.ru/public_html/local/…`

---

## Урок для @pars

1. После импорта с автосозданием брендов — проверить `/info/brands/`: кликабельность, не только карточки товаров.
2. Aspro Max + `HIDE_LINK_WHEN_NO_DETAIL=Y` → при создании элементов справочника брендов **всегда** заполнять `DETAIL_TEXT` (хотя бы названием).
3. Детальная страница может существовать при пустом `DETAIL_TEXT` — баг только в листинге.
4. Поставщик (santehcentr, rgw) не добавлять в ИБ брендов без явного ТЗ.

**VERDICT: PASS** — ссылка Artkera Group восстановлена, импортёр обновлён.
