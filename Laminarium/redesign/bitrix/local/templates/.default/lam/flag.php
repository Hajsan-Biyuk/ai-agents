<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

// Редизайн включён для всех с 18.11.2026. ОТКАТ: поставить false — все снова увидят старую версию.
if (!defined('LAM_DEFAULT_ON')) {
    define('LAM_DEFAULT_ON', true);
}

// ?lam_preview=0 — показать себе старую версию (cookie 30 дней), ?lam_preview=1 — вернуть новую.
// При LAM_DEFAULT_ON=false работает как раньше: новая только у тех, кто открыл ?lam_preview=1.
if (!function_exists('lam_preview')) {
    function lam_preview()
    {
        static $on = null;
        if ($on !== null) {
            return $on;
        }
        if (isset($_GET['lam_preview'])) {
            $on = $_GET['lam_preview'] === '1';
            if (!headers_sent()) {
                setcookie('lam_preview', $on ? '1' : '0', time() + 30 * 86400, '/');
            }
            return $on;
        }
        if (isset($_COOKIE['lam_preview']) && in_array($_COOKIE['lam_preview'], array('0', '1'), true)) {
            $on = $_COOKIE['lam_preview'] === '1';
            return $on;
        }
        $on = LAM_DEFAULT_ON;
        return $on;
    }
}
