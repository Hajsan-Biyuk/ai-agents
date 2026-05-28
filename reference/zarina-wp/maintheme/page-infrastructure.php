<?php
/*
Template Name: Инфраструктура
*/
?>
<?php
get_header();
?>
<section class="billbord-infrastructure" <?php if(!empty(get_field('izobrazhenie_bannera'))){ ?>style="background-image: url(<?php echo get_field('izobrazhenie_bannera')['url']; ?>);"<?php } ?>>
    <div class="container">
        <ul class="breadcrumbs">
            <li><a href="/">Главная</a></li>
            <li><?php the_title(); ?></li>
        </ul>
        <div class="billbord-infrastructure__content" >
            <h1 class="billbord-infrastructure__title"><?php echo get_field('zagolovok_bannera'); ?></h1>
        </div>
    </div>
</section>

<section class="infrastructure">
    <div class="container">
        <?php if(!empty(get_field('bloki'))){ ?>
            <div class="row row--main">
                <?php foreach (get_field('bloki') as $v_infr) { ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="infrastructure__item">
                            <div class="infrastructure__image">
                                <?php if(!empty($v_infr['izobrazhenie'])){ ?>
                                    <img src="<?php echo $v_infr['izobrazhenie']['sizes']['large']; ?>" alt="alt">
                                <?php } ?>
                            </div>
                            <h2><?php echo $v_infr['zagolovok']; ?></h2>
                            <?php echo $v_infr['opisanie']; ?>
                        </div>
                    </div>
                <?php } ?>               
            </div>
        <?php } ?>
        <?php the_content(); ?>
    </div>
</section>
<?php
get_footer();