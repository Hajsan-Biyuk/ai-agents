# 2026-05-21 — Berega: плавный скролл якорей `.zk-anchors__inner`

## Контекст

Страница объекта (`Berega/public_html/objects/index.html`): липкая шапка `.hero-header` + липкая поднавигация `.zk-anchors`. Якорные ссылки только внутри `.zk-anchors__inner`. Параллельно работает `IntersectionObserver` для класса `.is-active`.

## Правило

- Плавный скролл — **jQuery `.animate()`**, не нативный jump.
- Селектор — **точечный**: `.zk-anchors__inner a[href^="#"]`, не все `a[href^="#"]` на странице (не ломать fancybox, ссылку `#location` в hero и т.д.).
- Offset = **шапка + поднавигация + gap 16px**.
- На Berega шапка — `.hero-header`, не `.header`. Fallback: `.hero-header` → `.header` → `76`.
- Скорость анимации — **600 ms** (по ТЗ заказчика; базовый эталон Sheer — 500 ms).
- По клику сразу ставить `.is-active` на пункт меню; IO при скролле дополняет поведение.

## Скрипт (эталон для Berega)

Файл: `Berega/public_html/local/templates/berega/js/common.js` → `initObjectPage()` → блок `zkAnchorSticky`.

```js
function getZkAnchorOffset() {
  var headerH = $('.hero-header').outerHeight() || $('.header').outerHeight() || 76;
  var navH = $('.zk-anchors').outerHeight() || 52;
  return headerH + navH + 16;
}

$nav.on('click', '.zk-anchors__inner a[href^="#"]', function (e) {
  var href = $(this).attr('href') || '';
  var id = href.slice(1);
  if (!id || href === '#') return;

  var $target = $(href);
  if (!$target.length) return;

  e.preventDefault();
  $links.removeClass('is-active');
  $(this).addClass('is-active');

  $('html, body').animate({
    scrollTop: $target.offset().top - getZkAnchorOffset()
  }, 600);
});
```

## Отличия от базового паттерна

| Базовый (`getAnchorOffset`, zarina / туроператор) | Berega object |
|--------------------------------------------------|---------------|
| `.header` / `.header__wrapper` | `.hero-header` + `.zk-anchors` |
| `$('a[href^="#"]')` глобально | только `.zk-anchors__inner a[href^="#"]` |
| 500 ms | 600 ms (если не указано иное в ТЗ) |
| — | + `.is-active` на клик вместе со скроллом |

## Связанные файлы

- `агенты/КОЛЯ/_КОНТЕКСТ/примеры/2026-05-19_плавный_скролл_якоря.md` — общий паттерн
- `Berega/scripts/build_object_page.py` — дублирует JS при пересборке из `object.html`

## Результат

Оценка: ✅ (внедрено по запросу на `/objects/index.html`)
