"""Сравнить файл шаблона опта с сервером: python3 diff.py REL_PATH (от /local/templates/aspro_max_opt_site/)."""
import difflib, json, os, sys, urllib.request
here = os.path.dirname(os.path.abspath(__file__))
env = dict(l.strip().split('=', 1) for l in open(os.path.join(here, '..', '..', '..', 'bitrix_bridge_cursor_compiled', '.env.atriumceramic-opt')) if '=' in l and not l.startswith('#'))
rel = sys.argv[1]
req = urllib.request.Request(env['BITRIX_BRIDGE_URL'].rstrip('/') + '/local/modules/cursor.bitrixbridge/tools/bridge.php?action=file.get', data=json.dumps({'path': '/local/templates/aspro_max_opt_site/' + rel}).encode(), method='POST', headers={'X-Cursor-Bridge-Key': env['BITRIX_BRIDGE_KEY'], 'Content-Type': 'application/json'})
r = json.loads(urllib.request.urlopen(req, timeout=60).read()); srv = r.get('data', r).get('content')
loc = open(os.path.join(here, '..', 'local', 'templates', 'aspro_max_opt_site', rel), encoding='utf-8', newline='').read()
print(''.join(difflib.unified_diff(srv.splitlines(1), loc.splitlines(1), 'server', 'local')) or 'identical')
