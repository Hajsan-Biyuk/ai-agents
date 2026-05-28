## 2026-05-21 — Berega: плавный скролл `.zk-anchors__inner`

**Запрос:** Плавный скролл к якорям в поднавигации карточки объекта (jQuery animate 600 ms).

**Результат:** `initObjectPage()` в `Berega/public_html/local/templates/berega/js/common.js` — `getZkAnchorOffset()` (hero-header + zk-anchors + 16px), клик по `.zk-anchors__inner a[href^="#"]`, `.is-active` на пункте.

**Оценка:** ✅

**Уроки:** На странице объекта не `.header`, а `.hero-header`; offset учитывает ещё липкий `.zk-anchors`. Селектор якорей — узкий, чтобы не перехватывать fancybox и прочие `#`-ссылки.

**Пример:** `агенты/КОЛЯ/_КОНТЕКСТ/примеры/2026-05-21_Berega_zk-anchors_плавный_скролл.md`
