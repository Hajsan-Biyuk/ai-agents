<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package maintheme
 */

get_header();

get_template_part(
	'template-parts/page/service',
	'message',
	array(
		'modifier'   => '404',
		'breadcrumb' => 'Страница не найдена',
		'title'      => 'Ошибка 404<br>страница не найдена',
		'descr'      => 'Страница, на которую вы пытаетесь попасть, не существует или была удалена',
		'btn_text'   => 'Вернуться на главную',
		'btn_url'    => home_url( '/' ),
	)
);

get_footer();
