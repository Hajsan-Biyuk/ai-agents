<?php
/*
Template Name: Блог
*/
?>
<?php
get_header();
?>
<section class="page-news">
  <div class="container">
    <ul class="breadcrumbs">
      <li><a href="/">Главная</a></li>
      <li><?php the_title(); ?></li>
    </ul>
    <h1 class="title-section"><?php the_title(); ?></h1>
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
    <div class="row row_news">
    <?php while ( $itemList->have_posts() ) {
        $itemList->the_post(); ?>
      <div class="col-lg-4">
        <a href="<?php the_permalink(); ?>" class="item-news">
        <?php if ( has_post_thumbnail() ){ ?>
          <span class="item-news__image">
            <img src="<?php the_post_thumbnail_url(); ?>" alt="alt">
          </span>
        <?php } ?>
          <span class="item-news__date"><?php the_time('d F Y'); ?></span>
          <span class="item-news__title"><?php the_title(); ?></span>
          <span class="item-news__text"><?php the_excerpt(); ?></span>
        </a>
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
                  'prev_text'    => __('<i class="far fa-long-arrow-left"></i>'),
                  'next_text'    => __('<i class="far fa-long-arrow-right"></i>'),
              ) );
              ?>					
				</div>
        <?php } ?>
      <?php }  wp_reset_postdata(); ?>
  </div>
</section>
<?php
get_footer();
