(function($) {
$(function() { 
	$('.OpenMenu').click(
		function() {
			$('.PopupMenu').slideDown(60);
			return false;
		});

	$('.CloseMenu').click(
		function() {
			$('.CloseBlock').slideUp(220);
			return false;
		}); 
	$('.SearchPopup').click(
		function() {
			$('.PopupSearch').slideDown(60);
			return false;
		});
 
	$('.SearchClose').click(
		function() {
			$('.CloseSearch').slideUp(220);
			return false;
		});
	$('.SelectStyle, .Delivery').styler(); 
	$('ul.Links').on('click', 'li:not(.Active)', function() {
		$(this)
			.addClass('Active').siblings().removeClass('Active')
			.closest('div.TabsInfo').find('div.BlockBox').removeClass('Active').eq($(this).index()).addClass('Active');
	});
	$('ul.Links').on('click', 'li:not(.Active)', function() {
		$(this)
			.addClass('Active').siblings().removeClass('Active')
			.closest('div.PropsTabs').find('div.Box').removeClass('Active').eq($(this).index()).addClass('Active');
	});
 $("#Banner").owlCarousel({
		items: 1,
 slideSpeed: 2000,
 nav: true,
 autoplay: true, 
 dots: false,
 loop: true,
 });
 $("#GalleryDetail").owlCarousel(
	{
		items: 1,
 slideSpeed: 2000,
 nav: true,
 autoplay: false, 
 dots: false,
 loop: true,
	});
	$('#CarouselSale').owlCarousel(
	{ 
		items: 4,
 slideSpeed: 2000,
 nav: true,
 autoplay: false, 
 dots: true,
 loop: true,
		responsive:{
			0:{
				items:1,
				nav:true
			},
			768:{
				items:3,
			},
			1000:{
				items:3,
			},
			1200:{
				items:4,
			}
		}
	});
	$('#CarouselRecommend').owlCarousel(
	{
		items: 4,
 slideSpeed: 2000,
 nav: true,
 autoplay: false, 
 dots: true,
 loop: true,
		responsive:{
			0:{
				items:1,
				nav:true
			},
			768:{
				items:3,
			},
			1000:{
				items:3,
			},
			1200:{
				items:4,
			}
		}
	});
	$('#CarouselHit').owlCarousel(
	{
		items: 4,
 slideSpeed: 2000,
 nav: true,
 autoplay: false, 
 dots: true,
 loop: true,
		responsive:{
			0:{
				items:1,
				nav:true
			},
			768:{
				items:3,
			},
			1000:{
				items:3,
			},
			1200:{
				items:4,
			}
		}
	});
	$('#CarouselNew').owlCarousel(
	{
		items: 4,
 slideSpeed: 2000,
 nav: true,
 autoplay: false, 
 dots: true,
 loop: true,
		responsive:{
			0:{
				items:1,
				nav:true
			},
			768:{
				items:3,
			},
			1000:{
				items:3,
			},
			1200:{
				items:4,
			}
		}
	});
jQuery.browser = {};
jQuery.browser.mozilla=/mozilla/.test(navigator.userAgent.toLowerCase())&&!/webkit/.test(navigator.userAgent.toLowerCase());
jQuery.browser.webkit=/webkit/.test(navigator.userAgent.toLowerCase());
jQuery.browser.opera=/opera/.test(navigator.userAgent.toLowerCase());
jQuery.browser.msie=/msie/.test(navigator.userAgent.toLowerCase());
})
})(jQuery)

