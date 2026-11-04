<?php
/**
 * Главная страница. Тексты и фото читаются из группы полей «Главная страница».
 */

require_once get_template_directory() . '/hp-content.php';

function hp_store($key) {
    $options = array(
        'phone_1' => true,
        'phone_2' => true,
        'phone_boss' => true,
        'phone_boss_note' => true,
        'messenger_tg_url' => true,
        'messenger_max_url' => true,
        'header_consult' => true,
        'nav' => true,
        'img_logo' => true,
        'footer_legal' => true,
        'footer_email' => true,
        'footer_copy' => true,
        'vi_label' => true,
        'footer_menus' => true,
    );
    return isset($options[$key]) ? 'option' : false;
}

function hp_t($key) {
    $defaults = hp_default_content();
    $fallback = isset($defaults[$key]) && is_string($defaults[$key]) ? $defaults[$key] : '';
    if (function_exists('get_field')) {
        $where = hp_store($key);
        $value = $where ? get_field($key, $where) : get_field($key);
        if (is_string($value) && $value !== '') {
            return $value;
        }
    }
    return $fallback;
}

function hp_rows($key) {
    $defaults = hp_default_content();
    $fallback = isset($defaults[$key]) && is_array($defaults[$key]) ? $defaults[$key] : array();
    if (function_exists('get_field')) {
        $where = hp_store($key);
        $value = $where ? get_field($key, $where) : get_field($key);
        if (is_array($value) && count($value) > 0) {
            return $value;
        }
    }
    return $fallback;
}

function hp_on($key) {
    $post_id = get_queried_object_id();
    if (!$post_id || !function_exists('get_field')) {
        return true;
    }
    $ref = get_post_meta($post_id, '_' . $key, true);
    if ($ref === '' || $ref === null || $ref === false) {
        return true;
    }
    return (bool) get_field($key, $post_id);
}

function hp_theme_img($file) {
    return get_template_directory_uri() . '/home/img/' . ltrim($file, '/');
}

function hp_media_url($value, $fallback_file) {
    if (is_array($value) && !empty($value['url'])) {
        return $value['url'];
    }
    if (is_numeric($value)) {
        $url = wp_get_attachment_image_url((int) $value, 'full');
        if ($url) {
            return $url;
        }
    }
    if ($fallback_file) {
        return hp_theme_img($fallback_file);
    }
    return '';
}

function hp_img($key, $file) {
    $value = null;
    if (function_exists('get_field')) {
        $where = hp_store($key);
        $value = $where ? get_field($key, $where) : get_field($key);
    }
    return hp_media_url($value, $file);
}

function hp_tel($phone) {
    $digits = preg_replace('/\D+/', '', $phone);
    if (strpos($digits, '8') === 0) {
        $digits = '7' . substr($digits, 1);
    }
    return $digits ? '+' . $digits : '';
}

function hp_br($text) {
    return nl2br(esc_html($text), false);
}

function hp_rich($text) {
    return wp_kses($text, array(
        'br' => array(),
        'strong' => array(),
        'span' => array('class' => true),
        'a' => array('href' => true, 'target' => true, 'rel' => true),
    ));
}

function hp_icon($file, $w, $h) {
    printf(
        '<img src="%s" alt="" width="%d" height="%d">',
        esc_url(hp_theme_img($file)),
        (int) $w,
        (int) $h
    );
}

function hp_program($row, $plain = false) {
    $classes = 'hp-program';
    if (!empty($row['accent'])) {
        $classes .= ' hp-program--accent';
    }
    if ($plain) {
        $classes .= ' hp-program--plain';
    }
    $url = !empty($row['url']) ? $row['url'] : '#consult';
    ?>
    <a class="<?php echo esc_attr($classes); ?>" href="<?php echo esc_url($url); ?>">
        <span class="hp-program__name"><?php echo esc_html($row['name']); ?></span>
        <?php if (!$plain && !empty($row['price'])) : ?>
            <span class="hp-program__price"><?php echo esc_html($row['price']); ?></span>
            <span class="hp-program__go" aria-hidden="true">›</span>
        <?php endif; ?>
    </a>
    <?php
}

get_header();
$logo = hp_img('img_logo', 'logo-4faf96.png');
?>
<div class="hp">
    <div class="hp-topline"></div>
    <header class="hp-header">
        <div class="hp-container">
            <div class="hp-header__top">
                <a class="hp-header__logo" href="#hero">
                    <img src="<?php echo esc_url($logo); ?>" alt="<?php echo esc_attr(hp_t('hero_card_title')); ?>">
                </a>
                <div class="hp-header__tools">
                    <div class="hp-phones">
                        <div class="hp-phones__main">
                            <a href="tel:<?php echo esc_attr(hp_tel(hp_t('phone_1'))); ?>"><?php echo esc_html(hp_t('phone_1')); ?></a>
                            <a href="tel:<?php echo esc_attr(hp_tel(hp_t('phone_2'))); ?>"><?php echo esc_html(hp_t('phone_2')); ?></a>
                        </div>
                        <div class="hp-phones__dir">
                            <a href="tel:<?php echo esc_attr(hp_tel(hp_t('phone_boss'))); ?>"><?php echo esc_html(hp_t('phone_boss')); ?></a>
                            <span><?php echo hp_br(hp_t('phone_boss_note')); ?></span>
                        </div>
                    </div>
                    <div class="hp-messengers">
                        <a class="hp-messengers__btn" href="<?php echo esc_url(hp_t('messenger_tg_url')); ?>">
                            <?php hp_icon('icon-telegram.svg', 16, 13); ?>
                            Telegram
                        </a>
                        <a class="hp-messengers__btn" href="<?php echo esc_url(hp_t('messenger_max_url')); ?>">
                            <?php hp_icon('icon-max.svg', 12, 12); ?>
                            Max
                        </a>
                    </div>
                    <a class="hp-btn hp-btn--cyan hp-btn--header" href="#consult"><?php echo esc_html(hp_t('header_consult')); ?></a>
                </div>
                <a class="hp-header__phone" href="tel:<?php echo esc_attr(hp_tel(hp_t('phone_1'))); ?>">
                    <span class="hp-header__phone-ico" aria-hidden="true"></span>
                    <span class="hp-visually-hidden">Позвонить</span>
                </a>
                <button class="hp-header__sandwich" type="button" aria-label="Меню" aria-expanded="false">
                    <span class="hp-sw hp-sw--top"></span>
                    <span class="hp-sw hp-sw--mid"></span>
                    <span class="hp-sw hp-sw--bot"></span>
                </button>
            </div>
            <nav class="hp-header__nav" aria-label="Главная навигация">
                <ul class="hp-nav">
                    <?php foreach (hp_rows('nav') as $item) :
                        $children = !empty($item['children']) && is_array($item['children']) ? $item['children'] : array();
                        ?>
                        <li class="hp-nav__item<?php echo $children ? ' hp-nav__item--drop' : ''; ?>">
                            <?php if ($children) : ?>
                                <a class="hp-nav__drop-toggle" href="<?php echo esc_url($item['url']); ?>"><?php echo esc_html($item['title']); ?> <span aria-hidden="true">▾</span></a>
                                <ul class="hp-nav__drop">
                                    <?php foreach ($children as $child) : ?>
                                        <li><a href="<?php echo esc_url($child['url']); ?>"><?php echo esc_html($child['title']); ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else : ?>
                                <a href="<?php echo esc_url($item['url']); ?>"><?php echo esc_html($item['title']); ?></a>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>
        </div>
        <div class="hp-menu-mobile">
            <ul>
                <?php foreach (hp_rows('nav') as $item) : ?>
                    <li><a href="<?php echo esc_url($item['url']); ?>"><?php echo esc_html($item['title']); ?></a></li>
                    <?php if (!empty($item['children']) && is_array($item['children'])) :
                        foreach ($item['children'] as $child) : ?>
                            <li><a href="<?php echo esc_url($child['url']); ?>"><?php echo esc_html($child['title']); ?></a></li>
                        <?php endforeach;
                    endif; ?>
                <?php endforeach; ?>
                <li><a href="#consult"><?php echo esc_html(hp_t('header_consult')); ?></a></li>
                <li><a href="tel:<?php echo esc_attr(hp_tel(hp_t('phone_1'))); ?>"><?php echo esc_html(hp_t('phone_1')); ?></a></li>
                <li><a href="tel:<?php echo esc_attr(hp_tel(hp_t('phone_2'))); ?>"><?php echo esc_html(hp_t('phone_2')); ?></a></li>
                <li><a href="tel:<?php echo esc_attr(hp_tel(hp_t('phone_boss'))); ?>"><?php echo esc_html(hp_t('phone_boss')); ?></a></li>
            </ul>
        </div>
        <div class="hp-menu-overlay"></div>
    </header>

    <main>
        <?php if (hp_on('show_banner')) : ?>
        <section class="hp-hero" id="hero">
            <img class="hp-hero__bg" src="<?php echo esc_url(hp_img('img_hero', 'hero-bg-7d4ec6.png')); ?>" alt="<?php echo esc_attr(hp_t('hero_alt')); ?>">
            <div class="hp-hero__fade"></div>
            <div class="hp-container hp-hero__inner">
                <div class="hp-hero__copy">
                    <h1 class="hp-hero__title"><?php echo esc_html(hp_t('hero_title_start')); ?> <span><?php echo esc_html(hp_t('hero_title_accent')); ?></span><br><span><?php echo esc_html(hp_t('hero_title_place')); ?></span></h1>
                    <div class="hp-hero__lead">
                        <p><?php echo esc_html(hp_t('hero_lead_1')); ?></p>
                        <p><?php echo esc_html(hp_t('hero_lead_2')); ?></p>
                    </div>
                    <div class="hp-hero__actions">
                        <a class="hp-btn hp-btn--cyan" href="<?php echo esc_url(hp_t('hero_btn_1_url')); ?>"><?php echo esc_html(hp_t('hero_btn_1')); ?></a>
                        <a class="hp-btn hp-btn--navy" href="<?php echo esc_url(hp_t('hero_btn_2_url')); ?>"><?php echo esc_html(hp_t('hero_btn_2')); ?></a>
                    </div>
                    <ul class="hp-hero__pills">
                        <?php foreach (hp_rows('hero_pills') as $pill) : ?>
                            <li><?php echo esc_html($pill['text']); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <article class="hp-hero__card">
                    <div class="hp-hero__photo">
                        <img src="<?php echo esc_url(hp_img('img_hero_card', 'hero-bg-7d4ec6.png')); ?>" alt="<?php echo esc_attr(hp_t('hero_card_alt')); ?>">
                        <p class="hp-hero__price"><strong><?php echo esc_html(hp_t('hero_price')); ?></strong><br><span><?php echo esc_html(hp_t('hero_price_note')); ?></span></p>
                    </div>
                    <div class="hp-hero__card-body">
                        <h2><?php echo esc_html(hp_t('hero_card_title')); ?></h2>
                        <p><?php echo hp_rich(hp_t('hero_card_text')); ?></p>
                        <a class="hp-hero__more" href="<?php echo esc_url(hp_t('hero_card_link_url')); ?>"><?php echo esc_html(hp_t('hero_card_link')); ?></a>
                    </div>
                </article>
            </div>
        </section>

        <?php endif; ?>
        <?php if (hp_on('show_needs')) : ?>
        <section class="hp-needs" id="needs">
            <div class="hp-container">
                <h2 class="hp-title"><?php echo esc_html(hp_t('needs_title')); ?></h2>
                <div class="hp-needs__grid">
                    <?php foreach (hp_rows('needs') as $need) : ?>
                        <article class="hp-need">
                            <div class="hp-need__top">
                                <div class="hp-ico"><?php hp_icon($need['icon'], 48, 48); ?></div>
                                <div class="hp-need__text">
                                    <h3><?php echo esc_html($need['title']); ?></h3>
                                    <p><?php echo esc_html($need['text']); ?></p>
                                </div>
                            </div>
                            <div class="hp-need__bottom">
                                <p class="hp-need__for"><?php echo esc_html($need['audience']); ?></p>
                                <a class="hp-btn hp-btn--<?php echo esc_attr($need['style']); ?> hp-btn--block" href="<?php echo esc_url($need['url']); ?>"><?php echo esc_html($need['button']); ?></a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <?php endif; ?>
        <?php if (hp_on('show_courses')) : ?>
        <section class="hp-courses" id="courses">
            <div class="hp-container">
                <h2 class="hp-title"><?php echo esc_html(hp_t('courses_title')); ?></h2>
                <div class="hp-courses__catalog">
                    <article class="hp-panel hp-courses__main">
                        <div class="hp-courses__list-col">
                            <h3><?php echo esc_html(hp_t('spec_title')); ?></h3>
                            <p class="hp-courses__cats"><?php echo esc_html(hp_t('spec_cats')); ?></p>
                            <div class="hp-programs">
                                <?php foreach (hp_rows('spec_programs') as $row) {
                                    hp_program($row, false);
                                } ?>
                            </div>
                            <a class="hp-btn hp-btn--cyan hp-btn--arrow" href="<?php echo esc_url(hp_t('spec_cta_url')); ?>"><?php echo esc_html(hp_t('spec_cta')); ?> <span aria-hidden="true">›</span></a>
                        </div>
                        <div class="hp-courses__photo">
                            <div class="hp-courses__note">
                                <?php hp_icon('icon-check.svg', 42, 42); ?>
                                <p><?php echo hp_rich(hp_t('spec_note')); ?></p>
                            </div>
                            <img src="<?php echo esc_url(hp_img('img_catalog', 'catalog-tech.png')); ?>" alt="<?php echo esc_attr(hp_t('catalog_alt')); ?>">
                        </div>
                    </article>
                    <div class="hp-courses__grid">
                        <article class="hp-panel hp-dir" id="jobs">
                            <div class="hp-dir__head">
                                <div class="hp-ico"><?php hp_icon('icon-hardhat.svg', 48, 48); ?></div>
                                <h3><?php echo esc_html(hp_t('jobs_title')); ?></h3>
                            </div>
                            <div class="hp-programs hp-programs--compact">
                                <?php foreach (hp_rows('jobs_programs') as $row) {
                                    hp_program($row, false);
                                } ?>
                            </div>
                        </article>
                        <article class="hp-panel hp-dir" id="safety">
                            <div class="hp-dir__head">
                                <div class="hp-ico"><?php hp_icon('icon-users.svg', 48, 48); ?></div>
                                <h3><?php echo esc_html(hp_t('safety_title')); ?></h3>
                            </div>
                            <div class="hp-programs hp-programs--compact">
                                <?php foreach (hp_rows('safety_programs') as $row) {
                                    hp_program($row, true);
                                } ?>
                            </div>
                        </article>
                        <div class="hp-courses__stack">
                            <article class="hp-panel hp-dir" id="retrain">
                                <div class="hp-dir__head">
                                    <div class="hp-ico"><?php hp_icon('icon-badge.svg', 48, 48); ?></div>
                                    <h3><?php echo esc_html(hp_t('retrain_title')); ?></h3>
                                </div>
                                <div class="hp-programs hp-programs--compact">
                                    <?php foreach (hp_rows('retrain_programs') as $row) {
                                        hp_program($row, true);
                                    } ?>
                                </div>
                            </article>
                            <article class="hp-panel hp-dir" id="boats">
                                <div class="hp-dir__head">
                                    <div class="hp-ico"><?php hp_icon('icon-boat.svg', 57, 57); ?></div>
                                    <h3><?php echo esc_html(hp_t('boats_title')); ?></h3>
                                </div>
                                <div class="hp-programs hp-programs--compact">
                                    <?php foreach (hp_rows('boats_programs') as $row) {
                                        hp_program($row, true);
                                    } ?>
                                </div>
                            </article>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php endif; ?>
        <?php if (hp_on('show_tractor')) : ?>
        <section class="hp-tractor" id="tractor">
            <div class="hp-container">
                <div class="hp-tractor__card">
                    <div class="hp-tractor__body">
                        <p class="hp-badge"><?php echo esc_html(hp_t('tractor_badge')); ?></p>
                        <h2 class="hp-tractor__title"><?php echo hp_rich(hp_t('tractor_title')); ?></h2>
                        <div class="hp-tractor__perks">
                            <?php foreach (hp_rows('tractor_perks') as $perk) : ?>
                                <div class="hp-perk">
                                    <div class="hp-perk__ico"><?php hp_icon($perk['icon'], 52, 52); ?></div>
                                    <div>
                                        <h3><?php echo esc_html($perk['title']); ?></h3>
                                        <p><?php echo esc_html($perk['text']); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="hp-tractor__actions">
                            <a class="hp-btn hp-btn--cyan hp-btn--lg" href="<?php echo esc_url(hp_t('tractor_btn_1_url')); ?>"><?php echo esc_html(hp_t('tractor_btn_1')); ?> <?php hp_icon('icon-arrow.svg', 26, 20); ?></a>
                            <a class="hp-btn hp-btn--ghost hp-btn--lg" href="<?php echo esc_url(hp_t('tractor_btn_2_url')); ?>"><?php echo esc_html(hp_t('tractor_btn_2')); ?></a>
                        </div>
                    </div>
                    <div class="hp-tractor__photo">
                        <img src="<?php echo esc_url(hp_img('img_tractor', 'tractor-hero.png')); ?>" alt="<?php echo esc_attr(hp_t('tractor_alt')); ?>">
                        <div class="hp-place">
                            <?php hp_icon('icon-pin.svg', 27, 34); ?>
                            <p><?php echo hp_br(hp_t('tractor_place')); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php endif; ?>
        <?php if (hp_on('show_polygon')) : ?>
        <section class="hp-polygon" id="polygon">
            <div class="hp-container">
                <h2 class="hp-title"><?php echo esc_html(hp_t('polygon_title')); ?></h2>
                <div class="hp-polygon__gallery">
                    <div class="hp-shot hp-shot--main">
                        <img src="<?php echo esc_url(hp_img('img_polygon', 'polygon-main.png')); ?>" alt="<?php echo esc_attr(hp_t('polygon_alt')); ?>">
                        <button class="hp-play" type="button" aria-label="<?php echo esc_attr(hp_t('polygon_video_title')); ?>">
                            <?php hp_icon('icon-play.svg', 98, 98); ?>
                        </button>
                        <div class="hp-shot__caption">
                            <p class="hp-shot__caption-title"><?php echo esc_html(hp_t('polygon_video_title')); ?></p>
                            <p><?php echo esc_html(hp_t('polygon_video_text')); ?></p>
                        </div>
                    </div>
                    <div class="hp-polygon__side">
                        <?php foreach (hp_rows('polygon_shots') as $shot) : ?>
                            <div class="hp-shot">
                                <img src="<?php echo esc_url(hp_media_url(isset($shot['image']) ? $shot['image'] : null, $shot['file'])); ?>" alt="<?php echo esc_attr($shot['alt']); ?>">
                                <?php if (!empty($shot['tag'])) : ?><span class="hp-shot__tag"><?php echo esc_html($shot['tag']); ?></span><?php endif; ?>
                                <p class="hp-shot__label"><?php echo esc_html($shot['label']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="hp-polygon__bar">
                    <div class="hp-polygon__where">
                        <?php hp_icon('icon-pin-lg.svg', 63, 63); ?>
                        <div>
                            <p class="hp-polygon__where-title"><?php echo esc_html(hp_t('polygon_where_title')); ?></p>
                            <p><?php echo esc_html(hp_t('polygon_where_text')); ?></p>
                        </div>
                    </div>
                    <a class="hp-btn hp-btn--cyan hp-btn--arrow" href="<?php echo esc_url(hp_t('polygon_cta_url')); ?>"><?php echo esc_html(hp_t('polygon_cta')); ?> <span aria-hidden="true">›</span></a>
                </div>
            </div>
        </section>

        <?php endif; ?>
        <?php if (hp_on('show_about')) : ?>
        <section class="hp-about" id="about">
            <div class="hp-container">
                <div class="hp-about__head">
                    <p class="hp-badge"><?php echo esc_html(hp_t('about_badge')); ?></p>
                    <h2 class="hp-title hp-title--light"><?php echo esc_html(hp_t('about_title')); ?></h2>
                </div>
                <?php
                $about = hp_rows('about_items');
                $about_rows = array(array_slice($about, 0, 3), array_slice($about, 3));
                foreach ($about_rows as $chunk) :
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

        <?php endif; ?>
        <?php if (hp_on('show_steps')) : ?>
        <section class="hp-steps" id="steps">
            <div class="hp-container">
                <h2 class="hp-title"><?php echo esc_html(hp_t('steps_title')); ?></h2>
                <div class="hp-panel hp-steps__board">
                    <div class="hp-steps__flow">
                        <?php
                        $steps = hp_rows('steps');
                        $step_rows = array(array_slice($steps, 0, 2), array_slice($steps, 2));
                        foreach ($step_rows as $index => $chunk) :
                            ?>
                            <?php if ($index === 1) : ?><div class="hp-steps__bridge" aria-hidden="true"></div><?php endif; ?>
                            <div class="hp-steps__row">
                                <?php foreach ($chunk as $i => $step) : ?>
                                    <?php if ($i > 0) : ?><div class="hp-steps__dash" aria-hidden="true"></div><?php endif; ?>
                                    <article class="hp-step<?php echo $step['num'] === '04' ? ' hp-step--soft' : ''; ?>">
                                        <span class="hp-step__num"><?php echo esc_html($step['num']); ?></span>
                                        <h3><?php echo esc_html($step['title']); ?></h3>
                                        <p><?php echo esc_html($step['text']); ?></p>
                                        <?php if (!empty($step['button'])) : ?>
                                            <a class="hp-btn hp-btn--cyan hp-btn--arrow" href="<?php echo esc_url($step['url']); ?>"><?php echo esc_html($step['button']); ?> <span aria-hidden="true">›</span></a>
                                        <?php endif; ?>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="hp-steps__photo">
                        <img src="<?php echo esc_url(hp_img('img_steps', 'process-docs.png')); ?>" alt="<?php echo esc_attr(hp_t('steps_alt')); ?>">
                        <div class="hp-steps__badge">
                            <?php hp_icon('icon-docs.svg', 60, 60); ?>
                            <p><?php echo hp_br(hp_t('steps_badge')); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php endif; ?>
        <?php if (hp_on('show_companies')) : ?>
        <section class="hp-companies" id="companies">
            <div class="hp-container">
                <div class="hp-companies__grid">
                    <div class="hp-companies__copy">
                        <h2 class="hp-title hp-title--tight"><?php echo esc_html(hp_t('companies_title')); ?></h2>
                        <p class="hp-companies__lead"><?php echo esc_html(hp_t('companies_lead')); ?></p>
                        <ul class="hp-companies__list">
                            <?php foreach (hp_rows('companies_list') as $item) : ?>
                                <li><?php echo esc_html($item['text']); ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <p class="hp-companies__lead"><?php echo esc_html(hp_t('companies_lead_2')); ?></p>
                        <div class="hp-companies__actions">
                            <a class="hp-btn hp-btn--cyan hp-btn--wide" href="<?php echo esc_url(hp_t('companies_btn_1_url')); ?>"><?php echo esc_html(hp_t('companies_btn_1')); ?></a>
                            <a class="hp-btn hp-btn--navy hp-btn--wide" href="<?php echo esc_url(hp_t('companies_btn_2_url')); ?>"><?php echo esc_html(hp_t('companies_btn_2')); ?></a>
                        </div>
                    </div>
                    <div class="hp-companies__photo">
                        <img src="<?php echo esc_url(hp_img('img_classroom', 'classroom.png')); ?>" alt="<?php echo esc_attr(hp_t('classroom_alt')); ?>">
                    </div>
                </div>
            </div>
        </section>

        <?php endif; ?>
        <?php if (hp_on('show_partners')) : ?>
        <section class="hp-partners" id="partners">
            <div class="hp-container">
                <h2 class="hp-title"><?php echo esc_html(hp_t('partners_title')); ?></h2>
                <div class="hp-slider">
                    <div class="hp-slider__viewport">
                        <div class="hp-slider__track">
                            <?php foreach (hp_rows('partners') as $partner) : ?>
                                <article class="hp-partner"><img src="<?php echo esc_url(hp_media_url(isset($partner['image']) ? $partner['image'] : null, isset($partner['file']) ? $partner['file'] : '')); ?>" alt="<?php echo esc_attr($partner['alt']); ?>"></article>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="hp-slider__nav">
                        <button class="hp-slider__btn hp-slider__btn--prev" type="button" aria-label="Назад"><?php hp_icon('icon-arrow-left.svg', 20, 20); ?></button>
                        <button class="hp-slider__btn hp-slider__btn--next" type="button" aria-label="Вперёд"><?php hp_icon('icon-arrow-left.svg', 20, 20); ?></button>
                    </div>
                </div>
            </div>
        </section>

        <?php endif; ?>
        <?php if (hp_on('show_docs')) : ?>
        <section class="hp-docs" id="documents">
            <div class="hp-container">
                <h2 class="hp-title"><?php echo esc_html(hp_t('docs_title')); ?></h2>
                <div class="hp-docs__grid">
                    <?php foreach (hp_rows('docs') as $doc) : ?>
                        <article class="hp-doc">
                            <div class="hp-doc__sheet"><span class="hp-doc__glyph" aria-hidden="true"></span></div>
                            <p><?php echo hp_br($doc['text']); ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <?php endif; ?>
        <?php if (hp_on('show_reviews')) : ?>
        <section class="hp-reviews" id="reviews">
            <div class="hp-container">
                <h2 class="hp-title hp-title--light"><?php echo esc_html(hp_t('reviews_title')); ?></h2>
                <div class="hp-slider">
                    <div class="hp-slider__viewport">
                        <div class="hp-slider__track hp-reviews__track">
                            <?php foreach (hp_rows('reviews') as $review) : ?>
                                <article class="hp-review">
                                    <div class="hp-review__top">
                                        <div class="hp-review__quote"><?php hp_icon('icon-quote.svg', 26, 26); ?></div>
                                        <p><?php echo esc_html($review['text']); ?></p>
                                    </div>
                                    <div class="hp-review__line"></div>
                                    <p class="hp-review__author"><strong><?php echo esc_html($review['author']); ?></strong> · <?php echo esc_html($review['role']); ?></p>
                                    <div class="hp-review__meta">
                                        <span><?php echo esc_html($review['date']); ?></span>
                                        <span><?php echo esc_html($review['source']); ?></span>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="hp-slider__nav">
                        <button class="hp-slider__btn hp-slider__btn--prev" type="button" aria-label="Назад"><?php hp_icon('icon-arrow-left.svg', 20, 20); ?></button>
                        <button class="hp-slider__btn hp-slider__btn--next" type="button" aria-label="Вперёд"><?php hp_icon('icon-arrow-left.svg', 20, 20); ?></button>
                    </div>
                </div>
            </div>
        </section>

        <?php endif; ?>
        <?php if (hp_on('show_faq')) : ?>
        <section class="hp-faq" id="faq">
            <div class="hp-container">
                <h2 class="hp-title"><?php echo esc_html(hp_t('faq_title')); ?></h2>
                <div class="hp-faq__list">
                    <?php foreach (hp_rows('faq') as $item) : ?>
                        <article class="hp-faq__item">
                            <button class="hp-faq__q" type="button" aria-expanded="false"><?php echo esc_html($item['question']); ?><span class="hp-faq__chevron" aria-hidden="true"></span></button>
                            <div class="hp-faq__a" hidden><?php echo esc_html(isset($item['answer']) ? $item['answer'] : ''); ?></div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <?php endif; ?>
        <?php if (hp_on('show_consult')) : ?>
        <section class="hp-consult" id="consult">
            <div class="hp-container">
                <div class="hp-consult__panel">
                    <div class="hp-consult__info">
                        <div>
                            <h2><?php echo hp_br(hp_t('consult_title')); ?></h2>
                            <p class="hp-consult__lead"><?php echo esc_html(hp_t('consult_lead')); ?></p>
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
                        <h3><?php echo esc_html(hp_t('consult_form_title')); ?></h3>
                        <div class="hp-form__fields">
                            <label class="hp-field">
                                <span class="hp-visually-hidden">Направление</span>
                                <select name="training_direction" required>
                                    <option value="" selected><?php echo esc_html(hp_t('consult_placeholder')); ?></option>
                                    <?php foreach (hp_rows('consult_options') as $opt) : ?>
                                        <option><?php echo esc_html($opt['label']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                            <label class="hp-field">
                                <span class="hp-visually-hidden">Имя</span>
                                <input type="text" name="name" placeholder="<?php echo esc_attr(hp_t('consult_name')); ?>" required>
                            </label>
                            <label class="hp-field">
                                <span class="hp-visually-hidden">Телефон</span>
                                <input class="input-phone" type="tel" name="phone" inputmode="numeric" placeholder="<?php echo esc_attr(hp_t('consult_phone')); ?>" required>
                            </label>
                        </div>
                        <input type="hidden" name="form_type" value="Главная">
                        <div class="hp-form__checks">
                            <label class="hp-check">
                                <input type="checkbox" name="pd_consent" value="1" checked required>
                                <span><?php echo hp_rich(hp_t('consent_text')); ?></span>
                            </label>
                            <label class="hp-check">
                                <input type="checkbox" name="pd_consent_info" value="1" checked required>
                                <span><?php echo hp_rich(hp_t('consent_text_2')); ?></span>
                            </label>
                        </div>
                        <button class="hp-btn hp-btn--cyan hp-btn--submit" type="submit"><?php echo esc_html(hp_t('consult_submit')); ?> <?php hp_icon('icon-arrow.svg', 26, 20); ?></button>
                    </form>
                </div>
            </div>
        </section>

        <?php endif; ?>
        <?php if (hp_on('show_contacts')) : ?>
        <section class="hp-contacts" id="contacts">
            <div class="hp-container">
                <h2 class="hp-title"><?php echo esc_html(hp_t('contacts_title')); ?></h2>
                <div class="hp-contacts__grid">
                    <?php foreach (hp_rows('offices') as $office) : ?>
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
        <?php endif; ?>
    </main>

    <footer class="hp-footer">
        <div class="hp-footer__rule"></div>
        <div class="hp-container hp-footer__main">
            <div class="hp-footer__brand">
                <img class="hp-footer__logo" src="<?php echo esc_url($logo); ?>" alt="<?php echo esc_attr(hp_t('hero_card_title')); ?>">
                <p class="hp-footer__legal"><?php echo hp_rich(hp_t('footer_legal')); ?></p>
                <div class="hp-phones hp-phones--footer">
                    <div class="hp-phones__main">
                        <a href="tel:<?php echo esc_attr(hp_tel(hp_t('phone_1'))); ?>"><?php echo esc_html(hp_t('phone_1')); ?></a>
                        <a href="tel:<?php echo esc_attr(hp_tel(hp_t('phone_2'))); ?>"><?php echo esc_html(hp_t('phone_2')); ?></a>
                    </div>
                    <div class="hp-phones__dir">
                        <a href="tel:<?php echo esc_attr(hp_tel(hp_t('phone_boss'))); ?>"><?php echo esc_html(hp_t('phone_boss')); ?></a>
                        <span><?php echo hp_br(hp_t('phone_boss_note')); ?></span>
                    </div>
                </div>
                <a class="hp-footer__mail" href="mailto:<?php echo esc_attr(hp_t('footer_email')); ?>"><?php echo esc_html(hp_t('footer_email')); ?></a>
            </div>
            <?php foreach (hp_rows('footer_menus') as $col) : ?>
                <div class="hp-footer__col">
                    <h3 class="hp-footer__title"><?php echo hp_br($col['title']); ?></h3>
                    <div class="hp-footer__content">
                        <ul>
                            <?php foreach ($col['links'] as $link) : ?>
                                <li><a<?php echo !empty($link['class']) ? ' class="' . esc_attr($link['class']) . '"' : ''; ?> href="<?php echo esc_url($link['url']); ?>"><?php echo esc_html($link['label']); ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                        <?php if ($col['title'] === 'Документы') : ?>
                            <button class="hp-footer__vi hp-vi" type="button" aria-pressed="false"><?php echo esc_html(hp_t('vi_label')); ?></button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="hp-footer__bar">
            <div class="hp-container hp-footer__bar-inner">
                <div class="hp-footer__bar-links">
                    <a href="/privacy-policy/">Политика конфиденциальности</a>
                    <a href="/soglasie-na-obrabotku-personalnyh-dannyh/">Согласие на обработку персональных данных</a>
                </div>
                <p class="hp-footer__copy"><?php echo esc_html(hp_t('footer_copy')); ?> <span>Сделано в <img src="<?php echo esc_url(hp_theme_img('logo-sheer.svg')); ?>" alt="Шер" width="57" height="13"></span></p>
            </div>
        </div>
    </footer>
</div>
<?php require_once get_template_directory() . '/recaptcha-config.php'; ?>
<script>
window.specRecaptchaSiteKey = <?php echo wp_json_encode(spec_recaptcha_site_key()); ?>;
</script>
<script src="https://www.google.com/recaptcha/api.js?render=<?php echo esc_attr(spec_recaptcha_site_key()); ?>"></script>
<script src="<?php echo esc_url(JS_DIR . '/recaptcha.js?v=1'); ?>"></script>
<?php wp_footer(); ?>
</body>
</html>
