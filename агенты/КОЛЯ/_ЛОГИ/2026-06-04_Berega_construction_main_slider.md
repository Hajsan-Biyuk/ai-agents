## 2026-06-04 — Berega: слайдер construction-main__media

**Запрос:** На главной в блоке `construction-main__media` не работает слайдер; добавить дотсы и автопрокрутку каждые 5 секунд.

**Причина:** Инициализация через `setInterval` в IIFE до готовности DOM; без точек.

**Результат:**
- Слайды обёрнуты в `.construction-main__slider`, Slick (fade, dots, autoplay 5000 ms).
- `initConstructionMainSlider()` в `$(document).ready` и в общем `$(function)`.
- Стили слайдера и дотсов в `main.css` (дотсы справа снизу на градиенте).
- Разметка: `include/section-main-banner-3.php`, `_section-main-banner-3.php`, `new-main/index.php`.

**Оценка:** ✅
