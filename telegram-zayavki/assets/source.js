/**
 * Telegram заявки: запоминает, откуда посетитель пришёл на сайт.
 * tz_src   — первый заход (90 дней), tz_src_s — текущий визит (до закрытия браузера).
 */
(function () {
	function read(name) {
		var m = document.cookie.match('(?:^|; )' + name + '=([^;]*)');
		return m ? m[1] : '';
	}

	function write(name, value, age) {
		document.cookie = name + '=' + encodeURIComponent(JSON.stringify(value)) +
			'; path=/; SameSite=Lax' + (age ? '; max-age=' + age : '');
	}

	var host = location.hostname.replace(/^www\./, '');
	var ref = '';
	try {
		if (document.referrer && new URL(document.referrer).hostname.replace(/^www\./, '') !== host) {
			ref = document.referrer;
		}
	} catch (e) {}

	var marked = /[?&](utm_[a-z]+|yclid|gclid|fbclid|vkclid)=/.test(location.search);
	var touch = { r: ref.slice(0, 300), l: location.href.slice(0, 500) };

	if (ref || marked || !read('tz_src_s')) {
		write('tz_src_s', touch, 0);
	}
	if (!read('tz_src')) {
		write('tz_src', touch, 90 * 86400);
	}
})();
