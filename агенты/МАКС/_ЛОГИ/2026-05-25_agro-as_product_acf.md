# Agro-As — ACF поля товара (product)

**Дата:** 2026-05-25  
**Эталон:** `Agro-As/card.html`

## Сделано

- `acf-json/group_agro_as_product.json`
- Обновлён `acf-import-all.json`
- Хелперы в `inc/helpers.php`

## Группа `group_agro_as_product`

**Location:** `post_type` = `product`

| Tab | name | type | Назначение |
|-----|------|------|------------|
| Основное | `galereya` | gallery | Галерея (1 фото / слайдер при >1) |
| | `tegi` | repeater → `tekst` | Теги `.product-page__tag` |
| | `status_nalichiya` | select | `v_nalichii` (default), `net_v_nalichii`, `pod_zakaz` |
| | `nazvanie_bloka_obema` | text | Подпись `.product-page__volume-label` |
| | `variacii` | repeater | `znachenie`, `edinica`, `cena`, `po_umolchaniyu` |
| Характеристики | `harakteristiki` | repeater | `nazvanie`, `znachenie` — preview 6 строк |
| Табы | `primenenie` | wysiwyg | Таб «Применение» |
| | `otzyvy_zaglushka` | textarea | Таб «Отзывы» |
| Рекомендуемые | `rekomenduemye_tovary` | relationship | Товары `product` |

**Описание:** `post_content` (редактор записи, не скрыт).

## Хелперы

| Функция | Назначение |
|---------|------------|
| `maintheme_product_status_label()` | Текст статуса |
| `maintheme_product_status_class()` | CSS-модификатор: `--in-stock`, `--out-of-stock`, `--preorder` |
| `maintheme_get_product_variations()` | Repeater + индекс активной вариации |
| `maintheme_get_recommended_products( $id, 6 )` | Relationship или 6 random из `product_cat` |

## Fallback рекомендаций

Если `rekomenduemye_tovary` пусто → `WP_Query` 6 случайных `product` из тех же терминов `product_cat`, без текущего товара.

## После деплоя

ACF → Sync или Import `acf-import-all.json`

## Следующий шаг

- `single-product.php` + template-parts
- JS: цена при смене вариации, expand specs, слайдер галереи
- CSS статусов `.product-page__status--*`
