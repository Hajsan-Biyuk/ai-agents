<?php
/**
 * Страница не найдена (404). Тексты — из группы полей «Страница 404» на странице «Шапка и подвал».
 */

require_once get_template_directory() . '/hp-ks.php';
ks_enqueue();

$title = hp_t('err404_title', 'option') ?: 'Страница не найдена';
$text = hp_t('err404_text', 'option') ?: 'Такой страницы нет или ссылка устарела.';
$btn = hp_t('err404_btn', 'option') ?: 'На главную';

get_header();
?>
<div class="hp hp--inner">
    <?php get_template_part('hp-header'); ?>

    <main>
        <section class="ks-notice">
            <div class="hp-container">
                <div class="hp-panel ks-notice__card">
                    <p class="ks-notice__code" aria-hidden="true">404</p>
                    <h1 class="ks-notice__title"><?php echo esc_html($title); ?></h1>
                    <div class="ks-notice__lead"><p><?php echo esc_html($text); ?></p></div>
                    <div class="ks-button"><a class="hp-btn" href="<?php echo esc_url(home_url('/')); ?>"><?php echo esc_html($btn); ?></a></div>
                </div>
            </div>
        </section>
    </main>

<?php get_template_part('hp-footer'); ?>
