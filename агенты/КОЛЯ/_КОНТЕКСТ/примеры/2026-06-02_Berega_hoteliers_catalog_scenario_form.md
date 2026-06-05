# 2026-06-02 — Berega: scenario-screen на каталоге отельеров

## Задача

Страница `Berega/berega.ru/public_html/hoteliers/catalog.html` — блок заявки как на главной (отступы, чекбокс, поля).

## Эталон

**Файл:** `berega.ru/public_html/include/forms/form_2.php` (Bitrix-главная).

Не копировать черновик `/new-main/index.php` — там `.scenario-consent` без кастомной галочки.

## Разметка (фрагмент)

```html
<section class="scenario-screen">
  <div class="container">
    <div class="scenario-screen__inner">
      <div class="scenario-screen__grid" aria-hidden="true"></div>
      <div class="row">
        <div class="col-12">
          <div class="scenario-screen__header">...</div>
        </div>
        <div class="col-12">
          <div id="scenario-form-wrap">
            <form class="scenario-form" action="#" method="post">
              <div class="scenario-form__group">...</div>
              <input class="btn-main" type="submit" value="...">
              <div class="checkbox">
                <label>
                  <input type="checkbox" required checked>
                  <span>Выражаю <a href="/soglasie/">согласие</a> ... <a href="/policy/">Политикой...</a></span>
                </label>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="scenario-pdf">...</div>
      <div class="scenario-media">...</div>
    </div>
  </div>
</section>
```

На статике пути к img: `../local/templates/berega/img/`. Текст согласия = константа `SOGLASIE` из `local/php_interface/init.php`.

## CSS

- Стили чекбокса на тёмном фоне: `.scenario-screen .checkbox` (`main.css`).
- Отступы колонок: `.scenario-screen__inner > .row > [class*="col-"]` — `padding-left/right: 12px`.
- Не добавлять `.ok-catalog-scenario` — дублирует и ломает отступы.

## Результат

Оценка: ✅ Отлично
