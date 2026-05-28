<?php
get_header();
/*
Template Name: Спасибо
*/
?>
<div class="wrapper-page">
    <section class="thanks">       
        <div class="thanks-block">
            <div class="thanks-block__image" <?php if(has_post_thumbnail()) { ?>style="background: url(<?php the_post_thumbnail_url(); ?>) no-repeat; background-size: cover;"<?php } else { ?>style="background: url('/wp-content/uploads/2023/01/billbord1.jpg');"<?php } ?>>
                
                <div class="thanks-block__title"><?php the_title(); ?></div>
                <div class="thanks-block__descr"><?php the_content(); ?></div>
                <a href="#" onclick="history.go(-1); return false;" class="btn-main">вернуться назад</a>
            </div> 
        </div>        
    </section>
<?php
get_footer();