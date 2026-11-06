<?php
/**
 * Страница «Удостоверение тракториста» (/tractor-driver/).
 * Тексты и фото читаются из группы полей «Удостоверение тракториста».
 * Шапка, подвал, телефоны, согласия и контакты — общие, с главной и «Шапки и подвала».
 */

require_once get_template_directory() . '/hp-core.php';

$tr = get_queried_object_id();

/** Текст поля страницы без подстановок из кода. */
function tr_t($key) {
    return hp_t($key, get_queried_object_id());
}

/** Строки повторителя страницы. */
function tr_rows($key) {
    return hp_rows($key, get_queried_object_id());
}

/** URL картинки из поля страницы. */
function tr_img($key) {
    return hp_img($key, '', get_queried_object_id());
}

/** Картинка из подполя повторителя или галереи. */
function tr_media($value) {
    return hp_media_url($value, '');
}

/** Alt картинки: из медиатеки, иначе запасной текст. */
function tr_alt($value, $fallback) {
    if (is_array($value) && !empty($value['alt'])) {
        return $value['alt'];
    }
    if (is_numeric($value)) {
        $alt = get_post_meta((int) $value, '_wp_attachment_image_alt', true);
        if ($alt) {
            return $alt;
        }
    }
    return $fallback;
}

add_action('wp_enqueue_scripts', function () {
    $dir = get_stylesheet_directory() . '/home';
    $uri = get_stylesheet_directory_uri() . '/home';
    wp_enqueue_style('hp-fonts', $uri . '/css/fonts.css', array('main-style'), filemtime($dir . '/css/fonts.css'));
    wp_enqueue_style('hp-main', $uri . '/css/main.css', array('hp-fonts'), filemtime($dir . '/css/main.css'));
    wp_enqueue_style('hp-media', $uri . '/css/media.css', array('hp-main'), filemtime($dir . '/css/media.css'));
    wp_enqueue_style('hp-tractor', $uri . '/css/tractor.css', array('hp-media'), filemtime($dir . '/css/tractor.css'));
    wp_enqueue_script('hp-home', $uri . '/js/common.js', array('jquery-script'), filemtime($dir . '/js/common.js'), true);
}, 30);

get_header();
?>
<div class="hp hp--inner">
    <?php get_template_part('hp-header'); ?>

    <main>
        <section class="hp-hero tr-hero" id="hero">
            <img class="hp-hero__bg tr-hero__bg" src="<?php echo esc_url(tr_img('tr_img_hero')); ?>" alt="<?php echo esc_attr(tr_t('tr_hero_alt')); ?>">
            <div class="hp-hero__fade"></div>
            <div class="hp-container tr-hero__inner">
                <nav class="tr-crumbs" aria-label="Хлебные крошки">
                    <a href="<?php echo esc_url(home_url('/')); ?>">Главная</a>
                    <span aria-hidden="true">→</span>
                    <span><?php echo esc_html(tr_t('tr_crumb')); ?></span>
                </nav>
                <h1 class="tr-hero__title"><?php echo hp_br(tr_t('tr_hero_title')); ?></h1>
                <p class="tr-hero__lead"><?php echo esc_html(tr_t('tr_hero_lead')); ?></p>
                <article class="hp-panel tr-price">
                    <?php hp_icon('icon-price.svg', 80, 80); ?>
                    <div class="tr-price__body">
                        <p class="tr-price__label"><?php echo esc_html(tr_t('tr_price_label')); ?></p>
                        <h2 class="tr-price__title"><?php echo esc_html(tr_t('tr_price_title')); ?></h2>
                        <p class="tr-price__text"><?php echo hp_br(tr_t('tr_price_text')); ?></p>
                        <p class="tr-price__note"><?php echo esc_html(tr_t('tr_price_note')); ?></p>
                    </div>
                </article>
                <div class="tr-hero__actions">
                    <a class="hp-btn" href="<?php echo esc_url(tr_t('tr_btn_1_url')); ?>"><?php echo esc_html(tr_t('tr_btn_1')); ?></a>
                    <a class="hp-btn hp-btn--navy" href="<?php echo esc_url(tr_t('tr_btn_2_url')); ?>"><?php echo esc_html(tr_t('tr_btn_2')); ?></a>
                    <a class="hp-btn hp-btn--pale" href="<?php echo esc_url(tr_t('tr_btn_3_url')); ?>"><?php echo esc_html(tr_t('tr_btn_3')); ?></a>
                </div>
            </div>
        </section>

        <section class="tr-cats" id="categories">
            <div class="hp-container">
                <div class="tr-cats__head">
                    <h2 class="hp-title tr-cats__title"><?php echo esc_html(tr_t('tr_cats_title')); ?></h2>
                    <p class="tr-cats__intro"><?php echo esc_html(tr_t('tr_cats_intro')); ?></p>
                </div>
                <div class="tr-cats__grid">
                    <?php foreach (tr_rows('tr_cats') as $cat) : ?>
                        <article class="hp-panel tr-cat">
                            <div class="tr-cat__head">
                                <span class="tr-cat__letter"><?php echo esc_html($cat['letter']); ?></span>
                                <h3><?php echo esc_html($cat['title']); ?></h3>
                            </div>
                            <p class="tr-cat__text"><?php echo hp_br($cat['text']); ?></p>
                            <div class="tr-cat__photo">
                                <img src="<?php echo esc_url(tr_media($cat['image'])); ?>" alt="<?php echo esc_attr(tr_alt($cat['image'], $cat['title'])); ?>" loading="lazy">
                            </div>
                            <p class="tr-cat__note"><?php echo esc_html($cat['note']); ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section class="tr-box tr-svo" id="svo">
            <div class="hp-container">
                <div class="tr-box__panel">
                    <div class="tr-svo__copy">
                        <div class="tr-svo__text">
                            <p class="hp-badge"><?php echo esc_html(tr_t('tr_svo_badge')); ?></p>
                            <h2 class="tr-box__title"><?php echo esc_html(tr_t('tr_svo_title')); ?></h2>
                            <p class="hp-companies__lead"><?php echo esc_html(tr_t('tr_svo_lead')); ?></p>
                            <ul class="hp-companies__list">
                                <?php foreach (tr_rows('tr_svo_list') as $item) : ?>
                                    <li><?php echo esc_html($item['text']); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <a class="hp-btn tr-btn" href="<?php echo esc_url(tr_t('tr_svo_btn_url')); ?>"><?php echo esc_html(tr_t('tr_svo_btn')); ?></a>
                    </div>
                    <div class="tr-box__photo">
                        <img src="<?php echo esc_url(tr_img('tr_svo_img')); ?>" alt="<?php echo esc_attr(tr_t('tr_svo_alt')); ?>" loading="lazy">
                    </div>
                </div>
            </div>
        </section>

        <section class="hp-about" id="about">
            <div class="hp-container">
                <div class="hp-about__head">
                    <p class="hp-badge"><?php echo esc_html(tr_t('tr_about_badge')); ?></p>
                    <h2 class="hp-title hp-title--light"><?php echo esc_html(tr_t('tr_about_title')); ?></h2>
                </div>
                <?php
                $about = tr_rows('tr_about_items');
                foreach (array(array_slice($about, 0, 3), array_slice($about, 3)) as $chunk) :
                    if (!$chunk) {
                        continue;
                    }
                    ?>
                    <div class="hp-about__grid">
                        <?php foreach ($chunk as $item) : ?>
                            <article class="hp-panel hp-why">
                                <?php if (!empty($item['note'])) : ?><div class="hp-why__top"><?php endif; ?>
                                <img src="<?php echo esc_url(hp_theme_img($item['icon'])); ?>" alt="" width="80" height="80">
                                <?php if (!empty($item['note'])) : ?>
                                    <span class="hp-license"><?php echo esc_html($item['note']); ?></span>
                                    </div>
                                <?php endif; ?>
                                <h3><?php echo esc_html($item['title']); ?></h3>
                                <p><?php echo hp_br($item['text']); ?></p>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <?php $gallery = tr_rows('tr_gallery'); ?>
        <?php if ($gallery) : ?>
        <section class="tr-gallery hp-slider" id="gallery">
            <div class="hp-container">
                <h2 class="hp-title"><?php echo esc_html(tr_t('tr_gallery_title')); ?></h2>
                <div class="tr-gallery__grid hp-slider__track">
                    <?php foreach ($gallery as $photo) : ?>
                        <a class="tr-gallery__item" href="<?php echo esc_url(tr_media($photo)); ?>" data-fancybox="tr-gallery">
                            <img src="<?php echo esc_url(tr_media($photo)); ?>" alt="<?php echo esc_attr(tr_alt($photo, tr_t('tr_gallery_title'))); ?>" loading="lazy">
                        </a>
                    <?php endforeach; ?>
                </div>
                <?php if (count($gallery) > 1) : ?>
                <div class="tr-gallery__nav">
                    <button class="hp-slider__btn hp-slider__btn--prev" type="button" aria-label="Назад"><?php hp_icon('icon-arrow-left.svg', 20, 20); ?></button>
                    <button class="hp-slider__btn hp-slider__btn--next" type="button" aria-label="Вперёд"><?php hp_icon('icon-arrow-left.svg', 20, 20); ?></button>
                </div>
                <?php endif; ?>
            </div>
        </section>
        <?php endif; ?>

        <section class="hp-steps" id="steps">
            <div class="hp-container">
                <h2 class="hp-title"><?php echo esc_html(tr_t('tr_steps_title')); ?></h2>
                <div class="hp-panel hp-steps__board">
                    <div class="hp-steps__flow">
                        <?php
                        $steps = tr_rows('tr_steps');
                        foreach (array(array_slice($steps, 0, 2), array_slice($steps, 2)) as $index => $chunk) :
                            if (!$chunk) {
                                continue;
                            }
                            ?>
                            <?php if ($index === 1) : ?><div class="hp-steps__bridge" aria-hidden="true"></div><?php endif; ?>
                            <div class="hp-steps__row">
                                <?php foreach ($chunk as $i => $step) : ?>
                                    <?php if ($i > 0) : ?><div class="hp-steps__dash" aria-hidden="true"></div><?php endif; ?>
                                    <article class="hp-step<?php echo $index === 1 && $i === 1 ? ' hp-step--soft' : ''; ?>">
                                        <span class="hp-step__num"><?php echo esc_html($step['num']); ?></span>
                                        <h3><?php echo esc_html($step['title']); ?></h3>
                                        <p><?php echo esc_html($step['text']); ?></p>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="hp-steps__photo">
                        <img src="<?php echo esc_url(tr_img('tr_steps_img')); ?>" alt="<?php echo esc_attr(tr_t('tr_steps_alt')); ?>" loading="lazy">
                        <div class="hp-steps__badge">
                            <?php hp_icon('icon-check-circle.svg', 60, 60); ?>
                            <p><?php echo hp_br(tr_t('tr_steps_badge')); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="tr-box tr-kit" id="documents">
            <div class="hp-container">
                <div class="tr-box__panel">
                    <div class="tr-kit__col">
                        <h2 class="tr-box__title"><?php echo esc_html(tr_t('tr_take_title')); ?></h2>
                        <ul class="hp-companies__list">
                            <?php foreach (tr_rows('tr_take_list') as $item) : ?>
                                <li><?php echo esc_html($item['text']); ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <p class="hp-companies__lead"><?php echo esc_html(tr_t('tr_take_note')); ?></p>
                    </div>
                    <div class="tr-kit__col tr-kit__col--get">
                        <h2 class="tr-box__title"><?php echo esc_html(tr_t('tr_get_title')); ?></h2>
                        <div class="tr-kit__photos">
                            <?php foreach (tr_rows('tr_get_photos') as $photo) : ?>
                                <a class="tr-kit__photo" href="<?php echo esc_url(tr_media($photo)); ?>" data-fancybox="tr-get">
                                    <img src="<?php echo esc_url(tr_media($photo)); ?>" alt="<?php echo esc_attr(tr_alt($photo, tr_t('tr_get_title'))); ?>" loading="lazy">
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="tr-companies" id="companies">
            <div class="hp-container tr-companies__inner">
                <div class="tr-companies__copy">
                    <div class="hp-about__head">
                        <p class="hp-badge"><?php echo esc_html(tr_t('tr_comp_badge')); ?></p>
                        <h2 class="hp-title hp-title--light"><?php echo hp_br(tr_t('tr_comp_title')); ?></h2>
                    </div>
                    <p class="tr-companies__lead"><?php echo esc_html(tr_t('tr_comp_lead')); ?></p>
                    <a class="hp-btn tr-btn" href="<?php echo esc_url(tr_t('tr_comp_btn_url')); ?>"><?php echo esc_html(tr_t('tr_comp_btn')); ?></a>
                </div>
                <div class="tr-companies__photo">
                    <img src="<?php echo esc_url(tr_img('tr_comp_img')); ?>" alt="<?php echo esc_attr(tr_t('tr_comp_alt')); ?>" loading="lazy">
                </div>
            </div>
        </section>

        <?php
        $hp_p = 'tr_';
        $hp_src = $tr;
        $hp_form_type = 'Удостоверение тракториста';
        include get_template_directory() . '/hp-faq.php';
        include get_template_directory() . '/hp-consult.php';
        include get_template_directory() . '/hp-contacts.php';
        ?>
    </main>

<?php get_template_part('hp-footer'); ?>
