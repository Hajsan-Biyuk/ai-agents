<?php /*
Template Name: Избранное
*/
get_header();
?>
<?php $favorite_ids = get_user_favorites();
if($favorite_ids) { ?>
<?php $favorite_ids_string = implode(',', $favorite_ids);
// foreach ($favorite_ids as $key => $fi) {
//     if ($key === count($favorite_ids) - 1) {
//         $favorite_ids_string .= $fi;
//     } else {
//         $favorite_ids_string .= $fi. ',';
//     }
// };
$params = http_build_query(['favorite_ids' => $favorite_ids_string],'', '&amp;');
// header("Location: /izbrannoe?".$params);
?>
<script>
    const favorite_ids = {
        'favorite_ids': '<?php echo  $favorite_ids_string; ?>',
    };
    let location_params = window.location.href + '?<?php echo $params; ?>';
    // window.history.pushState(favorite_ids, '', location_params)

    function addOrUpdateUrlParam(obj, name, value)    {
        let href = window.location.href;
        let regex = new RegExp("[&\\?]" + name + "=");
        if(regex.test(href))    {
            regex = new RegExp("([&\\?])" + name + "=\\d+");
            window.history.pushState(obj, '', href.replace(regex, "$1" + name + "=" + value));
        }    else    {
            if(href.indexOf("?") > -1)
            window.history.pushState(obj, '', href + "&" + name + "=" + value);
            else
            window.history.pushState(obj, '', href + "?" + name + "=" + value);
        }
    }
    addOrUpdateUrlParam(favorite_ids, 'favorite_ids', '<?php echo  $favorite_ids_string; ?>');
</script>
<div class="favorites">
<?php $paged = get_query_var('paged') ?: 1;
        // echo '<pre>';
        // var_dump($favorite_ids);
        // echo '</pre>';
        $post_args = array(             
          'post_type'   => 'product',
          'post__in'  => $favorite_ids,
          'order_by' => 'rand',
          'posts_per_page' => -1,
          'paged' => $paged,
          //'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
      );  

        $wp_query = new WP_Query($post_args);
        if ( $wp_query->have_posts() ) { ?>
            

            <section class="favorites favorites_page page-catalog">
                <div class="container">       
                    <ul class="breadcrumbs">
                        <li><a href="/">Главная</a></li>
                        <li>Избранное</li>
                    </ul>            
                    <h1 class="title-main">Избранные товары</h1>
                    <div class="favorit-header">
                        <div class="favorit-category">Вы добавили: <span class="fav_count"><?php echo get_user_favorites_count(); ?></span> товар</div>
                        <span class="favorit-link"><?php echo get_clear_favorites_button(); ?></span>
                        <!-- <a href="#" class="favorit-link">Очистить список</a> -->
                    </div>
                    <div class="favorit-item">
                        <div class="row row_catalog">
                        <?php while ( $wp_query->have_posts() ) {                  
                        $wp_query->the_post();
                        global $product; ?>
                          <?php wc_get_template_part( 'content', 'product' ); ?>
                        <?php } ?>
                        </div>
                    </div>
                </div>
            </section>
	<?php
        }
        wp_reset_postdata(); // сброс ?>
	
</div>
<?php } else { ?>
    <div class="breadcrumbs">
        <div class="container">
            <ul class="breadcrumbs">
                <li><a href="/">Главная</a></li>
                <li>Избранное</li>
            </ul>            
            <h1 class="title-main">Избранные товары</h1>
        </div>
    </div>
    <section class="favorites favorites_page page-catalog">
        <div class="container">            
            <div class="block-empty__title">Сохраняйте</div>
            <div class="block-empty__descr">понравившиеся варианты в избранное</div>
        </div>
    </section>
<?php } ?>


<?php
get_footer();