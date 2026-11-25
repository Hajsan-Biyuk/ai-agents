# Иконки разделов каталога: линейный стиль, фирменный синий
import json
from pathlib import Path

B='#0065FF'; L='#DDE9FF'
def svg(body):
    return f'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 150 150" width="300" height="300"><g fill="none" stroke="{B}" stroke-width="5" stroke-linecap="round" stroke-linejoin="round">{body}</g></svg>'
I={}
I['videonablyudenie']=svg(f'''
<path d="M22 112 V128 M14 128 H40" /><path d="M22 112 Q22 96 40 92 L52 88"/>
<g transform="translate(-8 4) rotate(-18 85 62)"><rect x="44" y="44" width="78" height="38" rx="9" fill="{L}"/><path d="M122 50 L136 44 V82 L122 76"/><circle cx="62" cy="63" r="8"/><path d="M40 40 H122"/></g>
<circle cx="110" cy="40" r="3" fill="{B}" stroke="none"/>''')
I['akkumulyatory']=svg(f'''
<rect x="30" y="38" width="80" height="86" rx="10" fill="{L}"/><path d="M50 38 V28 H90 V38"/>
<path d="M74 54 L58 84 H76 L66 108 L88 74 H70 L80 54 Z" fill="#fff"/>
''')
I['domofony']=svg(f'''
<rect x="40" y="18" width="70" height="114" rx="10" fill="{L}"/>
<circle cx="75" cy="44" r="11" fill="#fff"/><circle cx="75" cy="44" r="4" fill="{B}" stroke="none"/>
<path d="M58 70 H92 M58 79 H92 M58 88 H92" stroke-width="4"/><circle cx="75" cy="111" r="9" fill="#fff"/>''')
I['bloki-pitaniya']=svg(f'''
<rect x="24" y="40" width="102" height="74" rx="9" fill="{L}"/>
<path d="M42 66 q7 -10 14 0 t14 0" /><path d="M86 62 H108 M86 72 H94 M100 72 H108"/>
<path d="M44 92 H106" stroke-width="4" stroke-dasharray="6 7"/>
<path d="M60 114 V130 M90 114 V130"/>''')
I['instrument']=svg(f'''
<rect x="62" y="20" width="22" height="104" rx="9" transform="rotate(45 75 75)" fill="{L}"/>
<circle cx="106" cy="44" r="19" fill="{L}"/><path d="M106 33 l9.5 5.5 v11 l-9.5 5.5 l-9.5 -5.5 v-11 z" fill="#fff" stroke-width="4"/>
<circle cx="44" cy="106" r="7" fill="#fff" stroke-width="4"/>
<path d="M98 98 L64 64" stroke-width="6"/><path d="M64 64 L56 56" stroke-width="3"/>
<rect x="96" y="88" width="20" height="40" rx="8" transform="rotate(-45 106 108)" fill="#fff"/>''')
I['kontrol-dostupa']=svg(f'''
<rect x="28" y="22" width="56" height="106" rx="10" fill="{L}"/><circle cx="56" cy="104" r="7"/>
<path d="M44 46 a16 16 0 0 1 24 0 M48 54 a10 10 0 0 1 16 0" stroke-width="4"/>
<g transform="rotate(14 104 82)"><rect x="80" y="56" width="50" height="70" rx="7" fill="#fff"/><path d="M90 70 H118 M90 80 H110"  stroke-width="4"/><rect x="90" y="96" width="18" height="16" rx="3" fill="{L}" stroke-width="4"/></g>''')
I['kip']=svg(f'''
<rect x="38" y="16" width="74" height="118" rx="12" fill="{L}"/><rect x="50" y="28" width="50" height="30" rx="4" fill="#fff"/>
<path d="M58 48 l8 -10 l8 6 l10 -10" stroke-width="4"/>
<circle cx="75" cy="86" r="16" fill="#fff"/><path d="M75 86 L84 76"/>
<circle cx="56" cy="118" r="4" fill="{B}" stroke="none"/><circle cx="75" cy="118" r="4" fill="{B}" stroke="none"/><circle cx="94" cy="118" r="4" fill="{B}" stroke="none"/>''')
I['montazhnye-materialy']=svg(f'''
<circle cx="66" cy="70" r="44" fill="{L}"/><circle cx="66" cy="70" r="30" fill="#fff"/><circle cx="66" cy="70" r="10"/>
<path d="M66 26 Q120 30 118 80 Q116 112 132 126" />
<path d="M24 128 H70 M30 120 V128 M64 120 V128" stroke-width="4"/>''')
I['opoveshchenie']=svg(f'''
<path d="M26 62 H48 L92 34 V116 L48 88 H26 Z" fill="{L}"/><path d="M48 62 V88"/>
<path d="M104 58 q10 17 0 34 M114 46 q20 29 0 58" />
<path d="M40 88 L48 120 H60 L56 92" />''')
I['ops']=svg(f'''
<path d="M20 44 H130" /><path d="M34 44 Q36 72 75 72 Q114 72 116 44" fill="{L}"/>
<circle cx="75" cy="58" r="5" fill="{B}" stroke="none"/>
<path d="M75 128 c-18 0 -26 -12 -22 -26 c3 -10 12 -14 14 -24 c10 8 8 16 6 20 c6 -2 10 -8 10 -12 c10 10 14 18 12 26 c-2 10 -10 16 -20 16 z" fill="#fff"/>
<path d="M30 84 q-8 10 0 20 M120 84 q8 10 0 20" stroke-width="4"/>''')
I['raznoe']=svg(f'''
<rect x="26" y="26" width="42" height="42" rx="9" fill="{L}"/><rect x="82" y="26" width="42" height="42" rx="9"/>
<rect x="26" y="82" width="42" height="42" rx="9"/><rect x="82" y="82" width="42" height="42" rx="9" fill="{L}"/>''')
I['setevoe']=svg(f'''
<rect x="18" y="58" width="114" height="36" rx="7" fill="{L}"/>
<rect x="30" y="68" width="12" height="12" rx="2" fill="#fff" stroke-width="3.5"/><rect x="48" y="68" width="12" height="12" rx="2" fill="#fff" stroke-width="3.5"/><rect x="66" y="68" width="12" height="12" rx="2" fill="#fff" stroke-width="3.5"/><rect x="84" y="68" width="12" height="12" rx="2" fill="#fff" stroke-width="3.5"/>
<circle cx="114" cy="76" r="3" fill="{B}" stroke="none"/>
<path d="M36 58 V40 H75 V24 M114 58 V40 H75 M54 94 V118 M96 94 V118"/><circle cx="75" cy="20" r="6" fill="#fff"/>
<circle cx="54" cy="124" r="6" fill="#fff"/><circle cx="96" cy="124" r="6" fill="#fff"/>''')
I['tv']=svg(f'''
<rect x="18" y="44" width="114" height="74" rx="9" fill="{L}"/><rect x="30" y="54" width="90" height="54" rx="4" fill="#fff"/>
<path d="M58 30 L75 44 L92 22" /><path d="M56 130 H94 M75 118 V130"/>''')
I['telekom']=svg(f'''
<path d="M40 24 c-12 4 -22 16 -14 38 c10 28 30 50 58 60 c22 8 34 -2 38 -14 l-22 -18 l-14 10 c-14 -6 -26 -18 -32 -32 l10 -14 z" fill="{L}"/>
<path d="M88 28 a34 34 0 0 1 34 34 M88 46 a16 16 0 0 1 16 16" />''')
I['elektrotekhnika']=svg(f'''
<rect x="22" y="22" width="106" height="106" rx="16" fill="{L}"/><circle cx="75" cy="75" r="36" fill="#fff"/>
<circle cx="61" cy="75" r="6" fill="{B}" stroke="none"/><circle cx="89" cy="75" r="6" fill="{B}" stroke="none"/>
<path d="M75 39 V50 M75 100 V111" stroke-width="5"/>''')
Path('icons.json').write_text(json.dumps(I, ensure_ascii=False))
print(len(I))
