# 2026-05-25 — Agro-As: главная, header, footer, ACF Options

> **Устарело:** тема перенесена в `Agro-As/maintheme/`. Актуальный канон: `_КОНТЕКСТ/agro-as-wp-pattern.md`

## Задача

Натяжка вёрстки Agro-As на WordPress без WooCommerce: главная страница, header, footer, ACF Options в functions.php.

## Создано

```
агенты/МАКС/maintheme/
├── style.css
├── functions.php      ← ACF Options page, SVG mime, menus
├── header.php         ← ACF: email, telefon, telegram
├── footer.php         ← ACF: adres, email, telefon, telegram, politika, soglasie
├── front-page.php     ← все секции главной из index.html
├── index.php
├── css/ js/ img/ fonts/ libs/  ← скопировано из Agro-As/
```

## ACF Options — поля для создания в админке

| Имя поля | Тип | Пример |
|----------|-----|--------|
| `email` | Email | agroas@mail.ru |
| `telefon` | Text | +7 (978) 519-95-23 |
| `telegram` | URL | https://t.me/... |
| `adres` | Textarea | Республика Крым, ... |
| `politika` | URL | /privacy-policy/ |
| `soglasie` | URL | /soglasie/ |

Location: Options Page = «Настройки темы»

## Проверка на стенде

1. Скопировать `maintheme/` в `wp-content/themes/`
2. Активировать тему «Agro As»
3. Настройки → Чтение → «Статическая страница» (любая) + front-page.php сработает как is_front_page
4. ACF → создать группу полей на Options Page
5. Заполнить контакты
