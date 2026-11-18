// Цвет текста кнопок в покое и при наведении (CSS.forcePseudoState) на живой странице с ?sk_preview=1 и локальным sk-inner.css.
// node hover.js <путь> <css-файл> "<селектор>"
const fs = require('fs');
const [path, cssFile, sel] = process.argv.slice(2);
const url = 'https://stroy-krovlya26.ru' + path + (path.includes('?') ? '&' : '?') + 'sk_preview=1';
(async () => {
	const tab = await (await fetch('http://127.0.0.1:9333/json/new?about:blank', { method: 'PUT' })).json();
	const ws = new WebSocket(tab.webSocketDebuggerUrl);
	let id = 0; const wait = {};
	const send = (method, params = {}) => new Promise(r => { const i = ++id; wait[i] = r; ws.send(JSON.stringify({ id: i, method, params })); });
	ws.onmessage = e => { const m = JSON.parse(e.data); if (m.id && wait[m.id]) { wait[m.id](m.result || m); delete wait[m.id]; } };
	await new Promise(r => ws.onopen = r);
	await send('Emulation.setDeviceMetricsOverride', { width: 1440, height: 900, deviceScaleFactor: 1, mobile: false });
	await send('Page.enable'); await send('DOM.enable'); await send('CSS.enable');
	await send('Page.navigate', { url });
	await new Promise(r => setTimeout(r, 6000));
	const css = fs.readFileSync(cssFile, 'utf8');
	await send('Runtime.evaluate', { expression: `(() => { const s = document.createElement('style'); s.textContent = ${JSON.stringify(css)}; const l = document.querySelector('link[href*="sk-inner.css"]'); l ? l.replaceWith(s) : document.head.appendChild(s); })()` });
	await new Promise(r => setTimeout(r, 500));
	const doc = await send('DOM.getDocument', { depth: -1 });
	const q = await send('DOM.querySelectorAll', { nodeId: doc.root.nodeId, selector: sel });
	const color = `[...document.querySelectorAll(${JSON.stringify(sel)})].map(e => { const t = e.querySelector('span') || e; return e.tagName + ':' + (e.textContent || e.value || '').trim().slice(0, 20) + ' text=' + getComputedStyle(t).color + ' bg=' + getComputedStyle(e).backgroundImage.slice(0, 30); })`;
	const rest = await send('Runtime.evaluate', { expression: color, returnByValue: true });
	for (const n of q.nodeIds) await send('CSS.forcePseudoState', { nodeId: n, forcedPseudoClasses: ['hover'] });
	await new Promise(r => setTimeout(r, 300));
	const hov = await send('Runtime.evaluate', { expression: color, returnByValue: true });
	console.log(JSON.stringify({ rest: rest.result.value, hover: hov.result.value }, null, 1));
	await fetch('http://127.0.0.1:9333/json/close/' + tab.id);
	process.exit(0);
})().catch(e => { console.error(e); process.exit(1); });
