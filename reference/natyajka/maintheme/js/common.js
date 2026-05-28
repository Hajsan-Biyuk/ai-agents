$(document).ready(function() {

		/*redesign*/

			$('.show-password').on('click', function() {
    let input = $(this).closest('.item-form').find('input');
    let icon = $(this).find('i');

    if (input.attr('type') === 'password') {
        input.attr('type', 'text');
        icon.removeClass('fa-eye').addClass('fa-eye-slash');
        $(this).addClass('is-active');
    } else {
        input.attr('type', 'password');
        icon.removeClass('fa-eye-slash').addClass('fa-eye');
        $(this).removeClass('is-active');
    }
});

	const $zeleniy = $('.header__zeleniy');
	 $zeleniy.css('opacity', '1');
    $('.menu li:first-child').after($('<li>').append($zeleniy));

		jQuery('.quantity').each(function() {
		var spinner = jQuery(this),
		input = spinner.find('input[type="number"]'),
		btnUp = spinner.find('.quantity-up'),
		btnDown = spinner.find('.quantity-down'),
		min = input.attr('min'),
		max = input.attr('max');

		btnUp.click(function() {
			var oldValue = parseFloat(input.val());
			if (oldValue >= max) {
				var newVal = oldValue;
			} else {
				var newVal = oldValue + 1;
			}
			spinner.find("input").val(newVal);
			spinner.find("input").trigger("change");
		});

		btnDown.click(function() {
			var oldValue = parseFloat(input.val());
			if (oldValue <= min) {
				var newVal = oldValue;
			} else {
				var newVal = oldValue - 1;
			}
			spinner.find("input").val(newVal);
			spinner.find("input").trigger("change");
		});
	});

	$(".btn-card--favorites").click(function(e) {
		e.preventDefault();
		$(this).toggleClass("active");
	});

	$(".property__value").click(function() {
		$(this).siblings().removeClass("active");
		$(this).addClass("active");
	});
	
	{
		if ($(window).width() < 992) { 
			$(".title-catalog").click(function(e) {
				e.preventDefault();
				$(".nav-catalog").slideToggle(200);
			});
		}
	}

	$('.slider-composition').slick({
		arrows: true,
		dots: false,
		infinite: true,
		touchThreshold: 1000,
		slidesToShow: 3,
		slidesToScroll: 1,
		prevArrow: '<div class="slick-prev slick-arrow"><i class="far fa-long-arrow-left"></i><div/>',
		nextArrow: '<div class="slick-next slick-arrow"><i class="far fa-long-arrow-right"></i><div/>',
		responsive: [
		{
			breakpoint: 992,
			settings: {
				slidesToShow: 2,
			}
		},
		{
			breakpoint: 768,
			settings: {
				slidesToShow: 1,
				arrows: false,
				dots: true,
			}
		}
		]
	});

	
    $.getJSON("https://api.ipify.org/?format=json", function(e) {
    console.log(e.ip);
    $('form').find('[name=your-ip]').val(e.ip).attr('readonly', true);
    });

	  if (localStorage.getItem("show_cookies") === null) {   
	     console.log('lllll');
        $('.cookies-modal').show();     
        $('.cookies-modal .cookie-policy-button, .cookies-modal .yes-cookie').click(function(){     
            $('.cookies-modal').hide();
            localStorage.setItem('show_cookies', true); 
        }); 
        $('.cookies-modal .no-cookie').click(function(){            
            window.location.href = 'https://ya.ru/';        
        });
    }


//прилипающие меню
var $menu = $(".header");
$(window).scroll(function(){
	if ( $(this).scrollTop() > 0 && $menu.hasClass("default") ){
		$menu.removeClass("default").addClass("fixed");
	} else if($(this).scrollTop() <= 0 && $menu.hasClass("fixed")) {
		$menu.removeClass("fixed").addClass("default");
	}
	
});

if ( $(window).scrollTop() > 0 && $menu.hasClass("default") ){
	$menu.removeClass("default").addClass("fixed");
} else if($(window).scrollTop() <= 0 && $menu.hasClass("fixed")) {
	$menu.removeClass("fixed").addClass("default");
}

	//плавный скролл
	$(".navigat li a").mPageScroll2id();


	//кнопка sandwich
	$(".sandwich").click(function() {
		$(this).toggleClass("active");
		if ($(".menu-mobile").is(":hidden")) {
			$(".menu-mobile").slideDown(200);
		} else {
			$(".menu-mobile").slideUp(200);
		}
	});

	{
		if ($(window).width() < 992) { 
			$(".footer__title").click(function() {
				$(this).toggleClass("active");
				$(this).next(".footer__content").slideToggle(200);
			});
		}
	}


	/*input file*/
	$("input[type='file']").change(function(){
		var filename_text = $(this).parent().siblings(".name-upload");
		var filename = $(this).val().replace(/.*\\/, "");
		filename_text.html(filename);
	});

	$(".item-dropdown__head").click(function() {
		$(this).parent().toggleClass("active");
		$(this).siblings().slideToggle(200);
		$(this).parent().siblings(".item-dropdown").removeClass("active");
		$(this).parent().siblings(".item-dropdown").find(".item-dropdown__content").slideUp(200);
	});

	//слайдер

	$('.slider-portfolio').slick({
		arrows: false,
		dots: true,
		infinite: true,
		touchThreshold: 1000,
		autoplay: true,
		autoplaySpeed: 3000,
		slidesToShow: 2,
		slidesToScroll: 1,
		prevArrow: '<div class="slick-prev slick-arrow"><i class="fas fa-chevron-left"></i><div/>',
		nextArrow: '<div class="slick-next slick-arrow"><i class="fas fa-chevron-right"></i><div/>',
		responsive: [
		{
			breakpoint: 992,
			settings: {
				slidesToShow: 1,
				autoplay: false,
			}
		}
		]
	});

	$('.slider-for').slick({
		arrows: false,
		dots: false,
		infinite: true,
		slidesToShow: 1,
		slidesToScroll: 1,
		swipe: false,
		asNavFor: '.slider-nav',
		touchThreshold: 1000,
		prevArrow: '<div class="slick-prev slick-arrow"><i class="fas fa-chevron-left"></i><div/>',
		nextArrow: '<div class="slick-next slick-arrow"><i class="fas fa-chevron-right"></i><div/>',
		responsive: [
		{
			breakpoint: 992,
			settings: {
				swipe: true
			}
		}
		]
	});

	$('.slider-nav').slick({
		arrows: true,
		dots: false,
		infinite: true,
		slidesToShow: 3,
		slidesToScroll: 1,
		asNavFor: '.slider-for',
		touchThreshold: 1000,
		focusOnSelect: true,
		prevArrow: '<div class="slick-prev slick-arrow"><i class="far fa-long-arrow-left"></i><div/>',
		nextArrow: '<div class="slick-next slick-arrow"><i class="far fa-long-arrow-right"></i><div/>',
		responsive: [
		{
			breakpoint: 992,
			settings: {
				

			}
		}
		]
	});

	function initTabDescripClamp() {
		$('.js-tab-descrip').each(function() {
			var $wrap = $(this);
			if ($wrap.data('descrip-ready')) {
				return;
			}
			var $inner = $wrap.find('.tab-descrip__inner');
			var $btn = $wrap.find('.js-tab-descrip-more');
			var $fade = $wrap.find('.tab-descrip__fade');
			var $toolbar = $wrap.find('.tab-descrip__toolbar');
			if (!$inner.length || !$btn.length) {
				return;
			}

			var el = $inner[0];
			var cs = window.getComputedStyle(el);
			var lh = parseFloat(cs.lineHeight);
			if (!lh || isNaN(lh) || lh < 10) {
				var fs = parseFloat(cs.fontSize) || 16;
				lh = fs * 1.4;
			}
			var maxH = Math.ceil(lh * 5);

			var w = $inner.width();
			if (!w || w < 40) {
				w = $wrap.closest('.col-xl-5').width() || $wrap.parent().width() || 400;
			}

			var $clone = $inner.clone(false);
			$clone.css({
				position: 'absolute',
				left: '-99999px',
				top: 0,
				width: w,
				maxHeight: 'none',
				height: 'auto',
				display: 'block',
				visibility: 'hidden'
			});
			$('body').append($clone);
			var fullH = $clone.outerHeight(true);
			$clone.remove();

			if (fullH <= maxH + 2) {
				$toolbar.remove();
				$fade.remove();
				var $iw = $wrap.find('.tab-descrip__inner-wrap');
				if ($iw.length) {
					$inner.detach();
					$iw.replaceWith($inner);
				}
				$wrap.data('descrip-ready', true);
				return;
			}

			$wrap.data('descrip-ready', true);
			$wrap.addClass('tab-descrip--has-clamp');
			$wrap.data('collapsed-max', maxH);
			$wrap.data('full-h', fullH);
			$inner.addClass('tab-descrip__inner--collapsed').css('max-height', maxH + 'px');
			$btn.removeAttr('hidden').attr('aria-expanded', 'false');

			var expandDur = 580;

			$btn.off('click.tabDescrip').on('click.tabDescrip', function(e) {
				e.preventDefault();
				var expanded = $wrap.hasClass('tab-descrip--expanded');
				var collapsedMax = $wrap.data('collapsed-max');
				var fullHeight = $wrap.data('full-h');

				if (expanded) {
					var openH = $inner[0].scrollHeight;
					$inner.css('max-height', openH + 'px');
					$wrap.removeClass('tab-descrip--expanded');
					window.requestAnimationFrame(function() {
						window.requestAnimationFrame(function() {
							$inner.addClass('tab-descrip__inner--collapsed').css('max-height', collapsedMax + 'px');
						});
					});
					$btn.text('Показать больше').attr('aria-expanded', 'false');
				} else {
					$wrap.addClass('tab-descrip--expanded');
					$inner.removeClass('tab-descrip__inner--collapsed');
					$inner.css('max-height', collapsedMax + 'px');
					window.requestAnimationFrame(function() {
						window.requestAnimationFrame(function() {
							$inner.css('max-height', Math.ceil(fullHeight + 32) + 'px');
						});
					});
					window.setTimeout(function() {
						if ($wrap.hasClass('tab-descrip--expanded')) {
							$inner.css('max-height', 'none');
						}
					}, expandDur);
					$btn.text('Скрыть').attr('aria-expanded', 'true');
				}
			});
		});
	}

	function initCardProductGallerySlider() {
		$('.js-slider-card-gallery').each(function() {
			var $slider = $(this);
			if ($slider.hasClass('slick-initialized')) {
				return;
			}
			if ($slider.children('.slider-card-gallery__slide').length < 2) {
				return;
			}
			var $dotsWrap = $slider.closest('.slider-card-gallery-outer').find('.slider-card-gallery-dots-wrap');
			$slider.slick({
				arrows: false,
				dots: true,
				infinite: false,
				slidesToShow: 1,
				slidesToScroll: 1,
				autoplay: true,
				autoplaySpeed: 4500,
				pauseOnHover: true,
				adaptiveHeight: false,
				appendDots: $dotsWrap.length ? $dotsWrap : $slider,
				touchThreshold: 1000
			});
		});
	}

	$('.tabs li a').click(function(event) {
		event.preventDefault();
		$(this).parent().parent().find("li").removeClass('active');
		$(this).parent().addClass('active');
		$(this).parents().find(".tab-pane").fadeOut(0);
		var selectTab = $(this).attr("href");
		$(selectTab).fadeIn(200);

		$(this).parent().parent().parent().find(".slider-photos").slick('refresh');

		setTimeout(function() {
			initTabDescripClamp();
			initCardProductGallerySlider();
		}, 250);
	}); 

	$(".input-phone").mask("+7 (999) 999-99-99");


	 // стайлер для select
	 $('select').styler();

	//Попап менеджер FancyBox
	//Документация: http://fancybox.net/howto
	//<a class="fancybox"><img src="image.jpg" /></a>
	//<a class="fancybox" data-fancybox-group="group"><img src="image.jpg" /></a>
	$(".fancybox").fancybox();


	//Кнопка "Наверх"
	//Документация:
	//http://api.jquery.com/scrolltop/
	//http://api.jquery.com/animate/
	$(".btn_top").click(function () {
		$("body, html").animate({
			scrollTop: 0
		}, 800);
		return false;
	});

	objectFitImages();

	/****/
	//search
		$(".open-search").click(function() {
			if ($(".header-search").hasClass("active")) {
				$(".header-search").removeClass("active");
			} else {
				$(".header-search").addClass("active");
			}
		});
	
		  $(document).mouseup(function (e) {
			var container = $(".header-search");
			if (container.has(e.target).length === 0){
			$(".header-search").removeClass("active");
			}
		});
	$('.service-single').each(function(){		
		let value = $(this).data('title');
		if(value) {
			value = value.replace(/<\/?[a-zA-Z]+>/gi,'');
			$(this).find('[name=your-title]').val(value).attr('readonly', true);
		}
	});


	  $('a[href="#order-1click"]').click(function(){		
			let value2 = $(this).data('title');
			value2 = value2.replace(/<\/?[a-zA-Z]+>/gi,'');
			$('#order-1click').find('[name=your-title]').val(value2).attr('readonly', true);
		});

		$('.btn-like').click(function(){
		setTimeout(function(){
			$('.btn-like .simplefavorite-button').each(function(index, value) {
				let count_like = $('.btn-like.active').length;
				//console.log($('.btn-like.active').length);
				if(count_like > 0) {					
					$('.list-social__like .btn-header__value').html(count_like);
				} else {
					$('.list-social__like .btn-header__value').html('0');
				}        
			})
		},1000); 
	});

	$('.favorit-link .simplefavorites-clear').click(function(){
		setTimeout(function(){
			$('.btn-like').removeClass('active');
			$('.btn-header__value, .fav_count').html('0');
		},1000); 
	});
	/* COMPOSITION LOGIC */
	// Handle variation clicks inside a composition unit
	$('.js-unit-variations .js-unit-v').click(function(e){
		e.preventDefault();
		var $this = $(this);
		var $unit = $this.closest('.js-unit-composition');
		
		// Update active class
		$this.siblings().removeClass('active');
		$this.addClass('active');

		// Update price
		var newPriceHtml = $this.data('price-html');
		if(newPriceHtml) {
			$unit.find('.js-unit-price').html(newPriceHtml);
		}
	});

	// Handle single unit "Add to cart" click
	$('.js-unit-add-cart').click(function(e){
		e.preventDefault();
		var $btn = $(this);
		var $unit = $btn.closest('.js-unit-composition');
		var productId = $unit.data('product-id');
		var $activeVar = $unit.find('.js-unit-v.active');
		var variationId = $activeVar.length ? $activeVar.data('variation-id') : 0;

		$btn.css('opacity', '0.5'); // simple loading state
		
		var ajaxEndpoint = (typeof wc_add_to_cart_params !== 'undefined' && wc_add_to_cart_params.ajax_url) ? wc_add_to_cart_params.ajax_url : '/wp-admin/admin-ajax.php';

		$.post( ajaxEndpoint, {
			action: 'add_composition_to_cart',
			items: [{
				product_id: productId,
				variation_id: variationId,
				quantity: 1
			}]
		}, function(response) {
			if(response && response.fragments) {
				$(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $btn]);
			}
			$btn.css('opacity', '1');
		});
	});

	// Handle "Buy Whole Composition" batch add
	$('.add-composition-btn').click(function(e){
		e.preventDefault();
		var $btn = $(this);
		var itemsToAdd = [];

		$('.js-unit-composition').each(function(){
			var $unit = $(this);
			var productId = $unit.data('product-id');
			var $activeVar = $unit.find('.js-unit-v.active');
			var variationId = $activeVar.length ? $activeVar.data('variation-id') : 0;
			
			itemsToAdd.push({
				product_id: productId,
				variation_id: variationId,
				quantity: 1
			});
		});

		if (itemsToAdd.length === 0) return;

		var originalText = $btn.text();
		$btn.text('Добавление...').css('opacity', '0.7');

		var ajaxEndpoint = (typeof wc_add_to_cart_params !== 'undefined' && wc_add_to_cart_params.ajax_url) ? wc_add_to_cart_params.ajax_url : '/wp-admin/admin-ajax.php';

		$.post( ajaxEndpoint, {
			action: 'add_composition_to_cart',
			items: itemsToAdd
		}, function(response) {
			if(response && response.fragments) {
				$(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $btn]);
				$btn.text('Добавлено!').css('background', '#7a9c52').css('color', '#fff').css('opacity', '1');
				setTimeout(function(){
					$btn.text(originalText).css('background', '').css('color', '');
				}, 3000);
			} else {
				$btn.text('Ошибка!').css('background', 'red');
				setTimeout(function(){ $btn.text(originalText).css('background', ''); }, 3000);
			}
		});
	});

	// PDF Presentation Generation
	$('.fv-get-presentation').click(function(e){
		e.preventDefault();
		var $btn = $(this);
		var product_id = $btn.attr('product_id');
		if(!product_id) return;

		var variation_id = 0;
		var $productNode = $btn.closest('[id^="product-"]');
		if ($productNode.length) {
			variation_id = parseInt($productNode.find('input[name="variation_id"]').val(), 10) || 0;
		}

		var originalText = $btn.html();
		$btn.html('Генерация...').css('opacity', '0.7');

		var ajaxEndpoint = (typeof wc_add_to_cart_params !== 'undefined' && wc_add_to_cart_params.ajax_url) ? wc_add_to_cart_params.ajax_url : '/wp-admin/admin-ajax.php';

		$.ajax({
			type: 'POST',
			url: ajaxEndpoint,
			data: {
				action: 'fv_get_presentation',
				product_id: product_id,
				variation_id: variation_id
			},
			success: function(response){
				if(response.success) {
					window.open(response.data, '_blank');
					$btn.html(originalText).css('opacity', '1');
				} else {
					alert(response.data || 'Ошибка при генерации PDF');
					$btn.html(originalText).css('opacity', '1');
				}
			},
			error: function( xhr ) {
				var msg = 'Сетевая ошибка';
				if ( xhr && xhr.status ) {
					msg += ' (HTTP ' + xhr.status + ')';
				}
				if ( xhr && xhr.responseText && xhr.responseText.indexOf( '{' ) === 0 ) {
					try {
						var r = JSON.parse( xhr.responseText );
						if ( r && r.data ) {
							msg = r.data;
						}
					} catch ( err ) {}
				}
				alert( msg );
				$btn.html(originalText).css('opacity', '1');
			}
		});
	});

	setTimeout(function() {
		initTabDescripClamp();
		initCardProductGallerySlider();
	}, 150);

	$(".fancybox-rutube").fancybox({
        type: 'iframe',
        onComplete: function(){
            $('#fancybox-frame').load(function(){
                $.fancybox.hideActivity();
            });
            $.fancybox.showActivity();
        },		
    });

});


/*polifyl*/
/*! npm.im/object-fit-images 3.2.4 */
var objectFitImages=function(){"use strict";function t(t,e){return"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='"+t+"' height='"+e+"'%3E%3C/svg%3E"}function e(t){if(t.srcset&&!p&&window.picturefill){var e=window.picturefill._;t[e.ns]&&t[e.ns].evaled||e.fillImg(t,{reselect:!0}),t[e.ns].curSrc||(t[e.ns].supported=!1,e.fillImg(t,{reselect:!0})),t.currentSrc=t[e.ns].curSrc||t.src}}function i(t){for(var e,i=getComputedStyle(t).fontFamily,r={};null!==(e=u.exec(i));)r[e[1]]=e[2];return r}function r(e,i,r){var n=t(i||1,r||0);b.call(e,"src")!==n&&h.call(e,"src",n)}function n(t,e){t.naturalWidth?e(t):setTimeout(n,100,t,e)}function c(t){var c=i(t),o=t[l];if(c["object-fit"]=c["object-fit"]||"fill",!o.img){if("fill"===c["object-fit"])return;if(!o.skipTest&&f&&!c["object-position"])return}if(!o.img){o.img=new Image(t.width,t.height),o.img.srcset=b.call(t,"data-ofi-srcset")||t.srcset,o.img.src=b.call(t,"data-ofi-src")||t.src,h.call(t,"data-ofi-src",t.src),t.srcset&&h.call(t,"data-ofi-srcset",t.srcset),r(t,t.naturalWidth||t.width,t.naturalHeight||t.height),t.srcset&&(t.srcset="");try{s(t)}catch(t){window.console&&console.warn("https://bit.ly/ofi-old-browser")}}e(o.img),t.style.backgroundImage='url("'+(o.img.currentSrc||o.img.src).replace(/"/g,'\\"')+'")',t.style.backgroundPosition=c["object-position"]||"center",t.style.backgroundRepeat="no-repeat",t.style.backgroundOrigin="content-box",/scale-down/.test(c["object-fit"])?n(o.img,function(){o.img.naturalWidth>t.width||o.img.naturalHeight>t.height?t.style.backgroundSize="contain":t.style.backgroundSize="auto"}):t.style.backgroundSize=c["object-fit"].replace("none","auto").replace("fill","100% 100%"),n(o.img,function(e){r(t,e.naturalWidth,e.naturalHeight)})}function s(t){var e={get:function(e){return t[l].img[e?e:"src"]},set:function(e,i){return t[l].img[i?i:"src"]=e,h.call(t,"data-ofi-"+i,e),c(t),e}};Object.defineProperty(t,"src",e),Object.defineProperty(t,"currentSrc",{get:function(){return e.get("currentSrc")}}),Object.defineProperty(t,"srcset",{get:function(){return e.get("srcset")},set:function(t){return e.set(t,"srcset")}})}function o(){function t(t,e){return t[l]&&t[l].img&&("src"===e||"srcset"===e)?t[l].img:t}d||(HTMLImageElement.prototype.getAttribute=function(e){return b.call(t(this,e),e)},HTMLImageElement.prototype.setAttribute=function(e,i){return h.call(t(this,e),e,String(i))})}function a(t,e){var i=!y&&!t;if(e=e||{},t=t||"img",d&&!e.skipTest||!m)return!1;"img"===t?t=document.getElementsByTagName("img"):"string"==typeof t?t=document.querySelectorAll(t):"length"in t||(t=[t]);for(var r=0;r<t.length;r++)t[r][l]=t[r][l]||{skipTest:e.skipTest},c(t[r]);i&&(document.body.addEventListener("load",function(t){"IMG"===t.target.tagName&&a(t.target,{skipTest:e.skipTest})},!0),y=!0,t="img"),e.watchMQ&&window.addEventListener("resize",a.bind(null,t,{skipTest:e.skipTest}))}var l="fregante:object-fit-images",u=/(object-fit|object-position)\s*:\s*([-.\w\s%]+)/g,g="undefined"==typeof Image?{style:{"object-position":1}}:new Image,f="object-fit"in g.style,d="object-position"in g.style,m="background-size"in g.style,p="string"==typeof g.currentSrc,b=g.getAttribute,h=g.setAttribute,y=!1;return a.supportsObjectFit=f,a.supportsObjectPosition=d,o(),a}();

