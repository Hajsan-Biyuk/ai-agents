# Макс — интегратор CMS

AI-агент для внедрения готовой вёрстки на WordPress, WooCommerce, 1C-Bitrix, MODX.

## Вызов

В чате Cursor:

```
@макс [ваша задача]
```

## Что делает

- Перенос HTML/CSS/JS в тему WordPress
- Шаблоны WooCommerce (каталог, карточка, корзина, checkout)
- ACF, меню, CPT, AJAX, кастомные поля товаров
- **Паттерн CPT:** `_КОНТЕКСТ/cpt-wordpress-pattern.md`
- **Паттерн карточки товара:** `_КОНТЕКСТ/product-single-wp-pattern.md`
- **Паттерн каталога:** `_КОНТЕКСТ/product-catalog-wp-pattern.md`
- **Паттерн корзины:** `_КОНТЕКСТ/basket-wp-pattern.md`
- **Категории на главной:** `_КОНТЕКСТ/front-categories-wp-pattern.md`
- Интеграция на 1C-Bitrix и MODX
- Python-скрипты для миграций и импорта (по запросу)

## Стек

PHP · JavaScript · jQuery · Python

## Правила

- Правила агента: `.cursor/rules/макс.mdc`
- **Канон Agro-As (WP без WC):** `_КОНТЕКСТ/agro-as-wp-pattern.md` — **делать только так**
- **Паттерны CMS:** `_КОНТЕКСТ/паттерн/` — **новый паттерн после сдачи → сразу дописать**
- **Канон Agro-As:** `_КОНТЕКСТ/agro-as-wp-pattern.md`
- ACF: `_КОНТЕКСТ/acf-zarina-reference.md`
- Вёрстка и классы: `агенты/КОЛЯ/_КОНТЕКСТ/паттерн/`

## Структура агента

```
агенты/МАКС/
├── README.md
├── _КОНТЕКСТ/
│   ├── паттерн/                ← ГЛАВНЫЙ: WP/ACF фрагменты (дополнять после каждой задачи)
│   ├── agro-as-wp-pattern.md   ← КАНОН натяжки Agro-As
│   ├── cpt-wordpress-pattern.md ← КАНОН регистрации CPT + taxonomy
│   ├── product-single-wp-pattern.md ← КАНОН карточки товара (card.html)
│   ├── product-catalog-wp-pattern.md
│   ├── basket-wp-pattern.md
│   ├── front-categories-wp-pattern.md ← Категории на главной из product_cat
│   ├── acf-zarina-reference.md
│   ├── референсы.md
│   └── примеры/
└── _ЛОГИ/
```

## Рабочая тема Agro-As

**Путь:** `Agro-As/maintheme/` (не в папке агента)

## Связь с @коля

| Этап | Агент |
|------|--------|
| Вёрстка по макету | @коля |
| Внедрение на CMS | @макс |

## Тестовые задачи

1. **Простая:** `@макс Подключи header.php из вёрстки в тему WP`
2. **Типичная:** `@макс Внедри footer с ACF-контактами как в natyajka`
3. **Сложная:** `@макс Тема WooCommerce из папки туроператор/ по образцу natyajka`

---

> Агент создан по методологии Denis Pleada — курс «CURSOR: Экзоскелет для ума»
