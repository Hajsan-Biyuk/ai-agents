# Протокол сверки с Figma (@даша)

Цель: снять **измеримые** спеки с макета и сравнить с кодом без пропуска деталей.

---

## 1. Лимиты API (обязательно)

Как в skill-verstka и @коля:

- Только `get_figma_data` с конкретным `nodeId` (не весь файл).
- 1–3 запроса на задачу; не повторять из кэша сессии.
- Ассеты — батч `download_figma_images`, не по одной иконке.
- При rate limit → работать из кэша, `VERDICT: BLOCKED` если спеков нет.

---

## 2. Подготовка

1. Из URL извлечь `fileKey` и `node-id` → `nodeId`.
2. Отдельно Desktop и Mobile (разные nodeId).
3. Составить список секций в scope.

---

## 3. Таблица спеков (шаблон)

Для каждой секции заполнить:

```markdown
## [Название секции] — Figma nodeId: xxx

| Элемент | Свойство | Значение макета | Селектор в коде | Значение в коде | OK? |
|---------|----------|-----------------|-----------------|-----------------|-----|
| Заголовок h2 | font-size | 48px | .title-section | 44px | ❌ |
| Заголовок h2 | font-weight | 600 | .title-section | 600 | ✅ |
| Заголовок h2 | line-height | 56px | .title-section | 56px | ✅ |
| Заголовок h2 | color | #1A1A1A | .title-section | #1A1A1A | ✅ |
| Секция | padding-top | 80px | .advantages | 60px | ❌ |
| Карточка | width | 382px | .item-product | max 382px | ✅ |
| Фото | object-fit | contain | .item-product__top img | cover | ❌ |
```

**Минимум на элемент:** font-family, font-size, font-weight, line-height, color, padding, margin, width, height, border-radius, background.

---

## 4. Порядок сверки по странице

1. **Header** — logo (w×h), меню, CTA, phone, callback, sandwich.
2. **Hero / billbord** — h1, descr, form-booking, scroll-down.
3. **Секции сверху вниз** — по DOM в `index.html`.
4. **Footer** — колонки, аккордеон mobile, соцсети.
5. **Модалки** — `#modal-callback` (скрытый блок + триггеры).
6. **Mobile-фрейм** — повторить таблицу для ключевых секций @ 992px и ниже.

---

## 5. Ассеты

| Проверка | Как |
|----------|-----|
| Наличие файла | `img/` vs список export из Figma |
| Имя файла | kebab-case, без пробелов |
| Размер файла | не placeholder 1×1 |
| Отображение | width/height в CSS vs макет |
| Иконки | SVG inline или FA класс из макета |

---

## 6. Инструменты

- **Figma MCP** (Framelink): `get_figma_data`, `download_figma_images`
- **Код:** Read/Grep по `main.css`, `media.css`, HTML
- **Сравнение:** DevTools Computed (если есть браузер) — скриншот vs макет

Если браузера нет — статическая сверка таблицы спеков с CSS достаточна для FAIL; в отчёте указать ручные шаги.

---

## 7. Что записать в отчёт

Каждый ❌ из таблицы → строка в разделе «Расхождения с Figma»:

```
Секция advantages, .title-section: макет font-size 48px, в коде 44px (main.css:312) — Critical
```

---

*Использовать вместе с `чек-лист_тестировщика.md`.*
