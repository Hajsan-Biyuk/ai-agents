"""Проверка главной laminarium.com: без флага — старая версия, с флагом — без ошибок; размеры картинок и ссылки новостей."""
import io
import re
import urllib.error
import urllib.request

from PIL import Image

SITE = 'https://www.laminarium.com'


def get(url, preview=False):
    headers = {'User-Agent': 'Mozilla/5.0'}
    if preview:
        headers['Cookie'] = 'lam_preview=1'
    try:
        r = urllib.request.urlopen(urllib.request.Request(SITE + url, headers=headers), timeout=30)
        return r.status, r.read()
    except urllib.error.HTTPError as e:
        return e.code, b''


for url in ('/', '/catalog/'):
    code, body = get(url)
    s = body.decode('utf-8', 'ignore')
    print('без флага', url, code, 'lam-header:', s.count('lam-header'), 'ошибок PHP:', len(re.findall(r'(?i)fatal error|parse error', s)))

code, body = get('/', preview=True)
s = body.decode('utf-8', 'ignore')
print('с флагом /', code, 'ошибок PHP:', len(re.findall(r'(?i)fatal error|parse error|warning:', s)), '#YEAR#:', s.count('#YEAR#'))
for cls in ('lam-card__image', 'lam-news-card__image', 'lam-brand"'):
    for src in re.findall(cls + r'[^>]*><img src="([^"]+)"', s)[:3]:
        c, img = get(src)
        size = Image.open(io.BytesIO(img)).size if img else None
        print(' ', cls.strip('"'), src[-36:], size, len(img) // 1024, 'KB')
for href in re.findall(r'lam-news-card__title" href="([^"]+)"', s)[:3]:
    print('  новость', href, get(href)[0])
