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
        return implode("\n", [
            '# map — в блоке http { }',
            'map $http_accept $sheer_webp_suffix {',
            '    default "";',
            '    "~*image/webp" ".webp";',
            '}',
            '',
            '# location — в блоке server { }',
            'location ~* ^/(upload|local/templates|bitrix/templates)/.*\.(jpe?g|png|bmp)$ {',
            '    add_header Vary Accept;',
            '    try_files $uri$sheer_webp_suffix $uri =404;',
            '}',
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
