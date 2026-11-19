"""Снимок живой страницы yugtoys.ru с локальными правками: CSS (tools/*.css) и JS-имитацией правок разметки.
python3 preview.py URL out-prefix css-file [js-file]  → out-prefix-1440.png и out-prefix-375.png"""
import os
import subprocess
import tempfile
import sys
import urllib.request

url, out, css = sys.argv[1:4]
js = open(sys.argv[4]).read() if len(sys.argv) > 4 else ''
html = urllib.request.urlopen(urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'})).read().decode('utf-8')
inj = '<style>' + open(css).read() + '</style><script>document.addEventListener("DOMContentLoaded",function(){' + js + '});</script>'
html = html.replace('<head>', '<head><base href="https://www.yugtoys.ru/">', 1).replace('</head>', inj + '</head>', 1)
out = os.path.abspath(out)
src = out + '.html'
open(src, 'w').write(html)
chrome = '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome'
for w, h in ((1440, 5000), (375, 9000)):
    subprocess.run([chrome, '--headless=new', '--disable-gpu', '--hide-scrollbars', '--user-data-dir=' + tempfile.mkdtemp(), f'--window-size={w},{h}',
                    '--virtual-time-budget=8000', f'--screenshot={out}-{w}.png', 'file://' + src],
                   capture_output=True, timeout=120)
    print(f'{out}-{w}.png', os.path.exists(f'{out}-{w}.png'))
