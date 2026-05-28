# 2026-05-25 — Паттерн pokazat + проверки полей

> **Канон:** `_КОНТЕКСТ/agro-as-wp-pattern.md` — делать только так.

## Изменено

- ACF JSON: `pokazat` (true_false, default 1) в каждой секции главной
- Убраны `default_value` из полей контента
- Удалён `inc/front-defaults.php`
- Хелперы в `inc/helpers.php`: `maintheme_section_show()`, `maintheme_is_filled()`, `maintheme_img_url()`, `maintheme_img_alt()`
- Все `template-parts/front/*` — вывод только при `pokazat` + заполненных полях
- Изображения: только ACF array (`return_format: array`)

## PHP-паттерн секции

```php
$sect = get_field( 'banner' );
if ( ! maintheme_section_show( $sect ) ) {
    return;
}
// каждое поле: maintheme_is_filled( $value )
// картинка: maintheme_img_url( $img ), maintheme_img_alt( $img )
```

## Где проверка pokazat

- **Не** в `front-page.php`
- В начале каждого `template-parts/front/*.php`
- `faq-partners.php` — отдельно для `faq` и `partnery`

## ACF Sync

После деплоя: ACF → Sync «Главная страница» и «Основные настройки» (или Import `acf-import-all.json`).
