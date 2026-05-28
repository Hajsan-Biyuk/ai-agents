<?php
/*
Plugin Name: Simple basket
Plugin URI: https://github.com/ivannin/simple-basket
Description: Простая корзина для Wordpress
Version: 0.4
Author: Sheer Dev
Author URI: http://ivannikitin.com
License:
	Copyright 2013  Ivan Nikitin  (email : ivan@nikitin.org)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
================================================================================
*/
// Старт сессии
@session_start();


// ------------------------- Инициализация -------------------------
add_action('plugins_loaded', 'simple_basketInit');
function simple_basketInit() 
{
	// Локализация
	load_plugin_textdomain( 'simple_basket', false, basename(dirname(__FILE__)) . '/lang/' );
	
	// Режим и типы доставки
	if (get_option('simple_basket_delivery') == '1')
		include(plugin_dir_path(__FILE__).'delivery.php');
}


// ---------------- Страница параметров плагина ----------------
add_action( 'admin_menu', 'simple_basketCreateAdminMenu' );
function simple_basketCreateAdminMenu() {
	add_options_page(__('Simple Basket Options', 'simple_basket'), __('Simple Basket', 'simple_basket'), 
		'manage_options', 'simple-baslet', 'simple_basketOptions' );
}

// ---------------- Загрузка нужных компонентов на страницу аминистрирования плагинов ----------------
add_action('admin_print_scripts', 'simple_basket_adminJS' );
function simple_basket_adminJS() 
{
	wp_enqueue_script('jquery-ui-tabs');
    wp_enqueue_script('editor');
    wp_enqueue_script('thickbox');
    add_action( 'admin_head', 'wp_tiny_mce' );	
}

add_action('admin_print_styles', 'simple_basket_adminCSS' );
function simple_basket_adminCSS() 
{
	wp_register_style('simple-basket-admin-css', plugins_url('css/simple-basket-admin.css', __FILE__));	
	wp_register_style('simple-basket-admin-jquery-ui', 
		'http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/smoothness/jquery-ui.min.css', 
		null, '1.10.3');
	wp_enqueue_style('simple-basket-admin-css');
	wp_enqueue_style('simple-basket-admin-jquery-ui');
}

function simple_basketOptions() 
{
	if (!current_user_can( 'manage_options' ))
		wp_die( __( 'You do not have sufficient permissions to access this page.','simple_basket') );
	
	include(plugin_dir_path(__FILE__).'options.php');
}

// ----------------------- GET параметры -----------------------
define('SIMPLE_BASKET_ADD',			'add');
define('SIMPLE_BASKET_UPDATE',		'update');
define('SIMPLE_BASKET_DELETE',		'delete');
define('SIMPLE_BASKET_CHECKOUT',	'checkout');
define('SIMPLE_BASKET_CLEAR',		'clear');
define('SIMPLE_BASKET_MODE',		'mode');

// ----------------------- Подключения -----------------------
require(plugin_dir_path(__FILE__) . 'classes/SimpleBasketOrderForm.php');
require(plugin_dir_path(__FILE__) . 'classes/SimpleBasketOrder.php');
require(plugin_dir_path(__FILE__) . 'inc/product-line.php');
require(plugin_dir_path(__FILE__) . 'integrations/acf-repeater.php');
require(plugin_dir_path(__FILE__) . 'integrations/simple-meta.php');
require(plugin_dir_path(__FILE__) . 'integrations/agro-as.php');
require(plugin_dir_path(__FILE__) . 'shortcodes.php');
require(plugin_dir_path(__FILE__) . 'order-form.php');
require(plugin_dir_path(__FILE__) . 'ajax-api.php');


// ------------------- Некоторые общие функии ----------------
// Возвращает значение произвольных полей
function simple_basket_custom_fields($postId, $customField) 
{
	$values = get_post_meta($postId, $customField);
	if (count($values) == 0) 
		return '';
	else
		return trim($values[0]);		
}

// Разбор настроек полей стоимости услуг
function simple_basket_get_service_fields_definitions()
{
	$result = array();
	$raw = get_option('simple_basket_service_fields');
	if (empty($raw)) return $result;
	$lines = preg_split('/\r\n|\r|\n/', $raw);
	if (!is_array($lines)) return $result;
	foreach ($lines as $line)
	{
		$line = trim($line);
		if ($line === '') continue;
		$parts = explode('|', $line, 3);
		$metaKey = trim($parts[0]);
		$titleMetaKey = isset($parts[1]) ? trim($parts[1]) : '';
		$idMetaKey = isset($parts[2]) ? trim($parts[2]) : '';
		if ($metaKey === '') continue;
		$result[] = array(
			'meta_key' => $metaKey,
			'title_meta_key' => $titleMetaKey,
			'id_meta_key' => $idMetaKey
		);
	}
	return $result;
}

// Получение стоимостей услуг для товара
function simple_basket_get_product_services($postId)
{
	$definitions = simple_basket_get_service_fields_definitions();
	$services = array();
	if (!is_array($definitions) || count($definitions) == 0) return $services;

	foreach ($definitions as $def)
	{
		$metaKey = $def['meta_key'];
		$titleMetaKey = $def['title_meta_key'];
		$idMetaKey = $def['id_meta_key'];
		$price = simple_basket_custom_fields($postId, $metaKey);
		if ($price === '') continue;
		
		// Получаем название услуги из custom field
		$label = '';
		if (!empty($titleMetaKey)) {
			$label = simple_basket_custom_fields($postId, $titleMetaKey);
		}
		if ($label === '') {
			// Fallback на meta_key, если title_meta_key не указан или пуст
			$label = $metaKey;
		}
		
		// Получаем идентификатор услуги из custom field
		$serviceId = '';
		if (!empty($idMetaKey)) {
			$serviceId = simple_basket_custom_fields($postId, $idMetaKey);
		}
		
		$services[] = array(
			SimpleBasketOrder::SERVICE_LABEL => $label,
			SimpleBasketOrder::SERVICE_PRICE => $price,
			SimpleBasketOrder::SERVICE_ID => $serviceId
		);
	}
	return $services;
}

// Символ / обозначение валюты
function simple_basket_get_currency_symbol()
{
	$symbol = get_option('simple_basket_currency_symbol');
	if ($symbol === '' || $symbol === false)
	{
		// Fallback на перевод старой строки
		$symbol = __('USD', 'simple_basket');
	}
	return $symbol;
}

// Глобальный лимит количества одного товара (0 - без лимита)
function simple_basket_get_item_limit()
{
	$limit = (int) get_option('simple_basket_global_item_limit');
	if ($limit < 0) $limit = 0;
	return $limit;
}

// Количество товаров в корзине (сумма QUO по всем позициям)
function simple_basket_get_items_count()
{
	$basket = SimpleBasketOrder::create();
	$count = 0;
	if (is_array($basket->items)) {
		foreach ($basket->items as $item) {
			if (isset($item[SimpleBasketOrder::QUO]) && $item[SimpleBasketOrder::QUO] > 0) {
				$count += (int) $item[SimpleBasketOrder::QUO];
			}
		}
	}
	return $count;
}

// Вывести количество товаров в корзине (для использования в шаблонах темы)
function simple_basket_items_count()
{
	echo simple_basket_get_items_count();
}


// Подключения CSS, JavaScript и т.п. 
add_action('wp_enqueue_scripts', 'simple_basket_externals');
function simple_basket_externals() 
{
    // Respects SSL, Style.css is relative to the current file
    wp_register_style('simple-basket-css', plugins_url('css/simple-basket.min.css', __FILE__) );
    wp_enqueue_style('simple-basket-css' );
}

// Обработка вывода версии корзины для печати (через браузер)
add_action('template_redirect', 'simple_basket_handle_print_view');
function simple_basket_handle_print_view()
{
	if (!isset($_GET['simple_basket_print']) || $_GET['simple_basket_print'] != '1')
		return;

	// Формируем страницу только с корзиной (без темы)
	$orderForm = new SimpleBasketOrderForm();
	// Обработка возможных действий (обновление количества и т.п.)
	$orderForm->handle();

	// Простой HTML для печати (дальше пользователь может сохранить как PDF в браузере)
	echo '<!DOCTYPE html><html><head><meta charset="' . esc_attr(get_bloginfo('charset')) . '">';
	echo '<title>' . esc_html(get_bloginfo('name')) . ' - ' . esc_html(__('Basket', 'simple_basket')) . '</title>';
	echo '<style>body{font-family:Arial,Helvetica,sans-serif;font-size:14px;margin:20px;} h1{font-size:20px;margin-bottom:20px;} table.basket{width:100%;border-collapse:collapse;} table.basket td,table.basket th{border:1px solid #ccc;padding:4px 6px;}</style>';
	echo '</head><body onload="window.print()">';
	echo '<h1>' . esc_html(__('Basket', 'simple_basket')) . '</h1>';
	echo $orderForm->getBasketHTML();
	echo '</body></html>';
	exit;
}

// Обработка скачивания корзины в виде PDF файла
add_action('template_redirect', 'simple_basket_handle_pdf_download');
function simple_basket_handle_pdf_download()
{
	if (!isset($_GET['simple_basket_pdf']) || $_GET['simple_basket_pdf'] != '1')
		return;

	// Подключаем dompdf из папки lib (без Composer)
	$autoloadPath = plugin_dir_path(__FILE__) . 'lib/dompdf/autoload.inc.php';
	if (!file_exists($autoloadPath)) {
		wp_die(__('PDF library (dompdf) is not installed. Please upload dompdf to lib/dompdf.', 'simple_basket'));
	}
	require_once $autoloadPath;

	// Собираем HTML корзины для PDF (без кнопок удаления и без ссылок)
	$orderForm = new SimpleBasketOrderForm();
	$orderForm->handle();
	$basket = SimpleBasketOrder::create();
	$basketHtml = $basket->getHTML(false); // false = без ссылок на товары
	
	// Формируем читаемую дату и время
	$months_ru = array(
		1 => 'января', 2 => 'февраля', 3 => 'марта', 4 => 'апреля',
		5 => 'мая', 6 => 'июня', 7 => 'июля', 8 => 'августа',
		9 => 'сентября', 10 => 'октября', 11 => 'ноября', 12 => 'декабря'
	);
	$day = date('j');
	$month = $months_ru[(int)date('n')];
	$year = date('Y');
	$time = date('H:i');
	$now_date = $day . ' ' . $month . ' ' . $year . ' г. в ' . $time;

	// Получаем HTML-шаблон шапки PDF из настроек и подставляем дату
	$pdfHeaderTemplate = get_option('simple_basket_pdf_header_html');
	if (empty($pdfHeaderTemplate)) {
		$pdfHeaderTemplate = '<table>
		<tr>
			<td colspan="2">Example Company LLC</td>
		</tr>
		<tr>
			<td>Полное наименование</td>
			<td>Общество с ограниченной ответственностью &quot;Пример компании для тестового шаблона&quot;</td>
		</tr>
		<tr>
			<td>Сокращенное наименование</td>
			<td>ООО &quot;Пример компании&quot;</td>
		</tr>
		<tr>
			<td>ИНН</td>
			<td>0000000000</td>
		</tr>
		<tr>
			<td>ОГРН</td>
			<td>0000000000000</td>
		</tr>
		<tr>
			<td>Юридический адрес</td>
			<td>123456, Город, Улица, дом 1</td>
		</tr>
		<tr>
			<td>Контакты</td>
			<td>Контактное лицо, должность</td>
		</tr>
		<tr>
			<td>Телефон</td>
			<td>+0 (000) 000-00-00</td>
		</tr>
		<tr>
			<td>Электронная почта</td>
			<td>example@example.com</td>
		</tr>
		<tr>
			<td>Дата и время</td>
			<td>[order-date]</td>
		</tr>
	</table>';
	}
	$orderDateSafe = esc_html($now_date);
	$pdfHeaderHtml = str_replace('[order-date]', $orderDateSafe, $pdfHeaderTemplate);

	$html = '<!DOCTYPE html><html><head><meta charset="' . esc_attr(get_bloginfo('charset')) . '">';
	$html .= '<title>' . esc_html(get_bloginfo('name')) . ' - ' . esc_html(__('Basket', 'simple_basket')) . '</title>';
	$html .= '<style>
		body{font-family:DejaVu Sans,Arial,Helvetica,sans-serif;font-size:12px;}
		h1{font-size:18px;margin-bottom:15px;}
		table.basket{width:100%;border-collapse:collapse;margin-top:10px;}
		table.basket td,table.basket th{border:1px solid #ccc;padding:4px 6px;}
		table.basket td.price,table.basket td.summ {white-space: nowrap;}
		table.basket td.delete, .basketForm .btn-main {display:none}
	</style>';
	$html .= '</head><body>';
	$html .= '<h1>Список заказов:</h1>';
	$html .= wp_kses_post($pdfHeaderHtml);
	$html .= $basketHtml;
	$html .= '</body></html>';

	// Генерация PDF
	$dompdf = new \Dompdf\Dompdf();
	$dompdf->loadHtml($html);
	$dompdf->setPaper('A4', 'portrait');
	$dompdf->render();

	$filename = 'order-' . date('Ymd-His') . '.pdf';

	// Отдаём PDF как скачиваемый файл
	header('Content-Type: application/pdf');
	header('Content-Disposition: attachment; filename="' . $filename . '"');
	echo $dompdf->output();
	exit;
}

// Для почты
function simple_basket_set_html_content_type()
{
	return 'text/html';
}

/**
* Вместо wp_redirect() используем свою функцию, так как
* скорее всего git добавил концы строк в файлы и wp_redirect() не работает (был начат вывод страницы).
* Разбираться лениво, поэтому делаю переадресацию скриптом JavaScript.
*/
function simple_basket_redirect($url)
{
	echo "<script>console.log('$url');location.replace('$url');</script>";
	exit;

}
?>