# Agro-As — внутренние страницы WP

**Дата:** 2026-05-25  
**Задача:** натянуть wholesale, delivery, contacts по паттерну главной

## Сделано

### Шаблоны страниц
- `page-wholesale.php` — Template Name: Оптовым клиентам
- `page-delivery.php` — Template Name: Доставка и оплата
- `page-contacts.php` — Template Name: Контакты

### ACF JSON (acf-json/)
- `group_agro_as_wholesale.json` — location: page-wholesale.php
- `group_agro_as_delivery.json` — location: page-delivery.php
- `group_agro_as_contacts.json` — location: page-contacts.php
- `acf-import-all.json` — пересобран (5 групп)
- `modified`: 1779798995

### template-parts/page/
- wholesale: hero, advantages, tiers, catalog, steps, request
- delivery: payment, shipping
- contacts: contacts.php (карточки + форма + #map)

### Прочее
- Скопированы `icons/` в maintheme (иконки блока заявки wholesale)
- Контакты из Options: telefon, pochta, polnyj_adres, telegram, politika, soglasie
- Карта: karta_shirota/dolgota/zoom в ACF страницы контактов

## После деплоя

1. ACF → Sync или Import `acf-import-all.json`
2. Создать 3 страницы в WP, назначить шаблоны
3. Заполнить ACF-поля контентом из статики
4. Options — глобальные контакты и ссылки политик

## Не сделано (backlog)

- category, card
- wp_nav_menu()
