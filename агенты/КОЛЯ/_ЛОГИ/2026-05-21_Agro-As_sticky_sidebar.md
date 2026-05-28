# Agro-As — sticky catalog-sidebar — 2026-05-21

**Задача:** `.catalog-sidebar` на desktop липнет при скролле, как buy-блок на `card.html`.

**Сделано:**
- `media.css`: `.catalog-page__sidebar-col` — `position: sticky`, `top: 24px`, `align-self: flex-start`, `z-index: 2`
- Брейкпоинт **`min-width: 992px`** (до 991px — без sticky, mobile-фильтр)
- Паттерн зафиксирован: `_КОНТЕКСТ/примеры/2026-05-21_Agro-As_sticky_sidebar_desktop.md`

**Файлы:** `Agro-As/css/media.css`, `Agro-As/category.html` (`media.css?ver=6.4`)
