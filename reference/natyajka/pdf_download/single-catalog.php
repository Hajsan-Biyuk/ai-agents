<?php
    get_header();
    $terms = wp_get_post_terms($post->ID, 'catalog_cat');
?>

	<section class="container">
		<?php
$terms = wp_get_post_terms(get_the_ID(), 'catalog_cat');

if( !empty($terms) ):

    // Берем самую конкретную категорию (последнюю в массиве)
    $term = end($terms);

    // Формируем массив для хлебных крошек с учётом родителей
    $breadcrumbs = [];

    $parent_id = $term->parent;

    // Добавляем родителей рекурсивно
    while($parent_id != 0){
        $parent_term = get_term($parent_id, 'catalog_cat');
        if($parent_term && !is_wp_error($parent_term)){
            array_unshift($breadcrumbs, $parent_term); // вставляем в начало массива
            $parent_id = $parent_term->parent;
        } else {
            break;
        }
    }

    // Добавляем сам термин в конец
    $breadcrumbs[] = $term;
?>
<nav class="rubric__nav">
    <span><a href="/">Главная</a></span>
    <?php foreach($breadcrumbs as $crumb): ?>
        <span><a href="<?php echo get_term_link($crumb); ?>"><?php echo esc_html($crumb->name); ?></a></span>
    <?php endforeach; ?>
    <span class="active"><?php the_title(); ?></span>
</nav>
<?php endif; ?>

		<div class="row row-card">
			<div class="col-lg-5">
    <section class="gallery <?php if(get_field('продано')) { ?>single-realty__sold<?php } ?>">
        <?php
            $gallery = get_field('галерея');
            $logo_url = get_template_directory_uri() . '/img/logo.svg';
        ?>
        <div class="circle-icon__wrap">
            <div class="btn-like"><?php the_favorites_button() ?></div>
        </div>
        <div class="fotorama js-object-fotorama" data-nav="thumbs" data-thumbmargin="20" data-thumbwidth="200" data-thumbheight="140" data-thumbborderwidth="5" data-width="100%" data-ratio="800/600">
            
            <?php
                if($gallery){
                    foreach($gallery as $image){
                        ?>
                        <a href="<?=$image['sizes']['large'];?>"><img src="<?=$image['sizes']['medium']?>" alt="<?=get_the_title();?>" title="<?=get_the_title();?>"></a>
                        <?php
                    }
                } else {
                    // Если галереи нет — выводим логотип
                    ?>
                    <img src="<?php echo $logo_url; ?>" alt="<?=get_the_title();?>" title="<?=get_the_title();?>" style="width: 100%; height: auto;">
                    <?php
                }
            ?>
        </div>
        <!-- fotorama.css & fotorama.js. -->
        <link  href="http://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.css" rel="stylesheet"> <!-- 3 KB -->
        <script src="http://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.js"></script> <!-- 16 KB -->

    </section> <!-- /.gallery -->

			
			</div>
			<div class="col-lg-5">
				<article class="cart__description">
					<h1><?=get_the_title();?></h1>
					<? if(get_field('адрес')){?>
					<div>Адрес:&nbsp; <?=get_field('адрес');?></div>
					<?
    					}
					?>
					<?php if(get_field('жк')){ ?><a class="jk-link" href="<?php echo get_term_link(get_field('жк')->term_id); ?>"><?php echo get_field('жк')->name; ?></a> <?php } ?>								
					
			
					<table class="house__info">
						<tbody>
							<?
        							if(get_field('площадь')){
    							?>
								<tr>
									<td><strong>Общая</strong></td>
									<td><?=get_field('площадь');?> м<sup>2</sup></td>
								</tr>
								<?
    								}
    								if(get_field('жилая')){
								?>
								<tr>
									<td><strong>Жилая</strong></td>
									<td><?=get_field('жилая');?> м<sup>2</sup></td>
								</tr>
								<?
    								}
    								if(get_field('кухня')){
								?>
								<tr>
									<td><strong>Кухня</strong></td>
									<td><?=get_field('кухня');?> м<sup>2</sup></td>
								</tr>
								<?
    								}
    								if(get_field('этаж')){
								?>
								<tr>
									<td><strong>Этаж</strong></td>
									<td><?=get_field('этаж');?> <?=(get_field('кол-во_этажей'))? ' из '.get_field('кол-во_этажей') : '';?></td>
								</tr>
								<?
    								}
    								if(get_field('площадь_участка')){
								?>
								<tr>
									<td><strong>Площадь участка</strong></td>
									<td><?=get_field('площадь_участка');?> сот.</td>
								</tr>
								<?
    								}
    								if(get_field('вид_использования')){
								?>
								<tr>
									<td><strong>Вид раз-го исп-я</strong></td>
									<td><?=get_field('вид_использования')['label'];?></td>
								</tr>
								<?
    								}
								?>
    						<?
        						if(get_field('до_моря')){
    						?>
							<tr>
								<td><strong>До моря:</strong></td>
								<td><?=get_field('до_моря');?></td>
							</tr>
							<?
    							}
    							if(get_field('до_центра')){
							?>
							<tr>
								<td><strong>До центра:</strong></td>
								<td><?=get_field('до_центра');?></td>
							</tr>
							<?
    							}
    							if(get_field('окончание_строительства')){
							?>
							<tr>
								<td><strong>Окончание строительства:</strong></td>
								<td><?=get_field('окончание_строительства');?></td>
							</tr>
							<?
    							}
							?>
						</tbody>
					</table>

				

					 <div class="online-view-wr">
						<div class="price__box">
						<?php if(get_field('стоимость_со_скидкой')) { ?>
						<strong class="price__box_discount"><?=(get_field('стоимость'))? number_format(get_field('стоимость'), 0, ',', ' '). ' ₽': '';?><?=(get_field('стоимость_аренды'))? number_format(get_field('стоимость_аренды'), 0, ',', ' '). ' ₽ / мес': '';?></strong>
						<?
    						if(get_field('стоимость')){
						?>
						<strong><?php echo number_format(get_field('стоимость_со_скидкой'), 0, ',', ' '); ?> ₽</strong>
						<?php if(get_field('площадь')) { ?>
						<span><?php // echo get_cost_usd($post->ID); ?><?php echo number_format(round(get_field('стоимость_со_скидкой')/get_field('площадь')), 0, ',', ' '); ?> ₽/м<sup>2</sup></span>
						<? }
    						}
						?>
						<?php } else { ?>
							<?
    						if(get_field('стоимость')){
						?>
						<strong><?php echo number_format(get_field('стоимость'), 0, ',', ' '); ?> ₽</strong>
						<?php if(get_field('площадь')) { ?>
						<span><?php // echo get_cost_usd($post->ID); ?><?php echo number_format(round(get_field('стоимость')/get_field('площадь')), 0, ',', ' '); ?> ₽/м<sup>2</sup></span>
						<? }
    						}
						?>
						<?php } ?>
					</div>	
					<?php if (function_exists('wpfp_link')) { wpfp_link(); } ?>
					<?php if(get_field('возможен_онлайн-просмотр')) {?>				
						<a href="#modal-view" class="online-view modal-magnificPopup button normal black" data-title="<?=get_the_title();?>">Записаться на онлайн-просмотр</a>					
					<?php } ?>
					</div> 
					
				<div class="fv-presentation-block">
					<a href="#" class="fv-get-presentation button normal gray" product_id="<?=get_the_ID();?>">Скачать презентацию</a>
				</div>
					
					<!-- <div class="contact__form contact__form_callback">
                            <?=do_shortcode('[contact-form-7 id="1556" title="Быстрый заказ обратного звонка"]');?>
                    </div> -->
					
				</article>
			</div>
			<div class="col-lg-2">
				<?
    					if($manager = get_field('менеджер')){
    				?>
					<div class="object_manager">
    					<?
        					if(get_the_post_thumbnail($manager, 'thumbnail')){
    					?>
    					<div class="manager-img">
        					<?=get_the_post_thumbnail($manager, 'thumbnail')?>
    					</div>
    					<?
        					}
    					?>
    					<div class="manager-info">
							<div class="manager-name"><?=get_the_title($manager);?></div>
							<p>Менеджер</p>
							<a class="manager-tel" href="tel:+<?=preg_replace('/[^0-9]/','',get_field('телефон',$manager))?>"><?=get_field('телефон',$manager);?></a>
							<a href="#modal-message" class="modal-magnificPopup button normal gray">Связаться</a>
						</div>
					</div>
					<?
    					} 
					?>

			</div>
			<div class="col-lg-12">
				<section class="m-code--tabs" id="our-product--tabs">
					<div class="m-tabs-nav">
						<a href="#tech" class="m-tabs-nav__link is-active">ТЕХНИЧЕСКАЯ ИНФОРМАЦИЯ</a>
						<a href="#desc" class="m-tabs-nav__link">ОПИСАНИЕ</a>
						<?
    						if(get_field('инфраструктура')){
						?>
						<a href="#infra" class="m-tabs-nav__link">Инфраструктура</a>
						<?
    						}
						?>
						<div class="m-tabs-nav-marker"></div>
					</div> 
					
					<div class="tab-content">
						<div id="tech" class="m-tab is-active">
							<div class="m-tab__content">
								<div class="container">
									<h2 class="tab-title">
										Техническая информация о "<?=get_the_title();?>"
									</h2>
									<div class="row">
										<div class="col">
    										<?
        										if(get_field('ремонт')){
    										?>
											<div>Ремонт:</div>
											<ul>
												<li><?=get_field('ремонт');?></li>
											</ul>
											<?
    											}
        										if(get_field('парковка')){
    										?>
											<div>Парковка:</div>
											<ul>
												<li><?=get_field('парковка');?></li>
											</ul>

                                            <?
                                                }
        										if(get_field('охлаждение')){
    										?>
											<div>Охлаждение:</div>
											<ul>
												<li><?=get_field('охлаждение');?></li>
											</ul>

                                            <?
                                                }
        										if(get_field('счетчики')){
    										?>
											<div>Счетчики:</div>
											<ul>
												<li><?=get_field('счетчики');?></li>
											</ul>
                                            
                                            <?
                                                }
        										if(get_field('стены')){
    										?>
											<div>Стены:</div>
											<ul>
												<li><?=get_field('стены');?></li>
											</ul>
                                            
                                            <?
                                                }
        										if(get_field('утпеление_стен')){
    										?>
											<div>Утпеление стен:</div>
											<ul>
												<li><?=get_field('утпеление_стен')?></li>
											</ul>
											<?
    											}
											?>
										</div> <!-- /.col -->

										<div class="col">
    										<?
        										if(get_field('управление')){
    										?>
											<div>Управляющая компания:</div>
											<ul>
												<li><?=get_field('управление');?></li>
											</ul>
                                            <?
                                                }
        										if(get_field('в_доме')){
    										?>
											<div>В доме:</div>
											<ul>
												<li><?=get_field('в_доме');?></li>
											</ul>
											<?
                                                }
    										?>
										</div> <!-- /.col -->

										<div class="col">
    										<?
        										if(get_field('вид_из_окна')){
    										?>
											<div>Вид из окна:</div>
											<ul>
												<li><?=get_field('вид_из_окна');?></li>
											</ul>
                                            <?
                                                }
        										if(get_field('конструктив')){
    										?>
											<div>Конструктив:</div>
											<ul>
												<li><?=get_field('конструктив');?></li>
											</ul>
											<?
    											}
											?>
										</div>
									</div> <!-- /.row -->
								</div> <!-- /.container -->
							</div> <!-- /.m-tab__content -->
						</div> <!-- /.m-tab --> 

						<div id="desc" class="m-tab">
							<div class="m-tab__content">
																	<h2 class="tab-title">
										Описание объекта "<?=get_the_title();?>"
									</h2>
                            <?php while ( have_posts() ) : the_post(); ?>							
                            <?php the_content(); ?>
                            <?php endwhile; // end of the loop. ?>
							</div> <!-- m-tab__content -->
						</div> <!-- m-tab -->
						<?php /* if(get_field('координаты_объекта_на_карте')) { ?>
							<div id="singel-page__map" class="singel-page__map"  data-coordinates="<?php echo get_field('координаты_объекта_на_карте'); ?>"></div>
						<?php } */ ?>
                        
                        <?
                            if(get_field('инфраструктура')){
                        ?>
						<div id="desc" class="m-tab">
							<div class="m-tab__content">
								<?=get_field('инфраструктура')?>
							</div> <!-- m-tab__content -->
						</div> <!-- m-tab -->
						<?
    						}
						?>
					</div>
					<!-- /.container -->
					
				</section> <!-- /.m-code--tabs -->
			</div>
		</div> <!-- /.row -->

			
		
		<?php /*
		$jk = get_field("жк")->term_id;
		//var_dump($jk);
		if($jk){
		$args = array(
			'post_type' => 'realty',
			'posts_per_page' => 6,			
			 'orderby'   => 'rand',	
			'meta_query' => array(
				'relation' => 'OR',			
			array(
				'key' => 'жк',
				'value' => $jk,
			)	
		),
			// 'tax_query' => array(
			// 	array(
			// 		'taxonomy' => 'realty-category',
			// 		'field'    => 'id',
			// 		'terms'    => get_queried_object()->term_id
			// 	)
			// ),
		);
		$query = new WP_Query($args);
		?>
	<?php if ($query->have_posts()){ ?>
		<div class="also-sells-wrap">
			<p class="title">В этом комплексе также продаются</p>
			<div class="also-sells">
			
    		<?php while ($query->have_posts()) : $query->the_post(); ?>
				<a href="<?=get_the_permalink();?>" class="also-sells__card-item">
					<img src="<?php echo get_the_post_thumbnail_url(); ?>" alt="">
					<div class="also-sells__text-box">
						<p class="text-box__desc"><?php the_title(); ?></p>
						<strong class="text-box__price"><?=(get_field('стоимость'))? number_format(get_field('стоимость'), 0, ',', ' '). ' ₽': '';?><?=(get_field('стоимость_аренды'))? number_format(get_field('стоимость_аренды'), 0, ',', ' '). ' ₽ / мес': '';?></strong>
						<p class="text-box__address"><?=( $complex = get_field('жк') )? $complex->name.',<br> ' : '';?>
							<?
        						   		if(get_field('этаж')){
    						   		?>
                                    <br><?=get_field('этаж');?> <?=(get_field('кол-во_этажей'))? ' этаж из '.get_field('кол-во_этажей').' ,': '';?>
                                    
                                    <?
                                        }
                                    ?> <?=get_field('адрес')?></p>
					</div>
				</a>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
		</div>
		<?php } } */ ?>

	</section>
<?php
// Получаем все термины текущего поста
$terms = wp_get_post_terms(get_the_ID(), 'catalog_cat');

if( !empty($terms) ):
    $term = end($terms);

    // Получаем все посты в этой категории, кроме текущего
    $all_similar = get_posts(array(
        'post_type'      => get_post_type(get_the_ID()),
        'posts_per_page' => -1, // берем все, чтобы потом выбрать случайные
        'post__not_in'   => array(get_the_ID()),
        'orderby'        => 'rand',
        'tax_query'      => array(
            array(
                'taxonomy' => 'catalog_cat',
                'field'    => 'term_id',
                'terms'    => $term->term_id,
            )
        )
    ));

    if( $all_similar ):
        // Ограничиваем до 10 случайных объектов
        if( count($all_similar) > 10 ) {
            $all_similar = array_slice($all_similar, 0, 10);
        }
?>
<section class="container similar-objects">
    <div class="title_alternative mb-4">Похожие объекты</div>
    
    <div class="item__cart-wrap slider-similar position-relative">
        <?php foreach($all_similar as $post): setup_postdata($post); ?>
        <div class="item__cart col-lg-4">
            <div>
                <figure>
                    <div class="item_card_main">
                        <div class="circle-icon__wrap">
                            <div class="btn-like">
                                <a class="simplefavorite-button" data-postid="<?php the_ID(); ?>" data-siteid="1" data-groupid="1" data-favoritecount="0">
                                    <i class="icon-heart"></i>
                                </a>
                            </div>
                        </div>
                        <a href="<?php the_permalink(); ?>">
                            <?php if(has_post_thumbnail()): ?>
                                <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(),'medium_large'); ?>" alt="<?php the_title(); ?>">
                            <?php else: ?>
                                <img src="<?php echo get_template_directory_uri(); ?>/img/logo.svg" alt="<?php the_title(); ?>">
                            <?php endif; ?>
                        </a>
                    </div>
                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                        <div class="figcaption_div"><?php the_title(); ?></div>
                    </a>
                </figure>
                <article>
                    <p>
                        <?php if(get_field('адрес')) echo get_field('адрес'); ?>
                        <?php if(get_field('этаж')) echo '<br>'.get_field('этаж').' этаж'.(get_field('кол-во_этажей') ? ' из '.get_field('кол-во_этажей') : ''); ?>
                    </p>
                    <div class="row between-flex">
                        <strong><?php if(get_field('стоимость')) echo number_format(get_field('стоимость'),0,' ',' '); ?> ₽</strong>
                        <a href="<?php the_permalink(); ?>" class="button normal black">Узнать подробнее</a>
                    </div>
                </article>
            </div>
        </div>
        <?php endforeach; wp_reset_postdata(); ?>
    </div>
</section>

<!-- Slick -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>

<script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<?php
    endif;
endif;
?>
<script>
jQuery(window).on('load', function() {
    // Название объекта из WP
    var objectTitle = <?php echo json_encode( get_the_title() ); ?>;

    function applyAltTitle() {
        var $ = jQuery;

        // Все картинки, которые рисует Fotorama (основные и превью)
        jQuery('.js-object-fotorama').find('img.fotorama__img').attr({
            alt: objectTitle,
            title: objectTitle
        });
    }

    // 1) Один раз после полной загрузки страницы (к этому моменту Fotorama уже инициализирована)
    applyAltTitle();

    // 2) На всякий случай — немного позже, если Fotorama что-то перерисует
    setTimeout(applyAltTitle, 500);

    // 3) И ещё раз после переключения слайдов (стрелки/превью)
    jQuery(document).on('click', '.js-object-fotorama .fotorama__nav__frame, .js-object-fotorama .fotorama__arr', function () {
        setTimeout(applyAltTitle, 50);
    });
});
</script>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "RealEstateListing",
    "name": <?= json_encode(get_the_title()) ?>,
    "url": <?= json_encode(get_permalink()) ?>,
    "datePosted": "<?= get_the_date('c') ?>",
    <?php if ($gallery = get_field('галерея')): 
        $images = [];
        foreach ($gallery as $img) {
            $url = $img['sizes']['large'] ?? $img['url'] ?? '';
            if ($url) $images[] = json_encode($url);
        }
        if (!empty($images)):
    ?>
    "image": [<?= implode(", ", $images) ?>],
    <?php endif; ?>
    <?php elseif (!empty($logo_url)): ?>
    "image": <?= json_encode($logo_url) ?>,
    <?php endif; ?>

    "description": <?= json_encode(trim(
        (get_the_excerpt() ?: wp_strip_all_tags(get_the_content())) .
        (get_field('до_моря') ? "\nДо моря: " . get_field('до_моря') : '') .
        (get_field('вид_из_окна') ? "\nВид из окна: " . get_field('вид_из_окна') : '')
    )) ?>,

    <?php if ($addr = get_field('адрес')): ?>
    "address": {
        "@type": "PostalAddress",
        "streetAddress": <?= json_encode($addr) ?>,
        "addressLocality": <?= json_encode($terms[0]->name ?? 'Алушта') ?>,
        "addressCountry": "RU"
    },
    <?php endif; ?>

    "offers": {
        "@type": "Offer",
        "priceCurrency": "RUB",
        <?php
        $price = get_field('стоимость_со_скидкой') ?: get_field('стоимость');
        $rent  = get_field('стоимость_аренды');
        if ($price):
        ?>
        "price": "<?= number_format((float)$price, 0, '', '') ?>",
        <?php endif; ?>
        <?php if ($rent): ?>
        <?php if ($price) echo ','; ?>
        "priceSpecification": {
            "@type": "UnitPriceSpecification",
            "price": "<?= number_format((float)$rent, 0, '', '') ?>",
            "priceCurrency": "RUB",
            "unitText": "MONTH"
        },
        <?php endif; ?>
        "availability": "https://schema.org/InStock",
        "url": <?= json_encode(get_permalink()) ?>,
        "seller": {
            "@type": "RealEstateAgent",
            "name": <?= json_encode(get_bloginfo('name')) ?>
            <?php if ($manager = get_field('менеджер')): 
                $tel = preg_replace('/[^0-9+]/', '', get_field('телефон', $manager));
                if ($tel):
            ?>,
            "contactPoint": {
                "@type": "ContactPoint",
                "contactType": "sales",
                "name": <?= json_encode(get_the_title($manager)) ?>,
                "telephone": <?= json_encode('+' . $tel) ?>
            }
            <?php endif; endif; ?>
        }
    },

    "about": {
        "@type": <?= !empty(get_field('площадь_участка')) ? '"House"' : '"Apartment"' ?>,
        "name": <?= json_encode(get_the_title()) ?>
        <?php
        $props = [];

        if ($area = get_field('площадь')) {
            $val = (float) str_replace(',', '.', $area);
            $props[] = '"floorSize": {"@type": "QuantitativeValue", "value": ' . $val . ', "unitCode": "MTK", "unitText": "м²"}';
        }

        $land = get_field('площадь_участка');
        if ($land) {
            $val = (float) preg_replace('/[^0-9.]/', '', str_replace(',', '.', $land));
            $props[] = '"additionalProperty": {"@type": "PropertyValue", "name": "Площадь участка", "value": ' . $val . ', "unitText": "сот"}';
        }

        if ($living = get_field('жилая')) {
            $val = (float) str_replace(',', '.', $living);
            $props[] = '"livingArea": ' . $val;
        }

        if ($kitchen = get_field('кухня')) {
            $val = (float) str_replace(',', '.', $kitchen);
            $props[] = '"kitchenArea": ' . $val;
        }

        if ($floor = get_field('этаж')) {
            $total = get_field('кол-во_этажей');
            $floor_str = $floor . ($total ? ' из ' . $total : '');
            $props[] = '"floorLevel": ' . json_encode($floor_str);
        }

        if ($repair = get_field('ремонт')) {
            $props[] = '"amenityFeature": {"@type": "LocationFeatureSpecification", "name": "Ремонт", "value": ' . json_encode($repair) . '}';
        }

        if (!empty($props)) {
            echo ",\n        " . implode(",\n        ", $props);
        }
        ?>
    }
}
</script>
<?php get_footer(); ?>