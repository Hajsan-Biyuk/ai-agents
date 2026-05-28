<?php
/*
Template Name: Номера и цены
*/
?>
<?php
get_header();
?>
<section class="billbord-catalog">
    <div class="container">
        <ul class="breadcrumbs">
            <li><a href="/">Главная</a></li>
            <li><?php the_title(); ?></li>
        </ul>
        <h1 class="title-section"><?php the_title(); ?></h1>
        <div class="form-booking">
            <div class="form-booking__head">
                <div class="form-booking__title">Бронирование путёвок</div>
                <div class="form-booking__official"><i class="fas fa-bookmark"></i> официальный сайт</div>
            </div>
            <form>
                <div class="form-booking__line">
                    <div class="item-form item-form--date">
                        <div class="item-form__title">Дата заезда</div>
                        <input type="text" id="date-start" placeholder="01.05.26">
                    </div>
                    <div class="item-form item-form--date">
                        <div class="item-form__title">Дата выезда</div>
                        <input type="text" id="date-end" placeholder="01.05.26">
                    </div>
                    <div class="item-form item-form--people">
                        <div class="item-form__title">Гости</div>
                        <input type="text" placeholder="2 взрослых, 1 ребенок">
                    </div>
                    <input type="submit" class="btn-main" value="Найти">
                </div>
            </form>
        </div>
    </div>
</section>
<?php
    $paged = get_query_var('paged') ?: 1;
    $post_arg = array(
        'post_type' => 'catalog',
        'post_status' => 'publish',
        'posts_per_page' => '-1',
        'paged' => $paged,
        'orderby' => array(
                    'sort' => 'ASC',
                    'date' => 'DESC',
                  ),
        );
    $itemList = new WP_Query($post_arg);
    if ( $itemList->have_posts() ) { ?> 
<section class="page-catalog">
    <div class="container">
        <?php while ( $itemList->have_posts() ) {
            $itemList->the_post(); ?>
            <div class="unit-catalog">
                <div class="row">
                    <div class="col-lg-2">
                        <div class="unit-catalog__content">
                            <div class="unit-catalog__head">
                                <h2 class="unit-catalog__title"><?php echo get_field('kategoriya_nomera'); ?></h2>
                                <div class="unit-catalog__area"><?php echo get_field('ploshhad'); ?></div>
                            </div>
                            <div class="unit-catalog__text">
                                <?php echo get_field('kratkoe_opisanie'); ?>
                            </div>
                            <?php if(!empty(get_field('bloki'))){ ?>
                                <div class="properties">
                                    <?php foreach (get_field('bloki') as $v) { ?>
                                        <div class="properties__item">
                                            <?php if(!empty($v['ikonka'])){ ?>
                                                <div class="properties__item-icon">
                                                    <img src="<?php echo $v['ikonka']['sizes']['thumbnail']; ?>" alt="alt">
                                                </div>
                                            <?php } ?>
                                            <div class="properties__item-content">
                                                <?php echo $v['tekst']; ?>
                                            </div>
                                        </div>
                                    <?php } ?>                                    
                                </div>
                            <?php } ?>
                            <div class="unit-catalog__bottom">                              
                                <?php if(!empty(get_field('stoimost'))){ ?>
                                    <div class="price-main"><?php echo get_field('stoimost'); ?></div>
                                <?php } ?>
                                <a href="#order-modal" class="btn-main fancybox" data-title="<?php the_title(); ?>">Забронировать</a>
                            </div>
                        </div>
                    </div>
                    <?php if(!empty(get_field('galereya_izobrazhenij'))){ ?>
                    <div class="col-lg-6 col-7">
                        <div class="unit-catalog__image">
                            <img src="<?php echo get_field('galereya_izobrazhenij')[0]['sizes']['large']; ?>" alt="alt">
                        </div>
                    </div>
                    <?php if(!empty(get_field('galereya_izobrazhenij')[1])){ ?>
                    <div class="col-lg-4 col-5">
                        <div class="unit-catalog__image">
                            <img src="<?php echo get_field('galereya_izobrazhenij')[1]['sizes']['large']; ?>" alt="alt">
                        </div>
                    </div>
                    <?php }
                    } ?>
                </div>
            </div>
        <?php } ?>     
    </div>
</section>
<?php }  wp_reset_postdata(); ?>  
<?php
get_footer();