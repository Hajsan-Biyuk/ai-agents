$(document).ready(function () {

	var $menu = $('.header');
	var $body = $('body');
	var $wrapper = $('.wrapper');

	function setInnerLogoFixed(fixed) {
		var $logo = $menu.find('.logo');
		var $img = $logo.find('img');

		if (!$img.length) {
			return;
		}

		if (fixed) {
			if (!$logo.data('inner-logo-src')) {
				$logo.data('inner-logo-src', $img.attr('src'));
				$logo.data('inner-logo-class', $logo.attr('class') || '');
			}

			$img.attr('src', String($logo.data('inner-logo-src')).replace('logo-inner.svg', 'logo.svg'));
			$logo.removeClass('logo--inner');
			return;
		}

		if ($logo.data('inner-logo-src')) {
			$img.attr('src', $logo.data('inner-logo-src'));
			$logo.attr('class', $logo.data('inner-logo-class'));
		}
	}

	function setHeaderFixed(fixed) {
		var isInner = $body.hasClass('wrapper__default');

		if (fixed) {
			if (!$menu.hasClass('fixed')) {
				if (isInner) {
					setInnerLogoFixed(true);
				} else {
					$menu.removeClass('default');
				}
				$menu.addClass('fixed');
				if (isInner) {
					$wrapper.css('padding-top', $menu.outerHeight() + 'px');
				}
			}
			return;
		}

		if ($menu.hasClass('fixed')) {
			if (isInner) {
				setInnerLogoFixed(false);
			}
			$menu.removeClass('fixed');
			if (isInner) {
				$wrapper.css('padding-top', '');
			} else {
				$menu.addClass('default');
			}
		}
	}

	function updateHeaderOnScroll() {
		setHeaderFixed($(window).scrollTop() > 50);
	}

	$(window).on('scroll', updateHeaderOnScroll);
	updateHeaderOnScroll();

	$('.sandwich').click(function () {
		if ($('.menu-mobile').is(':hidden')) {
			$('.menu-mobile').slideDown(200);
			$('.sandwich').addClass('active');
			$('body').addClass('body--menu');
			$('.menu-overlay').fadeIn(200);
		} else {
			$('.menu-mobile').slideUp(200);
			$('.sandwich').removeClass('active');
			$('body').removeClass('body--menu');
			$('.menu-overlay').fadeOut(200);
		}
	});

	$('.menu-overlay').click(function () {
		$('.menu-mobile').slideUp(200);
		$('.sandwich').removeClass('active');
		$('body').removeClass('body--menu');
		$('.menu-overlay').fadeOut(200);
	});

	$('.menu-mobile a').click(function () {
		if ($(window).width() < 1025) {
			$('.menu-mobile').slideUp(200);
			$('.sandwich').removeClass('active');
			$('body').removeClass('body--menu');
			$('.menu-overlay').fadeOut(200);
		}
	});

	$('.item-question__head').click(function () {
		var $item = $(this).closest('.item-question');
		if ($item.hasClass('active')) {
			$item.removeClass('active');
			$item.find('.item-question__answer').slideUp(200);
		} else {
			$('.item-question').removeClass('active');
			$('.item-question__answer').slideUp(200);
			$item.addClass('active');
			$item.find('.item-question__answer').slideDown(200);
		}
	});

	var productsSliderResizeTimer;

	function getProductsSliderSlidesToShow(width) {
		if (width <= 575) {
			return 1;
		}

		if (width <= 948) {
			return 2;
		}

		if (width <= 1200) {
			return 3;
		}

		return 4;
	}

	function productsSliderNeedsNav(slideCount, width) {
		return slideCount > getProductsSliderSlidesToShow(width);
	}

	function initProductsCardsSlider(options) {
		var $layout = options.$layout;
		var $slider = options.$slider;
		var $prevArrow = options.$prevArrow;
		var $nextArrow = options.$nextArrow;
		var scrollableClass = options.scrollableClass || 'products-slider-layout--scrollable';

		if (!$layout.length || !$slider.length || !$prevArrow.length || !$nextArrow.length) {
			return;
		}

		var slideCount = $slider.children('.products__slide').length;

		if (!slideCount) {
			return;
		}

		var windowWidth = $(window).width();
		var needsNav = productsSliderNeedsNav(slideCount, windowWidth);

		$layout.toggleClass(scrollableClass, needsNav);

		if ($slider.hasClass('slick-initialized')) {
			$slider.slick('unslick');
		}

		if (!needsNav) {
			return;
		}

		$slider.slick({
			slidesToShow: 4,
			slidesToScroll: 1,
			infinite: slideCount > 4,
			arrows: true,
			dots: false,
			touchThreshold: 12,
			variableWidth: false,
			prevArrow: $prevArrow,
			nextArrow: $nextArrow,
			responsive: [
				{
					breakpoint: 1201,
					settings: {
						slidesToShow: 3,
						slidesToScroll: 1,
						infinite: slideCount > 3,
						arrows: slideCount > 3,
						dots: false
					}
				},
				{
					breakpoint: 949,
					settings: {
						slidesToShow: 2,
						slidesToScroll: 1,
						infinite: slideCount > 2,
						arrows: slideCount > 2,
						dots: slideCount > 2
					}
				},
				{
					breakpoint: 576,
					settings: {
						slidesToShow: 1,
						slidesToScroll: 1,
						infinite: slideCount > 1,
						arrows: slideCount > 1,
						dots: slideCount > 1
					}
				}
			]
		});

		$slider.off('breakpoint.productsSlider').on('breakpoint.productsSlider', function () {
			$layout.toggleClass(scrollableClass, productsSliderNeedsNav(slideCount, $(window).width()));
		});
	}

	function initFrontProductsSlider() {
		$('.products .products__slider-layout').each(function () {
			var $layout = $(this);

			initProductsCardsSlider({
				$layout: $layout,
				$slider: $layout.find('.products__slider').first(),
				$prevArrow: $layout.find('.products__slider-prev .slick-prev'),
				$nextArrow: $layout.find('.products__slider-next .slick-next')
			});
		});
	}

	function initProductGallerySlider() {
		var $gallery = $('.product-page__gallery--slider');

		if (!$gallery.length) {
			return;
		}

		$gallery.each(function () {
			var $el = $(this);

			if ($el.hasClass('slick-initialized')) {
				$el.slick('unslick');
			}

			if ($el.find('.product-page__gallery-slide').length > 1) {
				$el.slick({
					slidesToShow: 1,
					slidesToScroll: 1,
					infinite: true,
					arrows: false,
					dots: true,
					touchThreshold: 12
				});
			}
		});
	}

	function initProductPageRecommendSlider() {
		$('.product-page__recommend').each(function () {
			var $block = $(this);
			var $layout = $block.find('.product-page__recommend-layout').first();

			initProductsCardsSlider({
				$layout: $layout,
				$slider: $layout.find('.products__slider').first(),
				$prevArrow: $layout.find('.product-page__recommend-prev .slick-prev'),
				$nextArrow: $layout.find('.product-page__recommend-next .slick-next')
			});
		});
	}

	function initAllProductsSliders() {
		initFrontProductsSlider();
		initProductGallerySlider();
		initProductPageRecommendSlider();
	}

	initAllProductsSliders();

	$(window).on('resize', function () {
		clearTimeout(productsSliderResizeTimer);
		productsSliderResizeTimer = setTimeout(initAllProductsSliders, 150);
	});

	$('.fancybox').fancybox({
		autoFocus: false,
		backFocus: false,
		loop: true,
		afterShow: function (instance, current) {
			if (current && current.$content) {
				current.$content.find('.input-phone').mask('+7 (999) 999-99-99');
			}
		}
	});

	$('.input-phone').mask('+7 (999) 999-99-99');

	function getAnchorOffset() {
		var gap = 16;
		var $header = $('.header');
		if ($header.hasClass('fixed')) {
			return $header.outerHeight() + gap;
		}
		var $row = $header.find('.header__row');
		if ($row.length) {
			return $row.outerHeight() + 30 + gap;
		}
		return $header.outerHeight() + gap;
	}

	function switchProductTab(tabId) {
		if (!tabId) {
			return;
		}
		$('.product-page__tab').removeClass('active');
		$('.product-page__tab[data-tab="' + tabId + '"]').addClass('active');
		$('.product-page__panel').removeClass('active');
		$('.product-page__panel[data-tab="' + tabId + '"]').addClass('active');
	}

	$('.product-page__tab').on('click', function () {
		switchProductTab($(this).data('tab'));
	});

	$('.product-page__specs-all').on('click', function () {
		var $btn = $(this);
		var $preview = $btn.closest('.product-page__specs-preview');
		var showLabel = $btn.data('label-show') || 'Все характеристики';
		var hideLabel = $btn.data('label-hide') || 'Скрыть';
		var expanded = $preview.hasClass('product-page__specs-preview--expanded');

		if (expanded) {
			$preview.removeClass('product-page__specs-preview--expanded');
			$btn.text(showLabel);
			return;
		}

		$preview.addClass('product-page__specs-preview--expanded');
		$btn.text(hideLabel);
	});

	$('.product-page__volume-item').on('click', function () {
		var $item = $(this);
		var volume = $item.data('volume');
		var price = $item.data('price');
		var variationIndex = $item.attr('data-variation-index');
		var $orderBtn = $('.product-page__order.js-add-to-cart');

		$item.addClass('active').siblings().removeClass('active');

		if (volume) {
			$item.closest('.product-page__volume').find('.product-page__volume-label strong').text(volume);
		}

		if (price) {
			$item.closest('.product-page__hero').find('.product-page__price').text(price);
		}

		if (typeof variationIndex !== 'undefined' && variationIndex !== null && variationIndex !== '') {
			$orderBtn.attr('data-variation', variationIndex).data('variation', variationIndex);
		}
	});

	$('.product-page__buy .product-page__qty-btn--minus').on('click', function () {
		var $input = $(this).siblings('.product-page__qty-input');
		var value = parseInt($input.val(), 10) || 1;
		if (value > 1) {
			$input.val(value - 1);
		}
	});

	$('.product-page__buy .product-page__qty-btn--plus').on('click', function () {
		var $input = $(this).siblings('.product-page__qty-input');
		var value = parseInt($input.val(), 10) || 1;
		$input.val(value + 1);
	});

	$('.btn-main_filter').on('click', function (e) {
		e.preventDefault();
		$(this).toggleClass('active');
		$('.catalog-sidebar').slideToggle(200);
	});

	$('.catalog-sidebar__toggle').on('click', function (e) {
		e.preventDefault();

		var $item = $(this).closest('.catalog-sidebar__item');
		var $toggle = $(this);
		var isOpen = $item.hasClass('catalog-sidebar__item--open');

		if (isOpen) {
			$item.removeClass('catalog-sidebar__item--open');
			$item.find('.catalog-sidebar__body').slideUp(200);
			$toggle.attr('aria-expanded', 'false');
		} else {
			$('.catalog-sidebar__item').removeClass('catalog-sidebar__item--open');
			$('.catalog-sidebar__body').slideUp(200);
			$('.catalog-sidebar__toggle').attr('aria-expanded', 'false');
			$item.addClass('catalog-sidebar__item--open');
			$item.find('.catalog-sidebar__body').slideDown(200);
			$toggle.attr('aria-expanded', 'true');
		}
	});

	$('#catalog-sort').on('change', function () {
		var sort = $(this).val();
		var url = new URL(window.location.href);
		var path = url.pathname.replace(/\/page\/\d+\/?$/, '/');

		url.pathname = path;

		if (sort === 'default') {
			url.searchParams.delete('sort');
		} else {
			url.searchParams.set('sort', sort);
		}

		window.location.href = url.toString();
	});

	$('a[href^="#"]').on('click', function (event) {
		var href = $(this).attr('href');
		if (!href || href === '#' || href.length < 2) {
			return;
		}
		if ($(this).hasClass('fancybox') || $(this).closest('.tabs-page').length) {
			return;
		}
		var $target = $(href);
		if (!$target.length) {
			return;
		}
		event.preventDefault();
		$('html, body').animate({
			scrollTop: $target.offset().top - getAnchorOffset()
		}, 500);
	});

	function mainthemeCartButtonFeedback($btn) {
		if (!$btn.length) {
			return;
		}

		var defaultLabel = $btn.data('label') || $.trim($btn.text());

		if (!$btn.data('original-label')) {
			$btn.data('original-label', defaultLabel);
		}

		$btn.prop('disabled', true).text('Добавлено');

		setTimeout(function () {
			$btn.prop('disabled', false).text($btn.data('original-label'));
		}, 1000);
	}

	$(document).on('click', '.js-add-to-cart, .simple-basket-add', function (e) {
		e.preventDefault();

		var $btn = $(this);

		if ($btn.prop('disabled') || !window.SimpleBasket || !SimpleBasket.add) {
			return;
		}

		var productId = parseInt($btn.attr('data-product-id'), 10);
		var variation = parseInt($btn.attr('data-variation'), 10);
		var quantity = parseInt($btn.attr('data-quantity'), 10);

		if (isNaN(productId) || productId <= 0) {
			return;
		}

		if ($btn.hasClass('product-page__order')) {
			var $activeVariation = $btn.closest('.product-page__hero').find('.product-page__volume-item.active');

			if ($activeVariation.length) {
				variation = parseInt($activeVariation.attr('data-variation-index'), 10);
			}
		}

		if (isNaN(variation) || variation < 0) {
			variation = 0;
		}

		if (isNaN(quantity) || quantity < 1) {
			quantity = 1;
		}

		if ($btn.hasClass('product-page__order')) {
			var $qtyInput = $btn.closest('.product-page__buy').find('.product-page__qty-input');

			if ($qtyInput.length) {
				quantity = parseInt($qtyInput.val(), 10) || 1;
			}
		}

		SimpleBasket.add(productId, variation, quantity, function (basket) {
			SimpleBasket.updateCountView(basket);
			mainthemeCartButtonFeedback($btn);
			$('.btn-basket').addClass('animate-me');
			setTimeout(function () {
				$('.btn-basket').removeClass('animate-me');
			}, 1000);
		});
	});

	if ($('#map').length && typeof ymaps !== 'undefined') {
		ymaps.ready(initYandexMap);
	}

	function initYandexMap() {
		var $mapEl = $('#map');
		if (!$mapEl.length) {
			return;
		}

		var lat = parseFloat($mapEl.data('lat'), 10) || 45.30861;
		var lng = parseFloat($mapEl.data('lng'), 10) || 34.64750;
		var zoom = parseInt($mapEl.data('zoom'), 10) || 14;
		var pinHref = $mapEl.data('pin') || 'img/icon-map-pin.svg';
		var coords = [lat, lng];
		var iconSize = [24, 32];
		var iconOffset = [-12, -32];
		var balloonOffset = [0, -32];

		var myMap = new ymaps.Map('map', {
			center: coords,
			zoom: zoom
		}, {
			searchControlProvider: 'yandex#search'
		});

		var placemark = new ymaps.Placemark(coords, {}, {
			iconLayout: 'default#image',
			iconImageHref: pinHref,
			hideIconOnBalloonOpen: false,
			iconImageSize: iconSize,
			iconImageOffset: iconOffset,
			balloonOffset: balloonOffset
		});

		myMap.controls.remove('searchControl');
		myMap.behaviors.disable('scrollZoom');
		myMap.geoObjects.add(placemark);
	}

});
