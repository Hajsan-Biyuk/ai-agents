# 2026-05-25 — Создана папка maintheme, изучен референс zarina-wp

## Задача

Создать `агенты/МАКС/maintheme/` и изучить паттерн натяжки вёрстки на WordPress по `reference/zarina-wp`.

## Что сделано

- Создана рабочая папка `агенты/МАКС/maintheme/`
- Добавлен `maintheme/README.md` с паттерном интеграции
- Изучен референс `reference/zarina-wp/maintheme/`

## Ключевые находки (zarina-wp)

| Компонент | Паттерн |
|-----------|---------|
| CSS/JS | Явные `<link>` / `<script>` в header/footer + wp_head/wp_footer |
| Header | ACF Options (adres, telefon, telegram, max), is_front_page для logo |
| Footer | Модалки CF7, cookies, карта Yandex, common.js |
| Страницы | page-*.php с Template Name, ACF repeater/group |
| Главная | front-page.php, WP_Query для CPT catalog |
| CPT | single-catalog.php |
| functions.php | ACF Options page, SVG mime, excerpt filters |

## Следующий шаг

Ждём папку с готовой вёрсткой и задачу на конкретный проект.
