/* СтройКровля — редизайн. jQuery уже подключён на сайте; fancybox — для «Заказать звонок». */
(function ($) {
	'use strict';

	var $doc = $(document);

	/* ---------- Табы ---------- */
	$doc.on('click', '.sk-js-tabs .sk-tabs__btn', function () {
		var $btn = $(this);
		var $wrap = $btn.closest('.sk-js-tabs');
		var $panel = $('#' + $btn.attr('aria-controls'));
		if (!$panel.length || $btn.hasClass('is-active')) return;

		$wrap.find('.sk-tabs__btn').removeClass('is-active').attr('aria-selected', 'false');
		$wrap.find('.sk-tabs__panel').removeClass('is-active').attr('hidden', true);
		$btn.addClass('is-active').attr('aria-selected', 'true');
		$panel.addClass('is-active').removeAttr('hidden');
	});

	/* ---------- Меню каталога / мобильное меню ---------- */
	var $menu = $('#sk-menu');
	var $toggle = $('.sk-js-menu-toggle');
	var mobileMq = window.matchMedia('(max-width: 767px)');

	function setMenu(open) {
		$menu.toggleClass('is-open', open).attr('aria-hidden', open ? 'false' : 'true');
		$toggle.attr('aria-expanded', open ? 'true' : 'false');
		$('body').toggleClass('sk-menu-open', open && mobileMq.matches);
	}

	$doc.on('click', '.sk-js-menu-toggle', function (e) {
		e.stopPropagation();
		setMenu(!$menu.hasClass('is-open'));
	});

	$doc.on('click', '.sk-js-menu-close', function () {
		setMenu(false);
	});

	$doc.on('click', function (e) {
		if ($menu.hasClass('is-open') && !$(e.target).closest('#sk-menu').length) setMenu(false);
	});

	$doc.on('keydown', function (e) {
		if (e.key === 'Escape' && $menu.hasClass('is-open')) setMenu(false);
	});

	// Ресайз через брейкпоинт — закрыть, чтобы не застрял overflow:hidden
	if (mobileMq.addEventListener) {
		mobileMq.addEventListener('change', function () { setMenu(false); });
	}

	/* ---------- Попапы сайта (fancybox по data-fancybox) — закрыть меню ---------- */
	$doc.on('click', '#sk-menu [data-fancybox]', function () {
		setMenu(false);
	});

	/* ---------- Плавный скролл к якорю ---------- */
	$doc.on('click', '.sk-js-scroll', function (e) {
		var $target = $($(this).attr('href'));
		if (!$target.length) return;
		e.preventDefault();
		$('html, body').animate({ scrollTop: $target.offset().top - 20 }, 500);
	});

	/* ---------- Маска телефона +7 (999) 999-99-99 ---------- */
	function formatPhone(value) {
		var d = value.replace(/\D/g, '');
		if (d.charAt(0) === '7' || d.charAt(0) === '8') d = d.slice(1);
		// ввели номер с 8/7 после префикса «+7 (»: лишняя первая цифра
		if (d.length > 10 && (d.charAt(0) === '8' || d.charAt(0) === '7')) d = d.slice(1);
		d = d.slice(0, 10);
		if (!d) return value.replace(/[^+\d (]/g, ''); // даём стереть префикс
		var out = '+7 (' + d.slice(0, 3);
		if (d.length > 3) out += ') ' + d.slice(3, 6);
		if (d.length > 6) out += '-' + d.slice(6, 8);
		if (d.length > 8) out += '-' + d.slice(8, 10);
		return out;
	}

	$doc.on('focus', '.sk-js-phone', function () {
		if (!this.value) this.value = '+7 (';
	});

	$doc.on('input', '.sk-js-phone', function () {
		this.value = formatPhone(this.value);
	});

	$doc.on('blur', '.sk-js-phone', function () {
		if (this.value.replace(/\D/g, '').length <= 1) this.value = '';
	});

	/* ---------- Проверка формы перед отправкой ---------- */
	$doc.on('submit', '.sk-form', function (e) {
		var ok = true;
		$(this).find('.sk-js-phone').each(function () {
			var bad = this.value.replace(/\D/g, '').length !== 11;
			$(this).toggleClass('is-error', bad);
			if (bad) ok = false;
		});
		$(this).find('.sk-check__input[required]').each(function () {
			$(this).toggleClass('is-error', !this.checked);
			if (!this.checked) ok = false;
		});
		if (!ok) { e.preventDefault(); return; }
		// Формы Битрикса: токен reCAPTCHA и отправка — SubmittingForm из шаблона (jquery.validateform.js)
		if ($(this).hasClass('sk-js-bxform') && window.SubmittingForm) {
			e.preventDefault();
			var goal = $(this).data('goal');
			if (goal && window.ym) ym(38770565, 'reachGoal', goal);
			window.SubmittingForm(this);
		}
	});

	/* ---------- Карточка товара: покрытие → толщина → цвет, цена ---------- */
	// Чистая функция подбора предложения (тест: Stroy-krovlya26/redesign/tests/variants-test.js)
	function skPick(offers, sel) {
		var s = { coat: sel.coat || '', thick: sel.thick || '', color: sel.color || '' };
		if (!offers.length) return { sel: s, offer: null, thicks: [] };
		var has = function (k, v) { return offers.some(function (o) { return o[k] === v; }); };
		if (!has('coat', s.coat)) s.coat = offers[0].coat;
		var thicks = [];
		offers.forEach(function (o) { if (o.coat === s.coat && thicks.indexOf(o.thick) < 0) thicks.push(o.thick); });
		if (thicks.indexOf(s.thick) < 0) s.thick = thicks[0];
		var offer = null;
		offers.forEach(function (o) { if (!offer && o.coat === s.coat && o.thick === s.thick) offer = o; });
		if (!offer || offer.colors.indexOf(s.color) < 0) s.color = '';
		return { sel: s, offer: offer, thicks: thicks };
	}
	window.skPick = skPick;

	function money(v) {
		return Math.round(v).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
	}

	$('.sk-js-variants').each(function () {
		var $root = $(this);
		var d = $root.data('sk') || {};
		var offers = d.offers || [];
		var colors = d.colors || {};
		var sel = {};

		function render() {
			var r = skPick(offers, sel);
			sel = r.sel;
			var o = r.offer;
			$root.find('[data-group="coat"] .sk-variants__opt').each(function () {
				var on = this.getAttribute('data-value') === sel.coat;
				$(this).toggleClass('is-active', on).attr('aria-pressed', on);
			});
			$root.find('[data-group="thick"] .sk-variants__opt').each(function () {
				var v = this.getAttribute('data-value');
				$(this).toggleClass('is-active', v === sel.thick).attr('aria-pressed', v === sel.thick).prop('disabled', r.thicks.indexOf(v) < 0);
			});
			$root.find('.sk-variants__color').each(function () {
				var v = this.getAttribute('data-value');
				$(this).prop('hidden', !o || o.colors.indexOf(v) < 0).toggleClass('is-active', v === sel.color).attr('aria-pressed', v === sel.color);
			});
			$root.find('[data-group="coat"] .sk-js-v-value').text(sel.coat || '');
			$root.find('[data-group="thick"] .sk-js-v-value').text(sel.thick || '');
			$root.find('[data-group="color"] .sk-js-v-value').text(sel.color && colors[sel.color] ? colors[sel.color].name : 'выберите');
			$root.find('[data-group="color"]').prop('hidden', !o || !o.colors.length);

			var price = o ? o.price : d.base;
			var old = o ? o.old : d.old;
			var unit = ' ₽' + (d.measure ? '/' + d.measure : '');
			$root.find('.sk-js-v-price').text(price ? money(price) + unit : 'Цена по запросу');
			$root.find('.sk-js-v-old').text(old && price && old > price ? money(old) + unit : '');
			$root.find('.sk-js-v-note').prop('hidden', !offers.length || !!o);

			var parts = [d.name];
			if (sel.coat) parts.push('Покрытие: ' + sel.coat);
			if (sel.thick) parts.push('Толщина: ' + sel.thick);
			if (sel.color && colors[sel.color]) parts.push('Цвет: ' + colors[sel.color].name);
			if (price) parts.push('Цена: ' + money(price) + unit);
			$root.data('order-text', parts.join('; '));
		}

		$root.on('click', '.sk-variants__opt, .sk-variants__color', function () {
			sel[$(this).closest('.sk-variants__group').data('group')] = this.getAttribute('data-value');
			render();
		});
		$root.on('click', '.sk-js-v-order', function () {
			var t = $root.data('order-text');
			$('.sk-js-order-product').val(t);
			$('.sk-js-order-product-text').text(t);
		});
		render();
	});

	$doc.on('input change', '.sk-form .is-error', function () {
		$(this).removeClass('is-error');
	});
})(jQuery);
