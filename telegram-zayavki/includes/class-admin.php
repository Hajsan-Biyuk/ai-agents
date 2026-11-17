<?php
/**
 * Страница настроек.
 *
 * @package Telegram_Zayavki
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Админка плагина: токен, группы, тест, выбор форм и заказов.
 */
class TZ_Admin {
	const NOTICE = 'telegram_zayavki_notice';

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
	 * Регистрирует меню и обработчик формы.
	 */
	public function hooks() {
		add_action('admin_menu', array($this, 'menu'));
		add_action('admin_enqueue_scripts', array($this, 'assets'));
		add_action('admin_post_telegram_zayavki_handle', array($this, 'handle'));
	}

	/**
	 * Пункт меню.
	 */
	public function menu() {
		add_menu_page(
			'Telegram заявки',
			'Telegram заявки',
			'manage_options',
			'telegram-zayavki',
			array($this, 'render'),
			'dashicons-format-chat',
			58
		);
	}

	/**
	 * Стили только на странице плагина.
	 *
	 * @param string $hook Суффикс страницы.
	 */
	public function assets($hook) {
		if ($hook !== 'toplevel_page_telegram-zayavki') {
			return;
		}

		wp_register_style('telegram-zayavki-admin', false, array(), TZ_VERSION);
		wp_enqueue_style('telegram-zayavki-admin');
		wp_add_inline_style(
			'telegram-zayavki-admin',
			'.tz-card{background:#fff;border:1px solid #c3c4c7;box-shadow:0 1px 1px rgba(0,0,0,.04);padding:4px 20px 8px;margin:16px 0;max-width:840px}' .
			'.tz-card h2{padding-top:12px}.tz-muted{color:#646970}.tz-forms label{display:block;margin:8px 0}' .
			'.tz-actions{display:flex;flex-wrap:wrap;gap:8px;align-items:center;margin:12px 0 20px}' .
			'.tz-actions .button{margin:0}'
		);
	}

	/**
	 * Сохраняет настройки и выполняет служебные действия.
	 */
	public function handle() {
		if (!current_user_can('manage_options')) {
			wp_die('Недостаточно прав.');
		}
		check_admin_referer('telegram_zayavki_handle');

		$action = isset($_POST['tz_action']) ? sanitize_key(wp_unslash($_POST['tz_action'])) : 'save';
		$settings = TZ_Settings::get();
		$token_error = $this->apply_token($settings);
		if (is_wp_error($token_error)) {
			$this->redirect_notice('error', $token_error->get_error_message());
		}

		$settings['woo_enabled'] = empty($_POST['woo_enabled']) ? 0 : 1;
		if (function_exists('wpcf7')) {
			$settings['cf7_forms'] = array();
			if (!empty($_POST['cf7_forms']) && is_array($_POST['cf7_forms'])) {
				foreach ($_POST['cf7_forms'] as $form_id) {
					$form_id = (int) $form_id;
					if ($form_id > 0) {
						$settings['cf7_forms'][] = $form_id;
					}
				}
			}
			$settings['cf7_forms'] = array_values(array_unique($settings['cf7_forms']));
		}

		$posted_chat = isset($_POST['chat_id']) ? (string) wp_unslash($_POST['chat_id']) : '';
		$posted_chat = preg_replace('/[^0-9-]/', '', $posted_chat);
		if ($posted_chat !== '' && isset($settings['chats'][$posted_chat])) {
			$settings['chat_id'] = $posted_chat;
		} elseif ($posted_chat === '') {
			$settings['chat_id'] = '';
		}

		if ($action === 'refresh' || $action === 'drop_webhook') {
			TZ_Settings::update($settings);
			$this->refresh_chats($action === 'drop_webhook');
		}

		if ($action === 'test') {
			if ($settings['chat_id'] === '' || $settings['bot_token'] === '') {
				TZ_Settings::update($settings);
				$this->redirect_notice('error', 'Сохраните токен и выберите группу, затем отправьте тест ещё раз.');
			}
			TZ_Settings::update($settings);
			$client = new TZ_Telegram($settings['bot_token']);
			$result = $client->send_message(
				$settings['chat_id'],
				'<b>Проверка связи</b>' . "\n" . 'Плагин «Telegram заявки» отправил тестовое сообщение в эту группу.'
			);
			if (is_wp_error($result)) {
				TZ_Settings::set_last_error($result->get_error_message());
				$this->redirect_notice('error', 'Тест не отправлен. ' . $result->get_error_message());
			}
			TZ_Settings::clear_last_error();
			$this->redirect_notice('success', 'Тестовое сообщение отправлено в выбранную группу.');
		}

		TZ_Settings::update($settings);
		$this->redirect_notice('success', 'Настройки сохранены.');
	}

	/**
	 * Страница настроек.
	 */
	public function render() {
		if (!current_user_can('manage_options')) {
			return;
		}

		$settings = TZ_Settings::get();
		$notice = get_transient(self::NOTICE . '_' . get_current_user_id());
		if (is_array($notice)) {
			delete_transient(self::NOTICE . '_' . get_current_user_id());
		}
		$error = TZ_Settings::last_error();
		$forms = $this->cf7_forms();
		$chats = $settings['chats'];
		uasort(
			$chats,
			static function ($a, $b) {
				return strcasecmp((string) ($a['title'] ?? ''), (string) ($b['title'] ?? ''));
			}
		);

		echo '<div class="wrap">';
		echo '<h1>Telegram заявки</h1>';

		if (is_array($notice) && !empty($notice['text'])) {
			$type = (string) ($notice['type'] ?? '');
			$class = 'notice-success';
			if ($type === 'error') {
				$class = 'notice-error';
			} elseif ($type === 'warning') {
				$class = 'notice-warning';
			}
			echo '<div class="notice ' . esc_attr($class) . ' is-dismissible"><p>' . esc_html($notice['text']) . '</p></div>';
		}
		if ($error) {
			echo '<div class="notice notice-warning"><p><strong>Последняя ошибка отправки:</strong> ' . esc_html($error['text']) . '</p></div>';
		}

		echo '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '">';
		echo '<input type="hidden" name="action" value="telegram_zayavki_handle">';
		wp_nonce_field('telegram_zayavki_handle');
		echo '<button type="submit" class="screen-reader-text" name="tz_action" value="save">Сохранить</button>';

		echo '<div class="tz-card">';
		echo '<h2>Бот</h2>';
		echo '<p class="tz-muted">Токен выдаёт @BotFather. После сохранения бот проверяется, а список групп можно обновить.</p>';
		echo '<table class="form-table" role="presentation"><tr><th scope="row"><label for="tz-token">Токен бота</label></th><td>';
		echo '<input type="password" class="regular-text" id="tz-token" name="bot_token" value="" autocomplete="new-password" placeholder="' . esc_attr($settings['bot_token'] !== '' ? 'Токен сохранён' : '') . '">';
		if ($settings['bot_username'] !== '') {
			echo '<p class="description">Подключён бот @' . esc_html($settings['bot_username']) . '. Оставьте поле пустым, чтобы не менять токен.</p>';
		}
		echo '</td></tr></table>';
		echo '</div>';

		echo '<div class="tz-card">';
		echo '<h2>Группа</h2>';
		echo '<p class="tz-muted">В списке только группы, где бот уже состоит. Если группы нет, добавьте бота в группу, отправьте туда /start и нажмите «Обновить список групп».</p>';
		echo '<table class="form-table" role="presentation"><tr><th scope="row"><label for="tz-chat">Группа</label></th><td>';
		echo '<select id="tz-chat" name="chat_id">';
		echo '<option value="">Не выбрана</option>';
		foreach ($chats as $chat) {
			if (!is_array($chat) || empty($chat['id'])) {
				continue;
			}
			$id = (string) $chat['id'];
			$title = (string) ($chat['title'] ?? $id);
			echo '<option value="' . esc_attr($id) . '"' . selected($settings['chat_id'], $id, false) . '>' . esc_html($title) . '</option>';
		}
		echo '</select>';
		if ($chats === array()) {
			echo '<p class="description">Список пока пуст.</p>';
		}
		echo '</td></tr></table>';
		echo '<p class="tz-actions">';
		echo '<button type="submit" class="button button-secondary" name="tz_action" value="refresh">Обновить список групп</button>';
		echo '<button type="submit" class="button" name="tz_action" value="drop_webhook" onclick="return confirm(\'Снять webhook этого бота и заново прочитать группы? Другая интеграция, которая слушает webhook, перестанет получать обновления.\');">Снять webhook и обновить</button>';
		echo '</p>';
		echo '<p class="tz-muted">Кнопка «Снять webhook» нужна только если Telegram отвечает, что getUpdates недоступен из-за webhook.</p>';
		echo '</div>';

		echo '<div class="tz-card">';
		echo '<h2>Проверка</h2>';
		echo '<p class="tz-muted">Тест уходит в группу, выбранную в списке выше. Перед отправкой настройки сохраняются.</p>';
		echo '<button type="submit" class="button button-secondary" name="tz_action" value="test">Отправить тест</button>';
		echo '</div>';

		echo '<div class="tz-card">';
		echo '<h2>Формы Contact Form 7</h2>';
		if (!function_exists('wpcf7')) {
			echo '<p>Contact Form 7 не активен. После установки плагина формы появятся в этом списке.</p>';
		} elseif ($forms === array()) {
			echo '<p>Форм Contact Form 7 пока нет.</p>';
		} else {
			echo '<p class="tz-muted">В группу уходят только отмеченные формы, и только после успешной отправки письма.</p>';
			echo '<div class="tz-forms">';
			foreach ($forms as $form) {
				$id = (int) $form->ID;
				echo '<label><input type="checkbox" name="cf7_forms[]" value="' . esc_attr((string) $id) . '"' . checked(in_array($id, $settings['cf7_forms'], true), true, false) . '> ';
				echo esc_html(get_the_title($form)) . ' <span class="tz-muted">#' . esc_html((string) $id) . '</span></label>';
			}
			echo '</div>';
		}
		echo '</div>';

		echo '<div class="tz-card">';
		echo '<h2>Заказы WooCommerce</h2>';
		if (!class_exists('WooCommerce')) {
			echo '<p>WooCommerce не активен. Галочка сохранится и начнёт работать после установки магазина.</p>';
		} else {
			echo '<p class="tz-muted">Один заказ отправляется один раз: при оформлении или когда он переходит в обработку либо ожидание оплаты.</p>';
		}
		echo '<label><input type="checkbox" name="woo_enabled" value="1"' . checked($settings['woo_enabled'], 1, false) . '> Отправлять заказы WooCommerce</label>';
		echo '</div>';

		echo '<p class="tz-actions"><button type="submit" class="button button-primary" name="tz_action" value="save">Сохранить</button></p>';
		echo '</form></div>';
	}

	/**
	 * Подставляет новый токен и проверяет его через getMe.
	 *
	 * @param array<string, mixed> $settings Настройки по ссылке.
	 * @return true|WP_Error
	 */
	private function apply_token(&$settings) {
		$token = isset($_POST['bot_token']) ? trim((string) wp_unslash($_POST['bot_token'])) : '';
		if ($token === '') {
			return true;
		}
		if (!TZ_Settings::is_token($token)) {
			return new WP_Error('tz_token', 'Токен не похож на токен бота Telegram.');
		}
		if ($token === $settings['bot_token']) {
			return true;
		}

		$client = new TZ_Telegram($token);
		$me = $client->get_me();
		if (is_wp_error($me)) {
			return new WP_Error('tz_token', 'Токен не принят Telegram. ' . $me->get_error_message());
		}

		$settings['bot_token'] = $token;
		$settings['bot_username'] = is_array($me) ? (string) ($me['username'] ?? '') : '';
		$settings['chats'] = array();
		$settings['chat_id'] = '';
		delete_option(TZ_Settings::OFFSET);
		return true;
	}

	/**
	 * Читает группы, в которых состоит бот.
	 *
	 * @param bool $drop_webhook Снять webhook перед чтением.
	 */
	private function refresh_chats($drop_webhook) {
		$settings = TZ_Settings::get();
		if ($settings['bot_token'] === '') {
			$this->redirect_notice('error', 'Сначала укажите и сохраните токен бота.');
		}

		$client = new TZ_Telegram($settings['bot_token']);
		if ($drop_webhook) {
			$deleted = $client->delete_webhook();
			if (is_wp_error($deleted)) {
				$this->redirect_notice('error', 'Webhook не снят. ' . $deleted->get_error_message());
			}
		}

		$updates = $client->pull_updates();
		if (is_wp_error($updates)) {
			$status = $updates->get_error_data();
			$code = is_array($status) ? (int) ($status['status'] ?? 0) : 0;
			if ($code === 409) {
				$this->redirect_notice('error', 'Telegram не отдал список групп: у бота включён webhook. Нажмите «Снять webhook и обновить», если этот бот не используется в другом сервисе.');
			}
			$this->redirect_notice('error', 'Список групп не обновлён. ' . $updates->get_error_message());
		}

		list($found, $left) = $client->chats_from_updates($updates);
		$chats = $settings['chats'];
		foreach ($left as $id) {
			unset($chats[$id]);
		}
		foreach ($found as $id => $chat) {
			$chats[$id] = $chat;
		}

		$checked = array();
		foreach ($chats as $id => $chat) {
			$fresh = $client->refresh_chat($id);
			if (is_wp_error($fresh)) {
				$data = $fresh->get_error_data();
				$code = is_array($data) ? (int) ($data['status'] ?? 0) : 0;
				if (in_array($code, array(400, 403), true)) {
					continue;
				}
				$checked[$id] = is_array($chat) ? $chat : array('id' => (string) $id, 'title' => (string) $id, 'type' => 'group');
				continue;
			}
			$checked[$id] = $fresh;
		}

		$settings['chats'] = $checked;
		if ($settings['chat_id'] !== '' && !isset($checked[$settings['chat_id']])) {
			$settings['chat_id'] = '';
		}
		TZ_Settings::update($settings);

		if ($checked === array()) {
			$this->redirect_notice('warning', 'Группы не найдены. Добавьте бота в группу, отправьте в ней /start и обновите список ещё раз.');
		}

		$this->redirect_notice('success', 'Список групп обновлён: ' . count($checked) . '.');
	}

	/**
	 * Формы Contact Form 7.
	 *
	 * @return array<int, WP_Post>
	 */
	private function cf7_forms() {
		if (!post_type_exists('wpcf7_contact_form')) {
			return array();
		}

		$forms = get_posts(
			array(
				'post_type' => 'wpcf7_contact_form',
				'post_status' => 'publish',
				'posts_per_page' => 200,
				'orderby' => 'title',
				'order' => 'ASC',
			)
		);

		return is_array($forms) ? $forms : array();
	}

	/**
	 * Сохраняет уведомление и возвращает на страницу настроек.
	 *
	 * @param string $type Тип: success, warning или error.
	 * @param string $text Текст.
	 */
	private function redirect_notice($type, $text) {
		set_transient(
			self::NOTICE . '_' . get_current_user_id(),
			array(
				'type' => $type,
				'text' => $text,
			),
			60
		);

		wp_safe_redirect(admin_url('admin.php?page=telegram-zayavki'));
		exit;
	}
}
