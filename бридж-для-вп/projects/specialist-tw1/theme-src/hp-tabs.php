<?php
/**
 * Одноразово: шапка и подвал на страницу настроек, блоки главной по вкладкам.
 * После запуска файл убирается из темы.
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('acf/init', 'hp_split_home_fields', 30);

function hp_split_home_fields() {
    if (get_option('hp_tabs_ready') === '1') {
        return;
    }
    if (!function_exists('acf_get_fields') || !function_exists('acf_import_field_group') || !function_exists('update_field')) {
        return;
    }

    $page_id = (int) get_option('page_on_front');
    if (!$page_id) {
        return;
    }

    $option_names = array(
        'phone_1', 'phone_2', 'phone_boss', 'phone_boss_note',
        'messenger_tg_url', 'messenger_max_url', 'header_consult',
        'img_logo', 'nav',
        'footer_legal', 'footer_email', 'footer_copy', 'vi_label', 'footer_menus',
    );
    $tabs = array(
        'show_banner' => array('Баннер', array(
            'hero_title_start', 'hero_title_accent', 'hero_title_place',
            'hero_lead_1', 'hero_lead_2', 'hero_btn_1', 'hero_btn_1_url', 'hero_btn_2', 'hero_btn_2_url',
            'hero_price', 'hero_price_note', 'hero_card_title', 'hero_card_text', 'hero_card_link', 'hero_card_link_url',
            'hero_alt', 'hero_card_alt', 'img_hero', 'img_hero_card', 'hero_pills',
        )),
        'show_needs' => array('Что вам нужно', array('needs_title', 'needs')),
        'show_courses' => array('Направления', array(
            'courses_title', 'spec_title', 'spec_cats', 'spec_note', 'spec_cta', 'spec_cta_url', 'catalog_alt', 'img_catalog',
            'spec_programs', 'jobs_title', 'jobs_programs', 'safety_title', 'safety_programs',
            'retrain_title', 'retrain_programs', 'boats_title', 'boats_programs',
        )),
        'show_tractor' => array('Тракторист', array(
            'tractor_badge', 'tractor_title', 'tractor_btn_1', 'tractor_btn_1_url', 'tractor_btn_2', 'tractor_btn_2_url',
            'tractor_place', 'tractor_alt', 'img_tractor', 'tractor_perks',
        )),
        'show_polygon' => array('Полигон', array(
            'polygon_title', 'polygon_video_title', 'polygon_video_text', 'polygon_alt',
            'polygon_where_title', 'polygon_where_text', 'polygon_cta', 'polygon_cta_url',
            'img_polygon', 'polygon_shots',
        )),
        'show_about' => array('Почему мы', array('about_badge', 'about_title', 'about_items')),
        'show_steps' => array('Как проходит обучение', array('steps_title', 'steps_badge', 'steps_alt', 'img_steps', 'steps')),
        'show_companies' => array('Компаниям', array(
            'companies_title', 'companies_lead', 'companies_lead_2', 'companies_btn_1', 'companies_btn_1_url',
            'companies_btn_2', 'companies_btn_2_url', 'classroom_alt', 'img_classroom', 'companies_list',
        )),
        'show_partners' => array('Партнёры', array('partners_title', 'partners')),
        'show_docs' => array('Документы', array('docs_title', 'docs')),
        'show_reviews' => array('Отзывы', array('reviews_title', 'reviews')),
        'show_faq' => array('Вопрос-ответ', array('faq_title', 'faq')),
        'show_consult' => array('Консультация', array(
            'consult_title', 'consult_lead', 'consult_form_title', 'consult_placeholder', 'consult_name', 'consult_phone',
            'consult_submit', 'consent_text', 'consent_text_2', 'consult_options',
        )),
        'show_contacts' => array('Контакты', array('contacts_title', 'offices')),
    );

    $saved = array();
    foreach ($option_names as $name) {
        $saved[$name] = get_field($name, $page_id);
    }

    $by_name = array();
    foreach ((array) acf_get_fields('group_hp_home') as $field) {
        if (!empty($field['name'])) {
            $by_name[$field['name']] = hp_clean_acf_field($field);
        }
    }
    if (!$by_name) {
        return;
    }

    $option_fields = array();
    foreach ($option_names as $name) {
        if (isset($by_name[$name])) {
            $option_fields[] = $by_name[$name];
        }
    }

    $home_fields = array();
    foreach ($tabs as $show_key => $tab) {
        $home_fields[] = array(
            'key' => 'field_hp_tab_' . $show_key,
            'label' => $tab[0],
            'name' => '',
            'type' => 'tab',
            'placement' => 'top',
        );
        $home_fields[] = array(
            'key' => 'field_hp_' . $show_key,
            'label' => 'Показать',
            'name' => $show_key,
            'type' => 'true_false',
            'ui' => 1,
            'default_value' => 1,
            'ui_on_text' => 'Да',
            'ui_off_text' => 'Нет',
        );
        foreach ($tab[1] as $name) {
            if (!isset($by_name[$name])) {
                continue;
            }
            $field = $by_name[$name];
            $field['label'] = str_replace(
                array('героя', 'Героя'),
                array('баннера', 'Баннера'),
                $field['label']
            );
            $home_fields[] = $field;
        }
    }

    acf_import_field_group(array(
        'key' => 'group_hp_header_footer',
        'title' => 'Шапка и подвал',
        'fields' => $option_fields,
        'location' => array(
            array(
                array(
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'hp-header-footer',
                ),
            ),
        ),
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'active' => true,
    ));

    acf_import_field_group(array(
        'key' => 'group_hp_home',
        'title' => 'Главная страница',
        'fields' => $home_fields,
        'location' => array(
            array(
                array(
                    'param' => 'page_type',
                    'operator' => '==',
                    'value' => 'front_page',
                ),
            ),
        ),
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'active' => true,
    ));

    foreach ($saved as $name => $value) {
        $value = hp_acf_ids($value);
        if ($value === null || $value === false || $value === '') {
            continue;
        }
        update_field($name, $value, 'option');
    }
    foreach (array_keys($tabs) as $show_key) {
        update_field($show_key, 1, $page_id);
    }

    update_option('hp_tabs_ready', '1', false);
}

function hp_clean_acf_field($field) {
    foreach (array('ID', 'id', 'parent', 'menu_order', 'prefix', '_name', '_valid', '_prepare', 'class') as $drop) {
        unset($field[$drop]);
    }
    if (!empty($field['sub_fields']) && is_array($field['sub_fields'])) {
        foreach ($field['sub_fields'] as $index => $sub) {
            $field['sub_fields'][$index] = hp_clean_acf_field($sub);
        }
    }
    return $field;
}

function hp_acf_ids($value) {
    if (!is_array($value)) {
        return $value;
    }
    if (isset($value['ID'], $value['url'])) {
        return (int) $value['ID'];
    }
    foreach ($value as $key => $item) {
        $value[$key] = hp_acf_ids($item);
    }
    return $value;
}
