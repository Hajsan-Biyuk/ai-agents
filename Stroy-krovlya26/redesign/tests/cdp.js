// Мини-клиент Chrome DevTools: node cdp.js <url> <width> <height> <js-выражение> [screenshot.png]
// Нужен Chrome, запущенный с --remote-debugging-port=9333 (см. tests/README в коммите).
const [, , url, w, h, expr, shot] = process.argv;
const fs = require('fs');
(async () => {
	const tab = await (await fetch('http://127.0.0.1:9333/json/new?about:blank', { method: 'PUT' })).json();
	const ws = new WebSocket(tab.webSocketDebuggerUrl);
	let id = 0; const wait = {};
	const send = (method, params = {}) => new Promise(r => { const i = ++id; wait[i] = r; ws.send(JSON.stringify({ id: i, method, params })); });
	ws.onmessage = e => { const m = JSON.parse(e.data); if (m.id && wait[m.id]) { wait[m.id](m.result || m); delete wait[m.id]; } };
	await new Promise(r => ws.onopen = r);
	await send('Emulation.setDeviceMetricsOverride', { width: +w, height: +h, deviceScaleFactor: 1, mobile: +w < 768 });
	await send('Page.enable');
	await send('Page.navigate', { url });
	await new Promise(r => setTimeout(r, 7000));
	const res = await send('Runtime.evaluate', { expression: expr, returnByValue: true, awaitPromise: true });
	console.log(JSON.stringify(res.result ? res.result.value : res, null, 1));
	if (shot) { const s = await send('Page.captureScreenshot', { format: 'png' }); fs.writeFileSync(shot, Buffer.from(s.data, 'base64')); }
	await fetch('http://127.0.0.1:9333/json/close/' + tab.id);
	process.exit(0);
})();
