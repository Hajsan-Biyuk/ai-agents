"""Собирает описание типа записи «Курсы и услуги», групп полей ACF и одноразовый установщик."""
import base64, json, os, sys

D = '/private/tmp/claude-501/-Users-asanhajretdinov--claude-spares-spare-EXeJV3/d34a3cee-986b-40a5-a2fd-6976eb8b44ac/scratchpad/pg'
OUT = D + '/installer'
os.makedirs(OUT, exist_ok=True)
for f in os.listdir(OUT):
    os.remove(os.path.join(OUT, f))
TOKEN = sys.argv[1]
LIST_PAGE_ID = 70
data = json.load(open(D + '/final.json'))


def fld(key, name, label, ftype, **kw):
    f = {'key': 'field_ks_' + key, 'label': label, 'name': name, 'type': ftype}
    if ftype == 'textarea':
        f.update({'rows': 3, 'new_lines': ''})
    if ftype == 'wysiwyg':
        f.update({'tabs': 'all', 'toolbar': 'basic', 'media_upload': 0, 'delay': 1})
    if ftype == 'image':
        f.update({'return_format': 'id', 'preview_size': 'medium', 'library': 'all'})
    if ftype == 'select':
        f.update({'allow_null': 0, 'multiple': 0, 'ui': 0, 'return_format': 'value'})
    if ftype == 'true_false':
        f.update({'ui': 1, 'default_value': 0})
    f.update(kw)
    return f


def tab(label):
    return {'key': 'field_ks_tab_' + str(abs(hash(label)) % 10 ** 6), 'label': label, 'name': '', 'type': 'tab', 'placement': 'top', 'endpoint': 0}


def layout(key, label, subs, display='block'):
    return {'key': 'layout_ks_' + key, 'name': key, 'label': label, 'display': display, 'sub_fields': subs, 'min': '', 'max': ''}


S = 'sections__'
sections = fld('sections', 'ks_sections', 'Блоки страницы', 'flexible_content',
               instructions='Блоки идут на странице сверху вниз. Новый «Заголовок» начинает новый раздел страницы.',
               button_label='Добавить блок', min='', max='',
               layouts=[
                   layout('heading', 'Заголовок раздела', [
                       fld(S + 'heading__level', 'level', 'Размер', 'select', choices={'2': 'Заголовок раздела', '3': 'Подзаголовок', '4': 'Малый заголовок'}, default_value='2'),
                       fld(S + 'heading__before', 'before', 'Текст до выделенной части', 'text'),
                       fld(S + 'heading__accent', 'accent', 'Выделенная цветом часть', 'text'),
                       fld(S + 'heading__after', 'after', 'Текст после выделенной части', 'text'),
                   ]),
                   layout('text', 'Текст', [
                       fld(S + 'text__content', 'content', 'Текст', 'wysiwyg'),
                       fld(S + 'text__emphasis', 'emphasis', 'Выделенный абзац (плашка)', 'true_false'),
                   ]),
                   layout('list', 'Список', [
                       fld(S + 'list__style', 'style', 'Вид списка', 'select', choices={'marker': 'С маркерами', 'check': 'С галочками', 'warning': 'С предупреждением', 'numbered': 'Нумерованный'}, default_value='marker'),
                       fld(S + 'list__items', 'items', 'Пункты', 'repeater', layout='table', button_label='Добавить пункт', sub_fields=[
                           fld(S + 'list__items__text', 'text', 'Пункт', 'textarea', rows=2),
                       ]),
                   ]),
                   layout('cards', 'Карточки', [
                       fld(S + 'cards__variant', 'variant', 'Вид', 'select', choices={'cards': 'Обычные карточки', 'price': 'Карточки цен'}, default_value='cards'),
                       fld(S + 'cards__cards', 'cards', 'Карточки', 'repeater', layout='block', button_label='Добавить карточку', sub_fields=[
                           fld(S + 'cards__cards__title', 'title', 'Заголовок', 'text'),
                           fld(S + 'cards__cards__text', 'text', 'Текст', 'wysiwyg'),
                       ]),
                   ]),
                   layout('steps', 'Этапы', [
                       fld(S + 'steps__steps', 'steps', 'Этапы по порядку', 'repeater', layout='table', button_label='Добавить этап', sub_fields=[
                           fld(S + 'steps__steps__text', 'text', 'Этап', 'text'),
                       ]),
                   ]),
                   layout('images', 'Изображения', [
                       fld(S + 'images__variant', 'variant', 'Вид', 'select', choices={'documents': 'Сетка (документы, свидетельства)', 'slider': 'Лента с прокруткой'}, default_value='documents'),
                       fld(S + 'images__images', 'images', 'Изображения', 'repeater', layout='table', button_label='Добавить изображение', sub_fields=[
                           fld(S + 'images__images__image', 'image', 'Изображение', 'image'),
                           fld(S + 'images__images__link', 'link', 'Ссылка (если картинка ведёт на другой сайт)', 'url'),
                       ]),
                   ]),
                   layout('embed', 'Видео или вставка', [
                       fld(S + 'embed__code', 'code', 'Код вставки (iframe)', 'textarea', rows=4),
                   ]),
               ])

group_service = {
    'key': 'group_ks_service', 'title': 'Курс или услуга',
    'fields': [
        tab('Баннер'),
        fld('h1_before', 'ks_h1_before', 'Заголовок H1: текст до выделенной части', 'text'),
        fld('h1_accent', 'ks_h1_accent', 'Заголовок H1: выделенная цветом часть', 'text'),
        fld('h1_after', 'ks_h1_after', 'Заголовок H1: текст после', 'text'),
        fld('lead', 'ks_lead', 'Подзаголовок', 'wysiwyg'),
        fld('price', 'ks_price', 'Стоимость в баннере', 'text', instructions='Например: от 7 000 ₽. Пусто — карточка цены в баннере скрыта.'),
        fld('price_note', 'ks_price_note', 'Подпись к стоимости', 'text', placeholder='Стоимость обучения'),
        fld('hero_img', 'ks_hero_img', 'Фото в баннере', 'image', instructions='Необязательно. Если не задано, баннер без фото.'),
        tab('Для перечня услуг'),
        fld('icon', 'ks_icon', 'Значок для плитки', 'image', instructions='PNG или SVG на прозрачном фоне, лучше 160×160 px.'),
        fld('short', 'ks_short', 'Короткое описание для плитки', 'text'),
        tab('Содержимое'),
        sections,
    ],
    'location': [[{'param': 'post_type', 'operator': '==', 'value': 'uslugi'}]],
    'menu_order': 0, 'position': 'normal', 'style': 'default', 'label_placement': 'top', 'instruction_placement': 'label',
    'hide_on_screen': [], 'active': True, 'show_in_rest': 0,
    'description': 'Тип записи «Курсы и услуги». Шаблон single-uslugi.php только читает эти поля.',
}
group_list = {
    'key': 'group_ks_list', 'title': 'Перечень курсов и услуг',
    'fields': [
        fld('list_title', 'ks_list_title', 'Заголовок страницы', 'text'),
        fld('list_intro', 'ks_list_intro', 'Вводный текст', 'textarea'),
        fld('list_extra', 'ks_list_extra', 'Дополнительные плитки (страницы вне типа «Курсы и услуги»)', 'repeater', layout='block', button_label='Добавить плитку', sub_fields=[
            fld('list_extra__title', 'title', 'Название', 'text'),
            fld('list_extra__text', 'text', 'Короткое описание', 'text'),
            fld('list_extra__url', 'url', 'Ссылка', 'text'),
            fld('list_extra__icon', 'icon', 'Значок', 'image'),
            fld('list_extra__direction', 'direction', 'Направление', 'taxonomy', taxonomy='napravlenie', field_type='select', allow_null=0, add_term=0, save_terms=0, load_terms=0, return_format='id', multiple=0),
        ]),
    ],
    'location': [[{'param': 'page', 'operator': '==', 'value': str(LIST_PAGE_ID)}]],
    'menu_order': 0, 'position': 'normal', 'style': 'default', 'label_placement': 'top', 'instruction_placement': 'label',
    'hide_on_screen': ['the_content'], 'active': True, 'show_in_rest': 0,
    'description': 'Страница-перечень «Перечень курсов и услуг». Плитки выводятся из записей типа «Курсы и услуги».',
}

post_type = {
    'key': 'post_type_ks_uslugi', 'title': 'Курсы и услуги', 'menu_order': 0, 'active': True,
    'post_type': 'uslugi', 'advanced_configuration': True, 'import_source': '', 'import_date': '',
    'labels': {
        'name': 'Курсы и услуги', 'singular_name': 'Курс или услуга', 'menu_name': 'Курсы и услуги', 'all_items': 'Все курсы и услуги',
        'add_new': 'Добавить', 'add_new_item': 'Добавить курс или услугу', 'edit_item': 'Изменить курс или услугу', 'new_item': 'Новый курс или услуга',
        'view_item': 'Посмотреть', 'view_items': 'Посмотреть курсы и услуги', 'search_items': 'Найти курс или услугу', 'not_found': 'Ничего не найдено',
        'not_found_in_trash': 'В корзине пусто', 'archives': 'Курсы и услуги', 'attributes': 'Порядок', 'name_admin_bar': 'Курс или услуга',
        'item_published': 'Опубликовано', 'item_updated': 'Сохранено', 'filter_items_list': 'Фильтр списка', 'items_list': 'Список курсов и услуг',
        'items_list_navigation': 'Навигация по списку',
    },
    'description': 'Курсы обучения и услуги учебного центра', 'public': True, 'hierarchical': False, 'exclude_from_search': False,
    'publicly_queryable': True, 'show_ui': True, 'show_in_menu': True, 'menu_position': 21,
    'menu_icon': {'type': 'dashicons', 'value': 'dashicons-welcome-learn-more'}, 'show_in_admin_bar': True, 'show_in_nav_menus': True,
    'show_in_rest': False, 'has_archive': False,
    'rewrite': {'permalink_rewrite': 'custom_permalink', 'slug': 'uslugi', 'feeds': 0, 'pages': 0, 'with_front': 0},
    'query_var': 'post_type', 'supports': ['title', 'excerpt', 'thumbnail', 'revisions', 'page-attributes'],
    'taxonomies': ['napravlenie'], 'enter_title_here': 'Название курса или услуги',
    'delete_with_user': False, 'can_export': True, 'capability_type': 'post',
}
taxonomy = {
    'key': 'taxonomy_ks_napravlenie', 'title': 'Направления', 'menu_order': 0, 'active': True,
    'taxonomy': 'napravlenie', 'object_type': ['uslugi'], 'advanced_configuration': True, 'import_source': '', 'import_date': '',
    'labels': {'name': 'Направления', 'singular_name': 'Направление', 'menu_name': 'Направления', 'all_items': 'Все направления', 'edit_item': 'Изменить направление',
               'view_item': 'Посмотреть', 'update_item': 'Обновить', 'add_new_item': 'Добавить направление', 'new_item_name': 'Название направления',
               'search_items': 'Найти направление', 'not_found': 'Ничего не найдено'},
    'description': 'Группы курсов и услуг на странице-перечне', 'public': False, 'publicly_queryable': False, 'hierarchical': True, 'show_ui': True,
    'show_in_menu': True, 'show_in_nav_menus': False, 'show_in_rest': False, 'show_admin_column': True, 'show_tagcloud': False, 'show_in_quick_edit': True,
    'rewrite': {'permalink_rewrite': 'no_permalink'}, 'query_var': 'taxonomy', 'sort': False,
}

# ----- данные и медиа -----
services = data['services']
files = data['files']
packs, cur, cur_size = [], {}, 0
for name in files:
    raw = open(D + '/media/' + name, 'rb').read()
    b64 = base64.b64encode(raw).decode()
    if cur and cur_size + len(b64) > 360000:
        packs.append(cur)
        cur, cur_size = {}, 0
    cur[name] = b64
    cur_size += len(b64)
if cur:
    packs.append(cur)
for i, pack in enumerate(packs):
    body = ',\n'.join("%s => '%s'" % (json.dumps(k), v) for k, v in pack.items())
    open('%s/hp-ks-media-%02d.php' % (OUT, i), 'w').write("<?php\nif (!defined('ABSPATH')) { exit; }\nreturn array(\n%s\n);\n" % body)

payload = {'terms': data['terms'], 'services': services, 'groups': [group_service, group_list], 'post_type': post_type, 'taxonomy': taxonomy, 'packs': len(packs), 'list_page': LIST_PAGE_ID,
           'list_fields': {
               'ks_list_title': 'Чему мы можем вас обучить?',
               'ks_list_intro': 'Курсы, обучение рабочим профессиям, спецтехнике и охране труда в Крыму. Выберите направление и узнайте программу, документы и стоимость.',
               'extra': [{'title': 'Тракторист-машинист', 'text': 'Удостоверение тракториста: обучение и экзамен в одном месте', 'url': '/tractor-driver/', 'icon': 'file:icon-tractor.png', 'direction': 'Спецтехника и самоходные машины'}],
           }}
nowdoc = json.dumps(payload, ensure_ascii=False)
assert 'KSJSON' not in nowdoc
open(OUT + '/hp-ks-data.php', 'w').write("<?php\nif (!defined('ABSPATH')) { exit; }\nreturn json_decode(<<<'KSJSON'\n%s\nKSJSON\n, true);\n" % nowdoc)

installer = r"""<?php
// Одноразовый установщик «Курсы и услуги». Удаляется (заменяется заглушкой) сразу после запуска.
if (!isset($_GET['t']) || !hash_equals('__TOKEN__', (string) $_GET['t'])) { http_response_code(404); exit; }
require_once dirname(__DIR__, 3) . '/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/image.php';
header('Content-Type: application/json; charset=utf-8');
@set_time_limit(300);
$d = include __DIR__ . '/hp-ks-data.php';
$step = isset($_GET['step']) ? $_GET['step'] : 'check';
$out = array('step' => $step);

if ($step === 'types') {
    $out['post_type'] = acf_update_post_type($d['post_type']) ? 'ok' : 'fail';
    $out['taxonomy'] = acf_update_taxonomy($d['taxonomy']) ? 'ok' : 'fail';
    $term_ids = get_option('ks_term_ids', array());
    foreach ($d['terms'] as $i => $name) {
        if (!empty($term_ids[$name]) && get_term($term_ids[$name], 'napravlenie')) { continue; }
        $t = taxonomy_exists('napravlenie') ? wp_insert_term($name, 'napravlenie', array('slug' => sanitize_title($name))) : null;
        if ($t && !is_wp_error($t)) { $term_ids[$name] = $t['term_id']; }
        elseif ($t && is_wp_error($t) && $t->get_error_data('term_exists')) { $term_ids[$name] = (int) $t->get_error_data('term_exists'); }
    }
    update_option('ks_term_ids', $term_ids, false);
    $out['terms'] = $term_ids;
    $out['registered_now'] = array(post_type_exists('uslugi'), taxonomy_exists('napravlenie'));
}

if ($step === 'group') {
    foreach ($d['groups'] as $g) {
        $ex = acf_get_field_group($g['key']);
        if ($ex) { $g['ID'] = $ex['ID']; }
        $saved = acf_import_field_group($g);
        $out['groups'][$g['key']] = array('ID' => $saved['ID'], 'fields' => count(acf_get_fields($saved['ID'])));
    }
}

if ($step === 'flush') {
    flush_rewrite_rules(false);
    $out['rules'] = count((array) get_option('rewrite_rules'));
    $out['registered'] = array(post_type_exists('uslugi'), taxonomy_exists('napravlenie'));
}

if ($step === 'media') {
    $ids = get_option('ks_media_ids', array());
    $i = (int) (isset($_GET['pack']) ? $_GET['pack'] : 0);
    $pack = include __DIR__ . sprintf('/hp-ks-media-%02d.php', $i);
    foreach ($pack as $name => $b64) {
        if (!empty($ids[$name]) && get_post($ids[$name])) { continue; }
        $up = wp_upload_bits('ks-' . $name, null, base64_decode($b64));
        if (!empty($up['error'])) { $out['error'][] = $name . ': ' . $up['error']; continue; }
        $mime = preg_match('/\.png$/', $name) ? 'image/png' : 'image/jpeg';
        $title = preg_replace('/\.(png|jpe?g)$/', '', $name);
        $id = wp_insert_attachment(array('post_mime_type' => $mime, 'post_title' => $title, 'post_status' => 'inherit'), $up['file']);
        wp_update_attachment_metadata($id, wp_generate_attachment_metadata($id, $up['file']));
        $ids[$name] = $id;
    }
    update_option('ks_media_ids', $ids, false);
    $out['media_total'] = count($ids);
}

$ids = get_option('ks_media_ids', array());
$term_ids = get_option('ks_term_ids', array());
$resolve_file = function ($v) use ($ids) {
    return (is_string($v) && strpos($v, 'file:') === 0) ? (isset($ids[substr($v, 5)]) ? (int) $ids[substr($v, 5)] : null) : $v;
};

if ($step === 'convert') {
    foreach ($d['services'] as $s) {
        $p = get_post($s['id']);
        if (!$p) { $out['missing'][] = $s['id']; continue; }
        if ($p->post_type === 'page') {
            set_post_type($s['id'], 'uslugi');
        }
        wp_update_post(array('ID' => $s['id'], 'menu_order' => $s['order']));
        if (!empty($term_ids[$s['term']])) { wp_set_object_terms($s['id'], (int) $term_ids[$s['term']], 'napravlenie'); }
        $out['converted'][$s['id']] = get_post_type($s['id']);
    }
}

if ($step === 'fill') {
    $alts = array();
    foreach ($d['services'] as $s) {
        $id = $s['id'];
        update_field('field_ks_h1_before', $s['h1']['before'], $id);
        update_field('field_ks_h1_accent', $s['h1']['accent'], $id);
        update_field('field_ks_h1_after', $s['h1']['after'], $id);
        update_field('field_ks_lead', $s['lead'] !== '' ? '<p>' . $s['lead'] . '</p>' : '', $id);
        update_field('field_ks_price', $s['price'], $id);
        update_field('field_ks_icon', $resolve_file($s['icon']), $id);
        update_field('field_ks_short', $s['short'], $id);
        $rows = array();
        foreach ($s['sections'] as $l) {
            if ($l['acf_fc_layout'] === 'images') {
                foreach ($l['images'] as $k => $im) {
                    $l['images'][$k]['image'] = $resolve_file($im['image']);
                    if (!empty($l['images'][$k]['image']) && !empty($im['alt'])) { $alts[$l['images'][$k]['image']] = $im['alt']; }
                    unset($l['images'][$k]['alt']);
                }
            }
            $rows[] = $l;
        }
        update_field('field_ks_sections', $rows, $id);
        $out['filled'][$id] = count($rows);
    }
    foreach ($alts as $aid => $alt) { update_post_meta($aid, '_wp_attachment_image_alt', $alt); }
    $lp = (int) $d['list_page'];
    update_field('field_ks_list_title', $d['list_fields']['ks_list_title'], $lp);
    update_field('field_ks_list_intro', $d['list_fields']['ks_list_intro'], $lp);
    $extra = array();
    foreach ($d['list_fields']['extra'] as $e) {
        $extra[] = array('title' => $e['title'], 'text' => $e['text'], 'url' => $e['url'], 'icon' => $resolve_file($e['icon']), 'direction' => isset($term_ids[$e['direction']]) ? (int) $term_ids[$e['direction']] : 0);
    }
    update_field('field_ks_list_extra', $extra, $lp);
}

$out['state'] = array(
    'cpt' => post_type_exists('uslugi'), 'tax' => taxonomy_exists('napravlenie'),
    'groups' => array_map(function ($k) { $g = acf_get_field_group($k); return $g ? $g['ID'] : false; }, array('group_ks_service', 'group_ks_list')),
    'media' => count($ids), 'services' => count($d['services']),
    'uslugi_posts' => (int) (wp_count_posts('uslugi')->publish),
);
echo wp_json_encode($out, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
"""
open(OUT + '/hp-ks-install.php', 'w').write(installer.replace('__TOKEN__', TOKEN))
json.dump({'groups': [group_service, group_list], 'post_type': post_type, 'taxonomy': taxonomy}, open(D + '/ks_schema.json', 'w'), ensure_ascii=False, indent=1)
n_fields = sum(1 for f in group_service['fields'] if f['type'] != 'tab')
print('packs', len(packs), 'data kb', os.path.getsize(OUT + '/hp-ks-data.php') // 1024, 'top-level fields in service group', n_fields)
