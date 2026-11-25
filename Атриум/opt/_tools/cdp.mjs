// Проверка страницы в локальном headless Chrome: node cdp.mjs URL steps.json
// Шаги: {eval:"js", label}, {mouse:[[x,y],...]}, {click:[x,y]}, {sleep:ms}, {shot:"file.png"}.
import { spawn } from 'node:child_process';
import { writeFileSync, readFileSync, mkdtempSync } from 'node:fs';
import { tmpdir } from 'node:os';
import { join } from 'node:path';
const [url, stepsFile] = process.argv.slice(2);
const W = +(process.env.W || 1900), H = +(process.env.H || 1000), MOB = W < 768; // W=390 H=844 — телефон
const steps = JSON.parse(readFileSync(stepsFile, 'utf8'));
const dir = mkdtempSync(join(tmpdir(), 'cdp-chrome-'));
const chrome = spawn('/Applications/Google Chrome.app/Contents/MacOS/Google Chrome', ['--headless=new', '--remote-debugging-port=9333', `--user-data-dir=${dir}`, '--window-size=1900,1000', '--hide-scrollbars', 'about:blank'], { stdio: 'ignore' });
const sleep = ms => new Promise(r => setTimeout(r, ms));
let ws;
for (let i = 0; i < 40 && !ws; i++) {
	await sleep(250);
	try { const t = await (await fetch('http://127.0.0.1:9333/json')).json(); const p = t.find(x => x.type === 'page'); if (p) ws = new WebSocket(p.webSocketDebuggerUrl); } catch {}
}
await new Promise(r => ws.addEventListener('open', r));
let id = 0; const wait = {};
ws.addEventListener('message', e => { const m = JSON.parse(e.data); if (m.id && wait[m.id]) { wait[m.id](m); delete wait[m.id]; } if (m.method === 'Runtime.exceptionThrown') { const d = m.params.exceptionDetails; console.log('EXC', (d.exception?.description || d.text).split('\n').slice(0, 2).join(' '), d.url || '', d.lineNumber); } });
const send = (method, params = {}) => new Promise(r => { const i = ++id; wait[i] = r; ws.send(JSON.stringify({ id: i, method, params })); });
await send('Page.enable'); await send('Runtime.enable');
if (MOB) { await send('Emulation.setUserAgentOverride', { userAgent: 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1' }); await send('Emulation.setTouchEmulationEnabled', { enabled: true, maxTouchPoints: 5 }); }
await send('Emulation.setDeviceMetricsOverride', { width: W, height: H, deviceScaleFactor: MOB ? 2 : 1, mobile: MOB });
await send('Page.navigate', { url });
await sleep(6000);
for (const s of steps) {
	if (s.eval) { const r = await send('Runtime.evaluate', { expression: s.eval, awaitPromise: true, returnByValue: true }); console.log(s.label || 'eval', JSON.stringify(r.result?.result?.value ?? r.result?.exceptionDetails?.exception?.description)); }
	if (s.mouse) for (const [x, y] of s.mouse) { await send('Input.dispatchMouseEvent', { type: 'mouseMoved', x, y }); await sleep(60); }
	if (s.click) { const [x, y] = s.click; for (const type of ['mouseMoved', 'mousePressed', 'mouseReleased']) await send('Input.dispatchMouseEvent', { type, x, y, button: 'left', clickCount: 1 }); }
	if (s.sleep) await sleep(s.sleep);
	if (s.shot) { const r = await send('Page.captureScreenshot', { format: 'png' }); writeFileSync(s.shot, Buffer.from(r.result.data, 'base64')); console.log('shot', s.shot); }
}
ws.close(); chrome.kill();
