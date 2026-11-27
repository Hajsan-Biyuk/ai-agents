# upload rel paths from Attikarus/work/attikarus.ru to site; creates missing dirs
import sys, paramiko, os
env=dict(l.strip().split('=',1) for l in open('Attikarus/.env.sftp') if '=' in l)
t=paramiko.Transport((env['SFTP_HOST'],22)); t.connect(username=env['SFTP_USER'],password=env['SFTP_PASS']); s=paramiko.SFTPClient.from_transport(t)
R='attikarus.ru/public_html'; L='Attikarus/work/attikarus.ru'
def mk(d):
    parts=d.split('/'); cur=''
    for p in parts:
        cur=cur+'/'+p if cur else p
        try: s.stat(cur)
        except IOError: s.mkdir(cur)
for rel in sys.argv[1:]:
    lp=L+rel
    if os.path.isdir(lp):
        for root,_,files in os.walk(lp):
            for f in files:
                if f=='.DS_Store': continue
                r=R+(root[len(L):])+'/'+f; mk(os.path.dirname(r)); s.put(os.path.join(root,f),r)
        print('putdir',rel)
    else:
        mk(os.path.dirname(R+rel)); s.put(lp,R+rel); print('put',rel)
