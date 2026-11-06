<?php
/**
 * Курсы и услуги: общие функции вывода. Поля читаются из групп ACF
 * «Курс или услуга» (тип записи uslugi) и «Перечень курсов и услуг» (страница 70).
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once get_template_directory() . '/hp-core.php';

/** Стили и скрипты вёрстки «hp» для страниц услуг. */
function ks_enqueue() {
    add_action('wp_enqueue_scripts', function () {
        $dir = get_stylesheet_directory() . '/home';
        $uri = get_stylesheet_directory_uri() . '/home';
        wp_enqueue_style('hp-fonts', $uri . '/css/fonts.css', array('main-style'), filemtime($dir . '/css/fonts.css'));
        wp_enqueue_style('hp-main', $uri . '/css/main.css', array('hp-fonts'), filemtime($dir . '/css/main.css'));
        wp_enqueue_style('hp-media', $uri . '/css/media.css', array('hp-main'), filemtime($dir . '/css/media.css'));
        wp_enqueue_style('hp-uslugi', $uri . '/css/uslugi.css', array('hp-media'), filemtime($dir . '/css/uslugi.css'));
        wp_enqueue_script('hp-home', $uri . '/js/common.js', array('jquery-script'), filemtime($dir . '/js/common.js'), true);
    }, 30);
}

/** Значение поля записи. */
function ks_f($key, $post_id = null) {
    if (!function_exists('get_field')) {
        return null;
    }
    return get_field($key, $post_id ?: get_the_ID());
}

/** URL картинки из медиатеки по ID. */
function ks_img($id, $size = 'large') {
    $url = $id ? wp_get_attachment_image_url((int) $id, $size) : '';
    return $url ? $url : '';
}

/** Alt картинки: из медиатеки, иначе запасной текст. */
function ks_alt($id, $fallback = '') {
    $alt = $id ? get_post_meta((int) $id, '_wp_attachment_image_alt', true) : '';
    return $alt ? $alt : $fallback;
}

/** Безопасный HTML из wysiwyg-полей. */
function ks_html($html) {
    return wp_kses_post((string) $html);
}

/** Код вставки (iframe) с разрешёнными атрибутами. */
function ks_embed_html($code) {
    return wp_kses((string) $code, array('iframe' => array(
        'src' => true, 'width' => true, 'height' => true, 'frameborder' => true, 'allowfullscreen' => true,
        'allow' => true, 'title' => true, 'loading' => true, 'referrerpolicy' => true,
    )));
}

/** Заголовок из трёх частей: до, выделенная цветом, после. */
function ks_heading_inner($r) {
    $html = '';
    if (!empty($r['before'])) {
        $html .= esc_html($r['before']) . ' ';
    }
    if (!empty($r['accent'])) {
        $html .= '<span class="ks-accent">' . esc_html($r['accent']) . '</span>';
    }
    if (!empty($r['after'])) {
        $html .= ' ' . esc_html($r['after']);
    }
    return trim($html);
}

/** Блоки страницы → разделы: каждый заголовок крупного размера начинает новый раздел. */
function ks_group_sections($rows) {
    $sections = array();
    $cur = array('head' => null, 'items' => array());
    foreach ((array) $rows as $r) {
        if ($r['acf_fc_layout'] === 'heading' && (int) $r['level'] === 2) {
            if ($cur['head'] !== null || $cur['items']) {
                $sections[] = $cur;
            }
            $cur = array('head' => $r, 'items' => array());
        } else {
            $cur['items'][] = $r;
        }
    }
    if ($cur['head'] !== null || $cur['items']) {
        $sections[] = $cur;
    }
    return $sections;
}

/** Один блок страницы. */
function ks_block($r) {
    switch ($r['acf_fc_layout']) {
        case 'heading':
            $lvl = max(2, min(4, (int) $r['level']));
            if ($lvl === 2) {
                echo '<h2 class="ks-doc-h2">' . ks_heading_inner($r) . '</h2>';
                break;
            }
            printf('<h%1$d class="ks-h%1$d">%2$s</h%1$d>', $lvl, ks_heading_inner($r));
            break;

        case 'facts':
            echo '<dl class="hp-panel ks-facts">';
            foreach ((array) $r['rows'] as $row) {
                echo '<div class="ks-facts__row">';
                if ($row['label'] !== '') {
                    echo '<dt>' . esc_html($row['label']) . '</dt>';
                }
                echo '<dd>' . ks_html($row['value']) . '</dd></div>';
            }
            echo '</dl>';
            break;

        case 'button':
            echo '<div class="ks-button"><a class="hp-btn" href="' . esc_url($r['url']) . '">' . esc_html($r['label']) . '</a></div>';
            break;

        case 'text':
            if (trim(wp_strip_all_tags((string) $r['content'])) === '') {
                break;
            }
            echo '<div class="ks-prose' . (!empty($r['emphasis']) ? ' ks-callout' : '') . '">' . ks_html($r['content']) . '</div>';
            break;

        case 'list':
            $style = in_array($r['style'], array('marker', 'check', 'warning', 'numbered'), true) ? $r['style'] : 'marker';
            $tag = $style === 'numbered' ? 'ol' : 'ul';
            echo '<div class="hp-panel ks-list ks-list--' . esc_attr($style) . '"><' . $tag . '>';
            foreach ((array) $r['items'] as $it) {
                echo '<li>' . ks_html($it['text']) . '</li>';
            }
            echo '</' . $tag . '></div>';
            break;

        case 'cards':
            $price = $r['variant'] === 'price';
            echo '<div class="ks-cards' . ($price ? ' ks-cards--price' : '') . '">';
            foreach ((array) $r['cards'] as $c) {
                echo '<article class="hp-panel ks-card">';
                if (!empty($c['title'])) {
                    echo '<h3>' . esc_html($c['title']) . '</h3>';
                }
                echo '<div class="ks-card__body">' . ks_html($c['text']) . '</div>';
                if ($price) {
                    echo '<a class="hp-btn hp-btn--pale ks-card__btn" href="#consult">Записаться на обучение</a>';
                }
                echo '</article>';
            }
            echo '</div>';
            break;

        case 'steps':
            echo '<ol class="ks-steps">';
            foreach ((array) $r['steps'] as $i => $st) {
                echo '<li class="hp-panel ks-step"><span class="hp-step__num">' . sprintf('%02d', $i + 1) . '</span><p>' . esc_html($st['text']) . '</p></li>';
            }
            echo '</ol>';
            break;

        case 'images':
            $slider = $r['variant'] === 'slider';
            $gallery = $r['variant'] === 'gallery';
            echo '<div class="ks-docs' . ($slider ? ' ks-docs--slider' : '') . ($gallery ? ' ks-docs--gallery' : '') . '">';
            foreach ((array) $r['images'] as $im) {
                $id = $im['image'];
                $src = ks_img($id, 'large');
                if (!$src) {
                    continue;
                }
                $alt = ks_alt($id, get_the_title());
                if (!empty($im['link'])) {
                    echo '<a class="ks-doc ks-doc--link" href="' . esc_url($im['link']) . '" target="_blank" rel="noopener"><img src="' . esc_url($src) . '" alt="' . esc_attr($alt) . '" loading="lazy"></a>';
                } else {
                    echo '<a class="hp-panel ks-doc" href="' . esc_url(ks_img($id, 'full')) . '" data-fancybox="ks-docs-' . (int) get_the_ID() . '"><img src="' . esc_url($src) . '" alt="' . esc_attr($alt) . '" loading="lazy"></a>';
                }
            }
            echo '</div>';
            break;

        case 'embed':
            echo '<div class="ks-embed">' . ks_embed_html($r['code']) . '</div>';
            break;
    }
}

/** Плитка курса или услуги. */
function ks_tile($title, $url, $icon_id, $short = '') {
    $icon = ks_img($icon_id, 'medium');
    ?>
    <a class="hp-panel ks-tile" href="<?php echo esc_url($url); ?>">
        <span class="ks-tile__icon"><?php if ($icon) : ?><img src="<?php echo esc_url($icon); ?>" alt="" width="64" height="64" loading="lazy"><?php endif; ?></span>
        <span class="ks-tile__title"><?php echo esc_html($title); ?></span>
        <?php if ($short) : ?><span class="ks-tile__text"><?php echo esc_html($short); ?></span><?php endif; ?>
        <span class="ks-tile__more">Подробнее <span aria-hidden="true">›</span></span>
    </a>
    <?php
}

/** Все записи типа «Курсы и услуги» в порядке плиток. */
function ks_all_posts() {
    return get_posts(array(
        'post_type' => 'uslugi',
        'post_status' => 'publish',
        'numberposts' => -1,
        'orderby' => array('menu_order' => 'ASC', 'title' => 'ASC'),
    ));
}

/** ID страницы-перечня «Перечень курсов и услуг». */
function ks_list_page_id() {
    return 70;
}

/** Блоки подряд: соседние списки и видео выстраиваются в ряд. $doc — режим «документ» без группировки. */
function ks_blocks($items, $group = false) {
    $n = count($items);
    for ($i = 0; $i < $n; $i++) {
        $r = $items[$i];
        $kind = $r['acf_fc_layout'];
        if ($group && ($kind === 'list' || $kind === 'embed')) {
            $j = $i;
            while ($j < $n && $items[$j]['acf_fc_layout'] === $kind) {
                $j++;
            }
            if ($j - $i > 1) {
                $cols = min(3, $j - $i);
                echo '<div class="ks-row ks-row--' . esc_attr($kind) . '" style="--ks-cols:' . (int) $cols . '">';
                for ($k = $i; $k < $j; $k++) {
                    ks_block($items[$k]);
                }
                echo '</div>';
                $i = $j - 1;
                continue;
            }
        }
        ks_block($r);
    }
}
