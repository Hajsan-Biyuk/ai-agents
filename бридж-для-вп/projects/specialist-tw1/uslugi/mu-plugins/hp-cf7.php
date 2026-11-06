<?php
/**
 * Plugin Name: Формы сайта (Contact Form 7)
 * Description: Заявки с сайта идут через Contact Form 7: reCAPTCHA v3 на боевых доменах, копия заявки в Telegram,
 *              отправитель на домене сайта, список направлений дополняется курсами и услугами.
 */

if (!defined('ABSPATH')) {
    exit;
}

/** ID формы заявки (создаётся установщиком, хранится в настройках сайта). */
function hp_cf7_form_id() {
    return (int) get_option('hp_consult_form_id');
}

/** Шорткод формы заявки или пустая строка, если форма не создана или плагин выключен. */
function hp_cf7_shortcode() {
    $id = hp_cf7_form_id();
    if (!$id || !class_exists('WPCF7_ContactForm') || !WPCF7_ContactForm::get_instance($id)) {
        return '';
    }
    return do_shortcode('[contact-form-7 id="' . $id . '"]');
}

// Без автоматических <p> и <br>: разметка формы своя.
add_filter('wpcf7_autop_or_not', '__return_false');

// reCAPTCHA v3: ключи берутся из темы только на боевых доменах (на тестовом домене ключ не работает и форма бы не отправлялась).
add_filter('option_wpcf7', function ($opt) {
    $host = strtolower((string) parse_url(home_url(), PHP_URL_HOST));
    if (!in_array($host, array('xn---082-43d3avb0a0bvgn4c.xn--p1ai', 'специалист-082.рф'), true)) {
        return $opt;
    }
    $cfg = get_template_directory() . '/recaptcha-config.php';
    if (!is_readable($cfg)) {
        return $opt;
    }
    require_once $cfg;
    if (function_exists('spec_recaptcha_site_key') && function_exists('spec_recaptcha_secret')) {
        $opt = is_array($opt) ? $opt : array();
        $opt['recaptcha'] = array(spec_recaptcha_site_key() => spec_recaptcha_secret());
    }
    return $opt;
});

// Отправитель письма — на домене сайта.
add_filter('wpcf7_contact_form_properties', function ($props, $form) {
    if ($form->id() === hp_cf7_form_id() && isset($props['mail']) && is_array($props['mail'])) {
        $host = strtolower((string) parse_url(home_url(), PHP_URL_HOST));
        $props['mail']['sender'] = 'Специалист <no-reply@' . $host . '>';
    }
    return $props;
}, 10, 2);

// Направления: список из формы дополняется курсами и услугами из типа «Курсы и услуги».
add_filter('wpcf7_form_tag', function ($tag) {
    if (!is_array($tag) || empty($tag['name']) || $tag['name'] !== 'training_direction' || strpos((string) $tag['type'], 'select') !== 0) {
        return $tag;
    }
    if (!post_type_exists('uslugi')) {
        return $tag;
    }
    $posts = get_posts(array('post_type' => 'uslugi', 'post_status' => 'publish', 'numberposts' => -1, 'orderby' => array('menu_order' => 'ASC', 'title' => 'ASC')));
    $have = array_map('strval', (array) $tag['values']);
    foreach ($posts as $p) {
        if (!in_array($p->post_title, $have, true)) {
            $tag['raw_values'][] = $p->post_title;
            $tag['values'][] = $p->post_title;
            $tag['labels'][] = $p->post_title;
        }
    }
    if (class_exists('WPCF7_Pipes')) {
        $tag['pipes'] = new WPCF7_Pipes($tag['raw_values']);
    }
    return $tag;
});

// Копия заявки в Telegram (как раньше в mail.php). Уходит до отправки письма, чтобы заявка не потерялась.
add_action('wpcf7_before_send_mail', function ($form) {
    if ($form->id() !== hp_cf7_form_id() || !class_exists('WPCF7_Submission')) {
        return;
    }
    $sub = WPCF7_Submission::get_instance();
    $file = get_template_directory() . '/telegram-lead.php';
    if (!$sub || !is_readable($file)) {
        return;
    }
    require_once $file;
    if (!function_exists('spec_send_telegram')) {
        return;
    }
    $d = $sub->get_posted_data();
    $page_id = (int) $sub->get_meta('container_post_id');
    $msg = 'Имя: ' . trim((string) ($d['client_name'] ?? '')) . "\r\n"
        . 'Номер телефона: ' . trim((string) ($d['client_phone'] ?? '')) . "\r\n"
        . 'Форма: ' . ($page_id ? get_the_title($page_id) : 'Сайт') . "\r\n"
        . 'Согласие на обработку персональных данных: да';
    $dir = trim((string) ($d['training_direction'] ?? ''));
    if ($dir !== '') {
        $msg .= "\r\n" . 'Направление обучения: ' . $dir;
    }
    spec_send_telegram($msg);
}, 10, 1);
