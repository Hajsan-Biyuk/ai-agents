<?php
/*
Template Name:Корзина
*/
get_header();
?>
<section class="page-basket">
    <div class="container">
        <h1 class="title-section"><?php the_title(); ?></h1>
        <a href="#" onclick="history.go(-1); return false;" class="btn-main basket-btn">вернуться назад</a>
        <!-- <a href="/katalog/" class="btn basket-btn">вернуться в каталог</a> -->
        <div class="basket-wr">
            <?php echo do_shortcode('[basket-order-form]'); ?>
            <div class="order-basket-btn-wr">
                <a href="#order-basket" class="btn btn-order mt-3 fancybox">
                Оформить заказ
                </a>
            </div>
        </div>
        </div>
</section>
<?php
get_footer();