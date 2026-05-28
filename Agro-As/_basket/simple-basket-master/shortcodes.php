<?php
/**
 * Функции шорткодов
 *
 * [buy-now], [basket-buy-button] - Кнопка купить
 * [order-form], [basket-order-form] - Полная форма заказа
 */
 
// --------------------------- [buy-now] Для обратной совместимости --------------------------- 
add_shortcode('buy-now', 'getSimpleBasketBuyButton');
function showBuyNowButton()
{
	echo getSimpleBasketBuyButton(array());
}
// --------------------------- [basket-buy-button] --------------------------- 
add_shortcode('basket-buy-button', 'getSimpleBasketBuyButton');
function simpleBasketBuyButton()
{
	echo getSimpleBasketBuyButton(array());
}
function getSimpleBasketBuyButton($atts)
{
	$id = get_the_ID();
	$caption = get_option('simple_basket_buynow_caption');
	$title = get_the_title();
	// Для AJAX-режима добавления в корзину ссылку больше не используем для редиректа в корзину,
	// поэтому оставляем заглушку и передаём ID товара через data-атрибут.
	$url = '#';
	switch (get_option('simple_basket_google_analytics_mode'))
	{
		case 1:
			$ga = ' onclick="_gaq.push([\'_trackEvent\', \'' . __('Basket', 'simple_basket') .  '\', \'' . __('Add to basket', 'simple_basket')  . '\', \'' . $title . '\']);"';
			break;
		case 2:
			$ga = ' onclick="ga(\'send\', \'event\', \'' . __('Basket', 'simple_basket') .  '\', \'' . __('Add to basket', 'simple_basket')  . '\', \'' . $title . '\');"';
			break;
		default:
			$ga = '';
	}
	$output = '<a href="' . $url . '" class="simple-basket-buy-now" data-product-id="' . $id . '"' . $ga . '>' . 	$caption .'</a>';
	return $output;
} 

// --------------------------- [order-form] Для обратной совместимости --------------------------- 

add_shortcode('order-form', 'getSimplelBasketOrderForm');
function showOrderForm()
{
	echo getSimplelBasketOrderForm(array());
}

// ---------------------- [basket-order-form] ----------------------
add_shortcode('basket-order-form', 'getSimplelBasketOrderForm');
function simplelBasketOrderForm()
{
	echo getSimplelBasketOrderForm(array());
}
function getSimplelBasketOrderForm($atts)
{
	// ‘орма заказа
	$orderForm = new SimpleBasketOrderForm();
	// ќбработка формы заказа
	$orderForm->handle();


	// ѕокажем форму
	return // '<pre>' . session_id() . '<br>' . $_SESSION . '</pre>' . 
		$orderForm->getHTML();
}
?>