<?php
/*
Template Name: Контакты
*/
get_header();
?>
<section class="page-contacts">
    <div class="container">
        <ul class="breadcrumbs">
            <li><a href="/">Главная</a></li>
            <li>Контакты</li>
        </ul>
        <h1 class="title-section">контакты</h1>
        <div class="row">
            <div class="col-lg-3">
                <div class="item-contacts">
                    <div class="item-contacts__title">Телефон: </div>
                    <div class="line-contacts">
                        <?php if(get_field('whatsapp', 'option') || get_field('telegram', 'option') || get_field('max', 'option')) { ?>
							<ul class="social-list">
								<?php if(get_field('whatsapp', 'option')){ ?>
									<li><a href="whatsapp://send/?phone=+<?php echo preg_replace("/[^0-9]/", '', get_field('whatsapp', 'option')); ?>" target="_blank"><i class="fab fa-whatsapp"></i></a></li>
								<?php } if(get_field('telegram', 'option')) { ?>
									<li><a href="<?php echo get_field('telegram', 'option'); ?>" target="_blank"><i class="fab fa-telegram-plane"></i></a></li>
								<?php } if(get_field('max', 'option')) { ?>
									<li><a href="<?php echo get_field('max', 'option'); ?>" target="_blank"><img class="icon-soc" src="<?php bloginfo('template_directory'); ?>/img/max.svg" alt="alt"></a></li>
								<?php }  ?>
							</ul>
						<?php } ?>
                        <a href="tel:+<?php echo preg_replace("/[^0-9]/", '', get_field('телефон', 'option')); ?>" class="phone-main"><i class="fas fa-phone-alt"></i><?php echo get_field('телефон', 'option'); ?></a>
                    </div>
                    <?php if(!empty(get_field('telefony_pitomnika', 'option'))){ ?>
                    <br>
                    <div class="item-contacts__title">Телефон в питомнике: </div>
                    <?php foreach (get_field('telefony_pitomnika', 'option') as $v_t) { ?>
                       <p><a href="tel:+<?php echo preg_replace("/[^0-9]/", '', $v_t['telefon']); ?>" class="phone-main"><i class="fas fa-phone-alt"></i><?php echo $v_t['telefon']; ?></a><span><?php echo $v_t['imya']; ?></span></p>
                    <?php } ?>                   
                    <?php } ?>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="item-contacts">
                    <div class="item-contacts__title">Адрес: </div>
                    <p><?php echo get_field('адрес', 'option'); ?></p>
                </div>
                <?php if(!empty(get_field('adres_pitomnika', 'option'))){ ?>
                <div class="item-contacts">
                    <div class="item-contacts__title">Питомник: </div>
                    <p><?php echo get_field('adres_pitomnika', 'option'); ?></p>
                </div>
                <?php } ?>
            </div>
            <div class="col-lg-3">
                <div class="item-contacts">
                    <div class="item-contacts__title">Режим работы:</div>
                    <p><?php echo get_field('режим_работы', 'option'); ?></p>
                    <br>
                    <div class="item-contacts__title">E-mail:</div>
                    <p><a href="mailto:<?php echo get_field('почта', 'option'); ?>"><?php echo get_field('почта', 'option'); ?></a></p>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="item-contacts">
                    <div class="item-contacts__title">Реквизиты:</div>
                    <?php echo get_field('реквизиты', 'option'); ?>
                </div>
            </div>
        </div>
        <div id="map"></div>
    </div>
</section>
<section class="section-order">
    <div class="container">
        <div class="form-page">
            <div class="title-middle">Задать вопрос</div>
            <?php echo do_shortcode('[contact-form-7 id="74770d3" title="Задать вопрос"]'); ?>
        </div>
    </div>
</section>
<?php
get_footer();