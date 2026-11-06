"""Данные внутренних страниц: нормализация ссылок, оптимизация картинок, итоговый JSON."""
import json, os, re, urllib.parse
from PIL import Image

D = '/private/tmp/claude-501/-Users-asanhajretdinov--claude-spares-spare-EXeJV3/d34a3cee-986b-40a5-a2fd-6976eb8b44ac/scratchpad/pg'
OUT = D + '/media_pages'
os.makedirs(OUT, exist_ok=True)
parsed = json.load(open(D + '/parsed_pages.json'))


def fix_tel(html):
    """href телефона собираем из текста ссылки, а не из исходной ссылки."""
    def rep(m):
        digits = re.sub(r'\D', '', re.sub(r'<[^>]+>', '', m.group(2)))
        if len(digits) == 11:
            return '<a href="tel:+%s">%s</a>' % ('7' + digits[1:], m.group(2))
        return m.group(0)
    return re.sub(r'<a href="(tel:[^"]*)">(.*?)</a>', rep, html)


def optimize(src, name):
    im = Image.open(src)
    if im.mode in ('RGBA', 'LA', 'P'):
        im = im.convert('RGBA')
        bg = Image.new('RGB', im.size, (255, 255, 255))
        bg.paste(im, mask=im.getchannel('A'))
        im = bg
    else:
        im = im.convert('RGB')
    im.thumbnail((1100, 1500), Image.LANCZOS)
    dst = os.path.join(OUT, name)
    im.save(dst, quality=74, optimize=True, progressive=True)
    return name


files = {}
pages = []
for pid, x in parsed.items():
    lay = []
    last_head = x['title']
    for l in x['sections']:
        if l['acf_fc_layout'] == 'heading':
            last_head = ' '.join(t for t in (l['before'], l['accent'], l['after']) if t) or x['title']
        if l['acf_fc_layout'] == 'facts':
            l = {'acf_fc_layout': 'facts', 'rows': [{'label': r['label'], 'value': fix_tel(r['value'])} for r in l['rows']]}
        if l['acf_fc_layout'] == 'images':
            imgs = []
            for im in l['images']:
                path = urllib.parse.unquote(im['src'])
                key = path.replace('/wp-content/themes/spec/images/', '').replace('/', '__')
                name = 'pg-' + re.sub(r'[^0-9A-Za-z_.-]+', '-', os.path.splitext(key)[0]).strip('-') + '.jpg'
                if name not in files:
                    files[name] = optimize(D + '/pg2/raw/' + key, name)
                imgs.append({'image': 'file:' + name, 'link': im.get('link', ''), 'alt': im.get('alt') or last_head})
            l = {'acf_fc_layout': 'images', 'variant': l['variant'], 'images': imgs}
        lay.append(l)
    pages.append({'id': int(pid), 'title': x['title'], 'slug': x['slug'], 'style': x['style'], 'consult': x['consult'], 'contacts': x['contacts'],
                  'h1': {'before': x['title'], 'accent': '', 'after': ''}, 'sections': lay})
err404 = {'err404_title': 'Страница не найдена', 'err404_text': 'Такой страницы нет или ссылка устарела.', 'err404_btn': 'На главную'}
json.dump({'pages': pages, 'files': sorted(files), 'err404': err404}, open(D + '/pages_final.json', 'w'), ensure_ascii=False, indent=1)
tot = sum(os.path.getsize(os.path.join(OUT, f)) for f in os.listdir(OUT))
print('pages', len(pages), 'images', len(files), 'total', tot // 1024, 'KB')
