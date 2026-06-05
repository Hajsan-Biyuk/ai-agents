## 2026-06-04 — Berega: ссылка SHEER в футере

**Запрос:** Логотип SHEER в блоке «Сделано в» — кликабельная ссылка на https://sheer.ru/ (новая вкладка).

**Результат:**
- `footer.php`, `_footer.php`, `include/site-footer.static.html` — `<img class="site-footer__sheer">` обёрнут в `<a href="https://sheer.ru/" target="_blank" rel="noopener noreferrer">`.
- Исправлен баг скрипта: в PHP-футерах `</a>` не должен разрывать `src="<?=TMPL_URL?>/img/sheer-logo.svg"`.
- Стили `.site-footer__sheer` без изменений.

**Оценка:** ✅
