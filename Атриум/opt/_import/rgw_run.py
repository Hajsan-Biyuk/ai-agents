"""Прогон импорта RGW пачками: python3 rgw_run.py DATA.json START COUNT BATCH LOG [PHP-шаблон, по умолчанию rgw_ugolki_import.php]
Каждая пачка — отдельный разовый скрипт через bitrix_bridge_cursor_compiled/tools/run_php_once.py."""
import base64, json, os, subprocess, sys, urllib.parse

here = os.path.dirname(os.path.abspath(__file__))
bridge = os.path.join(here, '..', '..', '..', 'bitrix_bridge_cursor_compiled')
data_file, start, count, size, log = sys.argv[1], int(sys.argv[2]), int(sys.argv[3]), int(sys.argv[4]), sys.argv[5]
rows = json.load(open(data_file))[start:start + count]
tpl = open(os.path.join(here, sys.argv[6] if len(sys.argv) > 6 else 'rgw_ugolki_import.php'), encoding='utf-8').read()


def enc(u):  # кириллица в путях картинок
    p = urllib.parse.urlsplit(u)
    return urllib.parse.urlunsplit((p.scheme, p.netloc, urllib.parse.quote(p.path, safe='/%'), p.query, ''))


for i in range(0, len(rows), size):
    batch = [dict(r, img=[enc(u) for u in r['img']]) for r in rows[i:i + size]]
    php = tpl.replace('__BATCH__', base64.b64encode(json.dumps(batch, ensure_ascii=False).encode()).decode())
    tmp = '/tmp/atr/rgw_batch.php'
    open(tmp, 'w', encoding='utf-8').write(php)
    out = subprocess.run(['python3', 'tools/run_php_once.py', '.env.atriumceramic-opt', 'aspro_max_opt_site', tmp],
                         cwd=bridge, capture_output=True, text=True).stdout
    with open(log, 'a', encoding='utf-8') as f:
        f.write(f'# rows {start + i}..{start + i + len(batch) - 1}\n{out}\n')
    print(f'rows {start + i}..{start + i + len(batch) - 1}:', out.strip().replace('\n', ' | ')[:400], flush=True)
