<?php
/**
 * Template Name: Акции
 * Страница «Акции». Поля — группа ACF «Акции» на этой странице.
 * Баннер акции — sp_promo_card() из mu-плагина spec-promo-popup.php (та же карточка, что во всплывающем окне).
 */

require_once get_template_directory() . '/hp-ks.php';
ks_enqueue();

the_post();
$id = get_the_ID();
$lead = ks_f('promo_lead', $id);
$today = wp_date('Ymd');
$promos = array();
foreach ((array) ks_f('promos', $id) as $i => $r) {
    if (empty($r['promo_show']) || (!empty($r['promo_until']) && $r['promo_until'] < $today)) {
        continue;
    }
    // Номер — позиция в списке: якорь #akciya-N не меняется, когда соседняя акция скрыта
    $promos[$i + 1] = $r;
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
                <h1 class="ks-list__title"><?php echo esc_html(get_the_title()); ?></h1>
                <?php if ($lead) : ?><div class="ks-list__intro"><?php echo ks_html($lead); ?></div><?php endif; ?>
            </div>
        </section>

        <?php if ($promos && function_exists('sp_promo_card')) :
            $n = 0;
            foreach ($promos as $num => $r) :
                $rows = array();
                foreach ((array) ($r['promo_rows'] ?? array()) as $row) {
                    $rows[] = array('name' => $row['promo_row_name'] ?? '', 'price' => $row['promo_row_price'] ?? '');
                }
                ?>
            <section class="ks-sec<?php echo $n++ % 2 ? ' ks-sec--alt' : ''; ?>">
                <div class="hp-container">
                    <?php
                    sp_promo_card(array(
                        'image' => $r['promo_image'] ?? 0,
                        'title' => $r['promo_title'] ?? '',
                        'subtitle' => $r['promo_subtitle'] ?? '',
                        'rows' => $rows,
                        'btn_text' => $r['promo_btn_text'] ?? '',
                        'btn_link' => '#consult',
                        'direction' => $r['promo_direction'] ?? '',
                    ), array('page' => true, 'id' => 'akciya-' . $num));
                    ?>
                    <?php if (!empty($r['promo_text'])) : ?>
                        <div class="ks-prose"><?php echo ks_html($r['promo_text']); ?></div>
                    <?php endif; ?>
                </div>
            </section>
            <?php endforeach; ?>
        <?php else : ?>
            <section class="ks-sec">
                <div class="hp-container">
                    <div class="ks-prose"><p>Сейчас акций нет. Оставьте заявку — расскажем о действующих ценах и подберём обучение.</p></div>
                    <div class="ks-button"><a class="hp-btn" href="#consult">Получить консультацию</a></div>
                </div>
            </section>
        <?php endif; ?>
    </main>

<?php get_template_part('hp-footer'); ?>
