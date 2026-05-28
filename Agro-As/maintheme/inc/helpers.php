<?php
/**
 * Хелперы темы Agro As
 *
 * @package maintheme
 */

if ( ! function_exists( 'maintheme_is_filled' ) ) {
	/**
	 * Поле заполнено.
	 */
	function maintheme_is_filled( $value ) {
		if ( is_array( $value ) ) {
			if ( isset( $value['url'] ) ) {
				return (string) $value['url'] !== '';
			}
			return ! empty( $value );
		}

		if ( is_string( $value ) ) {
			return trim( $value ) !== '';
		}

		if ( is_bool( $value ) || is_numeric( $value ) ) {
			return true;
		}

		return ! empty( $value );
	}
}

if ( ! function_exists( 'maintheme_section_show' ) ) {
	/**
	 * Секция включена чекбоксом «Показать».
	 */
	function maintheme_section_show( $group ) {
		if ( ! is_array( $group ) ) {
			return false;
		}

		return ! empty( $group['pokazat'] );
	}
}

if ( ! function_exists( 'maintheme_get_option' ) ) {
	/**
	 * Значение из ACF Options (только если заполнено).
	 */
	function maintheme_get_option( $field ) {
		if ( ! function_exists( 'get_field' ) ) {
			return '';
		}

		$value = get_field( $field, 'option' );

		if ( is_string( $value ) ) {
			$value = trim( $value );
		}

		if ( maintheme_is_filled( $value ) ) {
			return $value;
		}

		if ( 'pochta' === $field ) {
			$legacy = get_field( 'email', 'option' );
			if ( is_string( $legacy ) ) {
				$legacy = trim( $legacy );
			}
			if ( maintheme_is_filled( $legacy ) ) {
				return $legacy;
			}
		}

		return '';
	}
}

if ( ! function_exists( 'maintheme_phone_href' ) ) {
	function maintheme_phone_href( $phone ) {
		return preg_replace( '/[^0-9+]/', '', (string) $phone );
	}
}

if ( ! function_exists( 'maintheme_img_url' ) ) {
	/**
	 * URL из ACF image array.
	 */
	function maintheme_img_url( $image ) {
		if ( is_array( $image ) && ! empty( $image['url'] ) ) {
			return $image['url'];
		}
		return '';
	}
}

if ( ! function_exists( 'maintheme_img_alt' ) ) {
	function maintheme_img_alt( $image, $fallback = '' ) {
		if ( is_array( $image ) && isset( $image['alt'] ) && (string) $image['alt'] !== '' ) {
			return $image['alt'];
		}
		return $fallback;
	}
}

if ( ! function_exists( 'maintheme_nav_is_active' ) ) {
	/**
	 * Активный пункт меню на внутренних страницах.
	 */
	function maintheme_nav_is_active( $key ) {
		switch ( $key ) {
			case 'catalog':
				return is_page_template( 'page-catalog.php' ) || is_singular( 'product' ) || is_tax( 'product_cat' );
			case 'wholesale':
				return is_page_template( 'page-wholesale.php' );
			case 'delivery':
				return is_page_template( 'page-delivery.php' );
			case 'contacts':
				return is_page_template( 'page-contacts.php' );
		}

		return false;
	}
}

if ( ! function_exists( 'maintheme_nav_li_class_attr' ) ) {
	/**
	 * class="" для li меню (btn-catalog, current-menu-item).
	 *
	 * @param array $item Элемент $nav_items.
	 */
	function maintheme_nav_li_class_attr( $item ) {
		$classes = array();

		if ( ! empty( $item['li_class'] ) ) {
			$classes[] = $item['li_class'];
		}

		if ( ! empty( $item['active'] ) && maintheme_nav_is_active( $item['active'] ) ) {
			$classes[] = 'current-menu-item';
		}

		if ( empty( $classes ) ) {
			return '';
		}

		return ' class="' . esc_attr( implode( ' ', $classes ) ) . '"';
	}
}

if ( ! function_exists( 'maintheme_product_status_label' ) ) {
	/**
	 * Текст статуса наличия товара.
	 */
	function maintheme_product_status_label( $status = '' ) {
		$labels = array(
			'v_nalichii'     => 'Есть в наличии',
			'net_v_nalichii' => 'Нет в наличии',
			'pod_zakaz'      => 'Под заказ',
		);

		if ( ! maintheme_is_filled( $status ) || ! isset( $labels[ $status ] ) ) {
			return $labels['v_nalichii'];
		}

		return $labels[ $status ];
	}
}

if ( ! function_exists( 'maintheme_product_status_class' ) ) {
	/**
	 * CSS-модификатор статуса наличия.
	 */
	function maintheme_product_status_class( $status = '' ) {
		$classes = array(
			'v_nalichii'     => 'product-page__status--in-stock',
			'net_v_nalichii' => 'product-page__status--out-of-stock',
			'pod_zakaz'      => 'product-page__status--preorder',
		);

		if ( ! maintheme_is_filled( $status ) || ! isset( $classes[ $status ] ) ) {
			return $classes['v_nalichii'];
		}

		return $classes[ $status ];
	}
}

if ( ! function_exists( 'maintheme_get_product_stock_order' ) ) {
	/**
	 * Порядок статуса для сортировки каталога (меньше — выше).
	 */
	function maintheme_get_product_stock_order( $status = '' ) {
		$orders = array(
			'v_nalichii'     => 1,
			'pod_zakaz'      => 2,
			'net_v_nalichii' => 3,
		);

		if ( maintheme_is_filled( $status ) && isset( $orders[ $status ] ) ) {
			return $orders[ $status ];
		}

		return $orders['v_nalichii'];
	}
}

if ( ! function_exists( 'maintheme_get_product_variations' ) ) {
	/**
	 * Вариации товара с активной по умолчанию.
	 */
	function maintheme_get_product_variations( $post_id = 0 ) {
		if ( ! function_exists( 'get_field' ) ) {
			return array(
				'items'  => array(),
				'active' => null,
			);
		}

		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}

		$items  = get_field( 'variacii', $post_id );
		$items  = is_array( $items ) ? $items : array();
		$active = null;

		foreach ( $items as $index => $item ) {
			if ( ! is_array( $item ) ) {
				continue;
			}

			if ( ! empty( $item['po_umolchaniyu'] ) ) {
				$active = $index;
				break;
			}
		}

		if ( null === $active && ! empty( $items ) ) {
			$active = 0;
		}

		return array(
			'items'  => $items,
			'active' => $active,
		);
	}
}

if ( ! function_exists( 'maintheme_get_relationship_products' ) ) {
	/**
	 * Опубликованные product из ACF Relationship.
	 *
	 * @param mixed $relationship Значение поля relationship.
	 * @param array $args {
	 *   @type int $exclude_id ID записи для исключения.
	 *   @type int $limit      0 — без ограничения.
	 * }
	 */
	function maintheme_get_relationship_products( $relationship, $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'exclude_id' => 0,
				'limit'      => 0,
			)
		);

		if ( ! maintheme_is_filled( $relationship ) ) {
			return array();
		}

		$products  = array();
		$exclude   = (int) $args['exclude_id'];
		$limit     = max( 0, (int) $args['limit'] );

		foreach ( (array) $relationship as $item ) {
			$id = is_object( $item ) ? (int) $item->ID : (int) $item;

			if ( ! $id || $id === $exclude || 'product' !== get_post_type( $id ) ) {
				continue;
			}

			$post = get_post( $id );

			if ( ! ( $post instanceof WP_Post ) || 'publish' !== $post->post_status ) {
				continue;
			}

			$products[ $id ] = $post;

			if ( $limit > 0 && count( $products ) >= $limit ) {
				break;
			}
		}

		return array_values( $products );
	}
}

if ( ! function_exists( 'maintheme_get_front_products' ) ) {
	/**
	 * Товары секции «Товары» на главной (ACF group tovary → rekomenduemye_tovary).
	 */
	function maintheme_get_front_products( $sect ) {
		if ( ! is_array( $sect ) ) {
			return array();
		}

		return maintheme_get_relationship_products( $sect['rekomenduemye_tovary'] ?? null );
	}
}

if ( ! function_exists( 'maintheme_get_recommended_products' ) ) {
	/**
	 * Рекомендуемые товары: вручную или 6 случайных из той же категории.
	 */
	function maintheme_get_recommended_products( $post_id = 0, $limit = 6 ) {
		$limit = max( 1, (int) $limit );

		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}

		$post_id = (int) $post_id;

		if ( ! $post_id || 'product' !== get_post_type( $post_id ) ) {
			return array();
		}

		if ( function_exists( 'get_field' ) ) {
			$manual = get_field( 'rekomenduemye_tovary', $post_id );

			if ( maintheme_is_filled( $manual ) ) {
				return maintheme_get_relationship_products(
					$manual,
					array(
						'exclude_id' => $post_id,
						'limit'      => $limit,
					)
				);
			}
		}

		$term_ids = wp_get_object_terms(
			$post_id,
			'product_cat',
			array(
				'fields' => 'ids',
			)
		);

		if ( is_wp_error( $term_ids ) || empty( $term_ids ) ) {
			return array();
		}

		$query = new WP_Query(
			array(
				'post_type'              => 'product',
				'posts_per_page'         => $limit,
				'post__not_in'           => array( $post_id ),
				'orderby'                => 'rand',
				'post_status'            => 'publish',
				'ignore_sticky_posts'    => true,
				'no_found_rows'          => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
				'tax_query'              => array(
					array(
						'taxonomy' => 'product_cat',
						'field'    => 'term_id',
						'terms'    => $term_ids,
					),
				),
			)
		);

		return $query->posts;
	}
}

if ( ! function_exists( 'maintheme_get_product_specs' ) ) {
	/**
	 * Заполненные строки характеристик.
	 */
	function maintheme_get_product_specs( $post_id = 0 ) {
		if ( ! function_exists( 'get_field' ) ) {
			return array();
		}

		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}

		$rows   = get_field( 'harakteristiki', $post_id );
		$rows   = is_array( $rows ) ? $rows : array();
		$result = array();

		foreach ( $rows as $row ) {
			if ( ! is_array( $row ) ) {
				continue;
			}

			if ( ! maintheme_is_filled( $row['nazvanie'] ?? '' ) && ! maintheme_is_filled( $row['znachenie'] ?? '' ) ) {
				continue;
			}

			$result[] = $row;
		}

		return $result;
	}
}

if ( ! function_exists( 'maintheme_product_variation_volume_text' ) ) {
	/**
	 * Текст объёма/фасовки для label и data-volume.
	 */
	function maintheme_product_variation_volume_text( $item ) {
		if ( ! is_array( $item ) ) {
			return '';
		}

		$value = trim( (string) ( $item['znachenie'] ?? '' ) );
		$unit  = trim( (string) ( $item['edinica'] ?? '' ) );

		if ( ! maintheme_is_filled( $value ) ) {
			return '';
		}

		return maintheme_is_filled( $unit ) ? $value . ' ' . $unit : $value;
	}
}

if ( ! function_exists( 'maintheme_product_card_pack_value' ) ) {
	/**
	 * Все фасовки карточки через запятую, сортировка по числу znachenie ↑.
	 */
	function maintheme_product_card_pack_value( $items ) {
		if ( ! is_array( $items ) || empty( $items ) ) {
			return '';
		}

		$rows = array();

		foreach ( $items as $item ) {
			if ( ! is_array( $item ) ) {
				continue;
			}

			$text = maintheme_product_variation_volume_text( $item );

			if ( ! maintheme_is_filled( $text ) ) {
				continue;
			}

			$rows[] = array(
				'sort' => maintheme_parse_price_number( $item['znachenie'] ?? '' ),
				'text' => $text,
			);
		}

		if ( empty( $rows ) ) {
			return '';
		}

		usort(
			$rows,
			function ( $a, $b ) {
				if ( $a['sort'] === $b['sort'] ) {
					return 0;
				}

				return ( $a['sort'] < $b['sort'] ) ? -1 : 1;
			}
		);

		$texts = array();

		foreach ( $rows as $row ) {
			$texts[] = $row['text'];
		}

		return implode( ', ', $texts );
	}
}

if ( ! function_exists( 'maintheme_get_product_min_variation_index' ) ) {
	/**
	 * Индекс вариации с минимальным znachenie.
	 */
	function maintheme_get_product_min_variation_index( $post_id = 0 ) {
		if ( function_exists( 'simple_basket_get_min_variation_index' ) ) {
			return simple_basket_get_min_variation_index( $post_id );
		}

		return 0;
	}
}

if ( ! function_exists( 'maintheme_get_basket_line_data' ) ) {
	/**
	 * Данные строки корзины (обёртка над Simple Basket API).
	 */
	function maintheme_get_basket_line_data( $post_id = 0, $variation_index = 0 ) {
		if ( ! function_exists( 'simple_basket_get_product_line' ) ) {
			return array();
		}

		$line = simple_basket_get_product_line( $post_id, $variation_index, 1 );

		return is_array( $line ) ? $line : array();
	}
}

if ( ! function_exists( 'maintheme_basket_url' ) ) {
	/**
	 * URL страницы корзины.
	 */
	function maintheme_basket_url() {
		$option = get_option( 'simple_basket_order_page' );

		if ( maintheme_is_filled( $option ) ) {
			return $option;
		}

		foreach ( array( 'korzina', 'korzina-zakaza' ) as $slug ) {
			$page = get_page_by_path( $slug );

			if ( $page instanceof WP_Post && 'publish' === $page->post_status ) {
				return get_permalink( $page );
			}
		}

		return home_url( '/korzina/' );
	}
}

if ( ! function_exists( 'maintheme_thanks_page_url' ) ) {
	/**
	 * URL страницы «Спасибо» после отправки формы CF7.
	 */
	function maintheme_thanks_page_url() {
		foreach ( array( 'spasibo', 'thnx' ) as $slug ) {
			$page = get_page_by_path( $slug );

			if ( $page instanceof WP_Post && 'publish' === $page->post_status ) {
				return get_permalink( $page );
			}
		}

		return home_url( '/spasibo/' );
	}
}

if ( ! function_exists( 'maintheme_product_catalog_url' ) ) {
	/**
	 * Ссылка «Каталог» в крошках: первая категория или /catalog/.
	 */
	function maintheme_product_catalog_url( $post_id = 0 ) {
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}

		$terms = get_the_terms( $post_id, 'product_cat' );

		if ( $terms && ! is_wp_error( $terms ) ) {
			$term = $terms[0];
			$link = get_term_link( $term );

			if ( ! is_wp_error( $link ) ) {
				return $link;
			}
		}

		return home_url( '/catalog/' );
	}
}

if ( ! function_exists( 'maintheme_product_card_data' ) ) {
	/**
	 * Данные карточки товара для item-product.
	 */
	function maintheme_product_card_data( $post_id = 0 ) {
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}

		$data = array(
			'id'         => (int) $post_id,
			'url'        => get_permalink( $post_id ),
			'title'      => get_the_title( $post_id ),
			'image'      => '',
			'image_alt'  => get_the_title( $post_id ),
			'tag'        => '',
			'status'     => '',
			'pack_label' => 'Фасовка',
			'pack_value' => '',
			'price'      => '',
			'cart_variation' => 0,
		);

		if ( function_exists( 'get_field' ) ) {
			$gallery = get_field( 'galereya', $post_id );

			if ( is_array( $gallery ) && ! empty( $gallery[0] ) ) {
				$data['image']     = maintheme_img_url( $gallery[0] );
				$data['image_alt'] = maintheme_img_alt( $gallery[0], $data['title'] );
			}

			$tags = get_field( 'tegi', $post_id );

			if ( is_array( $tags ) ) {
				foreach ( $tags as $tag ) {
					if ( is_array( $tag ) && maintheme_is_filled( $tag['tekst'] ?? '' ) ) {
						$data['tag'] = $tag['tekst'];
						break;
					}
				}
			}

			if ( ! maintheme_is_filled( $data['tag'] ) ) {
				$terms = get_the_terms( $post_id, 'product_cat' );

				if ( $terms && ! is_wp_error( $terms ) ) {
					$data['tag'] = $terms[0]->name;
				}
			}

			$status_key     = get_field( 'status_nalichiya', $post_id );
			$data['status'] = maintheme_product_status_label( $status_key );

			$volume_label = get_field( 'nazvanie_bloka_obema', $post_id );

			if ( maintheme_is_filled( $volume_label ) ) {
				$data['pack_label'] = rtrim( trim( $volume_label ), ':' );
			}

			$variations = maintheme_get_product_variations( $post_id );

			if ( ! empty( $variations['items'] ) ) {
				$data['pack_value'] = maintheme_product_card_pack_value( $variations['items'] );
				$data['cart_variation'] = maintheme_get_product_min_variation_index( $post_id );
			}

			$min_price = get_post_meta( $post_id, 'maintheme_min_price', true );

			if ( maintheme_is_filled( $min_price ) ) {
				$data['price'] = $min_price;
			} elseif ( ! empty( $variations['items'] ) && null !== $variations['active'] ) {
				$active = $variations['items'][ $variations['active'] ];

				if ( maintheme_is_filled( $active['cena'] ?? '' ) ) {
					$data['price'] = $active['cena'];
				}
			}
		}

		return $data;
	}
}

if ( ! function_exists( 'maintheme_parse_price_number' ) ) {
	/**
	 * Число из текстовой цены ACF («2 284 ₽», «2 500 ₽/шт»).
	 */
	function maintheme_parse_price_number( $price_text ) {
		if ( ! maintheme_is_filled( $price_text ) ) {
			return 0.0;
		}

		$normalized = preg_replace( '/[^\d.,]/', '', str_replace( ' ', '', (string) $price_text ) );
		$normalized = str_replace( ',', '.', $normalized );

		if ( ! maintheme_is_filled( $normalized ) ) {
			return 0.0;
		}

		return (float) $normalized;
	}
}

if ( ! function_exists( 'maintheme_get_product_min_price_data' ) ) {
	/**
	 * Минимальная цена из repeater variacii.
	 */
	function maintheme_get_product_min_price_data( $post_id = 0 ) {
		$result = array(
			'num'     => 0.0,
			'display' => '',
		);

		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}

		$variations = maintheme_get_product_variations( $post_id );

		if ( empty( $variations['items'] ) ) {
			return $result;
		}

		$min_num = null;

		foreach ( $variations['items'] as $item ) {
			if ( ! is_array( $item ) || ! maintheme_is_filled( $item['cena'] ?? '' ) ) {
				continue;
			}

			$num = maintheme_parse_price_number( $item['cena'] );

			if ( $num <= 0 ) {
				continue;
			}

			if ( null === $min_num || $num < $min_num ) {
				$min_num           = $num;
				$result['display'] = trim( (string) $item['cena'] );
			}
		}

		if ( null !== $min_num ) {
			$result['num'] = $min_num;
		}

		return $result;
	}
}

if ( ! function_exists( 'maintheme_get_front_category_card_sizes' ) ) {
	/**
	 * Размеры карточек категорий на главной по их количеству.
	 *
	 * @param int $count Число родительских категорий.
	 * @return string[]
	 */
	function maintheme_get_front_category_card_sizes( $count ) {
		$count = (int) $count;

		if ( $count <= 0 ) {
			return array();
		}

		if ( 1 === $count ) {
			return array( 'lg' );
		}

		if ( 2 === $count ) {
			return array( 'md', 'md' );
		}

		if ( 3 === $count ) {
			return array( 'sm', 'sm', 'sm' );
		}

		if ( 4 === $count ) {
			return array( 'md', 'md', 'md', 'md' );
		}

		if ( 5 === $count ) {
			return array( 'sm', 'sm', 'sm', 'md', 'lg' );
		}

		if ( 0 === $count % 3 ) {
			return array_fill( 0, $count, 'sm' );
		}

		$sizes     = array( 'sm', 'sm', 'sm' );
		$remaining = $count - 3;

		while ( $remaining >= 2 ) {
			$sizes[] = 'md';
			$sizes[] = 'md';
			$remaining -= 2;
		}

		if ( 1 === $remaining ) {
			$sizes[] = 'lg';
		}

		return $sizes;
	}
}

if ( ! function_exists( 'maintheme_get_front_category_col_class' ) ) {
	/**
	 * Bootstrap-колонка для карточки категории.
	 */
	function maintheme_get_front_category_col_class( $size ) {
		$map = array(
			'sm' => 'col-lg-4',
			'md' => 'col-lg-6',
			'lg' => 'col-lg-6',
		);

		return $map[ $size ] ?? 'col-lg-4';
	}
}

if ( ! function_exists( 'maintheme_get_front_product_categories' ) ) {
	/**
	 * Родительские product_cat с ACF izobrazhenie_kategorii и дочерними категориями.
	 */
	function maintheme_get_front_product_categories() {
		$parents = get_terms(
			array(
				'taxonomy'   => 'product_cat',
				'parent'     => 0,
				'hide_empty' => false,
				'orderby'    => 'name',
				'order'      => 'ASC',
			)
		);

		if ( is_wp_error( $parents ) || empty( $parents ) ) {
			return array();
		}

		$items = array();

		foreach ( $parents as $parent ) {
			if ( ! ( $parent instanceof WP_Term ) ) {
				continue;
			}

			$image = function_exists( 'get_field' )
				? get_field( 'izobrazhenie_kategorii', 'product_cat_' . (int) $parent->term_id )
				: null;

			if ( ! maintheme_is_filled( $image ) ) {
				continue;
			}

			$parent_link = get_term_link( $parent );

			if ( is_wp_error( $parent_link ) ) {
				continue;
			}

			$children_data = array();
			$children      = get_terms(
				array(
					'taxonomy'   => 'product_cat',
					'parent'     => (int) $parent->term_id,
					'hide_empty' => false,
					'orderby'    => 'name',
					'order'      => 'ASC',
				)
			);

			if ( ! is_wp_error( $children ) && ! empty( $children ) ) {
				foreach ( $children as $child ) {
					if ( ! ( $child instanceof WP_Term ) ) {
						continue;
					}

					$child_link = get_term_link( $child );

					if ( is_wp_error( $child_link ) ) {
						continue;
					}

					$children_data[] = array(
						'term' => $child,
						'name' => $child->name,
						'url'  => $child_link,
					);
				}
			}

			$items[] = array(
				'term'     => $parent,
				'title'    => $parent->name,
				'image'    => $image,
				'url'      => $parent_link,
				'children' => $children_data,
			);
		}

		$sizes = maintheme_get_front_category_card_sizes( count( $items ) );

		foreach ( $items as $index => &$item ) {
			$size        = $sizes[ $index ] ?? 'sm';
			$item['size'] = $size;
			$item['col']  = maintheme_get_front_category_col_class( $size );
		}
		unset( $item );

		return $items;
	}
}

if ( ! function_exists( 'maintheme_get_footer_catalog_columns' ) ) {
	/**
	 * Каталог в футере: родительские product_cat + дети, две колонки.
	 *
	 * @return array{0: array, 1: array}
	 */
	function maintheme_get_footer_catalog_columns() {
		$parents = get_terms(
			array(
				'taxonomy'   => 'product_cat',
				'parent'     => 0,
				'hide_empty' => false,
				'orderby'    => 'name',
				'order'      => 'ASC',
			)
		);

		if ( is_wp_error( $parents ) || empty( $parents ) ) {
			return array( array(), array() );
		}

		$items = array();

		foreach ( $parents as $parent ) {
			if ( ! ( $parent instanceof WP_Term ) ) {
				continue;
			}

			$parent_link = get_term_link( $parent );

			if ( is_wp_error( $parent_link ) ) {
				continue;
			}

			$children_data = array();
			$children      = get_terms(
				array(
					'taxonomy'   => 'product_cat',
					'parent'     => (int) $parent->term_id,
					'hide_empty' => false,
					'orderby'    => 'name',
					'order'      => 'ASC',
				)
			);

			if ( ! is_wp_error( $children ) && ! empty( $children ) ) {
				foreach ( $children as $child ) {
					if ( ! ( $child instanceof WP_Term ) ) {
						continue;
					}

					$child_link = get_term_link( $child );

					if ( is_wp_error( $child_link ) ) {
						continue;
					}

					$children_data[] = array(
						'name' => $child->name,
						'url'  => $child_link,
					);
				}
			}

			$items[] = array(
				'title'    => $parent->name,
				'url'      => $parent_link,
				'children' => $children_data,
			);
		}

		if ( empty( $items ) ) {
			return array( array(), array() );
		}

		$half = (int) ceil( count( $items ) / 2 );

		return array(
			array_slice( $items, 0, $half ),
			array_slice( $items, $half ),
		);
	}
}

if ( ! function_exists( 'maintheme_catalog_url' ) ) {
	/**
	 * Ссылка «Каталог» в меню и крошках.
	 */
	function maintheme_catalog_url() {
		$page = get_page_by_path( 'catalog' );

		if ( $page instanceof WP_Post && 'publish' === $page->post_status ) {
			return get_permalink( $page );
		}

		$terms = get_terms(
			array(
				'taxonomy'   => 'product_cat',
				'hide_empty' => false,
				'parent'     => 0,
				'number'     => 1,
				'orderby'    => 'name',
				'order'      => 'ASC',
			)
		);

		if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
			$link = get_term_link( $terms[0] );

			if ( ! is_wp_error( $link ) ) {
				return $link;
			}
		}

		return home_url( '/catalog/' );
	}
}

if ( ! function_exists( 'maintheme_get_catalog_sort' ) ) {
	/**
	 * Текущая сортировка каталога из GET.
	 */
	function maintheme_get_catalog_sort() {
		$sort = isset( $_GET['sort'] ) ? sanitize_key( wp_unslash( $_GET['sort'] ) ) : 'default';
		$allowed = array( 'default', 'price', 'date', 'alpha', 'stock' );

		if ( ! in_array( $sort, $allowed, true ) ) {
			return 'default';
		}

		return $sort;
	}
}

if ( ! function_exists( 'maintheme_get_catalog_sort_query_args' ) ) {
	/**
	 * Аргументы orderby для WP_Query каталога.
	 */
	function maintheme_get_catalog_sort_query_args( $sort = null ) {
		if ( null === $sort ) {
			$sort = maintheme_get_catalog_sort();
		}

		switch ( $sort ) {
			case 'price':
				return array(
					'meta_key' => '_maintheme_min_price',
					'orderby'  => 'meta_value_num',
					'order'    => 'ASC',
				);
			case 'date':
				return array(
					'orderby' => 'date',
					'order'   => 'DESC',
				);
			case 'alpha':
				return array(
					'orderby' => 'title',
					'order'   => 'ASC',
				);
			case 'stock':
				return array(
					'meta_key' => '_maintheme_stock_order',
					'orderby'  => 'meta_value_num',
					'order'    => 'ASC',
				);
			default:
				return array(
					'orderby' => 'date',
					'order'   => 'DESC',
				);
		}
	}
}

if ( ! function_exists( 'maintheme_product_cat_sidebar_is_open' ) ) {
	/**
	 * Раскрыт ли пункт сайдбара для текущей категории.
	 */
	function maintheme_product_cat_sidebar_is_open( $parent, $current ) {
		if ( ! ( $parent instanceof WP_Term ) || ! ( $current instanceof WP_Term ) ) {
			return false;
		}

		if ( (int) $current->term_id === (int) $parent->term_id ) {
			return true;
		}

		return (int) $current->parent === (int) $parent->term_id;
	}
}

if ( ! function_exists( 'maintheme_catalog_page_link' ) ) {
	/**
	 * URL страницы категории с учётом сортировки.
	 */
	function maintheme_catalog_page_link( $page_num, $sort = null, $term = null ) {
		if ( null === $term ) {
			$term = get_queried_object();
		}

		if ( ! ( $term instanceof WP_Term ) ) {
			return '';
		}

		$url = get_term_link( $term );

		if ( is_wp_error( $url ) ) {
			return '';
		}

		$page_num = max( 1, (int) $page_num );

		if ( $page_num > 1 ) {
			$url = trailingslashit( $url ) . 'page/' . $page_num . '/';
		}

		if ( null === $sort ) {
			$sort = maintheme_get_catalog_sort();
		}

		if ( 'default' !== $sort ) {
			$url = add_query_arg( 'sort', $sort, $url );
		}

		return $url;
	}
}

if ( ! function_exists( 'maintheme_catalog_pagination' ) ) {
	/**
	 * Разметка .catalog-page__pager по макету category.html.
	 */
	function maintheme_catalog_pagination( $query = null ) {
		if ( ! $query ) {
			global $wp_query;
			$query = $wp_query;
		}

		$total = (int) $query->max_num_pages;

		if ( $total <= 1 ) {
			return;
		}

		$current = max( 1, (int) get_query_var( 'paged' ) );

		if ( $current < 1 ) {
			$current = max( 1, (int) get_query_var( 'page' ) );
		}

		$sort      = maintheme_get_catalog_sort();
		$arrow_img = get_template_directory_uri() . '/img/Arrow_Left_SM.svg';
		?>
		<div class="pager catalog-page__pager">
			<?php if ( $current > 1 ) { ?>
				<a class="prev page-numbers catalog-page__pager-arrow" href="<?php echo esc_url( maintheme_catalog_page_link( $current - 1, $sort ) ); ?>" aria-label="Предыдущая страница">
					<span class="catalog-page__pager-icon" aria-hidden="true">
						<img src="<?php echo esc_url( $arrow_img ); ?>" width="24" height="24" alt="">
					</span>
				</a>
			<?php } ?>

			<div class="catalog-page__pager-nums">
				<?php
				for ( $page = 1; $page <= $total; $page++ ) {
					if ( $page === $current ) {
						echo '<span aria-current="page" class="page-numbers current">' . esc_html( (string) $page ) . '</span>';
						continue;
					}

					echo '<a class="page-numbers" href="' . esc_url( maintheme_catalog_page_link( $page, $sort ) ) . '">' . esc_html( (string) $page ) . '</a>';
				}
				?>
			</div>

			<?php if ( $current < $total ) { ?>
				<a class="next page-numbers catalog-page__pager-arrow next" href="<?php echo esc_url( maintheme_catalog_page_link( $current + 1, $sort ) ); ?>" aria-label="Следующая страница">
					<span class="catalog-page__pager-icon" aria-hidden="true">
						<img src="<?php echo esc_url( $arrow_img ); ?>" width="24" height="24" alt="">
					</span>
				</a>
			<?php } ?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'maintheme_sync_product_min_price_meta' ) ) {
	/**
	 * Meta для сортировки каталога и карточек item-product.
	 */
	function maintheme_sync_product_min_price_meta( $post_id ) {
		$post_id = (int) $post_id;

		if ( ! $post_id || 'product' !== get_post_type( $post_id ) ) {
			return;
		}

		if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
			return;
		}

		$min_price = maintheme_get_product_min_price_data( $post_id );

		if ( $min_price['num'] > 0 && maintheme_is_filled( $min_price['display'] ) ) {
			update_post_meta( $post_id, '_maintheme_min_price', $min_price['num'] );
			update_post_meta( $post_id, 'maintheme_min_price', $min_price['display'] );
			return;
		}

		delete_post_meta( $post_id, '_maintheme_min_price' );
		delete_post_meta( $post_id, 'maintheme_min_price' );
	}
}

if ( ! function_exists( 'maintheme_sync_product_stock_meta' ) ) {
	/**
	 * Meta для сортировки каталога по наличию.
	 */
	function maintheme_sync_product_stock_meta( $post_id ) {
		$post_id = (int) $post_id;

		if ( ! $post_id || 'product' !== get_post_type( $post_id ) ) {
			return;
		}

		if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
			return;
		}

		$status = '';

		if ( function_exists( 'get_field' ) ) {
			$status = get_field( 'status_nalichiya', $post_id );
		}

		update_post_meta( $post_id, '_maintheme_stock_order', maintheme_get_product_stock_order( $status ) );
	}
}
