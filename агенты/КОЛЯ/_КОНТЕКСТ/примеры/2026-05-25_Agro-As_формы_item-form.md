# Agro-As — формы через item-form (без лишних классов в form)

**Дата:** 2026-05-25  
**Эталон:** `reference/Артэк/index.html`  
**Канон:** `../формы-разметка.md`

---

## Задача

Убрать BEM-классы **внутри** `<form>`. Оставить Bootstrap + `.item-form` + общие `.checkbox` / `.btn-main`.

---

## Было (контакты) — ❌

```html
<form class="contacts-page__form">
  <div class="row contacts-page__fields">
    <div class="col-md-6 contacts-page__fields-col">
      <div class="contacts-page__field">
        <input …>
      </div>
    </div>
  </div>
  <div class="row contacts-page__form-footer">
    <div class="checkbox contacts-page__checkbox">…</div>
    <button class="btn-main contacts-page__submit">…</button>
  </div>
</form>
```

---

## Стало (контакты) — ✅

```html
<form action="#" method="post">
  <div class="row align-items-stretch">
    <div class="col-12 col-md-6">
      <div class="item-form">
        <input type="text" name="name" placeholder="Имя" required>
      </div>
      <div class="item-form">
        <input type="email" name="email" placeholder="Почта" required>
      </div>
    </div>
    <div class="col-12 col-md-6">
      <div class="item-form item-form--message">
        <textarea name="message" placeholder="Сообщение" required></textarea>
      </div>
    </div>
  </div>
  <div class="row align-items-start">
    <div class="col-12 col-lg">
      <div class="checkbox">…</div>
    </div>
    <div class="col-12 col-lg-auto">
      <button type="submit" class="btn-main btn-main--yellow">Отправить</button>
    </div>
  </div>
</form>
```

**CSS:** `.contacts-page__card--form .item-form`, `.item-form--message` — вместо `.contacts-page__field`.

---

## Было (wholesale) — ❌

```html
<form>
  <div class="wholesale-page-request__form-fields">
    <div class="row wholesale-page-request__fields-row">
      <div class="item-form">
        <label class="wholesale-page-request__label">Имя*</label>
        …
```

---

## Стало (wholesale) — ✅

```html
<form action="#" method="post">
  <div class="row">
    <div class="col-md-6">
      <div class="item-form">
        <label>Имя*</label>
        <input …>
      </div>
    </div>
  </div>
  …
  <div class="row align-items-center">
    <div class="col-lg"><div class="checkbox">…</div></div>
    <div class="col-lg-auto"><button class="btn-main">…</button></div>
  </div>
</form>
```

**CSS:** `.wholesale-page-request__form .item-form label`, `.wholesale-page-request__form form > .row:last-child .btn-main`.

---

## Файлы

| Файл |
|------|
| `Agro-As/contacts.html` |
| `Agro-As/wholesale.html` |
| `Agro-As/maintheme/template-parts/page/contacts.php` |
| `Agro-As/maintheme/template-parts/page/wholesale-request.php` |
| `Agro-As/css/main.css`, `Agro-As/css/media.css` |
| `Agro-As/maintheme/css/` — копия |

Модалки (`index.html`, `footer.php`) изначально соответствовали Артэку — не менялись.

---

## Урок

Секция задаёт контекст (`.contacts-page__card--form`), форма — только структура. Не плодить `__field`, `__submit`, `__form-footer` внутри `<form>`.
