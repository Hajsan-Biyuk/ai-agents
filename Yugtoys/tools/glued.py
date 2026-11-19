"""С-3: найти слипшиеся (словоЗаглавное) и повторённые слова на страницах yugtoys.ru.
python3 glued.py /path1/ /path2/ ..."""
import html
import re
import sys
import urllib.request

for u in sys.argv[1:]:
    h = urllib.request.urlopen(urllib.request.Request('https://www.yugtoys.ru' + u, headers={'User-Agent': 'Mozilla/5.0'})).read().decode()
    t = re.sub(r'<script.*?</script>|<style.*?</style>|<!--.*?-->', '', h, flags=re.S)
    txt = html.unescape(re.sub(r'<[^>]+>', ' ', t))
    glued = set(re.findall(r'\b\w*[а-яё][А-ЯЁ]\w*\b', txt)) - {'ЮгТойз'}
    doubled = {m.group(0) for m in re.finditer(r'\b(\w{3,}) \1\b', txt)}
    print(u, sorted(glued), sorted(doubled))
