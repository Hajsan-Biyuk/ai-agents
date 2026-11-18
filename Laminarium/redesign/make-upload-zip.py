"""Архив для загрузки через админку Битрикса: папки /local/templates/.default + шрифты и картинки.
Бридж не создаёт папки и не пишет бинарники, поэтому это заливается вручную (Файлы и папки → Распаковать)."""
import os
import zipfile

here = os.path.dirname(os.path.abspath(__file__))
base = 'local/templates/.default/'
dirs = ['local/', 'local/templates/', base]
for d in ['lam/', 'lam/css/', 'lam/js/', 'lam/fonts/', 'lam/img/']:
    dirs.append(base + d)
for c in ['bitrix/menu/lam_top', 'bitrix/menu/lam_footer', 'bitrix/search.title/lam',
          'bitrix/sale.basket.basket.line/lam', 'bitrix/news.list/lam_adv', 'bitrix/news.list/lam_brands',
          'bitrix/news.list/lam_news', 'bitrix/subscribe.form/lam', 'bitrix/catalog.section/lam',
          'bitrix/catalog.top/lam', 'aspro/tabs.mshop/lam']:
    p = base
    for part in ('components/' + c).split('/'):
        p += part + '/'
        if p not in dirs:
            dirs.append(p)

os.makedirs(os.path.join(here, 'upload'), exist_ok=True)
out = os.path.join(here, 'upload', 'laminarium-lam.zip')
n = 0
with zipfile.ZipFile(out, 'w', zipfile.ZIP_DEFLATED) as z:
    for d in dirs:
        z.writestr(zipfile.ZipInfo(d), '')
    for sub in ('fonts', 'img'):
        src = os.path.join(here, 'html', sub)
        for f in sorted(os.listdir(src)):
            z.write(os.path.join(src, f), f'{base}lam/{sub}/{f}')
            n += 1
print(out, 'папок', len(dirs), 'файлов', n)
