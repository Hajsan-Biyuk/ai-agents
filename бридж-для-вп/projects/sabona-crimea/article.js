jQuery(function ($) {
	$('.article-content p').each(function () {
		var text = $(this).text().replace(/\u00a0/g, '').trim();
		if (!text && !$(this).find('img, iframe, table, video').length) {
			$(this).remove();
		}
	});

	$(document).on('click', '.article-toc__toggle', function () {
		var $button = $(this);
		var $list = $button.closest('.article-toc').find('.article-toc__list');
		var open = $button.attr('aria-expanded') !== 'false';
		$list.stop(true, true).slideToggle(200);
		$button.attr('aria-expanded', open ? 'false' : 'true');
		$button.attr('aria-label', open ? 'Развернуть содержание' : 'Свернуть содержание');
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
