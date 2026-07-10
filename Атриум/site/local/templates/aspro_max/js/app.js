$(function(){

    /* Magnific - modal */

    $('.call-modal').magnificPopup({
        fixedContentPos:true,
        fixedBgPos:true,
        overflowY:'scroll',
        preloader:false,
        midClick:true,
        mainClass:'my-mfp-zoom-in',
        closeMarkup:'',
        removalDelay:300,
    });

    $('.section-modal__close').on('click', function(){
        $.magnificPopup.close();
    });

    /* Slick - card slider */

    $('.card-img__main').slick({
        asNavFor:'.card-img__thumbs',
        slidesToShow:1,
        slidesToScroll:1,
        arrows:false,
        fade:true,
        draggable:false,
    });

    $('.card-img__thumbs').slick({
        asNavFor:'.card-img__main',
        slidesToShow:6,
        slidesToScroll:1,
        arrows:false,
        focusOnSelect:true,
        touchThreshold:100,
        responsive: [
            {
                breakpoint: 1280,
                settings: {
                    slidesToShow: 5,
                    slidesToScroll: 1,
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 4,
                    slidesToScroll: 1,
                }
            },
            {
                breakpoint: 400,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                }
            },
        ]
    });

    /* card tabs */

    $(document).on('click', '.card-tab__head__item', function(){
        let id = $(this).data('card');
        $('.card-tab__body__item').hide();
        $('.card-tab__body__item[data-card="' + id + '"]').show();
        $('.card-tab__head__item').removeClass('card-tab__head__item--selected');
        $(this).addClass('card-tab__head__item--selected');
    });

    /* side toggles */

    $(document).on('click', '.iq-sideClickTarget', function(){
        $(this).toggleClass('iq-side-item__head--active')
        $(this).next().slideToggle(500)
    })

    /* catalog tabs */

    $(document).on('click', '.iq-catalog-head__toggle__item', function(){
        let id = $(this).data('iq-catalog');
        $('.iq-catalog').hide();
        $('.iq-catalog[data-iq-catalog="' + id + '"]').show();
        $('.iq-catalog-head__toggle__item').removeClass('iq-catalog-head__toggle__item--selected');
        $(this).addClass('iq-catalog-head__toggle__item--selected');
    })

    /* catalog mobile side */

    $(document).on('click', '.iq-catalog-side-toggle', function() {
        $('.iq-side').toggleClass('iq-side--open');
        $('body').toggleClass('iq-overflow');
    });

    $(document).on('click', '.iq-side__mobile', function() {
        $('.iq-side').removeClass('iq-side--open');
        $('body').removeClass('iq-overflow');
    });

    /* catalog item gallery */

    $(document).on("mouseenter", ".iq-catalog-item-gallery__item", function () {
        $(this).siblings().removeClass("iq-catalog-item-gallery__item--active");
        $(this).addClass("iq-catalog-item-gallery__item--active");
    });

    $(document).on("mouseleave", ".iq-catalog-item__img", function () {
        const $elements = $(this).find(".iq-catalog-item-gallery__item");
        if ($elements.length) {
            $elements.removeClass("iq-catalog-item-gallery__item--active");
            $($elements[0]).addClass("iq-catalog-item-gallery__item--active");
        }
    });

    /* letter catalog */

    $('.letter-dir__grid__item').on({
        mouseenter:function(){
            $(this).find('.iq-letter').append('<div class="iq-letter-arrow"></div>')
            $(this).find('.iq-letter-arrow').show()
            $(this).find('.iq-letter-menu').show()
            $(this).find('.iq-letter-menu').position({
                my: 'left bottom',
                at: 'left top',
                of: $(this),
            });
        },
        mouseleave:function(){
            $(this).find('.iq-letter-arrow').remove();
            $(this).find('.iq-letter-menu').hide();
        },
        touchstart:function(){
            $(this).off('mouseenter');
        }
    }); $('body').css({'overflow-x':'hidden'})

    /* Chosen */

    $(".chosen-select").chosen()

    $('.chosen-select-clear').on('click', function(){
        $(".chosen-select").val('').trigger('chosen:updated');
        console.log('click')
    })
/*
    AbstractChosen.browser_is_supported = function() {
        if (window.navigator.appName === "Microsoft Internet Explorer") {
            return document.documentMode >= 8;
        }
        if (/iP(od|hone)/i.test(window.navigator.userAgent)) {
            return false;
        }
        if (/Android/i.test(window.navigator.userAgent)) {
            if (/Mobile/i.test(window.navigator.userAgent)) {
                return false;
            }
        }
        return true;
    }; */



$('.letter-dir__grid__item').on({mouseenter:function(){
$(this).find('.iq-letter').append('<div class="iq-letter-arrow"></div>')
$(this).find('.iq-letter-arrow').show()
$(this).find('.iq-letter-menu').show()
$(this).find('.iq-letter-menu').position({
my: 'left top',
at: 'left bottom',
of: $(this),
});
},
mouseleave:function(){
$(this).find('.iq-letter-arrow').remove();
$(this).find('.iq-letter-menu').hide();
},
touchstart:function(){
$(this).off('mouseenter');
}
}); $('body').css({'overflow-x':'hidden'})


})

  $(function(){

    // $('#title-search-input').bind('keyup', function(e) {
    //     var v = $(this).val();
    //     if(v == '') {
    //         $('.label-for-search').removeClass('disable_label');
    //     } else {
    //         $('.label-for-search').addClass('disable_label');
    //     }
    // });
 	// $(".typing").typed({
    //     strings: ["АСБл-10 3х240 мс", "Поиск по сайту"],
    //     attr: "placeholder",
    //     typeSpeed: 50,
    //     startDelay: 7000,
    //     backDelay: 7000,
    //     showCursor: true,
	// 	loop: true,
	// 	loopCount: 99,
    //     shuffle: true
    // });
    var typed = new Typed('#typed', {
        stringsElement: '#typed-strings',
        typeSpeed: 70,
        backSpeed: 20,
        startDelay: 2000,
        loop: true,
        loopCount: Infinity,
        // onBegin: function(self) {
        //   prettyLog('onBegin ' + self);
        // },
        // onComplete: function(self) {
        //   prettyLog('onComplete ' + self);
        // },
        // preStringTyped: function(pos, self) {
        //   prettyLog('preStringTyped ' + pos + ' ' + self);
        // },
        // onStringTyped: function(pos, self) {
        //   prettyLog('onStringTyped ' + pos + ' ' + self);
        // },
        // onLastStringBackspaced: function(self) {
        //   prettyLog('onLastStringBackspaced ' + self);
        // },
        // onTypingPaused: function(pos, self) {
        //   prettyLog('onTypingPaused ' + pos + ' ' + self);
        // },
        // onTypingResumed: function(pos, self) {
        //   prettyLog('onTypingResumed ' + pos + ' ' + self);
        // },
        // onReset: function(self) {
        //   prettyLog('onReset ' + self);
        // },
        // onStop: function(pos, self) {
        //   prettyLog('onStop ' + pos + ' ' + self);
        // },
        onStart: function(pos, self) {
        //   prettyLog('onStart ' + pos + ' ' + self);
            // console.log('test');
        },
        // onDestroy: function(self) {
        //   prettyLog('onDestroy ' + self);
        // }
      });   
      
      //typed.start();
});

document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('title-search-input');
    const label = document.getElementById('typed');
// console.log('test');
    function hideLabel() {
        label.classList.add('disable_label');
    }

    function showLabel() {
        if (input.value.trim() === '') {
            label.classList.remove('disable_label');
        }
    }    

    input.addEventListener('focus', hideLabel);
    input.addEventListener('input', hideLabel);
    input.addEventListener('mouseover', hideLabel);

    input.addEventListener('blur', function () {
        if (input.value.trim() !== '') {
            label.classList.add('disable_label');
        } else {
            label.classList.remove('disable_label');
        }
    });

    input.addEventListener('mouseout', showLabel);


    if (input.value.trim() !== '') {
        label.classList.add('disable_label');
    }
});

/*! jQuery UI - v1.13.1 - 2022-07-13
* http://jqueryui.com
* Includes: position.js
* Copyright jQuery Foundation and other contributors; Licensed MIT */

!function(t){"use strict";"function"==typeof define&&define.amd?define(["jquery"],t):t(jQuery)}(function(b){"use strict";b.ui=b.ui||{};var e,x,T,l,n,f,s,h,o;b.ui.version="1.13.1";function L(t,i,o){return[parseFloat(t[0])*(h.test(t[0])?i/100:1),parseFloat(t[1])*(h.test(t[1])?o/100:1)]}function P(t,i){return parseInt(b.css(t,i),10)||0}function r(t){return null!=t&&t===t.window}x=Math.max,T=Math.abs,l=/left|center|right/,n=/top|center|bottom/,f=/[\+\-]\d+(\.[\d]+)?%?/,s=/^\w+/,h=/%$/,o=b.fn.position,b.position={scrollbarWidth:function(){if(void 0!==e)return e;var t,i=b("<div style='display:block;position:absolute;width:200px;height:200px;overflow:hidden;'><div style='height:300px;width:auto;'></div></div>"),o=i.children()[0];return b("body").append(i),t=o.offsetWidth,i.css("overflow","scroll"),t===(o=o.offsetWidth)&&(o=i[0].clientWidth),i.remove(),e=t-o},getScrollInfo:function(t){var i=t.isWindow||t.isDocument?"":t.element.css("overflow-x"),o=t.isWindow||t.isDocument?"":t.element.css("overflow-y"),i="scroll"===i||"auto"===i&&t.width<t.element[0].scrollWidth;return{width:"scroll"===o||"auto"===o&&t.height<t.element[0].scrollHeight?b.position.scrollbarWidth():0,height:i?b.position.scrollbarWidth():0}},getWithinInfo:function(t){var i=b(t||window),o=r(i[0]),e=!!i[0]&&9===i[0].nodeType;return{element:i,isWindow:o,isDocument:e,offset:!o&&!e?b(t).offset():{left:0,top:0},scrollLeft:i.scrollLeft(),scrollTop:i.scrollTop(),width:i.outerWidth(),height:i.outerHeight()}}},b.fn.position=function(c){if(!c||!c.of)return o.apply(this,arguments);var a,d,g,u,m,t,w="string"==typeof(c=b.extend({},c)).of?b(document).find(c.of):b(c.of),W=b.position.getWithinInfo(c.within),v=b.position.getScrollInfo(W),y=(c.collision||"flip").split(" "),H={},i=9===(t=(i=w)[0]).nodeType?{width:i.width(),height:i.height(),offset:{top:0,left:0}}:r(t)?{width:i.width(),height:i.height(),offset:{top:i.scrollTop(),left:i.scrollLeft()}}:t.preventDefault?{width:0,height:0,offset:{top:t.pageY,left:t.pageX}}:{width:i.outerWidth(),height:i.outerHeight(),offset:i.offset()};return w[0].preventDefault&&(c.at="left top"),d=i.width,g=i.height,m=b.extend({},u=i.offset),b.each(["my","at"],function(){var t,i,o=(c[this]||"").split(" ");(o=1===o.length?l.test(o[0])?o.concat(["center"]):n.test(o[0])?["center"].concat(o):["center","center"]:o)[0]=l.test(o[0])?o[0]:"center",o[1]=n.test(o[1])?o[1]:"center",t=f.exec(o[0]),i=f.exec(o[1]),H[this]=[t?t[0]:0,i?i[0]:0],c[this]=[s.exec(o[0])[0],s.exec(o[1])[0]]}),1===y.length&&(y[1]=y[0]),"right"===c.at[0]?m.left+=d:"center"===c.at[0]&&(m.left+=d/2),"bottom"===c.at[1]?m.top+=g:"center"===c.at[1]&&(m.top+=g/2),a=L(H.at,d,g),m.left+=a[0],m.top+=a[1],this.each(function(){var o,t,f=b(this),s=f.outerWidth(),h=f.outerHeight(),i=P(this,"marginLeft"),e=P(this,"marginTop"),l=s+i+P(this,"marginRight")+v.width,n=h+e+P(this,"marginBottom")+v.height,r=b.extend({},m),p=L(H.my,f.outerWidth(),f.outerHeight());"right"===c.my[0]?r.left-=s:"center"===c.my[0]&&(r.left-=s/2),"bottom"===c.my[1]?r.top-=h:"center"===c.my[1]&&(r.top-=h/2),r.left+=p[0],r.top+=p[1],o={marginLeft:i,marginTop:e},b.each(["left","top"],function(t,i){b.ui.position[y[t]]&&b.ui.position[y[t]][i](r,{targetWidth:d,targetHeight:g,elemWidth:s,elemHeight:h,collisionPosition:o,collisionWidth:l,collisionHeight:n,offset:[a[0]+p[0],a[1]+p[1]],my:c.my,at:c.at,within:W,elem:f})}),c.using&&(t=function(t){var i=u.left-r.left,o=i+d-s,e=u.top-r.top,l=e+g-h,n={target:{element:w,left:u.left,top:u.top,width:d,height:g},element:{element:f,left:r.left,top:r.top,width:s,height:h},horizontal:o<0?"left":0<i?"right":"center",vertical:l<0?"top":0<e?"bottom":"middle"};d<s&&T(i+o)<d&&(n.horizontal="center"),g<h&&T(e+l)<g&&(n.vertical="middle"),x(T(i),T(o))>x(T(e),T(l))?n.important="horizontal":n.important="vertical",c.using.call(this,t,n)}),f.offset(b.extend(r,{using:t}))})},b.ui.position={fit:{left:function(t,i){var o=i.within,e=o.isWindow?o.scrollLeft:o.offset.left,l=o.width,n=t.left-i.collisionPosition.marginLeft,f=e-n,s=n+i.collisionWidth-l-e;i.collisionWidth>l?0<f&&s<=0?(o=t.left+f+i.collisionWidth-l-e,t.left+=f-o):t.left=!(0<s&&f<=0)&&s<f?e+l-i.collisionWidth:e:0<f?t.left+=f:0<s?t.left-=s:t.left=x(t.left-n,t.left)},top:function(t,i){var o=i.within,e=o.isWindow?o.scrollTop:o.offset.top,l=i.within.height,n=t.top-i.collisionPosition.marginTop,f=e-n,s=n+i.collisionHeight-l-e;i.collisionHeight>l?0<f&&s<=0?(o=t.top+f+i.collisionHeight-l-e,t.top+=f-o):t.top=!(0<s&&f<=0)&&s<f?e+l-i.collisionHeight:e:0<f?t.top+=f:0<s?t.top-=s:t.top=x(t.top-n,t.top)}},flip:{left:function(t,i){var o=i.within,e=o.offset.left+o.scrollLeft,l=o.width,n=o.isWindow?o.scrollLeft:o.offset.left,f=t.left-i.collisionPosition.marginLeft,s=f-n,h=f+i.collisionWidth-l-n,r="left"===i.my[0]?-i.elemWidth:"right"===i.my[0]?i.elemWidth:0,o="left"===i.at[0]?i.targetWidth:"right"===i.at[0]?-i.targetWidth:0,f=-2*i.offset[0];s<0?((e=t.left+r+o+f+i.collisionWidth-l-e)<0||e<T(s))&&(t.left+=r+o+f):0<h&&(0<(n=t.left-i.collisionPosition.marginLeft+r+o+f-n)||T(n)<h)&&(t.left+=r+o+f)},top:function(t,i){var o=i.within,e=o.offset.top+o.scrollTop,l=o.height,n=o.isWindow?o.scrollTop:o.offset.top,f=t.top-i.collisionPosition.marginTop,s=f-n,h=f+i.collisionHeight-l-n,r="top"===i.my[1]?-i.elemHeight:"bottom"===i.my[1]?i.elemHeight:0,o="top"===i.at[1]?i.targetHeight:"bottom"===i.at[1]?-i.targetHeight:0,f=-2*i.offset[1];s<0?((e=t.top+r+o+f+i.collisionHeight-l-e)<0||e<T(s))&&(t.top+=r+o+f):0<h&&(0<(n=t.top-i.collisionPosition.marginTop+r+o+f-n)||T(n)<h)&&(t.top+=r+o+f)}},flipfit:{left:function(){b.ui.position.flip.left.apply(this,arguments),b.ui.position.fit.left.apply(this,arguments)},top:function(){b.ui.position.flip.top.apply(this,arguments),b.ui.position.fit.top.apply(this,arguments)}}};b.ui.position});