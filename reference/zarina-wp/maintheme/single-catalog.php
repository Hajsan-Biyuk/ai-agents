<?php
get_header();
?>
<section class="billbord-card" <?php if(!empty(get_field('izobrazhenie_bannera'))){ ?>style="background-image: url(<?php echo get_field('izobrazhenie_bannera')['url']; ?>);"<?php } ?>>
    <div class="container">
        <ul class="breadcrumbs">
            <li><a href="/">Главная</a></li>
            <li><a href="/nomera-i-tseny/">Номера</a></li>
            <li><?php the_title(); ?></li>
        </ul>
        <div class="billbord-card__content">
            <h1 class="billbord-card__title"><?php echo get_field('zagolovok'); ?></h1>
            <div class="billbord-card__text">
                <?php echo get_field('kratkoe_opisanie'); ?>
            </div>
        </div>
    </div>
</section>

<section class="page-card">
    <div class="container">
        <div class="form-booking form-booking--page">
            <form>
                <div class="form-booking__line">
                    <div class="form-booking__title">Бронирование путёвок</div>
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
        <div class="row">
            <div class="col-lg-6">
                <?php if(!empty(get_field('galereya_izobrazhenij'))){ ?>
                    <div class="slider-wrapper">
                        <div class="slider-card">
                            <?php foreach (get_field('galereya_izobrazhenij') as $v_img) { ?>
                                <div>
                                    <a href="<?php echo $v_img['sizes']['large']; ?>" class="slider-card__image fancybox" data-fancybox="card">
                                        <img src="<?php echo $v_img['sizes']['large']; ?>" alt="alt">
                                    </a>
                                </div>
                            <?php } ?>                      
                        </div>
                        <div class="slider-controls">
                            <div class="slider-controls__counts">
                                <div class="slider-controls__number"></div>
                                /
                                <div class="slider-controls__total"></div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <div class="card-about">
                    <?php the_content(); ?>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card-info">
                    <div class="card-info__head">
                        <div class="card-info__title">О номере</div>
                        <div class="card-info__warning">
                            <img src="<?php bloginfo('template_directory'); ?>/img/dontsmoke.svg" alt="alt">
                            Номер для некурящих!
                        </div>
                    </div>
                    <?php if(!empty(get_field('harakteristiki'))){ ?>
                        <div class="card-info__features">
                            <?php foreach (get_field('harakteristiki') as $v_char) { ?>
                                <div class="feature">
                                    <div class="feature__title"><?php echo $v_char['nazvanie']; ?></div>
                                    <div class="feature__value"><?php echo $v_char['znachenie']; ?></div>
                                </div>
                            <?php } ?>                           
                        </div>
                    <?php } ?>
                    <div class="card-info__bottom">
                        <?php if(!empty(get_field('stoimost'))){ ?>
                            <div class="price-main"><?php echo get_field('stoimost'); ?></div>
                        <?php } ?>
                        <a href="#order-modal" class="btn-main fancybox" data-title="<?php the_title(); ?>">Забронировать</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
get_footer();