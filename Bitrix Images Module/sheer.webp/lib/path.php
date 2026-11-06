<?php

namespace Sheer\Webp;

class Path
{
    private const CONVERTIBLE = ['jpg', 'jpeg', 'png', 'bmp'];
    private const SKIP_EXT = ['gif', 'svg', 'svgz', 'ico', 'webp', 'avif', 'tif', 'tiff'];

    public static function documentRoot()
    {
        return Config::getDocumentRoot();
    }

    public static function normalizeRelative($path)
    {
        $path = str_replace('\\', '/', (string)$path);
        if ($path === '') {
            return '';
        }

        if (preg_match('~^https?://[^/]+(/.*)$~i', $path, $m)) {
            $path = $m[1];
        }

        $qPos = strpos($path, '?');
        if ($qPos !== false) {
            $path = substr($path, 0, $qPos);
        }

        if ($path === '' || $path[0] !== '/') {
            $path = '/' . ltrim($path, '/');
        }

        $path = preg_replace('~/{2,}~', '/', $path);

        return $path;
    }

    public static function toAbsolute($relative)
    {
        $relative = self::normalizeRelative($relative);
        if ($relative === '') {
            return '';
        }

        return self::documentRoot() . $relative;
    }

    public static function toRelative($absolute)
    {
        $absolute = str_replace('\\', '/', (string)$absolute);
        $root = self::documentRoot();
        if ($root !== '' && strpos($absolute, $root) === 0) {
            $relative = substr($absolute, strlen($root));
            return self::normalizeRelative($relative === '' ? '/' : $relative);
        }

        return self::normalizeRelative($absolute);
    }

    public static function fromUnknown($path)
    {
        $path = str_replace('\\', '/', (string)$path);
        if ($path === '') {
            return '';
        }

        $root = self::documentRoot();
        if ($root !== '' && strpos($path, $root) === 0) {
            return self::toRelative($path);
        }

        if (preg_match('~^https?://~i', $path)) {
            return self::normalizeRelative($path);
        }

        return self::normalizeRelative($path);
    }

    public static function webpRelative($originalRelative)
    {
        return self::normalizeRelative($originalRelative) . '.webp';
    }

    public static function webpAbsolute($originalAbsolute)
    {
        return rtrim(str_replace('\\', '/', (string)$originalAbsolute), '/') . '.webp';
    }

    public static function extension($path)
    {
        $name = strtolower((string)pathinfo(self::stripWebpSuffix($path), PATHINFO_EXTENSION));

        return $name;
    }

    public static function stripWebpSuffix($path)
    {
        $path = str_replace('\\', '/', (string)$path);
        if (preg_match('~\.webp$~i', $path) && preg_match('~\.(jpe?g|png|bmp)\.webp$~i', $path)) {
            return substr($path, 0, -5);
        }

        return $path;
    }

    public static function isConvertibleExtension($path)
    {
        $path = str_replace('\\', '/', (string)$path);
        if (preg_match('~\.webp$~i', $path)) {
            return false;
        }

        $ext = self::extension($path);
        if (in_array($ext, self::SKIP_EXT, true)) {
            return false;
        }

        return in_array($ext, self::CONVERTIBLE, true);
    }

    public static function isInsideDocumentRoot($absolute)
    {
        $absolute = str_replace('\\', '/', (string)$absolute);
        $root = self::documentRoot();
        if ($root === '' || $absolute === '') {
            return false;
        }

        $realRoot = realpath($root);
        $base = rtrim($realRoot !== false ? str_replace('\\', '/', $realRoot) : $root, '/');

        $probe = $absolute;
        $real = realpath($probe);
        while ($real === false && $probe !== '/' && $probe !== '.' && $probe !== '') {
            $parent = dirname($probe);
            if ($parent === $probe) {
                break;
            }
            $probe = $parent;
            $real = realpath($probe);
        }

        $check = rtrim($real !== false ? str_replace('\\', '/', $real) : $absolute, '/');

        return $check === $base || strpos($check, $base . '/') === 0;
    }

    public static function isExcludedPath($relative)
    {
        $relative = strtolower(self::normalizeRelative($relative));
        $name = strtolower(basename($relative));

        $skipFragments = [
            '/bitrix/modules/',
            '/bitrix/components/',
            '/bitrix/js/',
            '/bitrix/panel/',
            '/bitrix/themes/',
            '/bitrix/images/',
            '/bitrix/backup/',
            '/bitrix/tmp/',
            '/upload/tmp/',
            '/upload/resize_cache/tmp/',
            '/.git/',
            '/node_modules/',
        ];

        foreach ($skipFragments as $fragment) {
            if (strpos($relative, $fragment) !== false) {
                return true;
            }
        }

        if (preg_match('/(favicon|apple-touch-icon|android-chrome|mstile|safari-pinned)/i', $name)) {
            return true;
        }

        if (preg_match('#/(icons?|favicons?|sprites?)/#', $relative)) {
            return true;
        }

        if (preg_match('/(^|[-_.])icon([-_.]|$)/i', $name) && !preg_match('/\.(jpe?g)$/i', $name)) {
            return true;
        }

        return false;
    }

    public static function isIconFile($absolute)
    {
        if (!is_file($absolute)) {
            return false;
        }

        $info = @getimagesize($absolute);
        if (!is_array($info) || empty($info[0]) || empty($info[1])) {
            return false;
        }

        $width = (int)$info[0];
        $height = (int)$info[1];

        return self::isTooSmall($width, $height);
    }

    public static function isTooSmall($width, $height)
    {
        $width = (int)$width;
        $height = (int)$height;
        if ($width <= 0 || $height <= 0) {
            return false;
        }

        $min = Config::getSkipMinPx();

        return $width <= $min && $height <= $min;
    }

    public static function isTinyResizeFromPath($relative)
    {
        $relative = self::normalizeRelative($relative);
        if (!preg_match('#/resize_cache/.*/(\d+)[_x](\d+)(?:_\d+)?/#i', $relative, $m)) {
            return false;
        }

        return self::isTooSmall((int)$m[1], (int)$m[2]);
    }

    public static function isMassScanSkipped($relative)
    {
        $relative = strtolower(self::normalizeRelative($relative));
        if ($relative === '/upload/resize_cache' || strpos($relative, '/upload/resize_cache/') === 0) {
            return !Config::scanResizeCache();
        }

        return false;
    }

    public static function isTinyResizeParams($arParams, $arImageSize = null)
    {
        $width = 0;
        $height = 0;

        if (is_array($arImageSize)) {
            $width = (int)($arImageSize[0] ?? $arImageSize['width'] ?? $arImageSize['WIDTH'] ?? 0);
            $height = (int)($arImageSize[1] ?? $arImageSize['height'] ?? $arImageSize['HEIGHT'] ?? 0);
        }

        if (($width <= 0 || $height <= 0) && is_array($arParams)) {
            $size = $arParams;
            if (isset($arParams[0]) && is_array($arParams[0])) {
                $size = $arParams[0];
            }
            $width = (int)($size['width'] ?? $size['WIDTH'] ?? 0);
            $height = (int)($size['height'] ?? $size['HEIGHT'] ?? 0);
        }

        return self::isTooSmall($width, $height);
    }

    public static function shouldConvert($relative, $absolute = '')
    {
        $relative = self::normalizeRelative($relative);
        if ($relative === '' || !self::isConvertibleExtension($relative) || self::isExcludedPath($relative)) {
            return false;
        }

        if (self::isTinyResizeFromPath($relative)) {
            return false;
        }

        if ($absolute === '') {
            $absolute = self::toAbsolute($relative);
        }

        if ($absolute === '' || !is_file($absolute) || !self::isInsideDocumentRoot($absolute)) {
            return false;
        }

        if (self::isIconFile($absolute)) {
            return false;
        }

        return true;
    }
}
