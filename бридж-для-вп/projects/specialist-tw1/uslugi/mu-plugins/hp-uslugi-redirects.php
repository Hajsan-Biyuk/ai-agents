<?php
/**
 * Plugin Name: Редиректы курсов и услуг
 * Description: Старые адреса страниц услуг /<название>/ ведут на /uslugi/<название>/ с кодом 301.
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('template_redirect', function () {
    if (!is_404()) {
        return;
    }
    $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
    $path = trim((string) parse_url($uri, PHP_URL_PATH), '/');
    if ($path === '' || strpos($path, '/') !== false) {
        return;
    }
    $slug = rawurldecode($path);
    $post = get_page_by_path($slug, OBJECT, 'uslugi');
    if (!$post || $post->post_status !== 'publish') {
        return;
    }
    $url = get_permalink($post);
    $qs = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
    if ($qs !== '') {
        $url .= (strpos($url, '?') === false ? '?' : '&') . $qs;
    }
    wp_safe_redirect($url, 301);
    exit;
}, 1);
