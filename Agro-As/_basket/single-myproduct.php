<?php
/**
 * Template: Single product page for "myproduct"
 * File: single-myproduct.php
 */

get_header();
?>

<?php
if ( have_posts() ) : while ( have_posts() ) : the_post();
?>

<div class="container">
	<nav class="catalog-breadcrumb mb-3" aria-label="breadcrumb">
    <ul class="breadcrumbs">
      <li class="breadcrumb-item">
        <a href="<?php echo esc_url( home_url('/') ); ?>">Главная</a>
      </li>
      <li class="breadcrumb-item">
        <a href="/myproduct/">Каталог продукции</a>
      </li>
      <?php
      // 1) Получаем все термины товара
      $terms = get_the_terms( get_the_ID(), 'myproduct_cat' );
      $term_for_breadcrumbs = null;

      if ( $terms && ! is_wp_error( $terms ) ) {

        // 2) Достаём из текущего URL именно тот путь категорий, который в permalinks
        //    /myproduct/{cat-path}/{product-slug}/
        $req_path = isset($_SERVER['REQUEST_URI']) ? rawurldecode($_SERVER['REQUEST_URI']) : '';
        // убираем параметры и якоря
        $req_path = strtok($req_path, '?#');
        // обрезаем слева/справа слэши
        $req_path = trim($req_path, '/');

        // ожидаем, что первые сегменты — myproduct / {cat-path} / {product}
        $segments = explode('/', $req_path);
        // ищем позицию 'myproduct'
        $mp_idx = array_search('myproduct', $segments, true);

        $cat_path_from_url = '';
        $product_slug_from_url = '';

        if ($mp_idx !== false) {
          // всё, что между 'myproduct' и последним сегментом — это путь категорий
          $tail = array_slice($segments, $mp_idx + 1);
          if (!empty($tail)) {
            $product_slug_from_url = end($tail);
            array_pop($tail); // убрали slug товара
            if (!empty($tail)) {
              $cat_path_from_url = implode('/', $tail);
            }
          }
        }

        // 3) Для каждого термина строим полный путь (root/.../term)
        //    и сравниваем с путём из URL. Если совпало — это нужный термин.
        foreach ( $terms as $t ) {
          $anc = array_reverse( get_ancestors( $t->term_id, 'myproduct_cat' ) ); // от корня к листу
          $slugs = array();
          foreach ( $anc as $aid ) {
            $at = get_term( $aid, 'myproduct_cat' );
            if ( $at && ! is_wp_error($at) ) {
              $slugs[] = $at->slug;
            }
          }
          $slugs[] = $t->slug;
          $built_path = implode('/', $slugs);

          if ( $built_path === $cat_path_from_url ) {
            $term_for_breadcrumbs = $t;
            break;
          }
        }

        // 4) Фоллбек: если точного совпадения не нашли — берём самую вложенную категорию
        if ( ! $term_for_breadcrumbs ) {
          usort($terms, function($a, $b) {
            return count(get_ancestors($b->term_id, 'myproduct_cat')) <=> count(get_ancestors($a->term_id, 'myproduct_cat'));
          });
          $term_for_breadcrumbs = reset($terms);
        }

        // 5) Рендерим предков и текущую категорию
        if ( $term_for_breadcrumbs ) {
          $ancestors = array_reverse( get_ancestors( $term_for_breadcrumbs->term_id, 'myproduct_cat' ) );
          foreach ( $ancestors as $ancestor_id ) {
            $ancestor = get_term( $ancestor_id, 'myproduct_cat' );
            if ( $ancestor && ! is_wp_error($ancestor) ) {
              echo '<li class="breadcrumb-item"><a href="' . esc_url( get_term_link($ancestor) ) . '">' . esc_html( $ancestor->name ) . '</a></li>';
            }
          }
          echo '<li class="breadcrumb-item"><a href="' . esc_url( get_term_link($term_for_breadcrumbs) ) . '">' . esc_html( $term_for_breadcrumbs->name ) . '</a></li>';
        }
      }

      // 6) Текущий товар
      echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html( get_the_title() ) . '</li>';
      ?>
    </ul>
  </nav>
    <article class="myproduct-card">
    <div class="row">
        <!-- Левая колонка: изображения (только на больших экранах) -->
        <div class="col-lg-4 col-md-5 d-none d-lg-flex flex-column align-items-center align-items-md-start">
            <?php
            // Главное изображение (ACF)
            $main_image = get_field('main_image');
            if ($main_image) {
                echo '<div class="myproduct-img mb-3"><img src="' . esc_url($main_image) . '" alt="' . esc_attr(get_the_title()) . '"></div>';
            }
            // --- Категорийное изображение-заглушка (строка-URL) ---
            $terms = get_the_terms(get_the_ID(), 'myproduct_cat');
            if ($terms && !is_wp_error($terms)) {
                $category = $terms[0];
                $cat_img = get_field('изображение_для_дочерних_товаров', 'myproduct_cat_' . $category->term_id);
                if (!empty($cat_img)) {
                    echo '<div class="myproduct-cat-img mb-3"><img src="' . esc_url($cat_img) . '" alt="' . esc_attr($category->name) . '"></div>';
                }
            }
            ?>
            <!-- Кнопка "Расчитать стоимость" для десктопа -->
            <!-- <a href="#quick-order"
               class="btn btn-order mt-3 fancybox js-quick-order"
               data-title="<?php echo esc_attr( get_the_title() ); ?>">
              Расчитать стоимость
            </a> -->
            <span class="simple-basket-buy-now-wrap"><?php echo do_shortcode('[basket-buy-button]'); ?></span>
        </div>

        <!-- Правая колонка: всегда занимает всю ширину если скрыта левая -->
        <div class="col-lg-8 col-md-12 col-12">
            <h1><?php the_title(); ?></h1>
            <?php
            // Характеристики вертикальным списком, на мобиле — каждая пара на две строки
            $acf_fields = [
                'type'             => 'Тип',
                'standard'         => 'Стандарт',
                'dn'               => 'DN / NPS',
                'pn'               => 'PN / Class',
                'material'         => 'Материал',
                'temperature'      => 'Температура, °C',
                'diameter_in'      => 'Внутренний диаметр, мм',
                'diameter_out'     => 'Наружный диаметр, мм',
                'thickness'        => 'Толщина, мм',
                'filler'           => 'Наполнитель',
                'spiral_material'  => 'Материал спирали',
                'ring_outer'       => 'Материал внешнего кольца',
                'ring_inner'       => 'Материал внутреннего кольца',
            ];
            $has_values = false;
            foreach ($acf_fields as $slug => $label) {
                if (get_field($slug)) {
                    $has_values = true;
                    break;
                }
            }
            if ($has_values) {
                echo '<div class="myproduct-features-vertical">';
                foreach ($acf_fields as $slug => $label) {
                    $value = get_field($slug);
                    if ($value) {
                        echo '<div class="feature-item"><span class="feature-item-label">' . esc_html($label) . ':</span> <span class="feature-item-value">' . esc_html($value) . '</span></div>';
                    }
                }
                echo '</div>';
            }

            // Кнопка "Расчитать стоимость" для мобильных
            echo '<a href="#quick-order"
                   class="btn btn-order btn-order-mobile d-lg-none fancybox js-quick-order"
                   data-title="' . esc_attr( get_the_title() ) . '">
                  Расчитать стоимость
                </a>';
            ?>
        </div>
    </div>

    <!-- Описание + Доставка и оплата (табы) -->
    <div class="row">
      <div class="col-12">
        <?php
          $content        = get_the_content();
          $acf_description= get_field('description');
          $title          = get_the_title();

          // Содержимое вкладки "Описание"
          $tab_desc = '';
          if ($content) {
            // снимаем автозаголовок H2, чтобы не дублировать
            $tab_desc = apply_filters('the_content', $content);
          } elseif ($acf_description) {
            $tab_desc = wpautop($acf_description);
          } else {
            $tab_desc = '<p>Описание формируется на основании технических требований и спецификаций. Запросите консультацию — подберём материал и конструкцию под ваш режим работы.</p>';
          }

          // Содержимое вкладки "Доставка и оплата" (SEO-сжатая версия)
          ob_start();
          ?>
          <div class="shipping-payment">
            <div class="item-accordion__title">Доставка по России и условия оплаты</div>
            <p><strong>Ю-Сеал</strong> отгружает прокладки по всей России. По Уфе доступна бесплатная доставка. В регионы бесплатно довозим до терминала ТК «Деловые линии»; далее транспортные услуги оплачивает заказчик. Также можем отгрузить в любую удобную ТК/службу: СДЭК, DPD, ПЭК, КИТ, Байкал-Сервис, Луч — по согласованию.</p>

            <strong>Сроки</strong>
            <ul class="sroki">
              <li>Екатеринбург, Челябинск — ориентировочно 3 дня.</li>
              <li>Саратов — ориентировочно 3–5 дней.</li>
              <li>Другие направления — по тарифам и срокам выбранной ТК.</li>
            </ul>

            <strong>Самовывоз</strong>
            <p>Возможен самовывоз со склада. Подготовим товар и документы к указанному времени.</p>

            <strong>Оплата</strong>
            <ul>
              <li>Работаем с юридическими лицами, с НДС.</li>
              <li>Безналичный расчёт: предоплата или постоплата.</li>
              <li>Отсрочка платежа до <strong>150 дней</strong> — по согласованию и договору.</li>
            </ul>

            <strong>Документы</strong>
            <p>К каждой партии предоставляем паспорт качества и сертификат соответствия. Дополнительная документация — по запросу.</p>

          </div>
          <?php
          $tab_shipping = ob_get_clean();
        ?>

        <div class="product-tabs" data-tabs>
          <div class="product-tabs__nav" role="tablist" aria-label="Информация о товаре">
            <button class="product-tabs__btn is-active" role="tab" aria-selected="true" aria-controls="tab-desc" id="tab-desc-btn">
              Описание
            </button>
            <button class="product-tabs__btn" role="tab" aria-selected="false" aria-controls="tab-ship" id="tab-ship-btn">
              Доставка и оплата
            </button>
          </div>

          <div class="product-tabs__panes">
            <div id="tab-desc" class="product-tabs__pane is-active" role="tabpanel" tabindex="0" aria-labelledby="tab-desc-btn">
              <?php echo $tab_desc; ?>
            </div>
            <div id="tab-ship" class="product-tabs__pane" role="tabpanel" tabindex="0" aria-labelledby="tab-ship-btn">
              <?php echo $tab_shipping; ?>
            </div>
          </div>
        </div>
      </div>
    </div>

		<?php
		// === Похожие товары (4 случайных из той же категории) ===
		$term_ids = array();

		// Используем уже найденную в хлебных крошках категорию, если есть
		if ( isset($term_for_breadcrumbs) && $term_for_breadcrumbs instanceof WP_Term ) {
			$term_ids = array( (int) $term_for_breadcrumbs->term_id );
		} else {
			// Фоллбек: берём все привязанные термины текущего товара
			$terms_for_related = get_the_terms( get_the_ID(), 'myproduct_cat' );
			if ( $terms_for_related && ! is_wp_error($terms_for_related) ) {
				$term_ids = wp_list_pluck( $terms_for_related, 'term_id' );
			}
		}

		if ( ! empty($term_ids) ) :
			$related_q = new WP_Query(array(
				'post_type'           => 'myproduct',
				'posts_per_page'      => 4,
				'post__not_in'        => array( get_the_ID() ),
				'orderby'             => 'rand',
				'tax_query'           => array(
					array(
						'taxonomy'         => 'myproduct_cat',
						'field'            => 'term_id',
						'terms'            => $term_ids,
						'include_children' => true,
						'operator'         => 'IN',
					),
				),
				'ignore_sticky_posts' => true,
				'no_found_rows'       => true,
			));
			if ( $related_q->have_posts() ) : ?>
				<section class="related-products mt-5">
					<div class="container">
						<h2 class="mb-4 item-accordion__title">Похожая продукция</h2>
						<div class="row">
							<?php while ( $related_q->have_posts() ) : $related_q->the_post(); ?>
								<div class="col-12 col-sm-6 col-lg-3 mb-4">
									<a class="card h-100 text-decoration-none related-card" href="<?php the_permalink(); ?>">
										<div class="card-img-top d-flex align-items-center justify-content-center" style="height:auto; overflow:hidden; background:#f7f7f7;">
											<?php
											// Картинка: ACF main_image -> категория товара -> пустой плейсхолдер
											$img_url = get_field('main_image', get_the_ID());

											if ( empty($img_url) ) {
												$p_terms = get_the_terms( get_the_ID(), 'myproduct_cat' );
												if ( $p_terms && ! is_wp_error($p_terms) ) {
													// берём первую категорию товара
													$cat_img = get_field('изображение_для_дочерних_товаров', 'myproduct_cat_' . $p_terms[0]->term_id);
													if ( ! empty($cat_img) ) {
														$img_url = $cat_img;
													}
												}
											}

											if ( ! empty($img_url) ) {
												echo '<img src="' . esc_url($img_url) . '" alt="' . esc_attr(get_the_title()) . '" style="max-height:100%; max-width:100%; object-fit:contain;">';
											} else {
												echo '<div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:#999;">Нет изображения</div>';
											}
											?>
										</div>
										<div class="card-body">
											<div class="card-title h6 mb-2" style="line-height:1.3;"><?php echo esc_html( get_the_title() ); ?></div>
										</div>
									</a>
								</div>
							<?php endwhile; ?>
						</div>
					</div>
				</section>
			<?php
			endif;
			wp_reset_postdata();
		endif;
		// === / Похожие товары ===
		?>

        <section class="section-application section-application_question">
            <div class="application-block">
                <div class="container">
                    <div class="title-section">Остались вопросы по прокладкам<?php echo !empty(get_field('вхождение_в_заголовок_формы')) ? ' ' . get_field('вхождение_в_заголовок_формы') : ''; ?>?</div>
                    <div class="descr-application">Укажите свои данные и мы свяжется с Вами в течение 15 минут</div>
                    <?php echo do_shortcode('[contact-form-7 id="088688b" title="Вопрос оплаты и доставки"]'); ?>
                </div>
            </div>
        </section>

        <?php
        // Галерея (ACF)
        $gallery = get_field('gallery');
        if ($gallery && is_array($gallery)) {
            echo '<div class="myproduct-gallery">';
            foreach ($gallery as $img) {
                echo '<img src="' . esc_url($img['url']) . '" alt="" style="max-width:130px;margin:5px;">';
            }
            echo '</div>';
        }

        // PDF-файл (ACF)
        $pdf_file = get_field('pdf_file');
        if ($pdf_file) {
            echo '<p><a href="' . esc_url($pdf_file) . '" target="_blank" rel="noopener" class="btn btn-success mt-3">Скачать PDF-документ</a></p>';
        }
        ?>

    </article>
</div>

<!-- ========== МОДАЛКА: БЫСТРЫЙ ЗАКАЗ ========== -->
<div id="quick-order" class="modal-block" style="display:none;">
  <div class="modal-wrap">
    <div class="title-modal">Быстрый расчет стоимости</div>
    <div class="descr-modal">Заполните форму и наш специалист свяжется с вами в течение 15 минут</div>

    <div class="modal-product" style="margin: 8px 0 14px; font-size: 14px; color: #666;">
      Товар: <strong class="js-selected-product">—</strong>
    </div>

    <?php echo do_shortcode('[contact-form-7 id="8b5c758" title="Быстрый заказ"]'); ?>
  </div>
</div>

<!-- ========== СКРИПТ: БЫСТРЫЙ ЗАКАЗ ========== -->
<script>
(function(){
  // безопасная подстановка значения (создаст hidden, если поля в форме нет)
  function setField(form, name, value) {
    if (!form) return;
    var field = form.querySelector('[name="'+name+'"]');
    if (!field) {
      field = document.createElement('input');
      field.type  = 'hidden';
      field.name  = name;
      form.appendChild(field);
    }
    field.value = value;
    field.dispatchEvent(new Event('input',{bubbles:true}));
    field.dispatchEvent(new Event('change',{bubbles:true}));
  }

  // Открыть один-единственный инстанс Fancybox как inline
  function openQuickOrder() {
    if (window.Fancybox && Fancybox.getInstance) {
      var inst = Fancybox.getInstance(); if (inst) inst.close(true);
    } else if (window.jQuery && jQuery.fancybox) {
      try { jQuery.fancybox.close(true); } catch(e){}
    }

    if (window.Fancybox && (Fancybox.show || Fancybox.open)) {
      var opts = {
        dragToClose: false,
        closeButton: "inside",
        Toolbar: false, Thumbs: false,
        trapFocus: true, autoFocus: false, placeFocusBack: true
      };
      if (Fancybox.show) Fancybox.show([{ src:"#quick-order", type:"html" }], opts);
      else Fancybox.open([{ src:"#quick-order", type:"html" }], opts);
      return;
    }
    if (window.jQuery && jQuery.fancybox) {
      jQuery.fancybox.open({
        src: '#quick-order',
        type: 'inline',
        opts: {
          smallBtn: true, toolbar: false, infobar: false, arrows: false,
          touch: false, autoFocus: false
        }
      });
      return;
    }

    // Фолбэк без Fancybox
    var m = document.getElementById('quick-order');
    if (m){ m.style.display='block'; m.classList.add('is-open'); }
  }

  // Кнопка «Быстрый заказ»
  document.addEventListener('click', function(e){
    var btn = e.target.closest('.js-quick-order');
    if (!btn) return;
    e.preventDefault();

    var title = btn.getAttribute('data-title') || '';
    var badge = document.querySelector('#quick-order .js-selected-product');
    if (badge) badge.textContent = title;

    // заполняем форму: название + дефолт количества
    setTimeout(function(){
      var form = document.querySelector('#quick-order .wpcf7 form');
      if (!form) return;
      setField(form, 'product_title', title);
      var qty = form.querySelector('[name="quantity"]');
      if (qty && !qty.value) setField(form, 'quantity', '1');
      if (!qty) setField(form, 'quantity', '1');
      // your-name и your-tel пользователь вводит сам
    }, 20);

    openQuickOrder();
  }, false);

  // Закрытие после успешной отправки CF7 — ОДИН раз
  document.addEventListener('wpcf7mailsent', function(){
    if (window.Fancybox && Fancybox.getInstance) {
      var fb = Fancybox.getInstance(); if (fb) fb.close(true);
    }
    if (window.jQuery && jQuery.fancybox) {
      try { jQuery.fancybox.close(true); } catch(e){}
    }
    var m = document.getElementById('quick-order');
    if (m) m.classList.remove('is-open');
  }, false);
})();
</script>

<?php
// ========== JSON-LD: SCHEMA.ORG ==========
// Собираем данные для Product
$product_id   = get_the_ID();
$title        = get_the_title($product_id);
$permalink    = get_permalink($product_id);
$desc_raw     = get_the_content(null, false, $product_id);
$desc_acf     = get_field('description', $product_id);
$description  = wp_strip_all_tags( $desc_raw ? apply_filters('the_content', $desc_raw) : ($desc_acf ?: '') );
$sku          = trim( (string) get_field('sku', $product_id) );
$material     = trim( (string) get_field('material', $product_id) );
$price_raw    = get_field('price', $product_id);
$price        = is_numeric($price_raw) && $price_raw > 0 ? (string) $price_raw : null;
$main_image   = get_field('main_image', $product_id);
$gallery      = get_field('gallery', $product_id);

// Картинки: главное + первые из галереи (не более 6)
$images = array();
if ( !empty($main_image) ) {
    $images[] = esc_url_raw($main_image);
}
if ( is_array($gallery) ) {
    foreach ($gallery as $g) {
        if (!empty($g['url'])) {
            $images[] = esc_url_raw($g['url']);
        }
        if (count($images) >= 6) break;
    }
}
$images = array_values(array_unique($images));

// Категория по хлебным крошкам/терминам
$category_names = array();
if ( isset($term_for_breadcrumbs) && $term_for_breadcrumbs instanceof WP_Term ) {
    // предки + сама категория
    $anc_ids = array_reverse( get_ancestors($term_for_breadcrumbs->term_id, 'myproduct_cat') );
    foreach ($anc_ids as $aid) {
        $at = get_term($aid, 'myproduct_cat');
        if ($at && !is_wp_error($at)) $category_names[] = $at->name;
    }
    $category_names[] = $term_for_breadcrumbs->name;
} else {
    $terms_tmp = get_the_terms($product_id, 'myproduct_cat');
    if ($terms_tmp && !is_wp_error($terms_tmp)) {
        $category_names = wp_list_pluck($terms_tmp, 'name');
    }
}

// Доп. характеристики как PropertyValue
$extra_keys = array(
    'standard'        => 'Стандарт',
    'dn'              => 'DN / NPS',
    'pn'              => 'PN / Class',
    'temperature'     => 'Температура, °C',
    'diameter_in'     => 'Внутренний диаметр, мм',
    'diameter_out'    => 'Наружный диаметр, мм',
    'thickness'       => 'Толщина, мм',
    'filler'          => 'Наполнитель',
    'spiral_material' => 'Материал спирали',
    'ring_outer'      => 'Материал внешнего кольца',
    'ring_inner'      => 'Материал внутреннего кольца',
    'type'            => 'Тип',
);
$additional_props = array();
foreach ($extra_keys as $key => $label) {
    $val = get_field($key, $product_id);
    if (!empty($val)) {
        $additional_props[] = array(
            '@type' => 'PropertyValue',
            'name'  => $label,
            'value' => wp_strip_all_tags( (string) $val ),
        );
    }
}

// Сборка Product
$product_ld = array(
    '@context' => 'https://schema.org',
    '@type'    => 'Product',
    '@id'      => trailingslashit($permalink) . '#product',
    'name'     => $title,
    'url'      => $permalink,
    // Google любит массив изображений
    'image'    => !empty($images) ? $images : null,
    // Обрежем описание до разумной длины
    'description' => mb_strimwidth($description, 0, 5000, '…', 'UTF-8'),
    'brand'    => array(
        '@type' => 'Brand',
        'name'  => 'U-Seal',
    ),
);

// Категория (массив в строку «A > B > C» + первая категория отдельно)
if (!empty($category_names)) {
    $product_ld['category'] = implode(' > ', $category_names);
}

// SKU / material
if (!empty($sku))      { $product_ld['sku']      = $sku; }
if (!empty($material)) { $product_ld['material'] = $material; }

// Доп. хар-ки
if (!empty($additional_props)) {
    $product_ld['additionalProperty'] = $additional_props;
}

// Offers: добавляем только валидные поля
$offers = array(
    '@type'         => 'Offer',
    'url'           => $permalink,
    'availability'  => 'https://schema.org/InStock',
    'itemCondition' => 'https://schema.org/NewCondition',
    'priceCurrency' => 'RUB',
);
if (!is_null($price)) {
    $offers['price'] = $price;
}
$product_ld['offers'] = $offers;

// ---------- BreadcrumbList ----------
$breadcrumb_items = array();
$pos = 1;
$breadcrumb_items[] = array(
    '@type'    => 'ListItem',
    'position' => $pos++,
    'name'     => 'Главная',
    'item'     => home_url('/'),
);
$breadcrumb_items[] = array(
    '@type'    => 'ListItem',
    'position' => $pos++,
    'name'     => 'Каталог продукции',
    'item'     => home_url('/myproduct/'),
);

// Те же крошки, что и в верстке
if ( isset($term_for_breadcrumbs) && $term_for_breadcrumbs instanceof WP_Term ) {
    $anc_ids = array_reverse( get_ancestors($term_for_breadcrumbs->term_id, 'myproduct_cat') );
    foreach ($anc_ids as $aid) {
        $at = get_term($aid, 'myproduct_cat');
        if ($at && !is_wp_error($at)) {
            $breadcrumb_items[] = array(
                '@type'    => 'ListItem',
                'position' => $pos++,
                'name'     => $at->name,
                'item'     => get_term_link($at),
            );
        }
    }
    $breadcrumb_items[] = array(
        '@type'    => 'ListItem',
        'position' => $pos++,
        'name'     => $term_for_breadcrumbs->name,
        'item'     => get_term_link($term_for_breadcrumbs),
    );
}

// Текущая страница товара
$breadcrumb_items[] = array(
    '@type'    => 'ListItem',
    'position' => $pos++,
    'name'     => $title,
    'item'     => $permalink,
);

$breadcrumb_ld = array(
    '@context'        => 'https://schema.org',
    '@type'           => 'BreadcrumbList',
    '@id'             => trailingslashit($permalink) . '#breadcrumb',
    'itemListElement' => $breadcrumb_items,
);

// Выводим две сущности одним массивом
$ld_output = array($product_ld, $breadcrumb_ld);
?>
<script type="application/ld+json">
<?php echo wp_json_encode($ld_output, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT); ?>
</script>

<?php
endwhile; endif;
get_footer();
?>