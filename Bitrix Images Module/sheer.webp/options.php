<?php

use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Sheer\Webp\Config;
use Sheer\Webp\Converter;
use Sheer\Webp\RegistryTable;
use Sheer\Webp\WebServer;

$module_id = 'sheer.webp';

Loc::loadMessages(__FILE__);

if ($APPLICATION->GetGroupRight($module_id) < 'R') {
    $APPLICATION->AuthForm(GetMessage('ACCESS_DENIED'));
}

Loader::includeModule($module_id);

if (!function_exists('sheer_webp_format_bytes')) {
    function sheer_webp_format_bytes($bytes)
    {
        $bytes = (int)$bytes;
        if ($bytes < 1024) {
            return $bytes . ' Б';
        }
        if ($bytes < 1048576) {
            return str_replace('.', ',', (string)round($bytes / 1024, 1)) . ' КБ';
        }
        if ($bytes < 1073741824) {
            return str_replace('.', ',', (string)round($bytes / 1048576, 1)) . ' МБ';
        }

        return str_replace('.', ',', (string)round($bytes / 1073741824, 2)) . ' ГБ';
    }
}

if (!function_exists('sheer_webp_render_stats')) {
    function sheer_webp_render_stats(array $stats, $phase, array $warm = [])
    {
        $labels = [
            'idle' => Loc::getMessage('SHEER_WEBP_PHASE_IDLE'),
            'scan' => Loc::getMessage('SHEER_WEBP_PHASE_SCAN'),
            'convert' => Loc::getMessage('SHEER_WEBP_PHASE_CONVERT'),
        ];
        $total = (int)($stats['total'] ?? 0);
        $done = (int)($stats['done'] ?? 0);
        $convertedPercent = (float)($stats['converted_percent'] ?? 0);
        $savedPercent = (float)($stats['saved_percent'] ?? 0);
        $saved = (int)($stats['saved'] ?? 0);
        $originalSize = (int)($stats['original_size'] ?? 0);
        $webpSize = (int)($stats['webp_size'] ?? 0);

        $html = '<div class="sheer-webp-dash">';
        $html .= '<div class="sheer-webp-card">';
        $html .= '<div class="sheer-webp-card-value">' . htmlspecialcharsbx(number_format($total, 0, ',', ' ')) . '</div>';
        $html .= '<div class="sheer-webp-card-label">' . Loc::getMessage('SHEER_WEBP_STAT_TOTAL') . '</div>';
        $html .= '</div>';

        $html .= '<div class="sheer-webp-card">';
        $html .= '<div class="sheer-webp-card-value">' . htmlspecialcharsbx(number_format($done, 0, ',', ' '));
        $html .= ' <span class="sheer-webp-card-pct">' . htmlspecialcharsbx(str_replace('.', ',', (string)$convertedPercent)) . '%</span></div>';
        $html .= '<div class="sheer-webp-card-label">' . Loc::getMessage('SHEER_WEBP_STAT_CONVERTED') . '</div>';
        $html .= '<div class="sheer-webp-bar"><span style="width:' . htmlspecialcharsbx((string)min(100, $convertedPercent)) . '%"></span></div>';
        $html .= '</div>';

        $html .= '<div class="sheer-webp-card">';
        $html .= '<div class="sheer-webp-card-value">' . htmlspecialcharsbx(str_replace('.', ',', (string)$savedPercent)) . '%</div>';
        $html .= '<div class="sheer-webp-card-label">' . Loc::getMessage('SHEER_WEBP_STAT_SAVED_VOLUME');
        if ($originalSize > 0) {
            $html .= ': ' . htmlspecialcharsbx(sheer_webp_format_bytes($saved))
                . ' ' . Loc::getMessage('SHEER_WEBP_STAT_OF') . ' '
                . htmlspecialcharsbx(sheer_webp_format_bytes($originalSize));
        }
        $html .= '</div>';
        $html .= '<div class="sheer-webp-bar sheer-webp-bar-save"><span style="width:' . htmlspecialcharsbx((string)min(100, $savedPercent)) . '%"></span></div>';
        $html .= '</div>';
        $html .= '</div>';

        if ($total === 0) {
            $html .= '<p class="sheer-webp-empty">' . Loc::getMessage('SHEER_WEBP_STAT_EMPTY') . '</p>';
        }

        $html .= '<div class="sheer-webp-meta">';
        $html .= '<b>Статус:</b> ' . htmlspecialcharsbx($labels[$phase] ?? $phase);
        $html .= ' · ' . Loc::getMessage('SHEER_WEBP_STAT_PENDING') . ': <b>' . (int)$stats['pending'] . '</b>';
        $html .= ' · ' . Loc::getMessage('SHEER_WEBP_STAT_SKIP') . ': ' . (int)$stats['skipped_larger'];
        $html .= ' · ' . Loc::getMessage('SHEER_WEBP_STAT_ERROR') . ': ' . (int)$stats['error'];
        if ($webpSize > 0) {
            $html .= ' · WebP: ' . htmlspecialcharsbx(sheer_webp_format_bytes($webpSize));
        }
        if (class_exists('\\Sheer\\Webp\\AjaxService')) {
            $m = \Sheer\Webp\AjaxService::mode();
            $html .= '<br><b>Фон (cron):</b> ' . ($m['paused']
                ? '<span style="color:#c00">пауза</span>'
                : ($m['night'] ? 'ночной режим' : 'дневной режим') . ' · ' . (int)$m['per_minute'] . ' картинок в минуту'
                    . ($m['warm_night_only'] ? ' · прогрев только ночью (' . (int)$m['night_from'] . '–' . (int)$m['night_to'] . ' ч)' : ''));
        }
        if (($warm['total'] ?? 0) > 0) {
            $warmLabels = ['run' => 'идёт', 'done' => 'завершён', 'idle' => 'остановлен'];
            $html .= '<br><b>Прогрев по sitemap:</b> ' . htmlspecialcharsbx($warmLabels[$warm['state']] ?? $warm['state'])
                . ' · ' . number_format((int)$warm['pos'], 0, ',', ' ') . ' из ' . number_format((int)$warm['total'], 0, ',', ' ') . ' страниц'
                . ((int)$warm['errors'] > 0 ? ' · не открылось: ' . (int)$warm['errors'] : '');
        }
        $html .= '</div>';

        return $html;
    }
}

$request = \Bitrix\Main\Context::getCurrent()->getRequest();
$canWrite = $APPLICATION->GetGroupRight($module_id) >= 'W';

if (
    $request->isPost()
    && $canWrite
    && check_bitrix_sessid()
    // $tabControl->Buttons() рисует кнопки save/apply; Update/Apply — классические имена.
    && ($request->getPost('save') || $request->getPost('apply') || $request->getPost('Update') || $request->getPost('Apply'))
) {
    // Композитный кеш чистим только если меняется то, что влияет на HTML:
    // очистка заставляет пересобрать все страницы и нагружает сайт.
    $htmlStateBefore = Option::get($module_id, 'enabled', 'Y') . Option::get($module_id, 'rewrite_html', 'Y');
    Option::set($module_id, 'enabled', $request->getPost('enabled') === 'Y' ? 'Y' : 'N');
    Option::set($module_id, 'quality', (string)max(80, min(100, (int)$request->getPost('quality'))));
    Option::set($module_id, 'skip_if_larger', $request->getPost('skip_if_larger') === 'Y' ? 'Y' : 'N');
    Option::set($module_id, 'rewrite_html', $request->getPost('rewrite_html') === 'Y' ? 'Y' : 'N');
    Option::set($module_id, 'convert_on_upload', $request->getPost('convert_on_upload') === 'Y' ? 'Y' : 'N');
    Option::set($module_id, 'scan_templates', $request->getPost('scan_templates') === 'Y' ? 'Y' : 'N');
    Option::set($module_id, 'scan_resize_cache', $request->getPost('scan_resize_cache') === 'Y' ? 'Y' : 'N');
    Option::set($module_id, 'skip_min_px', (string)max(48, min(240, (int)$request->getPost('skip_min_px'))));
    Option::set($module_id, 'scan_roots', (string)$request->getPost('scan_roots'));
    Option::set($module_id, 'batch_size', (string)max(3, min(40, (int)$request->getPost('batch_size'))));
    $maxSide = (int)$request->getPost('max_side');
    Option::set($module_id, 'max_side', (string)($maxSide <= 0 ? 0 : max(1000, min(8000, $maxSide))));
    Option::set($module_id, 'max_source_mb', (string)max(1, min(60, (int)$request->getPost('max_source_mb'))));
    Option::set($module_id, 'max_megapixels', (string)max(4, min(200, (int)$request->getPost('max_megapixels'))));
    Option::set($module_id, 'warm_threads', (string)max(1, min(8, (int)$request->getPost('warm_threads'))));
    Option::set($module_id, 'cron_per_minute', (string)max(10, min(500, (int)$request->getPost('cron_per_minute'))));
    Option::set($module_id, 'night_enabled', $request->getPost('night_enabled') === 'Y' ? 'Y' : 'N');
    Option::set($module_id, 'night_from', (string)max(0, min(23, (int)$request->getPost('night_from'))));
    Option::set($module_id, 'night_to', (string)max(0, min(23, (int)$request->getPost('night_to'))));
    Option::set($module_id, 'night_per_minute', (string)max(10, min(500, (int)$request->getPost('night_per_minute'))));
    Option::set($module_id, 'warm_night_only', $request->getPost('warm_night_only') === 'Y' ? 'Y' : 'N');
    Option::set($module_id, 'apache_htaccess', $request->getPost('apache_htaccess') === 'Y' ? 'Y' : 'N');

    if (Option::get($module_id, 'apache_htaccess', 'Y') === 'Y') {
        WebServer::installApacheRules();
    } else {
        WebServer::removeApacheRules();
    }

    $htmlStateAfter = Option::get($module_id, 'enabled', 'Y') . Option::get($module_id, 'rewrite_html', 'Y');
    if ($htmlStateBefore !== $htmlStateAfter && class_exists('CHTMLPagesCache') && method_exists('CHTMLPagesCache', 'CleanAll')) {
        CHTMLPagesCache::CleanAll();
    }
}

$engine = Converter::engine();
$stats = RegistryTable::getStats();
$phase = Config::getPhase();

$aTabs = [
    [
        'DIV' => 'edit1',
        'TAB' => Loc::getMessage('SHEER_WEBP_TAB_SETTINGS'),
        'TITLE' => Loc::getMessage('SHEER_WEBP_TAB_SETTINGS_TITLE'),
    ],
    [
        'DIV' => 'edit2',
        'TAB' => Loc::getMessage('SHEER_WEBP_TAB_CONVERT'),
        'TITLE' => Loc::getMessage('SHEER_WEBP_TAB_CONVERT_TITLE'),
    ],
    [
        'DIV' => 'edit3',
        'TAB' => Loc::getMessage('SHEER_WEBP_TAB_ROLLBACK'),
        'TITLE' => Loc::getMessage('SHEER_WEBP_TAB_ROLLBACK_TITLE'),
    ],
];

$tabControl = new CAdminTabControl('sheer_webp_tab', $aTabs);
$tabControl->Begin();
?>

<form method="post" action="<?= $APPLICATION->GetCurPage(); ?>?mid=<?= urlencode($module_id); ?>&lang=<?= LANGUAGE_ID; ?>">
    <?= bitrix_sessid_post(); ?>
    <?php $tabControl->BeginNextTab(); ?>
    <tr>
        <td colspan="2">
            <div class="sheer-webp-stats">
                <?= sheer_webp_render_stats($stats, $phase, \Sheer\Webp\Warmer::state()); ?>
            </div>
            <?php
            echo BeginNote();
            echo Loc::getMessage('SHEER_WEBP_NOTE');
            echo EndNote();
            ?>
        </td>
    </tr>
    <tr>
        <td width="40%"><?= Loc::getMessage('SHEER_WEBP_ENGINE'); ?></td>
        <td>
            <?php if ($engine !== ''): ?>
                <b><?= htmlspecialcharsbx(strtoupper($engine)); ?></b>
            <?php else: ?>
                <span style="color:#c00"><?= Loc::getMessage('SHEER_WEBP_ENGINE_NONE'); ?></span>
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('SHEER_WEBP_ENABLED'); ?></td>
        <td>
            <input type="checkbox" name="enabled" value="Y" <?= Option::get($module_id, 'enabled', 'Y') === 'Y' ? 'checked' : ''; ?>>
        </td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('SHEER_WEBP_QUALITY'); ?></td>
        <td>
            <input type="number" name="quality" min="80" max="100" value="<?= (int)Option::get($module_id, 'quality', '90'); ?>">
            <br><small><?= Loc::getMessage('SHEER_WEBP_QUALITY_HINT'); ?></small>
        </td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('SHEER_WEBP_SKIP_LARGER'); ?></td>
        <td>
            <input type="checkbox" name="skip_if_larger" value="Y" <?= Option::get($module_id, 'skip_if_larger', 'Y') === 'Y' ? 'checked' : ''; ?>>
        </td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('SHEER_WEBP_ON_UPLOAD'); ?></td>
        <td>
            <input type="checkbox" name="convert_on_upload" value="Y" <?= Option::get($module_id, 'convert_on_upload', 'Y') === 'Y' ? 'checked' : ''; ?>>
        </td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('SHEER_WEBP_REWRITE'); ?></td>
        <td>
            <input type="checkbox" name="rewrite_html" value="Y" <?= Option::get($module_id, 'rewrite_html', 'Y') === 'Y' ? 'checked' : ''; ?>>
            <br><small><?= Loc::getMessage('SHEER_WEBP_REWRITE_HINT'); ?></small>
        </td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('SHEER_WEBP_SCAN_TEMPLATES'); ?></td>
        <td>
            <input type="checkbox" name="scan_templates" value="Y" <?= Option::get($module_id, 'scan_templates', 'N') === 'Y' ? 'checked' : ''; ?>>
        </td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('SHEER_WEBP_SCAN_RESIZE'); ?></td>
        <td>
            <input type="checkbox" name="scan_resize_cache" value="Y" <?= Option::get($module_id, 'scan_resize_cache', 'N') === 'Y' ? 'checked' : ''; ?>>
            <br><small><?= Loc::getMessage('SHEER_WEBP_SCAN_RESIZE_HINT'); ?></small>
        </td>
    </tr>
    <tr>
        <td valign="top"><?= Loc::getMessage('SHEER_WEBP_SCAN_ROOTS'); ?></td>
        <td>
            <textarea name="scan_roots" cols="72" rows="5"><?= htmlspecialcharsbx(Option::get($module_id, 'scan_roots', "/upload/iblock\n/upload/CMax\n/upload/medialibrary")); ?></textarea>
            <br><small><?= Loc::getMessage('SHEER_WEBP_SCAN_ROOTS_HINT'); ?></small>
        </td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('SHEER_WEBP_SKIP_MIN'); ?></td>
        <td>
            <input type="number" name="skip_min_px" min="48" max="240" value="<?= (int)Option::get($module_id, 'skip_min_px', '120'); ?>">
            <br><small><?= Loc::getMessage('SHEER_WEBP_SKIP_MIN_HINT'); ?></small>
        </td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('SHEER_WEBP_BATCH'); ?></td>
        <td>
            <input type="number" name="batch_size" min="3" max="40" value="<?= (int)Option::get($module_id, 'batch_size', '8'); ?>">
        </td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('SHEER_WEBP_MAX_SIDE'); ?></td>
        <td>
            <input type="number" name="max_side" min="0" max="8000" step="10" value="<?= (int)Option::get($module_id, 'max_side', '2560'); ?>">
            <br><small><?= Loc::getMessage('SHEER_WEBP_MAX_SIDE_HINT'); ?></small>
        </td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('SHEER_WEBP_MAX_MB'); ?></td>
        <td>
            <input type="number" name="max_source_mb" min="1" max="60" value="<?= (int)Option::get($module_id, 'max_source_mb', '30'); ?>">
            <br><small><?= Loc::getMessage('SHEER_WEBP_MAX_MB_HINT'); ?></small>
        </td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('SHEER_WEBP_MAX_MP'); ?></td>
        <td>
            <input type="number" name="max_megapixels" min="4" max="200" value="<?= (int)Option::get($module_id, 'max_megapixels', '80'); ?>">
        </td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('SHEER_WEBP_WARM_THREADS'); ?></td>
        <td>
            <input type="number" name="warm_threads" min="1" max="8" value="<?= (int)Option::get($module_id, 'warm_threads', '4'); ?>">
            <br><small><?= Loc::getMessage('SHEER_WEBP_WARM_THREADS_HINT'); ?></small>
        </td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('SHEER_WEBP_CRON_PER_MINUTE'); ?></td>
        <td>
            <input type="number" name="cron_per_minute" min="10" max="500" value="<?= (int)Option::get($module_id, 'cron_per_minute', '50'); ?>">
            <br><small><?= Loc::getMessage('SHEER_WEBP_CRON_PER_MINUTE_HINT'); ?></small>
        </td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('SHEER_WEBP_NIGHT'); ?></td>
        <td>
            <input type="checkbox" name="night_enabled" value="Y" <?= Option::get($module_id, 'night_enabled', 'N') === 'Y' ? 'checked' : ''; ?>>
            с <input type="number" name="night_from" min="0" max="23" style="width:56px" value="<?= (int)Option::get($module_id, 'night_from', '1'); ?>"> ч
            до <input type="number" name="night_to" min="0" max="23" style="width:56px" value="<?= (int)Option::get($module_id, 'night_to', '7'); ?>"> ч,
            картинок в минуту: <input type="number" name="night_per_minute" min="10" max="500" style="width:72px" value="<?= (int)Option::get($module_id, 'night_per_minute', '100'); ?>">
            <br><label><input type="checkbox" name="warm_night_only" value="Y" <?= Option::get($module_id, 'warm_night_only', 'Y') === 'Y' ? 'checked' : ''; ?>> <?= Loc::getMessage('SHEER_WEBP_WARM_NIGHT_ONLY'); ?></label>
            <br><small><?= Loc::getMessage('SHEER_WEBP_NIGHT_HINT'); ?> <?= htmlspecialcharsbx(date('H:i')); ?>.</small>
        </td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('SHEER_WEBP_APACHE'); ?></td>
        <td>
            <input type="checkbox" name="apache_htaccess" value="Y" <?= Option::get($module_id, 'apache_htaccess', 'N') === 'Y' ? 'checked' : ''; ?>>
            <?php if ($canWrite): ?>
                <br><a href="javascript:void(0)" id="sheer-webp-htaccess"><?= Loc::getMessage('SHEER_WEBP_INSTALL_HTACCESS'); ?></a>
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <td valign="top"><?= Loc::getMessage('SHEER_WEBP_NGINX'); ?></td>
        <td>
            <textarea readonly cols="90" rows="22"><?= htmlspecialcharsbx(WebServer::nginxSnippet()); ?></textarea>
        </td>
    </tr>

    <?php $tabControl->BeginNextTab(); ?>
    <tr>
        <td colspan="2">
            <div class="sheer-webp-stats">
                <?= sheer_webp_render_stats($stats, $phase, \Sheer\Webp\Warmer::state()); ?>
            </div>
            <?php if ($canWrite): ?>
                <p>
                    <input type="button" class="adm-btn-save" id="sheer-webp-start" value="<?= htmlspecialcharsbx(Loc::getMessage('SHEER_WEBP_START')); ?>">
                    <input type="button" id="sheer-webp-stop" value="<?= htmlspecialcharsbx(Loc::getMessage('SHEER_WEBP_STOP')); ?>">
                    <input type="button" id="sheer-webp-clear" value="<?= htmlspecialcharsbx(Loc::getMessage('SHEER_WEBP_CLEAR_QUEUE')); ?>">
                    <input type="button" id="sheer-webp-pause" value="<?= htmlspecialcharsbx(Loc::getMessage(\Sheer\Webp\Config::isPaused() ? 'SHEER_WEBP_RESUME' : 'SHEER_WEBP_PAUSE')); ?>">
                </p>
                <p>
                    <input type="button" id="sheer-webp-warm" value="<?= htmlspecialcharsbx(Loc::getMessage('SHEER_WEBP_WARM_START')); ?>">
                    <input type="button" id="sheer-webp-warm-stop" value="<?= htmlspecialcharsbx(Loc::getMessage('SHEER_WEBP_WARM_STOP')); ?>">
                    <br><small><?= Loc::getMessage('SHEER_WEBP_WARM_HINT'); ?></small>
                </p>
                <p id="sheer-webp-run-status" class="sheer-webp-status"></p>
            <?php endif; ?>
        </td>
    </tr>

    <?php $tabControl->BeginNextTab(); ?>
    <tr>
        <td colspan="2">
            <?php
            echo BeginNote();
            echo Loc::getMessage('SHEER_WEBP_ROLLBACK_HINT');
            echo EndNote();
            ?>
            <?php if ($canWrite): ?>
                <p>
                    <input type="button" id="sheer-webp-rollback" value="<?= htmlspecialcharsbx(Loc::getMessage('SHEER_WEBP_ROLLBACK')); ?>">
                </p>
                <p id="sheer-webp-rollback-status" class="sheer-webp-status"></p>
            <?php endif; ?>
        </td>
    </tr>

    <?php
    $tabControl->Buttons([
        'disabled' => !$canWrite,
    ]);
    $tabControl->End();
    ?>
</form>

<style>
.sheer-webp-dash {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin: 4px 0 16px;
}
.sheer-webp-card {
    flex: 1 1 200px;
    min-width: 180px;
    background: #fff;
    border: 1px solid #dce2e8;
    border-radius: 8px;
    padding: 14px 16px 16px;
    box-sizing: border-box;
}
.sheer-webp-card-value {
    font-size: 28px;
    line-height: 1.15;
    font-weight: 700;
    color: #1f2a37;
}
.sheer-webp-card-pct {
    font-size: 16px;
    font-weight: 600;
    color: #2f6fed;
}
.sheer-webp-card-label {
    margin-top: 6px;
    color: #667085;
    font-size: 13px;
}
.sheer-webp-bar {
    margin-top: 10px;
    height: 6px;
    background: #eef2f6;
    border-radius: 99px;
    overflow: hidden;
}
.sheer-webp-bar span {
    display: block;
    height: 100%;
    background: #2f6fed;
    border-radius: 99px;
}
.sheer-webp-bar-save span {
    background: #12b76a;
}
.sheer-webp-empty {
    color: #667085;
    margin: 0 0 10px;
}
.sheer-webp-meta {
    color: #475467;
    line-height: 1.6;
    margin-bottom: 8px;
}
</style>

<script>
(function () {
    var ajaxUrl = '/bitrix/admin/sheer_webp_ajax.php';
    var running = false;
    var rolling = false;
    var sessid = '<?= CUtil::JSEscape(bitrix_sessid()); ?>';

    // Статус пишем на обе вкладки: кнопки отката и конвертации на разных вкладках.
    function setStatus(text) {
        var nodes = document.querySelectorAll('.sheer-webp-status');
        for (var i = 0; i < nodes.length; i++) {
            nodes[i].textContent = text;
        }
    }

    function showAjaxError(detail) {
        running = false;
        rolling = false;
        var rb = document.getElementById('sheer-webp-rollback');
        if (rb) {
            rb.disabled = false;
        }
        setStatus('Ошибка запроса' + (detail ? ': ' + detail : ''));
        if (window.console) {
            console.error('Sheer WebP:', detail);
        }
    }

    function shortText(text) {
        // Фатальная ошибка PHP приходит HTML-страницей — показываем её текст.
        return String(text || '').replace(/<[^>]+>/g, ' ').replace(/\s+/g, ' ').trim().slice(0, 400);
    }

    // onError — для фоновых запросов: свой обработчик вместо сообщения об ошибке.
    function postFallback(action, cb, firstError, onError) {
        var body = 'sessid=' + encodeURIComponent(sessid) + '&ajax_action=' + encodeURIComponent(action);
        fetch(ajaxUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
            body: body
        }).then(function (r) {
            return r.text().then(function (text) {
                var data;
                try {
                    data = JSON.parse(text);
                } catch (e) {
                    throw new Error('HTTP ' + r.status + ' ' + shortText(text));
                }
                if (data && data.error) {
                    throw new Error('HTTP ' + r.status + ' ' + data.error);
                }
                return data;
            });
        }).then(function (data) {
            try {
                cb(data);
            } catch (e) {
                showAjaxError('JS: ' + e.message);
            }
        }).catch(function (e) {
            var msg = (e && e.message ? e.message : String(e)) + (firstError ? ' | runAction: ' + firstError : '');
            if (onError) {
                onError(msg);
                return;
            }
            showAjaxError(msg);
        });
    }

    function runActionError(err) {
        if (err && err.errors && err.errors.length) {
            return err.errors.map(function (x) { return x.message; }).join('; ');
        }
        return err && err.message ? err.message : String(err);
    }

    function post(action, cb, onError) {
        var map = {
            rollback_chunk: 'rollbackChunk',
            install_htaccess: 'installHtaccess',
            clear_queue: 'clearQueue',
            warm_start: 'warmStart',
            warm_stop: 'warmStop'
        };
        var engineAction = map[action] || action;
        if (window.BX && BX.ajax && typeof BX.ajax.runAction === 'function') {
            BX.ajax.runAction('sheer:webp.ajax.' + engineAction, { data: {} })
                .then(function (response) {
                    var data = response && response.data ? response.data : response;
                    if (data && (data.ok || data.phase || data.engine !== undefined)) {
                        try {
                            cb(data);
                        } catch (e) {
                            showAjaxError('JS: ' + e.message);
                        }
                        return;
                    }
                    postFallback(action, cb, 'пустой ответ', onError);
                }, function (err) {
                    postFallback(action, cb, runActionError(err), onError);
                });
            return;
        }
        postFallback(action, cb, '', onError);
    }

    function fmtBytes(n) {
        n = parseInt(n, 10) || 0;
        if (n < 1024) return n + ' Б';
        if (n < 1048576) return (n / 1024).toFixed(1).replace('.', ',') + ' КБ';
        if (n < 1073741824) return (n / 1048576).toFixed(1).replace('.', ',') + ' МБ';
        return (n / 1073741824).toFixed(2).replace('.', ',') + ' ГБ';
    }

    function fmtNum(n) {
        n = parseInt(n, 10) || 0;
        return String(n).replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
    }

    function fmtPct(n) {
        n = parseFloat(n) || 0;
        return String(n).replace('.', ',');
    }

    function phaseLabel(phase) {
        if (phase === 'scan') return '<?= CUtil::JSEscape(Loc::getMessage('SHEER_WEBP_PHASE_SCAN')); ?>';
        if (phase === 'convert') return '<?= CUtil::JSEscape(Loc::getMessage('SHEER_WEBP_PHASE_CONVERT')); ?>';
        return '<?= CUtil::JSEscape(Loc::getMessage('SHEER_WEBP_PHASE_IDLE')); ?>';
    }

    function renderStats(data) {
        var total = parseInt(data.total, 10) || 0;
        var done = parseInt(data.done, 10) || 0;
        var convertedPercent = parseFloat(data.converted_percent) || 0;
        var savedPercent = parseFloat(data.saved_percent) || 0;
        var saved = parseInt(data.saved, 10) || 0;
        var originalSize = parseInt(data.original_size, 10) || 0;
        var webpSize = parseInt(data.webp_size, 10) || 0;
        var html = '<div class="sheer-webp-dash">';
        html += '<div class="sheer-webp-card"><div class="sheer-webp-card-value">' + fmtNum(total) + '</div>';
        html += '<div class="sheer-webp-card-label"><?= CUtil::JSEscape(Loc::getMessage('SHEER_WEBP_STAT_TOTAL')); ?></div></div>';
        html += '<div class="sheer-webp-card"><div class="sheer-webp-card-value">' + fmtNum(done);
        html += ' <span class="sheer-webp-card-pct">' + fmtPct(convertedPercent) + '%</span></div>';
        html += '<div class="sheer-webp-card-label"><?= CUtil::JSEscape(Loc::getMessage('SHEER_WEBP_STAT_CONVERTED')); ?></div>';
        html += '<div class="sheer-webp-bar"><span style="width:' + Math.min(100, convertedPercent) + '%"></span></div></div>';
        html += '<div class="sheer-webp-card"><div class="sheer-webp-card-value">' + fmtPct(savedPercent) + '%</div>';
        html += '<div class="sheer-webp-card-label"><?= CUtil::JSEscape(Loc::getMessage('SHEER_WEBP_STAT_SAVED_VOLUME')); ?>';
        if (originalSize > 0) {
            html += ': ' + fmtBytes(saved) + ' <?= CUtil::JSEscape(Loc::getMessage('SHEER_WEBP_STAT_OF')); ?> ' + fmtBytes(originalSize);
        }
        html += '</div><div class="sheer-webp-bar sheer-webp-bar-save"><span style="width:' + Math.min(100, savedPercent) + '%"></span></div></div></div>';
        if (total === 0) {
            html += '<p class="sheer-webp-empty"><?= CUtil::JSEscape(Loc::getMessage('SHEER_WEBP_STAT_EMPTY')); ?></p>';
        }
        html += '<div class="sheer-webp-meta"><b>Статус:</b> ' + phaseLabel(data.phase);
        html += ' · <?= CUtil::JSEscape(Loc::getMessage('SHEER_WEBP_STAT_PENDING')); ?>: <b>' + (data.pending || 0) + '</b>';
        html += ' · <?= CUtil::JSEscape(Loc::getMessage('SHEER_WEBP_STAT_SKIP')); ?>: ' + (data.skipped_larger || 0);
        html += ' · <?= CUtil::JSEscape(Loc::getMessage('SHEER_WEBP_STAT_ERROR')); ?>: ' + (data.error || 0);
        if (webpSize > 0) {
            html += ' · WebP: ' + fmtBytes(webpSize);
        }
        if (data.queue_dirs) {
            html += ' · каталогов в очереди: ' + data.queue_dirs;
        }
        var m = data.mode;
        if (m) {
            html += '<br><b>Фон (cron):</b> ' + (m.paused
                ? '<span style="color:#c00">пауза</span>'
                : (m.night ? 'ночной режим' : 'дневной режим') + ' · ' + m.per_minute + ' картинок в минуту'
                    + (m.warm_night_only ? ' · прогрев только ночью (' + m.night_from + '–' + m.night_to + ' ч)' : ''));
            var pb = document.getElementById('sheer-webp-pause');
            if (pb) {
                pb.value = m.paused ? '<?= CUtil::JSEscape(Loc::getMessage('SHEER_WEBP_RESUME')); ?>' : '<?= CUtil::JSEscape(Loc::getMessage('SHEER_WEBP_PAUSE')); ?>';
                pb.setAttribute('data-paused', m.paused ? 'Y' : 'N');
            }
        }
        var w = data.warm || {};
        if (w.total > 0) {
            var wl = { run: 'идёт', done: 'завершён', idle: 'остановлен' };
            html += '<br><b>Прогрев по sitemap:</b> ' + (wl[w.state] || w.state)
                + ' · ' + fmtNum(w.pos) + ' из ' + fmtNum(w.total) + ' страниц'
                + (w.errors > 0 ? ' · не открылось: ' + w.errors : '');
        }
        html += '</div>';
        var nodes = document.querySelectorAll('.sheer-webp-stats');
        for (var i = 0; i < nodes.length; i++) {
            nodes[i].innerHTML = html;
        }
    }

    function loopChunk() {
        if (!running) return;
        post('chunk', function (data) {
            renderStats(data);
            if (data.phase && data.phase !== 'idle') {
                setTimeout(loopChunk, 350);
            } else {
                running = false;
                setStatus('Готово');
            }
        });
    }

    var rolledBack = 0;

    function loopRollback() {
        if (!rolling) return;
        post('rollback_chunk', function (data) {
            renderStats(data);
            if (data.deleted > 0) {
                rolledBack += data.deleted;
            }
            if (!data.done) {
                setStatus(data.busy
                    ? 'Откат… ждём, пока агент закончит текущую порцию. Обработано записей: ' + rolledBack
                    : 'Откат… обработано записей: ' + rolledBack);
                setTimeout(loopRollback, data.busy ? 1000 : 250);
            } else {
                rolling = false;
                var rb = document.getElementById('sheer-webp-rollback');
                if (rb) {
                    rb.disabled = false;
                }
                setStatus('Откат завершён: обработано записей ' + rolledBack
                    + ', WebP-копии модуля удалены, оригиналы на месте. Модуль выключен — включите его на вкладке «Настройки», если нужно конвертировать заново.');
                var enabledBox = document.querySelector('input[name="enabled"]');
                if (enabledBox) {
                    enabledBox.checked = false;
                }
            }
        });
    }

    var startBtn = document.getElementById('sheer-webp-start');
    var stopBtn = document.getElementById('sheer-webp-stop');
    var rollbackBtn = document.getElementById('sheer-webp-rollback');
    var clearBtn = document.getElementById('sheer-webp-clear');
    var warmBtn = document.getElementById('sheer-webp-warm');
    var warmStopBtn = document.getElementById('sheer-webp-warm-stop');
    var warmTimer = null;

    // Пока идёт прогрев, обновляем цифры раз в 15 с (работу делает cron, не страница).
    function pollWarm(data) {
        if (warmTimer) {
            clearTimeout(warmTimer);
            warmTimer = null;
        }
        if (data && data.warm && data.warm.state === 'run') {
            warmTimer = setTimeout(function () {
                if (running || rolling) {
                    pollWarm(data);
                    return;
                }
                post('status', function (d) {
                    renderStats(d);
                    pollWarm(d);
                }, function () {
                    // Сбой сети (сон компьютера, обрыв) — молча повторяем, работа идёт в cron.
                    pollWarm(data);
                });
            }, 15000);
        }
    }
    pollWarm({ warm: <?= CUtil::PhpToJSObject(\Sheer\Webp\Warmer::state()); ?> });

    var pauseBtn = document.getElementById('sheer-webp-pause');
    if (pauseBtn) {
        pauseBtn.setAttribute('data-paused', '<?= \Sheer\Webp\Config::isPaused() ? 'Y' : 'N'; ?>');
        pauseBtn.onclick = function () {
            var paused = pauseBtn.getAttribute('data-paused') === 'Y';
            post(paused ? 'resume' : 'pause', function (data) {
                renderStats(data);
                setStatus(paused
                    ? 'Фоновая работа продолжена.'
                    : 'Пауза: cron не кодирует и не прогревает. Очередь копится, сайт работает как обычно.');
            });
        };
    }

    if (warmBtn) {
        warmBtn.onclick = function () {
            if (!confirm('Обойти страницы из sitemap.xml? Работает в фоне через cron, страницу можно закрыть.')) return;
            warmBtn.disabled = true;
            setStatus('Собираю список страниц из sitemap…');
            post('warm_start', function (data) {
                warmBtn.disabled = false;
                renderStats(data);
                setStatus(data.warm_error
                    ? 'Прогрев не запущен: ' + data.warm_error
                    : (data.warm.pos > 0
                        ? 'Прогрев продолжен с ' + fmtNum(data.warm.pos) + ' из ' + fmtNum(data.warm.total) + ' страниц.'
                        : 'Прогрев запущен: ' + fmtNum(data.warm.total) + ' страниц.')
                      + ' Идёт в фоне через cron.');
                pollWarm(data);
            });
        };
    }
    if (warmStopBtn) {
        warmStopBtn.onclick = function () {
            post('warm_stop', function (data) {
                renderStats(data);
                setStatus('Прогрев остановлен.');
                pollWarm(data);
            });
        };
    }
    var htaccessBtn = document.getElementById('sheer-webp-htaccess');

    if (startBtn) {
        startBtn.onclick = function () {
            running = true;
            setStatus('Работает…');
            post('start', function (data) {
                renderStats(data);
                loopChunk();
            });
        };
    }
    if (stopBtn) {
        stopBtn.onclick = function () {
            running = false;
            post('stop', function (data) {
                renderStats(data);
                setStatus('Остановлено');
            });
        };
    }
    if (clearBtn) {
        clearBtn.onclick = function () {
            if (!confirm('Очистить очередь? Сканирование остановится, ожидающие записи удалятся. Готовые WebP и оригиналы не трогаются.')) return;
            running = false;
            clearBtn.disabled = true;
            setStatus('Очистка очереди…');
            post('clear_queue', function (data) {
                clearBtn.disabled = false;
                renderStats(data);
                setStatus(data.busy
                    ? 'Сейчас работает агент — повторите через минуту.'
                    : 'Очередь очищена: удалено записей ' + (data.removed || 0) + '.');
            });
        };
    }
    if (rollbackBtn) {
        rollbackBtn.onclick = function () {
            if (!confirm('Удалить все WebP-копии, созданные модулем? Модуль будет выключен, оригиналы останутся.')) return;
            rolling = true;
            running = false;
            rolledBack = 0;
            rollbackBtn.disabled = true;
            setStatus('Откат…');
            loopRollback();
        };
    }
    if (htaccessBtn) {
        htaccessBtn.onclick = function () {
            post('install_htaccess', function (data) {
                alert(data.ok ? 'Правила записаны в /upload/.htaccess' : 'Не удалось записать .htaccess');
            });
        };
    }
})();
</script>
