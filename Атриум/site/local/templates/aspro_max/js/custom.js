/*
You can use this file with your scripts.
It will not be overwritten when you upgrade solution.
*/


$(document).ready(function(){
	function atriumCleanCatalogPageUrl(rawUrl) {
		if (!rawUrl) {
			return '';
		}
		var clean = String(rawUrl).split('#')[0];
		clean = clean
			.replace(/[&?]ajax_get=[^&]*/g, '')
			.replace(/[&?]AJAX_REQUEST=[^&]*/g, '')
			.replace(/[&?]clear_cache=[^&]*/g, '')
			.replace(/[&?]v=[^&]*/g, '')
			.replace(/[&?]BLOCK=[^&]*/g, '');
		clean = clean.replace(/\?&/g, '?').replace(/&&+/g, '&').replace(/[?&]$/g, '');
		return clean.indexOf('PAGEN_') === -1 ? '' : clean;
	}

	$(document).ajaxSuccess(function (event, xhr, settings) {
		if (!settings || !document.querySelector('.catalog1 .js_append')) {
			return;
		}
		var data = settings.data;
		var isLoadMore = false;
		if (typeof data === 'string') {
			isLoadMore = data.indexOf('ajax_get=Y') !== -1 || data.indexOf('ajax_get%3DY') !== -1;
		} else if (data && data.ajax_get === 'Y') {
			isLoadMore = true;
		}
		if (!isLoadMore) {
			return;
		}
		var pageUrl = atriumCleanCatalogPageUrl(settings.url || '');
		if (!pageUrl) {
			return;
		}
		var current = window.location.pathname + window.location.search;
		if (pageUrl === current || !window.history || !history.pushState) {
			return;
		}
		history.pushState({ atriumCatalogPage: true }, '', pageUrl);
	});

	window.addEventListener('popstate', function () {
		if (document.querySelector('.catalog1 .js_append')) {
			window.location.reload();
		}
	});

	document.addEventListener('click', function(e) {
		var el = e.target && e.target.nodeType === 1 ? e.target : (e.target && e.target.parentElement);
		if (!el || !el.closest) {
			return;
		}
		var btn = el.closest('.ajax_load_btn');
		if (!btn || !btn.closest('.js-load-block')) {
			return;
		}

		var $btn = $(btn);
		var $block = $btn.closest('.js-load-block');
		var $nav = $btn.closest('.bottom_nav');
		var nextUrl = $nav.find('a.flex-next').attr('href') || $nav.find('.flex-next').attr('href');
		var $appendTo = $block.find('.js_append').first();
		if (!nextUrl || !$appendTo.length) {
			return;
		}

		e.preventDefault();
		e.stopImmediatePropagation();

		var $text = $btn.find('.more_text_ajax');
		if ($text.hasClass('loading')) {
			return;
		}

		$text.addClass('loading');
		$.ajax({
			url: nextUrl,
			data: {
				ajax_get: 'Y',
				AJAX_REQUEST: 'Y',
				BLOCK: $block.data('block') || 'assoc'
			},
			success: function(html) {
				var $wrap = $('<div>').append($.parseHTML(html, document, false));
				$wrap.find('.item_block').each(function() {
					$appendTo.append(this);
				});
				$appendTo.find('.wrap_nav, .bottom_nav_wrapper').remove();

				var $newNav = $wrap.find('.bottom_nav').first();
				if ($newNav.length && $newNav.find('.ajax_load_btn').length) {
					$nav.html($newNav.html());
				} else {
					$nav.remove();
				}

				$text.removeClass('loading');
				if (typeof sliceItemBlock === 'function') {
					sliceItemBlock();
				}
			},
			error: function() {
				$text.removeClass('loading');
			}
		});
	}, true);

	$(document).on('click', '.js-buy-product', function(e){
		if(!$(this).hasClass('js-buy-product')) return;
		e.preventDefault();
		
		var   $product = $(this).parents('[product_id]')
			, product_id = $product.attr('product_id')
			, quantity = parseInt($product.find('.quantity-block__num').val()) || 1
		;
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
					alert(reply.msg);
					console.log(reply);
				}
				else{
					BX.onCustomEvent('OnBasketChange');
				}
			}
		});
	});
	
	
	
	
	
	
	
	
	
	

	//Попап менеджер FancyBox
Fancybox.bind("[data-fancybox]", {
  Html: {
    videoAutoplay: true,
  },

});

/*complect*/
    $('.complect-card__tabs a').click(function(event) {
    event.preventDefault();
    $(this).parent().parent().find("li").removeClass('active');
    $(this).parent().addClass('active');
    $(".complect-card__pane").fadeOut(0);
    var selectTab = $(this).attr("href");
    $(selectTab).fadeIn(200);
  });

  $('.complect-card__checkbox').click(function() {
    $(this).toggleClass('checked');
  });


//move card tabs

$(".product-view--side-left .ordered-block.tabs-block").insertAfter(".section-content-wrapper");

  //main page

$('.list-features').each(function () {

        const $list = $(this);
        const $items = $list.children('.list-features__item');
        const limit = 9;

        if ($items.length > limit) {
            const hiddenCount = $items.length - limit;

            $items.slice(limit).hide();

            const $btn = $('<div class="btn-show-features">Все характеристики</div>');

            $btn.insertAfter($list);

            $btn.on('click', function (e) {
                e.preventDefault();
				
				$('.tab-content .tab-pane').not('#props').removeClass('active').removeClass('show');
				$('[href="#props"]').click();
				$('[href="#props"]').parent().click();
				
				var targetOffset = $('#props').offset().top;
				$('html, body').animate({scrollTop: targetOffset - 200}, 600);
				
                // if ($btn.hasClass('is-open')) {
                    // $items.slice(limit).slideUp(200);
                // } else {
                    // $items.slice(limit).slideDown(200);
                     // $btn.hide();
                // }
            });
        }
    });


    var $first = $('.unit-banner__image--first, .unit-banner__item--first');
    var $second = $('.unit-banner__image--second, .unit-banner__item--second');
    
    var switchInterval = 4000; 

    $first.addClass('is-active');

    setInterval(function() {
        if ($first.hasClass('is-active')) {
            $first.removeClass('is-active');
            $second.addClass('is-active');
        } else {
            $second.removeClass('is-active');
            $first.addClass('is-active');
        }
    }, switchInterval);


  		var austDay = new Date($(".countdown").attr("date-time"));
	$(".countdown").countdown({until: austDay, format: 'dHMS'});

  
document.querySelectorAll('.swiper--billbord').forEach(function(element) {
  var swiper = new Swiper(element, {
  
    loop: true,
    autoHeight: true,
    
navigation: {
      nextEl: element.querySelector('.swiper-button-next'),
      prevEl: element.querySelector('.swiper-button-prev')
    },

    speed: 1000,         
    autoplay: {
      delay: 4000,  
    },
  });
  element.querySelectorAll('.billbord-img--mobile').forEach(function(img) {
    var src = img.getAttribute('data-src') || img.getAttribute('src') || '';
    if (!src || src.indexOf('data:image') === 0) {
      img.remove();
      return;
    }
    var probe = new Image();
    probe.onload = function() {
      if (!probe.naturalWidth) {
        img.remove();
        swiper.updateAutoHeight(0);
        return;
      }
      img.setAttribute('src', src);
      img.classList.add('is-loaded');
      swiper.updateAutoHeight(0);
    };
    probe.onerror = function() {
      img.remove();
      swiper.updateAutoHeight(0);
    };
    probe.src = src;
  });
  swiper.updateAutoHeight(0);
});

  document.querySelectorAll('.swiper--catalog').forEach(function(element) {
  const wrapper = element.closest('.swiper-catalog-wrapper');

  var swiper = new Swiper(element, {
    slidesPerView: 6,
    spaceBetween: 10,
    navigation: {
      nextEl: wrapper.querySelector('.swiper-button-next'),
      prevEl: wrapper.querySelector('.swiper-button-prev')
    },
    breakpoints: {
      0: {
        slidesPerView: 1,
      },
	  768: {
        slidesPerView: 3,
      },
      993: {
        slidesPerView: 5,
      },
	  1400: {
        slidesPerView: 6,
      }
    }
  });
});

document.querySelectorAll('.swiper--vertical').forEach(function(element) {
  var swiper = new Swiper(element, {
    direction: 'vertical',  
    slidesPerView: "auto",   
	spaceBetween: 24, 
    slidesPerGroup: 1,     
    loop: true,        
    
    allowTouchMove: false, 

    speed: 10000,         
    autoplay: {
      delay: 0,  
      disableOnInteraction: false,
    },
    
breakpoints: {
      0: {
        spaceBetween: 10, 
      },
      1200: {
      spaceBetween: 24, 
      }
    }
  });
});


	
	if(localStorage.getItem("atriumceramic_show_cookies") === null || localStorage.getItem("atriumceramic_show_cookies") === 'null'){
		$('.cookies-modal').show();
		$('.cookies-modal .cookie-policy-button, .cookies-modal .yes-cookie').click(function(){
			$('.cookies-modal').hide();
			localStorage.setItem('atriumceramic_show_cookies', true);
		});
		$('.cookies-modal .no-cookie').click(function(){
			window.location.href = 'https://ya.ru/';
		});
	}
	
    $('*[data-param-form_id="CALLBACK"]').on('click',function(){
    setTimeout(function(){$('form[name="CALLBACK"] input[type="tel"]').focus();},250);
    });
    $('*[data-param-form_id="13"]').on('click',function(){
        setTimeout(function(){$('form[name="SIMPLE_FORM_13"] input[type="tel"]').focus();},250);
        });    
});


$(document).ready(function(){
    $('#ID_DELIVERY_ID_1').parent().parent().find('.bx-soa-pp-list-description').text('индивидуально');

if($('#ID_DELIVERY_ID_1').is(':checked')){
    setTimeout(function(){
      $('.bx-soa-cart-d .bx-soa-price-free:contains("бесплатно")').html('Индивидуально');
      $('#ID_DELIVERY_ID_1').parent().parent().find('.bx-soa-pp-list-description').text('Индивидуально');
    },100)
console.log('script.js in component order and custom.js');
}
});
$(document).ready(function(){
$('footer .compact_arrow').on('click',function(e){
    e.preventDefault();
    console.log('preventDefault');
});
});


$(document).ready(function() {
    $('.product-share-new-url-icon,#PRODUCT_SHARE_URL_5vHZx').click(function() {
      var text = $('#PRODUCT_SHARE_URL_5vHZx').val();
      var input = $('<textarea style>').val(text).appendTo('body').select();
      document.execCommand('copy');
      input.remove();
      $('#PRODUCT_SHARE_URL_5vHZx').val('Скопированно!');
      setTimeout(() => {
          $('#PRODUCT_SHARE_URL_5vHZx').val(text);
      }, 1050);
    });
  
  
    $('.open_share_product').on('click',function(){
      $('#share_product_modal').fadeIn();
    });
    $('#share_product_modal .close,#share_product_modal .overlay_chareproduct').on('click',function(e){
        e.preventDefault();
        $('#share_product_modal').fadeOut();
    });

    
      $(' input[data-sid="CLIENT_NAME"], input[data-sid="NAME"], input[data-sid="FIO"]', ).on('input',function(){
        this.value = this.value.replace(/[^а-яё\s]/gi, '');
      });
  

  });
  /*
$(document).ready(function() {
  
  //$('#header-fixed-margin').addClass('fixed_header_custom');
});
$(window).scroll(function(){
  if($(window).width()>768){
  if($(window).scrollTop()>1000){
    $('#header>.header-v11').addClass('fixed_header_custom');
    setTimeout(() => {
      $('#header>.header-v11').addClass('animate_top');
    }, 150);
  }
  if($(window).scrollTop()<800){
    $('#header>.header-v11').removeClass('fixed_header_custom','animate_top');
  }
  }
});*/



// Этот код можно добавить в конец вашего файла
document.addEventListener('DOMContentLoaded', function() {
  console.log('DOM загружен, инициализируем обработчик каталога...');
  
  // Используем делегирование событий на document
  document.addEventListener('click', function(e) {
    const target = e.target;
    
    // Проверяем, была ли нажата наша кнопка
    if (target.closest('.iq-new-btn-catalog') && window.innerWidth <= 768) {
      console.log('Кнопка "Весь каталог" нажата');
      e.preventDefault();
      e.stopPropagation();
      
      const gridContainer = document.querySelector('.CATALOG_SECTIONS .iq-popular-grid');
      const showMoreBtn = target.closest('.iq-new-btn-catalog');
      
      if (!gridContainer) {
        console.error('Контейнер каталога не найден');
        return;
      }
      
      // Показываем все элементы
      const items = gridContainer.querySelectorAll('.iq-popular-item');
      console.log('Найдено элементов:', items.length);
      
      items.forEach((item, index) => {
        if (index >= 2) {
          item.style.display = 'block';
          item.style.visibility = 'visible';
          item.style.opacity = '1';
        }
      });
      
      // Меняем кнопку
      showMoreBtn.textContent = 'Все товары показаны';
      showMoreBtn.style.opacity = '0.6';
      showMoreBtn.style.cursor = 'default';
      showMoreBtn.classList.add('hidden_shows');
      
      // Можно скрыть кнопку через 2 секунды
      setTimeout(() => {
        showMoreBtn.style.display = 'none';
      }, 2000);
    }
  });
  
  // Инициализация при загрузке
  setTimeout(initCatalog, 1000);
  
  function initCatalog() {
    if (window.innerWidth <= 768) {
      const gridContainer = document.querySelector('.CATALOG_SECTIONS .iq-popular-grid');
      if (gridContainer) {
        const items = gridContainer.querySelectorAll('.iq-popular-item');
        if (items.length > 2) {
          // Скрываем элементы начиная с 3-го
          items.forEach((item, index) => {
            if (index >= 2) {
              item.style.display = 'none';
            }
          });
        }
      }
    }
  }

	$(document).on('click', '.fixed-buttons__toggle', function () {
		var $wrap = $(this).closest('.fixed-buttons');
		var $extra = $wrap.find('.fixed-buttons__extra');
		var open = !$wrap.hasClass('is-open');
		$wrap.toggleClass('is-open', open);
		$(this).attr('aria-expanded', open ? 'true' : 'false');
		$extra.attr('aria-hidden', open ? 'false' : 'true');
	});
});

// Вход по телефону: у клиентов без e-mail логин = 7XXXXXXXXXX, приводим введённый номер к этому виду.
(function () {
	function phoneLogin(v) {
		if (!/^[\d\s()+\-]{10,}$/.test(v.trim())) return null;
		var d = v.replace(/\D+/g, '');
		if (d.length === 11 && d[0] === '8') d = '7' + d.slice(1);
		if (d.length === 10) d = '7' + d;
		return d.length === 11 && d[0] === '7' ? d : null;
	}
	function fix(input) {
		var d = phoneLogin(input.value || '');
		if (d) input.value = d;
	}
	document.addEventListener('submit', function (e) {
		var input = e.target && e.target.querySelector && e.target.querySelector('input[name="USER_LOGIN"]');
		if (input) fix(input);
	}, true);
	document.addEventListener('change', function (e) {
		if (e.target && e.target.name === 'USER_LOGIN') fix(e.target);
	}, true);
})();
