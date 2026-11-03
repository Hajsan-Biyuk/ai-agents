jQuery(function ($) {
	$('.article-content p').each(function () {
		var text = $(this).text().replace(/\u00a0/g, '').trim();
		if (!text && !$(this).find('img, iframe, table, video').length) {
			$(this).remove();
		}
	});

	$(document).on('click', '.article-toc__toggle', function () {
		var $button = $(this);
		var $extra = $button.closest('.article-toc').find('.article-toc__item_more');
		var willOpen = $button.attr('aria-expanded') !== 'true';
		$button.attr('aria-expanded', willOpen ? 'true' : 'false');
		$button.attr('aria-label', willOpen ? 'Свернуть содержание' : 'Показать все пункты');
		$extra.stop(true, true).slideToggle(200);
	});

	$(document).on('click', '.page-article a[href^="#"]', function (e) {
		var hash = $(this).attr('href');
		if (!hash || hash === '#') {
			return;
		}
		var $target = $(hash);
		if (!$target.length) {
			return;
		}
		e.preventDefault();
		var header = $('.header').outerHeight() || 0;
		var top = Math.max(0, $target.offset().top - header - 16);
		$('html, body').stop(true).animate({ scrollTop: top }, 700);
		if (window.history && history.pushState) {
			history.pushState(null, '', hash);
		}
	});

	$(document).on('click', '.article-faq__q', function () {
		var $button = $(this);
		var $item = $button.closest('.article-faq__item');
		var $answer = $item.find('.article-faq__answer');
		var willOpen = !$item.hasClass('is-open');
		$item.toggleClass('is-open', willOpen);
		$button.attr('aria-expanded', willOpen ? 'true' : 'false');
		if ($answer.length) {
			$answer.stop(true, true).slideToggle(200);
		}
	});
});
