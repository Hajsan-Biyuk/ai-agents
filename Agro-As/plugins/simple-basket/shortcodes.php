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
	$variation = 0;
	$quantity = 1;

	if ( is_array( $atts ) ) {
		if ( ! empty( $atts['id'] ) ) {
			$id = (int) $atts['id'];
		}
		if ( isset( $atts['variation'] ) ) {
			$variation = (int) $atts['variation'];
		}
		if ( isset( $atts['quantity'] ) ) {
			$quantity = max( 1, (int) $atts['quantity'] );
		}
	}

	if ( function_exists( 'simple_basket_get_min_variation_index' ) && $variation === 0 && ! isset( $atts['variation'] ) ) {
		$variation = simple_basket_get_min_variation_index( $id );
	}

	$title = get_the_title( $id );
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
	$output = '<button type="button" class="simple-basket-buy-now simple-basket-add js-add-to-cart" data-product-id="' . esc_attr( (string) $id ) . '" data-variation="' . esc_attr( (string) $variation ) . '" data-quantity="' . esc_attr( (string) $quantity ) . '" data-label="' . esc_attr( $caption ) . '"' . $ga . '>' . esc_html( $caption ) . '</button>';
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