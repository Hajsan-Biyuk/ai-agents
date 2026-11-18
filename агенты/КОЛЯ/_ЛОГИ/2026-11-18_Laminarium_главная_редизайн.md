## 2026-11-18 — Laminarium: статичная вёрстка главной (редизайн)

**Запрос:** главная laminarium.com по Figma `o0HxCw5ZSuOVfvtH4hJDNT` / `77:97` в `Laminarium/redesign/html/` — шапка и подвал поверх старого шаблона Аспро: Маркет, без Bootstrap и глобальных стилей, JS без библиотек.

**Результат:** `index.html`, `css/lam.css`, `js/lam.js`, `fonts/` (Inter + Onest woff2, кириллица/латиница), `img/` (webp + svg из Figma). Все правила под `.lam-header` / `.lam-home` / `.lam-footer`, классы `lam-`. Мобильная шапка через `display: contents` + grid без дублей разметки. Карусели — scroll-snap + стрелки, вкладки — role=tab.

**Оценка:** —

**Уроки:**
- Вёрстка поверх чужого шаблона: каждое правило `.обёртка .lam-*` (0,2,0) — бьёт `a:hover`, `input[type=…]` Аспро; нормализация через `:is(обёртки) тег` (0,1,1) — ниже компонентов.
- Абсолютный `.lam-visually-hidden` внутри scroll-трека без `position: relative` на треке вылезает из overflow и растягивает документ (на мобильном layout viewport 1500px). Трек — всегда `position: relative`.
- Шрифты переименованы (`LamInter`, `LamOnest`), чтобы не конфликтовать с `@font-face` шаблона.
