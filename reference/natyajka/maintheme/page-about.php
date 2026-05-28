<?php
/*
Template Name: О нас
*/
get_header();
?>
<section class="page-about">
    <div class="container">
        <ul class="breadcrumbs">
            <li><a href="/">Главная</a></li>
            <li><?php the_title(); ?></li>
        </ul>
        <div class="row">
            <div class="col-lg-7">
                <?php the_content(); ?>
                <h2 class="our-goal"><?php echo get_field('заголовок_преимущества'); ?></h2>
            </div>
            <?php if(has_post_thumbnail()) { ?>
                <div class="col-lg-5">
                    <img src="<?php the_post_thumbnail_url(); ?>" alt="alt">
                </div>
            <?php } ?>
        </div>
    </div>
</section>
<?php if(get_field('преимущества', 2)){ ?>
<section class="advantages">
    <div class="container">
        <div class="row">
            <?php foreach (get_field('преимущества', 2) as $v_adv) { ?>
                <div class="col-lg-4">
                    <div class="item-advantage">
                        <?php if($v_adv['иконка']) { ?>
                            <div class="item-advantage__icon">
                                <img src="<?php echo $v_adv['иконка']['sizes']['thumbnail']; ?>" alt="alt">
                            </div>
                        <?php } ?>
                        <div class="title-small"><?php echo $v_adv['заголовок']; ?></div>
                        <p><?php echo $v_adv['текст']; ?></p>
                    </div>
                </div>
            <?php } ?>            
        </div>
    </div>
</section>
<?php } ?>
<?php $team = get_field('наша_команда');
if($team['команда']) { ?>
<section class="team">
    <div class="container">
        <h2 class="title-section"><?php echo $team['заголовок']; ?></h2>
        <div class="row row_team">
            <?php foreach ($team['команда'] as $v_team) { ?>
                <div class="col-lg-4">
                    <div class="item-team">                        
                        <div class="item-team__image">
                        <?php if($v_team['фото']) { ?>
                            <img src="<?php echo $v_team['фото']['sizes']['medium_large']; ?>" alt="alt">
                        <?php } else { ?>
                            <img src="/wp-content/themes/maintheme/img/no-image.jpeg" alt="alt">
                        <?php } ?>
                        </div>                        
                        <div class="item-team__content">
                            <div class="title-small"><?php echo $v_team['фио']; ?></div>
                            <div class="title-min"><?php echo $v_team['должность']; ?></div>
                            <?php echo $v_team['описание']; ?>
                        </div>
                    </div>
                </div>
            <?php } ?>          
        </div>
    </div>
</section>
<?php } ?>
<?php $vacancies = get_field('вакансии');
if($vacancies['блоки']) { ?>
<section class="vacancies">
    <div class="container">
        <h2 class="title-section"><?php echo $vacancies['заголовок']; ?></h2>
        <div class="row">
            <div class="col-lg-8">
                <?php foreach ($vacancies['блоки'] as $k_vac => $v_vac) { ?>
                    <div class="item-dropdown <?php if($k_vac == 0) { ?>active<?php } ?>">
                        <div class="item-dropdown__head"><?php echo $v_vac['заголовок']; ?> <i class="far fa-chevron-down"></i></div>
                        <div class="item-dropdown__content" <?php if($k_vac == 0) { ?>style="display: block;"<?php } ?>>
                            <?php echo $v_vac['описание']; ?>
                        </div>
                    </div>
                <?php } ?>               
            </div>
            <div class="col-lg-4">
                <div class="block-form">
                    <div class="title-small">отправить резюме</div>
                    <?php echo do_shortcode('[contact-form-7 id="d93be27" title="Отправить резюме"]'); ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php } ?>

<?php
get_footer();