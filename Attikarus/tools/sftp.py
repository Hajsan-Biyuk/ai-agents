import sys, paramiko, os
env=dict(l.strip().split('=',1) for l in open(sys.argv[1]) if '=' in l)
t=paramiko.Transport((env['SFTP_HOST'],22)); t.connect(username=env['SFTP_USER'],password=env['SFTP_PASS']); s=paramiko.SFTPClient.from_transport(t)
cmd,args=sys.argv[2],sys.argv[3:]
def mk(p):
    try: s.stat(p)
    except IOError: s.mkdir(p)
if cmd=='ls':
    for p in args:
        try: print(p, sorted(s.listdir(p)))
        except Exception as e: print(p,'ERR',e)
elif cmd=='get':
    s.get(args[0],args[1]); print('got',args[0])
elif cmd=='put':
    s.put(args[0],args[1]); print('put',args[1])
elif cmd=='putdir':
    mk(args[1])
    for root,dirs,files in os.walk(args[0]):
        rel=os.path.relpath(root,args[0]); rd=args[1] if rel=='.' else args[1]+'/'+rel
        mk(rd)
        for f in files:
            if f=='.DS_Store': continue
            s.put(os.path.join(root,f),rd+'/'+f); print('put',rd+'/'+f)
elif cmd=='rm':
    for p in args: s.remove(p); print('rm',p)
elif cmd=='exists':
    for p in args:
        try: s.stat(p); print('EXISTS',p)
        except IOError: print('NO',p)
