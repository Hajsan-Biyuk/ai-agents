
<?php
get_header();
?>
<section class="page-portfolio">
	<div class="container">
		<ul class="breadcrumbs">
			<li><a href="/">Главная</a></li>
			<li>Портфолио</li>
		</ul>
		<h1 class="title-section">портфолио</h1>
		<?php
			$terms = get_terms(
				array(
					'taxonomy' => 'portfolio_cat',
					'hide_empty' => false,		                     
					'parent' => 0
				)
			);
		?>
		<ul class="tabs-portfolio">
		<?php		           
			$url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];		
			
			foreach ($terms as $key => $term) { 
				$str = get_term_link($term);
				$str = preg_replace('#^https?://#', '', $str);	
			          
			$classes = '';
			if( $str == $url) {
					$classes = 'active'; } ?>
			<li class="<?php echo $classes; ?>"><a href="<?php echo get_term_link($term); ?>"><?php echo $term->name;?></a></li>
			<?php } ?>
		</ul>
		<div class="tab-container">
			<div class="tab-pane">
			<?php			               
				$paged = get_query_var('paged') ?: 1; 

              $posts = array(                  
                  'tax_query' => [
                      [
                          'taxonomy' => 'portfolio_cat',
                          'terms' => get_queried_object_id(),
                          'field' => 'id',
                          'include_children' => true // Remove if you need posts from term 7 child terms
                      ],
                  ],
                  'orderby'     => 'date',
                  'order'       => 'DESC',
                  'post_type'   => 'portfolio',
                  'posts_per_page' => 6,
                	'paged' => $paged,
                //'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
               );
			 $wp_query = new WP_Query($posts);
                if ( $wp_query->have_posts() ) { ?>
				<div class="row row_catalog">
					<?php while ( $wp_query->have_posts() ) {                  
                	$wp_query->the_post(); ?>
						<div class="col-lg-6">
							<a href="<?php echo get_the_permalink();?>" class="item-catalog">
								<?php if(has_post_thumbnail()) { ?>
									<span class="item-catalog__image">
										<img src="<?php the_post_thumbnail_url(); ?>" alt="alt">
									</span>
								<?php } elseif(get_field('галерея_изображений')) { ?>
									<span class="item-catalog__image">
										<img src="<?php echo get_field('галерея_изображений')['0']['sizes']['large']; ?>" alt="alt">
									</span>
								<?php } ?>
								<span class="item-catalog__content">
									<span class="item-catalog__title"><?php the_title(); ?></span>
								</span>
							</a>
						</div>
					<?php } ?>					
				</div>

				<?php if($wp_query->max_num_pages > 1) {?>
				    <div class="pager">
                    <?php
                        $big = 999999999; // need an unlikely integer

                        echo paginate_links( array(
                            'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                            'format' => '?paged=%#%',
                            'current' => max(1, get_query_var('paged') ),
                            'total' => $wp_query->max_num_pages,
                            'prev_text'    => __('<i class="far fa-long-arrow-left"></i>'),
                            'next_text'    => __('<i class="far fa-long-arrow-right"></i>'),
                        ) );
                        ?>					
                    </div>
                <?php } ?>  
				<?php
                }	wp_reset_postdata(); // сброс ?>
			</div>			
		</div>
	</div>
</section>

<?php
get_footer();
