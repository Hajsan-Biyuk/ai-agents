# Agro-As — слайдер товаров — 2026-05-27

**Задача:** главная `.products` + блок «Рекомендуем» на карточке — отступы между карточками, стрелки только когда есть прокрутка, без растягивания карточек.

**Сделано:**
- Отступы **8px** на `.products__slide` (`box-sizing: border-box`), не на `.slick-slide`
- Карточки **max-width: 382px** — убран `max-width: none` в slick
- **Slick только при** `товаров > видимых колонок`; иначе flex-сетка 25% / 33% / 50% / 100%
- Стрелки через класс **`products-slider-layout--scrollable`**
- Общий **`initProductsCardsSlider()`** для главной и recommend

**Паттерн:** `_КОНТЕКСТ/примеры/2026-05-27_Agro-As_products_slider.md`

**Файлы:** `Agro-As/maintheme/js/common.js`, `css/main.css`, `css/media.css`, `template-parts/front/products.php`, `template-parts/product/recommend.php`
