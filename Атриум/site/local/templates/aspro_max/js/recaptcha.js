(function () {
  var FAIL = "Не удалось подтвердить отправку. Обновите страницу и попробуйте ещё раз.";

  function siteKey() {
    if (window.ATRIUM_RECAPTCHA_SITEKEY) return window.ATRIUM_RECAPTCHA_SITEKEY;
    if (window.asproRecaptcha && window.asproRecaptcha.key) return window.asproRecaptcha.key;
    return "";
  }

  function ensureApi() {
    if (window.grecaptcha && typeof window.grecaptcha.execute === "function") return;
    if (document.getElementById("recaptchaApiLoader") || document.getElementById("atrium-recaptcha-api")) return;
    var key = siteKey();
    if (!key) return;
    var script = document.createElement("script");
    script.id = "atrium-recaptcha-api";
    script.src = "https://www.google.com/recaptcha/api.js?render=" + encodeURIComponent(key);
    document.head.appendChild(script);
  }

  function token(action) {
    return new Promise(function (resolve, reject) {
      var key = siteKey();
      var started = Date.now();
      function attempt() {
        if (!key) {
          reject(new Error("key"));
          return;
        }
        if (window.grecaptcha && typeof window.grecaptcha.execute === "function") {
          window.grecaptcha.ready(function () {
            window.grecaptcha.execute(key, { action: action }).then(resolve, reject);
          });
          return;
        }
        if (Date.now() - started > 8000) {
          reject(new Error("load"));
          return;
        }
        setTimeout(attempt, 100);
      }
      attempt();
    });
  }

  function isWebForm(form) {
    return !!(form && form.querySelector && form.querySelector('input[name="web_form_submit"]'));
  }

  function setToken(form, value) {
    var input = form.querySelector('[name="g-recaptcha-response"]');
    if (!input) {
      input = document.createElement("input");
      input.type = "hidden";
      input.name = "g-recaptcha-response";
      form.appendChild(input);
    }
    input.value = value;
  }

  function unlock(form) {
    var button = form.querySelector('button[type="submit"]');
    if (button) button.disabled = false;
    var wrap = form.closest ? form.closest(".form") : null;
    if (wrap) wrap.classList.remove("sending");
    var note = form.querySelector(".atrium-recaptcha-error");
    if (!note) {
      note = document.createElement("div");
      note.className = "atrium-recaptcha-error";
      note.style.cssText = "color:#c0392b;margin:0 0 12px;font-size:14px;";
      var body = form.querySelector(".form_body") || form;
      body.insertBefore(note, body.firstChild);
    }
    note.textContent = FAIL;
  }

  function installSubmit() {
    if (HTMLFormElement.prototype.submit && HTMLFormElement.prototype.submit.atrium) return;
    var nativeSubmit = HTMLFormElement.prototype.submit;
    function hooked() {
      var form = this;
      if (!isWebForm(form)) {
        return nativeSubmit.call(form);
      }
      if (form.getAttribute("data-atrium-recaptcha") === "pending") {
        return;
      }
      form.setAttribute("data-atrium-recaptcha", "pending");
      token("webform")
        .then(function (value) {
          setToken(form, value);
          form.removeAttribute("data-atrium-recaptcha");
          nativeSubmit.call(form);
        })
        .catch(function () {
          form.removeAttribute("data-atrium-recaptcha");
          unlock(form);
          setTimeout(function () {
            unlock(form);
          }, 700);
        });
    }
    hooked.atrium = true;
    HTMLFormElement.prototype.submit = hooked;
  }

  function isOneClick(options) {
    if (!options || typeof options.url !== "string" || typeof options.data !== "string") return false;
    if (options.url.indexOf("oneclickbuy.max/script.php") === -1) return false;
    if (options.url.indexOf("uploadfiles") !== -1) return false;
    if (options.data.indexOf("ONE_CLICK_BUY") === -1) return false;
    if (options.data.indexOf("g-recaptcha-response=") !== -1) return false;
    return true;
  }

  function installAjax(jq) {
    if (!jq || !jq.ajax || jq.ajax.atrium) return;
    var ajax = jq.ajax;
    function wrapped(url, options) {
      var opts = url;
      var args = arguments;
      if (typeof url === "string") {
        opts = options || {};
        if (!opts.url) opts.url = url;
      }
      if (!isOneClick(opts)) {
        return ajax.apply(this, args);
      }
      token("oneclick")
        .then(function (value) {
          opts.data += "&g-recaptcha-response=" + encodeURIComponent(value);
          ajax.call(jq, opts);
        })
        .catch(function () {
          if (typeof opts.success === "function") {
            opts.success({ result: "N", message: FAIL, err: "" });
          }
        });
      return jq.Deferred().promise();
    }
    wrapped.atrium = true;
    jq.ajax = wrapped;
  }

  ensureApi();
  installSubmit();
  document.addEventListener("DOMContentLoaded", function () {
    ensureApi();
    installSubmit();
  });

  function bootAjax() {
    if (window.jQuery) installAjax(window.jQuery);
  }
  bootAjax();
  document.addEventListener("DOMContentLoaded", bootAjax);
  setTimeout(bootAjax, 500);
  setTimeout(bootAjax, 2000);
})();
