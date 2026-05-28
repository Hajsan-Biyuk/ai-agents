# Яндекс.Карта на сайте (вместо картинки map.png)

**Обязательно для @коля** при блоке «Как нас найти» / «Контакты» с картой.

**Главный эталон:** `reference/Артэк/index.html`  
**Реализация Agro-As:** `Agro-As/js/common.js` → `initYandexMap()`, пример `примеры/2026-05-25_Agro-As_yandex_map.md`

---

## Когда применять

- В макете блок контактов с картой
- **Не** использовать статичный `map.png` / скрин карты из Figma
- **Да** — интерактивная Яндекс.Карта API 2.1

---

## HTML

Контейнер с фиксированной высотой из макета + пустой `#map`:

```html
<div class="contacts__map">
  <div
    id="map"
    data-lat="45.30861"
    data-lng="34.64750"
    data-zoom="14"
    data-pin="img/icon-map-pin.svg"
  ></div>
</div>
```

| Атрибут | Назначение |
|---------|------------|
| `data-lat` | Широта (десятичные градусы) |
| `data-lng` | Долгота |
| `data-zoom` | Масштаб 1–19 (обычно 12–15 для офиса) |
| `data-pin` | Путь к SVG/PNG метки (от корня темы) |

**Запрещено:** `<img src="map.png">` + декоративный pin поверх (дублирует метку API).

На странице контактов класс контейнера может быть `.contacts-page__map` — логика та же.

---

## Подключение скриптов

Порядок в конце `<body>` (после jQuery, перед `common.js`):

```html
<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU"></script>
<script src="js/common.js?ver=X"></script>
```

В WordPress — в `footer.php` темы, перед `common.js` и `wp_footer()`.

---

## JavaScript (`common.js`)

Паттерн из Артэк, адаптированный под Agro-As:

```javascript
if ($('#map').length && typeof ymaps !== 'undefined') {
  ymaps.ready(initYandexMap);
}

function initYandexMap() {
  var $mapEl = $('#map');
  if (!$mapEl.length) {
    return;
  }

  var lat = parseFloat($mapEl.data('lat'), 10) || 45.30861;
  var lng = parseFloat($mapEl.data('lng'), 10) || 34.64750;
  var zoom = parseInt($mapEl.data('zoom'), 10) || 14;
  var pinHref = $mapEl.data('pin') || 'img/icon-map-pin.svg';
  var coords = [lat, lng];

  var myMap = new ymaps.Map('map', {
    center: coords,
    zoom: zoom
  }, {
    searchControlProvider: 'yandex#search'
  });

  var placemark = new ymaps.Placemark(coords, {}, {
    iconLayout: 'default#image',
    iconImageHref: pinHref,
    hideIconOnBalloonOpen: false,
    iconImageSize: [24, 32],
    iconImageOffset: [-12, -32],
    balloonOffset: [0, -32]
  });

  myMap.controls.remove('searchControl');
  myMap.behaviors.disable('scrollZoom');
  myMap.geoObjects.add(placemark);
}
```

### Обязательно

| Требование | Зачем |
|------------|--------|
| `scrollZoom` **отключён** | Чек-лист Sheer — карта не зумится колесом при скролле страницы |
| `searchControl` убрать | Как в Артэк — чище блок контактов |
| Инициализация только если `#map` есть | Не ломать страницы без карты |
| `ymaps.ready()` | API загружается асинхронно |

### Координаты

- Брать из ТЗ, геокодера Яндекса или адреса в макете
- Agro-As (с. Садовое): `45.30861`, `34.64750`, zoom `14`
- При неточной метке — уточнить у заказчика, править `data-lat` / `data-lng`

---

## CSS

Контейнер — размеры из макета (Agro-As: `height: 428px`, `border-radius: 12px`, `overflow: hidden`):

```css
.contacts__map,
.contacts-page__map {
  border-radius: 12px;
  overflow: hidden;
  position: relative;
  width: 100%;
  height: 428px;
  background: #f5f5f5;
}

.contacts__map #map,
.contacts-page__map #map {
  width: 100%;
  height: 100%;
  min-height: 100%;
}
```

**Не** стилизовать `#map` как `<img>` (`object-fit: cover` для картинки не нужен).

Hover на блоке — только `box-shadow` на контейнере, без `scale` «картинки».

---

## WordPress / ACF

В Agro-As поля в группе `kontakty`:

| name | type | default |
|------|------|---------|
| `karta_shirota` | text | 45.30861 |
| `karta_dolgota` | text | 34.64750 |
| `karta_zoom` | number | 14 |

В шаблоне выводить в `data-lat`, `data-lng`, `data-zoom`; `data-pin` — `get_template_directory_uri() . '/img/icon-map-pin.svg'`.

Поле **image** `karta` / `map.png` — **не использовать**.

---

## Чек-лист перед сдачей

- [ ] Нет `map.png` в разметке блока контактов
- [ ] `#map` внутри контейнера с высотой из макета
- [ ] API 2.1 подключён перед `common.js`
- [ ] `scrollZoom` отключён
- [ ] Метка на координатах офиса, zoom читаемый
- [ ] На mobile карта не вылезает из контейнера (`overflow: hidden`)
- [ ] Проверка через локальный HTTP, не `file://`

---

## Эталоны для сверки

| Что | Путь |
|-----|------|
| Артэк (полный пример) | `reference/Артэк/index.html` (~стр. 528, 679–721) |
| CSS `#map` Артэк | `reference/Артэк/css/main.css` |
| Agro-As статика | `Agro-As/index.html`, `Agro-As/contacts.html` |
| Agro-As JS | `Agro-As/js/common.js` |
| Agro-As WP | `Agro-As/maintheme/template-parts/front/contacts.php` |

---

## Запрещено

- ❌ Статичный скрин карты из Figma вместо API
- ❌ Отдельный `<img class="*-map-pin">` поверх div-карты (метка только через `Placemark`)
- ❌ Забыть `scrollZoom.disable`
- ❌ Дублировать `id="map"` на одной странице
