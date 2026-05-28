/**
 * JS API корзины
 */

jQuery(function ($)
{
	// На всякий случай, если объект не локализован
	window.SimpleBasket = window.SimpleBasket || {};

	// Демо-метод вывода времени с сервера
	SimpleBasket.getTime = function (callback)
	{
		jQuery.ajax({
			url: SimpleBasket.ajaxurl,
			type: 'POST',
			data: ({
				action: 'getTime',
				nonce: SimpleBasket.nonce
			}),
			success: function (data)
			{
				if (callback) callback(data);
			}
		});
	};

	// Метод выводит содержимое корзины
	SimpleBasket.getData = function (callback)
	{
		jQuery.ajax({
			url: SimpleBasket.ajaxurl,
			type: 'POST',
			data: ({
				action: 'getData',
				nonce: SimpleBasket.nonce
			}),
			success: function (data)
			{
				if (callback) callback(data);
			}
		});
	};

	// Метод добавляет товар в корзину (AJAX, без редиректа)
	SimpleBasket.add = function (id, variation, quo, callback)
	{
		if (!id) return;

		if (typeof variation === 'function') {
			callback = variation;
			variation = 0;
			quo = 1;
		} else if (typeof quo === 'function') {
			callback = quo;
			quo = 1;
		}

		variation = parseInt(variation, 10);
		if (isNaN(variation) || variation < 0) {
			variation = 0;
		}

		quo = parseInt(quo, 10);
		if (isNaN(quo) || quo < 1) {
			quo = 1;
		}

		jQuery.ajax({
			url: SimpleBasket.ajaxurl,
			type: 'POST',
			data: ({
				action: 'add',
				nonce: SimpleBasket.nonce,
				id: id,
				variation: variation,
				quo: quo
			}),
			success: function (data)
			{
				if (callback) callback(data);
			}
		});
	};

	// Метод удаляет товар из корзины (AJAX, без редиректа)
	SimpleBasket.remove = function (cartKey, callback)
	{
		if (!cartKey) return;
		jQuery.ajax({
			url: SimpleBasket.ajaxurl,
			type: 'POST',
			data: ({
				action: 'remove',
				nonce: SimpleBasket.nonce,
				cart_key: cartKey
			}),
			success: function (data)
			{
				if (callback) callback(data);
			}
		});
	};

	// Метод обновляет количество товара в корзине (AJAX, без редиректа)
	SimpleBasket.updateItem = function (cartKey, quantity, callback)
	{
		if (!cartKey) return;
		var quo = parseInt(quantity, 10);
		if (isNaN(quo) || quo < 0) {
			quo = 0;
		}
		jQuery.ajax({
			url: SimpleBasket.ajaxurl,
			type: 'POST',
			data: ({
				action: 'update',
				nonce: SimpleBasket.nonce,
				cart_key: cartKey,
				quo: quo
			}),
			success: function (data)
			{
				if (callback) callback(data);
			}
		});
	};

	/**
	 * Обновляем классы кнопок "Купить" в зависимости от лимита и содержимого корзины.
	 * Если у товара количество в корзине >= лимита, добавляем класс и блокируем кнопку.
	 * Также меняем текст кнопки на текст из админки для состояния "лимит достигнут".
	 */
	SimpleBasket.updateButtonsState = function (basket)
	{
		var limit = parseInt(SimpleBasket.itemLimit || 0, 10) || 0;
		var normalText = SimpleBasket.buyNowCaption || 'Buy';
		var limitText = SimpleBasket.buyNowLimitCaption || 'Added';
		
		if (!basket || !basket.items) {
			// Корзина пуста — снимаем подсветку и возвращаем обычный текст
			$('.simple-basket-buy-now').each(function () {
				var $btn = $(this);
				if (!$btn.data('original-text')) {
					$btn.data('original-text', $btn.text());
				}
				$btn.removeClass('simple-basket-buy-now--limit')
					.prop('disabled', false)
					.text($btn.data('original-text') || normalText);
			});
			return;
		}

		var counts = {};
		$.each(basket.items, function (id, item) {
			if (item && item.SIMPLE_BASKET_QUO) {
				counts[id] = parseInt(item.SIMPLE_BASKET_QUO, 10) || 0;
			}
		});

		$('.simple-basket-buy-now').each(function () {
			var $btn = $(this);
			var id = $btn.data('product-id');
			if (!id) return;

			// Сохраняем оригинальный текст при первом проходе
			if (!$btn.data('original-text')) {
				$btn.data('original-text', $btn.text());
			}

			var quo = counts[id] || 0;
			if (limit > 0 && quo >= limit) {
				// Товар в корзине и достигнут лимит — считаем состояние "заказанным"
				$btn.addClass('simple-basket-buy-now--limit')
					.prop('disabled', false)
					.text(limitText);
			} else {
				$btn.removeClass('simple-basket-buy-now--limit')
					.prop('disabled', false)
					.text($btn.data('original-text') || normalText);
			}
		});
	};

	/**
	 * Подсчёт количества товаров в корзине по объекту, который вернул сервер.
	 * Ожидается структура, соответствующая SimpleBasketOrder в PHP.
	 */
	SimpleBasket.getCountFromBasket = function (basket)
	{
		var total = 0;
		if (!basket || !basket.items) {
			return 0;
		}
		$.each(basket.items, function (id, item) {
			if (item && item.SIMPLE_BASKET_QUO && item.SIMPLE_BASKET_QUO > 0) {
				total += parseInt(item.SIMPLE_BASKET_QUO, 10) || 0;
			}
		});
		return total;
	};

	/**
	 * Форматирование цены как в PHP number_format.
	 */
	SimpleBasket.formatPrice = function (value) {
		var num = parseFloat(value);
		var symbol = SimpleBasket.currencySymbol || '₽';

		if (isNaN(num)) {
			num = 0;
		}

		var parts = (Math.round(num * 100) / 100).toFixed(2).split('.');
		parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ' ');

		return parts.join(',') + symbol;
	};

	/**
	 * Обновление сумм в таблице корзины после AJAX.
	 */
	SimpleBasket.refreshBasketView = function (basket) {
		if (!basket) {
			return;
		}

		if (basket.isEmpty) {
			window.location.reload();
			return;
		}

		var $table = $('.basketForm table.basket');

		if (!$table.length || !basket.rows) {
			return;
		}

		$.each(basket.rows, function (cartKey, rowData) {
			var $row = $table.find('tbody tr[data-product-id="' + cartKey + '"]');

			if (!$row.length) {
				return;
			}

			if (rowData.quo) {
				$row.find('.basket-qty-input').val(rowData.quo);
			}

			if (rowData.summFormatted) {
				$row.find('td.summ')
					.attr('data-value', rowData.summ)
					.text(rowData.summFormatted);
			}
		});

		$table.find('tbody tr[data-product-id]').each(function () {
			var cartKey = $(this).data('product-id');

			if (!basket.rows || !basket.rows[cartKey]) {
				$(this).remove();
			}
		});

		if (basket.totalFormatted) {
			$table.find('tfoot tr').last().find('td.summ')
				.attr('data-value', basket.total)
				.text(basket.totalFormatted);
		}
	};

	SimpleBasket.afterBasketChange = function (basket) {
		SimpleBasket.updateCountView(basket);
		SimpleBasket.updateButtonsState(basket);
		SimpleBasket.refreshBasketView(basket);
		SimpleBasket.updateBasketDataFromDom();
	};

	SimpleBasket.getRowCartKey = function ($row) {
		return $row.attr('data-product-id') || '';
	};

	/**
	 * Обновление DOM-счётчика количества товаров.
	 * Счётчик ожидается в элементах с классом .simple-basket-count
	 */
	SimpleBasket.updateCountView = function (basket)
	{
		var total = SimpleBasket.getCountFromBasket(basket);	
		if(total > 0){	
			$('.simple-basket-count').text(total);
			$('.btn-basket').addClass('not_empty-basket');	
		} else {
			$('.simple-basket-count').text('0');
			$('.btn-basket').removeClass('not_empty-basket');
		}
	};

	/**
	 * Прочитать корзину с сервера и обновить счётчик.
	 */
	SimpleBasket.refreshCount = function ()
	{
		SimpleBasket.getData(function (basket) {
			SimpleBasket.updateCountView(basket);
			SimpleBasket.updateButtonsState(basket);
		});
	};

	/**
	 * Обновить data-basket на блоке #basketForm на основе DOM-таблицы корзины.
	 * Используется, чтобы передать актуальный список заказа во внешние формы.
	 */
	SimpleBasket.updateBasketDataFromDom = function ()
	{
		var $basketFormWrapper = $('#basketForm');
		if (!$basketFormWrapper.length) {
			return;
		}

		var items = [];
		$basketFormWrapper.find('table.basket tbody tr').each(function () {
			var $row = $(this);
			var id = SimpleBasket.getRowCartKey($row);
			if (!id) {
				return;
			}

			var title = $.trim($row.find('td.title').text());

			var quantity = 0;
			var $qtyInput = $row.find('.basket-qty-input, .quantity input[type="number"]');
			if ($qtyInput.length) {
				quantity = parseInt($qtyInput.val(), 10) || 0;
			} else {
				var $quoCell = $row.find('td.quo');
				if ($quoCell.length) {
					var raw = $quoCell.data('value');
					if (typeof raw === 'undefined' || raw === null || raw === '') {
						raw = $quoCell.text();
					}
					quantity = parseInt(raw, 10) || 0;
				}
			}

			var price = null;
			var $priceCell = $row.find('td.price');
			if ($priceCell.length) {
				var rawPrice = $priceCell.data('value');
				if (typeof rawPrice !== 'undefined') {
					price = parseFloat(rawPrice);
					if (isNaN(price)) {
						price = null;
					}
				}
			}

			items.push({
				id: id,
				title: title,
				quantity: quantity,
				price: price
			});
		});

		var payload = {
			items: items
		};

		$basketFormWrapper.attr('data-basket', JSON.stringify(payload));
	};

	/**
	 * Обработчики для quantity (+/-) в таблице корзины.
	 */
	$(document).on('click', '.basketForm .quantity-button', function (e) {
		e.preventDefault();
		var $btn = $(this);
		var $wrapper = $btn.closest('.basket-qty, .quantity');
		var $input = $wrapper.find('input[type="number"]');
		if (!$input.length) {
			return;
		}

		var current = parseInt($input.val(), 10);
		if (isNaN(current)) {
			current = 1;
		}

		var min = parseInt($input.attr('min'), 10);
		if (isNaN(min)) {
			min = 1;
		}

		if ($btn.hasClass('quantity-up')) {
			current += 1;
		} else if ($btn.hasClass('quantity-down')) {
			current -= 1;
		}

		if (current < min) {
			current = min;
		}

		$input.val(current);
		$input.trigger('change');
	});

	$(document).on('change', '.basketForm .basket-qty-input, .basketForm .quantity input[type="number"]', function () {
		var $input = $(this);
		var $row = $input.closest('tr[data-product-id]');
		var cartKey = SimpleBasket.getRowCartKey($row);

		if (!cartKey) {
			SimpleBasket.updateBasketDataFromDom();
			return;
		}

		var qty = parseInt($input.val(), 10);
		if (isNaN(qty) || qty < 1) {
			qty = 1;
			$input.val(qty);
		}

		$row.addClass('basket-row--loading');

		SimpleBasket.updateItem(cartKey, qty, function (basket) {
			$row.removeClass('basket-row--loading');
			SimpleBasket.afterBasketChange(basket);
		});
	});

	$(document).on('click', '.basketForm .basket-remove-btn', function (e) {
		e.preventDefault();

		var $btn = $(this);
		var cartKey = $btn.data('cart-key') || $btn.attr('data-cart-key');
		var $row = $btn.closest('tr[data-product-id]');

		if (!cartKey) {
			return;
		}

		$row.addClass('basket-row--loading');

		SimpleBasket.remove(cartKey, function (basket) {
			$row.removeClass('basket-row--loading');
			SimpleBasket.afterBasketChange(basket);
		});
	});

	/**
	 * Обработка клика по кнопке "Купить" в AJAX-режиме (toggle).
	 * Ожидается, что шорткод выводит ссылку с классом .simple-basket-buy-now
	 * и атрибутом data-product-id.
	 */
	$(document).on('click', '.simple-basket-buy-now', function (e) {
		var $link = $(this);

		if ($link.hasClass('js-add-to-cart')) {
			return;
		}
		var id = $link.data('product-id');
		if (!id) {
			return;
		}
		e.preventDefault();

		// Если товар уже "заказан" (кнопка в состоянии лимита) — удаляем его из корзины
		if ($link.hasClass('simple-basket-buy-now--limit')) {
			SimpleBasket.remove(id, function (basket) {
				SimpleBasket.updateCountView(basket);
				SimpleBasket.updateButtonsState(basket);
			});
		} else {
			// Иначе добавляем товар
			SimpleBasket.add(id, function (basket) {
				SimpleBasket.updateCountView(basket);
				SimpleBasket.updateButtonsState(basket);
			});	
		}

		$('.btn-basket').addClass('animate-me');
		setTimeout(function(){
			$('.btn-basket').removeClass('animate-me');
		}, 1000);
	});

	// Инициализация счётчика при загрузке страницы
	SimpleBasket.refreshCount();
	// Инициализация data-basket на странице корзины
	if (jQuery('#basketForm').length) {
		SimpleBasket.updateBasketDataFromDom();
	}
});	