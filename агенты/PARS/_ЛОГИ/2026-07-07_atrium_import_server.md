# 2026-07-07 — Атриум — серверная часть импорта (автономный конвейер)

**Задача:** импорт без участия Cursor: inbox на сервере → cron → сайт.

## Сделано

### Локально (Атриум/import/)
- `convert.py` — добавлены `convert_supplier()` (программный вход) и `--files-dir` (чтение из inbox); очистка упаковки из названий (`clean_name`, PACK_RE).
- `run_import.py` — оркестратор: inbox → конвертация → валидация → dry-run → импорт → processed/ + отчёт; карантин при ошибках; lock от параллельных cron; флаги `--dry-run-only`, `--confirm`, `--supplier`.
- `config.yaml` — url, токен, пороги per-supplier (`auto_confirm`, `max_create_auto`, `max_convert_errors`, `with_photos`). В `.gitignore`.
- `README.md` — инструкция для менеджера и разработчика.

### На сервере (Beget, `~/atriumceramic.sheer26.ru/import/`)
- Структура: `inbox/{artkera,rgw,santehcentr}`, `processed/`, `quarantine/`, `reports/`, `suppliers/`, `data/`.
- Python 3.10 системный; venv на Beget не работает (нет ensurepip) → `pip3 install --user pandas openpyxl xlrd` — OK (pandas 2.3.3).
- Загружены: convert.py, run_import.py, config.yaml, профиль artkera, run_cron.sh.
- Файлы Арткеры (6 шт.) лежат в `inbox/artkera/` — готовы к импорту.

### Тесты
- Локальный e2e: inbox → dry-run → отчёт: **create=203, update=1164, err=0**.
- Серверный e2e (запуск на Beget): тот же результат — **create=203, update=1164, err=0**. Отчёт `reports/2026-07-07_105655_artkera.json`.

## Cron (осталось: добавить в панели Beget вручную)

```
/bin/bash /home/s/sheer26/atriumceramic.sheer26.ru/import/run_cron.sh
```
Расписание: */30. SSH `crontab` на Beget недоступен — только через панель.

## Поведение по умолчанию (безопасное)
- `auto_confirm: false` у всех → cron делает dry-run и ставит статус `awaiting_confirm`, файлы остаются в inbox.
- Полный импорт Арткеры: `python3 run_import.py --supplier artkera --confirm` — **не запускался** (пауза по указанию пользователя).

**VERDICT: PASS** — конвейер автономен, ждёт cron-задание в панели и команду на полный импорт.
