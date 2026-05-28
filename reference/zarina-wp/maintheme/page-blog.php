<?php
/*
Template Name: Блог
*/
?>
<?php
get_header();
?>
<section class="billbord-news">
    <div class="container">
        <ul class="breadcrumbs">
            <li><a href="/">Главная</a></li>
            <li> Новости</li>
        </ul>
        <div class="billbord-news__content">
            <h1 class="billbord-news__title">Новости</h1>
        </div>
    </div>
</section>

<?php
    $paged = get_query_var('paged') ?: 1;
    $post_arg = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => '9',
        'paged' => $paged,
        'orderby' => array(
                    'sort' => 'ASC',
                    'date' => 'DESC',
                  ),
        );
    $itemList = new WP_Query($post_arg);
    if ( $itemList->have_posts() ) { ?> 

<section class="page-news">
    <div class="container">
        <div class="row row--main">
            <?php while ( $itemList->have_posts() ) {
            $itemList->the_post(); ?>
                <div class="col-lg-4 col-md-6">
                    <div class="item-news">
                        <a href="<?php the_permalink(); ?>" class="item-news__image">
                            <?php if ( has_post_thumbnail() ){ ?>
                                <img src="<?php the_post_thumbnail_url(); ?>" alt="alt">
                            <?php } ?>
                        </a>
                        <div class="item-news__content">
                            <a href="<?php the_permalink(); ?>" class="item-news__title"><?php the_title(); ?></a>
                            <div class="item-news__text">
                                <?php the_excerpt(); ?>
                            </div>
                            <div class="item-news__bottom">
                                <a href="<?php the_permalink(); ?>" class="btn-main btn-main--border">Подробнее</a>
                                <div class="item-news__date"><?php the_time('d.m.Y'); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>           
        </div>
        <?php if($itemList->max_num_pages > 1) {?>
            <div class="pager">
            <?php
                $big = 999999999; // need an unlikely integer

                echo paginate_links( array(
                    'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                    'format' => '?paged=%#%',
                    'current' => max(1, get_query_var('paged') ),
                    'total' => $itemList->max_num_pages,
                    'prev_text'    => __('<i class="far fa-arrow-left"></i>'),
                    'next_text'    => __('<i class="far fa-arrow-right"></i>'),
                ) );
                ?>					
            </div>            
        <?php } ?>
    </div>
</section>
 <?php }  wp_reset_postdata(); ?>  

<?php
get_footer();