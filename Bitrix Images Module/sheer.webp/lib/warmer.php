<?php

namespace Sheer\Webp;

use Bitrix\Main\Config\Option;

/**
 * Прогрев по sitemap: открывает страницы сайта, чтобы Битрикс собрал их
 * (и композитный кеш), а модуль поставил их картинки в очередь.
 * Работает агентом из cron, порциями, в несколько потоков; прогресс в b_option,
 * список адресов — в tmp-файле модуля.
 */
class Warmer
{
    public const CALLBACK = '\\Sheer\\Webp\\Agent::warmBatch();';

    /** Секунд на порцию за один запуск агента. */
    public const BUDGET = 40;

    public const USER_AGENT = 'Mozilla/5.0 (compatible; SheerWebP-Warmup/1.0)';

    public static function getThreads()
    {
        return max(1, min(8, (int)Option::get(Config::MODULE_ID, 'warm_threads', '4')));
    }

    public static function state()
    {
        return [
            'state' => Option::get(Config::MODULE_ID, 'warm_state', 'idle'),
            'pos' => (int)Option::get(Config::MODULE_ID, 'warm_pos', '0'),
            'total' => (int)Option::get(Config::MODULE_ID, 'warm_total', '0'),
            'errors' => (int)Option::get(Config::MODULE_ID, 'warm_errors', '0'),
        ];
    }

    public static function listFile()
    {
        return dirname(Config::queueFilePath()) . '/sheer_webp_warm_urls.txt';
    }

    /**
     * Собрать адреса из sitemap и запустить агента.
     * @param string $base https://site.ru — адрес сайта, с которого открыта админка
     * @return array state + 'error' при неудаче
     */
    public static function start($base)
    {
        $base = rtrim((string)$base, '/');
        if (!preg_match('~^https?://[^/]+$~i', $base)) {
            return self::state() + ['error' => 'Не удалось определить адрес сайта'];
        }

        $urls = self::collect($base);
        if (!$urls) {
            return self::state() + ['error' => 'В ' . $base . '/sitemap.xml не найдено страниц'];
        }

        $file = self::listFile();
        if (@file_put_contents($file, implode("\n", $urls) . "\n", LOCK_EX) === false) {
            return self::state() + ['error' => 'Не удалось записать список страниц'];
        }

        Option::set(Config::MODULE_ID, 'warm_pos', '0');
        Option::set(Config::MODULE_ID, 'warm_total', (string)count($urls));
        Option::set(Config::MODULE_ID, 'warm_errors', '0');
        Option::set(Config::MODULE_ID, 'warm_state', 'run');
        Agent::ensureWarm();

        return self::state();
    }

    public static function stop()
    {
        Option::set(Config::MODULE_ID, 'warm_state', 'idle');
        Agent::removeWarm();

        return self::state();
    }

    /**
     * Адреса из sitemap (с вложенными sitemap-*.xml) только своего хоста,
     * отсортированы: сначала разделы (меньше сегментов пути), потом карточки.
     */
    public static function collect($base)
    {
        $host = strtolower((string)parse_url($base, PHP_URL_HOST));
        $queue = [$base . '/sitemap.xml'];
        $seenMaps = [];
        $urls = [];

        while ($queue && count($seenMaps) < 200) {
            $map = array_shift($queue);
            if (isset($seenMaps[$map])) {
                continue;
            }
            $seenMaps[$map] = true;

            $xml = self::fetch($map);
            if ($xml === '') {
                continue;
            }
            foreach (self::parseLocs($xml) as $loc) {
                if (strtolower((string)parse_url($loc, PHP_URL_HOST)) !== $host) {
                    continue;
                }
                if (preg_match('~\.xml(\?.*)?$~i', $loc)) {
                    $queue[] = $loc;
                } else {
                    $urls[$loc] = true;
                }
            }
        }

        return self::sortByDepth(array_keys($urls));
    }

    public static function parseLocs($xml)
    {
        if (!preg_match_all('~<loc>\s*([^<\s]+)\s*</loc>~i', (string)$xml, $m)) {
            return [];
        }

        return array_map(static function ($u) {
            return html_entity_decode($u, ENT_QUOTES | ENT_XML1, 'UTF-8');
        }, $m[1]);
    }

    public static function sortByDepth(array $urls)
    {
        $depth = static function ($u) {
            return count(array_filter(explode('/', (string)parse_url($u, PHP_URL_PATH)), 'strlen'));
        };
        usort($urls, static function ($a, $b) use ($depth) {
            return [$depth($a), $a] <=> [$depth($b), $b];
        });

        return $urls;
    }

    /**
     * Один запуск агента: открыть следующую порцию страниц.
     * @return bool true — есть ещё страницы
     */
    public static function step($budget = self::BUDGET)
    {
        $st = self::state();
        if ($st['state'] !== 'run' || !Config::isEnabled()) {
            return false;
        }

        // Отдельная блокировка: два запуска cron не открывают одни и те же страницы.
        $lockFp = @fopen(dirname(Config::queueFilePath()) . '/sheer_webp_warm.lock', 'c');
        if ($lockFp && !flock($lockFp, LOCK_EX | LOCK_NB)) {
            fclose($lockFp);
            return true;
        }

        try {
            $urls = @file(self::listFile(), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            if (!is_array($urls) || !$urls) {
                Option::set(Config::MODULE_ID, 'warm_state', 'idle');
                return false;
            }

            $pos = $st['pos'];
            $errors = $st['errors'];
            $threads = self::getThreads();
            $started = time();

            while ($pos < count($urls) && (time() - $started) < $budget) {
                $batch = array_slice($urls, $pos, $threads);
                $errors += self::openPages($batch);
                $pos += count($batch);
                // Прогресс после каждой пачки — переживает обрыв процесса.
                Option::set(Config::MODULE_ID, 'warm_pos', (string)$pos);
                if (!Config::isEnabled() || Option::get(Config::MODULE_ID, 'warm_state', 'idle') !== 'run') {
                    break;
                }
            }
            Option::set(Config::MODULE_ID, 'warm_errors', (string)$errors);

            if ($pos >= count($urls)) {
                Option::set(Config::MODULE_ID, 'warm_state', 'done');
                return false;
            }

            return Option::get(Config::MODULE_ID, 'warm_state', 'idle') === 'run';
        } finally {
            if ($lockFp) {
                flock($lockFp, LOCK_UN);
                fclose($lockFp);
            }
        }
    }

    /**
     * Открыть страницы параллельно. Тело ответа не нужно — важна сборка страницы.
     * @return int сколько страниц ответили не 2xx/3xx
     */
    private static function openPages(array $batch)
    {
        if (!function_exists('curl_multi_init')) {
            $failed = 0;
            foreach ($batch as $url) {
                if (self::fetch($url) === '') {
                    $failed++;
                }
            }
            return $failed;
        }

        $mh = curl_multi_init();
        $handles = [];
        foreach ($batch as $url) {
            $ch = self::curl($url);
            curl_multi_add_handle($mh, $ch);
            $handles[] = $ch;
        }

        do {
            $status = curl_multi_exec($mh, $running);
            if ($running) {
                curl_multi_select($mh, 1.0);
            }
        } while ($running && $status === CURLM_OK);

        $failed = 0;
        foreach ($handles as $ch) {
            $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($code < 200 || $code >= 400) {
                $failed++;
            }
            curl_multi_remove_handle($mh, $ch);
            if (PHP_VERSION_ID < 80500) {
                curl_close($ch);
            }
        }
        curl_multi_close($mh);

        return $failed;
    }

    private static function curl($url)
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_USERAGENT => self::USER_AGENT,
            // image/webp: без композита модуль ставит в очередь только для таких браузеров.
            CURLOPT_HTTPHEADER => ['Accept: text/html,image/webp'],
            CURLOPT_ENCODING => '',
        ]);

        return $ch;
    }

    private static function fetch($url)
    {
        if (function_exists('curl_init')) {
            $ch = self::curl($url);
            $body = curl_exec($ch);
            $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if (PHP_VERSION_ID < 80500) {
                curl_close($ch);
            }

            return ($body !== false && $code >= 200 && $code < 400) ? (string)$body : '';
        }

        $ctx = stream_context_create(['http' => [
            'timeout' => 60,
            'header' => "User-Agent: " . self::USER_AGENT . "\r\nAccept: text/html\r\n",
        ]]);
        $body = @file_get_contents($url, false, $ctx);

        return $body === false ? '' : (string)$body;
    }
}
