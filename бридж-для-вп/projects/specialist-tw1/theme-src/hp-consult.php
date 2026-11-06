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
                    <form class="hp-form" action="<?php echo esc_url(get_template_directory_uri() . '/mail.php'); ?>" method="post">
                        <h3><?php echo esc_html(hp_t($hp_p . 'consult_form_title', $hp_src)); ?></h3>
                        <div class="hp-form__fields">
                            <label class="hp-field">
                                <span class="hp-visually-hidden">Направление</span>
                                <select name="training_direction" required>
                                    <?php $hp_sel = isset($hp_selected) ? $hp_selected : ''; ?>
                                    <option value=""<?php echo $hp_sel === '' ? ' selected' : ''; ?>><?php echo esc_html(hp_t($hp_p . 'consult_placeholder', $hp_src)); ?></option>
                                    <?php foreach ((isset($hp_options) && $hp_options ? $hp_options : hp_rows($hp_p . 'consult_options', $hp_src)) as $opt) : ?>
                                        <option<?php echo $hp_sel !== '' && $hp_sel === $opt['label'] ? ' selected' : ''; ?>><?php echo esc_html($opt['label']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                            <label class="hp-field">
                                <span class="hp-visually-hidden">Имя</span>
                                <input type="text" name="name" placeholder="<?php echo esc_attr(hp_t('consult_name', $hp_front)); ?>" required>
                            </label>
                            <label class="hp-field">
                                <span class="hp-visually-hidden">Телефон</span>
                                <input class="input-phone" type="tel" name="phone" inputmode="numeric" placeholder="<?php echo esc_attr(hp_t('consult_phone', $hp_front)); ?>" required>
                            </label>
                        </div>
                        <input type="hidden" name="form_type" value="<?php echo esc_attr(isset($hp_form_type) ? $hp_form_type : 'Главная'); ?>">
                        <div class="hp-form__checks">
                            <label class="hp-check">
                                <input type="checkbox" name="pd_consent" value="1" checked required>
                                <span><?php echo hp_rich(hp_t('consent_text', $hp_front)); ?></span>
                            </label>
                            <label class="hp-check">
                                <input type="checkbox" name="pd_consent_info" value="1" checked required>
                                <span><?php echo hp_rich(hp_t('consent_text_2', $hp_front)); ?></span>
                            </label>
                        </div>
                        <button class="hp-btn hp-btn--cyan hp-btn--submit" type="submit"><?php echo esc_html(hp_t($hp_p . 'consult_submit', $hp_src)); ?> <?php hp_icon('icon-arrow.svg', 26, 20); ?></button>
                    </form>
                </div>
            </div>
        </section>
