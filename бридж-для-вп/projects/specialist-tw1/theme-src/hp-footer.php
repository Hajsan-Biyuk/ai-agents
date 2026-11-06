<?php
/** Подвал страниц «hp» и закрытие обёртки .hp. */
if (!defined('ABSPATH')) {
    exit;
}
$logo = hp_img('img_logo', 'logo-4faf96.png');
?>
    <footer class="hp-footer">
        <div class="hp-footer__rule"></div>
        <div class="hp-container hp-footer__main">
            <div class="hp-footer__brand">
                <img class="hp-footer__logo" src="<?php echo esc_url($logo); ?>" alt="<?php echo esc_attr(hp_t('hero_card_title')); ?>">
                <p class="hp-footer__legal"><?php echo hp_rich(hp_t('footer_legal')); ?></p>
                <div class="hp-phones hp-phones--footer">
                    <div class="hp-phones__main">
                        <a href="tel:<?php echo esc_attr(hp_tel(hp_t('phone_1'))); ?>"><?php echo esc_html(hp_t('phone_1')); ?></a>
                        <a href="tel:<?php echo esc_attr(hp_tel(hp_t('phone_2'))); ?>"><?php echo esc_html(hp_t('phone_2')); ?></a>
                    </div>
                    <div class="hp-phones__dir">
                        <a href="tel:<?php echo esc_attr(hp_tel(hp_t('phone_boss'))); ?>"><?php echo esc_html(hp_t('phone_boss')); ?></a>
                        <span><?php echo hp_br(hp_t('phone_boss_note')); ?></span>
                    </div>
                </div>
                <a class="hp-footer__mail" href="mailto:<?php echo esc_attr(hp_t('footer_email')); ?>"><?php echo esc_html(hp_t('footer_email')); ?></a>
            </div>
            <?php foreach (hp_rows('footer_menus') as $col) : ?>
                <div class="hp-footer__col">
                    <h3 class="hp-footer__title"><?php echo hp_br($col['title']); ?></h3>
                    <div class="hp-footer__content">
                        <ul>
                            <?php foreach ($col['links'] as $link) : ?>
                                <li><a<?php echo !empty($link['class']) ? ' class="' . esc_attr($link['class']) . '"' : ''; ?> href="<?php echo esc_url($link['url']); ?>"><?php echo esc_html($link['label']); ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                        <?php if ($col['title'] === 'Документы') : ?>
                            <button class="hp-footer__vi hp-vi" type="button" aria-pressed="false"><?php echo esc_html(hp_t('vi_label')); ?></button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="hp-footer__bar">
            <div class="hp-container hp-footer__bar-inner">
                <div class="hp-footer__bar-links">
                    <a href="/privacy-policy/">Политика конфиденциальности</a>
                    <a href="/soglasie-na-obrabotku-personalnyh-dannyh/">Согласие на обработку персональных данных</a>
                </div>
                <p class="hp-footer__copy"><?php echo esc_html(hp_t('footer_copy')); ?> <span>Сделано в <img src="<?php echo esc_url(hp_theme_img('logo-sheer.svg')); ?>" alt="Шер" width="57" height="13"></span></p>
            </div>
        </div>
    </footer>
</div>
<?php require_once get_template_directory() . '/recaptcha-config.php'; ?>
<script>
window.specRecaptchaSiteKey = <?php echo wp_json_encode(spec_recaptcha_site_key()); ?>;
</script>
<script src="https://www.google.com/recaptcha/api.js?render=<?php echo esc_attr(spec_recaptcha_site_key()); ?>"></script>
<script src="<?php echo esc_url(JS_DIR . '/recaptcha.js?v=1'); ?>"></script>
<?php wp_footer(); ?>
</body>
</html>
