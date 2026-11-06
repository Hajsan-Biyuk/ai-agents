<?php

namespace Sheer\Webp;

class Handler
{
    public static function onAfterFileSave($arFile)
    {
        if (!Config::isEnabled() || !Config::isConvertOnUpload() || !is_array($arFile)) {
            return;
        }

        try {
            $relative = self::relativeFromFileArray($arFile);
            self::convertOrQueue($relative, (int)($arFile['ID'] ?? 0));
        } catch (\Throwable $e) {
        }
    }

    public static function onFileSave(&$arFile, $fileName = '', $savePath = '')
    {
        if (!Config::isEnabled() || !Config::isConvertOnUpload() || !is_array($arFile)) {
            return;
        }

        try {
            $relative = self::destinationRelative($arFile, $fileName, $savePath);
            if ($relative === '' || !Path::isConvertibleExtension($relative)) {
                return;
            }

            $fileId = (int)($arFile['ID'] ?? 0);
            register_shutdown_function(static function () use ($relative, $fileId) {
                try {
                    self::convertOrQueue($relative, $fileId);
                } catch (\Throwable $e) {
                }
            });
        } catch (\Throwable $e) {
        }
    }

    public static function onFileDelete($arFile)
    {
        if (!is_array($arFile)) {
            return;
        }

        try {
            $relative = self::relativeFromFileArray($arFile);
            if ($relative === '') {
                return;
            }
            Converter::deleteWebpFor($relative);
        } catch (\Throwable $e) {
        }
    }

    public static function onAfterResizeImage(
        $sourceFile,
        $arParams,
        &$callbackData,
        &$cacheImageFile,
        &$cacheImageFileTmp,
        &$arImageSize
    ) {
        if (!Config::isEnabled() || !Config::isConvertOnUpload()) {
            return;
        }

        try {
            $finalRelative = Path::fromUnknown((string)$cacheImageFile);
            if ($finalRelative === '' || !Path::isConvertibleExtension($finalRelative)) {
                return;
            }
            if (Path::isTinyResizeParams($arParams, $arImageSize) || Path::isTinyResizeFromPath($finalRelative)) {
                return;
            }

            $finalAbs = Path::toAbsolute($finalRelative);
            $tmp = str_replace('\\', '/', (string)$cacheImageFileTmp);

            if (!Config::isImmediateConvertContext()) {
                if (is_file($finalAbs)) {
                    self::convertOrQueue($finalRelative);
                    return;
                }

                $queue = $finalRelative;
                register_shutdown_function(static function () use ($queue) {
                    try {
                        self::convertOrQueue($queue);
                    } catch (\Throwable $e) {
                    }
                });
                return;
            }

            if ($tmp !== '' && is_file($tmp)) {
                Converter::convertAbsolute($tmp, 0, $finalRelative, Path::webpAbsolute($finalAbs));
                return;
            }

            self::convertOrQueue($finalRelative);
        } catch (\Throwable $e) {
        }
    }

    public static function onEndBufferContent(&$content)
    {
        if (!is_string($content) || $content === '') {
            return;
        }
        if (!Config::isEnabled() || !Config::isRewriteEnabled()) {
            return;
        }
        if (defined('ADMIN_SECTION') && ADMIN_SECTION === true) {
            return;
        }
        if (defined('BX_CRONTAB') && BX_CRONTAB === true) {
            return;
        }

        try {
            $content = Rewriter::rewrite($content);
        } catch (\Throwable $e) {
        }
    }

    private static function convertOrQueue($relative, $fileId = 0)
    {
        $relative = Path::normalizeRelative($relative);
        if ($relative === '' || !Path::shouldConvert($relative)) {
            return;
        }

        if (Config::isImmediateConvertContext()) {
            Converter::convertRelative($relative, (int)$fileId);
            return;
        }

        Converter::enqueue($relative, (int)$fileId);
    }

    private static function destinationRelative(array $arFile, $fileName, $savePath)
    {
        $fromArray = self::relativeFromFileArray($arFile);
        if ($fromArray !== '' && strpos($fromArray, '/upload/') === 0) {
            return $fromArray;
        }

        $name = (string)$fileName;
        if ($name === '') {
            $name = (string)($arFile['name'] ?? $arFile['FILE_NAME'] ?? '');
        }
        $savePath = trim(str_replace('\\', '/', (string)$savePath), '/');
        if ($name === '') {
            return '';
        }

        return Path::normalizeRelative('/upload/' . ($savePath !== '' ? $savePath . '/' : '') . $name);
    }

    private static function relativeFromFileArray(array $arFile)
    {
        if (!empty($arFile['SRC'])) {
            return Path::fromUnknown($arFile['SRC']);
        }

        $subdir = trim(str_replace('\\', '/', (string)($arFile['SUBDIR'] ?? '')), '/');
        $name = (string)($arFile['FILE_NAME'] ?? $arFile['fileName'] ?? '');
        if ($name === '') {
            return '';
        }

        $relative = '/upload/';
        if ($subdir !== '') {
            $relative .= $subdir . '/';
        }
        $relative .= $name;

        return Path::normalizeRelative($relative);
    }
}
