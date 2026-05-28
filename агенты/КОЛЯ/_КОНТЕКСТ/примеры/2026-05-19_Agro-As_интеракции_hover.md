# Agro-As — hover-анимации кнопок и карточек (2026-05-19)

**Проект:** `Agro-As/`  
**Файл:** `css/main.css` — блок **«Interactions — hover и focus»** (в конце файла)  
**Статус:** ✅ утверждено пользователем («супер, зафиксируй»)  
**Версия ассетов:** `?ver=3.3`

---

## Принцип

- Один блок CSS в конце `main.css`, без дублирования по секциям.
- Easing: `cubic-bezier(0.22, 1, 0.36, 1)` → переменная `--ease-smooth`.
- Тени: `--shadow-btn`, `--shadow-card`, `--shadow-card-deep`.
- **Не** использовать `gap` на `.row` (как и везде в проекте).
- Учитывать `prefers-reduced-motion: reduce` — отключать transform/transition.

---

## Кнопки

| Класс | Hover |
|-------|--------|
| `.btn-main`, `.btn-catalog`, `.btn-order` | `translateY(-3px)`, тень, фон `#b5dc4a` |
| `.btn-main--yellow` | фон `#e6c800` |
| `.btn-main--dark` | фон `#0a3d32` |
| `.btn-main--outline` | полупрозрачный зелёный фон |
| `.btn-main img` (стрелка) | `translateX(5px)` |
| `.btn-telegram` | подъём + `scale(1.06)`, тень фиолетовая, фон `#9b68eb` |
| `.menu-pills__list li a:not(.btn-catalog)` | фон `rgba(255,255,255,0.18)` |

`:focus-visible` — outline `2px solid #c9ec5f`, offset `3px`.

---

## Карточки и блоки

| Элемент | Эффект |
|---------|--------|
| `.item-advantage` | подъём −5px, тень; иконка scale + rotate −4° |
| `.item-advantage__photo img` | zoom 1.05 |
| `.item-category` | подъём −6px, тень; градиент легче; фон `scale(1.07)` |
| `.item-product` | подъём −6px, тень; фото scale 1.06; `.btn-order` активнее |
| `.wholesale-card` | подъём −4px, тень; иконка scale 1.08 |
| `.wholesale-benefit`, `.delivery-benefit` | сдвиг вправо 8px; иконка scale 1.12 |
| `.item-step` | сдвиг вправо 6px; круг `.item-step__num` scale + тень |
| `.item-question` (не `.active`) | тень; head — зелёный фон 22% opacity |
| `.item-question__toggle` | scale 1.08 на hover head |
| `.partners__item` | подъём −4px; лого scale 1.05 |
| `.billbord__promo` | подъём −4px, тёмная тень |
| `.steps__photo img`, `.delivery__photo img` | scale 1.03 |
| `.contacts__map` | тень + лёгкий zoom карты 1.02 |

---

## Что не трогать

- Footer-ссылки — свои `:hover` уже в блоке Footer.
- Глобальное правило `a, button, input, img { transition: all 0.3s }` — оставить; блок Interactions задаёт точечные `transition` с приоритетом.

---

## При правках

1. Менять только секцию **Interactions** в `main.css` (или расширять её).
2. Увеличивать `?ver=` в `index.html`.
3. Новые интерактивные карточки — те же паттерны: `transform` + `box-shadow` + опциональный zoom дочернего `img`.

---

*Связано:* `2026-05-19_Agro-As_главная_паттерны.md`, лог `_ЛОГИ/2026-05-19_Agro-As_интеракции.md`
