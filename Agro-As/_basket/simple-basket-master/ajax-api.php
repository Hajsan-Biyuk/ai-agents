<?php
/**
 * AJAX API корзины
 */

$simpeBasketAPI = new SimpleBasketAPI();


class SimpleBasketAPI
{
	/**
	 * Корзина
	 * @var SimpleBasketOrder
	 */
	 private $basket;	



	/**
	 * Конструктор класса
	 */
	public function __construct()
	{
		add_action( 'wp_ajax_nopriv_getTime', array( &$this, 'getTime'));
		add_action( 'wp_ajax_getTime', array( &$this, 'getTime'));

		add_action( 'wp_ajax_nopriv_getData', array( &$this, 'getData'));
		add_action( 'wp_ajax_getData', array( &$this, 'getData'));

		add_action( 'wp_ajax_nopriv_add', array( &$this, 'add'));
		add_action( 'wp_ajax_add', array( &$this, 'add'));

		// Удаление товара из корзины (AJAX)
		add_action( 'wp_ajax_nopriv_remove', array( &$this, 'remove'));
		add_action( 'wp_ajax_remove', array( &$this, 'remove'));

		// Обновление количества товара в корзине (AJAX)
		add_action( 'wp_ajax_nopriv_update', array( &$this, 'update'));
		add_action( 'wp_ajax_update', array( &$this, 'update'));
		add_action( 'init', array( &$this, 'init' ) );


		$this->basket = SimpleBasketOrder::create();
	}

	/**
	 * Функция инициализации AJAX
	 */
	public function init()
	{
		// Подключаем неминифицированную версию для удобства доработки
		wp_enqueue_script('simple-basket', plugin_dir_url( __FILE__ ) . 'js/simple-basket.js', array( 'jquery' ) );
		wp_localize_script('simple-basket', 'SimpleBasket', array(
		    'ajaxurl'            => admin_url( 'admin-ajax.php' ),
		    'nonce'              => wp_create_nonce( 'ajax-example-nonce' ),
		    'itemLimit'          => function_exists('simple_basket_get_item_limit') ? simple_basket_get_item_limit() : 0,
		    'buyNowCaption'     => get_option('simple_basket_buynow_caption', __('Buy', 'simple_basket')),
		    'buyNowLimitCaption' => get_option('simple_basket_buynow_limit_caption', __('Added', 'simple_basket'))
		) );
	}

	/**
	 * Функция проверки токена
	 */
	public function validateNonce()
	{
		if ( ! isset( $_REQUEST['nonce'] ) || ! wp_verify_nonce( $_REQUEST['nonce'], 'ajax-example-nonce' ) )
			die ( 'Invalid Nonce' );
	}

	/**
	 * Функция ответа
	 */
	public function responce($result)
	{
		header('Content-Type: application/json');
		echo json_encode($result);
		exit;
	}
	
	/* --------------------- AJAX методы ---------------------- */
	public function getTime()
	{
		$this->validateNonce();
		$this->responce(array(
			'time' => date('d.m.Y H:i:s')
		));
	}

	// Возврат корзины
	public function getData()
	{
		$this->validateNonce();
		$this->responce($this->basket);
	}

	// Добавление товара в корзину
	public function add()
	{
		$this->validateNonce();
		if (!isset($_REQUEST['id']))
			die ( 'ID not specified' );

			// Код товара
			$id = (int) $_REQUEST['id'];
			$product = get_post($id);
			// Название с типом данных перед заголовком
			$postTypeObject = get_post_type_object($product->post_type);
			$postTypeTitle = ($postTypeObject && isset($postTypeObject->labels->singular_name)) ? $postTypeObject->labels->singular_name : $product->post_type;
			$title = $postTypeTitle . ': ' . $product->post_title;
			// Цена
			$price = simple_basket_custom_fields($id, get_option('simple_basket_catalog_price'));
			// Стоимости дополнительных услуг
			$services = simple_basket_get_product_services($id);
			// Вычисляем категорию по таксономии 
			$category = '';
			// Тип записи каталога товара
			$postType = $product->post_type;
			// Таксономии записи
			$taxonomies = get_object_taxonomies($postType);
			// Ищем таксономию, которая не тег
			foreach ($taxonomies as $taxonomy)
			{
				if (strpos($taxonomy, 'tag') !== FALSE) continue;
				// Берем элементы этой таксономии
				$categories = get_the_terms($id, $taxonomy);
				$category = (count($categories) > 0) ? $categories[0]->name : '';
				// Следующие таксономии не рассматриваем
				break;
			}
			
			// Добавляем в корзину
			if (!empty($title)) $this->basket->add($id, $title, $price, $category, $services);
		$this->responce($this->basket);
	}

	// Удаление товара из корзины (установка количества в 0)
	public function remove()
	{
		$this->validateNonce();
		if (!isset($_REQUEST['id']))
			die ( 'ID not specified' );

		$id = (int) $_REQUEST['id'];
		// Обнуляем количество товара, что приводит к его удалению из корзины
		$this->basket->update($id, 0);
		$this->responce($this->basket);
	}

	// Обновление количества товара в корзине
	public function update()
	{
		$this->validateNonce();
		if (!isset($_REQUEST['id']))
			die ( 'ID not specified' );
		if (!isset($_REQUEST['quo']))
			die ( 'Quantity not specified' );

		$id = (int) $_REQUEST['id'];
		$quo = (int) $_REQUEST['quo'];
		if ($quo < 0) {
			$quo = 0;
		}

		$this->basket->update($id, $quo);
		$this->responce($this->basket);
	}
}
