$(document).ready(function() {

	//vacancies

	$(".item-dropdown__head").click(function() {
		$(".item-dropdown").removeClass("active");
		$(".item-dropdown__content").slideUp(200);
		if ($(this).siblings().is(":hidden")) {
			$(this).parent().addClass("active");
			$(this).siblings().slideDown(200);
		} else {
			$(this).parent().removeClass("active");
			$(this).siblings().slideUp(200);
		}
	  });

	  //datatime
	  $('.input-datatime').datetimepicker({
		dayOfWeekStart : 1,
		lang:'ru',
		formatDate:'Y/m/d',
	});

	$.datetimepicker.setLocale('ru');

  //sidebar catalog
  $(".btn-main_filter").click(function(e) {
	e.preventDefault();
	$(this).toggleClass("active");
	$(".sidebar-catalog").slideToggle(200);
});

	//кнопка sandwich
	$(".sandwich").click(function() {
		if ($(".header__bottom").is(":hidden")) {
			$(".header__bottom").slideDown(200);
			$(".sandwich").addClass("active");
			$("body").addClass("no-scroll");
			$(".menu-overlay").fadeIn(200);
		} else {
			$(".header__bottom").slideUp(200);
			$(".sandwich").removeClass("active");
			$("body").removeClass("no-scroll");
			$(".menu-overlay").fadeOut(200);
		}
	});

	$(".menu-overlay").click(function() {
		$(".header__bottom").slideUp(200);
		$("body").removeClass("no-scroll");
		$(".sandwich").removeClass("active");
		$(".menu-overlay").fadeOut(200);
	});

	
	{
		if ($(window).width() > 992) { 
			$(".menu__haschild").on("mouseenter", function () {
				$(this).find(".menu-dropdown").stop(true, true).fadeIn(200);
			}).on("mouseleave", function () {
				$(this).find(".menu-dropdown").stop(true, true).fadeOut(200);
			});
			$(document).mouseup(function (e) {
				var container = $(".menu-dropdown");
				if (container.has(e.target).length === 0){
					$(".menu-dropdown").fadeOut(200);
				}
			  });		
		}
	}

	
{
	if ($(window).width() < 992) { 
		$(".menu__haschild > a").click(function(e) {
			e.preventDefault();
			$(".menu-dropdown").slideUp(200);
			$(this).parent().siblings().removeClass("opened");
			if ($(this).siblings(".menu-dropdown").is(":hidden")) {
				$(this).siblings(".menu-dropdown").slideDown(200);
				$(this).parent().addClass("opened");
			} else {
				$(this).siblings(".menu-dropdown").slideUp(200);
				$(this).parent().removeClass("opened");
			}
		});

		$(".menu-dropdown__content_haschild > .menu-dropdown__title").click(function(e) {
			e.preventDefault();
			$(".menu-dropdown__content ul").slideUp(200);
			$(".menu-dropdown__title").removeClass("active");
			$(".menu-dropdown .col-lg-6").removeClass("active");
			if ($(this).siblings("ul").is(":hidden")) {
				$(this).siblings("ul").slideDown(200);
				$(this).addClass("active");
				$(this).parents(".col-lg-6").addClass("active");
			} else {
				$(this).siblings("ul").slideUp(200);
				$(this).removeClass("active");
				$(this).parents(".col-lg-6").removeClass("active");
			}
		});
	}
}

	$(".menu-dropdown__close").click(function() {
		$(".menu-dropdown").fadeOut(200);
	});

	//слайдер

	$('.slider-catalog').slick({
		arrows: true,
		dots: false,
		infinite: true,
		touchThreshold: 1000,
		slidesToShow: 4,
		slidesToScroll: 1,
		prevArrow: '<div class="slick-prev slick-arrow"><i class="fal fa-chevron-left"></i><div/>',
		nextArrow: '<div class="slick-next slick-arrow"><i class="fal fa-chevron-right"></i><div/>',
		responsive: [
			{
				breakpoint: 1200,
				settings: {
					slidesToShow: 3,
				}
			},
			{
				breakpoint: 992,
				settings: {
					slidesToShow: 2,
					arrows: false,
					dots: true,
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

	$('.slider-banner').slick({
		arrows: true,
		dots: false,
		infinite: true,
		touchThreshold: 1000,
		slidesToShow: 1,
		slidesToScroll: 1,
		autoplay: true,
		autoplaySpeed: 4000,
		speed: 1500,
		prevArrow: '<div class="slick-prev slick-arrow"><i class="fal fa-chevron-left"></i><div/>',
		nextArrow: '<div class="slick-next slick-arrow"><i class="fal fa-chevron-right"></i><div/>',
		responsive: [
			{
				breakpoint: 992,
				settings: {
					arrows: false,
					dots: true,
				}
			}
			]
		});

		$('.slider-card').slick({
			arrows: true,
			dots: true,
			infinite: true,
			touchThreshold: 1000,
			slidesToShow: 1,
			slidesToScroll: 1,
			speed: 1500,
			prevArrow: '<div class="slick-prev slick-arrow"><i class="fal fa-chevron-left"></i><div/>',
			nextArrow: '<div class="slick-next slick-arrow"><i class="fal fa-chevron-right"></i><div/>',
		});

	$(".input-phone").mask("+7 (999) 999-99-99");


	$('.tabs-wrap').each(function() {
		var currentTab = $(this);
		var initalTextTab = currentTab.find(".active a").html();
		currentTab.find(".btn-tab").html(initalTextTab);
}); 
$('.btn-tab').click(function() {
	$(this).toggleClass("active");
	$(this).siblings(".tabs").slideToggle(200);
	$('.tabs:not(.main-links) li a').click(function(event) {
		$(this).parent().parent().slideUp(200);
	}); 
}); 

$('.tabs li a').click(function(event) {
	event.preventDefault();
	var textTab = $(this).html();
	$(this).parent().parent().find("li").removeClass('active');
	$(this).parent().addClass('active');
	$(".tab-pane").fadeOut(0);
	var selectTab = $(this).attr("href");
	$(selectTab).fadeIn(200);
	$(".tab-pane .slider-catalog").slick('setPosition');
	$(this).parent().parent().siblings(".btn-tab").html(textTab);
	$(this).parent().parent().siblings(".btn-tab").removeClass("active");	
}); 

//switch adds
$('.item-toggle .switch').click(function(event) {
	$(this).closest(".item-toggle").toggleClass("active");
	$(this).closest(".item-toggle").find(".item-toggle__content").slideToggle(200);
}); 
//card adds
function updateItemState($input) {
	let value = parseInt($input.val(), 10);
	let $item = $input.closest('.item-add');

	if (value > 1) {
		$item.addClass('active');
	} else {
		$item.removeClass('active');
	}
}

//social radios

$('.radios-wrap_socials input').on('change', function () {
	let index = $('.radios-wrap_socials input').index(this);
	$('.item-form_socials input').hide().eq(index).show();
});

// Проверяем все инпуты при загрузке страницы
$('.item-add input[type="number"]').each(function() {
	updateItemState($(this));
});

// Отслеживаем изменения в input
$('.item-add input[type="number"]').on('input change', function() {
	updateItemState($(this));
});

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

$('.basket-wrap .btn-header').click(function(event) {
	event.preventDefault();
	{
		if ($(window).width() < 992) { 
			$(".header__bottom").slideUp(200);
			$("body").removeClass("no-scroll");
			$(".sandwich").removeClass("active");
			$(".menu-overlay").fadeOut(200);
		}
	}

	if ($(".basket-popup").is(":hidden")) {
		$(".basket-popup").fadeIn(200);
		$(this).addClass("active");
	} else {
		$(".basket-popup").fadeOut(0);
		$(this).removeClass("active");
	}
}); 

$('.share-wrap .btn-header').click(function(event) {
	event.preventDefault();
	{
		if ($(window).width() < 992) { 
			$(".header__bottom").slideUp(200);
			$("body").removeClass("no-scroll");
			$(".sandwich").removeClass("active");
			$(".menu-overlay").fadeOut(200);
		}
	}

	if ($(".share-popup").is(":hidden")) {
		$(".share-popup").fadeIn(200);
		$(this).addClass("active");
	} else {
		$(".share-popup").fadeOut(0);
		$(this).removeClass("active");
	}
}); 

$(document).mouseup(function (e) {
    var container = $(".basket-wrap");
    if (container.has(e.target).length === 0){
		$(".basket-popup").fadeOut(0);
		$('.basket-wrap .btn-header').removeClass("active");
    }
  });

  $(document).mouseup(function (e) {
    var container = $(".share-wrap");
    if (container.has(e.target).length === 0){
		$(".share-popup").fadeOut(0);
		$('.share-wrap .btn-header').removeClass("active");
    }
  });

	 // стайлер для select
	 $('select').styler();

	//Попап менеджер FancyBox
	//Документация: http://fancybox.net/howto
	//<a class="fancybox"><img src="image.jpg" /></a>
	//<a class="fancybox" data-fancybox-group="group"><img src="image.jpg" /></a>
	$(".fancybox").fancybox({
		autoFocus: false,
		backFocus: false,
	});

	$(".fancybox-gallery").fancybox({
		autoFocus: false,
		backFocus: false,
		buttons: ["close"], // Оставляем только кнопку закрытия
		btnTpl: {
			close:
				'<button data-fancybox-close class="fancybox-button fancybox-close-custom" title="Close">' +
				'<svg xmlns="http://www.w3.org/2000/svg" version="1" viewBox="0 0 24 24"><path d="M13 12l5-5-1-1-5 5-5-5-1 1 5 5-5 5 1 1 5-5 5 5 1-1z"></path></svg>' +
				"</button>"
		},
		afterShow: function (instance, current) {
			$(".fancybox-content").append($(".fancybox-close-custom"));
		},
		beforeShow: function (instance, current) {
			$(".fancybox-container").addClass("fancybox-gallery-container");
		}
	});


	objectFitImages();


});


/*polifyl*/
  /*! npm.im/object-fit-images 3.2.4 */
  var objectFitImages=function(){"use strict";function t(t,e){return"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='"+t+"' height='"+e+"'%3E%3C/svg%3E"}function e(t){if(t.srcset&&!p&&window.picturefill){var e=window.picturefill._;t[e.ns]&&t[e.ns].evaled||e.fillImg(t,{reselect:!0}),t[e.ns].curSrc||(t[e.ns].supported=!1,e.fillImg(t,{reselect:!0})),t.currentSrc=t[e.ns].curSrc||t.src}}function i(t){for(var e,i=getComputedStyle(t).fontFamily,r={};null!==(e=u.exec(i));)r[e[1]]=e[2];return r}function r(e,i,r){var n=t(i||1,r||0);b.call(e,"src")!==n&&h.call(e,"src",n)}function n(t,e){t.naturalWidth?e(t):setTimeout(n,100,t,e)}function c(t){var c=i(t),o=t[l];if(c["object-fit"]=c["object-fit"]||"fill",!o.img){if("fill"===c["object-fit"])return;if(!o.skipTest&&f&&!c["object-position"])return}if(!o.img){o.img=new Image(t.width,t.height),o.img.srcset=b.call(t,"data-ofi-srcset")||t.srcset,o.img.src=b.call(t,"data-ofi-src")||t.src,h.call(t,"data-ofi-src",t.src),t.srcset&&h.call(t,"data-ofi-srcset",t.srcset),r(t,t.naturalWidth||t.width,t.naturalHeight||t.height),t.srcset&&(t.srcset="");try{s(t)}catch(t){window.console&&console.warn("https://bit.ly/ofi-old-browser")}}e(o.img),t.style.backgroundImage='url("'+(o.img.currentSrc||o.img.src).replace(/"/g,'\\"')+'")',t.style.backgroundPosition=c["object-position"]||"center",t.style.backgroundRepeat="no-repeat",t.style.backgroundOrigin="content-box",/scale-down/.test(c["object-fit"])?n(o.img,function(){o.img.naturalWidth>t.width||o.img.naturalHeight>t.height?t.style.backgroundSize="contain":t.style.backgroundSize="auto"}):t.style.backgroundSize=c["object-fit"].replace("none","auto").replace("fill","100% 100%"),n(o.img,function(e){r(t,e.naturalWidth,e.naturalHeight)})}function s(t){var e={get:function(e){return t[l].img[e?e:"src"]},set:function(e,i){return t[l].img[i?i:"src"]=e,h.call(t,"data-ofi-"+i,e),c(t),e}};Object.defineProperty(t,"src",e),Object.defineProperty(t,"currentSrc",{get:function(){return e.get("currentSrc")}}),Object.defineProperty(t,"srcset",{get:function(){return e.get("srcset")},set:function(t){return e.set(t,"srcset")}})}function o(){function t(t,e){return t[l]&&t[l].img&&("src"===e||"srcset"===e)?t[l].img:t}d||(HTMLImageElement.prototype.getAttribute=function(e){return b.call(t(this,e),e)},HTMLImageElement.prototype.setAttribute=function(e,i){return h.call(t(this,e),e,String(i))})}function a(t,e){var i=!y&&!t;if(e=e||{},t=t||"img",d&&!e.skipTest||!m)return!1;"img"===t?t=document.getElementsByTagName("img"):"string"==typeof t?t=document.querySelectorAll(t):"length"in t||(t=[t]);for(var r=0;r<t.length;r++)t[r][l]=t[r][l]||{skipTest:e.skipTest},c(t[r]);i&&(document.body.addEventListener("load",function(t){"IMG"===t.target.tagName&&a(t.target,{skipTest:e.skipTest})},!0),y=!0,t="img"),e.watchMQ&&window.addEventListener("resize",a.bind(null,t,{skipTest:e.skipTest}))}var l="fregante:object-fit-images",u=/(object-fit|object-position)\s*:\s*([-.\w\s%]+)/g,g="undefined"==typeof Image?{style:{"object-position":1}}:new Image,f="object-fit"in g.style,d="object-position"in g.style,m="background-size"in g.style,p="string"==typeof g.currentSrc,b=g.getAttribute,h=g.setAttribute,y=!1;return a.supportsObjectFit=f,a.supportsObjectPosition=d,o(),a}();

