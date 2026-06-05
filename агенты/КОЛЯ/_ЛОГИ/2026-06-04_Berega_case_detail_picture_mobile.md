## 2026-06-04 — Berega: картинка кейса на мобилке

**Запрос:** На странице одного кейса не скрывать `case_detail_picture`, вывести после `.main-features` (≤992px).

**Результат (`css/app.css`, `_app.css`):**
- Убран `display: none`.
- Flex `order`: h1 (1) → main-features (2) → picture (3) → case_detail_h1 (4).
- Картинка на всю ширину, `border-radius: 12px`, отступ снизу 24px.
- Десктопный `@media (min-width: 993px)` без изменений.

**Оценка:** ✅
