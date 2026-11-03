<?php
get_header();
?>
<?php $banner = get_field('баннер'); ?>
<section class="billbord front-page-billbord">
    <div class="container">
        <?php if(!empty($banner['слайдер'])){ ?>
            <div class="slider-billbord">
    <?php foreach ($banner['слайдер'] as $v_slider) { ?>     
        <div>
            <div class="billbord__item">
                <div class="billbord__content-wr">
                    <div class="title-section"><?php echo $v_slider['заголовок']; ?></div>
                    <div class="billbord__content">
                        <div class="title-middle"><?php echo $v_slider['подзаголовок']; ?></div>
                        <div class="billbord__wrap">
                            <div class="billbord__text">
                                <div class="mob-hidden">
                                    <?php echo $v_slider['описание']; ?>
                                </div>
                                <?php if (!empty($v_slider['кнопка']['ссылка'])) { ?>
                                    <a href="<?php echo $v_slider['кнопка']['ссылка']; ?>"
                                       class="btn-main btn-main_page <?php if (!empty($v_slider['кнопка']['модалка'])) { ?>fancybox<?php } ?>">
                                        <?php echo $v_slider['кнопка']['текст_кнопки']; ?>
                                    </a>
                                <?php } ?>
								<a href="tel:+79022223187" class="btn-main btn-main_phone">Позвонить</a>
                            </div>
                            <?php if (!empty($v_slider['акция'])) { ?>
                                <div class="circle-unit">
                                    <?php echo $v_slider['акция']; ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <?php if (!empty($v_slider['изображение_баннера_мобильное'])) { ?>
                    <div class="billbord__image billbord_mob">
                        <img src="<?php echo $v_slider['изображение_баннера_мобильное']['url']; ?>" alt="alt" class="img-mob">
                        <?php if (!empty($v_slider['изображение_баннера'])) { ?>
                            <img src="<?php echo $v_slider['изображение_баннера']['url']; ?>" alt="alt" class="img-desktop">
                        <?php } ?>
                    </div>
                <?php } else { ?>
                    <div class="billbord__image">
                        <?php if (!empty($v_slider['изображение_баннера'])) { ?>
                            <img src="<?php echo $v_slider['изображение_баннера']['url']; ?>" alt="alt">
                        <?php } ?>
                    </div>
                <?php } ?>

                <!--
                <div class="billbord__picture">
                    <img src="<?php bloginfo('template_directory'); ?>/img/billbord_picture1.png" alt="alt">
                </div>
                -->

                <?php if (!empty($v_slider['сноска'])) { ?>
                    <div class="billbord__subdescr">
                        <?php echo $v_slider['сноска']; ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
</div>

        <?php } if(!empty(get_field('преимущества'))){ ?>
            <div class="advantages">
				<h1 class="front-page-title-section">
					Производим ортопедическое оборудование для КРС и оказываем услуги по обработке копыт
				</h1>
                <div class="row">
                    <?php foreach (get_field('преимущества') as $v_adv) { ?>
                        <div class="col-lg-3 col-6">
                            <div class="item-advantage">
                                <div class="item-advantage__head"><?php if(!empty($v_adv['значение'])){ ?><strong><?php echo $v_adv['значение']; ?></strong><?php } ?> <?php echo $v_adv['заголовок']; ?></div>
                                <div class="item-advantage__text"><?php echo $v_adv['текст']; ?></div>
                                <div class="item-advantage__image">
                                    <?php if(!empty($v_adv['изображение'])){ ?>
                                        <img src="<?php echo $v_adv['изображение']['sizes']['medium_large']; ?>" alt="alt">
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>                   
                </div>
            </div>
        <?php } ?>
    </div>
</section>
<?php $category = get_field('категории');
if(!empty($category['блоки'])){ ?>
<section class="categories">
    <div class="container">
        <?php if (!empty($category['заголовок'])): ?>
    <h2 class="title-section"><?php echo $category['заголовок']; ?></h2>
<?php endif; ?>
        <div class="title-min"><?php echo $category['подзаголовок']; ?></div>
        <div class="row row_main">
            <?php foreach ($category['блоки'] as $v_b) { ?>
                <div class="col-lg-4">
                    <div class="item-category">
                        <a href="<?php echo $v_b['кнопка']['ссылка_кнопки']; ?>" class="title-middle"><?php echo $v_b['заголовок']; ?></a>
                        <div class="title-small"><?php echo $v_b['подзаголовок']; ?></div>
                        <div class="item-category__image">
                            <?php if(!empty($v_b['изображение'])){ ?>
                                <img src="<?php echo $v_b['изображение']['sizes']['large']; ?>" alt="alt">
                            <?php } ?>
                        </div>
                        <a href="<?php echo $v_b['кнопка']['ссылка_кнопки']; ?>" class="btn-simple"><?php echo $v_b['кнопка']['текст_кнопки']; ?> <i class="far fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            <?php } ?>            
        </div>
    </div>
</section>
<?php } $etaps = get_field('этапы');
if(!empty($etaps['блоки'])){ ?>
<section class="etaps">
    <div class="container">
        <h2 class="title-section"><?php echo $etaps['заголовок']; ?></h2>
        <div class="row row_etaps">
            <?php foreach ($etaps['блоки'] as $k_etap => $v_etap) { ?>
                <div class="col-lg-4 col-6">
                    <div class="item-etap">
                        <div class="item-etap__head">
                            <span>0<?php echo $k_etap+1; ?></span>
                            <svg width="40" height="43" viewBox="0 0 40 43" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8.3 7.6001H0L11.3 21.5001L0 35.3001H8.3L19.6 21.5001L8.3 7.6001Z" fill="white"/>
                                <path d="M22.5001 0H9.6001L27.1001 21.5L9.6001 42.9H22.5001L40.0001 21.5L22.5001 0Z" fill="#FF9345"/>
                            </svg>
                        </div>
                        <?php echo $v_etap['текст']; ?>
                    </div>
                </div>
            <?php } ?>          
        </div>
        <div class="etaps__button">
            <a href="#modal-callback" class="btn-main fancybox">оставить заявку</a>
        </div>
    </div>
</section>
<?php } ?>

<?php $portfolio = get_field('портфолио', 2);
if(!empty($portfolio['показать'])){ ?>
<section class="portfolio">
    <div class="container">
        <h2 class="title-section"><?php echo $portfolio['заголовок']; ?></h2>
        <div class="title-min"><?php echo $portfolio['подзаголовок']; ?></div>
        <ul class="tabs">
            <li class="active"><a href="#tab1">все работы</a></li>
            <?php
			$terms = get_terms(
				array(
					'taxonomy' => 'portfolio_cat',
					'hide_empty' => false,		                     
					'parent' => 0
				)
			);
			foreach ($terms as $key => $term) { 
			?>
			<li><a href="#tab<?php echo $key+2; ?>"><?php echo $term->name;?></a></li>
			<?php } ?>          
        </ul>
        <div class="tab-container">
            <div class="tab-pane" id="tab1">
            <?php			               
			$paged = get_query_var('paged') ?: 1;
              $posts = array(              
                  'orderby' => 'menu_order',
                  'post_type'   => 'portfolio',
                  'meta_key'      => 'показать_на_главной',
                  'meta_value'    => true,
                  'posts_per_page' => -1,
                	'paged' => $paged,
                //'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
               );
			 $wp_query = new WP_Query($posts);
                if ( $wp_query->have_posts() ) { ?>
                <div class="slider-portfolio">
                <?php while ( $wp_query->have_posts() ) {                  
                	$wp_query->the_post(); ?>
                        <div>
                            <div class="item-portfolio">
							<?php if(!empty(get_field('галерея_изображений'))){ ?>
								<div class="slider-photos">
								<?php foreach (get_field('галерея_изображений') as $v_img) { ?>
									<div>
										<div class="slider-photos__item">
											<img src="<?php echo $v_img['sizes']['medium_large']; ?>" alt="alt">
										</div>
									</div>
								<?php } ?>									
								</div>
							<?php } ?>
								<div class="title-middle"><?php the_title(); ?></div>
								<div class="features row">
									<?php if(!empty(get_field('адрес_объекта'))){ ?>
										<div class="col-lg-6 features__item"><strong>Адрес объекта:</strong> <?php echo get_field('адрес_объекта'); ?></div>
									<?php } if(!empty(get_field('год_работы'))){ ?>
										<div class="col-lg-6 features__item"><strong>Год работы:</strong> <?php echo get_field('год_работы'); ?></div>
									<?php } if(!empty(get_field('поголовье'))){ ?>
										<div class="col-lg-6 features__item"><strong>Поголовье:</strong> <?php echo get_field('поголовье'); ?></div>
									<?php } if(!empty(get_field('список'))){
										foreach (get_field('список') as $v_li) { ?>
											<div class="col-lg-6 features__item"><strong><?php echo $v_li['заголовок']; ?></strong> <?php echo $v_li['текст']; ?></div>
									<?php }
									} ?>	
								</div>
								<div class="item-portfolio__buttons">
									<a href="<?php the_permalink(); ?>" class="btn-main btn-main_default">смотреть итог</a>
									<a href="#order-modal" class="btn-main fancybox" data-title="<?php the_title(); ?>">хочу также</a>
								</div>
							</div>
                        </div>  
                    <?php } ?>                 
                </div>
                <?php
                } wp_reset_postdata(); // сброс ?>	
            </div>
            <?php foreach ($terms as $key_c => $term_c) { ?>
                <div class="tab-pane" id="tab<?php echo $key_c+2; ?>">
                <?php			               
				$paged = get_query_var('paged') ?: 1; 

              $posts = array(                  
                  'tax_query' => [
                      [
                          'taxonomy' => 'portfolio_cat',
                          'terms' => $term_c->term_id,
                          'field' => 'id',
                          'include_children' => true // Remove if you need posts from term 7 child terms
                      ],
                  ],
                  'orderby' => 'menu_order',
                  'post_type'   => 'portfolio',
                  'meta_key'      => 'показать_на_главной',
                  'meta_value'    => true,
                  'posts_per_page' => -1,
                  'paged' => $paged,
                //'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
               );
			 $wp_query = new WP_Query($posts);
                if ( $wp_query->have_posts() ) { ?>
                    <div class="slider-portfolio">
                        <?php while ( $wp_query->have_posts() ) {                  
                        $wp_query->the_post(); ?>
                            <div>
                                <div class="item-portfolio">
                                <?php if(!empty(get_field('галерея_изображений'))){ ?>
                                    <div class="slider-photos">
                                    <?php foreach (get_field('галерея_изображений') as $v_img) { ?>
                                        <div>
                                            <div class="slider-photos__item">
                                                <img src="<?php echo $v_img['sizes']['medium_large']; ?>" alt="alt">
                                            </div>
                                        </div>
                                    <?php } ?>									
                                    </div>
                                <?php } ?>
                                    <div class="title-middle"><?php the_title(); ?></div>
                                    <div class="features">
                                        <?php if(!empty(get_field('адрес_объекта'))){ ?>
                                            <div class="features__item"><strong>Адрес объекта:</strong> <?php echo get_field('адрес_объекта'); ?></div>
                                        <?php } if(!empty(get_field('год_работы'))){ ?>
                                            <div class="features__item"><strong>Год работы:</strong> <?php echo get_field('год_работы'); ?></div>
                                        <?php } if(!empty(get_field('поголовье'))){ ?>
                                            <div class="features__item"><strong>Поголовье:</strong> <?php echo get_field('поголовье'); ?></div>
                                        <?php } ?>									
                                    </div>
                                    <div class="item-portfolio__buttons">
                                        <a href="<?php the_permalink(); ?>" class="btn-main btn-main_default">смотреть итог</a>
                                        <a href="#order-modal" class="btn-main fancybox" data-title="<?php the_title(); ?>">хочу также</a>
                                    </div>
                                </div>
                            </div>  
                        <?php } ?>                    
                    </div>
                    <?php
                    } wp_reset_postdata(); // сброс ?>	
                </div>
            <?php } ?>           
        </div>
    </div>
</section>
<?php } ?>

<?php
$farm_rows  = get_field( 'mini', 2 );
$farm_cards = array();
if ( is_array( $farm_rows ) ) {
	foreach ( $farm_rows as $farm_row ) {
		if ( ! is_array( $farm_row ) ) {
			continue;
		}
		$card = array(
			'titles' => array(),
			'texts'  => array(),
			'lists'  => array(),
			'image'  => '',
			'alt'    => '',
		);
		foreach ( $farm_row as $farm_key => $farm_value ) {
			if ( ! is_string( $farm_key ) || $farm_key === '' ) {
				continue;
			}
			if ( is_string( $farm_value ) ) {
				$farm_value = trim( $farm_value );
				if ( $farm_value === '' ) {
					continue;
				}
				if ( mb_strlen( $farm_value ) <= 140 ) {
					$card['titles'][] = $farm_value;
				} else {
					$card['texts'][] = $farm_value;
				}
			} elseif ( is_array( $farm_value ) && ! empty( $farm_value['url'] ) ) {
				$card['image'] = $farm_value['url'];
				$card['alt']   = isset( $farm_value['alt'] ) ? (string) $farm_value['alt'] : '';
			} elseif ( is_array( $farm_value ) ) {
				$items = array();
				foreach ( $farm_value as $farm_item ) {
					if ( is_string( $farm_item ) && trim( $farm_item ) !== '' ) {
						$items[] = trim( $farm_item );
					} elseif ( is_array( $farm_item ) ) {
						foreach ( $farm_item as $farm_piece ) {
							if ( is_string( $farm_piece ) && trim( $farm_piece ) !== '' ) {
								$items[] = trim( $farm_piece );
								break;
							}
						}
					}
				}
				if ( $items ) {
					$card['lists'][] = $items;
				}
			}
		}
		if ( $card['titles'] || $card['texts'] || $card['lists'] || $card['image'] !== '' ) {
			$farm_cards[] = $card;
		}
	}
}
if ( $farm_cards ) {
	$farm_photo = get_template_directory_uri() . '/img/farm-check.png';
	?>
<section class="farm-check" id="farm-check">
    <div class="container">
        <?php foreach ( $farm_cards as $farm_card ) {
			$farm_img = $farm_card['image'] !== '' ? $farm_card['image'] : $farm_photo;
			$farm_alt = $farm_card['alt'] !== '' ? $farm_card['alt'] : 'Торговый представитель с планшетом осматривает коров в коровнике';
			$farm_btn = ! empty( $farm_card['titles'][0] ) ? $farm_card['titles'][0] : 'Проверить ферму';
			?>
        <div class="farm-check__card">
            <div class="row farm-check__row">
                <div class="col-lg-6">
                    <div class="farm-check__content">
                        <div class="farm-check__body">
                            <?php foreach ( $farm_card['titles'] as $farm_title ) { ?>
                                <h2 class="farm-check__title"><?php echo esc_html( $farm_title ); ?></h2>
                            <?php } ?>
                            <?php foreach ( $farm_card['texts'] as $farm_text ) { ?>
                                <div class="farm-check__text"><?php echo wp_kses_post( wpautop( $farm_text ) ); ?></div>
                            <?php } ?>
                            <?php foreach ( $farm_card['lists'] as $farm_list ) { ?>
                                <ul class="farm-check__list">
                                    <?php foreach ( $farm_list as $farm_item ) { ?>
                                        <li><?php echo esc_html( $farm_item ); ?></li>
                                    <?php } ?>
                                </ul>
                            <?php } ?>
                        </div>
                        <div class="farm-check__actions">
                            <a href="#order-modal" class="farm-check__btn farm-check__btn--order fancybox" data-title="<?php echo esc_attr( $farm_btn ); ?>">Оставить заявку</a>
                            <a href="#farm-check" class="farm-check__btn farm-check__btn--more">Подробнее</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 farm-check__photo-col">
                    <div class="farm-check__photo">
                        <img src="<?php echo esc_url( $farm_img ); ?>" alt="<?php echo esc_attr( $farm_alt ); ?>">
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</section>
<?php } ?>

<?php if (have_rows('отзывы', 2)) : ?>
<section class="reviews-section">
    <div class="container">
		<div class="row">
			<div class="col-sm-5">
				<h2 class="title-section">Отзывы наших клиентов</h2>
			</div>
			<div class="col-sm-7">
				<iframe src="https://yandex.ru/sprav/widget/rating-badge/90035872256?type=rating" width="150" height="50" frameborder="0"></iframe>
			</div>
		</div>
        <div class="reviews-slider row">
            <?php while (have_rows('отзывы', 2)) : the_row(); 
                $image = get_sub_field('изображение', 2);
                $company = get_sub_field('компания', 2);
                $text = get_sub_field('текст_отзыва', 2);
            ?>
                <div class="col-sm-6">
                    <div class="review-card">
                        <div class="review-header">
                            <?php if ($image) : ?>
                                <img src="<?php echo esc_url($image['sizes']['thumbnail']); ?>" alt="" class="review-avatar">
                            <?php endif; ?>
                            <div>
                                <div class="review-name"><?php echo esc_html($company); ?></div>
                            </div>
                        </div>
                        <div class="review-text">
                            <?php echo wp_kses_post(wpautop($text)); ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php $form = get_field('форма', 2); ?>
<section class="consultation">
    <div class="container">
        <div class="row">
            <div class="col-xl-8 col-lg-7">
                <div class="title-section"><?php echo $form['заголовок']; ?></div>
                <div class="title-middle"><?php echo $form['подзаголовок']; ?></div>
                <?php echo $form['описание']; ?>
            </div>
            <div class="col-xl-4 col-lg-5">
                <div class="form-page">
                    <div class="title-form">Заказать консультацию</div>
                    <div class="descr-form">Заполните форму и мы перезвоним Вам<br> в ближайшее время</div>
                    <?php echo do_shortcode('[contact-form-7 id="c900fc2" title="Заказать консультацию"]'); ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
get_footer();