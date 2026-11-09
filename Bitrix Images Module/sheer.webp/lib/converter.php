<?php

namespace Sheer\Webp;

class Converter
{
    /** Статусы, при которых рядом лежит (или лежала) копия, созданная модулем. */
    public const OWNED_STATUSES = ['done', 'pending', 'processing'];

    /** Ошибочные файлы с хитов повторяем не чаще раза в сутки. */
    public const ERROR_RETRY_SECONDS = 86400;

    /** Типы, которые реально читаем: проверка по содержимому, не по расширению. */
    private const IMAGE_TYPES = [
        IMAGETYPE_JPEG => 'jpeg',
        IMAGETYPE_PNG => 'png',
        IMAGETYPE_BMP => 'bmp',
    ];

    public static function engine()
    {
        if (self::hasImagick()) {
            return 'imagick';
        }
        if (self::hasGd()) {
            return 'gd';
        }

        return '';
    }

    public static function hasImagick()
    {
        if (!extension_loaded('imagick') || !class_exists('\\Imagick')) {
            return false;
        }

        try {
            $formats = array_map('strtoupper', \Imagick::queryFormats('WEBP') ?: []);
            return in_array('WEBP', $formats, true);
        } catch (\Throwable $e) {
            return false;
        }
    }

    public static function hasGd()
    {
        return function_exists('imagewebp') && function_exists('imagecreatefromstring');
    }

    public static function convertRelative($relative, $fileId = 0)
    {
        $relative = Path::normalizeRelative($relative);
        $absolute = Path::toAbsolute($relative);

        return self::convertAbsolute($absolute, (int)$fileId, $relative);
    }

    public static function convertAbsolute($absolute, $fileId = 0, $relative = '', $webpAbs = '')
    {
        try {
            return self::convertAbsoluteInner($absolute, $fileId, $relative, $webpAbs);
        } catch (\Throwable $e) {
            if ($relative === '') {
                $relative = Path::toRelative($absolute);
            }
            self::store($relative, $fileId, 'error', 0, 0, $e->getMessage());
            return 'error';
        }
    }

    public static function markProcessing($relative, $fileId = 0)
    {
        $relative = Path::normalizeRelative($relative);
        if ($relative === '') {
            return;
        }

        // WEBP_SIZE не трогаем — по нему узнаём свою копию.
        RegistryTable::upsert([
            'FILE_ID' => $fileId > 0 ? $fileId : 0,
            'ORIGINAL_PATH' => $relative,
            'PATH_HASH' => RegistryTable::pathHash($relative),
            'WEBP_PATH' => Path::webpRelative($relative),
            'STATUS' => 'processing',
            'ERROR_TEXT' => '',
        ]);
    }

    public static function deleteWebpFor($relative)
    {
        $relative = Path::normalizeRelative($relative);
        $webpAbs = Path::toAbsolute(Path::webpRelative($relative));
        $row = RegistryTable::getByOriginal($relative);

        if ($webpAbs !== '' && Path::isInsideDocumentRoot($webpAbs) && self::isOwnedFile($row, $webpAbs)) {
            @unlink($webpAbs);
        }
        self::removeTmpFiles($webpAbs);

        if ($row) {
            RegistryTable::delete((int)$row['ID']);
        }
    }

    /**
     * WebP рядом с оригиналом создан этим модулем: строка реестра в статусе
     * с готовой копией и размер файла совпадает с записанным.
     * Чужие *.jpg.webp (другой модуль, скрипт nginx) не трогаем.
     */
    public static function isOwnedFile($row, $webpAbs)
    {
        if (!is_array($row) || $webpAbs === '' || !is_file($webpAbs)) {
            return false;
        }
        if (!in_array((string)($row['STATUS'] ?? ''), self::OWNED_STATUSES, true)) {
            return false;
        }
        $size = (int)($row['WEBP_SIZE'] ?? 0);

        return $size > 0 && (int)filesize($webpAbs) === $size;
    }

    /**
     * Удаляет только временные файлы модуля: старый формат «x.webp.tmp»
     * и новый «x.webp.sheer-<rand>.tmp».
     */
    public static function removeTmpFiles($webpAbs)
    {
        $webpAbs = (string)$webpAbs;
        if ($webpAbs === '') {
            return;
        }
        $legacy = $webpAbs . '.tmp';
        if (Path::isInsideDocumentRoot($legacy) && is_file($legacy)) {
            @unlink($legacy);
        }

        $dir = dirname($webpAbs);
        $prefix = basename($webpAbs) . '.sheer-';
        if (!is_dir($dir) || !Path::isInsideDocumentRoot($dir)) {
            return;
        }
        $entries = @scandir($dir);
        if ($entries === false) {
            return;
        }
        foreach ($entries as $entry) {
            if (strpos($entry, $prefix) === 0 && substr($entry, -4) === '.tmp') {
                @unlink($dir . '/' . $entry);
            }
        }
    }

    /**
     * @param bool $force true — ручное сканирование из админки: повторить и
     *                    error/skipped_*, даже если оригинал не менялся.
     */
    public static function enqueue($relative, $fileId = 0, $force = false)
    {
        $relative = Path::normalizeRelative($relative);
        if ($relative === '') {
            return false;
        }

        $existing = RegistryTable::getByOriginal($relative);
        if ($existing && in_array($existing['STATUS'], ['pending', 'processing'], true)) {
            Agent::wake();
            return true;
        }

        if ($existing && !self::needsRequeue($existing, $relative, (bool)$force)) {
            return true;
        }

        // ORIGINAL_SIZE/WEBP_SIZE не обнуляем: по WEBP_SIZE узнаём «свою» копию.
        RegistryTable::upsert([
            'FILE_ID' => $fileId > 0 ? $fileId : (int)($existing['FILE_ID'] ?? 0),
            'ORIGINAL_PATH' => $relative,
            'PATH_HASH' => RegistryTable::pathHash($relative),
            'WEBP_PATH' => Path::webpRelative($relative),
            'STATUS' => 'pending',
            'ERROR_TEXT' => '',
        ]);

        Agent::wake();

        return true;
    }

    /**
     * Нужно ли снова ставить файл в очередь.
     * Финальные статусы не сбрасываются с каждого хита: иначе PNG, у которых
     * WebP больше оригинала, и файлы с ошибкой перекодируются по кругу.
     */
    private static function needsRequeue(array $row, $relative, $force)
    {
        $status = (string)($row['STATUS'] ?? '');
        $abs = Path::toAbsolute($relative);
        $srcMtime = is_file($abs) ? (int)filemtime($abs) : 0;
        $rowTime = RegistryTable::rowTimestamp($row);
        $sourceChanged = $srcMtime > 0 && $srcMtime > $rowTime;

        if ($status === 'done') {
            $webp = Path::toAbsolute(Path::webpRelative($relative));
            return !(is_file($abs) && is_file($webp) && filemtime($abs) <= filemtime($webp));
        }

        if ($sourceChanged) {
            return true;
        }

        if ($force) {
            return true;
        }

        if ($status === 'error') {
            return $rowTime > 0 && (time() - $rowTime) > self::ERROR_RETRY_SECONDS;
        }

        // skipped_larger, skipped_icon, skipped_type, skipped_foreign
        return false;
    }

    private static function convertAbsoluteInner($absolute, $fileId, $relative, $webpAbs)
    {
        $absolute = str_replace('\\', '/', (string)$absolute);
        if ($relative === '') {
            $relative = Path::toRelative($absolute);
        }

        if ($relative === '') {
            return 'missing';
        }

        if (!is_file($absolute)) {
            self::store($relative, $fileId, 'error', 0, 0, 'Файл не найден');
            return 'missing';
        }

        if (!Path::isConvertibleExtension($relative) || Path::isExcludedPath($relative) || Path::isTinyResizeFromPath($relative)) {
            self::store($relative, $fileId, 'skipped_type', 0, 0, '');
            return 'skipped_type';
        }

        if (Path::isIconFile($absolute)) {
            self::store($relative, $fileId, 'skipped_icon', (int)filesize($absolute), 0, '');
            return 'skipped_icon';
        }

        if ($webpAbs === '') {
            $webpAbs = Path::webpAbsolute(Path::toAbsolute($relative));
            if ($webpAbs === Path::documentRoot() . '.webp' || !Path::isInsideDocumentRoot(Path::toAbsolute($relative))) {
                $webpAbs = Path::webpAbsolute($absolute);
            }
        }
        $webpAbs = str_replace('\\', '/', (string)$webpAbs);

        if (!Path::isInsideDocumentRoot($webpAbs)) {
            return 'skipped_type';
        }

        $originalSize = (int)filesize($absolute);

        if ($originalSize <= 0) {
            self::store($relative, $fileId, 'error', 0, 0, 'Пустой файл');
            return 'error';
        }

        if ($originalSize > Config::getMaxSourceBytes()) {
            self::store($relative, $fileId, 'error', $originalSize, 0, 'Файл больше лимита (память)');
            return 'error';
        }

        // Тип определяем по содержимому. Файл «.jpg» с SVG/PS/PDF внутри
        // не должен попасть в делегаты ImageMagick.
        $info = @getimagesize($absolute);
        $type = is_array($info) ? (int)($info[2] ?? 0) : 0;
        if (!isset(self::IMAGE_TYPES[$type]) || empty($info[0]) || empty($info[1])) {
            self::store($relative, $fileId, 'skipped_type', $originalSize, 0, 'Содержимое не JPEG/PNG/BMP');
            return 'skipped_type';
        }
        $format = self::IMAGE_TYPES[$type];
        $width = (int)$info[0];
        $height = (int)$info[1];

        if ($width * $height > Config::getMaxPixels()) {
            self::store($relative, $fileId, 'error', $originalSize, 0, 'Слишком много пикселей');
            return 'error';
        }

        if (is_file($webpAbs)) {
            $row = RegistryTable::getByOriginal($relative);
            if (!self::isOwnedFile($row, $webpAbs)) {
                // Копию сделал кто-то другой — не перезаписываем и не удаляем.
                self::store($relative, $fileId, 'skipped_foreign', $originalSize, 0, 'Рядом чужой .webp');
                return 'skipped_foreign';
            }
            if (filemtime($webpAbs) >= filemtime($absolute)) {
                $webpSize = (int)filesize($webpAbs);
                if (Config::isSkipIfLarger() && $webpSize >= $originalSize) {
                    self::store($relative, $fileId, 'skipped_larger', $originalSize, $webpSize, '');
                    return 'skipped_larger';
                }
                self::store($relative, $fileId, 'done', $originalSize, $webpSize, '');
                return 'done';
            }
            // Своя устаревшая копия: оригинал поменялся.
            @unlink($webpAbs);
        }

        $engine = self::engine();
        if ($engine === '') {
            self::store($relative, $fileId, 'error', $originalSize, 0, 'Нет GD/Imagick с поддержкой WebP');
            return 'error';
        }

        if ($engine === 'gd' && !self::gdMemoryFits($width, $height, $originalSize, self::needsRotation($absolute, $format))) {
            self::store($relative, $fileId, 'error', $originalSize, 0, 'Не хватит memory_limit для GD');
            return 'error';
        }

        $webpDir = dirname($webpAbs);
        if (!is_dir($webpDir)) {
            @mkdir($webpDir, 0755, true);
        }
        $webpRel = Path::webpRelative($relative);

        // Уникальный tmp: два процесса не пишут в один файл.
        $tmp = $webpAbs . '.sheer-' . bin2hex(random_bytes(6)) . '.tmp';
        $ok = false;
        $error = '';

        try {
            if ($engine === 'imagick') {
                $ok = self::convertWithImagick($absolute, $tmp, $format);
            } else {
                $ok = self::convertWithGd($absolute, $tmp);
            }
        } catch (\Throwable $e) {
            $ok = false;
            $error = $e->getMessage();
        }

        if (!$ok || !is_file($tmp)) {
            if (is_file($tmp)) {
                @unlink($tmp);
            }
            self::store($relative, $fileId, 'error', $originalSize, 0, $error !== '' ? $error : 'Не удалось создать WebP');
            return 'error';
        }

        $webpSize = (int)filesize($tmp);
        if ($webpSize <= 0) {
            @unlink($tmp);
            self::store($relative, $fileId, 'error', $originalSize, 0, 'Пустой WebP');
            return 'error';
        }

        if (Config::isSkipIfLarger() && $webpSize >= $originalSize) {
            @unlink($tmp);
            self::store($relative, $fileId, 'skipped_larger', $originalSize, $webpSize, '');
            return 'skipped_larger';
        }

        if (is_file($webpAbs) && !self::isOwnedFile(RegistryTable::getByOriginal($relative), $webpAbs)) {
            // Пока кодировали, рядом появился чужой файл.
            @unlink($tmp);
            self::store($relative, $fileId, 'skipped_foreign', $originalSize, 0, 'Рядом чужой .webp');
            return 'skipped_foreign';
        }

        // rename атомарно заменяет файл на POSIX; на Windows — через unlink.
        if (!@rename($tmp, $webpAbs)) {
            if (is_file($webpAbs)) {
                @unlink($webpAbs);
            }
            if (!@rename($tmp, $webpAbs)) {
                @unlink($tmp);
                self::store($relative, $fileId, 'error', $originalSize, 0, 'Не удалось сохранить WebP');
                return 'error';
            }
        }

        @chmod($webpAbs, 0644);
        // Копия не может быть «старее» оригинала, даже если у него дата в
        // будущем (сбой часов, rsync) — иначе бесконечная переконвертация.
        $srcMtime = (int)@filemtime($absolute);
        if ($srcMtime > time()) {
            @touch($webpAbs, $srcMtime);
        }
        clearstatcache(true, $webpAbs);
        self::store($relative, $fileId, 'done', $originalSize, $webpSize, '', $webpRel);

        return 'done';
    }

    /**
     * Оценка памяти под GD: truecolor ≈ 4 байта на пиксель плюс служебное,
     * при повороте по EXIF — вторая копия. Fatal по memory_limit не ловится
     * try/catch и роняет весь cron-запуск, поэтому проверяем заранее.
     */
    public static function gdMemoryFits($width, $height, $fileSize = 0, $rotate = false, $limit = null, $used = null)
    {
        $limit = $limit === null ? self::memoryLimitBytes() : (int)$limit;
        if ($limit <= 0) {
            return true;
        }
        $used = $used === null ? memory_get_usage(true) : (int)$used;

        $bitmap = (int)$width * (int)$height * 5;
        $need = $bitmap * ($rotate ? 2 : 1) + (int)$fileSize * 2 + 16 * 1024 * 1024;

        return $used + $need < $limit;
    }

    public static function memoryLimitBytes()
    {
        $raw = trim((string)ini_get('memory_limit'));
        if ($raw === '' || $raw === '-1') {
            return -1;
        }
        $num = (int)$raw;
        $unit = strtolower(substr($raw, -1));
        if ($unit === 'g') {
            $num *= 1024 * 1024 * 1024;
        } elseif ($unit === 'm') {
            $num *= 1024 * 1024;
        } elseif ($unit === 'k') {
            $num *= 1024;
        }

        return $num;
    }

    private static function needsRotation($source, $format)
    {
        if ($format !== 'jpeg' || !function_exists('exif_read_data') || !function_exists('imagerotate')) {
            return false;
        }
        $exif = @exif_read_data($source);

        return in_array((int)($exif['Orientation'] ?? 1), [3, 6, 8], true);
    }

    private static function convertWithImagick($source, $destination, $format)
    {
        $binary = @file_get_contents($source);
        if ($binary === false || $binary === '') {
            return false;
        }

        $image = new \Imagick();
        // Префикс формата жёстко задаёт декодер (без автоопределения по
        // содержимому), а blob убирает разбор имени файла вида «a[1].jpg».
        $image->readImageBlob($binary, $format . ':source');
        unset($binary);

        if ($image->getNumberImages() > 1) {
            $image->clear();
            $image->destroy();
            return false;
        }

        if (method_exists($image, 'autoOrient')) {
            $image->autoOrient();
        }

        $image->setImageFormat('webp');
        $image->setImageCompressionQuality(Config::getQuality());
        $image->setOption('webp:method', '6');
        $image->setOption('webp:alpha-quality', '100');
        $image->setOption('webp:use-sharp-yuv', 'true');
        $image->setOption('webp:lossless', 'false');

        $ok = (bool)file_put_contents($destination, $image->getImagesBlob());
        $image->clear();
        $image->destroy();

        return $ok;
    }

    private static function convertWithGd($source, $destination)
    {
        $binary = @file_get_contents($source);
        if ($binary === false || $binary === '') {
            return false;
        }

        $image = @imagecreatefromstring($binary);
        unset($binary);
        if (!$image) {
            return false;
        }

        $image = self::applyExifOrientation($image, $source);

        if (function_exists('imagepalettetotruecolor') && function_exists('imageistruecolor') && !imageistruecolor($image)) {
            imagepalettetotruecolor($image);
        }

        imagealphablending($image, false);
        imagesavealpha($image, true);

        $ok = imagewebp($image, $destination, Config::getQuality());
        if (PHP_VERSION_ID < 80500) {
            imagedestroy($image);
        }

        return $ok;
    }

    private static function applyExifOrientation($image, $source)
    {
        if (!function_exists('exif_read_data') || !function_exists('imagerotate')) {
            return $image;
        }

        $ext = strtolower((string)pathinfo($source, PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg'], true)) {
            return $image;
        }

        $exif = @exif_read_data($source);
        $orientation = (int)($exif['Orientation'] ?? 1);
        if ($orientation <= 1) {
            return $image;
        }

        $rotated = $image;
        if ($orientation === 3) {
            $rotated = imagerotate($image, 180, 0);
        } elseif ($orientation === 6) {
            $rotated = imagerotate($image, -90, 0);
        } elseif ($orientation === 8) {
            $rotated = imagerotate($image, 90, 0);
        }

        if ($rotated && $rotated !== $image && PHP_VERSION_ID < 80500) {
            imagedestroy($image);
        }

        return $rotated ?: $image;
    }

    private static function store($relative, $fileId, $status, $originalSize, $webpSize, $error, $webpRel = '')
    {
        if ($webpRel === '') {
            $webpRel = Path::webpRelative($relative);
        }

        if (!in_array($status, self::OWNED_STATUSES, true)) {
            // Инвариант: копия модуля лежит рядом только при статусе done.
            // Своя старая копия удаляется, чужая остаётся (её размер не наш).
            $webpAbs = Path::toAbsolute($webpRel);
            if ($webpAbs !== '' && Path::isInsideDocumentRoot($webpAbs)
                && self::isOwnedFile(RegistryTable::getByOriginal($relative), $webpAbs)) {
                @unlink($webpAbs);
            }
        }

        RegistryTable::upsert([
            'FILE_ID' => $fileId > 0 ? $fileId : 0,
            'ORIGINAL_PATH' => Path::normalizeRelative($relative),
            'PATH_HASH' => RegistryTable::pathHash($relative),
            'WEBP_PATH' => $webpRel,
            'ORIGINAL_SIZE' => (int)$originalSize,
            'WEBP_SIZE' => (int)$webpSize,
            'STATUS' => $status,
            'ERROR_TEXT' => $error !== '' ? self::clipError($error) : '',
        ]);
    }

    private static function clipError($error)
    {
        $error = (string)$error;
        if (function_exists('mb_substr')) {
            return mb_substr($error, 0, 250);
        }

        return substr($error, 0, 250);
    }
}
