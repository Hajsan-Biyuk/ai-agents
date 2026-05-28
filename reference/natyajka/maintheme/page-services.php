<?php
/*
Template Name: Услуги
*/
get_header();
?>
<section class="page-services">
    <div class="container">
        <ul class="breadcrumbs">
            <li><a href="/">Главная</a></li>
            <li>Услуги</li>
        </ul>
        <div class="head-section">
            <h2 class="title-section">Наши услуги</h2>
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
<?php
get_footer();