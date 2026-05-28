# Чекбокс согласия — разметка + Font Awesome 5 Pro

**Проект:** `Agro-As/maintheme/` — модалки, контакты, опт  
**Статус:** утверждено — **обязательный паттерн** для всех форм с согласием.

---

## HTML — только так

**`span` строго внутри `label`**, сразу после `input`:

```html
<div class="checkbox">
	<label>
		<input type="checkbox" required checked>
		<span>Я согласен с условиями обработки персональных данных</span>
	</label>
</div>
```

Ссылки в тексте согласия — **внутри `span`**:

```html
<span>
	Подтверждаю, что ознакомлен(а) с условиями
	<a href="/politika/">Политики конфиденциальности</a>
</span>
```

---

## CSS — селекторы только через `label span`

**Правильно:** `.checkbox label span`, `.checkbox label span::before`, `.checkbox label span::after`  
**Неправильно:** `.checkbox span` без `label` — ломает специфичность и расходится с разметкой.

Состояние checked — sibling-селектор (input и span рядом в label):

```css
.checkbox input:checked + span::before { … }
.checkbox input:checked + span::after { … }
```

---

## Полный блок (эталон Agro-As)

**Файл:** `Agro-As/maintheme/css/main.css` (блок `.checkbox`, `?ver=8.9+`)  
**Референсы:** `reference/Гармония (7)/css/main.css`, `reference/natyajka/maintheme/css/main.css`

```css
.checkbox label {
	display: flex;
	align-items: flex-start;
	cursor: pointer;
	font-size: 14px;
	line-height: 160%;
	color: #3d3d3d;
	position: relative;
}

.checkbox label input {
	position: absolute;
	z-index: -1;
	width: 1px !important;
	height: 1px !important;
	opacity: 0 !important;
	margin: 0 !important;
}

.checkbox label span {
	position: relative;
	padding-left: 32px;
	display: block;
	cursor: pointer;
}

.checkbox label span::before {
	content: '';
	position: absolute;
	left: 0;
	top: 2px;
	width: 20px;
	height: 20px;
	border: 1px solid #c9ec5f;
	border-radius: 4px;
	box-sizing: border-box;
	transition: 0.2s;
}

.checkbox label span::after {
	content: '\f00c';
	position: absolute;
	left: 0;
	top: 2px;
	width: 20px;
	height: 20px;
	display: flex;
	align-items: center;
	justify-content: center;
	font-family: 'Font Awesome 5 Pro';
	font-weight: 400;
	font-size: 11px;
	line-height: 1;
	font-style: normal;
	font-variant: normal;
	text-rendering: auto;
	-webkit-font-smoothing: antialiased;
	-moz-osx-font-smoothing: grayscale;
	color: #052d24;
	opacity: 0;
	transition: 0.2s;
	z-index: 1;
}

.checkbox input:checked + span::before {
	background: #c9ec5f;
	border-color: #c9ec5f;
}

.checkbox input:checked + span::after {
	opacity: 1;
}
```

### Логика псевдоэлементов

1. **`label input`** — скрыт, остаётся доступным для формы и `:checked`.
2. **`label span::before`** — квадрат рамки 20×20.
3. **`label span::after`** — галочка `\f00c`, **всегда** в DOM, `opacity: 0`.
4. **`:checked + span::before`** — заливка `#c9ec5f`.
5. **`:checked + span::after`** — только `opacity: 1`.
6. **`z-index: 1`** на `::after` — галочка поверх `::before`.

---

## Font Awesome — не путать Pro / Free

В `libs/fontawesome-free-5.12.0-web/css/all.min.css` подключён **Font Awesome 5 Pro**.

| ❌ Неправильно | ✅ Правильно |
|----------------|-------------|
| `font-family: 'Font Awesome 5 Free'` | `font-family: 'Font Awesome 5 Pro'` |
| `font-weight: 900` | `font-weight: 400` |
| Галочка только в `:checked::after` | Базовый `span::after` + opacity |

**Симптом ошибки:** рамка заливается, галочки нет.

---

## Локальные переопределения (Agro-As)

Контакты / опт — менять **только** через `.checkbox label span`:

```css
.contacts-page__card--form .checkbox label span {
	padding-left: 30px;
}

.contacts-page__card--form .checkbox label span a {
	text-decoration: underline;
}
```

Аналогично: `.wholesale-page-request__form form > .row:last-child .checkbox label span`

---

## Антипаттерны

```html
<!-- ❌ span вне label -->
<div class="checkbox">
	<input type="checkbox">
	<label><span>…</span></label>
</div>

<!-- ❌ BEM на checkbox -->
<div class="form__checkbox">…</div>
```

```css
/* ❌ без label в селекторе */
.checkbox span::before { … }

/* ❌ только checked::after без базового after */
.checkbox input:checked + span::after {
	content: '\f00c';
	/* без opacity: 0 в базовом состоянии */
}
```

---

## Перед сдачей

- [ ] HTML: `div.checkbox` → `label` → `input` + `span`
- [ ] CSS: псевдоэлементы на **`.checkbox label span`**
- [ ] FA: Pro + weight 400, сверить с `all.min.css`
- [ ] Галочка видна в модалке, на контактах, на опте
- [ ] Ссылки в тексте — внутри `span`, стили `label span a`

---

## Связанные файлы

| Файл | Назначение |
|------|------------|
| `Agro-As/maintheme/css/main.css` | блок `.checkbox` |
| `Agro-As/maintheme/footer.php` | модалки |
| `template-parts/page/contacts.php` | форма контактов |
| `template-parts/page/wholesale-request.php` | форма опта |
| `_КОНТЕКСТ/формы-разметка.md` | общая разметка форм |
| `_ЛОГИ/2026-05-20_Agro-As_чекбокс_fa.md` | FA Pro |
| `_ЛОГИ/2026-05-27_Agro-As_чекбокс_label_span.md` | селектор `label span` |

---

*Связано: `2026-05-19_модалка_заказать_звонок.md`, `2026-05-25_Agro-As_формы_item-form.md`*
