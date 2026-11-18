# Bitrix: редизайн живого сайта под флагом предпросмотра (эталон — Laminarium, 18.11.2026)

- Всё новое — `/local/templates/.default/<prefix>/` + `components/<ns>/<comp>/<prefix>_*` (не затрёт обновление решения).
- Бридж не создаёт папки и не пишет бинарники → папки/шрифты/картинки заранее архивом через админку.
- Флаг: `flag.php` с `function_exists`, `?x_preview=1` → cookie 30 дней, `=0` удаляет; подключать через `is_file()`.
- Развилки в боевых файлах ставить с колонки 0: `<?if($flag):?>…<?else:?>\n` и `<?endif;?>` — PHP съедает перевод строки, вывод без флага байт-в-байт. Проверка: убрать `<\?.*?\?>\n?` из старого и нового файла — статический HTML должен совпасть.
- После каждой правки: curl без cookie 6 страниц, diff с эталоном с нормализацией (sessid, randString, captcha_code, sale_gift_product_N, recaptcha-XXXX).
- Шапка/подвал вне `#footer` Аспро → убрать `.wrapper{padding-bottom}` в CSS, грузящемся только под флагом.
- Кеш компонентов через бридж не сбросить → служебный параметр версии (`LAM_V`) в вызове компонента.
- Корзина в шапке: свой `sale.basket.basket.line` шаблон + перерисовка по `OnBasketChange` и `ajaxComplete` на `/ajax/item.php` (Аспро событие не шлёт).
- Избранное/сравнение в своих карточках: классы Аспро `wish_item text` / `compare_item text` + `data-item`, `data-iblock`.
- Если Browser pane блокирует подресурсы (ERR_BLOCKED_BY_CLIENT) — headless Chrome через CDP (node, свой профиль в /tmp).
