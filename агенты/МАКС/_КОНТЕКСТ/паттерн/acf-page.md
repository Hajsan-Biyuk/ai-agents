# Паттерн: ACF на странице

**Когда:** page template, repeater, секции с `pokazat`  
**Источник:** Agro-As/maintheme  
**Обновлено:** 2026-06-05

## Page template

```php
<?php
/*
Template Name: Сертификаты
*/
get_header();
?>
<section class="certificates-page">
  <div class="container-fluid container-main">
    <?php get_template_part( 'template-parts/page/certificates' ); ?>
  </div>
</section>
<?php
get_footer();
```

## Repeater в template-part

```php
$items = get_field( 'sertifikaty' );
if ( ! empty( $items ) && is_array( $items ) ) {
  foreach ( $items as $item ) {
    $file = $item['fajl'] ?? null;
    if ( ! maintheme_is_filled( $file ) ) {
      continue;
    }
    $file_url = is_array( $file ) ? ( $file['url'] ?? '' ) : '';
    // …
  }
}
```

## Секция с чекбоксом «Показать»

```php
$sect = get_field( 'dostavka' );
if ( ! maintheme_section_show( $sect ) ) {
  return;
}
```

`maintheme_section_show()` — true если `$group['pokazat']` включён.

## Изображение ACF

```php
maintheme_img_url( $image );
maintheme_img_alt( $image, 'Fallback' );
```

## ACF JSON

- Файлы в `acf-json/`, импорт вручную: ACF → Tools → Import
- **Не** автoимпорт в `functions.php` без согласования

## Сертификаты — поля (пример)

| Поле | Тип |
|------|-----|
| `opisanie` | Text / WYSIWYG |
| `sertifikaty` | Repeater |
| → `izobrazhenie` | Image |
| → `zagolovok` | Text |
| → `fajl` | File |
