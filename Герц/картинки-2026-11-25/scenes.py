# Иллюстрации услуг 1200x800 и комплектов: плоский стиль, палитра сайта
import json
from pathlib import Path

B='#0065FF'; D='#0B2A66'; L='#DDE9FF'; M='#9DC0FF'; W='#FFFFFF'; G='#F3F7FF'
def bg(body, w=1200, h=800):
    return f'''<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {w} {h}" width="{w}" height="{h}">
<defs><linearGradient id="g" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#EAF2FF"/><stop offset="1" stop-color="#FFFFFF"/></linearGradient>
<filter id="s" x="-20%" y="-20%" width="140%" height="140%"><feDropShadow dx="0" dy="14" stdDeviation="16" flood-color="#0B2A66" flood-opacity=".16"/></filter></defs>
<rect width="{w}" height="{h}" fill="url(#g)"/>
<circle cx="{w*0.78}" cy="{h*0.42}" r="{h*0.42}" fill="{L}" opacity=".55"/><circle cx="{w*0.1}" cy="{h*0.12}" r="{h*0.16}" fill="{L}" opacity=".45"/>
<g stroke-linecap="round" stroke-linejoin="round">{body}</g></svg>'''
S={}
# стена + домофон: вызывная панель и монитор
wall=f'<rect x="560" y="70" width="560" height="560" rx="28" fill="{W}" filter="url(#s)"/><rect x="560" y="560" width="560" height="70" rx="0" fill="{G}"/>'
panel=lambda x,y,s=1: f'''<g transform="translate({x} {y}) scale({s})"><rect width="130" height="250" rx="22" fill="{D}"/><rect x="12" y="12" width="106" height="226" rx="14" fill="#1B3F8F"/>
<circle cx="65" cy="58" r="24" fill="{D}"/><circle cx="65" cy="58" r="12" fill="{B}"/><circle cx="60" cy="53" r="4" fill="{W}"/>
<g stroke="{M}" stroke-width="5"><path d="M38 110 H92 M38 124 H92 M38 138 H92"/></g><circle cx="65" cy="192" r="20" fill="{B}"/><path d="M57 192 l6 6 l11 -12" stroke="{W}" stroke-width="5" fill="none"/></g>'''
monitor=lambda x,y,s=1: f'''<g transform="translate({x} {y}) scale({s})"><rect width="280" height="190" rx="18" fill="{D}"/><rect x="14" y="14" width="190" height="162" rx="8" fill="{B}"/>
<circle cx="109" cy="80" r="28" fill="{M}"/><path d="M60 176 q49 -76 98 0" fill="{M}"/><g fill="{M}"><circle cx="242" cy="50" r="12"/><circle cx="242" cy="95" r="12"/><circle cx="242" cy="140" r="12"/></g></g>'''
S['domofon']=bg(wall+panel(640,190)+monitor(800,240)+f'<path d="M705 440 V500 Q705 520 725 520 H930 Q950 520 950 500 V430" stroke="{B}" stroke-width="6" stroke-dasharray="4 16" fill="none"/>'
 +f'<g transform="translate(330 250) rotate(-30)"><rect width="46" height="150" rx="16" fill="{B}"/><rect x="18" y="150" width="10" height="120" fill="{M}"/><path d="M18 270 h10 l-5 18z" fill="{D}"/></g>')
# видеонаблюдение: камеры на углу здания + сектор обзора + регистратор
cam=lambda x,y,r=0: f'''<g transform="translate({x} {y}) rotate({r})"><rect x="-10" y="-60" width="20" height="60" fill="{D}"/><rect x="-30" y="-10" width="60" height="24" rx="6" fill="{D}"/>
<rect x="-20" y="10" width="190" height="80" rx="20" fill="{W}" stroke="{D}" stroke-width="6"/><rect x="150" y="0" width="40" height="100" rx="12" fill="{D}"/><circle cx="185" cy="50" r="22" fill="{B}"/><circle cx="185" cy="50" r="9" fill="{D}"/><circle cx="20" cy="50" r="7" fill="{B}"/></g>'''
S['video']=bg(f'<path d="M780 275 L1200 100 L1200 450 Z" fill="{B}" opacity=".13"/>'
 +f'<rect x="420" y="80" width="200" height="560" rx="20" fill="{W}" filter="url(#s)"/><g fill="{L}"><rect x="450" y="300" width="60" height="70" rx="8"/><rect x="530" y="300" width="60" height="70" rx="8"/><rect x="450" y="400" width="60" height="70" rx="8"/><rect x="530" y="400" width="60" height="70" rx="8"/></g>'
 +cam(600,200,8)+f'<g transform="translate(820 470)"><rect width="300" height="200" rx="18" fill="{D}" filter="url(#s)"/><g fill="{B}"><rect x="16" y="16" width="128" height="78" rx="6"/><rect x="156" y="16" width="128" height="78" rx="6" opacity=".7"/><rect x="16" y="106" width="128" height="78" rx="6" opacity=".7"/><rect x="156" y="106" width="128" height="78" rx="6"/></g><circle cx="262" cy="34" r="7" fill="#FF4D4F"/></g>')
# СКД: дверь, электромагнитный замок, считыватель, карта
door=f'''<rect x="620" y="60" width="320" height="600" rx="10" fill="{W}" filter="url(#s)"/><rect x="650" y="110" width="260" height="520" rx="8" fill="{G}" stroke="{L}" stroke-width="6"/>
<rect x="650" y="64" width="260" height="34" rx="6" fill="{D}"/><rect x="700" y="98" width="160" height="14" rx="4" fill="{M}"/><path d="M880 360 h-40" stroke="{D}" stroke-width="14"/>'''
reader=lambda x,y: f'''<g transform="translate({x} {y})"><rect width="110" height="180" rx="18" fill="{D}"/><rect x="12" y="12" width="86" height="156" rx="12" fill="#1B3F8F"/>
<g fill="none" stroke="{M}" stroke-width="6"><path d="M30 70 a30 30 0 0 1 50 0"/><path d="M40 86 a18 18 0 0 1 30 0"/></g><circle cx="55" cy="140" r="9" fill="#2BD47D"/></g>'''
card=lambda x,y,r: f'<g transform="translate({x} {y}) rotate({r})"><rect width="170" height="110" rx="14" fill="{B}" filter="url(#s)"/><rect x="20" y="24" width="40" height="30" rx="6" fill="{M}"/><path d="M20 80 H130" stroke="{W}" stroke-width="8" opacity=".8"/></g>'
S['skd']=bg(door+reader(980,300)+card(960,150,-18)+f'<g fill="none" stroke="{B}" stroke-width="6"><path d="M1060 240 a40 40 0 0 1 30 30"/><path d="M1070 210 a70 70 0 0 1 50 50"/></g>'
 +f'<g transform="translate(980 520)"><rect width="110" height="110" rx="14" fill="{W}" stroke="{D}" stroke-width="6"/><circle cx="55" cy="55" r="28" fill="{B}"/><path d="M42 55 h26" stroke="{W}" stroke-width="7"/></g>')
# аудиотрубка: настенная трубка + провод
S['trubka']=bg(wall+f'''<g transform="translate(700 140)"><rect width="200" height="400" rx="30" fill="{W}" stroke="{D}" stroke-width="8"/>
<rect x="30" y="30" width="140" height="340" rx="70" fill="{B}"/><g fill="{D}" opacity=".5"><circle cx="85" cy="80" r="6"/><circle cx="115" cy="80" r="6"/><circle cx="100" cy="100" r="6"/><circle cx="85" cy="300" r="6"/><circle cx="115" cy="300" r="6"/></g>
<rect x="220" y="150" width="70" height="110" rx="12" fill="{D}"/><circle cx="255" cy="185" r="12" fill="{B}"/><circle cx="255" cy="228" r="12" fill="{M}"/></g>
<path d="M800 540 q-20 20 0 40 q20 20 0 40 q-20 20 0 40" stroke="{D}" stroke-width="8" fill="none"/>'''
 +f'<g fill="none" stroke="{B}" stroke-width="8"><path d="M640 300 q-30 40 0 80"/><path d="M600 270 q-50 70 0 140"/></g>')
# обслуживание: шестерня + ключ + щит
gear=lambda x,y,r,c: ''.join([f'<rect x="{x-r*0.18}" y="{y-r*1.18}" width="{r*0.36}" height="{r*0.4}" rx="6" fill="{c}" transform="rotate({a} {x} {y})"/>' for a in range(0,360,45)])+f'<circle cx="{x}" cy="{y}" r="{r}" fill="{c}"/><circle cx="{x}" cy="{y}" r="{r*0.42}" fill="{G}"/>'
wrench=lambda x,y,r,s=1: f'<g transform="translate({x} {y}) rotate({r}) scale({s})"><rect x="-22" y="0" width="44" height="300" rx="22" fill="{D}"/><circle cx="0" cy="0" r="58" fill="{D}"/><rect x="-24" y="-70" width="48" height="76" fill="#EEF4FF"/></g>'
shield=lambda x,y,s=1: f'<g transform="translate({x} {y}) scale({s})"><path d="M0 0 L120 -40 L240 0 V120 Q240 240 120 290 Q0 240 0 120 Z" fill="{B}" filter="url(#s)"/><path d="M70 120 l40 40 l70 -80" fill="none" stroke="{W}" stroke-width="22"/></g>'
S['obsluzhivanie']=bg(gear(760,300,130,M)+gear(980,470,90,L)+wrench(900,260,40)+shield(640,380,0.9))
S['servis']=bg(f'''<g transform="translate(800 330)"><circle r="210" fill="{W}" filter="url(#s)"/><circle r="180" fill="none" stroke="{L}" stroke-width="14"/>
{''.join(f'<rect x="-5" y="-170" width="10" height="26" rx="4" fill="{D}" transform="rotate({a})"/>' for a in range(0,360,30))}
<path d="M0 0 V-110 M0 0 L80 50" stroke="{B}" stroke-width="16"/><circle r="16" fill="{D}"/></g>
<g transform="translate(560 420)"><rect width="190" height="120" rx="22" fill="{B}" filter="url(#s)"/><text x="95" y="80" text-anchor="middle" font-family="Open Sans,Arial" font-weight="800" font-size="56" fill="{W}">24/7</text></g>'''
 +wrench(1050,520,-35,0.6))
# анализ проекта: документы, график, лупа
doc=lambda x,y,r=0: f'<g transform="translate({x} {y}) rotate({r})"><rect width="300" height="390" rx="20" fill="{W}" filter="url(#s)"/><g stroke="{L}" stroke-width="12"><path d="M40 60 H200 M40 100 H260 M40 140 H230"/></g>'
S['analiz']=bg(doc(560,140,-8)+'</g>'+doc(700,110,4)+f'<rect x="40" y="190" width="220" height="150" rx="12" fill="{G}"/><path d="M60 310 L110 260 L150 285 L230 210" fill="none" stroke="{B}" stroke-width="12"/><g fill="{B}"><circle cx="110" cy="260" r="10"/><circle cx="150" cy="285" r="10"/><circle cx="230" cy="210" r="10"/></g></g>'
 +f'<g transform="translate(960 430)"><circle r="95" fill="{W}" fill-opacity=".6" stroke="{D}" stroke-width="22"/><rect x="60" y="-18" width="150" height="40" rx="20" fill="{D}" transform="rotate(45)"/></g>')
# комплектация: коробка + чек-лист
box=lambda x,y,s=1: f'''<g transform="translate({x} {y}) scale({s})"><path d="M0 80 L180 0 L360 80 L180 160 Z" fill="{M}"/><path d="M0 80 V280 L180 360 V160 Z" fill="{B}"/><path d="M360 80 V280 L180 360 V160 Z" fill="{D}"/>
<path d="M90 40 L270 120 V170" stroke="{W}" stroke-width="18" fill="none" opacity=".75"/></g>'''
check=lambda x,y,n=4: f'<g transform="translate({x} {y})"><rect width="260" height="{90+n*70}" rx="20" fill="{W}" filter="url(#s)"/><rect x="80" y="-18" width="100" height="40" rx="12" fill="{D}"/>'+''.join(f'<rect x="34" y="{60+i*70}" width="38" height="38" rx="8" fill="{B}"/><path d="M43 {79+i*70} l8 8 l14 -16" stroke="{W}" stroke-width="7" fill="none"/><rect x="90" y="{72+i*70}" width="{130-i%2*30}" height="14" rx="7" fill="{L}"/>' for i in range(n))+'</g>'
S['komplektaciya']=bg(box(560,250)+check(900,120))
# консультация: облака диалога + гарнитура
S['konsult']=bg(f'''<g filter="url(#s)"><path d="M560 120 h340 a30 30 0 0 1 30 30 v170 a30 30 0 0 1 -30 30 h-240 l-70 60 v-60 h-30 a30 30 0 0 1 -30 -30 v-170 a30 30 0 0 1 30 -30z" fill="{B}"/>
<path d="M780 330 h310 a30 30 0 0 1 30 30 v150 a30 30 0 0 1 -30 30 h-30 v60 l-70 -60 h-210 a30 30 0 0 1 -30 -30 v-150 a30 30 0 0 1 30 -30z" fill="{W}"/></g>
<g fill="{W}"><circle cx="660" cy="235" r="18"/><circle cx="730" cy="235" r="18"/><circle cx="800" cy="235" r="18"/></g>
<g stroke="{L}" stroke-width="16"><path d="M830 400 H1060 M830 445 H1000 M830 490 H1040"/></g>
<g transform="translate(1000 140)"><path d="M0 90 a70 70 0 0 1 140 0" fill="none" stroke="{D}" stroke-width="14"/><rect x="-14" y="80" width="40" height="70" rx="14" fill="{D}"/><rect x="114" y="80" width="40" height="70" rx="14" fill="{D}"/><path d="M134 150 q0 50 -60 50" fill="none" stroke="{D}" stroke-width="8"/><circle cx="70" cy="200" r="12" fill="{B}"/></g>''')
# поставка: грузовик + коробки
truck=f'''<g transform="translate(560 300)"><rect width="380" height="230" rx="16" fill="{B}" filter="url(#s)"/><path d="M380 70 h110 l70 80 v80 h-180z" fill="{D}"/><path d="M400 90 h80 l45 55 h-125z" fill="{M}"/>
<rect x="30" y="30" width="150" height="30" rx="10" fill="{W}" opacity=".85"/><g><circle cx="100" cy="240" r="46" fill="{D}"/><circle cx="100" cy="240" r="18" fill="{L}"/><circle cx="460" cy="240" r="46" fill="{D}"/><circle cx="460" cy="240" r="18" fill="{L}"/></g></g>'''
cube=lambda x,y,s: f'<g transform="translate({x} {y}) scale({s})"><rect width="120" height="110" rx="8" fill="#C9A26B"/><rect y="0" width="120" height="24" rx="6" fill="#B38A52"/><rect x="48" width="24" height="110" fill="#E3C48F"/></g>'
S['postavka']=bg('<rect x="930" y="610" width="260" height="26" rx="6" fill="#B38A52"/><rect x="950" y="636" width="30" height="20" fill="#8F6B3A"/><rect x="1045" y="636" width="30" height="20" fill="#8F6B3A"/><rect x="1140" y="636" width="30" height="20" fill="#8F6B3A"/>'+cube(940,500,1)+cube(1065,500,1)+cube(1002,390,1)+truck.replace('translate(560 300)','translate(370 320)'))
# проектирование: план помещения с камерами и зонами обзора
S['proekt']=bg(f'''<g transform="translate(540 90) rotate(-4)"><rect width="580" height="460" rx="18" fill="#1550D8" filter="url(#s)"/>
<g stroke="{W}" stroke-width="3" opacity=".12">{''.join(f'<path d="M{x} 0 V460"/>' for x in range(40,580,40))}{''.join(f'<path d="M0 {y} H580"/>' for y in range(40,460,40))}</g>
<g fill="none" stroke="{W}" stroke-width="10"><path d="M50 50 H530 V410 H50 Z"/><path d="M290 50 V230 H530 M290 300 V410 M50 250 H200"/></g>
<g fill="{W}" opacity=".25"><path d="M60 60 L230 120 L170 230 Z"/><path d="M520 240 L360 300 L420 400 Z"/></g>
<g fill="{W}"><circle cx="62" cy="62" r="16"/><circle cx="518" cy="244" r="16"/></g><path d="M330 160 h80 v-40" stroke="#FFD24D" stroke-width="10" fill="none"/></g>
<g transform="translate(1010 520) rotate(-40)"><rect width="34" height="230" rx="6" fill="#FFD24D"/><path d="M0 230 L17 280 L34 230Z" fill="#F2C9A0"/><rect width="34" height="30" rx="6" fill="{D}"/></g>
<g transform="translate(500 560) rotate(-4)"><rect width="320" height="60" rx="8" fill="{M}"/>{''.join(f'<path d="M{x} 0 V{26 if x%40==20 else 16}" stroke="{D}" stroke-width="4"/>' for x in range(20,320,20))}</g>''')
# комплекты СКД (белый фон, без плашек)
def kit(keypad, w=1000, h=892):
    rd = (f'''<g transform="translate(110 170)"><rect width="200" height="400" rx="30" fill="{D}" filter="url(#s)"/><rect x="20" y="20" width="160" height="360" rx="20" fill="#1B3F8F"/>'''
      + (''.join(f'<circle cx="{60+40*(i%3)}" cy="{110+60*(i//3)}" r="18" fill="{M}"/>' for i in range(12)) if keypad else
         f'<g fill="none" stroke="{M}" stroke-width="12"><path d="M50 170 a70 70 0 0 1 100 0"/><path d="M72 200 a40 40 0 0 1 56 0"/></g>')
      + '<circle cx="100" cy="345" r="12" fill="#2BD47D"/></g>')
    lock='''<g transform="translate(400 150)"><rect width="500" height="110" rx="16" fill="#C8D1DE" filter="url(#s)"/><rect x="0" y="0" width="500" height="34" rx="16" fill="#AEB9C9"/><rect x="30" y="140" width="440" height="56" rx="12" fill="#C8D1DE" filter="url(#s)"/><circle cx="470" cy="70" r="10" fill="#FF4D4F"/></g>'''
    btn=f'''<g transform="translate(470 450)"><rect width="230" height="230" rx="26" fill="{W}" stroke="#D5DCE8" stroke-width="6" filter="url(#s)"/><circle cx="115" cy="115" r="68" fill="{B}"/><text x="115" y="128" text-anchor="middle" font-family="Open Sans,Arial" font-weight="800" font-size="38" fill="{W}">EXIT</text></g>'''
    fob=f'''<g transform="translate(760 470)"><rect width="120" height="190" rx="60" fill="{B}" filter="url(#s)"/><circle cx="60" cy="40" r="18" fill="{W}"/><rect x="30" y="100" width="60" height="50" rx="12" fill="{M}"/></g>'''
    return f'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {w} {h}" width="{w}" height="{h}"><defs><filter id="s" x="-20%" y="-20%" width="140%" height="140%"><feDropShadow dx="0" dy="12" stdDeviation="14" flood-color="#0B2A66" flood-opacity=".18"/></filter></defs><rect width="{w}" height="{h}" fill="{W}"/><g stroke-linecap="round">{rd}{lock}{btn}{fob}</g></svg>'
K={'kit-reader':kit(False),'kit-keypad':kit(True)}


items={k:{'svg':v,'w':1200,'h':800} for k,v in S.items()}
items.update({k:{'svg':v,'w':1000,'h':892} for k,v in K.items()})
Path('scenes_items.json').write_text(json.dumps(items))
print(len(items))
