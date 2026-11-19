$(document).ready(function() {

	if(localStorage.getItem("yugtoys_show_cookies") === null || localStorage.getItem("yugtoys_show_cookies") === 'null'){
		$('.cookies-modal').show();
		$('.cookies-modal .cookie-policy-button, .cookies-modal .yes-cookie').click(function(){
			$('.cookies-modal').hide();
			localStorage.setItem('yugtoys_show_cookies', true);
		});
		$('.cookies-modal .no-cookie').click(function(){
			window.location.href = 'https://ya.ru/';
		});
	}
	
	$('.fancybox-contact-manager').click(function(e){
		e.preventDefault();
		var title_prod = $(e.target).parents('[product_id]').eq(0).find('.title-catalog').text() || $(e.target).parents('[product_id]').eq(0).find('.title-card').text();
		$('#contact_manager').find('[name="PRODUCT"]').val(title_prod);
		$.fancybox.open('#contact_manager');
	});
	
	$('.fancybox-one-click').click(function(e){
		e.preventDefault();
		var title_prod = $(e.target).parents('[product_id]').eq(0).find('.title-catalog').text();
		$('#opt_price').find('[name="PRODUCT"]').val(title_prod);
		$.fancybox.open('#opt_price');
	});
	
	$('.fancybox-create-order-retail').click(function(e){
		e.preventDefault();
		$.fancybox.open('#create-order-retail');
	});
	
	$('.js-create-order-retail').submit(function(e){
		e.preventDefault();
		var $form = $(this);
		var data = new FormData($form[0]);
		
		data.append("action", "create-order-retail");
		
		$form.find('.btn-main').prop('disabled', true).attr('disabled', 'disabled');
		$.ajax({
			method: 'POST',
			type: "POST",
			dataType: 'json',
			url: "/local/tools/ajax.php",
			data: data,
			processData: false,
			contentType: false,
			cache: false,
			success: function(reply){
				$form.find('.btn-main').prop('disabled', false).removeAttr('disabled');
				if(reply.status == 'error'){
					console.log(reply);
					alert(reply.msg);
				}
				else{
					$.fancybox.close();
					location.href = "/thanks/";
				}
			},
			error: function(e){
				$form.find('.btn-main').prop('disabled', false).removeAttr('disabled');
				console.log(e);
			}
		});
	});
	
	
	$('.js-sort button').click(function(e){
		e.preventDefault();
		$.cookie('catalog-sort', $(this).attr('sort'), { expires: 1, path: '/' });
		if($.cookie('catalog-order') == null || $.cookie('catalog-order') == 'DESC'){
			$.cookie('catalog-order', 'ASC', { expires: 1, path: '/' });
		}
		else{
			$.cookie('catalog-order', 'DESC', { expires: 1, path: '/' });
		}
		location.reload();
	});
	
	$('.js-form').submit(function(e){
		e.preventDefault();
		var $form = $(this);
		var form_name = $form.find('[name="form-name"]').val();
		var data = new FormData($form[0]);
		
		data.append("action", $form.find('[name="action"]').val());
		
		$.ajax({
			method: 'POST',
			type: "POST",
			dataType: 'json',
			url: "/local/tools/ajax.php",
			data: data,
			processData: false,
			contentType: false,
			cache: false,
			success: function(reply){
				console.log(reply);
				if(form_name == 'registration'){
					if(reply.status == 'error'){
						$form.find('.fv-msg-block').html(reply.msg).addClass('error');
					}
					else{
						$form.find('.fv-msg-block').html(reply.msg).removeClass('error');
						$form[0].reset();
    					/*
						setTimeout(function(){
							location.href = "/thanks/";
						}, 3000); */
						$.fancybox.open('#modal-success');
					}
				}
				else if(form_name == 'login_lk'){
					if(reply.status == 'error'){
						alert(reply.msg);
					}
					else{
						location.href = "/personal/";
					}
				}
				else{
					//window.location.reload();
					location.href = "/thanks/";
				}
			},
			error: function(e){
				console.log(e);
			}
		});
	});
	
	$(document).on('click', '.js-buy-product', function(e){
		e.preventDefault();
		var   that = this
			, $product = $(this).parents('[product_id]')
			, product_id = $product.attr('product_id')
			, quantity = parseInt($product.find('.quantity-inp').val())
		;
		
		if($('.offersblock_tree').length){
			product_id = PRODUCT_OFFERS_CURRENT;
		}
		
		$.ajax({
			url: '/local/tools/ajax.php',
			method: 'GET',
			data: {
				action: 'buy-product',
				product_id: product_id,
				quantity: quantity,
			},
			success: function(reply){
				if(reply.status == 'error'){
					console.log(reply);
					alert(reply.msg);
				}
				else{
					$(that).addClass('in_basked').html('В КОРЗИНЕ');
					
					BX.onCustomEvent('OnBasketChange');
				}
			}
		});
	});
	
	$('.js-followers-btn').click(function(e){
		e.preventDefault();
		var   $that = $(this)
			, action = '';
		if($(this).hasClass('active')){
			action = 'followers-delete';
		}
		else{
			action = 'followers-add';
		}
		$.ajax({
			url: '/local/tools/ajax.php',
			method: 'GET',
			data: {
				action: action,
				product_id: $that.attr('product_id')
			},
			success: function(reply){
				if(action == 'followers-delete') $that.removeClass('active');
				else if(action == 'followers-add') $that.addClass('active');
				
				BX.onCustomEvent('OnBasketChange');
			}
		});
	});
	$('.js-unsubscribe-all').click(function(e){
		e.preventDefault();
		$.ajax({
			url: '/local/tools/ajax.php',
			method: 'GET',
			data: {
				action: 'followers-reset',
			},
			success: function(reply){
				$('.js-followers-small').text(0);
				window.location.reload();
			}
		});
	});
	
	if (typeof ymaps !== 'undefined') ymaps.ready(init1); // API карт подключён только на главной и в контактах
	function init1() {
		
		if($('#map').length == 0) return;
		
		var myMap = new ymaps.Map('map', {
			center: [45.026916, 39.011758],
			zoom: 13	
		}, {
			searchControlProvider: 'yandex#search'
		});		


		myPlacemark1 = new ymaps.Placemark([45.026916, 39.011758], {
		    balloonContent: '<div class="baloon-content">ул. Новороссийская, 98</div>',
		}, {
			iconLayout: 'default#image',
			iconImageHref: template.url_theme + '/img/pin.svg',
			hideIconOnBalloonOpen: false,
			iconImageSize: [44, 58],
			iconImageOffset: [-22, -58],
			balloonOffset: [ 0, -58 ]
		});
		
		myPlacemark2 = new ymaps.Placemark([45.037923, 39.084566], {
		     balloonContent: '<div class="baloon-content">ул. Уральская, 97/1</div>',
		}, {
			iconLayout: 'default#image',
			iconImageHref: template.url_theme + '/img/pin.svg',
			hideIconOnBalloonOpen: false,
			iconImageSize: [44, 58],
			iconImageOffset: [-22, -58],
			balloonOffset: [ 0, -58 ]
		});

		myMap.controls.remove('searchControl');
		myMap.behaviors.disable('scrollZoom');
		myMap.geoObjects.add(myPlacemark1);
		myMap.geoObjects.add(myPlacemark2);
		
		$( ".item-location" ).click(function() {
				$(".item-location").removeClass("active");
				$(this).addClass("active");
			});

			$( ".location_1" ).click(function() {
				myPlacemark1.balloon.open();
				myMap.setCenter([45.026916, 39.011758]);
			});

			$( ".location_2" ).click(function() {
				myPlacemark2.balloon.open();
				myMap.setCenter([45.037923, 39.084566]);
			});
	}
	
	if (typeof ymaps !== 'undefined') ymaps.ready(init2); // API карт подключён только на главной и в контактах
	function init2() {
		
		if($('#map-contact1').length == 0) return;
		
		var myMap = new ymaps.Map('map-contact1', {
			center: [45.026916, 39.011758],
			zoom: 15	
		}, {
			searchControlProvider: 'yandex#search'
		});		


		myPlacemark1 = new ymaps.Placemark([45.026916, 39.011758], {
		}, {
			iconLayout: 'default#image',
			iconImageHref: template.url_theme + '/img/pin.svg',
			hideIconOnBalloonOpen: false,
			iconImageSize: [33, 43],
			iconImageOffset: [-16, -43],
			balloonOffset: [ 0, -43 ]
		});

		myMap.controls.remove('searchControl');
		myMap.behaviors.disable('scrollZoom');
		myMap.geoObjects.add(myPlacemark1);
	}
	
	if (typeof ymaps !== 'undefined') ymaps.ready(init3); // API карт подключён только на главной и в контактах
	function init3() {
		
		if($('#map-contact2').length == 0) return;
		
		var myMap = new ymaps.Map('map-contact2', {
			center: [45.039439, 39.082500],
			zoom: 15	
		}, {
			searchControlProvider: 'yandex#search'
		});		


		myPlacemark1 = new ymaps.Placemark([45.039439, 39.082500], {
		}, {
			iconLayout: 'default#image',
			iconImageHref: template.url_theme + '/img/pin.svg',
			hideIconOnBalloonOpen: false,
			iconImageSize: [33, 43],
			iconImageOffset: [-16, -43],
			balloonOffset: [ 0, -43 ]
		});

		myMap.controls.remove('searchControl');
		myMap.behaviors.disable('scrollZoom');
		myMap.geoObjects.add(myPlacemark1);
	}
});