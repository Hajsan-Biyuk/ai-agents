# Agro-As — Simple Basket (универсальный API)

**Плагин:** `Agro-As/plugins/simple-basket/` v0.4

---

## 1. Контракт строки корзины

Функция: `simple_basket_get_product_line( $post_id, $variation_index = 0, $quantity = 1 )`

Фильтр: **`simple_basket_product_line`** (null, post_id, variation_index, quantity)

Нормализованный массив:

| key | описание |
|-----|----------|
| `cart_key` | уникальный ключ (`123_2`) |
| `post_id` | ID записи |
| `variation` | int, индекс вариации |
| `title` | название для заказа |
| `price` | текст цены |
| `volume` | фасовка (опционально) |
| `category` | категория |
| `quantity` | qty для add |

Если фильтры не вернули строку → fallback **`simple_basket_build_simple_meta_line()`** (одно meta-поле цены из настроек плагина).

Добавление в корзину: **`simple_basket_add_product_to_cart( $post_id, $variation, $quantity )`**

---

## 2. Конфиг интеграций

Фильтр: **`simple_basket_integration_config`**

```php
array(
  'post_types'              => array(),
  'variation_mode'          => 'none', // none | acf_repeater
  'acf_repeater'            => 'variacii',
  'acf_value'               => 'znachenie',
  'acf_unit'                => 'edinica',
  'acf_price'               => 'cena',
  'taxonomy'                => '',
  'fallback_price_meta'     => '',
  'title_suffix_separator'  => ' — ',
  'include_post_type_label' => false,
)
```

### Agro-As (авто)

`integrations/agro-as.php` — если в теме есть `inc/cpt-product.php`:

- `post_types`: `product`
- `variation_mode`: `acf_repeater`
- mapping ACF `variacii`
- `taxonomy`: `product_cat`
- `fallback_price_meta`: `maintheme_min_price`

### Любой другой сайт

**Простой товар (одна цена):**

```php
add_filter( 'simple_basket_integration_config', function ( $config ) {
  $config['post_types'] = array( 'myproduct' );
  $config['variation_mode'] = 'none';
  $config['taxonomy'] = 'myproduct_cat';
  return $config;
} );
```

**ACF repeater вариации:**

```php
add_filter( 'simple_basket_integration_config', function ( $config ) {
  $config['post_types'] = array( 'product' );
  $config['variation_mode'] = 'acf_repeater';
  $config['acf_repeater'] = 'variacii';
  // ...
  return $config;
} );
```

**Полностью кастомная логика:**

```php
add_filter( 'simple_basket_product_line', function ( $line, $post_id, $variation, $qty ) {
  // вернуть массив строки или null для fallback
  return $line;
}, 10, 4 );
```

---

## 3. Минимальная вариация

`simple_basket_get_min_variation_index( $post_id )`

Фильтр: `simple_basket_min_variation_index`

---

## 4. UI (любая тема)

```html
<button type="button"
  class="simple-basket-add js-add-to-cart"
  data-product-id="123"
  data-variation="0"
  data-quantity="1"
  data-label="В корзину">
  В корзину
</button>
```

Шорткод: `[basket-buy-button id="123" variation="0" quantity="1"]`

Agro-As тема: обёртки `maintheme_get_basket_line_data()` → API плагина.

---

## 5. Файлы плагина

```
plugins/simple-basket/
├── inc/product-line.php       ← API
├── integrations/
│   ├── acf-repeater.php
│   ├── simple-meta.php
│   └── agro-as.php
├── ajax-api.php               ← только simple_basket_add_product_to_cart()
└── js/simple-basket.js
```
