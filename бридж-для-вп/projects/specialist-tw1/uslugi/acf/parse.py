"""Разбор HTML страниц услуг в плоский список блоков ACF (flexible content «Разделы»).

Блоки: heading, text, list, cards, steps, images, embed. Порядок документа сохраняется.
"""
import json, re, sys, html as htmllib
from collections import Counter
from bs4 import BeautifulSoup, Comment, NavigableString, Tag

D = '/private/tmp/claude-501/-Users-asanhajretdinov--claude-spares-spare-EXeJV3/d34a3cee-986b-40a5-a2fd-6976eb8b44ac/scratchpad/pg'
SERVICE_IDS = [97, 110, 112, 114, 116, 120, 122, 126, 129, 132, 134, 137, 139, 142, 263, 274, 304, 330]
ABBR = ['ГТН', 'ГИМС', 'РФ', 'ГО', 'ЧС', 'ФЗ', 'СВО', 'ПТМ', 'ПДД', 'РГО', 'ЖКХ', 'ГСМ', 'ООО', 'НПА', 'СИЗ', 'ЕСУТП', 'ГОСТ', 'ПУЭ', 'ПТЭЭП', 'МЧС', 'ЕГАИС', 'АТВ', 'КПП', 'ИТР']

ALLOWED = {'strong', 'em', 'br', 'a', 'p', 'u', 'sup', 'sub'}


def ws(s):
    return re.sub(r'\s+', ' ', s.replace('\xa0', ' ')).strip()


def clean_inline(node):
    """innerHTML с безопасным набором тегов."""
    out = []
    for ch in node.children:
        if isinstance(ch, Comment):
            continue
        if isinstance(ch, NavigableString):
            out.append(htmllib.escape(str(ch).replace('\xa0', ' '), quote=False))
            continue
        name = ch.name
        inner = clean_inline(ch)
        if name in ('b', 'strong'):
            out.append('<strong>%s</strong>' % inner if inner.strip() else '')
        elif name in ('i', 'em'):
            out.append('<em>%s</em>' % inner if inner.strip() else '')
        elif name == 'br':
            out.append('<br>')
        elif name == 'a':
            href = ch.get('href', '')
            out.append('<a href="%s">%s</a>' % (htmllib.escape(href, quote=True), inner))
        elif name in ('u', 'sup', 'sub'):
            out.append('<%s>%s</%s>' % (name, inner, name))
        elif name == 'button':
            continue
        else:  # span, font, div и т.п. — оставляем только содержимое
            out.append(inner)
    s = ''.join(out)
    s = re.sub(r'(?:<br>\s*){3,}', '<br><br>', s)
    s = re.sub(r'^(?:\s|<br>)+|(?:\s|<br>)+$', '', s)
    return re.sub(r'[ \t\r\n]+', ' ', s).strip()


def classes(n):
    return n.get('class', []) if isinstance(n, Tag) else []


def upper_ratio(s):
    letters = [c for c in s if c.isalpha()]
    return (sum(c.isupper() for c in letters) / len(letters)) if letters else 0


def _lower(s):
    low = s.lower()
    for a in ABBR:
        low = re.sub(r'(?<![а-яa-z])%s(?![а-яa-z])' % a.lower(), a, low)
    return low


def sentence_case(s):
    """Строка в ВЕРХНЕМ РЕГИСТРЕ → обычный регистр, аббревиатуры сохраняем."""
    if len([c for c in s if c.isalpha()]) < 4 or upper_ratio(s) < 0.6:
        return s
    low = _lower(s)
    return low[:1].upper() + low[1:]


def fix_caps(parts):
    """Три части заголовка приводим к одному регистру, первая непустая — с заглавной."""
    full = ' '.join(p for p in parts if p)
    if len([c for c in full if c.isalpha()]) < 4 or upper_ratio(full) < 0.6:
        return parts
    out, first = [], True
    for p in parts:
        if not p:
            out.append(p)
            continue
        low = _lower(p)
        out.append(low[:1].upper() + low[1:] if first else low)
        first = False
    return out


def heading_parts(el):
    """(before, accent, after) из .section-title / h*: акцент = span.color-main."""
    before, accent, after = [], [], []
    seen = False

    def rec(n):
        nonlocal seen
        for ch in n.children:
            if isinstance(ch, Comment):
                continue
            if isinstance(ch, NavigableString):
                t = str(ch).replace('\xa0', ' ')
                (after if seen else before).append(t)
            elif ch.name == 'br':
                (after if seen else before).append(' ')
            elif 'color-main' in classes(ch):
                accent.append(ch.get_text(' '))
                seen = True
            else:
                rec(ch)

    rec(el)
    b, a, f = ws(''.join(before)), ws(' '.join(accent)), ws(''.join(after))
    if not a:  # без акцента — весь текст в «до»
        b, f = ws(b + ' ' + f), ''
    b, a, f = fix_caps([b, a, f])
    return b, a, f


class Parser:
    def __init__(self):
        self.out = []
        self.buf = []      # накопленные абзацы обычного текста
        self.imgs = []
        self.img_slider = False
        self.h1 = None

    # --- накопители ---
    def flush_text(self):
        if self.buf:
            self.out.append({'acf_fc_layout': 'text', 'content': ''.join(self.buf), 'emphasis': 0})
            self.buf = []

    def flush_imgs(self):
        if self.imgs:
            self.out.append({'acf_fc_layout': 'images', 'variant': 'slider' if self.img_slider else 'documents', 'images': self.imgs})
            self.imgs = []
            self.img_slider = False

    def flush(self):
        self.flush_text()
        self.flush_imgs()

    # --- обход ---
    def walk(self, nodes, ctx=()):
        for n in list(nodes):
            if isinstance(n, Comment):
                continue
            if isinstance(n, NavigableString):
                t = ws(str(n))
                if t:
                    self.buf.append('<p>%s</p>' % htmllib.escape(t, quote=False))
                continue
            self.node(n, ctx)

    def node(self, n, ctx):
        name, cl = n.name, classes(n)
        if 'section-title' in cl:
            self.flush()
            b, a, f = heading_parts(n)
            lvl = 'h1' if n.find('h1') and self.h1 is None else None
            if lvl == 'h1':
                self.h1 = {'before': b, 'accent': a, 'after': f}
            elif b or a or f:
                self.out.append({'acf_fc_layout': 'heading', 'level': 2, 'before': b, 'accent': a, 'after': f})
            return
        if name == 'h1':
            self.flush()
            b, a, f = heading_parts(n)
            if self.h1 is None:
                self.h1 = {'before': b, 'accent': a, 'after': f}
            else:
                self.out.append({'acf_fc_layout': 'heading', 'level': 2, 'before': b, 'accent': a, 'after': f})
            return
        if name in ('h2', 'h3', 'h4', 'h5'):
            self.flush()
            b, a, f = heading_parts(n)
            lvl = {'h2': 2, 'h3': 3, 'h4': 4, 'h5': 4}[name]
            if b or a or f:
                self.out.append({'acf_fc_layout': 'heading', 'level': lvl, 'before': b, 'accent': a, 'after': f})
            return
        if name == 'button':
            return
        if name == 'iframe':
            self.flush()
            self.out.append({'acf_fc_layout': 'embed', 'code': str(n)})
            return
        if name == 'img':
            self.flush_text()
            src = n.get('data-src') or n.get('src') or ''
            if src:
                self.imgs.append({'src': src, 'alt': ws(n.get('alt', '')), 'link': ''})
            if any(c in ctx for c in ('slick', 'slick-multiple')):
                self.img_slider = True
            return
        if name in ('ul', 'ol'):
            self.flush()
            style = 'numbered' if name == 'ol' else 'marker'
            if 'list-icon--minus' in cl:
                style = 'check'
            elif 'list-icon--warning' in cl:
                style = 'warning'
            elif 'how-we-are-working-list' in cl:
                items = [{'text': clean_inline(li)} for li in n.find_all('li', recursive=False)]
                self.out.append({'acf_fc_layout': 'steps', 'steps': items})
                return
            items = [{'text': clean_inline(li)} for li in n.find_all('li', recursive=False)]
            self.out.append({'acf_fc_layout': 'list', 'style': style, 'items': items})
            return
        if 'blocks-wrap' in cl or any(c.startswith('blocks-wrap') for c in cl):
            self.flush()
            cards = []
            for card in n.find_all(class_='rectangle-with-spaces-in-angle'):
                t = card.find(class_='rectangle-with-spaces-in-angle--title')
                title = ws(t.get_text(' ')) if t else ''
                if t:
                    t.extract()
                body = []
                for ch in card.children:
                    if isinstance(ch, Comment):
                        continue
                    if isinstance(ch, NavigableString):
                        if ws(str(ch)):
                            body.append('<p>%s</p>' % htmllib.escape(ws(str(ch)), quote=False))
                    elif ch.name in ('p', 'div'):
                        c = clean_inline(ch)
                        if c:
                            body.append('<p>%s</p>' % c)
                    elif ch.name in ('ul', 'ol'):
                        body.append('<%s>%s</%s>' % (ch.name, ''.join('<li>%s</li>' % clean_inline(li) for li in ch.find_all('li', recursive=False)), ch.name))
                    else:
                        c = clean_inline(ch)
                        if c:
                            body.append('<p>%s</p>' % c)
                body = [x for x in body if not re.fullmatch(r'<p>\s*(записаться на обучение|подать заявку[^<]*|подробнее)\s*</p>', x, re.I)]
                cards.append({'title': sentence_case(title), 'text': ''.join(body)})
            if not cards:  # блок без карточек — обычный обход
                self.walk(n.children, ctx + tuple(cl))
                return
            variant = 'price' if 'blocks-wrap-cost' in cl else 'cards'
            self.out.append({'acf_fc_layout': 'cards', 'variant': variant, 'cards': cards})
            return
        if name == 'p':
            c = clean_inline(n)
            if not c:
                return
            if 'text-bold' in cl or 'text-center' in cl and n.find(['strong', 'b']) is not None and not n.get_text(strip=True).replace(n.find(['strong', 'b']).get_text(strip=True), ''):
                self.flush()
                self.out.append({'acf_fc_layout': 'text', 'content': '<p>%s</p>' % c, 'emphasis': 1})
            else:
                self.flush_imgs()
                self.buf.append('<p>%s</p>' % c)
            return
        if name == 'a':
            if 'btn' in cl:
                return
            imgs = n.find_all('img')
            if imgs:
                self.flush_text()
                href = n.get('href', '')
                for im in imgs:
                    src = im.get('data-src') or im.get('src') or ''
                    if not src:
                        continue
                    ext = href and not re.search(r'\.(jpe?g|png|gif|webp)(\?|$)', href, re.I) and href.startswith('http')
                    self.imgs.append({'src': src, 'alt': ws(im.get('alt', '')), 'link': href if ext else ''})
                if any(c in ctx for c in ('slick', 'slick-multiple', 'slick-item')):
                    self.img_slider = True
                return
            self.flush_imgs()
            self.buf.append('<p>%s</p>' % clean_inline(BeautifulSoup('<p>%s</p>' % str(n), 'lxml').p))
            return
        if name in ('b', 'strong', 'span', 'em', 'i', 'br', 'u'):
            c = clean_inline(BeautifulSoup('<p>%s</p>' % str(n), 'lxml').p) if name != 'br' else ''
            if c:
                self.buf.append('<p>%s</p>' % c)
            return
        # контейнеры-обёртки: container, container-fluid-gray, section, div, wow...
        self.walk(n.children, ctx + tuple(cl))


def parse(content):
    content = re.sub(r'<!--.*?-->', '', content, flags=re.S)
    soup = BeautifulSoup(content, 'lxml')
    p = Parser()
    if soup.body:
        p.walk(soup.body.children)
    p.flush()
    return p.h1, p.out


def words(s):
    s = re.sub(r'<[^>]+>', ' ', s)
    return re.findall(r'[0-9A-Za-zА-Яа-яЁё]+', htmllib.unescape(s).lower())


def source_words(content):
    content = re.sub(r'<!--.*?-->', '', content, flags=re.S)
    soup = BeautifulSoup(content, 'lxml')
    for b in soup.find_all('button'):
        b.decompose()
    for b in soup.find_all(class_='btn'):
        b.decompose()
    for b in soup.find_all(['iframe', 'script', 'style']):
        b.decompose()
    return words(soup.get_text(' '))


def layout_words(h1, layouts):
    parts = []
    if h1:
        parts.append(' '.join(h1.values()))
    for l in layouts:
        t = l['acf_fc_layout']
        if t == 'heading':
            parts.append('%s %s %s' % (l['before'], l['accent'], l['after']))
        elif t == 'text':
            parts.append(l['content'])
        elif t == 'list':
            parts += [i['text'] for i in l['items']]
        elif t == 'steps':
            parts += [i['text'] for i in l['steps']]
        elif t == 'cards':
            for c in l['cards']:
                parts += [c['title'], c['text']]
    return words(' '.join(parts))


if __name__ == '__main__':
    pages = {x['id']: x for x in json.load(open(D + '/prod_pages.json'))['items']}
    result = {}
    bad = 0
    for i in SERVICE_IDS:
        h1, lay = parse(pages[i]['content'])
        src = Counter(source_words(pages[i]['content']))
        got = Counter(layout_words(h1, lay))
        miss = src - got
        extra = got - src
        kinds = Counter(l['acf_fc_layout'] for l in lay)
        print(i, pages[i]['title'][:36].ljust(36), dict(kinds), 'LOST:' + str(dict(miss)) if miss else 'ok', ('extra:' + str(dict(extra))) if extra else '')
        bad += bool(miss)
        result[i] = {'title': pages[i]['title'], 'slug': pages[i]['slug'], 'h1': h1, 'layouts': lay}
    json.dump(result, open(D + '/parsed.json', 'w'), ensure_ascii=False, indent=1)
    print('pages with lost words:', bad)
