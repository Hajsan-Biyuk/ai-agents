/* Детальная новость: заголовок в шапку статьи, автосодержание с плавной прокруткой, форма «Перезвоните мне». */
(function () {
	function init() {
		var article = document.querySelector('.lam-article');
		if (!article || article.dataset.lamReady) return;
		article.dataset.lamReady = '1';

		// H1 сайта (#pagetitle) переносим в шапку статьи — на странице остаётся один h1
		var h1 = document.getElementById('pagetitle');
		var slot = article.querySelector('[data-lam-title]');
		if (h1 && slot) slot.appendChild(h1);

		initToc(article);
		wrapTables(article);
		initCallback(article);
		initStickyAside(article);
	}

	// Sticky-сайдбар. Если он выше экрана: при прокрутке вниз доезжает до своего низа,
	// при прокрутке вверх — до своего верха (форма снова видна). top меняется на величину прокрутки.
	function initStickyAside(article) {
		var aside = article.querySelector('.lam-article__aside');
		if (!aside) return;
		var top = headerOffset();
		var lastY = window.pageYOffset;
		var ticking = false;
		function update() {
			ticking = false;
			var maxTop = headerOffset();
			var minTop = Math.min(maxTop, window.innerHeight - aside.offsetHeight - 20);
			var y = window.pageYOffset;
			top = Math.max(minTop, Math.min(maxTop, top - (y - lastY)));
			lastY = y;
			article.style.setProperty('--lam-aside-top', top + 'px');
		}
		update();
		window.addEventListener('scroll', function () {
			if (!ticking) { ticking = true; window.requestAnimationFrame(update); }
		}, { passive: true });
		window.addEventListener('resize', update);
		window.addEventListener('load', update);
		Array.prototype.forEach.call(aside.querySelectorAll('img'), function (img) {
			if (!img.complete) img.addEventListener('load', update);
		});
	}

	function headerOffset() {
		var fixed = document.getElementById('headerfixed');
		var h = fixed ? fixed.offsetHeight : 0;
		return (h || (fixed ? 70 : 0)) + 20;
	}

	function scrollToTarget(target) {
		var smooth = !(window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches);
		var top = target.getBoundingClientRect().top + window.pageYOffset - headerOffset();
		window.scrollTo({ top: Math.max(top, 0), behavior: smooth ? 'smooth' : 'auto' });
	}

	function initToc(article) {
		var body = article.querySelector('.lam-article__body');
		var toc = article.querySelector('.lam-toc');
		var list = toc && toc.querySelector('.lam-toc__list');
		if (!body || !list) return;

		var headings = body.querySelectorAll('h2');
		Array.prototype.forEach.call(headings, function (h, i) {
			var text = (h.textContent || '').replace(/\s+/g, ' ').trim();
			if (!text) return;
			if (!h.id) h.id = 'section-' + i; // те же id, что были у старого шаблона — внешние ссылки с якорями не ломаются
			var li = document.createElement('li');
			var a = document.createElement('a');
			a.href = '#' + h.id;
			a.textContent = text;
			li.appendChild(a);
			list.appendChild(li);
		});
		if (!list.children.length) return;
		toc.hidden = false;

		list.addEventListener('click', function (e) {
			var a = e.target.closest('a[href^="#"]');
			if (!a) return;
			var target = document.getElementById(decodeURIComponent(a.getAttribute('href').slice(1)));
			if (!target) return;
			e.preventDefault();
			scrollToTarget(target);
			if (history.replaceState) history.replaceState(null, '', '#' + target.id);
		});

		// Пришли по ссылке с якорем — доскроллить с учётом шапки
		if (location.hash) {
			var fromHash = document.getElementById(decodeURIComponent(location.hash.slice(1)));
			if (fromHash && body.contains(fromHash)) setTimeout(function () { scrollToTarget(fromHash); }, 300);
		}
	}

	function wrapTables(article) {
		Array.prototype.forEach.call(article.querySelectorAll('.lam-article__body table'), function (t) {
			if (t.parentNode.classList.contains('lam-table-wrap')) return;
			var wrap = document.createElement('div');
			wrap.className = 'lam-table-wrap';
			t.parentNode.insertBefore(wrap, t);
			wrap.appendChild(t);
		});
	}

	// Заявка уходит через штатную форму сайта CALLBACK (веб-форма, reCAPTCHA, письма):
	// открываем её попап и подставляем телефон и согласия.
	function initCallback(article) {
		var form = article.querySelector('.lam-cta__form');
		if (!form) return;
		var phone = form.querySelector('[name="phone"]');
		var agree = form.querySelector('[name="agree"]');
		var subscribe = form.querySelector('[name="subscribe"]');
		var error = form.querySelector('.lam-cta__error');
		var $ = window.jQuery;

		if ($ && $.fn.inputmask) $(phone).inputmask('+7 (999) 999-99-99');

		function showError(text) {
			error.textContent = text;
			error.hidden = !text;
		}

		form.addEventListener('submit', function (e) {
			e.preventDefault();
			var digits = phone.value.replace(/\D/g, '');
			phone.classList.toggle('is-error', digits.length !== 11);
			if (digits.length !== 11) return showError('Введите номер телефона полностью');
			if (!agree.checked) return showError('Нужно согласие на обработку персональных данных');
			showError('');

			var trigger = document.querySelector('.callback_btn');
			if (!trigger) return showError('Форма временно недоступна, позвоните нам: +7 (918) 022-79-99');
			if ($) $(trigger).first().trigger('click'); else trigger.click();

			var tries = 0;
			var timer = setInterval(function () {
				var popup = document.querySelector('form[name="CALLBACK"]');
				if (!popup && ++tries < 60) return;
				clearInterval(timer);
				if (!popup) return;
				var popupPhone = popup.querySelector('[data-sid="PHONE"]');
				if (popupPhone) {
					var value = '+7' + digits.slice(1);
					if ($) $(popupPhone).val(value).trigger('input').trigger('change'); else popupPhone.value = value;
				}
				var lic = popup.querySelector('[name="licenses_popup"]');
				if (lic) lic.checked = agree.checked;
				var sus = popup.querySelector('[name="licenses_sus"]');
				if (sus) sus.checked = subscribe.checked;
				var name = popup.querySelector('[data-sid="CLIENT_NAME"]');
				if (name) name.focus();
			}, 150);
		});
	}

	if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
	else init();
})();
