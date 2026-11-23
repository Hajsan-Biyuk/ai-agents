<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<div class="gerz-cookies" id="gerz-cookies" role="dialog" aria-live="polite" aria-label="Уведомление об использовании cookie" hidden>
    <div class="gerz-cookies__text">
        Мы используем файлы cookie, чтобы сайт работал корректно, и сервис Яндекс&nbsp;Метрика для анализа посещаемости.
        Продолжая пользоваться сайтом, вы соглашаетесь с этим. Подробнее — в
        <a href="/privacy-policy/" target="_blank">Политике конфиденциальности</a>.
    </div>
    <button type="button" class="gerz-cookies__btn intec-ui intec-ui-control-button intec-ui-mod-round-3 intec-ui-scheme-current" id="gerz-cookies-ok">Принять</button>
</div>
<style>
    .gerz-cookies{position:fixed;left:20px;bottom:20px;z-index:10050;max-width:520px;display:flex;align-items:center;gap:16px;padding:16px 20px;background:#fff;color:#404040;border-radius:8px;box-shadow:0 8px 32px rgba(0,0,0,.18);font-size:14px;line-height:1.45}
    .gerz-cookies[hidden]{display:none}
    .gerz-cookies__text a{text-decoration:underline;color:inherit}
    .gerz-cookies__btn{flex-shrink:0}
    @media (max-width:600px){.gerz-cookies{left:10px;right:10px;bottom:10px;max-width:none;flex-direction:column;align-items:stretch;gap:12px;padding:14px 16px;font-size:13px}}
</style>
<script>
    (function () {
        var key = 'gerz_cookie_ok';
        var box = document.getElementById('gerz-cookies');
        var ok;
        try { ok = window.localStorage.getItem(key); } catch (e) { ok = null; }
        if (!box || ok) return;
        box.hidden = false;
        document.getElementById('gerz-cookies-ok').addEventListener('click', function () {
            try { window.localStorage.setItem(key, String(Date.now())); } catch (e) {}
            box.hidden = true;
        });
    })();
</script>
