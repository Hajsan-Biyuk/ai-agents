<?php
/**
 * Общие функции страниц на вёрстке «hp»: главная и внутренние страницы.
 * Тексты и фото читаются из групп полей ACF, поля в теме не регистрируются.
 */

if (!defined('ABSPATH')) {
    exit;
}

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

function hp_t($key, $post_id = null) {
    $defaults = hp_default_content();
    $fallback = $post_id === null && isset($defaults[$key]) && is_string($defaults[$key]) ? $defaults[$key] : '';
    if (function_exists('get_field')) {
        $where = hp_store($key) ?: $post_id;
        $value = $where ? get_field($key, $where) : get_field($key);
        if (is_string($value) && $value !== '') {
            return $value;
        }
    }
    return $fallback;
}

function hp_rows($key, $post_id = null) {
    $defaults = hp_default_content();
    $fallback = $post_id === null && isset($defaults[$key]) && is_array($defaults[$key]) ? $defaults[$key] : array();
    if (function_exists('get_field')) {
        $where = hp_store($key) ?: $post_id;
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

function hp_img($key, $file, $post_id = null) {
    $value = null;
    if (function_exists('get_field')) {
        $where = hp_store($key) ?: $post_id;
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

/** ID главной: общие для сайта блоки (контакты, согласия) берутся с неё. */
function hp_front_id() {
    return (int) get_option('page_on_front');
}

/** Якорь главной на внутренней странице ведёт на главную. */
function hp_href($url) {
    if (is_string($url) && strpos($url, '#') === 0 && !is_front_page()) {
        return home_url('/') . $url;
    }
    return $url;
}

