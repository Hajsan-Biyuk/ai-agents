"""Итоговые данные услуг: поля баннера, перечня, разделы. Картинки оптимизируются в каталог media/."""
import json, os, re, shutil, urllib.parse, html as htmllib
from PIL import Image

D = '/private/tmp/claude-501/-Users-asanhajretdinov--claude-spares-spare-EXeJV3/d34a3cee-986b-40a5-a2fd-6976eb8b44ac/scratchpad/pg'
OUT = D + '/media'
os.makedirs(OUT, exist_ok=True)
parsed = json.load(open(D + '/parsed.json'))

# направление, значок, порядок (как плитки на боевом сайте)
META = {
    '142': ('Спецтехника и самоходные машины', 'excavator', 2),
    '97': ('Спецтехника и самоходные машины', 'loader', 3),
    '129': ('Рабочие профессии', 'working', 4),
    '304': ('Спецтехника и самоходные машины', 'atv', 5),
    '110': ('Охрана труда и безопасность', 'safety', 6),
    '122': ('Охрана труда и безопасность', 'civil', 7),
    '126': ('Охрана труда и безопасность', 'first-aid', 8),
    '116': ('Переподготовка и повышение квалификации', 'retrain', 9),
    '132': ('Охрана труда и безопасность', 'industrial', 10),
    '134': ('Охрана труда и безопасность', 'electrical', 11),
    '137': ('Охрана труда и безопасность', 'height', 12),
    '139': ('Рабочие профессии', 'elevator', 13),
    '120': ('Охрана труда и безопасность', 'ecology', 14),
    '112': ('Охрана труда и безопасность', 'fire', 15),
    '274': ('Управление маломерными судами', 'boat', 16),
    '330': ('Управление маломерными судами', 'rescue', 17),
    '114': ('Охрана труда и безопасность', 'road', 18),
    '263': ('Спецтехника и самоходные машины', 'tractor', 19),
}
TERMS = ['Спецтехника и самоходные машины', 'Рабочие профессии', 'Охрана труда и безопасность', 'Переподготовка и повышение квалификации', 'Управление маломерными судами']


def text_of(h):
    return re.sub(r'\s+', ' ', htmllib.unescape(re.sub(r'<[^>]+>', ' ', h))).strip()


def fmt_price(n):
    s = '{:,}'.format(n).replace(',', ' ')
    return s + ' ₽'


def find_price(layouts):
    """Минимальная цена из блоков после заголовка «Стоимость»."""
    start = 0
    for i, l in enumerate(layouts):
        if l['acf_fc_layout'] == 'heading' and 'тоимост' in (l['before'] + l['accent'] + l['after']):
            start = i
    nums, has_from = [], False
    for l in layouts[start:]:
        t = l['acf_fc_layout']
        chunks = []
        if t == 'text':
            chunks.append(l['content'])
        elif t == 'cards':
            chunks += [c['text'] + ' ' + c['title'] for c in l['cards']]
        elif t == 'list':
            chunks += [i['text'] for i in l['items']]
        for ch in chunks:
            tx = text_of(ch)
            for m in re.finditer(r'(от\s+)?(\d{1,3}(?:[\s ]\d{3})+|\d{3,6})\s*(?:руб|₽|р\.)', tx, re.I):
                v = int(re.sub(r'\D', '', m.group(2)))
                if v >= 500:
                    nums.append(v)
                    has_from = has_from or bool(m.group(1))
    if not nums:
        return ''
    low = min(nums)
    return ('от ' if (has_from or len(set(nums)) > 1) else '') + fmt_price(low)


def split_lead(layouts):
    """Первый абзац перед первым заголовком — в подзаголовок баннера (если короткий)."""
    if not layouts or layouts[0]['acf_fc_layout'] != 'text' or layouts[0].get('emphasis'):
        return '', layouts
    paras = re.findall(r'<p>.*?</p>', layouts[0]['content'], re.S)
    if not paras:
        return '', layouts
    first = paras[0]
    if len(text_of(first)) > 360:
        return '', layouts
    rest = ''.join(paras[1:])
    lay = list(layouts)
    if rest:
        lay[0] = dict(lay[0], content=rest)
    else:
        lay = lay[1:]
    return first[3:-4], lay


def short_text(src):
    t = text_of(src)
    if not t:
        return ''
    m = re.match(r'(.{60,170}?[.!?])(\s|$)', t)
    if m:
        return m.group(1)
    if len(t) <= 170:
        return t
    return t[:167].rsplit(' ', 1)[0] + '…'


def optimize(src_path, name):
    ext = os.path.splitext(name)[1].lower()
    dst = os.path.join(OUT, os.path.splitext(name)[0] + ('.png' if name.startswith('icon-') else '.jpg'))
    if name.startswith('icon-'):
        shutil.copy(src_path, dst)
        return os.path.basename(dst)
    im = Image.open(src_path)
    if im.mode in ('RGBA', 'LA', 'P'):
        im = im.convert('RGBA')
        bg = Image.new('RGB', im.size, (255, 255, 255))
        bg.paste(im, mask=im.getchannel('A'))
        im = bg
    else:
        im = im.convert('RGB')
    im.thumbnail((1200, 1500), Image.LANCZOS)
    if os.path.getsize(src_path) < 30 * 1024 and im.width <= 300:
        shutil.copy(src_path, dst)  # маленький баннер не трогаем
        return os.path.basename(dst)
    im.save(dst, quality=76, optimize=True, progressive=True)
    return os.path.basename(dst)


files = {}
services = []
for pid, meta in META.items():
    x = parsed[pid]
    term, icon, order = meta
    lead, layouts = split_lead(x['layouts'])
    first_para = lead or next((re.findall(r'<p>.*?</p>', l['content'], re.S)[0] for l in layouts if l['acf_fc_layout'] == 'text' and re.findall(r'<p>.*?</p>', l['content'], re.S)), '')
    out_layouts = []
    for l in layouts:
        if l['acf_fc_layout'] == 'images':
            imgs = []
            for im in l['images']:
                u = im['src']
                name = os.path.basename(urllib.parse.urlparse(u).path)
                if name not in files:
                    files[name] = optimize(D + '/docimg/' + name, name)
                imgs.append({'image': 'file:' + files[name], 'link': im.get('link', ''), 'alt': im.get('alt') or x['title']})
            l = {'acf_fc_layout': 'images', 'variant': l['variant'], 'images': imgs}
        out_layouts.append(l)
    h1 = x['h1'] or {'before': x['title'], 'accent': '', 'after': ''}
    services.append({
        'id': int(pid), 'title': x['title'], 'slug': x['slug'], 'term': term, 'order': order,
        'icon': 'file:icon-%s.png' % icon,
        'h1': h1, 'lead': lead, 'price': find_price(out_layouts), 'short': short_text(first_para),
        'sections': out_layouts,
    })
for k in sorted({m[1] for m in META.values()}):
    src = D + '/icons/icon-%s.png' % k
    files['icon-%s.png' % k] = optimize(src, 'icon-%s.png' % k)
tile_icon = 'icon-tractor.png'
json.dump({'terms': TERMS, 'services': sorted(services, key=lambda s: s['order']), 'files': sorted(set(files.values()))},
          open(D + '/final.json', 'w'), ensure_ascii=False, indent=1)
tot = sum(os.path.getsize(os.path.join(OUT, f)) for f in os.listdir(OUT))
print('services', len(services), 'media files', len(os.listdir(OUT)), 'total', tot // 1024, 'KB')
for s in sorted(services, key=lambda s: s['order']):
    print(str(s['id']).ljust(4), s['title'][:34].ljust(34), '| price:', (s['price'] or '-').ljust(12), '| lead:', len(text_of(s['lead'])), '| short:', s['short'][:60])
