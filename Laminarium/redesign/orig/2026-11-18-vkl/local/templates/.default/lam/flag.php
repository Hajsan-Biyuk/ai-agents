<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

// Флаг предпросмотра редизайна: ?lam_preview=1 включает (cookie 30 дней), ?lam_preview=0 выключает.
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
                setcookie('lam_preview', $on ? '1' : '', $on ? time() + 30 * 86400 : time() - 3600, '/');
            }
            return $on;
        }
        $on = isset($_COOKIE['lam_preview']) && $_COOKIE['lam_preview'] === '1';
        return $on;
    }
}
