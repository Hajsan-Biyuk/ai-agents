<?php
/**
 * Заказы WooCommerce.
 *
 * @package Telegram_Zayavki
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Одно уведомление на заказ после оформления.
 */
class TZ_Woo {
	const SENT_META = '_telegram_zayavki_sent';

	/** Источник заказа: cookie есть только в запросе оформления, не в колбэке оплаты. */
	const SOURCE_META = '_telegram_zayavki_source';

	/**
	 * Заказы, которые уже обрабатываются в этом запросе.
	 *
	 * @var array<int, bool>
	 */
	private static $locked = array();

	/**
	 * Экземпляр.
	 *
	 * @var self|null
	 */
	private static $instance = null;

	/**
	 * @return self
	 */
	public static function instance() {
		if (self::$instance === null) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Регистрирует хуки оформления заказа.
	 */
	public function hooks() {
		add_action('woocommerce_checkout_order_processed', array($this, 'save_source'), 10, 1);
		add_action('woocommerce_store_api_checkout_order_processed', array($this, 'save_source'), 10, 1);
		add_action('woocommerce_checkout_order_processed', array($this, 'send'), 20, 1);
		add_action('woocommerce_store_api_checkout_order_processed', array($this, 'send'), 20, 1);
		add_action('woocommerce_order_status_processing', array($this, 'send'), 20, 1);
		add_action('woocommerce_order_status_on-hold', array($this, 'send'), 20, 1);
	}

	/**
	 * Отправляет заказ, если уведомления включены и он ещё не уходил.
	 *
	 * @param int|WC_Order $order_id Заказ или его идентификатор.
	 */
	public function save_source($order_id) {
		$order = $order_id instanceof WC_Order ? $order_id : wc_get_order($order_id);
		if ($order instanceof WC_Order && !$order->get_meta(self::SOURCE_META)) {
			$order->update_meta_data(self::SOURCE_META, implode("\n", TZ_Source::lines()));
			$order->save();
		}
	}

	public function send($order_id) {
		$settings = TZ_Settings::get();
		if (empty($settings['woo_enabled']) || !TZ_Settings::can_send() || !function_exists('wc_get_order')) {
			return;
		}

		$order = $order_id instanceof WC_Order ? $order_id : wc_get_order($order_id);
		if (!$order instanceof WC_Order) {
			return;
		}

		$order_key = $order->get_id();
		if ($order->get_meta(self::SENT_META)) {
			return;
		}

		$status = $order->get_status();
		if (in_array($status, array('failed', 'cancelled', 'refunded', 'trash', 'checkout-draft'), true)) {
			return;
		}

		if (isset(self::$locked[$order_key])) {
			return;
		}
		self::$locked[$order_key] = true;

		$order->update_meta_data(self::SENT_META, '1');
		$order->save();

		$client = new TZ_Telegram($settings['bot_token']);
		$result = $client->send_message($settings['chat_id'], TZ_Messages::order($order));
		if (is_wp_error($result)) {
			$order->delete_meta_data(self::SENT_META);
			$order->save();
			TZ_Settings::set_last_error('Заказ №' . $order->get_order_number() . ': ' . $result->get_error_message());
			return;
		}

		TZ_Settings::clear_last_error();
	}
}
