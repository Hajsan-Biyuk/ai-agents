<?php

namespace Sheer\Webp;

class Rewriter
{
    private static $existsCache = [];
    private static $enqueued = 0;

    public static function browserAcceptsWebp()
    {
        $accept = (string)($_SERVER['HTTP_ACCEPT'] ?? '');
        if ($accept !== '' && stripos($accept, 'image/webp') !== false) {
            return true;
        }

        // Фолбэк по UA — только там, где поддержка WebP определяется точно.
        // Настольный Safari 14 на macOS 10.15 и старше WebP не умеет, а UA у него
        // такой же, как на Big Sur (Mac OS X 10_15_7), поэтому Safari без
        // image/webp в Accept получает оригиналы.
        $ua = (string)($_SERVER['HTTP_USER_AGENT'] ?? '');
        if ($ua === '') {
            return false;
        }

        if (preg_match('/(?:iPhone|iPad|iPod).*? OS (\d+)_/i', $ua, $m)) {
            return (int)$m[1] >= 14;
        }
        if (preg_match('/Chrome\/(\d+)/i', $ua, $m) && (int)$m[1] >= 32) {
            return true;
        }
        if (preg_match('/Firefox\/(\d+)/i', $ua, $m) && (int)$m[1] >= 65) {
            return true;
        }
        if (preg_match('/Edg\/(\d+)/i', $ua, $m) && (int)$m[1] >= 18) {
            return true;
        }

        return false;
    }

    /**
     * Композитный кеш Битрикса сохраняет готовый HTML и отдаёт его всем —
     * в том числе браузерам без WebP. Подмена в HTML тогда небезопасна:
     * при композите WebP отдаёт только веб-сервер (.htaccess / nginx).
     */
    public static function isCompositeOn()
    {
        try {
            if (class_exists('\\Bitrix\\Main\\Composite\\Helper') && method_exists('\\Bitrix\\Main\\Composite\\Helper', 'isOn')) {
                return (bool)\Bitrix\Main\Composite\Helper::isOn();
            }
            if (class_exists('\\CHTMLPagesCache') && method_exists('\\CHTMLPagesCache', 'IsOn')) {
                return (bool)\CHTMLPagesCache::IsOn();
            }
        } catch (\Throwable $e) {
            return true;
        }

        return false;
    }

    public static function rewrite($content)
    {
        if (self::isCompositeOn()) {
            return $content;
        }

        if (strlen($content) > 8 * 1024 * 1024) {
            return $content;
        }

        if (
            strpos($content, '/upload/') === false
            && strpos($content, '/local/templates/') === false
            && strpos($content, '/bitrix/templates/') === false
        ) {
            return $content;
        }

        // Ответ зависит от Accept и User-Agent — сообщаем это любому кешу
        // (nginx proxy_cache, CDN), даже если для этого браузера ничего не меняли.
        if (!headers_sent()) {
            header('Vary: Accept, User-Agent', false);
        }

        if (!self::browserAcceptsWebp()) {
            return $content;
        }

        $pattern = '~((?:https?:)?//[^/\"\'\s]+)?(/(?:upload|local/templates|bitrix/templates)/[^\"\'\s\?\#<>]+?)\.(jpe?g|png|bmp)(?!\.webp)~i';

        $rewritten = preg_replace_callback($pattern, static function ($m) {
            $prefix = $m[1] ?? '';
            if ($prefix !== '' && !Rewriter::isOwnHost($prefix)) {
                return $m[0];
            }

            $path = $m[2] . '.' . $m[3];
            $relative = Path::normalizeRelative($path);

            if (!Path::isConvertibleExtension($relative) || Path::isExcludedPath($relative) || Path::isTinyResizeFromPath($relative)) {
                return $m[0];
            }

            if (!self::webpExists($relative)) {
                self::enqueueMissing($relative);
                return $m[0];
            }

            return $prefix . $relative . '.webp';
        }, $content);

        if (!is_string($rewritten)) {
            return $content;
        }

        return $rewritten;
    }

    private static function webpExists($relative)
    {
        if (isset(self::$existsCache[$relative])) {
            return self::$existsCache[$relative];
        }

        // Копия старее оригинала (оригинал заменили) — не отдаём её.
        $abs = Path::toAbsolute(Path::webpRelative($relative));
        $orig = Path::toAbsolute($relative);
        $exists = $abs !== '' && is_file($abs)
            && (!is_file($orig) || filemtime($abs) >= filemtime($orig));
        self::$existsCache[$relative] = $exists;

        return $exists;
    }

    public static function clearCache()
    {
        self::$existsCache = [];
        self::$enqueued = 0;
    }

    private static function enqueueMissing($relative)
    {
        if (self::$enqueued >= Config::getEnqueuePerRequest()) {
            return;
        }
        if (!Path::shouldConvert($relative)) {
            return;
        }

        Converter::enqueue($relative);
        self::$enqueued++;
    }

    public static function isOwnHost($prefix)
    {
        $prefix = trim((string)$prefix);
        if ($prefix === '' || $prefix === '//') {
            return true;
        }

        $url = $prefix;
        if (strpos($url, '//') === 0) {
            $url = 'http:' . $url;
        }

        $host = strtolower((string)parse_url($url, PHP_URL_HOST));
        if ($host === '') {
            return true;
        }

        $current = strtolower((string)($_SERVER['HTTP_HOST'] ?? ''));
        $current = preg_replace('/:\d+$/', '', $current);
        $host = preg_replace('/^www\./', '', $host);
        $current = preg_replace('/^www\./', '', $current);

        if ($current === '' || $host === $current) {
            return true;
        }

        if (substr($host, -strlen('.' . $current)) === '.' . $current) {
            return true;
        }
        if (substr($current, -strlen('.' . $host)) === '.' . $host) {
            return true;
        }

        $hostParts = explode('.', $host);
        $curParts = explode('.', $current);
        if (count($hostParts) >= 2 && count($curParts) >= 2) {
            $hostBase = implode('.', array_slice($hostParts, -2));
            $curBase = implode('.', array_slice($curParts, -2));
            if ($hostBase === $curBase && $hostBase !== '') {
                return true;
            }
        }

        return false;
    }
}
