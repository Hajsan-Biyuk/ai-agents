# 2026-05-25 — ACF главная + template-parts

> **Канон:** `_КОНТЕКСТ/agro-as-wp-pattern.md`. Паттерн pokazat: `_ЛОГИ/2026-05-25_pokazat_pattern.md`

## Сделано

- `acf-json/group_agro_as_front_page.json` — 10 вкладок секций
- `inc/front-defaults.php` — fallback из статики
- `template-parts/front/*.php` — 9 секций
- `front-page.php` — подключение частей

## Вкладки ACF (Главная страница)

| Tab | Group name | Repeater |
|-----|------------|----------|
| Баннер | `banner` | osobennosti, spisok |
| Преимущества | `preimushhestva` | elementy (tip/cvet/ikonka) |
| Категории | `kategorii` | elementy (razmer sm/md/lg) |
| Товары | `tovary` | elementy |
| Опт | `opt` | tipy, preimushhestva |
| Как начать | `shagi` | elementy |
| Доставка | `dostavka` | preimushhestva |
| FAQ | `faq` | voprosy |
| Партнёры | `partnery` | logotipy |
| Контакты | `kontakty` | karta (тел/почта из Options) |

## Деплой

1. Залить `maintheme/` целиком
2. ACF → Sync available (или Import JSON)
3. Редактировать главную в админке
