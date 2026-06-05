## 2026-06-01 — Berega: Fancybox без сдвига влево

**Запрос:** При открытии модалок страница дёргается влево.

**Причина:** `scrollbar-gutter: stable` на `html` + Fancybox `hideScrollbar: true` (доп. `margin-right` на `body`).

**Решение:** `hideScrollbar: false` в defaults и в `$(".fancybox").fancybox()`, `afterClose` — сброс `#fancybox-style-noscroll`, страховка в `app.css`.

**Оценка:** ✅

**Пример:** `агенты/КОЛЯ/_КОНТЕКСТ/примеры/2026-06-01_fancybox_scrollbar_gutter_без_сдвига.md`
