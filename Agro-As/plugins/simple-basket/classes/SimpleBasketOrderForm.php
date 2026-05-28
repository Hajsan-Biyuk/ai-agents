<?php
/**
 * Класс обработки заказа
 *
 */
class SimpleBasketOrderForm
{
	/**
	 * Корзина
	 * @var SimpleBasketOrder
	 */
	 private $basket;

	/**
	 * URL корзины
	 * @var string
	 */
	 private $basketURL;
	/**
	 * Способ доставки
	 * @var string
	 */
	 private $deliveryType;

	/**
	 * Код заказа
	 * @var string
	 */
	 private $orderId;


	/**
	 * Параметр формы UserName
	 * @var SimpleBasketOrder
	 */
	const USER_NAME = 'username';

	/**
	 * Параметр формы UserEmail
	 * @var SimpleBasketOrder
	 */
	const USER_EMAIL = 'useremail';

	/**
	 * Параметр формы UserPhone
	 * @var SimpleBasketOrder
	 */
	const USER_PHONE = 'userphone';
	/**
	 * Параметр формы UserPhone
	 * @var SimpleBasketOrder
	 */
	const USER_SHIPPING_TYPE = 'usershipping';

	/**
	 * Параметр формы UserComment
	 * @var SimpleBasketOrder
	 */
	const USER_COMMENT = 'usercomment';

	
	/**
	 * Типы сообщения пользователю
	 */
	const MAIL_TO_USER = 0;	
	const MAIL_TO_ADMIN = 1;	
	
	

	/**
	 * Конструктор класса
	 */	
	 public function __construct()
	 {
		 $this->basket = SimpleBasketOrder::create();
		 $this->basketURL = get_option('simple_basket_order_page');
		 $this->deliveryType = 0;
		 $this->orderId = '';
	 }

	/**
	 * Обработка обращения к форме заказа
	 */	
	 public function handle()
	 {
		// Если пользователь авторизован, пытаемся подставить пустые данные
		global $current_user;
		if (is_user_logged_in())
		{
			$current_user = wp_get_current_user();
			if (empty($this->basket->userName))
				$this->basket->userName = $current_user->display_name;
			if (empty($this->basket->userEmail))
				$this->basket->userEmail = $current_user->user_email;
			if (empty($this->basket->userPhone))
				$this->basket->userPhone = esc_attr(get_the_author_meta('phone', $current_user->user_ID));
		}

		// Обработка добавления
		if (isset($_GET[SIMPLE_BASKET_ADD]))
		{
			$id = (int) $_GET[SIMPLE_BASKET_ADD];
			$variation = isset( $_GET['variation'] ) ? (int) $_GET['variation'] : 0;
			$quo = isset( $_GET['quo'] ) ? max( 1, (int) $_GET['quo'] ) : 1;

			simple_basket_add_product_to_cart( $id, $variation, $quo );
			simple_basket_redirect($this->basketURL);
		}

		// Обработка обновления количества товаров
		if (isset($_POST[SIMPLE_BASKET_MODE]) && $_POST[SIMPLE_BASKET_MODE] == SIMPLE_BASKET_UPDATE)
		{
			// Очистка корзины по кнопке "Очистить корзину"
			if (isset($_POST[SIMPLE_BASKET_CLEAR])) {
				$this->basket->clear();
				// Переходим на корзину
				simple_basket_redirect($this->basketURL);
			}

			foreach ($_POST as $key=>$value)
			{
				if (strpos($key, SIMPLE_BASKET_UPDATE) === 0)
				{
					$cart_key = substr($key, strlen(SIMPLE_BASKET_UPDATE) + 1);
					$this->basket->update($cart_key, $value);
				}
				// Обработка удаления товара по нажатию на кнопку удалить
				if (strpos($key, SIMPLE_BASKET_DELETE) === 0)
				{
					$cart_key = substr($key, strlen(SIMPLE_BASKET_DELETE) + 1);
					$this->basket->update($cart_key, 0);
				}
			}
			// Переходим на корзину
			simple_basket_redirect($this->basketURL);
		}

		// Обработка нового заказа
		if (isset($_POST[SIMPLE_BASKET_MODE]) && $_POST[SIMPLE_BASKET_MODE] == SIMPLE_BASKET_CHECKOUT)
		{
			// Получение данных
			$this->basket->setUserData(
				trim(strip_tags($_POST[self::USER_NAME])),
				trim(strip_tags($_POST[self::USER_EMAIL])),
				trim(strip_tags($_POST[self::USER_PHONE])),
				trim(strip_tags($_POST[self::USER_COMMENT])));
			$this->deliveryType = (isset($_POST[self::USER_SHIPPING_TYPE])) ? (int) $_POST[self::USER_SHIPPING_TYPE] : 0;

			// Если данные верны - обрабатываем заказ
			if ($this->basket->isValid())
			{
				// Ищем пользователя
				$user = get_user_by('email', $this->basket->userEmail);
				if (!$user)
				{
					// Такого пользователя нет, добавляем!
					$password = wp_generate_password(12, true);
					$userId = wp_create_user ($this->basket->userEmail, $password, $this->basket->userEmail);
				}
				else
				{
					$userId = $user->ID;
				}

				// Обновляем данные о пользователе
				update_user_meta($userId, 'first_name', $this->basket->userName);
				update_user_meta($userId, 'phone', $this->basket->userPhone);

				// Учитываем доставку
				if ($this->deliveryType > 0)
				{
					
					$deliveryTitle = get_the_title($this->deliveryType);
					$deliveryPrice = (float) simple_basket_custom_fields($this->deliveryType, __('Cost', 'simple_basket'));
					$this->basket->add(SIMPLE_BASKET_DELIVERY, $deliveryTitle, $deliveryPrice, __('Delivery', 'simple_basket'));
				}
				

				// Добавляем заказ в таблицу заказов
				$this->orderId = current_time('Ymd-Hi');
				$orderBody = '<p><strong>' . __('Customer', 'simple_basket') . ':</strong> ' . $this->basket->userName . '</p>' .
							'<p><strong>' . __('E-mail', 'simple_basket') . ':</strong> ' . $this->basket->userEmail . '</p>' .
							'<p><strong>' . __('Phone', 'simple_basket') . ':</strong> ' . $this->basket->userPhone . '</p>' .
							'<div>' . $this->basket->getHTML() . '</div>' .
							'<div>' . $this->basket->userComment . '</div>';
				$newOrder = array(
					'post_type'		=> SIMPLE_BASKET_ORDER_TYPE,
					'post_title'	=> $this->orderId,
					'post_content'	=> $orderBody,
					'post_status'	=> 'publish',
					'post_author'	=> $userId
				);
				$postId = wp_insert_post($newOrder);
				
				// Устанавливаем статус заказа
				wp_set_object_terms($postId, __('New', 'simple_basket'), SIMPLE_BASKET_ORDER_STATUS);
				
				// Устанавливаем общую стоимость заказа
				update_post_meta($postId, __('Summ', 'simple_basket'), $this->basket->getTotal());
				
				// Подготовливаем отправку почты
				add_filter('wp_mail_content_type', 'simple_basket_set_html_content_type'); 
				
				// Высылаем письма пользователю
				$userEmail = $this->prepareLetter(self::MAIL_TO_USER);
				if (!empty($userEmail['body']))
					wp_mail($this->basket->userEmail, $userEmail['subject'], $userEmail['body']);

				// Высылаем письма администраторам
				$adminEmail = $this->prepareLetter(self::MAIL_TO_ADMIN);
				if (!empty($adminEmail['body']))
				{
					$admins = get_users(array('role' => 'Administrator'));
					$adminEmails = array();
					foreach($admins as $admin)
						$adminEmails[] = $admin->user_email;
					wp_mail($adminEmails, $adminEmail['subject'], $adminEmail['body']);
				}

				// reset content-type to to avoid conflicts -- http://core.trac.wordpress.org/ticket/23578
				remove_filter('wp_mail_content_type', 'simple_basket_set_html_content_type'); 
			}
		}
	 }

	/**
	 * Возвращает HTML код письма
	 * @return mixed массив с письмом
	 */	
	public function prepareLetter($type)
	{
		
		if ($type == self::MAIL_TO_USER)
			$mail = array(
				'subject' => get_option('simple_basket_conformation_email_subject'),
				'body' => get_option('simple_basket_conformation_email_post')
			);
		else
			$mail = array(
				'subject' => get_option('simple_basket_admin_email_subject'),
				'body' => get_option('simple_basket_admin_email_post')
			);		

		// Массив замен моих "шорткодов"
		$replacemets = array(
			'[order-code]'		=> $this->orderId,
			'[order-customer]'	=> $this->basket->userName,
			'[order-email]'		=> $this->basket->userEmail,
			'[order-phone]'		=> $this->basket->userPhone,
			'[order-comment]'	=> $this->basket->userComment,
			'[order-items]'		=> $this->basket->getHTML(),
		);
		foreach ($replacemets as $shorcode => $replacemet)
		{
			$mail['subject'] = str_replace($shorcode, $replacemet, $mail['subject']);
			$mail['body'] = str_replace($shorcode, $replacemet, $mail['body']);
		}

		return $mail;
	}
	


	/**
	 * Возвращает форму заказа
	 * @return strign HTML код формы заказа
	 */	
	public function getHTML()
	{
		// Если корзина пуста - выводим сообщение
		if ($this->basket->isEmpty())
			return '<div class="basket-page__empty">' . esc_html__( 'Корзина пуста.', 'maintheme' ) . '</div>';

		// Готовим данные корзины для data-атрибута #basketForm
		$basketItemsData = array();
		if (is_array($this->basket->items)) {
			foreach ($this->basket->items as $id => $item) {
				if (!isset($item[SimpleBasketOrder::QUO]) || $item[SimpleBasketOrder::QUO] <= 0) {
					continue;
				}
				$basketItemsData[] = array(
					'id'       => $id,
					'title'    => isset($item[SimpleBasketOrder::TITLE]) ? $item[SimpleBasketOrder::TITLE] : '',
					'quantity' => (int) $item[SimpleBasketOrder::QUO],
					'price'    => isset($item[SimpleBasketOrder::PRICE]) ? (float) $item[SimpleBasketOrder::PRICE] : null,
				);
			}
		}
		$basketData = array(
			'items' => $basketItemsData,
		);

		// Заказ сделан, выводим страницу-квитанцию
		if (!empty($this->orderId))
		{
			$output = '<div class="simple-basket-order-complete">' .
						'<h3>' . __('Thank you for your order!', 'simple_basket')  . '</h3>' .  
						'<h4>' . __('Your order code is #', 'simple_basket') . $this->orderId  . '</h4>';
			
			// Добавляем код Google Analytics
			$gaMode = get_option('simple_basket_google_analytics_mode');
			if ($gaMode > 0)
			{
				$output .= '<script>';

				$affiliationName = get_bloginfo('name');
				$totalSumm = $this->basket->getTotal();
				$shiipingPrice = 0;
				$tax = 0;
				$itemsString = '';

				// Поискольку нужно вычислить доставку в элементах заказа
				// сформируем строку элементов заказа заранее, пройдя по массиву
				foreach ($this->basket->items as $itemId=>$item)
				{
					
					if ($itemId == SIMPLE_BASKET_DELIVERY)
					{
						$shiipingPrice = $item[SimpleBasketOrder::PRICE];
						continue;
					}

					$itemName = addslashes($item[SimpleBasketOrder::TITLE]);
					$itemCategory = addslashes($item[SimpleBasketOrder::CATEGORY]);
					$itemPrice = (float) ($item[SimpleBasketOrder::PRICE]);
					$itemQuo = (int) ($item[SimpleBasketOrder::QUO]);

					$itemsString .= ($gaMode == 1) ?
						"_gaq.push(['_addItem', '{$this->orderId}', '{$itemId}', '{$itemName}', '{$itemCategory}', '{$itemPrice}', '{$itemQuo}']);" :
						"ga('ecommerce:addItem', {'id': '{$this->orderId}','name': '{$itemName}','sku': '{$itemId}', 'category': '{$itemCategory}', 'price': '{$itemPrice}', 'quantity': '{$itemQuo}'});";
				}

				$orderCompleteVirtualPage = get_option('simple_basket_order_compete_virtual_page');
				
				switch ($gaMode)
				{
					case 1:		// ga.js
						// Adding a Transaction
						$output .= "_gaq.push(['_addTrans', '{$this->orderId}', '{$affiliationName}', '{$totalSumm}', '{$tax}', '{$shiipingPrice}', '', '', '']);";
						// Adding items
						$output .= $itemsString;
						// Tracking
						$output .= "_gaq.push(['_trackTrans']);";
						if (!empty($orderCompleteVirtualPage))
							$output .= "_gaq.push(['_trackPageview', '{$orderCompleteVirtualPage}']);";
						break;

					case 2:		// analytics.js
						// Load the Ecommerce Plugin
						$output .= "ga('require', 'ecommerce', 'ecommerce.js');";
						// Adding a Transaction
						$output .= "ga('ecommerce:addTransaction', {'id': '{$this->orderId}', 'affiliation': '{$affiliationName}', 'revenue': '{$totalSumm}', 'shipping': '{$shiipingPrice}', 'tax': '{$tax}'});";
						// Adding items
						$output .= $itemsString;
						// Tracking
						$output .= "ga('ecommerce:send');";
						if (!empty($orderCompleteVirtualPage))
							$output .= "ga('send', 'pageview', '{$orderCompleteVirtualPage}');";						
						break;
				}
				$output .= '</script>';
			}


			$output .= '</div><!--/simple-basket-order-complete->';

			// Очистка заказа
			$this->orderId = '';
			$this->basket->clear();

			// Вывод
			return $output;
		}
		
		// Начало вывода
		$output = '<div class="simple-basket-order-form">';
		// Вывод корзины
		$basketDataAttr = esc_attr( wp_json_encode( $basketData ) );
		$output .= '<div id="basketForm" class="basket-page__basket" data-basket="' . $basketDataAttr . '">' .
			'<h2 class="basket-page__subtitle title-section">' . esc_html__( 'Ваш заказ', 'maintheme' ) . '</h2>' .
			$this->getBasketHTML() .
			'</div><!--/basketForm-->';

		$output .= '<div id="orderForm" class="basket-page__checkout">' .
			'<h2 class="basket-page__subtitle title-section">' . esc_html__( 'Оформление заказа', 'maintheme' ) . '</h2>' .
			'<form class="orderForm basket-page__order-form" action="' . esc_url( $this->basketURL ) . '" method="post">' .
				'<input type="hidden" name="' . SIMPLE_BASKET_MODE . '" value="' . SIMPLE_BASKET_CHECKOUT . '" />' .
				'<div class="row">' .
					'<div class="col-md-6">' .
						'<div class="item-form">' .
							'<label for="' . self::USER_NAME . '">' . esc_html__( 'Ваше имя', 'maintheme' ) . '</label>' .
							'<input id="' . self::USER_NAME . '" type="text" name="' . self::USER_NAME . '" value="' . esc_attr( $this->basket->userName ) . '" required="required" placeholder="' . esc_attr__( 'Ваше имя', 'maintheme' ) . '" />' .
						'</div>' .
					'</div>' .
					'<div class="col-md-6">' .
						'<div class="item-form">' .
							'<label for="' . self::USER_EMAIL . '">' . esc_html__( 'E-mail', 'maintheme' ) . '</label>' .
							'<input id="' . self::USER_EMAIL . '" type="email" name="' . self::USER_EMAIL . '" value="' . esc_attr( $this->basket->userEmail ) . '" required="required" placeholder="' . esc_attr__( 'mail@mail.ru', 'maintheme' ) . '" />' .
						'</div>' .
					'</div>' .
					'<div class="col-md-6">' .
						'<div class="item-form">' .
							'<label for="' . self::USER_PHONE . '">' . esc_html__( 'Телефон', 'maintheme' ) . '</label>' .
							'<input id="' . self::USER_PHONE . '" type="tel" name="' . self::USER_PHONE . '" class="input-phone" inputmode="numeric" value="' . esc_attr( $this->basket->userPhone ) . '" required="required" placeholder="' . esc_attr__( '+7 (978) 000-00-00', 'maintheme' ) . '" />' .
						'</div>' .
					'</div>';
		// Если есть доставка, учитываем его
		if ( get_option( 'simple_basket_delivery' ) == '1' ) {
			$output .= '<div class="col-md-6"><div class="item-form">' .
				'<label for="' . self::USER_SHIPPING_TYPE . '">' . esc_html__( 'Способ доставки', 'maintheme' ) . '</label>' .
				'<select id="' . self::USER_SHIPPING_TYPE . '" name="' . self::USER_SHIPPING_TYPE . '">';
			$deliveryType = new WP_Query( array( 'post_type' => 'delivery' ) );
			while ( $deliveryType->have_posts() ) {
				$deliveryType->next_post();
				$selected = ( $this->deliveryType == $deliveryType->post->ID ) ? ' selected="selected"' : '';
				$output .= '<option value="' . esc_attr( (string) $deliveryType->post->ID ) . '"' . $selected . '>' . esc_html( get_the_title( $deliveryType->post->ID ) ) . '</option>';
			}
			wp_reset_postdata();
			$output .= '</select></div></div>';
		}

		$commentPlaceholder = ( get_option( 'simple_basket_delivery' ) == '1' ) ?
			__( 'Shipping address and other comments', 'simple_basket' ) :
			__( 'Комментарий к заказу', 'maintheme' );

		$output .=
					'<div class="col-md-6">' .
						'<div class="item-form item-form--message">' .
							'<label for="' . self::USER_COMMENT . '">' . esc_html__( 'Комментарий', 'maintheme' ) . '</label>' .
							'<textarea id="' . self::USER_COMMENT . '" name="' . self::USER_COMMENT . '" placeholder="' . esc_attr( $commentPlaceholder ) . '">' . esc_textarea( $this->basket->userComment ) . '</textarea>' .
						'</div>' .
					'</div>' .
					'<div class="col-12">' .
						'<div class="basket-page__order-actions">' .
							'<button class="checkout btn-main" type="submit">' . esc_html__( 'Оформить заказ', 'maintheme' ) . ' <img src="' . esc_url( $this->getThemeAssetUrl( 'img/icon-arrow.svg' ) ) . '" width="24" height="24" alt=""></button>' .
						'</div>' .
					'</div>' .
				'</div>' .
			'</form>' .
			'</div><!--/orderForm--></div><!--/simple-basket-order-form-->';

		// Если есть ошибки - выводим ошибки
		if (is_array($this->basket->errorMessages) && count($this->basket->errorMessages) > 0)
		{
			$output .= '<ul class="error">';
			foreach ($this->basket->errorMessages as $error)
				$output .= '<li>' . $error . '</li>';
			$output .= '</ul>';
		}
		return $output;
	}

	/**
	 * URL ассета активной темы.
	 */
	private function getThemeAssetUrl( $relative_path ) {
		if ( ! function_exists( 'get_template_directory_uri' ) ) {
			return '';
		}

		return trailingslashit( get_template_directory_uri() ) . ltrim( $relative_path, '/' );
	}

	/**
	 * Возвращает преобразованную форму корзины, добавляя в нее кнопки и возможность изменения
	 * @return strign HTML код формы заказа
	 */	
	public function getBasketHTML()
	{
		$table = $this->basket->getHTML();

		$table = str_replace(
			array(
				'>' . __( 'Title', 'simple_basket' ) . '<',
				'>' . __( 'Quo', 'simple_basket' ) . '<',
				'>' . __( 'Price', 'simple_basket' ) . '<',
				'>' . __( 'Summ', 'simple_basket' ) . '<',
				'>' . __( 'Total', 'simple_basket' ) . '<',
			),
			array(
				'>Товар<',
				'>Кол-во<',
				'>Цена<',
				'>Сумма<',
				'>Итого<',
			),
			$table
		);

		$qty_minus   = $this->getThemeAssetUrl( 'img/icon-qty-minus.svg' );
		$qty_plus    = $this->getThemeAssetUrl( 'img/icon-qty-plus.svg' );
		$remove_icon = $this->getThemeAssetUrl( 'img/icon-basket-remove.svg' );

		$table = preg_replace(
			'/<td class="quo" data-value="([0-9]+)" data-id="([^"]+)">[0-9]+<\/td>/',
			'<td class="quo"><div class="basket-qty product-page__qty">
					<button type="button" class="basket-qty-btn quantity-button quantity-down" aria-label="' . esc_attr__( 'Уменьшить', 'maintheme' ) . '">
						<img src="' . esc_url( $qty_minus ) . '" width="24" height="24" alt="">
					</button>
					<input type="number" class="product-page__qty-input basket-qty-input" name="' . SIMPLE_BASKET_UPDATE . '_$2" min="1" step="1" value="$1">
					<button type="button" class="basket-qty-btn quantity-button quantity-up" aria-label="' . esc_attr__( 'Увеличить', 'maintheme' ) . '">
						<img src="' . esc_url( $qty_plus ) . '" width="24" height="24" alt="">
					</button>
				</div></td>',
			$table
		);

		$table = preg_replace(
			'/(<tr data-product-id="([^"]+)">.*?)(<\/tr>)/s',
			'$1<td class="delete"><button type="button" class="basket-remove-btn" data-cart-key="$2" aria-label="' . esc_attr__( 'Удалить', 'maintheme' ) . '"><img src="' . esc_url( $remove_icon ) . '" width="24" height="24" alt=""></button></td>$3',
			$table
		);

		$table = preg_replace( '/(<thead>.*?)(<\/tr><\/thead>)/s', '$1<td class="actions"></td>$2', $table, 1 );

		$table = preg_replace_callback(
			'/(<tfoot>.*?<\/tfoot>)/s',
			function ( $matches ) {
				$tfootContent = $matches[1];
				$tfootContent = preg_replace_callback(
					'/colspan="(\d+)"/',
					function ( $m ) {
						return 'colspan="' . ( (int) $m[1] + 1 ) . '"';
					},
					$tfootContent
				);
				return $tfootContent;
			},
			$table
		);

		$form = '<form class="basketForm basket-page__table-form" action="' . esc_url( $this->basketURL ) . '" method="post">' .
			'<input type="hidden" name="' . SIMPLE_BASKET_MODE . '" value="' . SIMPLE_BASKET_UPDATE . '" />' .
			'<div class="basket-page__table-wrap">' . $table . '</div>';

		$form .= '<div class="basket-page__toolbar">';

		$form .= '<button class="clear-basket basket-page__clear" type="submit" name="' . SIMPLE_BASKET_CLEAR . '" value="1">' . esc_html__( 'Очистить корзину', 'maintheme' ) . '</button>';

		$pdfUrl = $this->basketURL;
		if ( strpos( $pdfUrl, '?' ) === false ) {
			$pdfUrl .= '?simple_basket_pdf=1';
		} else {
			$pdfUrl .= '&simple_basket_pdf=1';
		}

		$form .= '<a class="download-order basket-page__download" href="' . esc_url( $pdfUrl ) . '">' . esc_html__( 'Скачать заказ', 'maintheme' ) . '</a>';
		$form .= '</div></form>';

		return $form;
	}

}


?>