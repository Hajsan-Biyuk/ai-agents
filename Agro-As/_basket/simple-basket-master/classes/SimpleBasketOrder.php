<?php
/**
 * Класс корзины заказа
 *
 */

class SimpleBasketOrder
{
	/* ----------------------- Класс-синглтон ------------------------- */
	/**
	 * Экземпляр класса
	 * @static
	 */
	 private static $instance = NULL;

	/**
	 * Возвращает экземпляр класса
	 * @static
	 * @return SimpleBasketOrder
	 */
	 public static function create()
	 {
	 	if (!self::$instance)
			self::$instance = new SimpleBasketOrder();
		return self::$instance;
	 }

	/* ------------------- Определения класса  ---------------------- */	
	const ITEMS			= 'SIMPLE_BASKET_ITEMS';
	const TITLE			= 'SIMPLE_BASKET_TITLE';
	const QUO			= 'SIMPLE_BASKET_QUO';
	const PRICE			= 'SIMPLE_BASKET_PRICE';
	const CATEGORY		= 'SIMPLE_BASKET_CATEGORY';
	const SERVICE_COSTS	= 'SIMPLE_BASKET_SERVICE_COSTS';
	const SERVICE_LABEL	= 'SIMPLE_BASKET_SERVICE_LABEL';
	const SERVICE_PRICE	= 'SIMPLE_BASKET_SERVICE_PRICE';
	const SERVICE_ID		= 'SIMPLE_BASKET_SERVICE_ID';
	const USER_EMAIL	= 'SIMPLE_BASKET_USER_EMAIL';
	const USER_PHONE	= 'SIMPLE_BASKET_USER_PHONE';
	const USER_NAME		= 'SIMPLE_BASKET_USER_NAME';
	const USER_COMMENT	= 'SIMPLE_BASKET_USER_COMMENT';

	/* ------------------- Конструктор и сессия  ---------------------- */
	/**
	 * Элементы заказа
	 * @var mixed
	 */
	 public $items;


	/**
	 * Конструктор класса
	 */
	 private function __construct()
	 {
		$this->items = (isset($_SESSION[self::ITEMS])) ? $_SESSION[self::ITEMS] : array();
		$this->userName = (isset($_SESSION[self::USER_NAME])) ? $_SESSION[self::USER_NAME] : '';
		$this->userEmail = (isset($_SESSION[self::USER_EMAIL])) ? $_SESSION[self::USER_EMAIL] : '';
		$this->userPhone = (isset($_SESSION[self::USER_PHONE])) ? $_SESSION[self::USER_PHONE] : '';
		$this->userComment = (isset($_SESSION[self::USER_COMMENT])) ? $_SESSION[self::USER_COMMENT] : '';
		$this->errorMessages = array();
	 }

	/**
	 * Деструктор класса
	 */
	 public function __destruct()
	 {
		$_SESSION[self::ITEMS] = $this->items;
		$_SESSION[self::USER_NAME] = $this->userName;
		$_SESSION[self::USER_EMAIL] = $this->userEmail;
		$_SESSION[self::USER_PHONE] = $this->userPhone;
		$_SESSION[self::USER_COMMENT] = $this->userComment;
	 }
	/* ------------------- Данные пользователя  ---------------------- */
	/**
	 * Имя пользователя
	 * @var string
	 */
	 public $userName;	
	/**
	 * E-mail пользователя
	 * @var string
	 */
	 public $userEmail;	
	/**
	 * Телефон пользователя
	 * @var string
	 */
	 public $userPhone;	
	/**
	 * Комментарий пользователя
	 * @var string
	 */
	 public $userComment;
	/**
	 * Сообщение об ошибке
	 * @var string
	 */
	 public $errorMessages;

	/**
	 * Сохраняет данные пользователя
	 * @param string name наименование товара
	 * @param string email наименование товара
	 * @param string phone наименование товара
	 * @param string comment наименование товара
	 */
	public function setUserData($name, $email, $phone, $comment='')
	{
		$this->userName = $name;
		$this->userEmail = $email;
		$this->userPhone = $phone;
		if (!empty($comment)) $this->userComment = $comment;
	}
	/**
	 * Проверка что в корзине есть товары
	 * @return bool true - товаров нет
	 */
	public function isEmpty()
	{
		return (count($this->items) == 0);
	}


	/**
	 * Проверка к готовности заказа
	 * @return bool true - заказ можно оформлять
	 */
	public function isValid()
	{
		$this->errorMessages = array();
		
		// Заполнение полей
		if (empty($this->userName) || empty($this->userEmail) || empty($this->userPhone))
			$this->errorMessages[] = __('Please enter required data!', 'simple_basket');

		// Правильность E-mail
		if (!preg_match('/^([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})$/', $this->userEmail))
			$this->errorMessages[] = __('Please specify correct E-mail!', 'simple_basket');

		// Правильность телефона
		if (!preg_match('/^\+?[ \-\(\)0-9]{11,20}$/', $this->userPhone))
			$this->errorMessages[] = __('Please specify correct phone!', 'simple_basket');
			
		// В корзине есть товары
		if ($this->isEmpty())
			$this->errorMessages[] = __('Basket is empty.', 'simple_basket');

		return (count($this->errorMessages) == 0);
	}	



	/* ------------------- Работа с корзиной  ---------------------- */
	/**
	 * Нормализация цены из произвольной строки
	 * @param string price
	 * @return float
	 */
	public static function normalizePriceString($price)
	{
		$currency = __('USD', 'simple_basket');
		if (function_exists('simple_basket_get_currency_symbol'))
			$currency = simple_basket_get_currency_symbol();
		return (float) preg_replace(
			// patterns
			array(
				'/' . addslashes($currency) . '/',
				'/[\s]/',
				'/,/'
			),
			// replacements
			array(
				'',
				'',
				'.'
			),
			$price
		);
	}

	/**
	 * Добавляет в заказ очередной элемент
	 * @param string id Код продукта
	 * @param string title наименование товара
	 * @param float price цена товара
	 * @param string category категория товара
	 * @param array services дополнительные услуги (массив массивов с ключами SERVICE_LABEL и SERVICE_PRICE)
	 */
	 public function add($id, $title, $price, $category='', $services=array())
	 {
		
		// Нормализуем цену
		$price = self::normalizePriceString($price);

		// Нормализуем цены услуг
		$normalizedServices = array();
		if (is_array($services))
		{
			foreach ($services as $service)
			{
				if (!isset($service[self::SERVICE_LABEL]) || !isset($service[self::SERVICE_PRICE]))
					continue;
				$servicePrice = self::normalizePriceString($service[self::SERVICE_PRICE]);
				if ($servicePrice == 0)
					continue;
				$serviceId = isset($service[self::SERVICE_ID]) ? trim($service[self::SERVICE_ID]) : '';
				$normalizedServices[] = array(
					self::SERVICE_LABEL => $service[self::SERVICE_LABEL],
					self::SERVICE_PRICE => $servicePrice,
					self::SERVICE_ID => $serviceId
				);
			}
		}
		
		// Узнаём глобальный лимит количества одного товара
		$limit = 0;
		if (function_exists('simple_basket_get_item_limit'))
			$limit = simple_basket_get_item_limit();
		
		// Если такой элемент уже есть...
		if (array_key_exists($id, $this->items))
		{			
			// Увеличим количество с учётом лимита
			$currentQuo = isset($this->items[$id][self::QUO]) ? (int) $this->items[$id][self::QUO] : 0;
			if ($limit > 0 && $currentQuo >= $limit)
			{
				// Уже достигли лимита, не увеличиваем
				return;
			}
			$this->items[$id][self::QUO] = $currentQuo + 1;
			if ($limit > 0 && $this->items[$id][self::QUO] > $limit)
				$this->items[$id][self::QUO] = $limit;
		}
		else
		{
			// Добавим элемент
			$initialQuo = 1;
			if ($limit > 0 && $initialQuo > $limit)
				$initialQuo = $limit;
			$this->items[$id] = array
			(
				self::TITLE => $title,
				self::QUO	=> $initialQuo,
				self::PRICE => $price,
				self::CATEGORY => $category,
				self::SERVICE_COSTS => $normalizedServices
			);			
		}

	 }

	/**
	 * Обновляем в заказе количество элементов
	 * @param string id Код продукта
	 * @param int quo Число товаров
	 */
	 public function update($id, $quo)
	 {
		
		// Узнаём глобальный лимит количества одного товара
		$limit = 0;
		if (function_exists('simple_basket_get_item_limit'))
			$limit = simple_basket_get_item_limit();

		// Применяем лимит к запрошенному количеству (кроме нуля - удаление)
		if ($quo > 0 && $limit > 0 && $quo > $limit)
			$quo = $limit;
		
		// Если такой элемент уже есть...
		if (array_key_exists($id, $this->items))
		{			
			if ($quo == 0)
			{
				// Удаляем элемент
				unset($this->items[$id]);
			}
			else
			{
				// Изменяем количество
				$this->items[$id][self::QUO] = $quo;				
			}
		}
	 }

	/**
	 * Очистка заказа
	 */
	 public function clear()
	 {
		 $this->items = array();
		 $this->userComment = '';
	 }

	/**
	 * Возвращает HTML код корзины
	 * @param bool $showLinks Показывать ли ссылки на товары (по умолчанию true)
	 * @return string HTML код корзины
	 */
	 public function getHTML($showLinks = true)
	 { 
		// Узнаём глобальный лимит количества одного товара
		$limit = 0;
		if (function_exists('simple_basket_get_item_limit'))
			$limit = simple_basket_get_item_limit();
		$showQuo = ($limit != 1);

		// Символ валюты
		$currencySymbol = __('USD', 'simple_basket');
		if (function_exists('simple_basket_get_currency_symbol'))
			$currencySymbol = simple_basket_get_currency_symbol();

		// Заголовок колонки стоимости услуг
		$serviceColumnTitle = get_option('simple_basket_service_column_title');
		if (empty($serviceColumnTitle))
			$serviceColumnTitle = __('Service cost', 'simple_basket');

		// Определяем, есть ли в корзине какие-либо цены (по товарам или услугам)
		$hasPrices = false;
		foreach ($this->items as $id=>$item)
		{
			if ($item[self::QUO] <= 0) continue;
			$itemPrice = isset($item[self::PRICE]) ? (float) $item[self::PRICE] : 0;
			if ($itemPrice > 0) {
				$hasPrices = true;
				break;
			}
			if (isset($item[self::SERVICE_COSTS]) && is_array($item[self::SERVICE_COSTS])) {
				foreach ($item[self::SERVICE_COSTS] as $service) {
					$servicePrice = isset($service[self::SERVICE_PRICE]) ? (float) $service[self::SERVICE_PRICE] : 0;
					if ($servicePrice > 0) {
						$hasPrices = true;
						break 2;
					}
				}
			}
		}

		// Показывать ли столбцы с ценой и суммой
		$showPrice = $hasPrices;
		$showSumm = $hasPrices && ($limit != 1); // Сумма не нужна, если нет цен или лимит = 1

		$output = '<table class="basket">' .
			'<thead><tr>' .
				'<td>' . __('Title', 'simple_basket') . '</td>';
		if ($showQuo)
			$output .= '<td>' . __('Quo', 'simple_basket') . '</td>';
		if ($showPrice)
			$output .= '<td>' . __('Price', 'simple_basket') . '</td>';
		if ($showSumm)
			$output .= '<td>' . __('Summ', 'simple_basket') . '</td>';
		$output .= '</tr></thead><tbody>';
		
		$total = 0;
		$totalServices = 0; // Общая стоимость всех услуг
		$shownServiceIds = array(); // Массив для отслеживания уже показанных идентификаторов услуг
		$allServicesHTML = array(); // Массив для сбора всех услуг
		
		foreach ($this->items as $id=>$item)
		{
			// Если в корзине количество товара меньше или ноль - пропускаем
			if ($item[self::QUO] <= 0) continue;
			
			// Стоимость услуг (собираем для отображения и итогов, только если вообще работаем с ценами)
			$servicesSumm = 0;
			if ($hasPrices && isset($item[self::SERVICE_COSTS]) && is_array($item[self::SERVICE_COSTS]) && count($item[self::SERVICE_COSTS]) > 0)
			{
				foreach ($item[self::SERVICE_COSTS] as $service)
				{
					$label = isset($service[self::SERVICE_LABEL]) ? $service[self::SERVICE_LABEL] : '';
					$price = isset($service[self::SERVICE_PRICE]) ? $service[self::SERVICE_PRICE] : 0;
					if ($price <= 0) continue;
					
					$serviceId = isset($service[self::SERVICE_ID]) ? trim($service[self::SERVICE_ID]) : '';
					
					// Если идентификатор указан и услуга с таким идентификатором уже показана, пропускаем
					if (!empty($serviceId) && in_array($serviceId, $shownServiceIds))
						continue;
					
					// Если идентификатор указан, добавляем в список показанных
					if (!empty($serviceId))
						$shownServiceIds[] = $serviceId;
					
					$servicesSumm += $price;
					// Собираем услуги для вывода над итого
					$allServicesHTML[] = $label . ': ' . number_format($price, 2, ',', ' ') . $currencySymbol;
				}
			}
			
			// Сумма по строке товара: ТОЛЬКО цена × количество (БЕЗ услуг)
			$summ = 0;
			if ($hasPrices) {
				$summ = $item[self::QUO] * $item[self::PRICE];
				$total += $summ;
				// Услуги добавляем к общей сумме отдельно
				$totalServices += $servicesSumm;
			}
			
			/* translators: please replace USD by your country currency */
			// Формируем HTML для названия товара (со ссылкой или без)
			if ($showLinks) {
				$productLink = get_permalink($id);
				$titleHtml = !empty($productLink) ? '<a href="' . esc_url($productLink) . '">' . $item[self::TITLE] . '</a>' : $item[self::TITLE];
			} else {
				$titleHtml = $item[self::TITLE];
			}
			$output .= '<tr data-product-id="' . $id . '"><td class="title">' . $titleHtml . '</td>';
			if ($showQuo)
				$output .= '<td class="quo" data-value="' . $item[self::QUO] . '" data-id="' . $id . '">' . $item[self::QUO] . '</td>';
			if ($showPrice)
				$output .= '<td class="price" data-value="' . $item[self::PRICE] . '">' . number_format($item[self::PRICE], 2, ',', ' ') . $currencySymbol . '</td>';
			if ($showSumm)
				$output .= '<td class="summ" data-value="' . $item[self::PRICE] . '">' . number_format($summ, 2, ',', ' ') . $currencySymbol . '</td>';
			$output .= '</tr>';
		}
		
		$output .= '</tbody><tfoot>';
		
		if ($hasPrices)
		{
			// Если есть дополнительные услуги, выводим их перед итого
			if (count($allServicesHTML) > 0)
			{
				$colspan = 0;
				if ($showPrice)
					$colspan++;
				if ($showQuo)
					$colspan++;
				if ($showSumm)
					$colspan++;
				
				if ($colspan < 1) {
					$colspan = 1;
				}
				
				$servicesText = implode('<br />', $allServicesHTML);
				$output .= '<tr><td class="title">' . esc_html($serviceColumnTitle) . '</td>' .
							'<td class="service-info" colspan="' . $colspan . '">' . $servicesText . '</td></tr>';
			}
			
			// Строка итого (товары + услуги)
			$grandTotal = $total + $totalServices;
			$colspan = 0;
			if ($showPrice)
				$colspan++;
			if ($showQuo)
				$colspan++;
			if ($showSumm)
				$colspan++;
			if ($colspan < 1) {
				$colspan = 1;
			}
			$output .= '<tr><td class="title">' . __('Total', 'simple_basket') . '</td>' .
						'<td class="summ"  colspan="' . $colspan . '" data-value="' . $grandTotal . '">' . number_format($grandTotal, 2, ',', ' ') . $currencySymbol . '</td></tr>';
		}

		$output .= '</tfoot></table><!--/.basket-->';
		return $output;
	 }

	/**
	 * Возвращает общую сумму заказа
	 * @return float сумма заказа
	 */
	 public function getTotal()
	 {
	 	$total = 0;
		$totalServices = 0;
		$shownServiceIds = array(); // Массив для отслеживания уже учтенных идентификаторов услуг
		
		foreach ($this->items as $id=>$item)
		{
			// Сумма товаров: цена × количество
			$total += $item[self::QUO] * $item[self::PRICE];
			
			// Собираем услуги отдельно
			if (isset($item[self::SERVICE_COSTS]) && is_array($item[self::SERVICE_COSTS]))
			{
				foreach ($item[self::SERVICE_COSTS] as $service)
				{
					$price = isset($service[self::SERVICE_PRICE]) ? $service[self::SERVICE_PRICE] : 0;
					if ($price <= 0) continue;
					
					$serviceId = isset($service[self::SERVICE_ID]) ? trim($service[self::SERVICE_ID]) : '';
					
					// Если идентификатор указан и услуга с таким идентификатором уже учтена, пропускаем
					if (!empty($serviceId) && in_array($serviceId, $shownServiceIds))
						continue;
					
					// Если идентификатор указан, добавляем в список учтенных
					if (!empty($serviceId))
						$shownServiceIds[] = $serviceId;
					
					$totalServices += $price;
				}
			}
		}
		
		// Возвращаем общую сумму: товары + услуги
		return $total + $totalServices;
	 }


}

?>