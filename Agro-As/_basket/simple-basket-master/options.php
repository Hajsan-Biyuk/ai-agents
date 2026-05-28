<?php
// Параметры плагина
add_option('simple_basket_buynow_caption', __('Buy', 'simple_basket'));
add_option('simple_basket_buynow_limit_caption', __('Added', 'simple_basket'));
add_option('simple_basket_order_page', '');
add_option('simple_basket_catalog_price', __('Price', 'simple_basket'));
add_option('simple_basket_delivery', '0');
add_option('simple_basket_delivery_default', '0');
add_option('simple_basket_google_analytics_mode', '0');
add_option('simple_basket_order_compete_virtual_page', '');
add_option('simple_basket_conformation_email_post', '');
add_option('simple_basket_conformation_email_subject', '');
add_option('simple_basket_admin_email_post', '');
add_option('simple_basket_admin_email_subject', '');
// Дополнительные поля стоимости услуг (каждая строка: meta_key|Label)
add_option('simple_basket_service_fields', '');
// Заголовок колонки стоимости услуг
add_option('simple_basket_service_column_title', __('Service cost', 'simple_basket'));
// Глобальный лимит количества одного товара (0 - без лимита)
add_option('simple_basket_global_item_limit', '0');
// Символ / обозначение валюты
add_option('simple_basket_currency_symbol', __('USD', 'simple_basket'));
// Шапка PDF-документа (HTML, редактируется в админке)
$defaultPdfHeader = '<table>
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
add_option('simple_basket_pdf_header_html', $defaultPdfHeader);


// Принимаем данные
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	if (isset($_POST['buynowcaption']))
		update_option('simple_basket_buynow_caption', $_POST['buynowcaption']);

	if (isset($_POST['buynowlimitcaption']))
		update_option('simple_basket_buynow_limit_caption', $_POST['buynowlimitcaption']);

	if (isset($_POST['orderpage']))
		update_option('simple_basket_order_page', $_POST['orderpage']);

	if (isset($_POST['pricefield']))
		update_option('simple_basket_catalog_price', $_POST['pricefield']);

	if (isset($_POST['deliverymode']))
		update_option('simple_basket_delivery', '1');
	else
		update_option('simple_basket_delivery', '0');

	if (isset($_POST['deliveryplan']))
		update_option('simple_basket_delivery_default', $_POST['deliveryplan']);

	if (isset($_POST['googleanalyticsmode']))
		update_option('simple_basket_google_analytics_mode', $_POST['googleanalyticsmode']);
		
	if (isset($_POST['ordercompletevirtualvage']))
		update_option('simple_basket_order_compete_virtual_page', $_POST['ordercompletevirtualvage']);
			

	if (isset($_POST['confirmationEmailSubject']))
		update_option('simple_basket_conformation_email_subject', $_POST['confirmationEmailSubject']);
	if (isset($_POST['confirmemail']))
		update_option('simple_basket_conformation_email_post', $_POST['confirmemail']);

	if (isset($_POST['adminEmailSubject']))
		update_option('simple_basket_admin_email_subject', $_POST['adminEmailSubject']);
	if (isset($_POST['adminemail']))
		update_option('simple_basket_admin_email_post', $_POST['adminemail']);
		
	// Поля стоимости услуг
	if (isset($_POST['servicefields']))
		update_option('simple_basket_service_fields', $_POST['servicefields']);
		
	// Заголовок колонки стоимости услуг
	if (isset($_POST['servicecolumntitle']))
		update_option('simple_basket_service_column_title', $_POST['servicecolumntitle']);

	// Глобальный лимит количества одного товара
	if (isset($_POST['global_item_limit']))
		update_option('simple_basket_global_item_limit', (int) $_POST['global_item_limit']);

	// Символ / обозначение валюты
	if (isset($_POST['currencysymbol']))
		update_option('simple_basket_currency_symbol', $_POST['currencysymbol']);
		
	// HTML-шаблон шапки PDF
	if (isset($_POST['pdf_header_html']))
		update_option('simple_basket_pdf_header_html', wp_kses_post($_POST['pdf_header_html']));
		
}


// Для списка SELECT
function showSelected($current=0, $setting=0)
{
	if ($setting == $current) echo ' selected="selected"';
}


?>
<div id="simple_basket">
	<h2><?php 
		echo '<img src="' . plugins_url( 'img/basket-icon-32x32.png' , __FILE__ ) . '" > ';
		_e('Simple Basket', 'simple_basket');
	?></h2>
	<p><?php _e('Please change the settings with caution. It is better to go to a technical expert.', 'simple_basket')?></p>
	<form method="post" action="#">
		<script>jQuery(function($){
			$('#tabSB').tabs();
		})</script>	
		<div id="tabSB">
			<ul><!-- Табы -->
				<li><a href="#tabSBMain"><?php _e('Main Options', 'simple_basket')?></a></li>
				<li><a href="#tabSBGoogleAnalytics"><?php _e('Google Analytics', 'simple_basket')?></a></li>
				<li><a href="#tabSBMessages"><?php _e('Messages', 'simple_basket')?></a></li>
			</ul>
			<div id="tabSBMain"><!-- Основные параетры корзины -->
				<fieldset>
					<legend><?php _e('Basket Options', 'simple_basket')?></legend>
					<div class="control">
						<label for="buyNowCaption"><?php _e('Buy Now Button Caption', 'simple_basket')?></label>
						<input id="buyNowCaption" class="textString" type="text" name="buynowcaption" value="<?php echo get_option('simple_basket_buynow_caption'); ?>" />
						<p><?php _e('This parameter specifies the caption on the buttom [Buy]. To display this button use shortcode [basket-buy-button] or call function &lt;?php showBuyNowButton() ?&gt;', 'simple_basket')?></p>
					</div>
					<div class="control">
						<label for="buyNowLimitCaption"><?php _e('Buy Now Button Caption (Limit Reached)', 'simple_basket')?></label>
						<input id="buyNowLimitCaption" class="textString" type="text" name="buynowlimitcaption" value="<?php echo esc_attr(get_option('simple_basket_buynow_limit_caption')); ?>" />
						<p><?php _e('This parameter specifies the caption on the button when the item limit is reached (for example: "Added", "In basket").', 'simple_basket')?></p>
					</div>
					<div class="control">
						<label for="orderPage"><?php _e('Order Page', 'simple_basket')?></label>
						<input id="orderPage" class="textString" type="text" name="orderpage" value="<?php echo get_option('simple_basket_order_page'); ?>" />
						<p><?php _e('This parameter specifies the URL of page contains order form. To display thу order form use shortcode [basket-order-form]', 'simple_basket')?></p>
					</div>
				</fieldset>
				<fieldset>
					<legend><?php _e('Product Catalog', 'simple_basket')?></legend>
					<div class="control">
						<label for="priceCustomFiled"><?php _e('Price Custom Field', 'simple_basket')?></label>
						<input id="priceCustomFiled" class="textString" type="text" name="pricefield" value="<?php echo get_option('simple_basket_catalog_price'); ?>" />
						<p><?php _e('This parameter specifies the name of product custom field that contains the price.', 'simple_basket')?></p>
					</div>
					<div class="control">
						<label for="currencySymbol"><?php _e('Currency Symbol', 'simple_basket')?></label>
						<input id="currencySymbol" class="textString" type="text" name="currencysymbol" value="<?php echo esc_attr(get_option('simple_basket_currency_symbol')); ?>" />
						<p><?php _e('This parameter specifies the currency symbol that will be shown after prices (for example: ₽, руб., $, €).', 'simple_basket')?></p>
					</div>
					<div class="control">
						<label for="serviceFields"><?php _e('Service Cost Fields', 'simple_basket')?></label>
						<textarea id="serviceFields" class="textString" name="servicefields" rows="4" cols="50"><?php echo esc_textarea(get_option('simple_basket_service_fields')); ?></textarea>
						<p><?php _e('Specify additional service cost fields. Each line: meta_key|title_meta_key|id_meta_key (for example: service_price|service_title|service_id). The meta_key is used as custom field name for the product price, title_meta_key is custom field name for the service title, id_meta_key is custom field name for the service identifier. Services with the same identifier will be shown only once in the basket.', 'simple_basket')?></p>
					</div>
					<div class="control">
						<label for="serviceColumnTitle"><?php _e('Service Cost Column Title', 'simple_basket')?></label>
						<input id="serviceColumnTitle" class="textString" type="text" name="servicecolumntitle" value="<?php echo esc_attr(get_option('simple_basket_service_column_title')); ?>" />
						<p><?php _e('This parameter specifies the title of the service cost column in the basket table.', 'simple_basket')?></p>
					</div>
					<div class="control">
						<label for="globalItemLimit"><?php _e('Global Item Quantity Limit', 'simple_basket')?></label>
						<input id="globalItemLimit" class="textString" type="number" min="0" step="1" name="global_item_limit" value="<?php echo (int) get_option('simple_basket_global_item_limit'); ?>" />
						<p><?php _e('Maximum quantity of a single product in one order. 0 means no limit. If set to 1, quantity column will be hidden and each product can be added only once.', 'simple_basket')?></p>
					</div>
				</fieldset>
				<fieldset>
					<legend><?php _e('PDF Header Template', 'simple_basket')?></legend>
					<div class="control">
						<label for="pdfHeaderHtml"><?php _e('PDF Header HTML', 'simple_basket')?></label>
						<?php
						$pdfHeaderHtml = get_option('simple_basket_pdf_header_html');
						wp_editor(
							$pdfHeaderHtml,
							'pdfHeaderHtml',
							array(
								'media_buttons' => false,
								'textarea_name' => 'pdf_header_html',
								'teeny' => true,
							)
						);
						?>
						<p><?php _e('HTML template for the PDF header (organization details, contacts, etc.). Use the [order-date] shortcode inside this HTML to automatically insert the current order date and time.', 'simple_basket')?></p>
					</div>
				</fieldset>	
				<fieldset>
					<legend><?php _e('Delivery', 'simple_basket')?></legend>
					<div class="control">
						<label for="deliveryMode"><?php _e('Paid Delivery', 'simple_basket')?></label>
						<input id="deliveryMode" type="checkbox" name="deliverymode" value="1"<?php
							if (get_option('simple_basket_delivery') == '1')
								echo ' checked="checked"'?> />
						<span><?php _e('My shop has different paid delivery plans.', 'simple_basket')?></span>
						<p><?php _e('After change this mode you must update settings at <a href="/wp-admin/options-permalink.php">permalink options page</a>.', 'simple_basket')?></p>
					</div>
					<div class="control">
						<label for="deliveryPlan"><?php _e('Default Delivery Plan', 'simple_basket')?></label>
						<select id="deliveryPlan" name="deliveryplan">
							<?php $defaultDeliveryPlan = get_option('simple_basket_delivery_default'); ?>
							<option value="0"<?php showSelected(0, $defaultDeliveryPlan)?>><?php _e('None', 'simple_basket')?></option>
							<?php 
								$args = array('post_type' => 'delivery');
								$deliveryType = new WP_Query($args);
								while ($deliveryType->have_posts())
								{
									$deliveryType->the_post(); ?>
							<option value="<?php echo $deliveryType->post->ID ?>"<?php showSelected($deliveryType->post->ID, $defaultDeliveryPlan)?>><?php echo get_the_title($deliveryType->post->ID) ?></option>
							<?php
								}
							?>
						</select>
						<p><?php _e('This parameter specifies the default delivery plan at customer order.', 'simple_basket')?></p>
					</div>
				</fieldset>
			</div><!--/tabSBMain-->
		
			<div id="tabSBGoogleAnalytics"><!-- Параметры Google Analytics -->
				<fieldset>
					<legend><?php _e('Google Analytics', 'simple_basket')?></legend>
					<div class="control">
						<label for="googleAnalytics"><?php _e('Google Analytics intergation', 'simple_basket')?></label>
						<?php 
							$gaMode = get_option('simple_basket_google_analytics_mode'); 
						?>
						<select id="googleAnalytics" name="googleanalyticsmode">
							<option value="0"<?php showSelected(0, $gaMode)?>><?php _e('None', 'simple_basket')?></option>
							<option value="1"<?php showSelected(1, $gaMode)?>><?php _e('Google Analytics (ga.js)', 'simple_basket')?></option>
							<option value="2"<?php showSelected(2, $gaMode)?>><?php _e('Universal Analytics (analytics.js)', 'simple_basket')?></option>
						</select>
						<p><?php _e('This parameter specifies the integration mode with Google Analytits. This option adds the necessary tracking code to the page "Thanks for your purchase". For correct intergation e-commerce mode must be enabled at Google Analytics profile options.', 'simple_basket')?></p>
					</div>
					<div class="control">
						<label for="ordercompletevirtualvage"><?php _e('Virtual page Order Complete', 'simple_basket')?></label>
						<input id="ordercompletevirtualvage" class="textString" type="text" name="ordercompletevirtualvage" value="<?php echo get_option('simple_basket_order_compete_virtual_page'); ?>" />
						<p><?php _e('This parameter specifies the URL of virtual page tracking GA when order completed. Leave it empty to disable this option.', 'simple_basket')?></p>
					</div>					
					
				</fieldset>		
			</div><!--/tabSBGoogleAnalytics-->
			
			<div id="tabSBMessages"><!-- Параметры писем -->
				<fieldset>
					<legend><?php _e('Confirmation E-mail to customer', 'simple_basket')?></legend>
					<div class="control">
						<label for="confirmationEmailSubject"><?php _e('Subject', 'simple_basket')?></label>
						<input id="confirmationEmailSubject" class="textString" type="text" name="confirmationEmailSubject" value="<?php echo get_option('simple_basket_conformation_email_subject'); ?>" />
					</div>				
					<div class="control">
						<label for="confirmationEmail"><?php _e('User E-mail Text', 'simple_basket')?></label>
						<?php wp_editor(get_option('simple_basket_conformation_email_post'), 'confirmationEmail', 
							array(
								'media_buttons' => false,
								'textarea_name' => 'confirmemail',
								'teeny' => 'true',
							)) ?>
					</div>
				</fieldset>
				<fieldset>	
					<legend><?php _e('Confirmation E-mail to administrator', 'simple_basket')?></legend>
					<div class="control">
						<label for="adminEmailSubject"><?php _e('Subject', 'simple_basket')?></label>
						<input id="adminEmailSubject" class="textString" type="text" name="adminEmailSubject" value="<?php echo get_option('simple_basket_admin_email_subject'); ?>" />
					</div>
					<div class="control">
						<label for="adminEmail"><?php _e('Admin E-mail Text', 'simple_basket')?></label>
						<?php wp_editor(get_option('simple_basket_admin_email_post'), 'adminEmail', 
							array(
								'media_buttons' => false,
								'textarea_name' => 'adminemail',
								'teeny' => 'true',
							)) ?>
					</div>
					<div>
						<?php _e('<p>This parameters specify the test fo user and admin confirmation e-mail\'s.<br> ' .  
									'If this paramater is empty no e-mail will send to user.<br/> '.
									'You may user the following short codes:</p><ul> ' . 
									'<li>[order-code] - the order code</li>' .
									'<li>[order-customer] - the customer name </li>' .
									'<li>[order-email] - the customer E-mail </li>' .
									'<li>[order-phone] - the customer phone number </li>' .
									'<li>[order-comment] - the order comment </li>' .
									'<li>[order-items] - the table with order items</li>' .
									'</ul>', 'simple_basket')?>

					</div>
				</fieldset>
			</div><!--/tabSBGoogleAnalytics-->
		</div><!--/tabSB-->
		<div>
			<button class="button button-primary" type="submit"><?php _e('Update settings', 'simple_basket')?></button>
		</div>
	</form>
</div>