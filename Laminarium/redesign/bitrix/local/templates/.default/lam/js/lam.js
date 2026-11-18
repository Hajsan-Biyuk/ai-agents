/* Ламинариум: шапка, вкладки, карусели. Без зависимостей, всё внутри IIFE. */
(function () {
	'use strict';

	function each(list, fn) {
		Array.prototype.forEach.call(list, fn);
	}

	/* ---------- Мобильное меню ---------- */
	each(document.querySelectorAll('.lam-header'), function (header) {
		var btn = header.querySelector('.lam-header__burger');
		if (!btn) return;

		function setOpen(open) {
			header.classList.toggle('is-open', open);
			btn.setAttribute('aria-expanded', open ? 'true' : 'false');
			btn.setAttribute('aria-label', open ? 'Закрыть меню' : 'Открыть меню');
		}

		btn.addEventListener('click', function () {
			setOpen(!header.classList.contains('is-open'));
		});
		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && header.classList.contains('is-open')) {
				setOpen(false);
				btn.focus();
			}
		});
	});

	/* ---------- Меню каталога (≥1200px; на мобильных кнопка — обычная ссылка на /catalog/) ---------- */
	each(document.querySelectorAll('[data-lam-catalog]'), function (btn) {
		var menu = document.getElementById(btn.getAttribute('aria-controls'));
		if (!menu) return;
		var desktop = window.matchMedia('(min-width: 1200px)');

		function setOpen(open) {
			menu.hidden = !open;
			btn.setAttribute('aria-expanded', open ? 'true' : 'false');
		}

		btn.addEventListener('click', function (e) {
			if (!desktop.matches) return;
			e.preventDefault();
			setOpen(menu.hidden);
		});
		document.addEventListener('click', function (e) {
			if (!menu.hidden && !menu.contains(e.target) && !btn.contains(e.target)) setOpen(false);
		});
		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && !menu.hidden) {
				setOpen(false);
				btn.focus();
			}
		});
		if (desktop.addEventListener) {
			desktop.addEventListener('change', function () { if (!desktop.matches) setOpen(false); });
		}
	});

	/* ---------- Карусели: CSS scroll-snap + стрелки ---------- */
	// В корне [data-lam-carousel] может быть несколько .lam-track (вкладки) — листаем видимый.
	function visibleTrack(root) {
		var tracks = root.querySelectorAll('.lam-track');
		for (var i = 0; i < tracks.length; i++) {
			if (tracks[i].offsetParent !== null) return tracks[i];
		}
		return null;
	}

	function stepOf(track) {
		var item = track.querySelector('.lam-track__item');
		if (!item) return track.clientWidth;
		var gap = parseFloat(getComputedStyle(track).columnGap) || 0;
		return item.getBoundingClientRect().width + gap;
	}

	function updateArrows(root) {
		if (!root) return;
		var track = visibleTrack(root);
		var prev = root.querySelector('[data-lam-prev]');
		var next = root.querySelector('[data-lam-next]');
		if (!track || !prev || !next) return;
		var max = track.scrollWidth - track.clientWidth;
		prev.disabled = track.scrollLeft <= 1;
		next.disabled = track.scrollLeft >= max - 1;
		root.classList.toggle('is-static', prev.disabled && next.disabled);
	}

	var carousels = document.querySelectorAll('[data-lam-carousel]');

	each(carousels, function (root) {
		root.addEventListener('click', function (e) {
			var btn = e.target.closest('[data-lam-prev], [data-lam-next]');
			if (!btn || !root.contains(btn)) return;
			var track = visibleTrack(root);
			if (!track) return;
			var dir = btn.hasAttribute('data-lam-prev') ? -1 : 1;
			track.scrollBy({ left: dir * stepOf(track), behavior: 'smooth' });
		});
		each(root.querySelectorAll('.lam-track'), function (track) {
			track.addEventListener('scroll', function () { updateArrows(root); }, { passive: true });
		});
		updateArrows(root);
	});

	var resizeTimer;
	window.addEventListener('resize', function () {
		clearTimeout(resizeTimer);
		resizeTimer = setTimeout(function () { each(carousels, updateArrows); }, 150);
	});

	/* ---------- Вкладки «Акция / Хит продаж / Новинка / Советуем» ---------- */
	each(document.querySelectorAll('.lam-tabs'), function (list) {
		var tabs = Array.prototype.slice.call(list.querySelectorAll('[role="tab"]'));

		function select(tab) {
			tabs.forEach(function (t) {
				var on = t === tab;
				t.classList.toggle('is-active', on);
				t.setAttribute('aria-selected', on ? 'true' : 'false');
				t.tabIndex = on ? 0 : -1;
				var panel = document.getElementById(t.getAttribute('aria-controls'));
				if (panel) panel.hidden = !on;
			});
			updateArrows(list.closest('[data-lam-carousel]'));
		}

		tabs.forEach(function (tab, i) {
			tab.addEventListener('click', function () { select(tab); });
			tab.addEventListener('keydown', function (e) {
				var d = e.key === 'ArrowRight' ? 1 : e.key === 'ArrowLeft' ? -1 : 0;
				if (!d) return;
				e.preventDefault();
				var nextTab = tabs[(i + d + tabs.length) % tabs.length];
				select(nextTab);
				nextTab.focus();
			});
		});
	});
})();
