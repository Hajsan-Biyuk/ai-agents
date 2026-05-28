<?php
get_header();
?>
<?php $banner = get_field('banner'); ?>
<section class="billbord" <?php if(!empty($banner['izobrazhenie_bannera'])){ ?>style="background-image: url(<?php echo $banner['izobrazhenie_bannera']['url']; ?>);"<?php } ?>>
    <div class="container-fluid">
        <div class="billbord__content">
            <h1 class="billbord__title"><?php echo $banner['zagolovok']; ?></h1>
            <h2 class="billbord__descr"><?php echo $banner['podzagolovok']; ?></h2>
        </div>
    </div>
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
        <div class="scroll-down"><i class="far fa-arrow-down"></i></div>
    </div>
</section>
<?php $katalog = get_field('katalog'); ?>
<section class="catalog">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <h2 class="title-section"><?php echo $katalog['zagolovok']; ?></h2>
                <div class="descr-section"><?php echo $katalog['podzagolovok']; ?></div>
                <a href="/nomera-i-tseny/" class="btn-main btn-main--dark">Смотреть все номера</a>
            </div>
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
            <div class="col-lg-8">
                <div class="slider-catalog">
                    <?php while ( $itemList->have_posts() ) {
                    $itemList->the_post(); ?>
                    <div>
                        <div class="item-catalog">
                            <a href="<?php the_permalink(); ?>" class="item-catalog__image">
                                <?php if(!empty(get_field('galereya_izobrazhenij'))){ ?>
                                <img src="<?php echo get_field('galereya_izobrazhenij')[0]['sizes']['large']; ?>" alt="<?php the_title(); ?>">
                                <?php } ?>
                            </a>
                            <div class="item-catalog__content">
                                <a href="<?php the_permalink(); ?>" class="item-catalog__title"><?php the_title(); ?></a>
                                <div class="item-catalog__text">
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
                                <div class="item-catalog__prices">
                                    <?php if(!empty(get_field('stoimost'))){ ?>
                                        <p>Цена:</p>                                    
                                        <div class="price-main"><?php echo get_field('stoimost'); ?></div>
                                    <?php } ?>
                                </div>
                                <div class="item-catalog__buttons">
                                    <a href="<?php the_permalink(); ?>" class="btn-main btn-main--border">Подробнее</a>
                                    <a href="#order-modal" class="btn-main fancybox" data-title="<?php the_title(); ?>">Забронировать</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>                  
                </div>
            </div>
            <?php }  wp_reset_postdata(); ?>  
        </div>
    </div>
</section>
<?php $uslugi = get_field('uslugi');
if(!empty($uslugi['bloki'])){ ?>
<section class="services">
    <div class="container">
        <div class="row row--main">
            <div class="col-lg-4">
                <h2 class="title-section"><?php echo $uslugi['zagolovok']; ?></h2>
                <div class="descr-section"><?php echo $uslugi['podzagolovok']; ?></div>
            </div>
            <?php foreach ($uslugi['bloki'] as $k_serv => $v_serv) {
                $class = 'col-lg-4';
                if($k_serv == 2 || $k_serv == 3 || $k_serv == 4 || $k_serv == 5){
                    $class = 'col-lg-3';
                } ?>
                <div class="<?php echo $class; ?>">
                    <div class="item-service">
                        <div class="item-service__image">
                            <?php if(!empty($v_serv['izobrazhenie'])){ ?>
                                <img src="<?php echo $v_serv['izobrazhenie']['sizes']['large']; ?>" alt="alt">
                            <?php } ?>
                        </div>
                        <div class="item-service__content">
                            <div class="item-service__title"><?php echo $v_serv['zagolovok']; ?></div>
                            <div class="item-service__text">
                                <?php echo $v_serv['opisanie']; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>      
        </div>
    </div>
</section>
<?php } ?>
<?php $about = get_field('ob_otele');
if(!empty($about['slajder'])){ ?>
<section class="about">
    <div class="container">
        <div class="row">
            <div class="col-lg-5">
                <div class="about__content">
                    <h2 class="title-section"><?php echo $about['zagolovok']; ?></h2>
                    <div class="descr-section"><?php echo $about['podzagolovok']; ?></div>
                </div>
                <div class="about__bottom">
                    <a href="<?php echo $about['ssylka']; ?>" class="btn-main btn-main--dark">Об отеле</a>
                    <div class="slider-count-about"></div>
                    <div class="slider-controls-about"></div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="slider-about">
                    <?php foreach ($about['slajder'] as $v_sl) { ?>
                        <div>
                            <div class="item-about">
                                <div class="item-about__top">
                                    <div class="item-about__image">
                                        <?php if(!empty($v_sl['izobrazhenie'])){ ?>
                                            <img src="<?php echo $v_sl['izobrazhenie']['sizes']['large']; ?>" alt="alt">
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="item-about__content">
                                    <div class="item-about__title"><?php echo $v_sl['zagolovok']; ?></div>
                                    <?php echo $v_sl['opisanie']; ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>                   
                </div>
            </div>
        </div>
    </div>
</section>
<?php } ?>

<?php 
    include('template-parts/sect_form.php');
?>
<?php
get_footer();