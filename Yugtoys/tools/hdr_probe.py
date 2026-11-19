"""Замер шапки yugtoys: высота и видимые элементы в состоянии default и fixed (с локальным CSS).
python3 hdr_probe.py css-file width  → печатает JSON"""
import os, subprocess, sys, tempfile, urllib.request
css, width = sys.argv[1], int(sys.argv[2])
h = urllib.request.urlopen(urllib.request.Request('https://www.yugtoys.ru/catalog/', headers={'User-Agent': 'Mozilla/5.0'})).read().decode()
js = '''<script>window.addEventListener("load",function(){
var H=document.querySelector(".header"),out={};
function snap(k){var vis=[];H.querySelectorAll(".header-top,.header-middle,.header-bottom,.logo,.location-main,.search-title,.bx-searchtitle,.btn-main,.phones-contacts,.phone-main,.descr-min,.btn-user-mobile,.btn-call,.btn-catalog,.sandwich_menu,.nav").forEach(function(e){var b=e.getBoundingClientRect();if(b.width&&b.height&&getComputedStyle(e).visibility!="hidden")vis.push(e.className.split(" ")[0]+"@"+Math.round(b.left)+","+Math.round(b.top)+" "+Math.round(b.width)+"x"+Math.round(b.height))});out[k]={h:Math.round(H.getBoundingClientRect().height),vis:vis}}
snap("default");H.classList.remove("default");H.classList.add("fixed");H.style.transition="none";H.querySelectorAll("*").forEach(function(e){e.style.transition="none"});snap("fixed");
var p=document.createElement("pre");p.id="HDR";p.textContent=JSON.stringify(out);document.body.appendChild(p)})</script>'''
h = h.replace('<head>', '<head><base href="https://www.yugtoys.ru/">', 1).replace('</head>', '<style>' + open(css).read() + '</style></head>', 1).replace('</body>', js + '</body>', 1)
inner = os.path.join(tempfile.gettempdir(), 'hdr-inner.html'); open(inner, 'w').write(h)
outer = os.path.join(tempfile.gettempdir(), 'hdr-outer.html')
open(outer, 'w').write(f"<html><body style='margin:0'><iframe id=f src='file://{inner}' style='width:{width}px;height:900px;border:0'></iframe>"
                       "<script>var f=document.getElementById('f');f.onload=function(){setTimeout(function(){var p=document.createElement('pre');p.id='OUT';var x=f.contentDocument.getElementById('HDR');p.textContent=x?x.textContent:'none';document.body.appendChild(p)},300)}</script></body></html>")
r = subprocess.run(['/Applications/Google Chrome.app/Contents/MacOS/Google Chrome', '--headless=new', '--disable-gpu', '--allow-file-access-from-files',
                    '--user-data-dir=' + tempfile.mkdtemp(), f'--window-size={max(width, 500)},1000', '--virtual-time-budget=12000', '--dump-dom', 'file://' + outer],
                   capture_output=True, text=True, timeout=120)
import re, json
m = re.search(r'id="OUT">([^<]*)', r.stdout)
print(json.dumps(json.loads(m.group(1).replace('&quot;', '"')), ensure_ascii=False, indent=0) if m and m.group(1) != 'none' else 'нет данных')
