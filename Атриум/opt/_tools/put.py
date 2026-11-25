"""Залить файл шаблона опта через бридж с проверкой хеша: python3 put.py REL_PATH [EXPECTED_SHA256]
REL_PATH — от /local/templates/aspro_max_opt_site/, файл берётся из Атриум/opt/local/templates/aspro_max_opt_site/.
Без EXPECTED_SHA256 берётся текущий хеш с сервера. Переводы строк сохраняются как есть."""
import hashlib, json, os, re, sys, urllib.error, urllib.request

here = os.path.dirname(os.path.abspath(__file__))
root = os.path.join(here, '..', 'local', 'templates', 'aspro_max_opt_site')
env_path = os.path.join(here, '..', '..', '..', 'bitrix_bridge_cursor_compiled', '.env.atriumceramic-opt')
env = dict(l.strip().split('=', 1) for l in open(env_path) if '=' in l and not l.startswith('#'))
base, key = env['BITRIX_BRIDGE_URL'].rstrip('/'), env['BITRIX_BRIDGE_KEY']
rel = sys.argv[1]
site_path = '/local/templates/aspro_max_opt_site/' + rel


def call(action, payload):
    req = urllib.request.Request(base + '/local/modules/cursor.bitrixbridge/tools/bridge.php?action=' + action,
                                 data=json.dumps(payload, ensure_ascii=False).encode(), method='POST',
                                 headers={'X-Cursor-Bridge-Key': key, 'Content-Type': 'application/json; charset=utf-8'})
    try:
        return urllib.request.urlopen(req, timeout=60).read().decode('utf-8', 'replace')
    except urllib.error.HTTPError as e:
        return 'HTTP %s %s' % (e.code, e.read().decode('utf-8', 'replace')[:300])


# Аспро портит JSON на файлах с <img — хеш достаём регуляркой
exp = sys.argv[2] if len(sys.argv) > 2 else (re.findall(r'"hash": *"([0-9a-f]+)"', call('file.get', {'path': site_path})) or [None])[0]
content = open(os.path.join(root, rel), encoding='utf-8', newline='').read()
raw = call('file.patch', {'path': site_path, 'content': content, 'expected_hash': exp})
local = hashlib.sha256(content.encode('utf-8')).hexdigest()
after = (re.findall(r'"hash_after": *"([0-9a-f]+)"', raw) or ['?'])[0]
op = (re.findall(r'"operation_id": *"([^"]+)"', raw) or [raw[:200]])[0]
print(rel, 'op', op, 'match' if after == local else 'MISMATCH ' + after)
