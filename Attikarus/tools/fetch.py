# fetch files via SFTP: site rel paths -> Attikarus/work/<site>/<path>
import sys, paramiko, os
env=dict(l.strip().split('=',1) for l in open('Attikarus/.env.sftp') if '=' in l)
t=paramiko.Transport((env['SFTP_HOST'],22)); t.connect(username=env['SFTP_USER'],password=env['SFTP_PASS']); s=paramiko.SFTPClient.from_transport(t)
site=sys.argv[1]
for p in sys.argv[2:]:
    lp=f'Attikarus/work/{site}{p}'; os.makedirs(os.path.dirname(lp),exist_ok=True)
    try: s.get(f'{site}/public_html{p}',lp); print('ok',p)
    except Exception as e: print('ERR',p,e)
