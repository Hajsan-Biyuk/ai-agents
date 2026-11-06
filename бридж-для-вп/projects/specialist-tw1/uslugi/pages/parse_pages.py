"""Разбор внутренних страниц (О компании, Контакты, Фотогалерея, Наша техника, Сведения, политика, согласие, Спасибо)."""
import json, os, re, sys, html as htmllib, urllib.parse
from collections import Counter
from bs4 import BeautifulSoup, Comment, NavigableString, Tag

D = '/private/tmp/claude-501/-Users-asanhajretdinov--claude-spares-spare-EXeJV3/d34a3cee-986b-40a5-a2fd-6976eb8b44ac/scratchpad/pg'
sys.path.insert(0, D)
import parse as base
from parse import Parser, clean_inline, classes, ws, fix_caps, heading_parts, words, source_words

PAGES = {
    9: dict(style='sections', consult=1, contacts=0),
    16: dict(style='sections', consult=1, contacts=1),
    381: dict(style='sections', consult=1, contacts=0),
    384: dict(style='sections', consult=1, contacts=0),
    486: dict(style='sections', consult=0, contacts=0),
    3: dict(style='document', consult=0, contacts=0),
    550: dict(style='document', consult=0, contacts=0),
    552: dict(style='notice', consult=0, contacts=0),
}
IMG_RE = re.compile(r'\.(jpe?g|png|gif|webp)(\?|$)', re.I)


def norm_src(u):
    u = (u or '').strip()
    return re.sub(r'(?<!:)/{2,}', '/', u) if u.startswith('/') else u


class P2(Parser):
    def __init__(self):
        super().__init__()
        self.skip = set()
        self.facts = []

    def flush_facts(self):
        if self.facts:
            self.out.append({'acf_fc_layout': 'facts', 'rows': self.facts})
            self.facts = []

    def flush(self):
        super().flush()
        self.flush_facts()

    def node(self, n, ctx):
        if id(n) in self.skip:
            return
        name, cl = n.name, classes(n)

        # реквизиты строками: «Контакты»
        if 'contacts-info' in cl:
            return self.contacts(n)

        # дата регистрации: подпись + значение
        if name == 'div' and 'fw-700' in cl:
            self.flush_text()
            sib = n.find_next_sibling()
            val = clean_inline(sib) if sib is not None else ''
            if sib is not None:
                self.skip.add(id(sib))
            self.facts.append({'label': ws(n.get_text(' ')), 'value': val})
            return
        if name == 'strong' and 'fw-700' in cl:
            self.flush()
            self.out.append({'acf_fc_layout': 'heading', 'level': 3, 'before': ws(n.get_text(' ')), 'accent': '', 'after': ''})
            return

        # таблица «название — значение»
        if name == 'table':
            self.flush_text()
            for tr in n.find_all('tr'):
                cells = [c for c in tr.find_all(['td', 'th']) if ws(c.get_text(' '))]
                if len(cells) >= 2:
                    self.facts.append({'label': ws(cells[0].get_text(' ')), 'value': clean_inline(cells[-1])})
                elif len(cells) == 1:
                    self.facts.append({'label': '', 'value': clean_inline(cells[0])})
            return

        # кнопка-ссылка
        if name == 'a' and 'btn' in cl and not n.find('img'):
            self.flush()
            self.out.append({'acf_fc_layout': 'button', 'label': ws(n.get_text(' ')), 'url': n.get('href', '/')})
            return

        # длинный «заголовок» — это абзац-плашка
        if name in ('h2', 'h3', 'h4') and len(ws(n.get_text(' '))) > 110:
            self.flush()
            c = clean_inline(n)
            if c:
                self.out.append({'acf_fc_layout': 'text', 'content': '<p>%s</p>' % c, 'emphasis': 1})
            return

        # изображение в ссылке: берём картинку из href, если она крупнее
        if name == 'a' and n.find('img'):
            self.flush_text()
            href = norm_src(n.get('href', ''))
            for im in n.find_all('img'):
                src = norm_src(im.get('data-src') or im.get('src') or '')
                if href and IMG_RE.search(href):
                    src = href
                if not src:
                    continue
                ext = href.startswith('http') and not IMG_RE.search(href)
                self.imgs.append({'src': src, 'alt': ws(im.get('alt', '')), 'link': href if ext else ''})
            if any(c in ctx for c in ('slick', 'slick-multiple', 'slick-item')):
                self.img_slider = True
            if 'photos' in ctx:
                self.img_gallery = True
            return
        if name == 'img':
            self.flush_text()
            src = norm_src(n.get('data-src') or n.get('src') or '')
            if src:
                self.imgs.append({'src': src, 'alt': ws(n.get('alt', '')), 'link': ''})
            if any(c in ctx for c in ('slick', 'slick-multiple', 'slick-item')):
                self.img_slider = True
            return
        # списки: «иконочные» считаем списками с галочками
        if name == 'ul' and 'list-with-icon--minus' in cl:
            self.flush()
            self.out.append({'acf_fc_layout': 'list', 'style': 'check', 'items': [{'text': clean_inline(li)} for li in n.find_all('li', recursive=False)]})
            return
        if name == 'ul' and 'founder-list' in cl:
            self.flush()
            self.out.append({'acf_fc_layout': 'list', 'style': 'marker', 'items': [{'text': clean_inline(li)} for li in n.find_all('li', recursive=False)]})
            return
        super().node(n, ctx)

    def flush_imgs(self):
        if self.imgs:
            variant = 'slider' if self.img_slider else ('gallery' if getattr(self, 'img_gallery', False) else 'documents')
            self.out.append({'acf_fc_layout': 'images', 'variant': variant, 'images': self.imgs})
            self.imgs = []
            self.img_slider = False
            self.img_gallery = False

    def contacts(self, n):
        """Блок «Как нас найти»: подписи span.color-main и значения до <br>."""
        self.flush()
        rows, label, buf = [], None, []

        def push():
            nonlocal label, buf
            html = ''.join(buf).strip()
            html = re.sub(r'^(?:\s|<br>)+|(?:\s|<br>)+$', '', html)
            if html or label:
                rows.append({'label': label or '', 'value': html})
            label, buf = None, []

        pending_br = False
        for ch in n.children:
            if isinstance(ch, Comment):
                continue
            if isinstance(ch, Tag) and 'section-title' in classes(ch):
                b, a, f = heading_parts(ch)
                self.out.append({'acf_fc_layout': 'heading', 'level': 2, 'before': b, 'accent': a, 'after': f})
            elif isinstance(ch, Tag) and ch.name == 'p':
                self.out.append({'acf_fc_layout': 'text', 'content': '<p>%s</p>' % clean_inline(ch), 'emphasis': 1})
            elif isinstance(ch, Tag) and 'color-main' in classes(ch):
                push()
                label = ws(ch.get_text(' ')).rstrip(':')
            elif isinstance(ch, Tag) and ch.name == 'br':
                # одиночный <br> закрывает строку, двойной — отделяет блок реквизитов
                if buf and buf[-1] == '<br>':
                    push()
                    label = 'Реквизиты'
                else:
                    buf.append('<br>')
                    if label is not None and ''.join(buf).strip() not in ('', '<br>') and not pending_br:
                        pass
            else:
                piece = clean_inline(BeautifulSoup('<p>%s</p>' % str(ch), 'lxml').p) if isinstance(ch, Tag) else htmllib.escape(str(ch).replace('\xa0', ' '), quote=False)
                buf.append(piece)
        push()
        # внутри строки <br> в конце — лишний
        for r in rows:
            r['value'] = re.sub(r'(?:<br>\s*)+$', '', r['value']).strip()
        # одиночные <br> между строками означают конец значения: делим «Адрес:...<br><span>Директор»
        self.out.append({'acf_fc_layout': 'facts', 'rows': [r for r in rows if r['label'] or r['value']]})


def parse(content):
    content = re.sub(r'<!--.*?-->', '', content, flags=re.S)
    soup = BeautifulSoup(content, 'lxml')
    p = P2()
    p.img_gallery = False
    if soup.body:
        p.walk(soup.body.children)
    p.flush()
    return p.out


def layout_words(layouts):
    parts = []
    for l in layouts:
        t = l['acf_fc_layout']
        if t == 'heading':
            parts.append('%s %s %s' % (l['before'], l['accent'], l['after']))
        elif t == 'text':
            parts.append(l['content'])
        elif t == 'list':
            parts += [i['text'] for i in l['items']]
        elif t == 'facts':
            parts += [r['label'] + ' ' + r['value'] for r in l['rows']]
        elif t == 'button':
            pass
    return words(' '.join(parts))


if __name__ == '__main__':
    pages = {x['id']: x for x in json.load(open(D + '/prod_pages.json'))['items']}
    res = {}
    for pid, cfg in PAGES.items():
        lay = parse(pages[pid]['content'])
        src = Counter(source_words(pages[pid]['content']))
        got = Counter(layout_words(lay))
        miss = src - got
        kinds = Counter(l['acf_fc_layout'] for l in lay)
        print(pid, pages[pid]['title'][:40].ljust(40), dict(kinds), ('LOST ' + str(dict(miss))) if miss else 'ok')
        res[pid] = {'id': pid, 'title': pages[pid]['title'], 'slug': pages[pid]['slug'], 'sections': lay, **cfg}
    json.dump(res, open(D + '/parsed_pages.json', 'w'), ensure_ascii=False, indent=1)
