"""Быстрая проверка живых страниц без флага: код ответа и отсутствие ошибок PHP. Выход 1 — если что-то не так."""
import re
import sys
import urllib.error
import urllib.request

PAGES = ['/', '/catalog/', '/catalog/laminat/6695/', '/contacts/', '/basket/',
         '/company/news/2026/tolshchina_laminata_kakaya_byvaet_i_kak_vybrat/',
         '/info/articles/2023/ekspert_krymov_denis_yurevich/']
bad = 0
for url in PAGES:
    try:
        r = urllib.request.urlopen(urllib.request.Request('https://www.laminarium.com' + url,
                                                          headers={'User-Agent': 'Mozilla/5.0'}), timeout=30)
        code, s = r.status, r.read().decode('utf-8', 'ignore')
    except urllib.error.HTTPError as e:
        code, s = e.code, ''
    errs = len(re.findall(r'(?i)fatal error|parse error', s))
    ipp, kry = s.count('Ипполитова'), s.count('ИП Крымов')
    ok = code == 200 and errs == 0
    bad += not ok
    print(('OK ' if ok else 'BAD'), code, url, 'ошибок PHP:', errs, '| Ипполитова:', ipp, 'ИП Крымов:', kry)
sys.exit(1 if bad else 0)
