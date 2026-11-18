// Скриншот живой страницы сайта в новой версии (?sk_preview=1) с подмешанным локальным CSS — без заливки на сервер.
// Нужен Chrome с портом отладки:
//   "/Applications/Google Chrome.app/Contents/MacOS/Google Chrome" --headless=new --disable-gpu --user-data-dir=/tmp/sk-chrome2 --remote-debugging-port=9333 about:blank &
// Запуск: node shot.js <путь, напр. /o-kompanii/> <ширина> <out.png> [css-файл] [--full] [--js "выражение"]
//   css-файл подменяет залитый /sk/css/sk-inner.css на его же месте в каскаде (если его ещё нет на сайте — добавляется в <head>).
//   --full — вся страница целиком; --js — выполнить выражение и напечатать результат (JSON).
const fs = require('fs');
const args = process.argv.slice(2);
const [path, w, out] = args;
const cssFile = args[3] && !args[3].startsWith('--') ? args[3] : '';
const full = args.includes('--full');
const jsIdx = args.indexOf('--js');
const js = jsIdx > -1 ? args[jsIdx + 1] : '';
const url = 'https://stroy-krovlya26.ru' + path + (path.includes('?') ? '&' : '?') + 'sk_preview=1';
(async () => {
	const tab = await (await fetch('http://127.0.0.1:9333/json/new?about:blank', { method: 'PUT' })).json();
	const ws = new WebSocket(tab.webSocketDebuggerUrl);
	let id = 0; const wait = {};
	const send = (method, params = {}) => new Promise(r => { const i = ++id; wait[i] = r; ws.send(JSON.stringify({ id: i, method, params })); });
	ws.onmessage = e => { const m = JSON.parse(e.data); if (m.id && wait[m.id]) { wait[m.id](m.result || m); delete wait[m.id]; } };
	await new Promise(r => ws.onopen = r);
	const width = +w;
	await send('Emulation.setDeviceMetricsOverride', { width, height: width < 768 ? 844 : 900, deviceScaleFactor: 1, mobile: width < 768 });
	await send('Page.enable');
	await send('Runtime.enable');
	// скрыть cookie-баннер и чат, чтобы не закрывали контент
	await send('Page.addScriptToEvaluateOnNewDocument', { source: "document.addEventListener('DOMContentLoaded',()=>{const s=document.createElement('style');s.textContent='.cookie-consent,#cookie-consent,[class*=cookie],jdiv,.grecaptcha-badge{display:none!important}';document.head.appendChild(s);});" });
	await send('Page.navigate', { url });
	await new Promise(r => setTimeout(r, 6000));
	if (cssFile) {
		const css = fs.readFileSync(cssFile, 'utf8');
		await send('Runtime.evaluate', { expression: `(() => { const s = document.createElement('style'); s.id = 'sk-local'; s.textContent = ${JSON.stringify(css)}; const ls = document.querySelectorAll('link[href*="sk-inner.css"]'); if (ls.length) { ls[0].replaceWith(s); ls.forEach((l, i) => i && l.remove()); } else document.head.appendChild(s); setTimeout(() => window.dispatchEvent(new Event('resize')), 50); })()` });
		await new Promise(r => setTimeout(r, 1200)); // resize — чтобы owl-карусели пересчитали ширину под новый CSS
	}
	if (js) {
		const res = await send('Runtime.evaluate', { expression: js, returnByValue: true, awaitPromise: true });
		console.log(JSON.stringify(res.result ? res.result.value : res, null, 1));
	}
	if (out) {
		let clip;
		if (full) {
			const m = await send('Page.getLayoutMetrics');
			const h = Math.min(Math.ceil(m.cssContentSize.height), 12000);
			clip = { x: 0, y: 0, width, height: h, scale: 1 };
		}
		const s = await send('Page.captureScreenshot', Object.assign({ format: 'png' }, full ? { clip, captureBeyondViewport: true } : {}));
		fs.writeFileSync(out, Buffer.from(s.data, 'base64'));
	}
	await fetch('http://127.0.0.1:9333/json/close/' + tab.id);
	process.exit(0);
})().catch(e => { console.error(e); process.exit(1); });
