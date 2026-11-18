/* Корзина в новой шапке: перерисовка счётчиков по событию OnBasketChange (как BitrixSmallCart). */
function LamSmallCart(params) {
	this.p = params;
}
LamSmallCart.prototype.activate = function () {
	var self = this;
	BX.addCustomEvent(window, 'OnBasketChange', function () { self.refresh(); });
	// Аспро добавляет в корзину/избранное/сравнение через свои ajax-скрипты и OnBasketChange не шлёт — ловим их ответы.
	if (window.jQuery) {
		jQuery(document).ajaxComplete(function (e, xhr, s) {
			if (s && s.url && /ajax\/(item|basket_fly|show_basket_popup)\.php|basket\.basket|compare/i.test(s.url)) {
				clearTimeout(self.timer);
				self.timer = setTimeout(function () { self.refresh(); }, 300);
			}
		});
	}
};
LamSmallCart.prototype.refresh = function () {
	var self = this;
	BX.ajax({
		url: this.p.ajaxPath,
		method: 'POST',
		dataType: 'html',
		data: {
			sessid: BX.bitrix_sessid(),
			siteId: this.p.siteId,
			templateName: this.p.templateName,
			arParams: this.p.arParams
		},
		onsuccess: function (html) {
			var el = BX(self.p.cartId);
			if (el) el.innerHTML = html;
		}
	});
};
