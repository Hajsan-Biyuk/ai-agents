## 2026-06-02 — Berega: каталог отельеров, форма scenario-screen

**Запрос:** Каталог `/hoteliers/catalog.html` — форма как на главной; чекбокс и боковые отступы совпадали с главной.

**Результат:**
- Блок `scenario-screen` — разметка 1:1 из `include/forms/form_2.php` (`.row` / `.col-12`, `.checkbox`, `input-phone`, select с `option value="" selected`).
- Секция вынесена из `.ok-catalog-screen`; SEO отдельно в `.ok-catalog-screen--seo`.
- Удалены стили `.ok-catalog-scenario`; отступ сверху: `.ok-catalog-screen + .scenario-screen`.
- Картинки: `../local/templates/berega/img/`.

**Оценка:** ✅ Отлично

**Уроки:** На прод-главной согласие — `.checkbox` внутри `.scenario-screen`, не `.scenario-consent`. Боковые отступы формы задаёт сетка `.scenario-screen__inner > .row > .col-12` (см. `main.css` ~2863–2907).

**Пример:** `агенты/КОЛЯ/_КОНТЕКСТ/примеры/2026-06-02_Berega_hoteliers_catalog_scenario_form.md`
