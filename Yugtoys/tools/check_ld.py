"""П-3.5: проверить карточки — код ответа, валидность JSON-LD, цена в разметке vs видимая розничная.
python3 check_ld.py urls.txt"""
import json
import re
import sys
import urllib.error
import urllib.request

for url in open(sys.argv[1]).read().split():
    try:
        r = urllib.request.urlopen(urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'}), timeout=30)
        code, h = r.status, r.read().decode()
    except urllib.error.HTTPError as e:
        print(e.code, url); continue
    lds = re.findall(r'<script type="application/ld\+json">(.*?)</script>', h, re.S)
    prod = [d for d in (json.loads(x) for x in lds) if isinstance(d, dict) and d.get('@type') == 'Product']
    retail = re.findall(r'Розничная цена: <strong>([^<]*)', h)
    p = prod[0].get('offers', {}).get('price', 'нет offers') if prod else 'нет Product'
    print(code, 'JSON ok', 'price=', p, '| видно гостю:', retail[0].replace('&nbsp;', ' ') if retail else '—', '|', url.split('/')[-2][:40])
