# Бэкап laminarium.com до редизайна — 18.11.2026

Снято через Cursor Bitrix Bridge (`.env.laminarium`), файлы как на сервере.

| Путь на сайте | Хэш (sha256, начало) | Изменён на сервере |
|---|---|---|
| /index.php | 1c595e5dbe8f58f5 | 2025-08-21 |
| /bitrix/templates/aspro_mshop/header.php | 51da57b9bd9a8800 | 2026-10-20 |
| /bitrix/templates/aspro_mshop/footer.php | d16674425945d70e | 2026-10-14 |
| /bitrix/templates/aspro_mshop/header_new.php | cc51340b6e679e57 | 2026-06-15 |
| /bitrix/templates/aspro_mshop/css/header_new.css | acbf98d8b375bd51 | 2026-06-04 |
| /bitrix/templates/aspro_mshop/styles.css | 3b34835a462fd03b | 2023-11-30 |
| /bitrix/templates/aspro_mshop/template_styles.css | 68bea631431157ec | 2025-11-28 |

`live-home.html` — отрендеренная главная на 18.11.2026.

Откат: `file-patch <путь> orig/<файл> --expected-hash <текущий хэш>` или `history` → `rollback`.
