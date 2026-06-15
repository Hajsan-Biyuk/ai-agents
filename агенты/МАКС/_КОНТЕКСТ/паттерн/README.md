# Паттерны CMS — @макс

**Главный источник кода WP/ACF/CPT.** Не искать `reference/natyajka` и `reference/zarina-wp` — сначала эта папка.

## Как пользоваться

1. Задача на натяжку → открыть `agro-as.md` или тематический файл.
2. Классы и разметку не менять — сверка с `агенты/КОЛЯ/_КОНТЕКСТ/паттерн/`.
3. **Новый паттерн** после сдачи — дописать сюда + журнал ниже.

## Оглавление

| Файл | Когда |
|------|--------|
| [header-footer-wp.md](header-footer-wp.md) | `header.php`, `footer.php`, libs, модалки, CF7, cookies |
| [service-pages-wp.md](service-pages-wp.md) | `404.php`, `page-thnx.php` — стиль сайта |
| [acf-page.md](acf-page.md) | Options, repeater, page templates |
| [agro-as.md](agro-as.md) | Канон Agro-As — структура темы, хелперы |

## Каноны проекта (расширенные)

Уже существуют — не дублировать, ссылаться:

| Документ | Тема |
|----------|------|
| `../agro-as-wp-pattern.md` | Полный канон Agro-As |
| `../cpt-wordpress-pattern.md` | CPT + taxonomy |
| `../product-single-wp-pattern.md` | Карточка товара |
| `../product-catalog-wp-pattern.md` | Каталог |
| `../basket-wp-pattern.md` | Корзина |
| `../acf-zarina-reference.md` | Поля ACF zarina |

## Журнал паттернов

| Дата | Паттерн | Файл |
|------|---------|------|
| 2026-06-05 | Стартовая структура паттерн/ | README + wp-файлы |
| 2026-06-05 | Страница сертификатов | acf-page.md |
| 2026-06-15 | Cookies + CF7 CSS, ipify, cookies-modal | header-footer-wp.md |
| 2026-06-15 | CF7 редирект на page-thnx.php | header-footer-wp.md |
| 2026-06-15 | 404 + Спасибо в стиле сайта | service-pages-wp.md |
