# Agro-As — чекбокс: span внутри label — 2026-05-27

**Задача:** зафиксировать верстку и CSS чекбоксов согласия в формах.

**Сделано:**
- CSS: псевдоэлементы только на **`.checkbox label span`** (не `.checkbox span`)
- HTML без изменений: `label` → `input` + `span` (уже было в теме)
- Локальные стили контактов/опта: `.checkbox label span`, `.checkbox label span a`
- `main.css?ver=8.9`

**Паттерн обновлён:** `_КОНТЕКСТ/примеры/2026-05-20_чекбокс_fontawesome_pro.md`

**Урок:** span согласия — **внутри label**; в CSS всегда цепочка `.checkbox label span` для рамки и галочки; `:checked` — через `input + span`.
