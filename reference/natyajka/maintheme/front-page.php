<?php
get_header();
$billbord = get_field('баннер');
?>
<section class="billbord" <?php if($billbord['изображение_баннера']){ ?>style="    background-image: url(<?php echo $billbord['изображение_баннера']['url']; ?>);
    background-repeat: no-repeat;
    background-position: center;
    background-size: cover;"<?php } ?>>
    <div class="container">
        <h1 class="title-big"><?php echo $billbord['заголовок']; ?></h1>
        <div class="descr-big"><?php echo $billbord['подзаголовок']; ?></div>
        <div class="billbord__bottom">
            <div class="row align-items-center">
                <div class="col-lg-4">
                    <a href="/portfolios/vse/" class="btn-main btn-main_transparent">Смотреть портфолио</a>
                </div>
                <?php if($billbord['блоки']){
                    foreach ($billbord['блоки'] as $v_bl) { ?>
                    <div class="col-lg-4 col-md-6">
                        <a href="<?php echo $v_bl['ссылка']; ?>" class="item-billbord"><?php echo $v_bl['заголовок']; ?></a>
                    </div>
                <?php } 
                } ?>                
            </div>
        </div>
    </div>
</section>
<?php if(get_field('преимущества')){ ?>
<section class="advantages">
    <div class="container">
        <div class="row">
            <?php foreach (get_field('преимущества') as $v_adv) { ?>
                <div class="col-lg-4">
                    <div class="item-advantage">
                        <?php if($v_adv['иконка']) { ?>
                            <div class="item-advantage__icon">
                                <img src="<?php echo $v_adv['иконка']['sizes']['thumbnail']; ?>" alt="alt">
                            </div>
                        <?php } ?>
                        <div class="title-small"><?php echo $v_adv['заголовок']; ?></div>
                        <p><?php echo $v_adv['текст']; ?></p>
                    </div>
                </div>
            <?php } ?>            
        </div>
    </div>
</section>
<?php } ?>

<section class="services">
    <div class="container">
        <div class="head-section">
            <h2 class="title-section">Наши услуги</h2>
            <a href="/uslugi/" class="btn-simple">Все услуги</a>
        </div>
        <?php
    $paged = get_query_var('paged') ?: 1;
    $post_serv = array(
        'post_type' => 'service',
        'post_status' => 'publish',       
        'paged' => $paged,
        'posts_per_page' => -1,
        'orderby' => array(
                    'sort' => 'ASC',
                    'date' => 'DESC',
                  ),
        );
        $itemServ = new WP_Query($post_serv);
        if ( $itemServ->have_posts() ) { ?> 
        <div class="row row_services">
        <?php while ( $itemServ->have_posts() ) {                  
            $itemServ->the_post(); ?>
            <div class="col-lg-4">
                <div class="item-service">
                    <div class="title-small"><?php the_title(); ?></div>
                    <?php the_content(); ?>
                    <a href="<?php echo get_the_permalink();?>" class="item-service__link"></a>
                </div>
            </div>
            <?php if(has_post_thumbnail()) { ?>
                <div class="col-lg-4">
                    <div class="image-service">
                        <img src="<?php the_post_thumbnail_url(); ?>" alt="alt">
                    </div>
                </div>
            <?php } ?>
            <?php } ?>           
        </div>
        <?php }	wp_reset_postdata(); // сброс ?>
    </div>
</section>

<section class="portfolio">
    <div class="container">
        <div class="head-section">
            <h2 class="title-section">Наши проекты</h2>
            <a href="/portfolios/zhilye-kompleksy/" class="btn-simple">Все проекты</a>
        </div>
        <?php			               
        $paged = get_query_var('paged') ?: 1; 

        $posts = array(                  
            'meta_query' => [
                [
                    'key' => 'показать_на_главной',                           
                    'value' => 1
                ],
            ], 
            'orderby'     => 'date',
            'order'       => 'DESC',
            'post_type'   => 'portfolio',
            'posts_per_page' => -1,
            'paged' => $paged,
        //'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
        );
        $wp_query = new WP_Query($posts);
        if ( $wp_query->have_posts() ) { ?>
        <div class="row row_portfolio">
        <?php while ( $wp_query->have_posts() ) {                  
            $wp_query->the_post(); ?>
            <div class="col-lg-6">
                <div class="item-portfolio">
                    <a href="<?php echo get_the_permalink();?>" class="item-portfolio__image">
                        <?php if(has_post_thumbnail()) { ?>
                            <img src="<?php the_post_thumbnail_url(); ?>" alt="alt">
                        <?php } elseif(get_field('галерея_изображений')) { ?>
                            <img src="<?php echo get_field('галерея_изображений')['0']['sizes']['large']; ?>" alt="alt">
                        <?php } ?>
                    </a>
                    <div class="item-portfolio__content">
                        <a href="<?php echo get_the_permalink();?>" class="title-small"><?php the_title(); ?></a>
                        <?php if(get_field('характеристики')) { ?>
                            <div class="features-wrapper">
                            <?php foreach (get_field('характеристики') as $k_char => $v_char) { 
                                if($k_char < 3){ ?>
                                <div class="item-feature"><strong><?php echo $v_char['заголовок']; ?></strong> <div class="text-wrapper"><p><?php echo $v_char['значение']; ?></p></div></div>
                            <?php }
                            } ?>                              
                            </div>
                        <?php } ?>
                        <div class="item-portfolio__text">
                            <?php the_excerpt(); ?>
                        </div>
                        <a href="<?php echo get_the_permalink();?>" class="btn-icon"></a>
                    </div>
                </div>
            </div>
        <?php } ?>           
        </div>
        <?php }	wp_reset_postdata(); // сброс ?>
    </div>
</section>

<section class="news">
    <div class="container">
        <div class="head-section">
            <h2 class="title-section">новости</h2>
        </div>
        <?php
    $paged = get_query_var('paged') ?: 1;
    $post_arg = array(
        'post_type' => 'post',
        'post_status' => 'publish',       
        'paged' => $paged,
        'posts_per_page' => 3,
        'orderby' => array(
                    'sort' => 'ASC',
                    'date' => 'DESC',
                  ),
        );
        $itemList = new WP_Query($post_arg);
        if ( $itemList->have_posts() ) { ?> 
        <div class="row row_news">
            <?php while ( $itemList->have_posts() ) {
            $itemList->the_post(); ?>
                <div class="col-lg-4">
                    <a href="<?php the_permalink(); ?>" class="item-news">
                    <?php if ( has_post_thumbnail() ){ ?>   
                    <span class="item-news__image">   
                        <img src="<?php the_post_thumbnail_url(); ?>" alt="" data-object-fit-cover> 
                    </span>                                    
                    <?php  } ?>	 
                        <span class="item-news__date"><?php the_time('d F Y'); ?></span>
                        <span class="item-news__title"><?php the_title(); ?></span>
                        <span class="item-news__text"><?php the_excerpt(); ?></span>
                    </a>
                </div>  
            <?php } ?>          
        </div>
        <?php }  wp_reset_postdata(); ?>
    </div>
</section>


<section class="portfolio">
    <div class="container">
        <div class="head-section">
            <h2 class="title-section">Примеры наших работ</h2>          
        </div>      
        <?php $paged = get_query_var('paged') ?: 1; 

            $posts_portf = array(
                'orderby'     => 'date',
                'order'       => 'DESC',
                'post_type'   => 'portfolio',
                'posts_per_page' => -1,
                'paged' => $paged,
            //'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
            );
            $wp_query_portf = new WP_Query($posts_portf);
            if ( $wp_query_portf->have_posts() ) { ?>
        <div class="row row_portfolio">
            <div class="col-lg-4">   
                <div class="item-location-wrap">
                    <?php $i=1;
                    while ( $wp_query_portf->have_posts() ) {                  
                        $wp_query_portf->the_post(); ?>
                        <div class="item-location location_<?php echo $i; ?>">
                            <span><?php the_title(); ?></span>
                        </div>            
                    <?php $i++; } ?> 
                </div>
            </div>
            <div class="col-lg-8">
                <div id="map_portfolio"></div>
            </div>          
        </div>
        <?php }	wp_reset_postdata(); // сброс ?>
    </div>
</section>

<?php
get_footer();