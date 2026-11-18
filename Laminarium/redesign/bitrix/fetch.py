"""Скачать файлы с сайта через бридж в две папки: orig/<дата-метка>/ (бэкап) и bitrix/ (рабочая копия).
Использование: python3 fetch.py <метка-бэкапа> /path/on/site [/path2 ...]"""
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

label = sys.argv[1]
for path in sys.argv[2:]:
    r = subprocess.run(['python3', 'tools/bitrix_bridge_client.py', 'file-get', path], cwd=bridge, env=env,
                       capture_output=True, text=True)
    d = json.loads(r.stdout)
    if not d.get('ok'):
        print(path, 'FAIL', d.get('error'))
        sys.exit(1)
    content = d['data']['content']
    for root in (os.path.join(here, '..', 'orig', label), here):
        dst = os.path.join(root, path.lstrip('/'))
        os.makedirs(os.path.dirname(dst), exist_ok=True)
        with open(dst, 'w', newline='') as f:
            f.write(content)
    print(path, d['data']['hash'][:16], d['data']['modified_at'])
