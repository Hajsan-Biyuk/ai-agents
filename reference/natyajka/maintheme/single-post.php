<?php
get_header();
?>
<?php
while ( have_posts() ) :
  the_post(); ?>
<section class="article">
	<div class="container">
		<ul class="breadcrumbs">
			<li><a href="/">Главная</a></li>
			<li><a href="/novosti/">Новости</a></li>
			<li><?php the_title(); ?></li>
		</ul>
		<div class="article-content">
			<div class="row">
				<div class="col-lg-5">
					<h1><?php the_title(); ?></h1>
					<time><?php the_time('d F Y'); ?></time>
					<?php if(get_field('цитата')) { ?>
						<blockquote>
							<p><?php echo get_field('цитата'); ?></p>
						</blockquote>
					<?php } ?>
				</div>
				<div class="col-lg-7">
					<?php if(has_post_thumbnail()) { ?>
						<img src="<?php the_post_thumbnail_url(); ?>" alt="alt">
					<?php } ?>
				</div>
			</div>
			<?php the_content(); ?>
		</div>
	</div>
</section>
<?php endwhile; // End of the loop.
          ?>
<?php
get_footer();
