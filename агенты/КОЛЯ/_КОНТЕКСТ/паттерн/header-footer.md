# Паттерн: header и mobile menu

**Когда:** шапка сайта, burger ≤1024, fixed на scroll  
**Источник:** Agro-As/maintheme, zarina 3  
**Обновлено:** 2026-06-05

## Правило

- Внутренние страницы: `wrapper__default`, логотип `logo--inner`
- Меню: `body.body--menu` при открытом sandwich
- **Не** убирать `.billbord__spacer` на адаптиве — см. [адаптив.md](адаптив.md)

## HTML — sandwich + overlay

```html
<div class="sandwich">
  <div class="sw-topper"></div>
  <div class="sw-bottom"></div>
  <div class="sw-footer"></div>
</div>
<div class="menu-mobile">…</div>
<div class="menu-overlay"></div>
```

## JS — sandwich (common.js)

```javascript
$('.sandwich').click(function () {
  if ($('.menu-mobile').is(':hidden')) {
    $('.menu-mobile').slideDown(200);
    $('.sandwich').addClass('active');
    $('body').addClass('body--menu');
    $('.menu-overlay').fadeIn(200);
  } else {
    $('.menu-mobile').slideUp(200);
    $('.sandwich').removeClass('active');
    $('body').removeClass('body--menu');
    $('.menu-overlay').fadeOut(200);
  }
});

$('.menu-overlay').click(function () {
  $('.menu-mobile').slideUp(200);
  $('.sandwich').removeClass('active');
  $('body').removeClass('body--menu');
  $(this).fadeOut(200);
});
```

## JS — fixed header (внутренние страницы)

```javascript
function setHeaderFixed(fixed) {
  var isInner = $body.hasClass('wrapper__default');
  if (fixed) {
    $menu.addClass('fixed');
    if (isInner) {
      $wrapper.css('padding-top', $menu.outerHeight() + 'px');
    }
  } else {
    $menu.removeClass('fixed');
    if (isInner) {
      $wrapper.css('padding-top', '');
    }
  }
}
$(window).on('scroll', function () {
  setHeaderFixed($(window).scrollTop() > 50);
});
```

## CSS — sandwich

```css
.sandwich {
  display: none;
  width: 40px;
  height: 30px;
  position: relative;
  cursor: pointer;
}
.sandwich div {
  width: 100%;
  height: 3px;
  background: #fff;
  border-radius: 2px;
  position: absolute;
  left: 0;
  transition: transform 0.3s ease, opacity 0.3s ease;
}
body.body--menu {
  overflow: hidden;
}
```

## Footer — базовая структура

```html
<footer class="footer">
  <div class="container-fluid container-main">
    <div class="footer__top row">…</div>
    <div class="footer__bottom">…</div>
  </div>
</footer>
```
