## 2026-05-20 — Fix brief @даша (Agro-As шрифты)

**Запрос:** Исправить подключение шрифтов по QA-отчёту Даши (404 на все @font-face)

**Результат:**
- Скачаны woff2 в `Agro-As/fonts/` (Manrope variable 400–800, Ubuntu 700, cyrillic + latin)
- `css/fonts.css` — локальные пути, `font-weight: 400 800` для Manrope
- `.btn-catalog`, `.btn-order` — `line-height: 140%` (Figma `style_AFAKHD`)
- `index.html`: `fonts.css?ver=1.0`, `main.css?ver=5.0`

**Оценка:** —

**Уроки:** Проверять HTTP 200 на woff2 перед сдачей; не использовать устаревшие gstatic v15 URL.

**NEXT:** @даша — ретест типографики
