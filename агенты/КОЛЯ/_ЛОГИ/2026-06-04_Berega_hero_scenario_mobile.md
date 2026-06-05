## 2026-06-04 — Berega: hero и финальная форма на ≤992px

**Запрос:** Hero `hero-screen__frame` / `frame-offset` — не прилипать к левому краю, stats не скрывать; у `scenario-screen--final` скрыть `scenario-media` на том же разрешении.

**Результат (`media.css`, max-width: 992px):**
- Hero: flex-колонка, stats видимы (wrap, 13px), frame на всю ширину контейнера, offset 24px.
- Финальная форма: `.scenario-screen.scenario-screen--final .scenario-media { display: none }` — перебивает `display:flex` из `main.css`.

**Оценка:** ✅
