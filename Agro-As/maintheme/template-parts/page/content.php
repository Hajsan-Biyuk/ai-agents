<?php
/**
 * Default page — текстовая страница (политика, согласие и т.д.).
 *
 * @package maintheme
 */
?>
<section class="page-content">
	<div class="container-fluid container-main">
		<nav class="breadcrumbs" aria-label="Хлебные крошки">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Главная</a>
			<span class="breadcrumbs__sep">/</span>
			<span class="breadcrumbs__current"><?php the_title(); ?></span>
		</nav>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-content__article' ); ?>>
			<h1 class="page-content__title title-section"><?php the_title(); ?></h1>
			<div class="page-content__body">
				<?php
				the_content();

				wp_link_pages(
					array(
						'before' => '<nav class="page-content__pager" aria-label="Страницы"><span class="page-content__pager-label">Страницы:</span>',
						'after'  => '</nav>',
					)
				);
				?>
			</div>
		</article>
	</div>
</section>
