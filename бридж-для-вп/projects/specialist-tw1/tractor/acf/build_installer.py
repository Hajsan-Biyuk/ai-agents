"""Собирает одноразовый установщик: группа ACF «Удостоверение тракториста», картинки в медиатеку, значения полей.

Группа импортируется в базу ACF (acf_import_field_group), в теме поля не регистрируются.
"""
import base64, json, os, sys

HERE = os.path.dirname(os.path.abspath(__file__))
sys.path.insert(0, HERE)
from content import CONTENT  # noqa: E402

IMG_DIR = '/Users/asanhajretdinov/Documents/Sites/Cursor-sheer/бридж-для-вп/projects/specialist-tw1/tractor/img/'
OUT = os.path.join(HERE, 'installer')
PAGE_ID = 394
TOKEN = sys.argv[1]

TABS = [
    ('Баннер', ['tr_crumb', 'tr_hero_title', 'tr_hero_lead', 'tr_img_hero', 'tr_hero_alt', 'tr_price_label', 'tr_price_title', 'tr_price_text', 'tr_price_note',
                'tr_btn_1', 'tr_btn_1_url', 'tr_btn_2', 'tr_btn_2_url', 'tr_btn_3', 'tr_btn_3_url']),
    ('Категории', ['tr_cats_title', 'tr_cats_intro', 'tr_cats']),
    ('Скидка СВО', ['tr_svo_badge', 'tr_svo_title', 'tr_svo_lead', 'tr_svo_list', 'tr_svo_btn', 'tr_svo_btn_url', 'tr_svo_img', 'tr_svo_alt']),
    ('Почему мы', ['tr_about_badge', 'tr_about_title', 'tr_about_items']),
    ('Фотогалерея', ['tr_gallery_title', 'tr_gallery']),
    ('Шаги', ['tr_steps_title', 'tr_steps', 'tr_steps_img', 'tr_steps_alt', 'tr_steps_badge']),
    ('Что взять и что получаете', ['tr_take_title', 'tr_take_list', 'tr_take_note', 'tr_get_title', 'tr_get_photos']),
    ('Компаниям', ['tr_comp_badge', 'tr_comp_title', 'tr_comp_lead', 'tr_comp_btn', 'tr_comp_btn_url', 'tr_comp_img', 'tr_comp_alt']),
    ('Вопрос-ответ', ['tr_faq_title', 'tr_faq']),
    ('Консультация', ['tr_consult_title', 'tr_consult_lead', 'tr_consult_form_title', 'tr_consult_placeholder', 'tr_consult_options', 'tr_consult_submit']),
]

LABELS = {
    'tr_crumb': 'Хлебная крошка (название страницы)', 'tr_hero_title': 'Заголовок H1', 'tr_hero_lead': 'Подзаголовок',
    'tr_img_hero': 'Фото баннера', 'tr_hero_alt': 'Alt фото баннера', 'tr_price_label': 'Карточка цены: надпись сверху',
    'tr_price_title': 'Карточка цены: заголовок', 'tr_price_text': 'Карточка цены: текст', 'tr_price_note': 'Карточка цены: примечание',
    'tr_btn_1': 'Кнопка 1', 'tr_btn_1_url': 'Кнопка 1: ссылка', 'tr_btn_2': 'Кнопка 2', 'tr_btn_2_url': 'Кнопка 2: ссылка',
    'tr_btn_3': 'Кнопка 3', 'tr_btn_3_url': 'Кнопка 3: ссылка',
    'tr_cats_title': 'Заголовок', 'tr_cats_intro': 'Пояснение справа', 'tr_cats': 'Категории',
    'tr_svo_badge': 'Метка', 'tr_svo_title': 'Заголовок', 'tr_svo_lead': 'Подзаголовок', 'tr_svo_list': 'Кто может получить скидку',
    'tr_svo_btn': 'Кнопка', 'tr_svo_btn_url': 'Кнопка: ссылка', 'tr_svo_img': 'Фото', 'tr_svo_alt': 'Alt фото',
    'tr_about_badge': 'Метка', 'tr_about_title': 'Заголовок', 'tr_about_items': 'Преимущества',
    'tr_gallery_title': 'Заголовок', 'tr_gallery': 'Фото (первое — большое)',
    'tr_steps_title': 'Заголовок', 'tr_steps': 'Шаги', 'tr_steps_img': 'Фото', 'tr_steps_alt': 'Alt фото', 'tr_steps_badge': 'Плашка на фото',
    'tr_take_title': 'Заголовок «Что взять»', 'tr_take_list': 'Список документов', 'tr_take_note': 'Строка под списком',
    'tr_get_title': 'Заголовок «Что вы получаете»', 'tr_get_photos': 'Фото удостоверения',
    'tr_comp_badge': 'Метка', 'tr_comp_title': 'Заголовок', 'tr_comp_lead': 'Текст', 'tr_comp_btn': 'Кнопка', 'tr_comp_btn_url': 'Кнопка: ссылка',
    'tr_comp_img': 'Фото', 'tr_comp_alt': 'Alt фото',
    'tr_faq_title': 'Заголовок', 'tr_faq': 'Вопросы',
    'tr_consult_title': 'Заголовок', 'tr_consult_lead': 'Текст под заголовком', 'tr_consult_form_title': 'Заголовок формы',
    'tr_consult_placeholder': 'Подсказка в списке', 'tr_consult_options': 'Варианты в списке', 'tr_consult_submit': 'Текст кнопки',
}
IMAGES = {'tr_img_hero', 'tr_svo_img', 'tr_comp_img', 'tr_steps_img'}
GALLERIES = {'tr_gallery', 'tr_get_photos'}
SUBS = {
    'tr_cats': [('letter', 'Буква на значке', 'text'), ('title', 'Название', 'text'), ('text', 'Описание', 'textarea'), ('image', 'Фото', 'image'), ('note', 'Возраст и срок', 'text')],
    'tr_svo_list': [('text', 'Пункт', 'text')],
    'tr_about_items': [('icon', 'Иконка (файл из темы, home/img)', 'text'), ('title', 'Заголовок', 'text'), ('text', 'Текст', 'textarea'), ('note', 'Плашка рядом с иконкой', 'text')],
    'tr_steps': [('num', 'Номер', 'text'), ('title', 'Заголовок', 'text'), ('text', 'Текст', 'textarea')],
    'tr_take_list': [('text', 'Пункт', 'text')],
    'tr_faq': [('question', 'Вопрос', 'text'), ('answer', 'Ответ', 'textarea')],
    'tr_consult_options': [('label', 'Вариант', 'text')],
}
BUTTON_LABEL = {'tr_cats': 'Добавить категорию', 'tr_svo_list': 'Добавить пункт', 'tr_about_items': 'Добавить преимущество', 'tr_steps': 'Добавить шаг',
                'tr_take_list': 'Добавить пункт', 'tr_faq': 'Добавить вопрос', 'tr_consult_options': 'Добавить вариант'}


def text_type(name, value):
    if isinstance(value, str) and ('\n' in value or len(value) > 90) or name in ('tr_hero_lead', 'tr_svo_lead', 'tr_comp_lead', 'tr_cats_intro', 'tr_consult_lead'):
        return 'textarea'
    return 'text'


def base(key, name, label, ftype):
    f = {'key': key, 'label': label, 'name': name, 'type': ftype}
    if ftype == 'textarea':
        f.update({'rows': 3, 'new_lines': ''})
    if ftype == 'image':
        f.update({'return_format': 'id', 'preview_size': 'medium', 'library': 'all'})
    if ftype == 'gallery':
        f.update({'return_format': 'id', 'preview_size': 'medium', 'library': 'all', 'insert': 'append'})
    if name.endswith('_url'):
        f['instructions'] = 'Адрес страницы или якорь блока: #consult — форма, #categories — категории, #svo — скидка СВО.'
    return f


fields = []
for tab, names in TABS:
    fields.append({'key': 'field_tr_tab_%d' % len(fields), 'label': tab, 'name': '', 'type': 'tab', 'placement': 'top', 'endpoint': 0})
    for name in names:
        key = 'field_' + name
        label = LABELS[name]
        if name in IMAGES:
            fields.append(base(key, name, label, 'image'))
        elif name in GALLERIES:
            fields.append(base(key, name, label, 'gallery'))
        elif name in SUBS:
            f = base(key, name, label, 'repeater')
            f.update({'layout': 'block', 'button_label': BUTTON_LABEL[name], 'sub_fields': []})
            for sub, slabel, stype in SUBS[name]:
                f['sub_fields'].append(base('%s__%s' % (key, sub), sub, slabel, stype))
            fields.append(f)
        else:
            fields.append(base(key, name, label, text_type(name, CONTENT[name])))
missing = set(CONTENT) - {f['name'] for f in fields}
assert not missing, missing

group = {
    'key': 'group_tr_tractor',
    'title': 'Удостоверение тракториста',
    'fields': fields,
    'location': [[{'param': 'page', 'operator': '==', 'value': str(PAGE_ID)}]],
    'menu_order': 0,
    'position': 'normal',
    'style': 'default',
    'label_placement': 'top',
    'instruction_placement': 'label',
    'hide_on_screen': ['the_content'],
    'active': True,
    'description': 'Страница /tractor-driver/. Шаблон page-tractor-driver.php только читает эти поля.',
    'show_in_rest': 0,
}

# картинки: отдельные файлы с base64, чтобы не упереться в размер запроса
os.makedirs(OUT, exist_ok=True)
files = sorted({v[5:] for v in json.dumps(CONTENT, ensure_ascii=False).split('"') if v.startswith('file:')})
for i, name in enumerate(files):
    data = base64.b64encode(open(IMG_DIR + name, 'rb').read()).decode()
    open(os.path.join(OUT, 'hp-tr-img-%02d.php' % i), 'w').write("<?php\nif (!defined('ABSPATH')) { exit; }\nreturn array('name' => %s, 'data' => '%s');\n" % (json.dumps(name), data))

php = r"""<?php
// Одноразовый установщик страницы «Удостоверение тракториста». Удаляется сразу после запуска.
if (!isset($_GET['t']) || !hash_equals('__TOKEN__', (string) $_GET['t'])) { http_response_code(404); exit; }
require_once dirname(__DIR__, 3) . '/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/image.php';
header('Content-Type: application/json; charset=utf-8');
@set_time_limit(300);
$page_id = __PAGE__;
$out = array('page' => get_post_status($page_id), 'title' => get_the_title($page_id), 'slug' => get_post_field('post_name', $page_id));
if (get_post_field('post_name', $page_id) !== 'tractor-driver') { $out['error'] = 'wrong page'; echo wp_json_encode($out); exit; }
$group = json_decode(__GROUP__, true);
$values = json_decode(__VALUES__, true);
$files = json_decode(__FILES__, true);
$step = isset($_GET['step']) ? $_GET['step'] : 'check';

if ($step === 'group') {
    $existing = acf_get_field_group('group_tr_tractor');
    if ($existing) {
        $group['ID'] = $existing['ID'];
    }
    $saved = acf_import_field_group($group);
    $out['group'] = array('ID' => $saved['ID'], 'key' => $saved['key'], 'fields' => count(acf_get_fields($saved['ID'])));
}

if ($step === 'media') {
    $ids = get_option('tr_media_ids', array());
    foreach ($files as $i => $file) {
        if (!empty($ids[$file])) { continue; }
        $pack = include __DIR__ . sprintf('/hp-tr-img-%02d.php', $i);
        if ($pack['name'] !== $file) { $out['error'] = 'mismatch ' . $file; break; }
        $up = wp_upload_bits($file, null, base64_decode($pack['data']));
        if (!empty($up['error'])) { $out['error'] = $up['error']; break; }
        $id = wp_insert_attachment(array('post_mime_type' => 'image/jpeg', 'post_title' => preg_replace('/\.jpg$/', '', $file), 'post_status' => 'inherit'), $up['file'], $page_id);
        wp_update_attachment_metadata($id, wp_generate_attachment_metadata($id, $up['file']));
        $ids[$file] = $id;
        update_option('tr_media_ids', $ids, false);
    }
    $out['media'] = $ids;
}

if ($step === 'values') {
    $ids = get_option('tr_media_ids', array());
    $resolve = function ($v) use (&$resolve, $ids) {
        if (is_array($v)) { return array_map($resolve, $v); }
        if (is_string($v) && strpos($v, 'file:') === 0) { return isset($ids[substr($v, 5)]) ? (int) $ids[substr($v, 5)] : null; }
        if (is_string($v) && strpos($v, 'id:') === 0) { return (int) substr($v, 3); }
        return $v;
    };
    $alts = json_decode(__ALTS__, true);
    foreach ($alts as $file => $alt) {
        if (!empty($ids[$file])) { update_post_meta($ids[$file], '_wp_attachment_image_alt', $alt); }
    }
    $done = array();
    foreach ($values as $name => $value) {
        $done[$name] = (bool) update_field('field_' . $name, $resolve($value), $page_id);
    }
    $out['values'] = $done;
}

$out['group_exists'] = (bool) acf_get_field_group('group_tr_tractor');
$out['filled'] = 0;
foreach (array_keys($values) as $name) { if (get_field($name, $page_id, false)) { $out['filled']++; } }
$out['total'] = count($values);
echo wp_json_encode($out, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
"""
ALTS = {
    'tr-hero.jpg': 'Тракторист с удостоверением на полигоне', 'tr-svo.jpg': 'Обучение участников СВО на тракториста',
    'tr-companies.jpg': 'Обучение тракториста для компаний', 'tr-cat-b.jpg': 'Трактор категории B', 'tr-cat-c.jpg': 'Трактор категории C',
    'tr-cat-d.jpg': 'Трактор категории D', 'tr-cat-e.jpg': 'Гусеничный трактор категории E', 'tr-cat-a1.jpg': 'Квадроцикл, категория A I',
    'tr-cat-a2.jpg': 'Багги, категория A II', 'tr-gallery-1.jpg': 'Трактородром учебного центра', 'tr-gallery-2.jpg': 'Трактор на учебном полигоне',
    'tr-gallery-3.jpg': 'Учебный полигон в Белогорске', 'tr-get-1.jpg': 'Удостоверение тракториста-машиниста, лицевая сторона',
    'tr-get-2.jpg': 'Удостоверение тракториста-машиниста, оборот',
}
assert set(ALTS) == set(files), set(files) ^ set(ALTS)
q = lambda o: "'" + json.dumps(o, ensure_ascii=False).replace('\\', '\\\\').replace("'", "\\'") + "'"
php = (php.replace('__TOKEN__', TOKEN).replace('__PAGE__', str(PAGE_ID)).replace('__GROUP__', q(group))
       .replace('__VALUES__', q(CONTENT)).replace('__FILES__', q(files)).replace('__ALTS__', q(ALTS)))
open(os.path.join(OUT, 'hp-tr-install.php'), 'w').write(php)
json.dump(group, open(os.path.join(HERE, 'group_tr_tractor.json'), 'w'), ensure_ascii=False, indent=1)
print('fields', sum(1 for f in fields if f['type'] != 'tab'), 'tabs', sum(1 for f in fields if f['type'] == 'tab'), 'images', len(files))
