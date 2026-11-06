<?php
/**
 * Главная страница. Тексты и фото читаются из группы полей «Главная страница».
 */


require_once get_template_directory() . '/hp-core.php';

get_header();
?>
<div class="hp">
    <?php get_template_part('hp-header'); ?>


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
                            <?php hp_icon('icon-check-circle.svg', 60, 60); ?>
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
                            <div class="hp-doc__sheet"><img src="<?php echo esc_url(hp_theme_img('icon-doc-placeholder.svg')); ?>" alt="" width="40" height="40"></div>
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
        <?php if (hp_on('show_faq')) { $hp_p = ''; include get_template_directory() . '/hp-faq.php'; } ?>
        <?php if (hp_on('show_consult')) { $hp_p = ''; include get_template_directory() . '/hp-consult.php'; } ?>
        <?php if (hp_on('show_contacts')) { $hp_p = ''; include get_template_directory() . '/hp-contacts.php'; } ?>
    </main>

<?php get_template_part('hp-footer'); ?>
