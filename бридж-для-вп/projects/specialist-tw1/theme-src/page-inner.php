<?php
/**
 * Template Name: Внутренняя страница (новый дизайн)
 * Внутренние страницы: О компании, Контакты, Фотогалерея, Наша техника, Сведения, политика, согласие, Спасибо.
 * Все тексты и картинки — из группы полей «Страница (внутренняя)». Шапка, подвал, форма и контакты общие с главной.
 */

require_once get_template_directory() . '/hp-ks.php';
ks_enqueue();

the_post();
$id = get_the_ID();
$front = hp_front_id();
$h1 = array('before' => ks_f('pg_h1_before', $id), 'accent' => ks_f('pg_h1_accent', $id), 'after' => ks_f('pg_h1_after', $id));
if (!$h1['before'] && !$h1['accent'] && !$h1['after']) {
    $h1['before'] = get_the_title();
}
$lead = ks_f('pg_lead', $id);
$style = ks_f('pg_style', $id) ?: 'sections';
$rows = (array) ks_f('pg_sections', $id);
$show_consult = (bool) ks_f('pg_show_consult', $id);
$show_contacts = (bool) ks_f('pg_show_contacts', $id);

get_header();
?>
<div class="hp hp--inner">
    <?php get_template_part('hp-header'); ?>

    <main>
        <?php if ($style === 'notice') : ?>
        <section class="ks-notice">
            <div class="hp-container">
                <div class="hp-panel ks-notice__card">
                    <?php hp_icon('icon-check-circle.svg', 80, 80); ?>
                    <h1 class="ks-notice__title"><?php echo ks_heading_inner($h1); ?></h1>
                    <?php if ($lead) : ?><div class="ks-notice__lead"><?php echo ks_html($lead); ?></div><?php endif; ?>
                    <?php ks_blocks($rows); ?>
                </div>
            </div>
        </section>
        <?php else : ?>
        <section class="ks-hero ks-hero--list">
            <div class="hp-container">
                <nav class="tr-crumbs ks-crumbs" aria-label="Хлебные крошки">
                    <a href="<?php echo esc_url(home_url('/')); ?>">Главная</a>
                    <span aria-hidden="true">→</span>
                    <span><?php echo esc_html(get_the_title()); ?></span>
                </nav>
                <h1 class="ks-list__title"><?php echo ks_heading_inner($h1); ?></h1>
                <?php if ($lead) : ?><div class="ks-list__intro"><?php echo ks_html($lead); ?></div><?php endif; ?>
            </div>
        </section>

        <?php if ($style === 'document') : ?>
        <section class="ks-sec ks-doc-page">
            <div class="hp-container">
                <?php ks_blocks($rows); ?>
            </div>
        </section>
        <?php else : ?>
            <?php foreach (ks_group_sections($rows) as $n => $sec) : ?>
            <section class="ks-sec<?php echo $n % 2 ? ' ks-sec--alt' : ''; ?>">
                <div class="hp-container">
                    <?php if ($sec['head']) : ?>
                        <h2 class="hp-title ks-h2"><?php echo ks_heading_inner($sec['head']); ?></h2>
                    <?php endif; ?>
                    <?php ks_blocks($sec['items'], true); ?>
                </div>
            </section>
            <?php endforeach; ?>
        <?php endif; ?>
        <?php endif; ?>

        <?php
        if ($show_consult) {
            $hp_p = '';
            $hp_src = $front;
            $hp_form_type = get_the_title();
            $hp_options = array();
            foreach (ks_all_posts() as $p) {
                $hp_options[] = array('label' => $p->post_title);
            }
            include get_template_directory() . '/hp-consult.php';
        }
        if ($show_contacts) {
            $hp_p = '';
            $hp_src = $front;
            include get_template_directory() . '/hp-contacts.php';
        }
        ?>
    </main>

<?php get_template_part('hp-footer'); ?>
