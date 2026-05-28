<?php
get_header();
?>
<section class="billbord-article" <?php if(has_post_thumbnail()){ ?>style="background-image: url(<?php the_post_thumbnail_url(); ?>);"<?php } ?>>
    <div class="container">
        <ul class="breadcrumbs">
            <li><a href="/">Главная</a></li>
            <li><a href="/novosti/">Новости</a></li>
            <li><?php the_title(); ?></li>
        </ul>
        <div class="billbord-article__content">
            <h1 class="billbord-article__title"><?php the_title(); ?></h1>
            <?php if(!empty(get_field('podzagolovok'))){ ?>
                <div class="billbord-article__text">
                    <p><?php echo get_field('podzagolovok'); ?></p>
                </div>
            <?php } ?>
        </div>
    </div>
</section>

<section class="page-article">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="article-content">
                    <h2>Климатические особенности марта в Крыму</h2>
                    <p>Погода в марте весьма переменчива, что требует понимания микроклиматических зон Крыма. Южный берег (ЮБК) традиционно предлагает более мягкие условия по сравнению с сухим, континентальным климатом степного севера. Среднесуточная температура воздуха колеблется в пределах от +5 до +12 градусов, но солнечные дни могут ощущаться значительно теплее.</p>
                    <br>
                    <p><strong>При выборе локации стоит учитывать рельеф. Горные хребты ЮБК служат естественным барьером, защищая прибрежные города от холодных северных ветров. Это делает южный берег более предпочтительным для март-отдыха.</strong></p>
                    <?php the_content(); ?>
                </div>
            </div>
            <?php $post_arg = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => '3',
            'paged' => $paged,
            'orderby' => 'rand',
            'post__not_in' => array( get_the_ID() ),
            );

            $itemList = new WP_Query($post_arg);
            if ( $itemList->have_posts() ) { ?>
            <div class="col-lg-4">
                <div class="row row--main">
                    <?php  while ( $itemList->have_posts() ) {                 
                    $itemList->the_post(); ?>
                        <div class="col-lg-12 col-md-6">
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
            </div>
            <?php }  wp_reset_postdata(); ?>
        </div>
    </div>
</section>
<?php
get_footer();