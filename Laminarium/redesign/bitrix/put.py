"""Залить файлы из Laminarium/redesign/bitrix на сайт через бридж с --expected-hash и записать в OPS.log.
Использование: python3 put.py "комментарий" /path/on/site [/path2 ...]"""
import hashlib
import json
import os
import subprocess
import sys

here = os.path.dirname(os.path.abspath(__file__))
bridge = os.path.join(here, '..', '..', '..', 'bitrix_bridge_cursor_compiled')
env = dict(os.environ)
for line in open(os.path.join(bridge, '.env.laminarium')):
    if '=' in line and not line.startswith('#'):
        k, v = line.strip().split('=', 1)
        env[k] = v


def cli(*args):
    r = subprocess.run(['python3', 'tools/bitrix_bridge_client.py', *args], cwd=bridge, env=env,
                       capture_output=True, text=True)
    try:
        return json.loads(r.stdout)
    except ValueError:
        return {'ok': False, 'error': r.stdout[-300:] + r.stderr[-300:]}


note = sys.argv[1]
for path in sys.argv[2:]:
    cur = cli('file-get', path)
    h = cur['data']['hash'] if cur.get('ok') else ''
    args = ['file-patch', path, os.path.join(here, path.lstrip('/'))] + (['--expected-hash', h] if h else [])
    local = os.path.join(here, path.lstrip('/'))
    want = hashlib.sha256(open(local, 'rb').read()).hexdigest()
    if want == h:
        print(path, 'без изменений')
        continue
    res = cli(*args)
    data = res.get('data') or {}
    backup = (data.get('backup') or {}).get('backup_id')
    after = cli('file-get', path)
    # ответ file-patch бывает разного формата — успех определяем по хэшу на сервере
    ok = after.get('ok') and after['data']['hash'] == want
    print(path, 'OK' if ok else 'FAIL', backup, '' if ok else res.get('error'))
    with open(os.path.join(here, 'OPS.log'), 'a') as f:
        f.write(f"op=- backup={backup} before={h[:16]} {path} ({note}){'' if ok else ' FAIL'}\n")
    if not ok:
        sys.exit(1)
