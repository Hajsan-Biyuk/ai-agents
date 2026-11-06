<?php
/**
 * Бесплатная консультация и форма. $hp_p — префикс полей страницы ('' на главной), $hp_src — ID страницы с полями.
 */
if (!defined('ABSPATH')) {
    exit;
}
$hp_p = isset($hp_p) ? $hp_p : '';
$hp_src = isset($hp_src) ? $hp_src : null;
$hp_front = hp_front_id();
?>
        <section class="hp-consult" id="consult">
            <div class="hp-container">
                <div class="hp-consult__panel">
                    <div class="hp-consult__info">
                        <div>
                            <h2><?php echo hp_br(hp_t($hp_p . 'consult_title', $hp_src)); ?></h2>
                            <p class="hp-consult__lead"><?php echo esc_html(hp_t($hp_p . 'consult_lead', $hp_src)); ?></p>
                        </div>
                        <div class="hp-consult__contacts">
                            <div class="hp-consult__phone-box" aria-hidden="true"><span class="hp-consult__phone-ico"></span></div>
                            <div>
                                <div class="hp-consult__nums">
                                    <a href="tel:<?php echo esc_attr(hp_tel(hp_t('phone_1'))); ?>"><?php echo esc_html(hp_t('phone_1')); ?></a>
                                    <a href="tel:<?php echo esc_attr(hp_tel(hp_t('phone_2'))); ?>"><?php echo esc_html(hp_t('phone_2')); ?></a>
                                </div>
                                <a class="hp-consult__dir" href="tel:<?php echo esc_attr(hp_tel(hp_t('phone_boss'))); ?>"><?php echo esc_html(hp_t('phone_boss')); ?></a>
                                <p class="hp-consult__dir-note"><?php echo hp_br(hp_t('phone_boss_note')); ?></p>
                            </div>
                        </div>
                        <div class="hp-messengers hp-messengers--lg">
                            <a class="hp-messengers__btn" href="<?php echo esc_url(hp_t('messenger_tg_url')); ?>">
                                <?php hp_icon('icon-telegram.svg', 18, 15); ?>
                                Написать в Telegram
                            </a>
                            <a class="hp-messengers__btn" href="<?php echo esc_url(hp_t('messenger_max_url')); ?>">
                                <?php hp_icon('icon-max.svg', 18, 18); ?>
                                Написать в Max
                            </a>
                        </div>
                    </div>
                    <div class="hp-form" data-selected="<?php echo esc_attr(isset($hp_selected) ? $hp_selected : ''); ?>">
                        <h3><?php echo esc_html(hp_t($hp_p . 'consult_form_title', $hp_src)); ?></h3>
                        <?php echo function_exists('hp_cf7_shortcode') ? hp_cf7_shortcode() : ''; ?>
                    </div>
                </div>
            </div>
        </section>
