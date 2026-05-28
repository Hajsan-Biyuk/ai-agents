<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package maintheme
 */

get_header();
?>
<div class="wrapper-page">
	<div id="primary" class="content-area not-front article">
		<main id="main" class="site-main container">

			<section class="error-404 not-found">
				<header class="page-header">
					<h1 class="page-title">Ошибка 404!</h1>
				</header><!-- .page-header -->

				<div class="page-content">

				<a href="/">Вернуться на главную страницу</a>
				</div><!-- .page-content -->
			</section><!-- .error-404 -->

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
