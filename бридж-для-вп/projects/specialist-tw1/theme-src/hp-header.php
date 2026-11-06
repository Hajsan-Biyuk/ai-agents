<?php
/** Шапка страниц «hp». Данные — группа полей «Шапка и подвал». */
if (!defined('ABSPATH')) {
    exit;
}
$logo = hp_img('img_logo', 'logo-4faf96.png');
?>
    <div class="hp-topline"></div>
    <header class="hp-header">
        <div class="hp-container">
            <div class="hp-header__top">
                <a class="hp-header__logo" href="<?php echo esc_url(is_front_page() ? '#hero' : home_url('/')); ?>">
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
                                <a class="hp-nav__drop-toggle" href="<?php echo esc_url(hp_href($item['url'])); ?>"><?php echo esc_html($item['title']); ?> <span aria-hidden="true">▾</span></a>
                                <ul class="hp-nav__drop">
                                    <?php foreach ($children as $child) : ?>
                                        <li><a href="<?php echo esc_url(hp_href($child['url'])); ?>"><?php echo esc_html($child['title']); ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else : ?>
                                <a href="<?php echo esc_url(hp_href($item['url'])); ?>"><?php echo esc_html($item['title']); ?></a>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>
        </div>
        <div class="hp-menu-mobile">
            <ul>
                <?php foreach (hp_rows('nav') as $item) : ?>
                    <li><a href="<?php echo esc_url(hp_href($item['url'])); ?>"><?php echo esc_html($item['title']); ?></a></li>
                    <?php if (!empty($item['children']) && is_array($item['children'])) :
                        foreach ($item['children'] as $child) : ?>
                            <li><a href="<?php echo esc_url(hp_href($child['url'])); ?>"><?php echo esc_html($child['title']); ?></a></li>
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
