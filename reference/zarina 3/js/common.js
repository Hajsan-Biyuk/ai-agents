$(document).ready(function () {

    //прилипающие меню
    var $menu = $(".header");
    $(window).scroll(function () {
        if ($(this).scrollTop() > 0 && $menu.hasClass("default")) {
            $menu.removeClass("default").addClass("fixed");
        } else if ($(this).scrollTop() <= 0 && $menu.hasClass("fixed")) {
            $menu.removeClass("fixed").addClass("default");
        }

    });

    if ($(this).scrollTop() > 0 && $menu.hasClass("default")) {
        $menu.removeClass("default").addClass("fixed");
    } else if ($(this).scrollTop() <= 0 && $menu.hasClass("fixed")) {
        $menu.removeClass("fixed").addClass("default");
    }

    //copy
    $('.copy__btn').on('click', function() {
        const $btn = $(this);
        const $parent = $btn.closest('.copy');
        const textToCopy = $parent.find('.copy__value').text();

        const $temp = $("<input>");
        $("body").append($temp);
        $temp.val(textToCopy).select();
        document.execCommand("copy");
        $temp.remove();

        const $tooltip = $('<span class="copy-tooltip">Скопировано!</span>');
        
        $('body').append($tooltip);

        const btnOffset = $btn.offset();
        $tooltip.css({
            top: btnOffset.top - $tooltip.outerHeight() - 10,
            left: btnOffset.left + ($btn.outerWidth() / 2) - ($tooltip.outerWidth() / 2)
        });

        $tooltip.fadeIn(200).delay(1000).fadeOut(400, function() {
            $(this).remove();
        });
    });

    //datatime
    $('#date-start').datetimepicker({
        dayOfWeekStart: 1,
        format: 'd.m.Y',
        formatDate: 'd.m.Y',
        timepicker: false,
        closeOnDateSelect: true,
        scrollMonth: false,
        scrollInput: false,
        onSelectDate: function (date) {
            $('#date-end').datetimepicker({
                minDate: date
            });
        }
    });

    $('#date-end').datetimepicker({
        dayOfWeekStart: 1,
        format: 'd.m.Y',
        formatDate: 'd.m.Y',
        timepicker: false,
        closeOnDateSelect: true,
        scrollMonth: false,
        scrollInput: false,
        minDate: 0
    });

    	$.datetimepicker.setLocale('ru');

    //кнопка sandwich
    $(".sandwich").click(function () {
        if ($(".menu-mobile").is(":hidden")) {
            $(".menu-mobile").slideDown(200);
            $(".sandwich").addClass("active");
            $("body").addClass("body--menu");
            $(".menu-overlay").fadeIn(200);
        } else {
            $(".menu-mobile").slideUp(200);
            $(".sandwich").removeClass("active");
            $("body").removeClass("body--menu");
            $(".menu-overlay").fadeOut(200);
        }
    });

    $(".menu-overlay").click(function () {
        $(".menu-mobile").slideUp(200);
        $(".sandwich").removeClass("active");
        $("body").removeClass("body--menu");
        $(".menu-overlay").fadeOut(200);
    });

    //слайдер

    $('.slider-catalog').slick({
        arrows: true,
        dots: false,
        infinite: true,
        touchThreshold: 1000,
        slidesToShow: 2,
        slidesToScroll: 1,
        prevArrow: '<div class="slick-prev slick-arrow"><i class="far fa-arrow-left"></i></div>',
        nextArrow: '<div class="slick-next slick-arrow"><i class="far fa-arrow-right"></i></div>',
        responsive: [
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 1,
                }
            }
        ]
    });

    $('.slider-about').slick({
        arrows: true,
        dots: false,
        infinite: true,
        touchThreshold: 1000,
        slidesToShow: 2,
        slidesToScroll: 1,
        appendArrows: ".slider-controls-about",
        prevArrow: '<div class="slick-prev slick-arrow"><i class="far fa-arrow-left"></i></div>',
        nextArrow: '<div class="slick-next slick-arrow"><i class="far fa-arrow-right"></i></div>',
        responsive: [
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 1,
                }
            }
        ]
    });



    function updateSliderCount() {
        const $slider = $('.slider-about');
        const $slideCount = $('.slider-count-about strong');

        const currentSlide = $slider.slick('slickCurrentSlide') + 1;

        const totalSlides = $slider.slick('getSlick').slideCount;

        $slideCount.text(currentSlide);
        $('.slider-count-about').html(`<strong>${currentSlide}</strong> / ${totalSlides}`);
    }

    $('.slider-about').on('init', function (event, slick) {
        updateSliderCount();
    });

    $('.slider-about').on('afterChange', function (event, slick, currentSlide) {
        updateSliderCount();
    });

    setTimeout(function () {
        updateSliderCount();
    }, 100);

         $('.slider-documents').slick({
        arrows: true,
        dots: false,
        infinite: true,
        touchThreshold: 1000,
        slidesToShow: 1,
        slidesToScroll: 1,
        prevArrow: '<div class="slick-prev slick-arrow"><i class="far fa-arrow-left"></i></div>',
        nextArrow: '<div class="slick-next slick-arrow"><i class="far fa-arrow-right"></i></div>',
    });

    $('.slider-wrapper').each(function () {
	const $wrapper = $(this);
	const $slider = $wrapper.find('.slider-card');
	const $number = $wrapper.find('.slider-controls__number');
	const $total = $wrapper.find('.slider-controls__total');
    const $controls = $wrapper.find('.slider-controls');

	$slider.slick({
		arrows: true,
		dots: false,
		infinite: true,
		touchThreshold: 1000,
        appendArrows: $controls,
                prevArrow: '<div class="slick-prev slick-arrow"><i class="far fa-arrow-left"></i></div>',
        nextArrow: '<div class="slick-next slick-arrow"><i class="far fa-arrow-right"></i></div>',
		slidesToShow: 1,
		slidesToScroll: 1,
	});

	const totalSlides = $slider.slick("getSlick").slideCount;
	$total.text(totalSlides);

	function updateSliderUI(currentIndex) {
		const currentSlide = currentIndex + 1; 
		$number.text(currentSlide);

	}

	updateSliderUI($slider.slick('slickCurrentSlide'));

	$slider.on('afterChange', function (event, slick, currentSlide) {
		updateSliderUI(currentSlide);
	});

});

    $(".input-phone").mask("+7 (999) 999-99-99");

    {
        if ($(window).width() < 992) {
            //footer
            $(".footer__title").click(function () {
                $(this).toggleClass("active");
                $(this).next(".footer__content").slideToggle(200);
            });
        }
    }

    //btn scroll
    $(".scroll-up").click(function () {
		$("body, html").animate({
			scrollTop: 0
		}, 800);
		return false;
	});

     var topPos = $('.header').height(); 

      $(".scroll-down").click(function (){
                $('html, body').animate({
                    scrollTop: $(".catalog").offset().top - topPos
                }, 800);
            });

    //Попап менеджер FancyBox
    $(".fancybox").fancybox({
        autoFocus: false,
        backFocus: false,
    });

});


