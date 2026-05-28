---
name: product-single-wp
description: Implements Agro-As single product page from card.html with ACF, Fancybox gallery lightbox, volume/price variations, specs toggle, and recommended products. Use for single-product.php, product card WP integration, product-page gallery modal, or @макс product page tasks.
---

# Product Single Page (Agro-As)

## Обязательный канон

Перед натяжкой карточки товара **прочитать и следовать**:

`агенты/МАКС/_КОНТЕКСТ/product-single-wp-pattern.md`

**Эталон:** `Agro-As/maintheme/single-product.php` + `template-parts/product/`  
**Статика:** `Agro-As/card.html`

## Галерея + Fancybox (ключевой паттерн)

```html
<a href="{full_url}" class="product-page__gallery-link fancybox"
   data-fancybox="product-gallery-{post_id}" data-caption="{alt}">
  <img src="{full_url}" alt="{alt}">
</a>
```

- 1 фото — без slick
- 2+ — `.product-page__gallery--slider` + slick; lightbox листает всю группу `data-fancybox`
- Fancybox уже в теме (`libs/fancybox-master/`), `loop: true` в `common.js`

Подробности — в каноническом документе.
