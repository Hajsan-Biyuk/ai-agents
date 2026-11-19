"""Коды ответа списка URL параллельно. python3 status.py urls.txt → печатает код и URL (кроме 200, если --bad)"""
import sys
import urllib.error
import urllib.request
from concurrent.futures import ThreadPoolExecutor

def code(u):
    try:
        return urllib.request.urlopen(urllib.request.Request(u, headers={'User-Agent': 'Mozilla/5.0'}), timeout=60).status, u
    except urllib.error.HTTPError as e:
        return e.code, u
    except Exception as e:
        return type(e).__name__, u

urls = open(sys.argv[1]).read().split()
with ThreadPoolExecutor(4) as ex:
    res = list(ex.map(code, urls))
from collections import Counter
print(Counter(c for c, _ in res))
for c, u in res:
    if c != 200 or '--bad' not in sys.argv:
        if c != 200: print(c, u)
