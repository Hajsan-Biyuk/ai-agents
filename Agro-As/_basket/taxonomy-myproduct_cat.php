<?php
/**
 * Template: Archive for taxonomy "myproduct_cat"
 * File: taxonomy-myproduct_cat.php
 */

get_header();

$term = get_queried_object();
?>

<div class="catalog-archive container my-4">
  <!-- Хлебные крошки -->
  <nav class="catalog-breadcrumb mb-3" aria-label="breadcrumb">
    <ul class="breadcrumbs">
      <li class="breadcrumb-item"><a href="<?php echo esc_url( home_url('/') ); ?>">Главная</a></li>
      <li class="breadcrumb-item"><a href="https://u-seal.ru/myproduct/">Каталог продукции</a></li>
      <?php
      // выводим цепочку предков
      if ( $term && $term->taxonomy === 'myproduct_cat' ) {
        $ancestors = array_reverse( get_ancestors( $term->term_id, 'myproduct_cat' ) );
        foreach ( $ancestors as $ancestor_id ) {
          $ancestor = get_term( $ancestor_id, 'myproduct_cat' );
          if ( ! is_wp_error( $ancestor ) ) {
            echo '<li class="breadcrumb-item"><a href="' . esc_url( get_term_link($ancestor) ) . '">' . esc_html( $ancestor->name ) . '</a></li>';
          }
        }
        echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html( $term->name ) . '</li>';
      }
      ?>
    </ul>
  </nav>

  <div class="row">
    <!-- Сайдбар категорий -->
    <aside class="catalog-sidebar col-12 col-lg-3 mb-4 mb-lg-0">
      <div class="catalog-sidebar-card card">
        <div class="card-header fw-semibold">Категории</div>
        <div class="catalog-categories list-group list-group-flush">
          <?php
          wp_list_categories([
            'taxonomy'   => 'myproduct_cat',
            'title_li'   => '',
            'hide_empty' => false,
            'show_count' => false,
          ]);
          ?>
        </div>
      </div>
    </aside>

    <!-- Контент: таблица товаров -->
    <main class="catalog-content col-12 col-lg-9">
      <h1 class="catalog-title h3 mb-3"><?php echo esc_html( $term->name ); ?></h1>

 <?php     // ─── Данные страницы ──────────────────────────────────────────────────────────
$page_title  = acf_filter_data( 'page_title' );
$page_desc   = acf_filter_data( 'page_description' );
$filter_url  = acf_filter_data( 'filter_url', home_url( '/filter/' ) );
$reset_url   = acf_filter_data( 'reset_url',  home_url( '/filter/' ) );
$is_filtered = acf_filter_data( 'is_filtered', false );


// Поля формы: конфигурация + доступные + активные значения
$form_fields = acf_filter_get_form_fields(); ?>

<aside class="filter-page__sidebar">
  <?php get_template_part( 'acf-filter-form' ); ?>
</aside>

      <?php if ( have_posts() ) : ?>
        <div class="catalog-table-wrapper table-responsive">
          <table class="catalog-table table table-striped align-middle">
            <thead>
              <tr>
                <th style="width:50%;">Название</th>
                <th style="width:20%;">Стандарт</th>
                <th style="width:15%;">Класс давления</th>
                <th style="width:15%;" class="text-end">Цена</th>
              </tr>
            </thead>
            <tbody>
              <?php while ( have_posts() ) : the_post(); ?>
                <tr>
                  <td class="catalog-col-title">
                    <a href="<?php echo esc_url( get_permalink( get_the_ID() ) ); ?>" class="catalog-link text-decoration-none">
                      <?php the_title(); ?>
                    </a>
                  </td>
                  <td class="catalog-col-standard">
                    <?php
                    $std = '';
                    if ( function_exists('get_field') ) {
                      $std = get_field('standard');
                      if ( ! $std ) {
                        $params = get_field('parametry_tovara'); // если поле — группа
                        if ( is_array($params) && isset($params['standard']) ) {
                          $std = $params['standard'];
                        }
                      }
                    } else {
                      $std = get_post_meta(get_the_ID(), 'standard', true);
                    }
                    echo esc_html( $std ?: '-' );
                    ?>
                  </td>
                  <td class="catalog-col-pn">
                    <?php
                    $pn = '';
                    if ( function_exists('get_field') ) {
                      $pn = get_field('pn');
                      if ( ! $pn ) {
                        $params = isset($params) && is_array($params) ? $params : get_field('parametry_tovara');
                        if ( is_array($params) && isset($params['pn']) ) {
                          $pn = $params['pn'];
                        }
                      }
                    } else {
                      $pn = get_post_meta(get_the_ID(), 'pn', true);
                    }
                    echo esc_html( $pn ?: '-' );
                    ?>
                  </td>
                  <td class="text-end">
                    <!-- Кнопка открывает локальную inline-модалку #quick-order -->
                    <!-- <a href="#quick-order"
                       class="btn btn-sm btn-primary js-quick-order"
                       data-title="<?php echo esc_attr( get_the_title() ); ?>">
                      Расчитать стоимость
                    </a> -->
                    <?php echo do_shortcode('[basket-buy-button]'); ?>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>

        <?php
        // Пагинация
        global $wp_query;
        if ( $wp_query->max_num_pages > 1 ) : ?>
          <div class="row">
            <div class="col-lg-12">
              <nav class="pager pager--catalog" aria-label="Навигация страниц">
                <?php
                $big   = 999999999;
                $paged = max( 1, get_query_var('paged') ? get_query_var('paged') : get_query_var('page') );

                echo paginate_links( array(
                  'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                  'format'    => get_option('permalink_structure') ? 'page/%#%/' : '?paged=%#%',
                  'current'   => $paged,
                  'total'     => $wp_query->max_num_pages,
                  'prev_text' => '<span aria-hidden="true">&laquo;</span>',
                  'next_text' => '<span aria-hidden="true">&raquo;</span>',
                  'type'      => 'list',
                ) );
                ?>
              </nav>
            </div>
          </div>
        <?php endif; ?>

       

      <?php else : ?>
        <div class="catalog-empty alert alert-info">В этой категории пока нет товаров.</div>
      <?php endif; ?>
    </main>
	   <?php
        // Описание категории
        $desc = term_description( $term->term_id, 'myproduct_cat' );
        if ( $desc ) : ?>
          <div class="mt-4">
            <div class="card">
              <div class="card-body">
                <?php echo $desc; ?>
              </div>
            </div>
          </div>
        <?php endif; ?>
  </div>
</div>

<!-- Модалка: Быстрый заказ (в той же стилистике, что #callback) -->
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

<script>
(function () {
  try {
    // 0) Не дублируем FAQPage, если уже есть
    var hasFAQ = Array.prototype.some.call(
      document.querySelectorAll('script[type="application/ld+json"]'),
      function (s) {
        try {
          var j = JSON.parse(s.textContent || s.innerText || 'null');
          return j && (j['@type'] === 'FAQPage' || (Array.isArray(j) && j.some(function (x){return x && x['@type']==='FAQPage';})));
        } catch (e) { return false; }
      }
    );
    if (hasFAQ) return;

    // 1) Ищем контейнер описания категории
    var root = document.querySelector('.category-seo');
    if (!root) return;

    // 2) Ищем заголовок FAQ (любой язык с "faq" в тексте, без учёта регистра)
    var faqH3 = Array.prototype.find.call(root.querySelectorAll('h3'), function (h3) {
      return /faq/i.test((h3.textContent || '').trim());
    });

    // 3) Собираем все <details> после этого h3 до следующего h3.
    var details = [];
    if (faqH3) {
      var el = faqH3.nextElementSibling;
      while (el && el.tagName !== 'H3') {
        if (el.tagName === 'DETAILS') details.push(el);
        el = el.nextElementSibling;
      }
    } else {
      // Фолбэк: берём все details внутри .category-seo
      details = Array.prototype.slice.call(root.querySelectorAll('details'));
    }
    if (!details.length) return;

    // 4) Вспомогательные функции
    var trimText = function (htmlNode) {
      var txt = (htmlNode && (htmlNode.textContent || htmlNode.innerText || '') || '')
        .replace(/\s+/g, ' ')
        .replace(/\u00A0/g, ' ')
        .trim();
      return txt;
    };

    // 5) Формируем массив Q&A (до 10)
    var qa = [];
    for (var i = 0; i < details.length && qa.length < 10; i++) {
      var d = details[i];
      var qNode = d.querySelector(':scope > summary');
      if (!qNode) continue;

      // Ответ: берем первый div|p внутри details, иначе весь details без summary
      var aNode = d.querySelector(':scope > div, :scope > p');
      if (!aNode) {
        // создаём временную копию без summary
        var clone = d.cloneNode(true);
        var sum = clone.querySelector('summary');
        if (sum) sum.remove();
        aNode = clone;
      }

      var q = trimText(qNode);
      var a = trimText(aNode);

      if (!q || !a) continue;

      qa.push({
        "@type": "Question",
        "name": q,
        "acceptedAnswer": {
          "@type": "Answer",
          "text": a
        }
      });
    }

    if (!qa.length) return;

    // 6) Собираем JSON-LD
    var data = {
      "@context": "https://schema.org",
      "@type": "FAQPage",
      "mainEntity": qa
    };

    // 7) Вставляем в <head>
    var s = document.createElement('script');
    s.type = 'application/ld+json';
    s.text = JSON.stringify(data);
    document.head.appendChild(s);
  } catch (e) {
    // мягко игнорируем ошибки — не ломаем страницу
    // console.warn('FAQ JSON-LD error', e);
  }
})();
</script>

<?php get_footer(); ?>
