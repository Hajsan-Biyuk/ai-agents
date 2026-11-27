# attikarus.ru (1С-Битрикс, шаблон /local/templates/atika)

Хостинг Beget, аккаунт общий с kub1000.ru («Кубы»). Доступы — `Attikarus/.env.sftp` (в git не идёт).
Бридж: `bitrix_bridge_cursor_compiled/.env.attikarus` (модуль поставлен 27.11.2026).

Скрипты запускать из корня Cursor-sheer:
- `python3 Attikarus/tools/fetch.py attikarus.ru /путь ...` — скачать в work/
- `python3 Attikarus/tools/put.py /путь ...` — залить из work/attikarus.ru

`orig/2026-11-27/` — файлы до правки «Цена договорная».
`work/kub1000.ru/` — эталон с «Кубов» (только чтение).

## Цена договорная (27.11.2026)
Перенесено с kub1000.ru. Переключатель: админка → Настройки → «Цена договорная»
(опция main/atika_negotiable_price, Y = вместо цен «Цена договорная»; суммы в каталоге/1С не трогаются).
Файлы: init.php (atikaPriceNegotiable, меню, хук заказа), bitrix/admin/atika_negotiable_price.php,
шаблоны catalog.item card, catalog.element (+fancybox_card, *_en.php → "Price on request"),
sale.basket.basket.line/header_links, search.title ajax, sale.basket.basket/bootstrap_v4 (копия системного + mutator).
