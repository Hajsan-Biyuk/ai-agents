<?php
/**
 * Где мы находимся (данные главной). $hp_p — префикс полей страницы ('' на главной), $hp_src — ID страницы с полями.
 */
if (!defined('ABSPATH')) {
    exit;
}
$hp_p = isset($hp_p) ? $hp_p : '';
$hp_src = isset($hp_src) ? $hp_src : null;
$hp_front = $hp_p === '' ? null : hp_front_id();
?>
        <section class="hp-contacts" id="contacts">
            <div class="hp-container">
                <h2 class="hp-title"><?php echo esc_html(hp_t('contacts_title', $hp_front)); ?></h2>
                <div class="hp-contacts__grid">
                    <?php foreach (hp_rows('offices', $hp_front) as $office) : ?>
                        <article class="hp-panel hp-office">
                            <div class="hp-place hp-place--soft">
                                <?php hp_icon('icon-pin.svg', 27, 34); ?>
                                <p><?php echo esc_html($office['title']); ?></p>
                            </div>
                            <div class="hp-office__text">
                                <p><?php echo hp_br($office['address']); ?></p>
                                <p class="hp-office__note"><?php echo esc_html($office['note']); ?></p>
                            </div>
                            <img class="hp-office__map" src="<?php echo esc_url(hp_media_url(isset($office['image']) ? $office['image'] : null, isset($office['file']) ? $office['file'] : 'map.png')); ?>" alt="<?php echo esc_attr($office['alt']); ?>">
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
