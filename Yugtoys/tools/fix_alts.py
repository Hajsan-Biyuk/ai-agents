"""С-2: заменить alt="alt" у картинок шаблона на осмысленные подписи (иконка без текста)
или alt="" (декоративная, рядом есть текст). Таблица по имени файла картинки.
python3 fix_alts.py file.php [...]  — правит на месте, печатает замены."""
import re
import sys

ALT = {
    'logo.svg': 'Юг Тойз — оптовая компания',
    'wp.svg': 'WhatsApp', 'wp2.svg': 'WhatsApp', 'whatsapp.svg': 'WhatsApp',
    'tg.svg': 'Telegram', 'telegram.svg': 'Telegram', 'vb.svg': 'Viber', 'max.svg': 'MAX',
    'favorites.svg': 'Избранное', 'basket.svg': 'Корзина', 'search.svg': 'Поиск', 'search2.svg': 'Поиск',
    'copy.svg': 'Sheer — разработка сайта',
    'sertificat_avd1.jpg': 'Сертификат соответствия',
    # декоративные: рядом есть текст или это фон
    'flash.svg': '', 'ic_avd1.svg': '', 'ic_avd2.svg': '', 'ic_avd3.svg': '', 'ic_avd4.svg': '',
    'ic_benefit1.svg': '', 'ic_benefit2.svg': '', 'ic_benefit3.svg': '',
    'img_subscribe1.svg': '', 'img_subscribe2.svg': '', 'toy1.png': '', 'clip.svg': '',
}

for path in sys.argv[1:]:
    src = open(path, encoding='utf-8', newline='').read()
    lines = src.splitlines(keepends=True)
    n = 0
    for i, line in enumerate(lines):
        def fix(m):
            global n
            tag = m.group(0)
            f = re.search(r'/img/([\w.-]+)"', tag)
            if not f or 'alt="alt"' not in tag:
                return tag
            name = f.group(1)
            if name == 'user.svg':  # с текстом рядом — декоративная, только иконка (моб.) — подпись
                alt = 'Личный кабинет' if 'btn-user-mobile' in line else ''
            elif name in ALT:
                alt = ALT[name]
            else:
                print('  ПРОПУСК (нет в таблице):', name, path)
                return tag
            n += 1
            return tag.replace('alt="alt"', f'alt="{alt}"')
        lines[i] = re.sub(r'<img(?:\?>|[^>])*>', fix, line)  # ?> внутри src="<?=...?>" — не конец тега
    out = ''.join(lines)
    if out != src:
        open(path, 'w', encoding='utf-8', newline='').write(out)
    print(n, path)
