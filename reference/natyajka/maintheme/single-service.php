<?php
get_header();
?>

<section class="page-service">
	<div class="container">
		<ul class="breadcrumbs">
			<li><a href="/">Главная</a></li>
			<li><a href="/uslugi/">Услуги</a></li>
			<li><?php the_title(); ?></li>
		</ul>
		<div class="row">
		<?php if(get_field('изображение_описания') || get_field('доп_описание')) { ?>
			<div class="col-lg-6">
		<?php } else { ?>
			<div class="col-lg-12">
		<?php } ?>
				<h1 class="title-section"><?php the_title(); ?></h1>
				<?php if(get_field('инфо_текст')) { ?>
					<div class="service-info">
						<p><?php echo get_field('инфо_текст'); ?></p>
					</div>
				<?php } ?>
				<?php echo get_field('описание'); ?>
				<?php if(get_field('изображение_описания')) { ?>
					<img src="<?php echo get_field('изображение_описания')['sizes']['medium_large']; ?>" alt="alt">
				<?php } ?>
			</div>
			<?php if(get_field('изображение_описания') || get_field('доп_описание')) { ?>
				<div class="col-lg-6">
					<?php if(get_field('изображение_описания')) { ?>
						<img src="<?php echo get_field('изображение_доп_описания')['sizes']['medium_large']; ?>" alt="alt">
					<?php } ?>
					<?php echo get_field('доп_описание'); ?>
				</div>
			<?php } ?>
		</div>
	</div>
</section>
<?php $portfolio = get_field('примеры_работ');
if(!empty($portfolio['блоки'])) { ?>
<section class="section-portfolio">
	<div class="container">
		<h2 class="title-section"><?php echo $portfolio['заголовок']; ?></h2>
		<ul class="slider-portfolio">
			<?php foreach ($portfolio['блоки'] as $v_portf) {
				//var_dump($v_portf); ?>
				<li>
					<div class="item-portfolio">
						<a href="<?php echo get_the_permalink($v_portf['портфолио']->ID);?>" class="item-portfolio__image">
						<?php if(has_post_thumbnail()) { ?>
							<img src="<?php echo get_the_post_thumbnail_url($v_portf['портфолио']->ID); ?>" alt="alt">
							<?php } ?>
						</a>
						<div class="item-portfolio__content">
							<a href="<?php echo get_the_permalink($v_portf['портфолио']->ID);?>" class="title-small"><?php echo get_the_title($v_portf['портфолио']->ID); ?></a>
							<div class="features-wrapper">
								<div class="item-feature"><strong>Тип работ:</strong> озеленение</div>
								<div class="item-feature"><strong>Расположение:</strong> г. Сочи</div>
								<div class="item-feature"><strong>Площадь участка:</strong> 300 м<sup>2</sup></div>
							</div>
							<div class="item-portfolio__text">
								<?php echo get_the_excerpt($v_portf['портфолио']->ID); ?>
							</div>
							<a href="<?php echo get_the_permalink($v_portf['портфолио']->ID);?>" class="btn-icon"></a>
						</div>
					</div>
				</li>
			<?php } ?>		
		</ul>
	</div>
</section>
<?php } ?>

<section class="section-order service-single" data-title="<?php the_title(); ?>">
	<div class="container">
		<div class="form-page">
			<div class="title-middle"><?php echo get_field('заголовок_формы'); ?></div>
			<?php echo do_shortcode('[contact-form-7 id="c7154ed" title="Заказать услугу"]'); ?>			
		</div>
	</div>
</section>
<?php
get_footer();