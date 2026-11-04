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

  $(document).on("click", "a[href^='#']", function (e) {
    var id = $(this).attr("href");
    if (!id || id.length < 2) {
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

  $(document).on("click", ".hp-faq__q", function () {
    var $item = $(this).closest(".hp-faq__item");
    var $answer = $item.find(".hp-faq__a");
    var open = $item.hasClass("is-open");
    $item.toggleClass("is-open", !open);
    $(this).attr("aria-expanded", open ? "false" : "true");
    if ($.trim($answer.text()).length) {
      $answer.stop(true, true).slideToggle(200);
    }
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

  $(".hp-vi").on("click", function () {
    $(".hp").toggleClass("hp--vi");
    var on = $(".hp").hasClass("hp--vi");
    $(".hp-vi").attr("aria-pressed", on ? "true" : "false");
  });

  $(".hp-form").on("submit", function (e) {
    e.preventDefault();
  });
});
