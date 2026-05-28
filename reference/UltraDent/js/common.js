$(document).ready(function() {


//прилипающие меню
var $menu = $(".header");
$(window).scroll(function(){
  if ( $(this).scrollTop() > 0 && $menu.hasClass("default") ){
    $menu.removeClass("default").addClass("fixed");
  } else if($(this).scrollTop() <= 0 && $menu.hasClass("fixed")) {
    $menu.removeClass("fixed").addClass("default");
  }
  
});

if ( $(this).scrollTop() > 0 && $menu.hasClass("default") ){
    $menu.removeClass("default").addClass("fixed");
  } else if($(this).scrollTop() <= 0 && $menu.hasClass("fixed")) {
    $menu.removeClass("fixed").addClass("default");
  }

  $('.nav-article a').on('click', function(e) {
  e.preventDefault();
  
  var targetId = $(this).attr('href');
  var headerHeight = $('.header').outerHeight();
  
  $('html, body').animate({
      scrollTop: $(targetId).offset().top - headerHeight
  }, 800); 
});

	//цены
	$(".item-table__head").click(function() {
		$(this).parent().toggleClass("active");
		$(this).siblings(".table-main").slideToggle(200);
		$(this).parent().siblings(".item-table").removeClass("active");
		$(this).parent().siblings(".item-table").find(".table-main").slideUp(200);
	});


$('.unit-category__arrow').each(function () {
      const $wrap = $(this);
      const $line = $wrap.find('.arrow-line');

      $wrap.on('mouseenter', function () {
        $line.attr('x2', '160');
      });

      $wrap.on('mouseleave', function () {
        $line.attr('x2', '59');
      });
    });


	//кнопка sandwich
	$(".sandwich").click(function() {
		if ($(".menu-main").is(":hidden")) {
			$(".menu-main").slideDown(200);
			$(".sandwich").addClass("active");
			$("body").addClass("no-scroll");
			$(".menu-overlay").fadeIn(200);
		} else {
			$(".menu-main").slideUp(200);
			$(".sandwich").removeClass("active");
			$("body").removeClass("no-scroll");
			$(".menu-overlay").fadeOut(200);
		}
	});

	$(".menu-overlay").click(function() {
		$(".menu-main").slideUp(200);
		$(".sandwich").removeClass("active");
		$("body").removeClass("no-scroll");
		$(".menu-overlay").fadeOut(200);
	});

	$('.beforeAfter').beforeAfter();

		$(".menu__haschild > a").click(function(e) {
		e.preventDefault();
		$(this).parent().siblings().removeClass("active");
		$(this).parent().siblings().find(".menu-dropdown").slideUp(200);
		if ($(this).siblings(".menu-dropdown").is(":hidden")) {
			$(this).siblings(".menu-dropdown").slideDown(200);
			$(this).parent().addClass("active");
		} else {
			$(this).siblings(".menu-dropdown").slideUp(200);
			$(this).parent().removeClass("active");
		}
		});

	//слайдер

$('.slider-wrapper').each(function () {
	const $wrapper = $(this);
	const $slider = $wrapper.find('.slider-specialists');
	const $number = $wrapper.find('.slider-controls__number');
	const $total = $wrapper.find('.slider-controls__total');
	const $progress = $wrapper.find('.progressbar__value');

	$slider.slick({
		arrows: false,
		dots: false,
		infinite: true,
		touchThreshold: 1000,
		slidesToShow: 5,
		slidesToScroll: 1,
		responsive: [
			{
				breakpoint: 1200,
				settings: {
					slidesToShow: 4,
				}
			},
			{
				breakpoint: 992,
				settings: {
					slidesToShow: 3,
				}
			},
			{
				breakpoint: 768,
				settings: {
					slidesToShow: 2,
				}
			},
			{
				breakpoint: 480,
				settings: {
					slidesToShow: 1,
				}
			}
		]
	});

	const totalSlides = $slider.slick("getSlick").slideCount;
	$total.text(totalSlides < 10 ? '0' + totalSlides : totalSlides);

	function updateSliderUI(currentIndex) {
		const currentSlide = currentIndex + 1; 
		const formatted = currentSlide < 10 ? '0' + currentSlide : currentSlide;
		$number.text(formatted);

		const percentage = (currentSlide / totalSlides) * 100;
		$progress.css('width', percentage + '%');
	}

	updateSliderUI($slider.slick('slickCurrentSlide'));

	$slider.on('afterChange', function (event, slick, currentSlide) {
		updateSliderUI(currentSlide);
	});
});

$('.slider-wrapper-page').each(function () {
	const $wrapper = $(this);
	const $slider = $wrapper.find('.slider-specialists-page');
	const $number = $wrapper.find('.slider-controls__number');
	const $total = $wrapper.find('.slider-controls__total');
	const $progress = $wrapper.find('.progressbar__value');

	$slider.slick({
		arrows: false,
		dots: false,
		infinite: true,
		touchThreshold: 1000,
		slidesToShow: 4,
		slidesToScroll: 1,
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
					slidesToShow: 3,
				}
			},
			{
				breakpoint: 768,
				settings: {
					slidesToShow: 2,
				}
			},
			{
				breakpoint: 480,
				settings: {
					slidesToShow: 1,
				}
			}
		]
	});

	const totalSlides = $slider.slick("getSlick").slideCount;
	$total.text(totalSlides < 10 ? '0' + totalSlides : totalSlides);

	function updateSliderUI(currentIndex) {
		const currentSlide = currentIndex + 1; 
		const formatted = currentSlide < 10 ? '0' + currentSlide : currentSlide;
		$number.text(formatted);

		const percentage = (currentSlide / totalSlides) * 100;
		$progress.css('width', percentage + '%');
	}

	updateSliderUI($slider.slick('slickCurrentSlide'));

	$slider.on('afterChange', function (event, slick, currentSlide) {
		updateSliderUI(currentSlide);
	});
});

$('.wrapper-slider-comparsion').each(function () {
	const $wrapper = $(this);
	const $slider = $wrapper.find('.slider-comparsion');
	const $controls = $wrapper.find('.buttons-slider');

	$slider.slick({
		arrows: true,
		dots: true,
		infinite: true,
		swipe: false,
		touchThreshold: 1000,
		appendArrows: $controls,
		appendDots: $controls,
		slidesToShow: 1,
		slidesToScroll: 1,
		prevArrow: '<div class="slick-prev slick-arrow"><div class="arrow-main"><div/><div/>',
		nextArrow: '<div class="slick-next slick-arrow"><div class="arrow-main"><div/><div/>',
	});
});

$('.wrapper-slider-specialist').each(function () {
	const $wrapper = $(this);
	const $slider = $wrapper.find('.slider-specialist-about');
	const $controls = $wrapper.find('.buttons-slider');

	$slider.slick({
		arrows: true,
		dots: false,
		infinite: true,
		touchThreshold: 1000,
		appendArrows: $controls,
		appendDots: $controls,
		slidesToShow: 2,
		slidesToScroll: 1,
		prevArrow: '<div class="slick-prev slick-arrow"><div class="arrow-main"><div/><div/>',
		nextArrow: '<div class="slick-next slick-arrow"><div class="arrow-main"><div/><div/>',
		responsive: [
			{
				breakpoint: 480,
				settings: {
					slidesToShow: 1,
				}
			}
		]
	});
});

$('.wrapper-slider-sertificats').each(function () {
	const $wrapper = $(this);
	const $slider = $wrapper.find('.slider-sertificats');
	const $controls = $wrapper.find('.buttons-slider');

	$slider.slick({
		arrows: true,
		dots: true,
		infinite: true,
		touchThreshold: 1000,
		appendArrows: $controls,
		appendDots: $controls,
		slidesToShow: 4,
		slidesToScroll: 1,
		prevArrow: '<div class="slick-prev slick-arrow"><div class="arrow-main"><div/><div/>',
		nextArrow: '<div class="slick-next slick-arrow"><div class="arrow-main"><div/><div/>',
		responsive: [
			{
				breakpoint: 992,
				settings: {
					slidesToShow: 3,
				}
			},
			{
				breakpoint: 768,
				settings: {
					slidesToShow: 2,
				}
			}
		]
	});
});

  {
    if ($(window).width() < 992) { 
      $(".footer__title").click(function() {
		$(this).toggleClass("active");
		$(this).next(".footer__content").slideToggle(200);
	});

    }
  }


$(".item-question__head").click(function() {
    $(this).parent().toggleClass("active");
    $(this).siblings().slideToggle(200);
    $(this).parent().siblings(".item-question").removeClass("active");
    $(this).parent().siblings(".item-question").find(".item-question__content").slideUp(200);
  });


	$(".input-phone").mask("+7 (999) 999-99-99");


	 // стайлер для select
	 $('.item-form select').styler();

	//Попап менеджер FancyBox
	$(".fancybox").fancybox({
		autoFocus: false,
		backFocus: false,
	});

	objectFitImages();


});


/*polifyl*/
  /*! npm.im/object-fit-images 3.2.4 */
  var objectFitImages=function(){"use strict";function t(t,e){return"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='"+t+"' height='"+e+"'%3E%3C/svg%3E"}function e(t){if(t.srcset&&!p&&window.picturefill){var e=window.picturefill._;t[e.ns]&&t[e.ns].evaled||e.fillImg(t,{reselect:!0}),t[e.ns].curSrc||(t[e.ns].supported=!1,e.fillImg(t,{reselect:!0})),t.currentSrc=t[e.ns].curSrc||t.src}}function i(t){for(var e,i=getComputedStyle(t).fontFamily,r={};null!==(e=u.exec(i));)r[e[1]]=e[2];return r}function r(e,i,r){var n=t(i||1,r||0);b.call(e,"src")!==n&&h.call(e,"src",n)}function n(t,e){t.naturalWidth?e(t):setTimeout(n,100,t,e)}function c(t){var c=i(t),o=t[l];if(c["object-fit"]=c["object-fit"]||"fill",!o.img){if("fill"===c["object-fit"])return;if(!o.skipTest&&f&&!c["object-position"])return}if(!o.img){o.img=new Image(t.width,t.height),o.img.srcset=b.call(t,"data-ofi-srcset")||t.srcset,o.img.src=b.call(t,"data-ofi-src")||t.src,h.call(t,"data-ofi-src",t.src),t.srcset&&h.call(t,"data-ofi-srcset",t.srcset),r(t,t.naturalWidth||t.width,t.naturalHeight||t.height),t.srcset&&(t.srcset="");try{s(t)}catch(t){window.console&&console.warn("https://bit.ly/ofi-old-browser")}}e(o.img),t.style.backgroundImage='url("'+(o.img.currentSrc||o.img.src).replace(/"/g,'\\"')+'")',t.style.backgroundPosition=c["object-position"]||"center",t.style.backgroundRepeat="no-repeat",t.style.backgroundOrigin="content-box",/scale-down/.test(c["object-fit"])?n(o.img,function(){o.img.naturalWidth>t.width||o.img.naturalHeight>t.height?t.style.backgroundSize="contain":t.style.backgroundSize="auto"}):t.style.backgroundSize=c["object-fit"].replace("none","auto").replace("fill","100% 100%"),n(o.img,function(e){r(t,e.naturalWidth,e.naturalHeight)})}function s(t){var e={get:function(e){return t[l].img[e?e:"src"]},set:function(e,i){return t[l].img[i?i:"src"]=e,h.call(t,"data-ofi-"+i,e),c(t),e}};Object.defineProperty(t,"src",e),Object.defineProperty(t,"currentSrc",{get:function(){return e.get("currentSrc")}}),Object.defineProperty(t,"srcset",{get:function(){return e.get("srcset")},set:function(t){return e.set(t,"srcset")}})}function o(){function t(t,e){return t[l]&&t[l].img&&("src"===e||"srcset"===e)?t[l].img:t}d||(HTMLImageElement.prototype.getAttribute=function(e){return b.call(t(this,e),e)},HTMLImageElement.prototype.setAttribute=function(e,i){return h.call(t(this,e),e,String(i))})}function a(t,e){var i=!y&&!t;if(e=e||{},t=t||"img",d&&!e.skipTest||!m)return!1;"img"===t?t=document.getElementsByTagName("img"):"string"==typeof t?t=document.querySelectorAll(t):"length"in t||(t=[t]);for(var r=0;r<t.length;r++)t[r][l]=t[r][l]||{skipTest:e.skipTest},c(t[r]);i&&(document.body.addEventListener("load",function(t){"IMG"===t.target.tagName&&a(t.target,{skipTest:e.skipTest})},!0),y=!0,t="img"),e.watchMQ&&window.addEventListener("resize",a.bind(null,t,{skipTest:e.skipTest}))}var l="fregante:object-fit-images",u=/(object-fit|object-position)\s*:\s*([-.\w\s%]+)/g,g="undefined"==typeof Image?{style:{"object-position":1}}:new Image,f="object-fit"in g.style,d="object-position"in g.style,m="background-size"in g.style,p="string"==typeof g.currentSrc,b=g.getAttribute,h=g.setAttribute,y=!1;return a.supportsObjectFit=f,a.supportsObjectPosition=d,o(),a}();

const observer = new IntersectionObserver((entries) => {
entries.forEach((entry) => {
    if (entry.isIntersecting) {
       setTimeout(() => {
		 entry.target.classList.add("active");
	   }, 200);
    }
})

}, {
    threshold: 0
})

document.querySelectorAll(".slideAnim").forEach(item => {
    observer.observe(item)
})