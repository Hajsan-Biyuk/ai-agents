/*
You can use this file with your scripts.
It will not be overwritten when you upgrade solution.
*/


$(document).ready(function(){
	
	if(localStorage.getItem("atriumceramic_opt_show_cookies") === null || localStorage.getItem("atriumceramic_opt_show_cookies") === 'null'){
		$('.cookies-modal').show();
		$('.cookies-modal .cookie-policy-button, .cookies-modal .yes-cookie').click(function(){
			$('.cookies-modal').hide();
			localStorage.setItem('atriumceramic_opt_show_cookies', true);
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
