# 18.11.2026 — Laminarium: новая главная/шапка/подвал под флагом ?lam_preview=1

## Ход
1. Первая попытка остановлена: бридж не создаёт папки и не пишет бинарники (FileService::patch без mkdir, контент — UTF-8 строка).
2. Пользователь распаковал архив через админку: появились `/local/templates/.default/lam/{css,js,fonts,img}` (74 бинарника) и пустые папки шаблонов компонентов.
3. Залиты новые файлы в /local (23 шт.), проверены: PHP парсится на сервере (прямой GET = 200/0 байт), ассеты 200.
4. Боевые файлы по одному: header.php → footer.php → index.php. После каждого — 6 страниц без cookie, diff с эталоном = 0 (кроме captcha_code/рандомных id).
5. Проверка под флагом — headless Chrome (CDP), т.к. в Browser pane все подресурсы сайта ERR_BLOCKED_BY_CLIENT, а Claude in Chrome не подключён.

## Ключевые решения
- Флаг: `lam/flag.php` → `lam_preview()`; в header.php `$lamPreview`, в footer/index — вызов функции + is_file().
- Композит на сайте выключен (нет X-Bitrix-Composite); при флаге всё равно `Composite\Engine::setEnable(false)`.
- Подвал вне #footer → `lam-bx.css`: `body .wrapper{padding-bottom:0}`.
- `.lam-header__tools` = контейнер sale.basket.basket.line (иначе ломается display:contents на мобиле).
- catalog.top приводит DISPLAY_COMPARE к bool — проверять `=== true || === 'Y'`.
- Кеш компонентов не сбросить через бридж → параметр `LAM_V` в вызове tabs.mshop (менять при правке шаблонов).
- H1 = «Магазин ламината в Краснодаре» (about), заголовок баннера → `<p class="lam-hero__title">`.

## Открытые вопросы
- Реквизиты: макет — ИП Ипполитова (ИНН 344811221964), сайт и политика — ИП Крымов (ИНН 235504804935). Сейчас в lam/config.php — из макета.
- /ajax/actualBasket.php отдаёт 500 (TypeError) — было до нас.

Итог и операции — `Laminarium/redesign/bitrix/OPS.log`.
