# Cursor-sheer — агенты и правила студии Sheer

Отвечай по-русски. Описание репозитория — README.md (читать только если нужно, не импортируется).

## Агенты (`.claude/agents/`)

Когда пользователь пишет `@имя`, передай задачу соответствующему сабагенту (Agent tool, `subagent_type` = имя справа):

| Вызов | Сабагент | Зона |
|---|---|---|
| `@коля` | `kolya` | вёрстка HTML/CSS/JS/jQuery по Figma, Bootstrap |
| `@даша` | `dasha` | QA вёрстки pixel-perfect по Figma, fix brief для Коли |
| `@макс` | `maks` | интеграция на CMS: WordPress/WooCommerce, 1C-Bitrix, MODX |
| `@астра` / `@astro` | `astra` | сайты на Astro целиком |
| `@pars` | `pars` | импорт каталога Excel/JSON, парсинг поставщиков |
| `@maxbot` / `@бот-max` | `maxbot` | чат-боты MAX + Bitrix24 CRM |

Пайплайн вёрстки: `kolya` → `dasha` → при FAIL снова `kolya` с fix brief → `dasha` ретест до PASS.
Глобальные сабагенты (`~/.claude/agents/`): `verstaller`, `coder`, `tester`.

Контекст, паттерны и логи агентов — в `агенты/<ИМЯ>/`. Новый устойчивый паттерн после задачи — сразу в `агенты/*/_КОНТЕКСТ/паттерн/`.

## Правила (`.claude/rules/`)

Всегда: любая работа с кодом — линтер и форматтер по изменённым файлам (`linter-formatter.md`), ACF только через «Группы полей», FTP+SFTP при проверке хостинга, GitVerse — правки в ветку `proger`, в `main` только по команде, заявки в Telegram всегда с **зашифрованным** источником трафика — «Номер заявки: ДДЧЧММ-XY», без спроса, во всех новых интеграциях (`tg-zayavki-istochnik.md`).
По путям: сдача сайта (HTML/PHP/Astro), ТехноАльянс SEO/CMS, WordPress Bridge.

## Скилы

Проект (`.claude/skills/`): `cpt-wordpress`, `product-single-wp`, `bitrix-slon-forms`, `pars-product-import`, `max-bot-b24`, `stitch-loop`.
Глобальные (`~/.claude/skills/`): `skill-verstka`, `wp-seo-bridge`.
