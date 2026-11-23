<?php
use Local\Tools;
?>
<link rel="stylesheet" href="https://cdn.saas-support.com/widget/cbk.css">
<script type="text/javascript" src="https://cdn.saas-support.com/widget/cbk.js?wcb_code=e1230d7e61a00403401b07fc10e57fc0" charset="UTF-8" async></script>
<div class="custom-widget js--wrap-widget">
    <a
            class="custom-widget__element"
            href="https://t.me/gerzsb"
            target="_blank"
            title="Написать в Telegram"
            rel="noopener noreferrer">
        <div class="custom-widget__element--title">Telegram</div>
        <?= Tools::getContentFileSvg('telegram.svg') ?>
    </a>
    <a
            class="custom-widget__element"
            href="https://wa.me/+79138377977?text="
            target="_blank"
            title="Написать в Whatsapp"
            rel="noopener noreferrer">
        <div class="custom-widget__element--title">WhatsApp</div>
        <?= Tools::getContentFileSvg('whatsapp.svg') ?>
    </a>
    <a
            class="custom-widget__element"
            href="mailto:info@gerzsb.ru"
            target="_blank"
            title="Написать в Whatsapp"
            rel="noopener noreferrer">
        <div class="custom-widget__element--title">info@gerzsb.ru</div>
        <?= Tools::getContentFileSvg('email.svg') ?>
    </a>

</div>
<a class="_link--widget js--link-widget">
    <div class="_link--widget__open"></div>
    <div class="_link--widget__close">
        <svg viewBox="0 0 409.806 409.806">
            <path d="M228.929,205.01L404.596,29.343c6.78-6.548,6.968-17.352,0.42-24.132c-6.548-6.78-17.352-6.968-24.132-0.42			c-0.142,0.137-0.282,0.277-0.42,0.42L204.796,180.878L29.129,5.21c-6.78-6.548-17.584-6.36-24.132,0.42			c-6.388,6.614-6.388,17.099,0,23.713L180.664,205.01L4.997,380.677c-6.663,6.664-6.663,17.468,0,24.132			c6.664,6.662,17.468,6.662,24.132,0l175.667-175.667l175.667,175.667c6.78,6.548,17.584,6.36,24.132-0.42			c6.387-6.614,6.387-17.099,0-23.712L228.929,205.01z"></path>
        </svg>
    </div>
</a>