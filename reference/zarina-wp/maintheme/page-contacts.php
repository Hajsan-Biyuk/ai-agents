<?php
/*
Template Name: Контакты
*/
get_header();
?>
<section class="page-contacts">
    <div class="container">
        <div class="row">
            <div class="col-lg-5">
                <div class="item-contact">
                    <div class="item-contact__title">Адрес:</div>
                    <div class="item-contact__value">Республика Крым, <?php echo get_field('polnyj_adres', 'option'); ?></div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="item-contact">
                    <div class="item-contact__title">Телефон:</div>
                    <div class="item-contact__value">
                        <a href="tel:<?php echo preg_replace("/[^0-9+]/", '', get_field('telefon', 'option')); ?>"><?php echo get_field('telefon', 'option'); ?></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="item-contact">
                    <div class="item-contact__title">Почта:</div>
                    <div class="item-contact__value">
                        <a href="mailto:<?php echo get_field('pochta', 'option'); ?>"><?php echo get_field('pochta', 'option'); ?></a>
                    </div>
                </div>
            </div>
        </div>
        <?php if(!empty(get_field('fajl_rekvizity', 'option'))){ ?>
            <a href="<?php echo get_field('fajl_rekvizity', 'option')['url']; ?>" class="btn-main btn-main--dark" target="_blank">Реквизиты</a>
        <?php } ?>
        <div id="map"></div>
    </div>
</section>
<?php
get_footer();