# 2026-05-25 — ACF sync: modified, дубликаты, тип поля кнопки

## Проблемы на стенде

1. Поля из JSON не появлялись — не было `"modified"` в acf-json
2. Добавлен `acf_import_field_group()` на `acf/init` → при каждой загрузке создавались дубликаты «Основные настройки»
3. `knopka_ssylka` — запрос сменить тип url → text (ссылка кнопки)

## Решения

- Добавлен `"modified": 1779779664` в оба JSON
- Создан `acf-import-all.json` для ручного Import
- Переименован options JSON → `group_agro_as_options.json`
- **Удалён** автоимпорт из `functions.php` — только load_json + save_json
- `knopka_ssylka` в banner: type `text`

## Запрет на будущее

Не использовать `acf_import_field_group()` в hooks без version-gate и без передачи `ID` существующей группы.

См. `_КОНТЕКСТ/agro-as-wp-pattern.md` §5.
