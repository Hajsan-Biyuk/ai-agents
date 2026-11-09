"""Проверка вёрстки: для страниц × ширин ищет вылезания за родителя, пересечения соседей и горизонтальный скролл."""
import json, re, subprocess, sys, html, os, collections

S = '/private/tmp/claude-501/-Users-asanhajretdinov-Documents-Sites-Cursor-sheer/d34a3cee-986b-40a5-a2fd-6976eb8b44ac/scratchpad/qa'
BASE = 'https://specialist.cm17867.tw1.ru'
PAGES = {
    'home': '/', 'tractor': '/tractor-driver/', 'list': '/%d1%83%d1%81%d0%bb%d1%83%d0%b3%d0%b8/',
    'loader': '/uslugi/loader-driver/', 'safety': '/uslugi/safety-and-health/', 'retrain': '/uslugi/professional-retraining/', 'boat': '/uslugi/small-boat-driver/',
    'about': '/%d0%be-%d0%ba%d0%be%d0%bc%d0%bf%d0%b0%d0%bd%d0%b8%d0%b8/', 'contacts': '/%d0%ba%d0%be%d0%bd%d1%82%d0%b0%d0%ba%d1%82%d1%8b/',
    'gallery': '/%d1%84%d0%be%d1%82%d0%be%d0%b3%d0%b0%d0%bb%d0%b5%d1%80%d0%b5%d1%8f/',
    'tech': '/%d0%bd%d0%b0%d1%88%d0%b0-%d1%82%d0%b5%d1%85%d0%bd%d0%b8%d0%ba%d0%b0-%d0%b8-%d0%bf%d0%be%d0%bb%d0%b8%d0%b3%d0%be%d0%bd-%d0%b4%d0%bb%d1%8f-%d0%b7%d0%b0%d0%bd%d1%8f%d1%82%d0%b8%d0%b9/',
    'sveden': '/%d1%81%d0%b2%d0%b5%d0%b4%d0%b5%d0%bd%d0%b8%d1%8f-%d0%be%d0%b1-%d0%be%d0%b1%d1%80%d0%b0%d0%b7%d0%be%d0%b2%d0%b0%d1%82%d0%b5%d0%bb%d1%8c%d0%bd%d0%be%d0%b9-%d0%be%d1%80%d0%b3%d0%b0%d0%bd%d0%b8%d0%b7/',
    'privacy': '/privacy-policy/', 'thanks': '/spasibo/', 'e404': '/no-such-page/',
}
WIDTHS = [1920, 1600, 1440, 1366, 1280, 1024, 768, 390]

JS = r"""
function sel(e){var s=e.tagName.toLowerCase();if(e.id)s+='#'+e.id;var c=(e.className&&e.className.baseVal===undefined?e.className:'').toString().trim().split(/\s+/).filter(Boolean).slice(0,2);if(c.length)s+='.'+c.join('.');return s;}
function check(d,w){
 var out=[],vw=d.documentElement.clientWidth;
 var skip='.hp-slider__track,.ks-docs--slider,.hp-menu-mobile,.hp-menu-overlay,.hp-hero__fade,.hp-hero__bg,.hp-modal,.hp-vplayer,[hidden],.hp-nav__drop,.wpcf7-form-control-wrap + .wpcf7-not-valid-tip';
 if(d.documentElement.scrollWidth>vw+1) out.push(['page-hscroll','html',d.documentElement.scrollWidth-vw]);
 var all=[].slice.call(d.querySelectorAll('.hp *'));
 all.forEach(function(e){
  var cs=w.getComputedStyle(e); if(cs.display==='none'||cs.position==='fixed'||cs.visibility==='hidden')return;
  if(e.closest(skip))return;
  var r=e.getBoundingClientRect(); if(r.width<1||r.height<1)return;
  var p=e.parentElement,pcs=w.getComputedStyle(p),pr=p.getBoundingClientRect();
  if(cs.position!=='absolute'&&pcs.overflowX==='visible'&&pr.width>0&&!/^(INPUT|SELECT|OPTION|IMG|SVG|PATH|BR)$/.test(e.tagName)){
    var over=Math.max(r.right-pr.right,pr.left-r.left);
    if(over>2)out.push(['out-of-parent',sel(p)+' > '+sel(e),Math.round(over)]);
  }
  if(r.right>vw+1&&cs.position!=='absolute')out.push(['out-of-viewport',sel(p)+' > '+sel(e)+' "'+(e.textContent||'').trim().slice(0,30)+'"',Math.round(r.right-vw)]);
 });
 // пересечения соседей в потоке
 var parents=new Set(all.map(function(e){return e.parentElement}));
 parents.forEach(function(p){
  if(!p||p.closest(skip))return;
  var kids=[].slice.call(p.children).filter(function(k){var c=w.getComputedStyle(k);return c.position!=='absolute'&&c.position!=='fixed'&&c.display!=='none'&&c.display!=='inline'&&!/^(BR|SCRIPT|STYLE|OPTION|INPUT)$/.test(k.tagName)&&k.getBoundingClientRect().width>1&&k.getBoundingClientRect().height>1&&!k.closest('[hidden]')});
  for(var i=0;i<kids.length;i++)for(var j=i+1;j<kids.length;j++){
   var a=kids[i].getBoundingClientRect(),b=kids[j].getBoundingClientRect();
   var ox=Math.min(a.right,b.right)-Math.max(a.left,b.left),oy=Math.min(a.bottom,b.bottom)-Math.max(a.top,b.top);
   if(ox>3&&oy>3)out.push(['overlap',sel(p)+' : '+sel(kids[i])+' x '+sel(kids[j]),Math.round(Math.min(ox,oy))]);
  }
 });
 return out;
}
"""


def run(name, path):
    h = subprocess.run(['curl', '-sk', '-m', '60', BASE + path], capture_output=True, text=True).stdout
    open('%s/p_%s.html' % (S, name), 'w').write(h)
    frames = ''.join('<iframe id="f%d" src="p_%s.html" style="width:%dpx;height:2000px;border:0;display:block"></iframe>' % (w, name, w) for w in WIDTHS)
    wrap = '<html><body style="margin:0">%s<script>%s\nwindow.onload=function(){setTimeout(function(){var res={};%s;var p=document.createElement("pre");p.id="OFF";p.textContent=JSON.stringify(res);document.body.appendChild(p)},4000)}</script></body></html>' % (
        frames, JS, ';'.join('try{var f=document.getElementById("f%d");res[%d]=check(f.contentDocument,f.contentWindow)}catch(e){res[%d]=[["error",String(e),0]]}' % (w, w, w) for w in WIDTHS))
    wp = '%s/w_%s.html' % (S, name)
    open(wp, 'w').write(wrap)
    r = subprocess.run(['/Applications/Google Chrome.app/Contents/MacOS/Google Chrome', '--headless=new', '--ignore-certificate-errors', '--user-data-dir=%s/chr' % S,
                        '--allow-file-access-from-files', '--window-size=2000,1000', '--virtual-time-budget=40000', '--dump-dom', 'file://' + wp], capture_output=True, text=True)
    m = re.search(r'<pre id="OFF">(.*?)</pre>', r.stdout, re.S)
    return json.loads(html.unescape(m.group(1))) if m else None


if __name__ == '__main__':
    only = sys.argv[1:]
    report = {}
    for name, path in PAGES.items():
        if only and name not in only:
            continue
        res = run(name, path)
        report[name] = res
        if res is None:
            print(name, 'NO RESULT')
            continue
        tot = sum(len(v) for v in res.values())
        print(name.ljust(9), 'issues:', tot, {w: len(v) for w, v in res.items() if v})
    json.dump(report, open(S + '/report.json', 'w'), ensure_ascii=False)
