<?php
/**
 * Вопрос-ответ. $hp_p — префикс полей страницы ('' на главной), $hp_src — ID страницы с полями.
 */
if (!defined('ABSPATH')) {
    exit;
}
$hp_p = isset($hp_p) ? $hp_p : '';
$hp_src = isset($hp_src) ? $hp_src : null;
$hp_front = $hp_p === '' ? null : hp_front_id();
?>
        <section class="hp-faq" id="faq">
            <div class="hp-container">
                <h2 class="hp-title"><?php echo esc_html(hp_t($hp_p . 'faq_title', $hp_src)); ?></h2>
                <div class="hp-faq__list">
                    <?php foreach (hp_rows($hp_p . 'faq', $hp_src) as $item) : ?>
                        <article class="hp-faq__item">
                            <button class="hp-faq__q" type="button" aria-expanded="false"><?php echo esc_html($item['question']); ?><span class="hp-faq__chevron" aria-hidden="true"></span></button>
                            <div class="hp-faq__a" hidden><?php echo esc_html(isset($item['answer']) ? $item['answer'] : ''); ?></div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
