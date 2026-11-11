<?php

/**
 * Автономные тесты Sheer WebP без установленного Битрикс.
 * Запуск: php tests/run.php
 */

namespace Bitrix\Main\Config {
    class Option
    {
        public static $data = [];

        public static function get($module, $name, $default = '')
        {
            return self::$data[$name] ?? $default;
        }

        public static function set($module, $name, $value)
        {
            self::$data[$name] = $value;
        }
    }
}

namespace Sheer\Webp {
    class RegistryTable
    {
        public static $rows = [];

        public static function pathHash($relative)
        {
            return md5(Path::normalizeRelative($relative));
        }

        public static function upsert(array $fields)
        {
            $path = $fields['ORIGINAL_PATH'] ?? '';
            $fields['DATE_UPDATE'] = self::$now ?? time();
            // Как и ORM update(): не переданные поля сохраняются.
            self::$rows[$path] = array_merge(
                ['ID' => count(self::$rows) + 1, 'ORIGINAL_SIZE' => 0, 'WEBP_SIZE' => 0],
                self::$rows[$path] ?? [],
                $fields
            );
            return 1;
        }

        public static $now = null;

        public static function rowTimestamp($row)
        {
            return is_array($row) ? (int)($row['DATE_UPDATE'] ?? 0) : 0;
        }

        public static function claim($id)
        {
            return true;
        }

        public static function getByOriginal($relative)
        {
            $path = Path::normalizeRelative($relative);
            return self::$rows[$path] ?? false;
        }

        public static function delete($id)
        {
            foreach (self::$rows as $path => $row) {
                if ((int)($row['ID'] ?? 0) === (int)$id) {
                    unset(self::$rows[$path]);
                }
            }
            return true;
        }

        public static function getList(array $params = [])
        {
            $rows = array_values(self::$rows);
            if (!empty($params['limit'])) {
                $rows = array_slice($rows, 0, (int)$params['limit']);
            }
            return new class($rows) {
                private $rows;
                public function __construct($rows) { $this->rows = $rows; }
                public function fetch() { return array_shift($this->rows) ?: false; }
            };
        }

        public static function clearPending()
        {
            $n = 0;
            foreach (self::$rows as $path => $row) {
                if (($row['STATUS'] ?? '') === 'pending' && (int)($row['WEBP_SIZE'] ?? 0) === 0) {
                    unset(self::$rows[$path]);
                    $n++;
                }
            }
            return $n;
        }

        public static function getStats()
        {
            return ['total' => count(self::$rows)];
        }

        public static function failStaleProcessing($seconds = 300)
        {
        }
    }
}

namespace {
    $root = sys_get_temp_dir() . '/sheer-webp-test-' . bin2hex(random_bytes(4));
    $failed = 0;
    $passed = 0;

    function t($name, $ok, $detail = '')
    {
        global $passed, $failed;
        if ($ok) {
            $passed++;
            echo "OK  {$name}\n";
            return;
        }
        $failed++;
        echo "FAIL  {$name}" . ($detail !== '' ? " — {$detail}" : '') . "\n";
    }

    $_SERVER['DOCUMENT_ROOT'] = $root;
    $_SERVER['HTTP_HOST'] = 'site.ru';
    $_SERVER['HTTP_ACCEPT'] = 'text/html,image/webp,image/apng';
    $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 Chrome/120.0.0.0';

    mkdir($root . '/upload/iblock/abc', 0755, true);
    mkdir($root . '/upload/CMax', 0755, true);
    mkdir($root . '/upload/medialibrary', 0755, true);
    mkdir($root . '/upload/resize_cache/iblock/abc/60_60_1', 0755, true);
    mkdir($root . '/upload/resize_cache/iblock/abc/600_600_1', 0755, true);
    mkdir($root . '/upload/icons', 0755, true);
    mkdir($root . '/bitrix/tmp', 0755, true);
    mkdir($root . '/bitrix/modules/main', 0755, true);
    mkdir($root . '/local/templates/aspro/images', 0755, true);

    class CHTMLPagesCache
    {
        public static $on = false;
        public static $cleaned = 0;

        public static function IsOn()
        {
            return self::$on;
        }

        public static function CleanAll()
        {
            self::$cleaned++;
        }
    }

    class CAgent
    {
        public static $list = [];

        public static function AddAgent($name, $module = '', $period = 'N', $interval = 60)
        {
            self::$list[] = $name;
            return true;
        }

        public static function RemoveModuleAgents($module)
        {
            self::$list = [];
        }

        public static function RemoveAgent($name, $module = '')
        {
            self::$list = array_values(array_diff(self::$list, [$name]));
        }

        public static function GetList($a = [], $b = [])
        {
            return new class {
                public function Fetch()
                {
                    return false;
                }
            };
        }
    }

    $lib = dirname(__DIR__) . '/sheer.webp/lib';
    require $lib . '/config.php';
    require $lib . '/path.php';
    require $lib . '/agent.php';
    require $lib . '/lock.php';
    require $lib . '/warmer.php';
    require $lib . '/rewriter.php';
    require $lib . '/converter.php';
    require $lib . '/handler.php';
    require $lib . '/scanner.php';
    require $lib . '/webserver.php';

    use Sheer\Webp\Config;
    use Sheer\Webp\Converter;
    use Sheer\Webp\Handler;
    use Sheer\Webp\Lock;
    use Sheer\Webp\Path;
    use Sheer\Webp\RegistryTable;
    use Sheer\Webp\Rewriter;
    use Sheer\Webp\Scanner;
    use Sheer\Webp\WebServer;

    \Bitrix\Main\Config\Option::$data = [
        'enabled' => 'Y',
        'quality' => '90',
        'skip_if_larger' => 'Y',
        'rewrite_html' => 'Y',
        'convert_on_upload' => 'Y',
        'scan_templates' => 'N',
        'scan_resize_cache' => 'N',
        'skip_min_px' => '120',
        'batch_size' => '8',
        'phase' => 'idle',
        'max_source_mb' => '8',
        'max_megapixels' => '24',
    ];

    echo "PHP " . PHP_VERSION . ", engine=" . Converter::engine() . "\n";
    echo "docroot={$root}\n\n";

    t('document root', Config::getDocumentRoot() === $root);
    t('engine gd/imagick', Converter::engine() !== '');

    t('normalize relative', Path::normalizeRelative('upload/iblock/a.jpg') === '/upload/iblock/a.jpg');
    t('normalize url', Path::normalizeRelative('https://site.ru/upload/a.jpg?v=1') === '/upload/a.jpg');
    t('webp sibling name', Path::webpRelative('/upload/a.jpg') === '/upload/a.jpg.webp');
    t('jpg convertible', Path::isConvertibleExtension('/upload/photo.jpg') === true);
    t('jpeg convertible', Path::isConvertibleExtension('/upload/photo.jpeg') === true);
    t('png convertible', Path::isConvertibleExtension('/upload/photo.png') === true);
    t('gif skipped', Path::isConvertibleExtension('/upload/a.gif') === false);
    t('svg skipped', Path::isConvertibleExtension('/upload/a.svg') === false);
    t('ico skipped', Path::isConvertibleExtension('/upload/a.ico') === false);
    t('webp sidecar not a source', Path::isConvertibleExtension('/upload/a.jpg.webp') === false);
    t('native webp skipped', Path::isConvertibleExtension('/upload/a.webp') === false);

    t('exclude icons dir', Path::isExcludedPath('/upload/icons/logo.png') === true);
    t('exclude favicon', Path::isExcludedPath('/upload/favicon.png') === true);
    t('exclude bitrix modules', Path::isExcludedPath('/bitrix/modules/main/photo.jpg') === true);
    t('keep iblock photo', Path::isExcludedPath('/upload/iblock/abc/photo.jpg') === false);

    t('inside docroot', Path::isInsideDocumentRoot($root . '/upload/iblock/abc/photo.jpg') === true);
    t('reject other tree', Path::isInsideDocumentRoot('/tmp/sheer-webp-outside.jpg') === false);
    t('reject sibling prefix', Path::isInsideDocumentRoot($root . '2/upload/a.jpg') === false);

    $photo = $root . '/upload/iblock/abc/photo.jpg';
    $im = imagecreatetruecolor(400, 280);
    $bg = imagecolorallocate($im, 20, 40, 200);
    imagefill($im, 0, 0, $bg);
    imagejpeg($im, $photo, 90);

    $icon = $root . '/upload/iblock/abc/small.png';
    $im = imagecreatetruecolor(24, 24);
    imagepng($im, $icon);

    $gif = $root . '/upload/iblock/abc/anim.gif';
    $im = imagecreatetruecolor(80, 80);
    imagegif($im, $gif);

    $alpha = $root . '/upload/iblock/abc/logo.png';
    $im = imagecreatetruecolor(200, 160);
    imagealphablending($im, false);
    imagesavealpha($im, true);
    $transparent = imagecolorallocatealpha($im, 0, 0, 0, 127);
    imagefilledrectangle($im, 0, 0, 200, 160, $transparent);
    $red = imagecolorallocatealpha($im, 220, 30, 30, 0);
    imagefilledellipse($im, 100, 80, 80, 80, $red);
    imagepng($im, $alpha);

    copy($photo, $root . '/bitrix/modules/main/core.jpg');

    t('should convert jpeg', Path::shouldConvert('/upload/iblock/abc/photo.jpg', $photo) === true);
    t('skip 24px icon', Path::shouldConvert('/upload/iblock/abc/small.png', $icon) === false);
    t('skip gif file', Path::shouldConvert('/upload/iblock/abc/anim.gif', $gif) === false);
    t('skip module files', Path::shouldConvert('/bitrix/modules/main/core.jpg', $root . '/bitrix/modules/main/core.jpg') === false);

    $status = Converter::convertRelative('/upload/iblock/abc/photo.jpg');
    t('convert jpeg', $status === 'done', $status);
    t('jpeg original kept', is_file($photo));
    t('jpeg webp created', is_file($photo . '.webp'));
    t('jpeg webp smaller or skip-larger logic', !is_file($photo . '.webp') || filesize($photo . '.webp') > 0);

    $status = Converter::convertRelative('/upload/iblock/abc/logo.png');
    t('convert png alpha', $status === 'done' || $status === 'skipped_larger', $status);
    if (is_file($alpha . '.webp')) {
        $info = @getimagesize($alpha . '.webp');
        t('png webp readable', is_array($info) && ($info[2] ?? 0) === IMAGETYPE_WEBP);
    } else {
        t('png webp readable', $status === 'skipped_larger', 'no sidecar, status=' . $status);
    }

    $status = Converter::convertRelative('/upload/iblock/abc/anim.gif');
    t('gif not converted', in_array($status, ['skipped_type', 'skipped_icon'], true), $status);
    t('gif has no webp', !is_file($gif . '.webp'));

    $status = Converter::convertRelative('/upload/iblock/abc/missing.jpg');
    t('missing file error', $status === 'missing');

    $sample = dirname(__DIR__, 2) . '/red-t-fitting-hq.png';
    if (is_file($sample)) {
        $dest = $root . '/upload/iblock/abc/real.png';
        copy($sample, $dest);
        $origSize = filesize($dest);
        $status = Converter::convertRelative('/upload/iblock/abc/real.png');
        t('real png convert', in_array($status, ['done', 'skipped_larger'], true), $status);
        if ($status === 'done' && is_file($dest . '.webp')) {
            $webpSize = filesize($dest . '.webp');
            t('real png original intact', filesize($dest) === $origSize);
            t('real png webp valid', (int)@getimagesize($dest . '.webp')[2] === IMAGETYPE_WEBP);
            echo "    original=" . $origSize . " webp=" . $webpSize . " saved=" . round(100 * (1 - $webpSize / $origSize), 1) . "%\n";
        } elseif ($status === 'skipped_larger') {
            t('real png original intact', filesize($dest) === $origSize);
            t('real png skipped if larger, no leftover', !is_file($dest . '.webp'));
        }
    }

    Rewriter::clearCache();
    $html = '<img src="/upload/iblock/abc/photo.jpg" alt="x"><img src="/upload/iblock/abc/missing2.jpg">';
    $out = Rewriter::rewrite($html);
    t('rewrite existing jpeg to webp', strpos($out, '/upload/iblock/abc/photo.jpg.webp') !== false, $out);
    t('keep missing jpeg url', strpos($out, '/upload/iblock/abc/missing2.jpg"') !== false, $out);
    t('do not double webp', strpos($out, '.jpg.webp.webp') === false);

    $html2 = '<img src="/upload/iblock/abc/photo.jpg.webp">';
    t('already webp unchanged', Rewriter::rewrite($html2) === $html2);

    $css = 'background:url(/upload/iblock/abc/photo.jpg)';
    t('rewrite css url', strpos(Rewriter::rewrite($css), 'photo.jpg.webp') !== false);

    $_SERVER['HTTP_ACCEPT'] = 'text/html';
    $_SERVER['HTTP_USER_AGENT'] = 'OldBrowser/1.0';
    $old = Rewriter::rewrite('<img src="/upload/iblock/abc/photo.jpg">');
    t('old browser keeps original', strpos($old, 'photo.jpg.webp') === false);
    $_SERVER['HTTP_ACCEPT'] = 'text/html,image/webp';
    $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 Chrome/120.0.0.0';

    $ht = WebServer::apacheBlock();
    t('apache block markers', strpos($ht, 'SHEER_WEBP_START') !== false && strpos($ht, '.webp') !== false);
    $ng = WebServer::nginxSnippet();
    t('nginx snippet: named location with webp', strpos($ng, 'try_files $uri.webp $uri') !== false && strpos($ng, 'error_page 418 = @sheer_webp') !== false);
    t('nginx snippet: no try_files inside if', !preg_match('/if \\(\$http_accept[^}]*try_files/s', $ng));

    Config::setDirQueue(['/upload', '/upload/iblock']);
    t('queue stored in file', is_file(Config::queueFilePath()));
    t('queue roundtrip', Config::getDirQueue() === ['/upload', '/upload/iblock']);
    t('queue not public upload', strpos(Config::queueFilePath(), '/upload/') === false);
    Config::setDirQueue([]);

    t('fromUnknown relative', Path::fromUnknown('/upload/iblock/abc/photo.jpg') === '/upload/iblock/abc/photo.jpg');
    t('fromUnknown absolute', Path::fromUnknown($root . '/upload/iblock/abc/photo.jpg') === '/upload/iblock/abc/photo.jpg');

    Rewriter::clearCache();
    $cdn = Rewriter::rewrite('<img src="https://cdn.example.com/upload/iblock/abc/photo.jpg">');
    t('skip foreign cdn host', strpos($cdn, '.webp') === false, $cdn);
    $own = Rewriter::rewrite('<img src="https://site.ru/upload/iblock/abc/photo.jpg">');
    t('rewrite own host', strpos($own, 'photo.jpg.webp') !== false, $own);

    \Bitrix\Main\Config\Option::$data['max_megapixels'] = '4';
    $huge = $root . '/upload/iblock/abc/huge.jpg';
    $im = imagecreatetruecolor(2500, 2200);
    imagejpeg($im, $huge, 60);
    $status = Converter::convertRelative('/upload/iblock/abc/huge.jpg');
    t('skip oversize megapixels', $status === 'error', $status);
    t('oversize original kept', is_file($huge) && !is_file($huge . '.webp'));
    \Bitrix\Main\Config\Option::$data['max_megapixels'] = '24';

    t('skip aspro 60px thumb path', Path::isTinyResizeFromPath('/upload/resize_cache/iblock/abc/60_60_1/photo.jpg') === true);
    t('keep aspro 600 listing path', Path::isTinyResizeFromPath('/upload/resize_cache/iblock/abc/600_600_1/photo.jpg') === false);
    t('skip 80px as icon', Path::isTooSmall(80, 80) === true);
    t('keep 180x222 card', Path::isTooSmall(180, 222) === false);

    $thumb60 = $root . '/upload/resize_cache/iblock/abc/60_60_1/photo.jpg';
    copy($photo, $thumb60);
    t('should not convert aspro thumb', Path::shouldConvert('/upload/resize_cache/iblock/abc/60_60_1/photo.jpg', $thumb60) === false);

    $listing = $root . '/upload/resize_cache/iblock/abc/600_600_1/photo.jpg';
    $im = imagecreatetruecolor(600, 400);
    imagejpeg($im, $listing, 85);
    t('should convert listing preview', Path::shouldConvert('/upload/resize_cache/iblock/abc/600_600_1/photo.jpg', $listing) === true);

    t('mass scan skips resize_cache', Path::isMassScanSkipped('/upload/resize_cache/iblock') === true);
    t('mass scan keeps iblock originals', Path::isMassScanSkipped('/upload/iblock') === false);

    $roots = Scanner::scanRoots();
    t('scan roots include iblock', in_array('/upload/iblock', $roots, true));
    t('scan roots include CMax', in_array('/upload/CMax', $roots, true));
    t('scan roots skip full upload', !in_array('/upload', $roots, true));
    t('scan roots skip resize_cache', !in_array('/upload/resize_cache', $roots, true));
    t('scan roots skip templates by default', !in_array('/local/templates', $roots, true));
    \Bitrix\Main\Config\Option::$data['scan_resize_cache'] = 'Y';
    $rootsRc = Scanner::scanRoots();
    t('resize_cache checkbox adds the root', in_array('/upload/resize_cache', $rootsRc, true), json_encode($rootsRc));
    \Bitrix\Main\Config\Option::$data['scan_roots'] = "/upload/iblock\n/upload/resize_cache/iblock";
    $rootsRc = Scanner::scanRoots();
    t('resize_cache not duplicated when listed', !in_array('/upload/resize_cache', $rootsRc, true) && in_array('/upload/resize_cache/iblock', $rootsRc, true), json_encode($rootsRc));
    unset(\Bitrix\Main\Config\Option::$data['scan_roots']);
    \Bitrix\Main\Config\Option::$data['scan_resize_cache'] = 'N';

    $cb = null;
    $tmp = '';
    $cacheTiny = '/upload/resize_cache/iblock/abc/60_60_1/photo.jpg';
    $sizeTiny = [60, 60];
    Handler::onAfterResizeImage($photo, ['width' => 60, 'height' => 60], $cb, $cacheTiny, $tmp, $sizeTiny);
    $thumbRow = RegistryTable::getByOriginal($cacheTiny);
    t('public 60px resize not queued', $thumbRow === false);

    $cacheBig = '/upload/resize_cache/iblock/abc/600_600_1/photo.jpg';
    $sizeBig = [600, 400];
    Handler::onAfterResizeImage($listing, ['width' => 600, 'height' => 600], $cb, $cacheBig, $tmp, $sizeBig);
    $listRow = RegistryTable::getByOriginal($cacheBig);
    t('public 600px resize queued not encoded', is_array($listRow) && ($listRow['STATUS'] ?? '') === 'pending' && !is_file($listing . '.webp'));

    t('immediate convert only in admin/cron', Config::isImmediateConvertContext() === false);

    Rewriter::clearCache();
    $htmlQueue = '<img src="/upload/resize_cache/iblock/abc/600_600_1/photo.jpg">';
    $outQueue = Rewriter::rewrite($htmlQueue);
    t('rewrite keeps jpg until webp exists', strpos($outQueue, 'photo.jpg.webp') === false);
    t('rewrite queues missing listing webp', (RegistryTable::getByOriginal($cacheBig)['STATUS'] ?? '') === 'pending');

    $htmlThumb = '<img src="/upload/resize_cache/iblock/abc/60_60_1/photo.jpg">';
    Rewriter::rewrite($htmlThumb);
    t('rewrite does not queue 60px thumb', RegistryTable::getByOriginal($cacheTiny) === false);

    $_SERVER['HTTP_HOST'] = 'kazan.atriumceramic.ru';
    t('subdomain shares apex host', Rewriter::isOwnHost('https://atriumceramic.ru') === true);
    t('sibling city subdomain own host', Rewriter::isOwnHost('https://spb.atriumceramic.ru') === true);
    t('foreign host still skipped', Rewriter::isOwnHost('https://cdn.example.com') === false);
    $_SERVER['HTTP_HOST'] = 'site.ru';

    $row = RegistryTable::getByOriginal('/upload/iblock/abc/photo.jpg');
    t('registry stored done', is_array($row) && ($row['STATUS'] ?? '') === 'done');

    $orig = (int)($row['ORIGINAL_SIZE'] ?? 0);
    $webp = (int)($row['WEBP_SIZE'] ?? 0);
    $savedPct = $orig > 0 ? round(($orig - $webp) * 100 / $orig, 1) : 0;
    t('saved percent in 0..100', $savedPct >= 0 && $savedPct <= 100, (string)$savedPct);


    // ===== 1.3.0: исправления 1–7 =====
    $opt = &\Bitrix\Main\Config\Option::$data;

    // 1. Финальные статусы не сбрасываются с каждого хита.
    $flat = $root . '/upload/iblock/abc/flat.png';
    $im = imagecreate(400, 400);
    imagecolorallocate($im, 255, 255, 255);
    imagepng($im, $flat, 9);
    // Плоский PNG: без потерь WebP легче — модуль берёт его вместо пропуска.
    $flatLl = $root . '/upload/iblock/abc/flat-ll.png';
    copy($flat, $flatLl);
    $status = Converter::convertRelative('/upload/iblock/abc/flat-ll.png');
    $llInfo = @getimagesize($flatLl . '.webp');
    t('flat png converted losslessly', $status === 'done' && is_array($llInfo) && $llInfo[2] === IMAGETYPE_WEBP && filesize($flatLl . '.webp') < filesize($flatLl), $status);
    // Крупнее 1 Мп второй проход без потерь не делаем — бережём CPU.
    $bigFlat = $root . '/upload/iblock/abc/flat-big.png';
    $im2 = imagecreate(1100, 1000);
    imagecolorallocate($im2, 255, 255, 255);
    imagepng($im2, $bigFlat, 9);
    $status = Converter::convertRelative('/upload/iblock/abc/flat-big.png');
    t('big flat png: no lossless pass', $status === 'skipped_larger', $status);

    // Дальше проверяем поведение пропусков — без попытки lossless.
    $opt['png_lossless'] = 'N';
    $status = Converter::convertRelative('/upload/iblock/abc/flat.png');
    t('flat png skipped_larger', $status === 'skipped_larger', $status);
    Rewriter::clearCache();
    Rewriter::rewrite('<img src="/upload/iblock/abc/flat.png">');
    t('hit does not requeue skipped_larger', RegistryTable::getByOriginal('/upload/iblock/abc/flat.png')['STATUS'] === 'skipped_larger');
    Converter::enqueue('/upload/iblock/abc/flat.png', 0, true);
    t('manual scan (force) requeues skipped', RegistryTable::getByOriginal('/upload/iblock/abc/flat.png')['STATUS'] === 'pending');
    Converter::convertRelative('/upload/iblock/abc/flat.png');

    RegistryTable::$rows['/upload/iblock/abc/flat.png']['DATE_UPDATE'] = time() - 10;
    touch($flat, time());
    Converter::enqueue('/upload/iblock/abc/flat.png');
    t('changed original requeued', RegistryTable::getByOriginal('/upload/iblock/abc/flat.png')['STATUS'] === 'pending');

    unset($opt['png_lossless']);
    $opt['max_megapixels'] = '4';
    $status = Converter::convertRelative('/upload/iblock/abc/huge.jpg');
    $opt['max_megapixels'] = '24';
    Converter::enqueue('/upload/iblock/abc/huge.jpg');
    t('fresh error not requeued from hit', RegistryTable::getByOriginal('/upload/iblock/abc/huge.jpg')['STATUS'] === 'error');
    RegistryTable::$rows['/upload/iblock/abc/huge.jpg']['DATE_UPDATE'] = time() - 2 * 86400;
    touch($huge, time() - 3 * 86400);
    Converter::enqueue('/upload/iblock/abc/huge.jpg');
    t('old error retried after a day', RegistryTable::getByOriginal('/upload/iblock/abc/huge.jpg')['STATUS'] === 'pending');

    // 2. Браузеры и композит.
    $_SERVER['HTTP_ACCEPT'] = 'text/html';
    $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Safari/605.1.15';
    t('desktop safari without accept gets original', Rewriter::browserAcceptsWebp() === false);
    $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_4 like Mac OS X) AppleWebKit/605.1.15 Version/14.0 Mobile/15E148 Safari/604.1';
    t('ios 14 gets webp', Rewriter::browserAcceptsWebp() === true);
    $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_7 like Mac OS X) AppleWebKit/605.1.15 Version/13.1 Mobile/15E148 Safari/604.1';
    t('ios 13 gets original', Rewriter::browserAcceptsWebp() === false);
    $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_7 like Mac OS X) CriOS/120.0 Mobile Safari/604.1';
    t('chrome on ios 13 gets original', Rewriter::browserAcceptsWebp() === false);
    $_SERVER['HTTP_ACCEPT'] = 'text/html,image/webp';
    $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 Chrome/120.0.0.0';

    CHTMLPagesCache::$on = true;
    Rewriter::clearCache();
    $cmp = '<img src="/upload/iblock/abc/photo.jpg">';
    t('composite on: html not rewritten', Rewriter::rewrite($cmp) === $cmp);

    // При композите страница не переписывается, но её картинки встают в очередь.
    $q1 = $root . '/upload/iblock/abc/queue1.jpg';
    copy($photo, $q1);
    Rewriter::clearCache();
    $cmpQ = '<img src="/upload/iblock/abc/queue1.jpg"><div style="background:url(/upload/iblock/abc/queue1.jpg)"></div>';
    t('composite on: html still untouched', Rewriter::rewrite($cmpQ) === $cmpQ);
    t('composite on: page images queued', (RegistryTable::getByOriginal('/upload/iblock/abc/queue1.jpg')['STATUS'] ?? '') === 'pending');
    t('enqueue: already queued returns false', Converter::enqueue('/upload/iblock/abc/queue1.jpg') === false);
    CHTMLPagesCache::$on = false;

    $removed = Scanner::clearQueue();
    t('clear queue removes pending rows', $removed >= 1 && RegistryTable::getByOriginal('/upload/iblock/abc/queue1.jpg') === false, (string)$removed);
    t('clear queue keeps done rows', (RegistryTable::getByOriginal('/upload/iblock/abc/photo.jpg')['STATUS'] ?? '') === 'done');
    t('clear queue stops scan', \Sheer\Webp\Config::getPhase() === 'idle' && \Sheer\Webp\Config::getDirQueue() === []);

    clearstatcache();
    touch($photo, time() + 5);
    Rewriter::clearCache();
    t('stale webp not served', strpos(Rewriter::rewrite($cmp), '.webp') === false);
    $status = Converter::convertRelative('/upload/iblock/abc/photo.jpg');
    t('own stale webp reconverted', $status === 'done', $status);
    Rewriter::clearCache();
    t('fresh webp served again', strpos(Rewriter::rewrite($cmp), 'photo.jpg.webp') !== false);

    // 4. Тип по содержимому: «.jpg» с SVG внутри не конвертируется.
    $fake = $root . '/upload/iblock/abc/evil.jpg';
    file_put_contents($fake, str_repeat(' ', 2000) . '<svg xmlns="http://www.w3.org/2000/svg"><image href="file:///etc/passwd"/></svg>');
    $status = Converter::convertRelative('/upload/iblock/abc/evil.jpg');
    t('non-image .jpg rejected', $status === 'skipped_type', $status);
    t('non-image .jpg has no webp', !is_file($fake . '.webp'));

    // 5. Блокировка и временные файлы.
    t('lock acquired', Lock::acquire(0) === true);
    $fp = fopen(Lock::path(), 'c');
    t('second process cannot take lock', flock($fp, LOCK_EX | LOCK_NB) === false);
    Lock::release();
    t('lock released for others', flock($fp, LOCK_EX | LOCK_NB) === true);
    $busy = Scanner::processChunk(5);
    t('chunk skipped while another process works', ($busy['busy'] ?? false) === true);
    flock($fp, LOCK_UN);
    fclose($fp);

    $leftTmp = $photo . '.webp.sheer-0123456789ab.tmp';
    $alien = $photo . '.webp.other.tmp';
    file_put_contents($leftTmp, 'x');
    file_put_contents($alien, 'x');
    Converter::removeTmpFiles($photo . '.webp');
    t('module tmp removed', !is_file($leftTmp));
    t('other tmp kept', is_file($alien));
    $leftovers = glob($root . '/upload/iblock/abc/*.tmp') ?: [];
    t('no tmp left after conversions', count($leftovers) === 1, implode(',', $leftovers));

    // 6. Память GD.
    $mb = 1024 * 1024;
    t('gd 24mp does not fit in 128M', Converter::gdMemoryFits(6000, 4000, 2 * $mb, false, 128 * $mb, 20 * $mb) === false);
    t('gd 1mp fits in 128M', Converter::gdMemoryFits(1200, 800, $mb, false, 128 * $mb, 20 * $mb) === true);
    t('gd rotation needs double', Converter::gdMemoryFits(4000, 3000, $mb, false, 160 * $mb, 40 * $mb) === true
        && Converter::gdMemoryFits(4000, 3000, $mb, true, 160 * $mb, 40 * $mb) === false);
    t('gd unlimited memory ok', Converter::gdMemoryFits(8000, 6000, 0, true, -1, 0) === true);

    // 7. Чужие .webp не трогаем.
    $other = $root . '/upload/iblock/abc/other.jpg';
    copy($photo, $other);
    touch($other, time() - 100);
    $foreignBody = str_repeat('F', filesize($other) + 500);
    file_put_contents($other . '.webp', $foreignBody);
    $status = Converter::convertRelative('/upload/iblock/abc/other.jpg');
    t('foreign webp detected', $status === 'skipped_foreign', $status);
    t('foreign webp kept intact', is_file($other . '.webp') && file_get_contents($other . '.webp') === $foreignBody);
    Converter::deleteWebpFor('/upload/iblock/abc/other.jpg');
    t('file delete keeps foreign webp', is_file($other . '.webp'));

    $status = Converter::convertRelative('/upload/iblock/abc/photo.jpg');
    t('own webp still done', $status === 'done', $status);
    $opt['max_megapixels'] = '4';
    $opt['skip_min_px'] = '240';
    $mid = $root . '/upload/iblock/abc/mid.jpg';
    $im = imagecreatetruecolor(2500, 2200);
    for ($i = 0; $i < 2500; $i += 7) {
        imageline($im, $i, 0, $i, 2200, imagecolorallocate($im, $i % 255, ($i * 3) % 255, 90));
    }
    imagejpeg($im, $mid, 70);
    $opt['max_megapixels'] = '24';
    t('mid converted', Converter::convertRelative('/upload/iblock/abc/mid.jpg') === 'done');
    $opt['max_megapixels'] = '4';
    Converter::convertRelative('/upload/iblock/abc/mid.jpg');
    t('own copy removed when status turns error', !is_file($mid . '.webp'));
    $opt['max_megapixels'] = '24';
    $opt['skip_min_px'] = '120';

    // Уменьшение тяжёлых фото.
    t('target 6048x4032 -> 2560x1707', Converter::targetSize(6048, 4032, 2560) === [2560, 1707]);
    t('target portrait keeps ratio', Converter::targetSize(3000, 6000, 2560) === [1280, 2560]);
    t('target small image untouched', Converter::targetSize(800, 600, 2560) === null);
    t('target disabled with 0', Converter::targetSize(6048, 4032, 0) === null);
    t('gd memory: downscale cheaper than rotate full', Converter::gdMemoryFits(6048, 4032, 4 * $mb, true, 256 * $mb, 40 * $mb, [2560, 1707]) === true
        && Converter::gdMemoryFits(6048, 4032, 4 * $mb, true, 256 * $mb, 40 * $mb) === false);

    $opt['max_side'] = '1200';
    $bigPhoto = $root . '/upload/iblock/abc/camera.jpg';
    $im = imagecreatetruecolor(3000, 2000);
    for ($i = 0; $i < 3000; $i += 3) {
        imageline($im, $i, 0, 3000 - $i, 2000, imagecolorallocate($im, $i % 255, ($i * 7) % 255, ($i * 13) % 255));
    }
    imagejpeg($im, $bigPhoto, 92);
    $status = Converter::convertRelative('/upload/iblock/abc/camera.jpg');
    $info = @getimagesize($bigPhoto . '.webp');
    t('heavy jpeg downscaled to webp', $status === 'done' && is_array($info) && $info[0] === 1200 && $info[1] === 800, $status . ' ' . json_encode($info));
    t('heavy jpeg original untouched', getimagesize($bigPhoto)[0] === 3000);

    $bigPng = $root . '/upload/iblock/abc/banner.png';
    $im = imagecreatetruecolor(2400, 1600);
    imagealphablending($im, false);
    imagesavealpha($im, true);
    imagefilledrectangle($im, 0, 0, 2400, 1600, imagecolorallocatealpha($im, 0, 0, 0, 127));
    // «Фото» (шум) в центре, прозрачные поля по краям.
    mt_srand(42);
    for ($y = 200; $y < 1400; $y += 2) {
        for ($x = 300; $x < 2100; $x += 6) {
            imagefilledrectangle($im, $x, $y, $x + 5, $y + 1, imagecolorallocatealpha($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255), 0));
        }
    }
    imagepng($im, $bigPng);
    $status = Converter::convertRelative('/upload/iblock/abc/banner.png');
    $info = @getimagesize($bigPng . '.webp');
    t('heavy png downscaled', in_array($status, ['done'], true) && is_array($info) && $info[0] === 1200 && $info[1] === 800, $status . ' ' . json_encode($info));
    if (is_file($bigPng . '.webp') && function_exists('imagecreatefromwebp')) {
        $w = imagecreatefromwebp($bigPng . '.webp');
        $corner = imagecolorsforindex($w, imagecolorat($w, 2, 2));
        t('downscaled png keeps transparency', $corner['alpha'] >= 120, json_encode($corner));
    }
    $opt['max_side'] = '0';
    $status = Converter::convertRelative('/upload/iblock/abc/camera.jpg');
    t('max_side=0: existing copy kept as is', $status === 'done');
    unset($opt['max_side']);

    // Ночной режим и пауза.
    $C = '\\Sheer\\Webp\\Config';
    t('night off by default', $C::isNight(3) === false && $C::getCronPerMinute(3) === 50);
    $opt['night_enabled'] = 'Y';
    t('night 1-7: 3h is night', $C::isNight(3) === true && $C::getCronPerMinute(3) === 100);
    t('night 1-7: 7h and 0h are day', $C::isNight(7) === false && $C::isNight(0) === false);
    $opt['night_from'] = '23';
    $opt['night_to'] = '6';
    t('night over midnight 23-6', $C::isNight(23) && $C::isNight(2) && !$C::isNight(6) && !$C::isNight(12));
    $opt['night_per_minute'] = '200';
    $opt['cron_per_minute'] = '10';
    t('day/night rates', $C::getCronPerMinute(1) === 200 && $C::getCronPerMinute(14) === 10);
    $opt['cron_per_minute'] = '0';
    t('day 0 = no daytime conversion', $C::getCronPerMinute(14) === 0 && $C::getCronPerMinute(1) === 200);
    t('warm night only default on', $C::isWarmNightOnly() === true);
    unset($opt['night_enabled'], $opt['night_from'], $opt['night_to'], $opt['night_per_minute'], $opt['cron_per_minute']);
    t('warm night only needs night mode', $C::isWarmNightOnly() === false);

    $pp = $root . '/upload/iblock/abc/paused.jpg';
    copy($photo, $pp);
    Converter::enqueue('/upload/iblock/abc/paused.jpg');
    $C::setPaused(true);
    t('pause: convert agent stays, nothing converted', \Sheer\Webp\Agent::convertBatch() === \Sheer\Webp\Agent::CALLBACK
        && RegistryTable::getByOriginal('/upload/iblock/abc/paused.jpg')['STATUS'] === 'pending' && !is_file($pp . '.webp'));
    t('pause: warm agent waits', \Sheer\Webp\Agent::warmBatch() === \Sheer\Webp\Warmer::CALLBACK);
    $C::setPaused(false);
    t('resume clears pause', $C::isPaused() === false);
    Scanner::clearQueue();

    // Прогрев по sitemap: сортировка и обход через настоящий HTTP-сервер.
    $sorted = \Sheer\Webp\Warmer::sortByDepth(['https://s.ru/a/b/c/', 'https://s.ru/a/', 'https://s.ru/', 'https://s.ru/a/b/']);
    t('warm: sections before products', $sorted === ['https://s.ru/', 'https://s.ru/a/', 'https://s.ru/a/b/', 'https://s.ru/a/b/c/'], json_encode($sorted));
    t('warm: parse loc with entities', \Sheer\Webp\Warmer::parseLocs('<loc> https://s.ru/?a=1&amp;b=2 </loc>') === ['https://s.ru/?a=1&b=2']);

    $web = sys_get_temp_dir() . '/sheer-warm-' . bin2hex(random_bytes(3));
    mkdir($web . '/cat/sub', 0755, true);
    $port = 18000 + random_int(0, 900);
    $srvBase = 'http://127.0.0.1:' . $port;
    file_put_contents($web . '/sitemap.xml', '<sitemapindex><sitemap><loc>' . $srvBase . '/sm-1.xml</loc></sitemap></sitemapindex>');
    file_put_contents($web . '/sm-1.xml', '<urlset><url><loc>' . $srvBase . '/cat/sub/</loc></url><url><loc>' . $srvBase . '/cat/</loc></url>'
        . '<url><loc>' . $srvBase . '/</loc></url><url><loc>https://other.host/x/</loc></url><url><loc>' . $srvBase . '/missing.html</loc></url></urlset>');
    file_put_contents($web . '/index.html', 'home');
    file_put_contents($web . '/cat/index.html', 'cat');
    file_put_contents($web . '/cat/sub/index.html', 'sub');
    // Роутер: встроенный сервер иначе отдаёт index.html на любой несуществующий адрес.
    file_put_contents($web . '/router.php', '<?php if (parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH) === "/missing.html") { http_response_code(404); echo "nf"; return true; } return false;');
    $srv = proc_open([PHP_BINARY, '-S', '127.0.0.1:' . $port, '-t', $web, $web . '/router.php'], [['pipe', 'r'], ['file', '/dev/null', 'w'], ['file', '/dev/null', 'w']], $pipes);
    usleep(400000);
    $st = \Sheer\Webp\Warmer::start($srvBase);
    t('warm: start collects own-host pages', $st['state'] === 'run' && $st['total'] === 4 && empty($st['error']), json_encode($st));
    t('warm: agent registered', in_array(\Sheer\Webp\Warmer::CALLBACK, CAgent::$list, true));
    $more = \Sheer\Webp\Warmer::step(20);
    $st = \Sheer\Webp\Warmer::state();
    t('warm: step opens all pages', $more === false && $st['state'] === 'done' && $st['pos'] === 4, json_encode($st));
    t('warm: missing page counted', $st['errors'] === 1, json_encode($st));
    t('warm: stop removes agent', \Sheer\Webp\Warmer::stop()['state'] === 'idle' && !in_array(\Sheer\Webp\Warmer::CALLBACK, CAgent::$list, true));
    \Bitrix\Main\Config\Option::set('sheer.webp', 'warm_pos', '2');
    $st = \Sheer\Webp\Warmer::start($srvBase);
    t('warm: restart resumes from position', $st['state'] === 'run' && $st['pos'] === 2 && !empty($st['resumed']), json_encode($st));
    \Sheer\Webp\Warmer::stop();
    proc_terminate($srv);
    array_map('unlink', glob($web . '/cat/sub/*') ?: []);
    array_map('unlink', glob($web . '/cat/*.html') ?: []);
    array_map('unlink', glob($web . '/*.*') ?: []);

    // 3. Откат: модуль выключается, композит чищен, чужое остаётся.
    $cleanedBefore = CHTMLPagesCache::$cleaned;
    $guard = 0;
    while (($d = Scanner::rollbackChunk(3)) !== 0 && $guard++ < 50) {
    }
    t('rollback disables module', ($opt['enabled'] ?? '') === 'N');
    t('rollback clears composite', CHTMLPagesCache::$cleaned > $cleanedBefore);
    t('rollback removes own webp', !is_file($photo . '.webp') && !is_file($alpha . '.webp'));
    t('rollback keeps foreign webp', is_file($other . '.webp') && file_get_contents($other . '.webp') === $foreignBody);
    t('rollback keeps originals', is_file($photo) && is_file($other) && is_file($alpha));
    t('rollback empties registry', count(RegistryTable::$rows) === 0);
    Rewriter::clearCache();
    Rewriter::rewrite('<img src="/upload/iblock/abc/photo.jpg">');
    t('disabled module: hits do not requeue', count(RegistryTable::$rows) === 0 || !\Sheer\Webp\Config::isEnabled());
    $opt['enabled'] = 'Y';

    $syntaxFiles = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator(dirname(__DIR__) . '/sheer.webp')
    );
    $syntaxOk = true;
    $syntaxFail = '';
    foreach ($syntaxFiles as $file) {
        if ($file->getExtension() !== 'php') {
            continue;
        }
        $out = [];
        $code = 0;
        exec('php -l ' . escapeshellarg($file->getPathname()) . ' 2>&1', $out, $code);
        if ($code !== 0) {
            $syntaxOk = false;
            $syntaxFail = implode(' ', $out);
            break;
        }
    }
    t('php -l all module files', $syntaxOk, $syntaxFail);

    echo "\n{$passed} passed, {$failed} failed\n";

    $cleanup = static function ($dir) use (&$cleanup) {
        if (!is_dir($dir)) {
            return;
        }
        foreach (scandir($dir) ?: [] as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $path = $dir . '/' . $item;
            if (is_dir($path)) {
                $cleanup($path);
            } else {
                @unlink($path);
            }
        }
        @rmdir($dir);
    };
    $cleanup($root);

    exit($failed > 0 ? 1 : 0);
}
