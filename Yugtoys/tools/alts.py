"""С-2: картинки страницы с пустым/служебным alt («alt», «бренды»), сгруппированные по папке src.
python3 alts.py URL [--all]  — --all печатает каждую картинку"""
import collections
import re
import sys
import urllib.request

url = sys.argv[1]
h = urllib.request.urlopen(urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'})).read().decode()
h = re.sub(r'<!--.*?-->', '', h, flags=re.S)  # закомментированное не показывается
imgs = re.findall(r'<img[^>]*>', h)
bad = collections.Counter()
rows = []
for i in imgs:
    a = re.search(r'alt="([^"]*)"', i)
    s = re.search(r'src="([^"]*)"', i)
    alt = a.group(1) if a else None
    src = s.group(1) if s else '?'
    if alt is None or alt.strip().lower() in ('', 'alt', 'бренды'):
        bad[(alt, src.rsplit('/', 1)[0] + '/')] += 1
        rows.append((alt, src))
print('всего img:', len(imgs), '| плохой alt:', len(rows))
for (alt, d), n in bad.most_common():
    print(n, repr(alt), d)
if '--all' in sys.argv:
    for r in rows:
        print(r)
