# Атриум — сервер импорта товаров

Конвейер: `inbox/{поставщик}/` → нормализация → dry-run → импорт на сайт → `processed/` + отчёт.

## Расположение

| Где | Путь |
|-----|------|
| Сервер (Beget) | `/home/s/sheer26/atriumceramic.sheer26.ru/import/` (вне public_html) |
| Endpoint сайта | `public_html/local/tools/import/receive.php` (токен) |
| Импортёр Bitrix | `public_html/local/php_interface/atrium_import/AtriumImporter.php` |
| Локальная копия | `Атриум/import/` (этот каталог) |

## Как загрузить товары (менеджер)

### RGW — два варианта

1. **Сырой прайс** — положить в `inbox/rgw/` файл `RGW_Price.xlsx` (как от ТМ). Cron сам соберёт плоский прайс с путями и ценами.
2. **Обработанные файлы** — 10 Excel из «Обработка прайса RGW» (с колонкой `Путь`).

Если в inbox лежит сырой прайс, обработанные файлы **игнорируются**.

Опционально: эталон в `data/reference/rgw/` (файлы «Обработка прайса RGW») — подтягивает пути, описания, фото.

### Центр Сантехники — два варианта

1. **Сырые прайсы** — `prices.xlsx` и/или `Прайс керамогранит….xlsx` в `inbox/santehcentr/`.
2. **После обработки** — `Santehcentr (Azario…).xls` + `Santehcentr - Аксессуары….xlsx` (с колонкой `Путь`).

Если в inbox лежат сырые файлы, обработанные **игнорируются**.

Опционально: эталон в `data/reference/santehcentr/` (файлы «после обработки»).

### Арткера — два варианта

1. **Один иерархический прайс** — положить в `inbox/artkera/` файл вида «Прайс-лист с … .xls» (как от ТМ). Cron сам разобьёт на 6 брендов и импортирует.
2. **6 файлов по брендам** — как раньше (Alma Ceramica, AltaCera, Delacora, LCM, NewTrend, Арткера).

Если в inbox лежит иерархический прайс, плоские файлы **игнорируются**.

Опционально: эталон обогащения в `data/reference/artkera/` (файлы «с обработкой») — подтягивает пути, описания, фото из прежнего каталога.

### Общий порядок

1. Положить Excel в `import/inbox/{artkera|rgw|santehcentr}/` (SFTP или файловый менеджер Beget).
2. Дождаться cron (или попросить запуск вручную).
3. Отчёт — в `import/reports/`, обработанные файлы — в `processed/`, проблемные — в `quarantine/` (с причиной в `REASON.txt`).

## Запуск вручную (SSH)

```bash
cd ~/atriumceramic.sheer26.ru/import

python3 run_import.py --dry-run-only              # только проверка, без записи
python3 run_import.py --supplier artkera --confirm # импорт с ручным подтверждением
python3 run_import.py                              # cron-режим: импорт только при auto_confirm=true
```

## Cron (панель Beget)

Задание (каждые 30 минут):

```
/bin/bash /home/s/sheer26/atriumceramic.sheer26.ru/import/run_cron.sh
```

Лог: `import/reports/cron.log`.  
Прогресс текущего импорта (партии): `import/reports/progress.log` — смотреть через `tail -f`.

**Поведение cron:** перед обработкой сравнивается fingerprint файлов в `inbox/{поставщик}/` с `data/state/{поставщик}.json`. Если прайс **не менялся** — поставщик пропускается (без dry-run на сайт). После успешного dry-run fingerprint сохраняется; при боевом импорте файлы уходят в `processed/`, state очищается.

Принудительный запуск без проверки:

```bash
python3 run_import.py --force
python3 run_import.py --supplier artkera --dry-run-only   # ручные флаги тоже обходят проверку
```

## Защита от ошибок (config.yaml, per-supplier)

| Параметр | Смысл |
|----------|-------|
| `watch_inbox: true` | cron пропускает поставщика, если файлы в inbox не менялись |
| `auto_confirm: false` | cron делает только dry-run; импорт — вручную с `--confirm`. После обкатки можно включить `true` |
| `max_convert_errors` | больше ошибок конвертации → файл в карантин |
| `max_create_auto` | dry-run создаёт больше N товаров → карантин (защита от кривого файла) |
| `with_photos` | скачивать фото новым товарам |

## Поставщики

| Ключ | inbox | Какие файлы класть |
|------|-------|-------------------|
| `artkera` | `inbox/artkera/` | 6 Excel по брендам **или** один иерархический прайс |
| `rgw` | `inbox/rgw/` | `RGW_Price.xlsx` (сырой) **или** 10 обработанных Excel с колонкой `Путь` |
| `santehcentr` | `inbox/santehcentr/` | `prices.xlsx` + керамогранит (сырые) **или** файлы «после обработки» с `Путь` |

Ошибки dry-run (любые) → карантин, импорт не выполняется.

## Подключение нового поставщика

1. `suppliers/_шаблон_/` → `suppliers/{key}/` (`profile.yaml` — синонимы колонок, `categories.yaml` — маппинг категорий).
2. `mkdir inbox/{key}`.
3. Блок в `config.yaml` → `suppliers.{key}`.
4. Тест: `python3 run_import.py --supplier {key} --dry-run-only`.

## Правила

- Импорт **только upsert** по артикулу (`CML2_ARTICLE`): создаёт и обновляет, никогда не удаляет.
- `config.yaml` содержит токен — **не коммитить** (в `.gitignore`).
- Python-зависимости на сервере: `pip3 install --user pandas openpyxl xlrd` (pyyaml системный).
