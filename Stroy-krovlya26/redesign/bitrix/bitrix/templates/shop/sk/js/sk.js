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

	$doc.on('input change', '.sk-form .is-error', function () {
		$(this).removeClass('is-error');
	});
})(jQuery);
