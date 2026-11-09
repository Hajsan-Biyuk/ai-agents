$(function () {
  var $header = $(".hp-header");
  var $menu = $(".hp-menu-mobile");
  var $overlay = $(".hp-menu-overlay");
  var $sandwich = $(".hp-header__sandwich");

  function closeMenu() {
    $menu.slideUp(200);
    $sandwich.removeClass("is-active").attr("aria-expanded", "false");
    $("body").removeClass("hp-menu-open");
    $overlay.fadeOut(200);
  }

  $(window).on("scroll", function () {
    $header.toggleClass("is-stuck", $(this).scrollTop() > 0);
  });

  $sandwich.on("click", function () {
    if ($menu.is(":hidden")) {
      $menu.slideDown(200);
      $sandwich.addClass("is-active").attr("aria-expanded", "true");
      $("body").addClass("hp-menu-open");
      $overlay.fadeIn(200);
    } else {
      closeMenu();
    }
  });

  $overlay.on("click", closeMenu);

  $(document).on("click", ".hp-nav__drop-toggle", function (e) {
    if ($(window).width() <= 992) {
      return;
    }
    var $item = $(this).closest(".hp-nav__item--drop");
    if (!$item.find(".hp-nav__drop").is(":visible") || e.target === this) {
      e.preventDefault();
      $item.toggleClass("is-open").siblings().removeClass("is-open");
    }
  });

  // Кнопки «заявка» (ссылки на #consult) открывают модальное окно с формой
  var modal = document.getElementById("hp-modal-consult");
  if (modal) {
    $(modal).find(".hp-modal__close").on("click", function () { modal.close(); });
    modal.addEventListener("click", function (e) {
      if (e.target === modal) {
        modal.close();
      }
    });
  }

  $(document).on("click", "a[href^='#']", function (e) {
    var id = $(this).attr("href");
    if (!id || id.length < 2) {
      return;
    }
    if (id === "#consult" && modal && modal.showModal) {
      e.preventDefault();
      closeMenu();
      modal.showModal();
      return;
    }
    var $target = $(id);
    if (!$target.length) {
      return;
    }
    e.preventDefault();
    var top = $target.offset().top - $header.outerHeight() + 1;
    $("html, body").animate({ scrollTop: top }, 600);
    closeMenu();
    $(".hp-nav__item--drop").removeClass("is-open");
  });

  // Видео о полигоне: клик по блоку — плеер, повторный клик — пауза и возврат к обложке
  $(".hp-shot[data-video]").each(function () {
    var $shot = $(this), $box = $shot.find(".hp-vplayer"), v = $box.find("video")[0];
    var $seek = $box.find(".hp-vplayer__seek"), $vol = $box.find(".hp-vplayer__vol");
    function show(on) {
      $shot.toggleClass("is-video", on);
      $box.prop("hidden", !on);
    }
    function play() {
      show(true);
      var p = v.play();
      if (p && p.catch) {
        p.catch(function () { show(false); });
      }
    }
    $shot.on("click", function (e) {
      if ($(e.target).closest(".hp-vplayer__bar").length) {
        return;
      }
      if (v.paused) {
        play();
      } else {
        v.pause();
        show(false);
      }
    });
    $box.find(".hp-vplayer__toggle").on("click", function () { v.paused ? play() : v.pause(); });
    $box.find(".hp-vplayer__mute").on("click", function () { v.muted = !v.muted; });
    $seek.on("input", function () { v.currentTime = this.value; });
    $vol.on("input", function () { v.volume = this.value; v.muted = false; });
    $(v).on("loadedmetadata durationchange timeupdate", function () {
        if (isFinite(v.duration)) { $seek.attr("max", v.duration); }
        $seek.val(v.currentTime);
      })
      .on("play pause", function () { $box.toggleClass("is-playing", !v.paused); })
      .on("volumechange", function () { $box.toggleClass("is-muted", v.muted || v.volume === 0); })
      .on("ended", function () { v.currentTime = 0; show(false); });
  });

  $(".hp-slider").each(function () {
    var $root = $(this);
    var $track = $root.find(".hp-slider__track");
    $root.on("click", ".hp-slider__btn", function () {
      var $items = $track.children();
      if ($items.length < 2) {
        return;
      }
      if ($(this).hasClass("hp-slider__btn--next")) {
        $track.append($items.first());
      } else {
        $track.prepend($items.last());
      }
    });
  });

  // Вопрос-ответ: открыт один пункт (по умолчанию первый), повторный клик закрывает его
  function faqSet($item, open) {
    var $answer = $item.find(".hp-faq__a");
    $item.toggleClass("is-open", open).find(".hp-faq__q").attr("aria-expanded", open ? "true" : "false");
    if ($.trim($answer.text()).length) {
      $answer.stop(true, true)[open ? "slideDown" : "slideUp"](200);
    }
  }

  $(document).on("click", ".hp-faq__q", function () {
    var $item = $(this).closest(".hp-faq__item");
    var open = !$item.hasClass("is-open");
    $item.siblings(".is-open").each(function () { faqSet($(this), false); });
    faqSet($item, open);
  });

  $(".hp-faq__list").each(function () {
    var $first = $(this).children(".hp-faq__item").first();
    $first.addClass("is-open").find(".hp-faq__q").attr("aria-expanded", "true");
    $first.find(".hp-faq__a").removeAttr("hidden").show();
  });

  function footerMode() {
    var mobile = $(window).width() <= 992;
    var $titles = $(".hp-footer__title");
    var $content = $(".hp-footer__content");
    if (!mobile) {
      $content.show();
      $titles.removeClass("is-open").attr("aria-expanded", "true");
    } else {
      $content.hide();
      $titles.removeClass("is-open").attr("aria-expanded", "false");
    }
  }

  footerMode();
  $(window).on("resize", footerMode);

  $(document).on("click", ".hp-footer__title", function () {
    if ($(window).width() > 992) {
      return;
    }
    var $title = $(this);
    var $content = $title.next(".hp-footer__content");
    $title.toggleClass("is-open");
    $title.attr("aria-expanded", $title.hasClass("is-open") ? "true" : "false");
    $content.stop(true, true).slideToggle(200);
  });

  // Заявка идёт через Contact Form 7: после отправки — страница «Спасибо»
  document.addEventListener("wpcf7mailsent", function () {
    window.location.href = "/spasibo/";
  });

  // Маска телефона +7 (999) 999-99-99
  $(document).on("input", "input.input-phone", function () {
    var d = this.value.replace(/\D/g, "");
    if (d.charAt(0) === "7" || d.charAt(0) === "8") {
      d = d.slice(1);
    }
    d = d.slice(0, 10);
    var out = "";
    if (d.length) {
      out = "+7 (" + d.slice(0, 3);
      if (d.length >= 3) { out += ")"; }
      if (d.length > 3) { out += " " + d.slice(3, 6); }
      if (d.length > 6) { out += "-" + d.slice(6, 8); }
      if (d.length > 8) { out += "-" + d.slice(8, 10); }
    }
    this.value = out;
  });

  // На странице услуги в списке направлений выбрана текущая услуга
  $(".hp-form[data-selected]").each(function () {
    var wanted = $.trim($(this).attr("data-selected"));
    if (!wanted) {
      return;
    }
    $(this).find("select[name='training_direction'] option").each(function () {
      if ($.trim($(this).text()) === wanted) {
        $(this).prop("selected", true);
      }
    });
  });
});
