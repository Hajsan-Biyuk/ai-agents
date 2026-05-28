(function($){
	
	$('.fv-get-presentation').click(function(e){
		e.preventDefault();
		var product_id = $(this).attr('product_id');
		$.ajax({
			type: 'GET',
			url: template.url_ajax,
			// dataType: 'binary',
			// xhrFields: {
				// 'responseType': 'blob'
			// },
			cache: false,
			data: {
				action: 'ajax_fn',
				param: {
					action: 'get-presentation',
					product_id: product_id,
				},
			},
			success: function(reply){
				// console.log(reply);
				var link = document.createElement('a');
				link.href = reply + '?v=' + Math.random();
				link.target = '_blank';
				link.click();
			}
		});
	});

	$(".fancybox-rutube").fancybox({
        type: 'iframe',
        onComplete: function(){
            $('#fancybox-frame').load(function(){
                $.fancybox.hideActivity();
            });
            $.fancybox.showActivity();
        },		
    });

		$('.slider-for-tabs').slick({
			arrows: false,
			dots: false,
			infinite: false,
			slidesToShow: 1,
			slidesToScroll: 1,
			asNavFor: '.slider-nav-tabs',
			touchThreshold: 1000,
			prevArrow: '<div class="slick-prev slick-arrow"><i class="fal fa-chevron-left"></i><div/>',
			nextArrow: '<div class="slick-next slick-arrow"><i class="fal fa-chevron-right"></i><div/>',
		});
	
		$('.slider-nav-tabs').slick({
			arrows: true,
			dots: false,
			infinite: false,
			slidesToShow: 3,
			slidesToScroll: 1,
			asNavFor: '.slider-for-tabs',
			touchThreshold: 1000,
			focusOnSelect: true,
			prevArrow: '<div class="slick-prev slick-arrow"><i class="fal fa-chevron-left"></i><div/>',
			nextArrow: '<div class="slick-next slick-arrow"><i class="fal fa-chevron-right"></i><div/>',
			responsive: [
				{
					breakpoint: 480,
					settings: {
						slidesToShow: 3,
					}
				}
			]
		});
		

		$('.card-tabs.best__offer .best__offer-nav > a').click(function(){
			$('.slider-nav-tabs').slick('reinit');
			$('.slider-for-tabs').slick('reinit');
		});







	
})(jQuery);