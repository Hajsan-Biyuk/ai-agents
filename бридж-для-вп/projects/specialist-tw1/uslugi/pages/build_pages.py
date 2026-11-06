"""Группы ACF «Страница» и «Страница 404» + одноразовый установщик для внутренних страниц."""
import base64, json, os, sys

D = '/private/tmp/claude-501/-Users-asanhajretdinov--claude-spares-spare-EXeJV3/d34a3cee-986b-40a5-a2fd-6976eb8b44ac/scratchpad/pg'
OUT = D + '/installer_pages'
os.makedirs(OUT, exist_ok=True)
for f in os.listdir(OUT):
    os.remove(os.path.join(OUT, f))
TOKEN = sys.argv[1]
data = json.load(open(D + '/pages_final.json'))
PAGE_IDS = [p['id'] for p in data['pages']]


def fld(key, name, label, ftype, **kw):
    f = {'key': 'field_pg_' + key, 'label': label, 'name': name, 'type': ftype}
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


def tab(key, label):
    return {'key': 'field_pg_tab_' + key, 'label': label, 'name': '', 'type': 'tab', 'placement': 'top', 'endpoint': 0}


def layout(key, label, subs):
    return {'key': 'layout_pg_' + key, 'name': key, 'label': label, 'display': 'block', 'sub_fields': subs, 'min': '', 'max': ''}


S = 'sec__'
sections = fld('sections', 'pg_sections', 'Блоки страницы', 'flexible_content',
               instructions='Блоки идут на странице сверху вниз. Новый «Заголовок раздела» начинает новый раздел страницы.',
               button_label='Добавить блок', min='', max='', layouts=[
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
        layout('facts', 'Реквизиты (название — значение)', [
            fld(S + 'facts__rows', 'rows', 'Строки', 'repeater', layout='table', button_label='Добавить строку', sub_fields=[
                fld(S + 'facts__rows__label', 'label', 'Название', 'text'),
                fld(S + 'facts__rows__value', 'value', 'Значение', 'textarea', rows=2),
            ]),
        ]),
        layout('images', 'Изображения', [
            fld(S + 'images__variant', 'variant', 'Вид', 'select', choices={'documents': 'Сетка (документы, свидетельства)', 'slider': 'Лента с прокруткой', 'gallery': 'Фотогалерея'}, default_value='documents'),
            fld(S + 'images__images', 'images', 'Изображения', 'repeater', layout='table', button_label='Добавить изображение', sub_fields=[
                fld(S + 'images__images__image', 'image', 'Изображение', 'image'),
                fld(S + 'images__images__link', 'link', 'Ссылка (если картинка ведёт на другой сайт)', 'url'),
            ]),
        ]),
        layout('embed', 'Видео или вставка', [
            fld(S + 'embed__code', 'code', 'Код вставки (iframe)', 'textarea', rows=4),
        ]),
        layout('button', 'Кнопка', [
            fld(S + 'button__label', 'label', 'Текст кнопки', 'text'),
            fld(S + 'button__url', 'url', 'Ссылка', 'text'),
        ]),
    ])

group_pages = {
    'key': 'group_pg_inner', 'title': 'Страница (внутренняя)',
    'fields': [
        tab('head', 'Заголовок'),
        fld('h1_before', 'pg_h1_before', 'Заголовок H1: текст до выделенной части', 'text'),
        fld('h1_accent', 'pg_h1_accent', 'Заголовок H1: выделенная цветом часть', 'text'),
        fld('h1_after', 'pg_h1_after', 'Заголовок H1: текст после', 'text'),
        fld('lead', 'pg_lead', 'Подзаголовок', 'wysiwyg'),
        tab('view', 'Вид страницы'),
        fld('style', 'pg_style', 'Вид', 'select', choices={'sections': 'Разделы на разном фоне', 'document': 'Документ (узкая колонка)', 'notice': 'Сообщение по центру'}, default_value='sections'),
        fld('show_consult', 'pg_show_consult', 'Показывать форму консультации', 'true_false'),
        fld('show_contacts', 'pg_show_contacts', 'Показывать карты офисов', 'true_false'),
        tab('body', 'Содержимое'),
        sections,
    ],
    'location': [[{'param': 'page', 'operator': '==', 'value': str(i)}] for i in PAGE_IDS],
    'menu_order': 0, 'position': 'normal', 'style': 'default', 'label_placement': 'top', 'instruction_placement': 'label',
    'hide_on_screen': ['the_content'], 'active': True, 'show_in_rest': 0,
    'description': 'Внутренние страницы в новом дизайне: О компании, Контакты, Фотогалерея, Наша техника, Сведения, политика, согласие, Спасибо. Шаблон page-inner.php.',
}
group_404 = {
    'key': 'group_pg_404', 'title': 'Страница 404',
    'fields': [
        fld('err404_title', 'err404_title', 'Заголовок', 'text'),
        fld('err404_text', 'err404_text', 'Текст', 'textarea'),
        fld('err404_btn', 'err404_btn', 'Текст кнопки', 'text'),
    ],
    'location': [[{'param': 'options_page', 'operator': '==', 'value': 'hp-header-footer'}]],
    'menu_order': 5, 'position': 'normal', 'style': 'default', 'label_placement': 'top', 'instruction_placement': 'label',
    'hide_on_screen': [], 'active': True, 'show_in_rest': 0,
    'description': 'Страница «Не найдено» (404.php). Поля на странице «Шапка и подвал».',
}

files = data['files']
packs, cur, size = [], {}, 0
for name in files:
    b64 = base64.b64encode(open(D + '/media_pages/' + name, 'rb').read()).decode()
    if cur and size + len(b64) > 360000:
        packs.append(cur)
        cur, size = {}, 0
    cur[name] = b64
    size += len(b64)
if cur:
    packs.append(cur)
for i, pack in enumerate(packs):
    body = ',\n'.join("%s => '%s'" % (json.dumps(k), v) for k, v in pack.items())
    open('%s/hp-pg-media-%02d.php' % (OUT, i), 'w').write("<?php\nif (!defined('ABSPATH')) { exit; }\nreturn array(\n%s\n);\n" % body)

payload = {'pages': data['pages'], 'err404': data['err404'], 'groups': [group_pages, group_404], 'packs': len(packs)}
nowdoc = json.dumps(payload, ensure_ascii=False)
assert 'PGJSON' not in nowdoc
open(OUT + '/hp-pg-data.php', 'w').write("<?php\nif (!defined('ABSPATH')) { exit; }\nreturn json_decode(<<<'PGJSON'\n%s\nPGJSON\n, true);\n" % nowdoc)

installer = r"""<?php
// Одноразовый установщик внутренних страниц. Заменяется заглушкой сразу после запуска.
if (!isset($_GET['t']) || !hash_equals('__TOKEN__', (string) $_GET['t'])) { http_response_code(404); exit; }
require_once dirname(__DIR__, 3) . '/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/image.php';
header('Content-Type: application/json; charset=utf-8');
@set_time_limit(300);
$d = include __DIR__ . '/hp-pg-data.php';
$step = isset($_GET['step']) ? $_GET['step'] : 'check';
$out = array('step' => $step);

if ($step === 'group') {
    foreach ($d['groups'] as $g) {
        $ex = acf_get_field_group($g['key']);
        if ($ex) { $g['ID'] = $ex['ID']; }
        $saved = acf_import_field_group($g);
        $out['groups'][$g['key']] = array('ID' => $saved['ID'], 'fields' => count(acf_get_fields($saved['ID'])));
    }
}

if ($step === 'media') {
    $ids = get_option('pg_media_ids', array());
    $i = (int) (isset($_GET['pack']) ? $_GET['pack'] : 0);
    $pack = include __DIR__ . sprintf('/hp-pg-media-%02d.php', $i);
    foreach ($pack as $name => $b64) {
        if (!empty($ids[$name]) && get_post($ids[$name])) { continue; }
        $up = wp_upload_bits($name, null, base64_decode($b64));
        if (!empty($up['error'])) { $out['error'][] = $name . ': ' . $up['error']; continue; }
        $id = wp_insert_attachment(array('post_mime_type' => 'image/jpeg', 'post_title' => preg_replace('/\.jpg$/', '', $name), 'post_status' => 'inherit'), $up['file']);
        wp_update_attachment_metadata($id, wp_generate_attachment_metadata($id, $up['file']));
        $ids[$name] = $id;
    }
    update_option('pg_media_ids', $ids, false);
    $out['media_total'] = count($ids);
}

$ids = get_option('pg_media_ids', array());
$resolve = function ($v) use ($ids) {
    return (is_string($v) && strpos($v, 'file:') === 0) ? (isset($ids[substr($v, 5)]) ? (int) $ids[substr($v, 5)] : null) : $v;
};

if ($step === 'fill') {
    $alts = array();
    foreach ($d['pages'] as $p) {
        $id = $p['id'];
        update_post_meta($id, '_wp_page_template', 'page-inner.php');
        update_field('field_pg_h1_before', $p['h1']['before'], $id);
        update_field('field_pg_h1_accent', $p['h1']['accent'], $id);
        update_field('field_pg_h1_after', $p['h1']['after'], $id);
        update_field('field_pg_lead', '', $id);
        update_field('field_pg_style', $p['style'], $id);
        update_field('field_pg_show_consult', (int) $p['consult'], $id);
        update_field('field_pg_show_contacts', (int) $p['contacts'], $id);
        $rows = array();
        foreach ($p['sections'] as $l) {
            if ($l['acf_fc_layout'] === 'images') {
                foreach ($l['images'] as $k => $im) {
                    $aid = $resolve($im['image']);
                    $l['images'][$k]['image'] = $aid;
                    if ($aid && !empty($im['alt'])) { $alts[$aid] = $im['alt']; }
                    unset($l['images'][$k]['alt']);
                }
            }
            $rows[] = $l;
        }
        update_field('field_pg_sections', $rows, $id);
        $out['filled'][$id] = count($rows);
    }
    foreach ($alts as $aid => $alt) { update_post_meta($aid, '_wp_attachment_image_alt', $alt); }
    foreach ($d['err404'] as $name => $value) { update_field('field_pg_' . $name, $value, 'option'); }
}

$out['state'] = array(
    'groups' => array_map(function ($k) { $g = acf_get_field_group($k); return $g ? $g['ID'] : false; }, array('group_pg_inner', 'group_pg_404')),
    'media' => count($ids),
    'templates' => array_map(function ($p) { return get_post_meta($p['id'], '_wp_page_template', true); }, $d['pages']),
);
echo wp_json_encode($out, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
"""
open(OUT + '/hp-pg-install.php', 'w').write(installer.replace('__TOKEN__', TOKEN))
json.dump({'groups': [group_pages, group_404]}, open(D + '/pg_schema.json', 'w'), ensure_ascii=False, indent=1)
print('packs', len(packs), 'data kb', os.path.getsize(OUT + '/hp-pg-data.php') // 1024)
