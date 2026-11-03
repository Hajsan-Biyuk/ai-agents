<?php
add_action( 'wp_enqueue_scripts', function () {
	$uri = get_template_directory_uri();
	wp_enqueue_style( 'sabona-article', $uri . '/article.css', array(), '1.1' );
	wp_enqueue_style( 'sabona-article-media', $uri . '/article-media.css', array( 'sabona-article' ), '1.1' );
	wp_enqueue_script( 'sabona-article', $uri . '/article.js', array( 'jquery' ), '1.0', true );
}, 20 );

if ( ! function_exists( 'sabona_art_text' ) ) {
	function sabona_art_text( $name, $default, $post_id ) {
		$value = function_exists( 'get_field' ) ? get_field( $name, $post_id ) : '';
		$value = is_string( $value ) ? trim( $value ) : '';
		return $value !== '' ? $value : $default;
	}
}

if ( ! function_exists( 'sabona_art_img' ) ) {
	function sabona_art_img( $image, $size = 'medium' ) {
		if ( is_array( $image ) ) {
			if ( ! empty( $image['sizes'][ $size ] ) ) {
				return $image['sizes'][ $size ];
			}
			if ( ! empty( $image['url'] ) ) {
				return $image['url'];
			}
			if ( ! empty( $image['ID'] ) ) {
				$url = wp_get_attachment_image_url( (int) $image['ID'], $size );
				return $url ? $url : '';
			}
		}
		if ( is_numeric( $image ) ) {
			$url = wp_get_attachment_image_url( (int) $image, $size );
			return $url ? $url : '';
		}
		return '';
	}
}

if ( ! function_exists( 'sabona_art_minutes' ) ) {
	function sabona_art_minutes( $post_id ) {
		$custom = function_exists( 'get_field' ) ? get_field( 'article_read_time', $post_id ) : '';
		$custom = is_string( $custom ) ? trim( $custom ) : '';
		if ( $custom !== '' ) {
			return $custom;
		}
		$text  = wp_strip_all_tags( (string) get_post_field( 'post_content', $post_id ) );
		$words = preg_split( '/\s+/u', trim( $text ), -1, PREG_SPLIT_NO_EMPTY );
		$count = is_array( $words ) ? count( $words ) : 0;
		$min   = max( 1, (int) ceil( $count / 180 ) );
		return $min . ' мин чтения';
	}
}

if ( ! function_exists( 'sabona_art_show_form' ) ) {
	function sabona_art_show_form( $post_id ) {
		if ( ! metadata_exists( 'post', $post_id, 'article_show_form' ) ) {
			return true;
		}
		return (string) get_post_meta( $post_id, 'article_show_form', true ) === '1';
	}
}

if ( ! function_exists( 'sabona_art_heading_items' ) ) {
	function sabona_art_heading_items( $html ) {
		$items = array();
		if ( ! preg_match_all( '/<h2[^>]*>(.*?)<\/h2>/is', (string) $html, $matches ) ) {
			return $items;
		}
		$n = 0;
		foreach ( $matches[1] as $inner ) {
			$text = trim( wp_strip_all_tags( $inner ) );
			if ( $text === '' ) {
				continue;
			}
			$n++;
			$items[] = array(
				'text' => $text,
				'id'   => 'article-part-' . $n,
			);
		}
		return $items;
	}
}

if ( ! function_exists( 'sabona_art_mark_headings' ) ) {
	function sabona_art_mark_headings( $html ) {
		$n = 0;
		return preg_replace_callback(
			'/<h2(\s[^>]*)?>/i',
			function ( $match ) use ( &$n ) {
				if ( ! empty( $match[1] ) && preg_match( '/\sid\s*=/i', $match[1] ) ) {
					return $match[0];
				}
				$n++;
				$attrs = isset( $match[1] ) ? $match[1] : '';
				return '<h2 id="article-part-' . $n . '"' . $attrs . '>';
			},
			(string) $html
		);
	}
}

if ( ! function_exists( 'sabona_art_related_ids' ) ) {
	function sabona_art_related_ids( $post_id ) {
		$picked = function_exists( 'get_field' ) ? get_field( 'article_related', $post_id ) : array();
		$ids    = array();
		if ( is_array( $picked ) ) {
			foreach ( $picked as $item ) {
				if ( is_object( $item ) && isset( $item->ID ) ) {
					$ids[] = (int) $item->ID;
				} elseif ( is_numeric( $item ) ) {
					$ids[] = (int) $item;
				}
			}
		}
		$ids = array_values( array_filter( $ids ) );
		if ( $ids ) {
			return $ids;
		}
		$query = new WP_Query( array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => 3,
			'post__not_in'        => array( (int) $post_id ),
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
			'fields'              => 'ids',
		) );
		$ids = array_map( 'intval', (array) $query->posts );
		wp_reset_postdata();
		return $ids;
	}
}

if ( ! function_exists( 'sabona_art_card_text' ) ) {
	function sabona_art_card_text( $id ) {
		$announce = function_exists( 'get_field' ) ? get_field( 'анонс_статьи', $id ) : '';
		if ( is_string( $announce ) && trim( $announce ) !== '' ) {
			return wp_trim_words( wp_strip_all_tags( $announce ), 22, '…' );
		}
		$excerpt = get_the_excerpt( $id );
		if ( is_string( $excerpt ) && trim( $excerpt ) !== '' ) {
			return wp_trim_words( $excerpt, 22, '…' );
		}
		return wp_trim_words( wp_strip_all_tags( (string) get_post_field( 'post_content', $id ) ), 22, '…' );
	}
}

get_header();

$post_id = 0;

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		$post_id = (int) get_the_ID();

		$author_name = sabona_art_text( 'article_author_name', '', $post_id );
		$author_role = sabona_art_text( 'article_author_role', '', $post_id );
		$author_img  = function_exists( 'get_field' ) ? sabona_art_img( get_field( 'article_author_photo', $post_id ), 'thumbnail' ) : '';
		$read_time   = sabona_art_minutes( $post_id );
		$toc_rows    = function_exists( 'get_field' ) ? get_field( 'article_toc', $post_id ) : array();
		$toc         = array();
		if ( is_array( $toc_rows ) ) {
			foreach ( $toc_rows as $row ) {
				$item = isset( $row['item'] ) ? trim( (string) $row['item'] ) : '';
				if ( $item !== '' ) {
					$toc[] = array( 'text' => $item, 'id' => '' );
				}
			}
		}
		if ( ! $toc ) {
			$toc = sabona_art_heading_items( get_post_field( 'post_content', $post_id ) );
		}
		$faq = function_exists( 'get_field' ) ? get_field( 'article_faq', $post_id ) : array();
		if ( ! is_array( $faq ) ) {
			$faq = array();
		}
		$related_ids  = sabona_art_related_ids( $post_id );
		$related_lead = sabona_art_text( 'article_related_lead', '', $post_id );
		$next_title   = sabona_art_text( 'article_next_title', '', $post_id );
		$next_text    = function_exists( 'get_field' ) ? get_field( 'article_next_text', $post_id ) : '';
		$next_text    = is_string( $next_text ) ? trim( $next_text ) : '';
		$show_form    = sabona_art_show_form( $post_id );
		$form_id      = (int) get_option( 'sabona_article_form_id' );
		$form_side_id = (int) get_option( 'sabona_article_form_side_id' );
		$side_title   = sabona_art_text( 'article_side_title', 'Подберём упаковку под Ваше меню', $post_id );
		$side_text    = sabona_art_text( 'article_side_text', 'Соберите стартовую заявку по своему меню в полном каталоге товаров или запросите подбор ассортимента у менеджера.', $post_id );
		$form_title   = sabona_art_text( 'article_form_title', 'Соберите стартовую заявку по своему меню', $post_id );
		$form_text    = sabona_art_text( 'article_form_text', 'Соберите стартовую заявку по своему меню в полном каталоге товаров или запросите подбор ассортимента у менеджера.', $post_id );
		$cover        = get_the_post_thumbnail_url( $post_id, 'large' );
		?>
<section class="page-article">
	<div class="container">
		<ul class="breadcrumbs">
			<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Главная</a></li>
			<li><a href="<?php echo esc_url( home_url( '/novosti/' ) ); ?>">Новости</a></li>
			<li><?php the_title(); ?></li>
		</ul>
		<div class="row article-layout">
			<div class="col-lg-8 article-main">
				<h1 class="article-title"><?php the_title(); ?></h1>
				<div class="article-meta">
					<div class="article-date"><?php echo esc_html( get_the_date( 'j F Y' ) ); ?></div>
					<div class="article-meta__row">
						<?php if ( $author_name !== '' ) { ?>
							<div class="article-author">
								<?php if ( $author_img ) { ?>
									<img class="article-author__photo" src="<?php echo esc_url( $author_img ); ?>" alt="<?php echo esc_attr( $author_name ); ?>">
								<?php } ?>
								<div class="article-author__text">
									<div class="article-author__name"><?php echo esc_html( $author_name ); ?></div>
									<?php if ( $author_role !== '' ) { ?>
										<div class="article-author__role"><?php echo esc_html( $author_role ); ?></div>
									<?php } ?>
								</div>
							</div>
							<span class="article-meta__line" aria-hidden="true"></span>
						<?php } ?>
						<div class="article-read">
							<svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><circle cx="8" cy="8" r="6.25" stroke="#338A17" stroke-width="1.5"/><path d="M8 4.5V8.2L10.4 9.6" stroke="#338A17" stroke-width="1.5" stroke-linecap="round"/></svg>
							<span><?php echo esc_html( $read_time ); ?></span>
						</div>
					</div>
				</div>
				<?php if ( $cover ) { ?>
					<img class="article-cover" src="<?php echo esc_url( $cover ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>">
				<?php } ?>
				<?php if ( $toc ) { ?>
					<div class="article-toc">
						<div class="article-toc__head">
							<div class="article-toc__title">В этой статье</div>
							<ul class="article-toc__list">
								<?php foreach ( $toc as $item ) { ?>
									<li>
										<?php if ( ! empty( $item['id'] ) ) { ?>
											<a href="#<?php echo esc_attr( $item['id'] ); ?>"><?php echo esc_html( $item['text'] ); ?></a>
										<?php } else { ?>
											<?php echo esc_html( $item['text'] ); ?>
										<?php } ?>
									</li>
								<?php } ?>
							</ul>
						</div>
						<button class="article-toc__toggle" type="button" aria-expanded="true" aria-label="Свернуть содержание">
							<svg width="32" height="32" viewBox="0 0 32 32" fill="none" aria-hidden="true"><path d="M8 20L16 12L24 20" stroke="#2A2D29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
						</button>
					</div>
				<?php } ?>
				<div class="article-content">
					<?php
					add_filter( 'the_content', 'sabona_art_mark_headings', 9 );
					the_content();
					remove_filter( 'the_content', 'sabona_art_mark_headings', 9 );
					?>
				</div>
				<?php if ( $faq ) { ?>
					<div class="article-faq">
						<h2 class="article-block-title">FAQ</h2>
						<div class="article-faq__list">
							<?php foreach ( $faq as $index => $row ) {
								$question = isset( $row['question'] ) ? trim( (string) $row['question'] ) : '';
								$answer   = isset( $row['answer'] ) ? trim( (string) $row['answer'] ) : '';
								if ( $question === '' ) {
									continue;
								}
								$open = $index === 0;
								?>
								<div class="article-faq__item<?php echo $open ? ' is-open' : ''; ?>">
									<button class="article-faq__q" type="button" aria-expanded="<?php echo $open ? 'true' : 'false'; ?>">
										<span><?php echo esc_html( $question ); ?></span>
										<svg class="article-faq__arrow" width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M6 9L12 15L18 9" stroke="#2A2D29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
									</button>
									<?php if ( $answer !== '' ) { ?>
										<div class="article-faq__answer"><?php echo esc_html( $answer ); ?></div>
									<?php } ?>
								</div>
							<?php } ?>
						</div>
					</div>
				<?php } ?>
				<?php if ( $related_ids ) { ?>
					<div class="article-links">
						<h2 class="article-block-title">Статьи по теме</h2>
						<?php if ( $related_lead !== '' ) { ?>
							<p class="article-links__lead"><?php echo esc_html( $related_lead ); ?></p>
						<?php } ?>
						<div class="article-links__list">
							<?php foreach ( $related_ids as $related_id ) {
								$title = get_the_title( $related_id );
								if ( $title === '' ) {
									continue;
								}
								?>
								<a class="article-link" href="<?php echo esc_url( get_permalink( $related_id ) ); ?>">
									<span><?php echo esc_html( $title ); ?></span>
									<svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="10" stroke="#338A17" stroke-width="1.5"/><path d="M9 15L15 9M15 9H10.5M15 9V13.5" stroke="#338A17" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
								</a>
							<?php } ?>
						</div>
					</div>
				<?php } ?>
				<?php if ( $next_title !== '' || $next_text !== '' ) { ?>
					<div class="article-next">
						<?php if ( $next_title !== '' ) { ?>
							<h2 class="article-block-title"><?php echo esc_html( $next_title ); ?></h2>
						<?php } ?>
						<?php if ( $next_text !== '' ) { ?>
							<div class="article-next__text"><?php echo wp_kses_post( $next_text ); ?></div>
						<?php } ?>
					</div>
				<?php } ?>
				<?php if ( $show_form && $form_id ) { ?>
					<div class="article-form">
						<h2 class="article-form__title"><?php echo esc_html( $form_title ); ?></h2>
						<p class="article-form__lead"><?php echo esc_html( $form_text ); ?></p>
						<?php echo do_shortcode( '[contact-form-7 id="' . $form_id . '" title="Заявка со статьи"]' ); ?>
					</div>
				<?php } ?>
			</div>
			<div class="col-lg-4 article-side">
				<?php if ( $show_form && $form_side_id ) { ?>
					<div class="article-form article-form_side">
						<h2 class="article-form__title"><?php echo esc_html( $side_title ); ?></h2>
						<p class="article-form__lead"><?php echo esc_html( $side_text ); ?></p>
						<?php echo do_shortcode( '[contact-form-7 id="' . $form_side_id . '" title="Заявка со статьи, коротко"]' ); ?>
					</div>
				<?php } ?>
				<?php if ( $related_ids ) { ?>
					<h2 class="article-side__title">Статьи по теме</h2>
					<div class="article-cards">
						<?php foreach ( $related_ids as $related_id ) {
							$title = get_the_title( $related_id );
							$thumb = get_the_post_thumbnail_url( $related_id, 'medium_large' );
							$text  = sabona_art_card_text( $related_id );
							if ( $title === '' ) {
								continue;
							}
							?>
							<a class="article-card" href="<?php echo esc_url( get_permalink( $related_id ) ); ?>">
								<?php if ( $thumb ) { ?>
									<span class="article-card__photo"><img src="<?php echo esc_url( $thumb ); ?>" alt="<?php echo esc_attr( $title ); ?>"></span>
								<?php } ?>
								<span class="article-card__title"><?php echo esc_html( $title ); ?></span>
								<?php if ( $text !== '' ) { ?>
									<span class="article-card__text"><?php echo esc_html( $text ); ?></span>
								<?php } ?>
								<span class="article-card__more">
									Подробнее
									<svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="10" stroke="#7B7B7B" stroke-width="1.5"/><path d="M9 15L15 9M15 9H10.5M15 9V13.5" stroke="#7B7B7B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
								</span>
							</a>
						<?php } ?>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
</section>
		<?php
	endwhile;
endif;

$schema = $post_id ? get_post_meta( $post_id, 'микроразметка', true ) : '';
if ( empty( $schema ) && $post_id && function_exists( 'get_field' ) ) {
	$schema = get_field( 'микроразметка', $post_id );
}
if ( ! empty( $schema ) ) {
	$json = trim( (string) $schema );
	if ( preg_match( '/<script[^>]*type=["\']application\/ld\+json["\'][^>]*>(.*?)<\/script>/is', $json, $matches ) ) {
		$json = trim( $matches[1] );
	}
	echo '<script type="application/ld+json">' . $json . '</script>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

get_footer();
