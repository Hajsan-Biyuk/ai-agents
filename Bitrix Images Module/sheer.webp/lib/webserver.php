<?php

namespace Sheer\Webp;

class WebServer
{
    public const MARKER_START = '# SHEER_WEBP_START';
    public const MARKER_END = '# SHEER_WEBP_END';

    public static function apacheBlock()
    {
        return implode("\n", [
            self::MARKER_START,
            '<IfModule mod_rewrite.c>',
            'RewriteEngine On',
            'RewriteCond %{HTTP_ACCEPT} image/webp',
            'RewriteCond %{REQUEST_FILENAME}.webp -f',
            'RewriteRule ^(.+)\.(jpe?g|png|bmp)$ $1.$2.webp [T=image/webp,L]',
            '</IfModule>',
            '<IfModule mod_headers.c>',
            'Header append Vary Accept',
            '</IfModule>',
            'AddType image/webp .webp',
            self::MARKER_END,
        ]);
    }

    public static function nginxSnippet()
    {
        // Только блок server { } — подходит для панелей (FASTPANEL, ISPmanager), где
        // нет доступа к http { }. try_files внутри if не работает, поэтому if
        // только переводит запрос в именованный location (безопасное использование if).
        return implode("\n", [
            '# В блок server { } ВЫШЕ общего location для статики (jpg|png|...).',
            '# Если в server есть свои add_header (например HSTS) — повторите их в обоих блоках.',
            'location ~* ^/upload/.+\.(jpe?g|png)$ {',
            '    error_page 418 = @sheer_webp;',
            '    if ($http_accept ~* "image/webp") {',
            '        return 418;',
            '    }',
            '    add_header Vary Accept;',
            '    expires 365d;',
            '    try_files $uri =404;',
            '}',
            '',
            'location @sheer_webp {',
            '    add_header Vary Accept;',
            '    expires 365d;',
            '    try_files $uri.webp $uri =404;',
            '}',
            '',
            '# Если статика у вас уходит на бэкенд через @fallback — замените =404 на @fallback.',
        ]);
    }

    public static function installApacheRules()
    {
        $file = Path::toAbsolute('/upload/.htaccess');
        if ($file === '' || !is_dir(dirname($file))) {
            return false;
        }

        $block = self::apacheBlock();
        $current = is_file($file) ? (string)file_get_contents($file) : '';

        if (strpos($current, self::MARKER_START) !== false) {
            $current = preg_replace(
                '/' . preg_quote(self::MARKER_START, '/') . '.*?' . preg_quote(self::MARKER_END, '/') . '/s',
                $block,
                $current
            );
        } else {
            $current = rtrim($current) . ($current !== '' ? "\n\n" : '') . $block . "\n";
        }

        return file_put_contents($file, $current) !== false;
    }

    public static function removeApacheRules()
    {
        $file = Path::toAbsolute('/upload/.htaccess');
        if ($file === '' || !is_file($file)) {
            return true;
        }

        $current = (string)file_get_contents($file);
        if (strpos($current, self::MARKER_START) === false) {
            return true;
        }

        $current = preg_replace(
            '/\n*' . preg_quote(self::MARKER_START, '/') . '.*?' . preg_quote(self::MARKER_END, '/') . '\n*/s',
            "\n",
            $current
        );

        return file_put_contents($file, trim($current) . (trim($current) !== '' ? "\n" : '')) !== false;
    }
}
