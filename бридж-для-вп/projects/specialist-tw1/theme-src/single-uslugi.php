<?php
/**
 * Курс или услуга (тип записи «Курсы и услуги»). Все тексты и картинки — из группы полей «Курс или услуга».
 * Шапка, подвал, телефоны, форма и контакты общие с главной.
 */

require_once get_template_directory() . '/hp-ks.php';
ks_enqueue();

the_post();
$id = get_the_ID();
$front = hp_front_id();
$h1 = array('before' => ks_f('ks_h1_before', $id), 'accent' => ks_f('ks_h1_accent', $id), 'after' => ks_f('ks_h1_after', $id));
if (!$h1['before'] && !$h1['accent'] && !$h1['after']) {
    $h1['before'] = get_the_title();
}
$lead = ks_f('ks_lead', $id);
$price = ks_f('ks_price', $id);
$price_note = ks_f('ks_price_note', $id) ?: 'Стоимость обучения';
$hero_img = ks_img(ks_f('ks_hero_img', $id), 'large');
$sections = ks_group_sections(ks_f('ks_sections', $id));
$phone = hp_t('phone_1', $front);

$terms = wp_get_object_terms($id, 'napravlenie');
$term = $terms && !is_wp_error($terms) ? $terms[0] : null;
$related = array();
foreach (ks_all_posts() as $p) {
    if ($p->ID === $id) {
        continue;
    }
    $same = $term && has_term($term->term_id, 'napravlenie', $p->ID);
    $related[$same ? 0 : 1][] = $p;
}
$related = array_slice(array_merge(isset($related[0]) ? $related[0] : array(), isset($related[1]) ? $related[1] : array()), 0, 4);

get_header();
?>
<div class="hp hp--inner">
    <?php get_template_part('hp-header'); ?>

    <main>
        <section class="ks-hero">
            <div class="hp-container">
                <nav class="tr-crumbs ks-crumbs" aria-label="Хлебные крошки">
                    <a href="<?php echo esc_url(home_url('/')); ?>">Главная</a>
                    <span aria-hidden="true">→</span>
                    <a href="<?php echo esc_url(get_permalink(ks_list_page_id())); ?>">Курсы и услуги</a>
                    <span aria-hidden="true">→</span>
                    <span><?php echo esc_html(get_the_title()); ?></span>
                </nav>
                <div class="ks-hero__panel">
                    <div class="ks-hero__copy">
                        <h1 class="ks-hero__title"><?php echo ks_heading_inner($h1); ?></h1>
                        <?php if ($lead) : ?><div class="ks-hero__lead"><?php echo ks_html($lead); ?></div><?php endif; ?>
                        <div class="ks-hero__actions">
                            <a class="hp-btn" href="#consult">Записаться на обучение</a>
                            <?php if ($phone) : ?><a class="hp-btn hp-btn--ghost" href="tel:<?php echo esc_attr(hp_tel($phone)); ?>"><?php echo esc_html($phone); ?></a><?php endif; ?>
                        </div>
                    </div>
                    <?php if ($price || $hero_img) : ?>
                    <aside class="ks-hero__side">
                        <?php if ($hero_img) : ?><div class="ks-hero__photo"><img src="<?php echo esc_url($hero_img); ?>" alt="<?php echo esc_attr(get_the_title()); ?>"></div><?php endif; ?>
                        <?php if ($price) : ?>
                        <div class="ks-price">
                            <?php hp_icon('icon-price.svg', 80, 80); ?>
                            <div class="ks-price__body">
                                <p class="ks-price__label"><?php echo esc_html($price_note); ?></p>
                                <p class="ks-price__value"><?php echo esc_html($price); ?></p>
                            </div>
                        </div>
                        <?php endif; ?>
                    </aside>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <?php foreach ($sections as $n => $sec) : ?>
        <section class="ks-sec<?php echo $n % 2 ? ' ks-sec--alt' : ''; ?>">
            <div class="hp-container">
                <?php if ($sec['head']) : ?>
                    <h2 class="hp-title ks-h2"><?php echo ks_heading_inner($sec['head']); ?></h2>
                <?php endif; ?>
                <?php foreach ($sec['items'] as $block) { ks_block($block); } ?>
            </div>
        </section>
        <?php endforeach; ?>

        <?php if ($related) : ?>
        <section class="ks-sec ks-related<?php echo count($sections) % 2 ? ' ks-sec--alt' : ''; ?>">
            <div class="hp-container">
                <h2 class="hp-title ks-h2">Другие курсы и услуги</h2>
                <div class="ks-tiles ks-tiles--four">
                    <?php foreach ($related as $p) { ks_tile($p->post_title, get_permalink($p), ks_f('ks_icon', $p->ID), ks_f('ks_short', $p->ID)); } ?>
                </div>
                <a class="hp-btn hp-btn--pale ks-related__all" href="<?php echo esc_url(get_permalink(ks_list_page_id())); ?>">Все курсы и услуги</a>
            </div>
        </section>
        <?php endif; ?>

        <?php
        $hp_p = '';
        $hp_src = $front;
        $hp_form_type = 'Услуга: ' . get_the_title();
        $hp_selected = get_the_title();
        $hp_options = array();
        foreach (ks_all_posts() as $p) {
            $hp_options[] = array('label' => $p->post_title);
        }
        include get_template_directory() . '/hp-consult.php';
        include get_template_directory() . '/hp-contacts.php';
        ?>
    </main>

<?php get_template_part('hp-footer'); ?>
