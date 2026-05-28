# Agro-As — рефакторинг разметки форм

**Дата:** 2026-05-25  
**Запрос:** формы как в `reference/Артэк` — без лишних классов в `<form>`, поля в `item-form`

## Результат

- Контакты и wholesale (HTML + WP) — упрощена разметка
- CSS перенесён на `.contacts-page__card--form .item-form`, `.wholesale-page-request__form .item-form`
- Удалены классы: `contacts-page__form`, `__field`, `__fields`, `__checkbox`, `__submit`, `wholesale-page-request__form-fields`, `__fields-row`, `__label`, `__form-footer`, `__checkbox`, `__submit`

## Документация

- `агенты/КОЛЯ/_КОНТЕКСТ/формы-разметка.md` — канон для @коля
- `агенты/КОЛЯ/_КОНТЕКСТ/примеры/2026-05-25_Agro-As_формы_item-form.md`
