<?php
/**
 * Перечень курсов и услуг (страница 70). Плитки выводятся из записей типа «Курсы и услуги»,
 * заголовок, вводный текст и дополнительные плитки — из группы полей «Перечень курсов и услуг».
 */

require_once get_template_directory() . '/hp-ks.php';
ks_enqueue();

the_post();
$id = get_the_ID();
$front = hp_front_id();
$title = ks_f('ks_list_title', $id) ?: get_the_title();
$intro = ks_f('ks_list_intro', $id);
$extra = (array) ks_f('ks_list_extra', $id);

$groups = array();
foreach (get_terms(array('taxonomy' => 'napravlenie', 'hide_empty' => false, 'orderby' => 'term_id')) as $t) {
    $groups[$t->term_id] = array('name' => $t->name, 'tiles' => array());
}
$groups[0] = array('name' => 'Другие курсы и услуги', 'tiles' => array());
foreach ($extra as $e) {
    $tid = !empty($e['direction']) && isset($groups[(int) $e['direction']]) ? (int) $e['direction'] : 0;
    $groups[$tid]['tiles'][] = array('title' => $e['title'], 'url' => $e['url'], 'icon' => $e['icon'], 'short' => $e['text']);
}
foreach (ks_all_posts() as $p) {
    $terms = wp_get_object_terms($p->ID, 'napravlenie');
    $tid = $terms && !is_wp_error($terms) && isset($groups[$terms[0]->term_id]) ? $terms[0]->term_id : 0;
    $groups[$tid]['tiles'][] = array('title' => $p->post_title, 'url' => get_permalink($p), 'icon' => ks_f('ks_icon', $p->ID), 'short' => ks_f('ks_short', $p->ID));
}

get_header();
?>
<div class="hp hp--inner">
    <?php get_template_part('hp-header'); ?>

    <main>
        <section class="ks-hero ks-hero--list">
            <div class="hp-container">
                <nav class="tr-crumbs ks-crumbs" aria-label="Хлебные крошки">
                    <a href="<?php echo esc_url(home_url('/')); ?>">Главная</a>
                    <span aria-hidden="true">→</span>
                    <span><?php echo esc_html(get_the_title()); ?></span>
                </nav>
                <h1 class="ks-list__title"><?php echo esc_html($title); ?></h1>
                <?php if ($intro) : ?><p class="ks-list__intro"><?php echo esc_html($intro); ?></p><?php endif; ?>
            </div>
        </section>

        <?php $n = 0; foreach ($groups as $g) : if (!$g['tiles']) { continue; } ?>
        <section class="ks-sec<?php echo $n++ % 2 ? ' ks-sec--alt' : ''; ?>">
            <div class="hp-container">
                <h2 class="hp-title ks-h2"><?php echo esc_html($g['name']); ?></h2>
                <div class="ks-tiles">
                    <?php foreach ($g['tiles'] as $t) { ks_tile($t['title'], $t['url'], $t['icon'], $t['short']); } ?>
                </div>
            </div>
        </section>
        <?php endforeach; ?>

        <?php
        $hp_p = '';
        $hp_src = $front;
        $hp_form_type = 'Перечень курсов и услуг';
        $hp_options = array();
        foreach (ks_all_posts() as $p) {
            $hp_options[] = array('label' => $p->post_title);
        }
        include get_template_directory() . '/hp-consult.php';
        include get_template_directory() . '/hp-contacts.php';
        ?>
    </main>

<?php get_template_part('hp-footer'); ?>
