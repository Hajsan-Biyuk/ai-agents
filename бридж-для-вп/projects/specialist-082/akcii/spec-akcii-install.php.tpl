<?php
// Одноразовый установщик страницы «Акции»: страница, ACF-группа, первая акция, ссылка попапа, пункт меню. После запуска — заглушка.
if (($_GET['t'] ?? '') !== '__TOKEN__') {
	http_response_code(404);
	exit;
}
require dirname(__DIR__, 3) . '/wp-load.php';
header('Content-Type: application/json; charset=utf-8');

$dry = !empty($_GET['dry']);
$out = [];
$page = get_page_by_path('akcii');
$out['page_exists'] = $page ? $page->ID : false;

// Откуда шапка берёт меню: меню WP в области hp_header или поле nav в «Шапка и подвал»
$locations = get_nav_menu_locations();
$menu_id = (int) ($locations['hp_header'] ?? 0);
$parent = null;
if ($menu_id) {
	$items = wp_get_nav_menu_items($menu_id) ?: [];
	foreach ($items as $it) {
		if (!(int) $it->menu_item_parent && trim($it->title) === 'Курсы и услуги') {
			$parent = $it;
		}
	}
	$out['menu'] = ['wp_menu' => $menu_id, 'parent' => $parent ? $parent->ID : null, 'items' => count($items)];
} else {
	$nav = get_field('nav', 'option');
	$out['menu'] = ['acf_nav' => is_array($nav) ? array_map(function ($r) {
		return [$r['title'] ?? '?', array_keys($r), count((array) ($r['children'] ?? []))];
	}, $nav) : $nav];
}
if ($dry) {
	echo wp_json_encode($out, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
	exit;
}

if (!$page) {
	$pid = wp_insert_post(['post_type' => 'page', 'post_status' => 'publish', 'post_title' => 'Акции', 'post_name' => 'akcii', 'post_content' => ''], true);
	if (is_wp_error($pid)) {
		echo wp_json_encode(['error' => $pid->get_error_message()]);
		exit;
	}
	update_post_meta($pid, '_wp_page_template', 'page-akcii.php');
	update_post_meta($pid, '_yoast_wpseo_metadesc', 'Акции учебного центра «Специалист» в Симферополе: специальные цены на обучение трактористов-машинистов и других рабочих профессий.');
	$page = get_post($pid);
}
$pid = $page->ID;
$out['page'] = $pid;

if (!acf_get_field_group('group_spec_akcii')) {
	$g = acf_import_field_group([
		'key' => 'group_spec_akcii',
		'title' => 'Акции',
		'location' => [[['param' => 'page_template', 'operator' => '==', 'value' => 'page-akcii.php']]],
		'hide_on_screen' => ['the_content'],
		'fields' => [
			['key' => 'field_spec_akcii_lead', 'name' => 'promo_lead', 'label' => 'Текст под заголовком', 'type' => 'textarea', 'rows' => 2, 'new_lines' => 'br'],
			['key' => 'field_spec_akcii_promos', 'name' => 'promos', 'label' => 'Акции', 'type' => 'repeater', 'layout' => 'block', 'button_label' => 'Добавить акцию', 'collapsed' => 'field_spec_akcii_title',
				'instructions' => 'Акции идут сверху вниз. Ссылка на акцию: /akcii/#akciya-1, #akciya-2 — по порядку в списке.',
				'sub_fields' => [
					['key' => 'field_spec_akcii_show', 'name' => 'promo_show', 'label' => 'Показывать', 'type' => 'true_false', 'ui' => 1, 'default_value' => 1, 'wrapper' => ['width' => 25]],
					['key' => 'field_spec_akcii_until', 'name' => 'promo_until', 'label' => 'Действует до (включительно)', 'type' => 'date_picker', 'display_format' => 'd.m.Y', 'return_format' => 'Ymd', 'first_day' => 1, 'instructions' => 'На следующий день акция скроется сама. Пусто — без срока.', 'wrapper' => ['width' => 35]],
					['key' => 'field_spec_akcii_direction', 'name' => 'promo_direction', 'label' => 'Направление в форме заявки', 'type' => 'text', 'instructions' => 'Точно как в списке формы, например «Удостоверение тракториста-машиниста». Пусто — не выбирать.', 'wrapper' => ['width' => 40]],
					['key' => 'field_spec_akcii_image', 'name' => 'promo_image', 'label' => 'Фото', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium', 'instructions' => '544×590'],
					['key' => 'field_spec_akcii_title', 'name' => 'promo_title', 'label' => 'Заголовок', 'type' => 'text'],
					['key' => 'field_spec_akcii_subtitle', 'name' => 'promo_subtitle', 'label' => 'Подзаголовок (срок акции)', 'type' => 'text'],
					['key' => 'field_spec_akcii_rows', 'name' => 'promo_rows', 'label' => 'Цены', 'type' => 'repeater', 'layout' => 'table', 'button_label' => 'Добавить строку', 'sub_fields' => [
						['key' => 'field_spec_akcii_row_name', 'name' => 'promo_row_name', 'label' => 'Название', 'type' => 'text'],
						['key' => 'field_spec_akcii_row_price', 'name' => 'promo_row_price', 'label' => 'Цена', 'type' => 'text', 'wrapper' => ['width' => 25]],
					]],
					['key' => 'field_spec_akcii_btn', 'name' => 'promo_btn_text', 'label' => 'Текст кнопки', 'type' => 'text', 'default_value' => 'Оставить заявку', 'instructions' => 'Кнопка открывает форму заявки. Пусто — без кнопки.'],
					['key' => 'field_spec_akcii_text', 'name' => 'promo_text', 'label' => 'Описание', 'type' => 'wysiwyg', 'tabs' => 'all', 'toolbar' => 'basic', 'media_upload' => 0],
				],
			],
		],
	]);
	$out['group'] = $g['ID'] ?? null;
}

if (!get_field('promos', $pid)) {
	$rows = [];
	foreach (get_field('popup_rows', 'option') ?: [] as $r) {
		$rows[] = ['field_spec_akcii_row_name' => $r['popup_row_name'], 'field_spec_akcii_row_price' => $r['popup_row_price']];
	}
	update_field('field_spec_akcii_promos', [[
		'field_spec_akcii_show' => 1,
		'field_spec_akcii_until' => '20270115',
		'field_spec_akcii_direction' => 'Удостоверение тракториста-машиниста',
		'field_spec_akcii_image' => (int) get_field('popup_image', 'option'),
		'field_spec_akcii_title' => get_field('popup_title', 'option'),
		'field_spec_akcii_subtitle' => get_field('popup_subtitle', 'option'),
		'field_spec_akcii_rows' => $rows,
		'field_spec_akcii_btn' => 'Оставить заявку',
		'field_spec_akcii_text' => '<p>Специальные цены на обучение и получение удостоверения тракториста-машиниста действуют для заявок, оформленных в период акции. Обучение и экзамен проходят на нашей технике и полигоне в Симферополе.</p><p>Оставьте заявку — менеджер перезвонит, расскажет об условиях и поможет выбрать категории.</p>',
	]], $pid);
	$out['promo'] = 'filled';
}

update_field('popup_btn_link', '/akcii/', 'option');

// Пункт «Акции» первым в выпадашке «Курсы и услуги»
$url = get_permalink($pid);
if ($menu_id && $parent) {
	$has = false;
	foreach (wp_get_nav_menu_items($menu_id) as $it) {
		$has = $has || ((int) $it->menu_item_parent === (int) $parent->ID && (int) $it->object_id === $pid);
	}
	if (!$has) {
		$new = wp_update_nav_menu_item($menu_id, 0, [
			'menu-item-title' => 'Акции', 'menu-item-object' => 'page', 'menu-item-object-id' => $pid, 'menu-item-type' => 'post_type',
			'menu-item-parent-id' => $parent->ID, 'menu-item-status' => 'publish',
		]);
		// Переставить: новый пункт сразу за родителем
		$order = 1;
		foreach (wp_get_nav_menu_items($menu_id) as $it) {
			if ((int) $it->ID === (int) $new) {
				continue;
			}
			wp_update_post(['ID' => $it->ID, 'menu_order' => $order++]);
			if ((int) $it->ID === (int) $parent->ID) {
				wp_update_post(['ID' => $new, 'menu_order' => $order++]);
			}
		}
		$out['menu_item'] = $new;
	}
} elseif (!$menu_id) {
	$nav = get_field('nav', 'option');
	foreach ((array) $nav as $k => $r) {
		if (trim($r['title'] ?? '') !== 'Курсы и услуги') {
			continue;
		}
		$children = (array) ($r['children'] ?? []);
		$urls = array_column($children, 'url');
		if (!in_array('/akcii/', $urls, true) && !in_array($url, $urls, true)) {
			array_unshift($children, ['title' => 'Акции', 'url' => '/akcii/']);
			$nav[$k]['children'] = $children;
			$out['menu_item'] = update_field('nav', $nav, 'option');
		}
	}
}
$out['url'] = $url;
$out['done'] = true;
echo wp_json_encode($out, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
