<?php
/**
 * Одноразовый импорт группы «Главная страница» в базу ACF.
 * После успешного запуска файл убирается из темы.
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('acf/init', 'hp_install_home_fields', 20);

function hp_install_home_fields() {
    if (get_option('hp_home_installed') === '1') {
        return;
    }
    if (!function_exists('acf_import_field_group') || !function_exists('update_field')) {
        return;
    }

    @set_time_limit(300);
    if (function_exists('wp_raise_memory_limit')) {
        wp_raise_memory_limit('image');
    }

    require_once get_template_directory() . '/hp-content.php';
    $content = hp_default_content();

    $text = function ($name, $label, $type) {
        return array(
            'key' => 'field_hp_' . $name,
            'label' => $label,
            'name' => $name,
            'type' => $type,
        );
    };
    $image = function ($name, $label) {
        return array(
            'key' => 'field_hp_' . $name,
            'label' => $label,
            'name' => $name,
            'type' => 'image',
            'return_format' => 'array',
            'preview_size' => 'medium',
            'library' => 'all',
        );
    };
    $sub = function ($parent, $name, $label, $type, $extra = array()) {
        return array_merge(array(
            'key' => 'field_hp_' . $parent . '__' . $name,
            'label' => $label,
            'name' => $name,
            'type' => $type,
        ), $extra);
    };
    $rep = function ($name, $label, $subs) {
        return array(
            'key' => 'field_hp_' . $name,
            'label' => $label,
            'name' => $name,
            'type' => 'repeater',
            'layout' => 'block',
            'button_label' => 'Добавить',
            'sub_fields' => $subs,
        );
    };
    $img_extra = array('return_format' => 'array', 'preview_size' => 'medium', 'library' => 'all');

    $labels = array(
        'phone_1' => 'Телефон',
        'phone_2' => 'Телефон 2',
        'phone_boss' => 'Телефон руководителя',
        'phone_boss_note' => 'Подпись к телефону руководителя',
        'messenger_tg_url' => 'Ссылка Telegram',
        'messenger_max_url' => 'Ссылка Max',
        'header_consult' => 'Кнопка консультации в шапке',
        'hero_title_start' => 'Заголовок героя, начало',
        'hero_title_accent' => 'Заголовок героя, акцент',
        'hero_title_place' => 'Заголовок героя, место',
        'hero_lead_1' => 'Подзаголовок героя 1',
        'hero_lead_2' => 'Подзаголовок героя 2',
        'hero_btn_1' => 'Кнопка героя 1',
        'hero_btn_1_url' => 'Ссылка кнопки героя 1',
        'hero_btn_2' => 'Кнопка героя 2',
        'hero_btn_2_url' => 'Ссылка кнопки героя 2',
        'hero_price' => 'Цена в карточке героя',
        'hero_price_note' => 'Подпись цены',
        'hero_card_title' => 'Заголовок карточки героя',
        'hero_card_text' => 'Текст карточки героя',
        'hero_card_link' => 'Ссылка карточки героя',
        'hero_card_link_url' => 'Адрес ссылки карточки',
        'hero_alt' => 'Подпись фото героя',
        'hero_card_alt' => 'Подпись фото карточки',
        'needs_title' => 'Заголовок «Что вам нужно»',
        'courses_title' => 'Заголовок направлений',
        'spec_title' => 'Заголовок спецтехники',
        'spec_cats' => 'Категории самоходных машин',
        'spec_note' => 'Плашка на фото спецтехники',
        'spec_cta' => 'Кнопка удостоверения',
        'spec_cta_url' => 'Ссылка кнопки удостоверения',
        'catalog_alt' => 'Подпись фото каталога',
        'jobs_title' => 'Заголовок рабочих профессий',
        'safety_title' => 'Заголовок охраны труда',
        'retrain_title' => 'Заголовок переподготовки',
        'boats_title' => 'Заголовок маломерных судов',
        'tractor_badge' => 'Бейдж тракториста',
        'tractor_title' => 'Заголовок блока тракториста',
        'tractor_btn_1' => 'Кнопка тракториста 1',
        'tractor_btn_1_url' => 'Ссылка кнопки тракториста 1',
        'tractor_btn_2' => 'Кнопка тракториста 2',
        'tractor_btn_2_url' => 'Ссылка кнопки тракториста 2',
        'tractor_place' => 'Подпись полигона на фото',
        'tractor_alt' => 'Подпись фото трактора',
        'polygon_title' => 'Заголовок полигона',
        'polygon_video_title' => 'Подпись видео',
        'polygon_video_text' => 'Текст видео',
        'polygon_alt' => 'Подпись главного фото полигона',
        'polygon_where_title' => 'Заголовок географии',
        'polygon_where_text' => 'Города',
        'polygon_cta' => 'Кнопка галереи',
        'polygon_cta_url' => 'Ссылка галереи',
        'about_badge' => 'Бейдж «Почему мы»',
        'about_title' => 'Заголовок «Почему мы»',
        'steps_title' => 'Заголовок шагов',
        'steps_badge' => 'Плашка на фото шагов',
        'steps_alt' => 'Подпись фото шагов',
        'companies_title' => 'Заголовок для компаний',
        'companies_lead' => 'Текст для компаний',
        'companies_lead_2' => 'Второй текст для компаний',
        'companies_btn_1' => 'Кнопка для компаний 1',
        'companies_btn_1_url' => 'Ссылка кнопки 1',
        'companies_btn_2' => 'Кнопка для компаний 2',
        'companies_btn_2_url' => 'Ссылка кнопки 2',
        'classroom_alt' => 'Подпись фото класса',
        'partners_title' => 'Заголовок партнёров',
        'docs_title' => 'Заголовок документов',
        'reviews_title' => 'Заголовок отзывов',
        'faq_title' => 'Заголовок вопросов',
        'consult_title' => 'Заголовок консультации',
        'consult_lead' => 'Текст консультации',
        'consult_form_title' => 'Заголовок формы',
        'consult_placeholder' => 'Плейсхолдер направления',
        'consult_name' => 'Плейсхолдер имени',
        'consult_phone' => 'Плейсхолдер телефона',
        'consult_submit' => 'Кнопка формы',
        'consent_text' => 'Текст согласия 1',
        'consent_text_2' => 'Текст согласия 2',
        'contacts_title' => 'Заголовок контактов',
        'footer_legal' => 'Реквизиты в подвале',
        'footer_email' => 'Почта',
        'footer_copy' => 'Копирайт',
        'vi_label' => 'Кнопка версии для слабовидящих',
    );
    $textarea = array(
        'phone_boss_note', 'hero_card_text', 'tractor_title', 'tractor_place', 'spec_note',
        'steps_badge', 'consult_title', 'consent_text', 'consent_text_2', 'footer_legal',
    );

    $fields = array();
    foreach ($labels as $name => $label) {
        $fields[] = $text($name, $label, in_array($name, $textarea, true) ? 'textarea' : 'text');
    }
    $fields[] = $image('img_logo', 'Логотип');
    $fields[] = $image('img_hero', 'Фото героя');
    $fields[] = $image('img_hero_card', 'Фото карточки героя');
    $fields[] = $image('img_catalog', 'Фото спецтехники');
    $fields[] = $image('img_tractor', 'Фото трактора');
    $fields[] = $image('img_polygon', 'Фото полигона');
    $fields[] = $image('img_steps', 'Фото выдачи документов');
    $fields[] = $image('img_classroom', 'Фото учебного класса');

    $fields[] = $rep('hero_pills', 'Плашки героя', array($sub('hero_pills', 'text', 'Текст', 'text')));
    $fields[] = $rep('nav', 'Меню', array(
        $sub('nav', 'title', 'Пункт', 'text'),
        $sub('nav', 'url', 'Ссылка', 'text'),
        array(
            'key' => 'field_hp_nav__children',
            'label' => 'Подпункты',
            'name' => 'children',
            'type' => 'repeater',
            'layout' => 'table',
            'button_label' => 'Добавить',
            'sub_fields' => array(
                $sub('nav_children', 'title', 'Пункт', 'text'),
                $sub('nav_children', 'url', 'Ссылка', 'text'),
            ),
        ),
    ));
    $fields[] = $rep('needs', 'Блоки «Что вам нужно»', array(
        $sub('needs', 'icon', 'Иконка (файл)', 'text'),
        $sub('needs', 'title', 'Заголовок', 'text'),
        $sub('needs', 'text', 'Текст', 'textarea'),
        $sub('needs', 'audience', 'Для кого', 'text'),
        $sub('needs', 'button', 'Кнопка', 'text'),
        $sub('needs', 'url', 'Ссылка', 'text'),
        $sub('needs', 'style', 'Цвет кнопки', 'select', array('choices' => array('cyan' => 'Бирюзовая', 'navy' => 'Тёмная', 'pale' => 'Светлая'), 'default_value' => 'cyan')),
    ));
    $program = function ($key, $label, $with_price, $with_accent) use ($sub, $rep) {
        $subs = array($sub($key, 'name', 'Название', 'text'));
        if ($with_price) {
            $subs[] = $sub($key, 'price', 'Цена', 'text');
        }
        $subs[] = $sub($key, 'url', 'Ссылка', 'text');
        if ($with_accent) {
            $subs[] = $sub($key, 'accent', 'Выделить', 'true_false', array('ui' => 1));
        }
        return $rep($key, $label, $subs);
    };
    $fields[] = $program('spec_programs', 'Программы спецтехники', true, true);
    $fields[] = $program('jobs_programs', 'Рабочие профессии', true, false);
    $fields[] = $program('safety_programs', 'Охрана труда', false, false);
    $fields[] = $program('retrain_programs', 'Переподготовка', false, false);
    $fields[] = $program('boats_programs', 'Маломерные суда', false, false);
    $fields[] = $rep('tractor_perks', 'Преимущества тракториста', array(
        $sub('tractor_perks', 'icon', 'Иконка (файл)', 'text'),
        $sub('tractor_perks', 'title', 'Заголовок', 'text'),
        $sub('tractor_perks', 'text', 'Текст', 'textarea'),
    ));
    $fields[] = $rep('polygon_shots', 'Фото техники', array(
        $sub('polygon_shots', 'image', 'Фото', 'image', $img_extra),
        $sub('polygon_shots', 'file', 'Файл в теме', 'text'),
        $sub('polygon_shots', 'tag', 'Метка', 'text'),
        $sub('polygon_shots', 'label', 'Подпись', 'text'),
        $sub('polygon_shots', 'alt', 'Alt', 'text'),
    ));
    $fields[] = $rep('about_items', 'Почему мы', array(
        $sub('about_items', 'icon', 'Иконка (файл)', 'text'),
        $sub('about_items', 'title', 'Заголовок', 'text'),
        $sub('about_items', 'text', 'Текст', 'textarea'),
        $sub('about_items', 'note', 'Плашка', 'text'),
    ));
    $fields[] = $rep('steps', 'Шаги обучения', array(
        $sub('steps', 'num', 'Номер', 'text'),
        $sub('steps', 'title', 'Заголовок', 'text'),
        $sub('steps', 'text', 'Текст', 'textarea'),
        $sub('steps', 'button', 'Кнопка', 'text'),
        $sub('steps', 'url', 'Ссылка', 'text'),
    ));
    $fields[] = $rep('companies_list', 'Список для компаний', array($sub('companies_list', 'text', 'Пункт', 'text')));
    $fields[] = $rep('partners', 'Партнёры', array(
        $sub('partners', 'image', 'Логотип', 'image', $img_extra),
        $sub('partners', 'alt', 'Alt', 'text'),
        $sub('partners', 'file', 'Файл в теме', 'text'),
    ));
    $fields[] = $rep('docs', 'Документы', array($sub('docs', 'text', 'Текст', 'textarea')));
    $fields[] = $rep('reviews', 'Отзывы', array(
        $sub('reviews', 'text', 'Текст', 'textarea'),
        $sub('reviews', 'author', 'Имя', 'text'),
        $sub('reviews', 'role', 'Кто и откуда', 'text'),
        $sub('reviews', 'date', 'Дата', 'text'),
        $sub('reviews', 'source', 'Источник', 'text'),
    ));
    $fields[] = $rep('faq', 'Вопросы', array(
        $sub('faq', 'question', 'Вопрос', 'text'),
        $sub('faq', 'answer', 'Ответ', 'textarea'),
    ));
    $fields[] = $rep('consult_options', 'Направления в форме', array($sub('consult_options', 'label', 'Название', 'text')));
    $fields[] = $rep('offices', 'Офисы', array(
        $sub('offices', 'title', 'Название', 'text'),
        $sub('offices', 'address', 'Адрес и время', 'textarea'),
        $sub('offices', 'note', 'Примечание', 'text'),
        $sub('offices', 'image', 'Карта', 'image', $img_extra),
        $sub('offices', 'alt', 'Alt', 'text'),
        $sub('offices', 'file', 'Файл в теме', 'text'),
    ));
    $fields[] = $rep('footer_menus', 'Колонки подвала', array(
        $sub('footer_menus', 'title', 'Заголовок', 'textarea'),
        array(
            'key' => 'field_hp_footer_menus__links',
            'label' => 'Ссылки',
            'name' => 'links',
            'type' => 'repeater',
            'layout' => 'table',
            'button_label' => 'Добавить',
            'sub_fields' => array(
                $sub('footer_links', 'label', 'Текст', 'text'),
                $sub('footer_links', 'url', 'Ссылка', 'text'),
                $sub('footer_links', 'class', 'Класс', 'text'),
            ),
        ),
    ));

    acf_import_field_group(array(
        'key' => 'group_hp_home',
        'title' => 'Главная страница',
        'fields' => $fields,
        'location' => array(
            array(
                array(
                    'param' => 'page_type',
                    'operator' => '==',
                    'value' => 'front_page',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'active' => true,
    ));

    $page = get_page_by_path('glavnaya');
    if ($page) {
        $page_id = (int) $page->ID;
    } else {
        $page_id = (int) wp_insert_post(array(
            'post_type' => 'page',
            'post_status' => 'publish',
            'post_title' => 'Главная',
            'post_name' => 'glavnaya',
            'post_content' => '',
        ));
    }
    if (!$page_id) {
        return;
    }
    update_option('show_on_front', 'page');
    update_option('page_on_front', $page_id);

    $attach = function ($file, $alt) {
        $found = get_posts(array(
            'post_type' => 'attachment',
            'post_status' => 'inherit',
            'posts_per_page' => 1,
            'fields' => 'ids',
            'meta_key' => '_hp_source',
            'meta_value' => $file,
        ));
        if ($found) {
            return (int) $found[0];
        }
        $path = get_template_directory() . '/home/img/' . $file;
        if (!file_exists($path)) {
            return 0;
        }
        $bits = wp_upload_bits($file, null, file_get_contents($path));
        if (!empty($bits['error'])) {
            return 0;
        }
        $filetype = wp_check_filetype($file);
        $id = wp_insert_attachment(array(
            'post_mime_type' => $filetype['type'] ? $filetype['type'] : 'image/png',
            'post_title' => sanitize_text_field(pathinfo($file, PATHINFO_FILENAME)),
            'post_status' => 'inherit',
        ), $bits['file']);
        if (is_wp_error($id) || !$id) {
            return 0;
        }
        require_once ABSPATH . 'wp-admin/includes/image.php';
        wp_update_attachment_metadata($id, wp_generate_attachment_metadata($id, $bits['file']));
        update_post_meta($id, '_hp_source', $file);
        if ($alt) {
            update_post_meta($id, '_wp_attachment_image_alt', $alt);
        }
        return (int) $id;
    };

    $with_images = array('partners', 'polygon_shots', 'offices');
    foreach ($content as $key => $value) {
        if (in_array($key, $with_images, true)) {
            continue;
        }
        update_field($key, $value, $page_id);
    }
    foreach ($with_images as $key) {
        $rows = $content[$key];
        foreach ($rows as $index => $row) {
            if (!empty($row['file'])) {
                $rows[$index]['image'] = $attach($row['file'], isset($row['alt']) ? $row['alt'] : '');
            }
        }
        update_field($key, $rows, $page_id);
    }
    update_field('img_logo', $attach('logo-4faf96.png', 'Крымский центр профессиональной подготовки кадров «Специалист»'), $page_id);
    update_field('img_hero', $attach('hero-bg-7d4ec6.png', $content['hero_alt']), $page_id);
    update_field('img_hero_card', $attach('hero-bg-7d4ec6.png', $content['hero_card_alt']), $page_id);
    update_field('img_catalog', $attach('catalog-tech.png', $content['catalog_alt']), $page_id);
    update_field('img_tractor', $attach('tractor-hero.png', $content['tractor_alt']), $page_id);
    update_field('img_polygon', $attach('polygon-main.png', $content['polygon_alt']), $page_id);
    update_field('img_steps', $attach('process-docs.png', $content['steps_alt']), $page_id);
    update_field('img_classroom', $attach('classroom.png', $content['classroom_alt']), $page_id);

    update_option('hp_home_installed', '1', false);
}
