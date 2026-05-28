<?php
get_header();
?>
<section class="portfolio-card">
	<div class="container">
		<ul class="breadcrumbs">
			<li><a href="/">Главная</a></li>
			<li><a href="/portfolios/zhilye-kompleksy/">Портфолио</a></li>
			<li><?php the_title(); ?></li>
		</ul>
		<h1 class="title-section"><?php the_title(); ?></h1>
		<div class="row">
			<div class="col-lg-6">
				<?php if(get_field('галерея_изображений')) { ?>
					<ul class="slider-for">
						<?php foreach (get_field('галерея_изображений') as $v_gal) { ?>
							<li>
								<a href="<?php echo $v_gal['sizes']['large']; ?>" class="slider-for__item fancybox" rel="group1">
									<img src="<?php echo $v_gal['sizes']['large']; ?>" alt="alt">
								</a>
							</li>
						<?php } ?>						
					</ul>
					<ul class="slider-nav">
						<?php foreach (get_field('галерея_изображений') as $v_nav) { ?>
							<li>
								<div class="slider-nav__item">
									<img src="<?php echo $v_nav['sizes']['medium']; ?>" alt="alt">
								</div>
							</li>
						<?php } ?>						
					</ul>
				<?php } ?>
			</div>
			<div class="col-lg-6">
				<?php if(get_field('характеристики')) { ?>
					<div class="features-card">
						<?php foreach (get_field('характеристики') as $v_char) { ?>
							<p><strong><?php echo $v_char['заголовок']; ?></strong> <?php echo $v_char['значение']; ?></p>
						<?php } ?>	
						<a href="https://www.youtube.com/embed/E8d_JvMpoY4" class="btn-video fancybox fancybox.iframe"><i class="fas fa-play"></i>Смотреть видео о проекте</a>
						<?php echo get_field('короткое_описание'); ?>					
					</div>
				<?php } ?>
				
			</div>
		</div>
		<?php the_content(); ?>
		<?php if(get_field('фото_альбомы')) { 
		foreach (get_field('фото_альбомы') as $key => $value) { ?>
			<h2 class="title-section title-section_photos"><?php echo $value['заголовок']; ?></h2>
			<?php if($value['галерея']) { ?>
				<div class="row row_photos">
					<?php foreach ($value['галерея'] as $v_photo) { ?>
						<div class="col-lg-4">
							<a href="<?php echo $v_photo['sizes']['large']; ?>" class="item-photo fancybox" rel="photos<?php echo $key+1; ?>">
								<img src="<?php echo $v_photo['sizes']['large']; ?>" alt="alt">
							</a>
						</div>		
					<?php } ?>		
				</div>
			<?php } ?>
		<?php }
		} ?>		
	</div>
</section>

<?php
get_footer();