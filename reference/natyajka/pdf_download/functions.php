<?php

	define( 'THEME_URL', get_stylesheet_directory_uri() );
	define( 'THEME_PATH', get_template_directory() );
	define( 'THEME_AJAX', admin_url('admin-ajax.php') );
	
// show_admin_bar(false);
    
function theme_scripts() {
	wp_enqueue_style('theme-style', get_stylesheet_uri());

	wp_enqueue_style('font-style', get_template_directory_uri() . '/libs/fontawesome-free-5.12.0-web/css/all.min.css');

	wp_enqueue_style('slick', get_template_directory_uri() . '/libs/slick-1.5.9/slick/slick.css');
	wp_enqueue_style('slick-theme', get_template_directory_uri() . '/libs/slick-1.5.9/slick/slick-theme.css');
	//wp_enqueue_style('fancybox', 'https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css');
  wp_enqueue_style('fancybox',  get_template_directory_uri() . '/libs/fancybox/jquery.fancybox.css');

  	wp_enqueue_style('main', get_template_directory_uri() . '/css/main.min.css');
	wp_enqueue_style('custom', get_template_directory_uri() . '/css/custom.css');
	wp_enqueue_style('add-style', get_template_directory_uri() . '/css/add-style.css?v=1.2');

	

    wp_enqueue_script('magnific-popup', get_template_directory_uri() . '/libs/magnific-popup/jquery.magnific-popup.min.js', array( 'jquery' ), '1.0', true);
    wp_enqueue_script('niceselect', get_template_directory_uri() . '/libs/nice-select/jquery.nice-select.min.js', array( 'jquery' ), '1.0', true);
    wp_enqueue_script('maskedinput', get_template_directory_uri() . '/js/jquery.maskedinput.min.js', array( 'jquery' ), '1.0', true);
		wp_enqueue_script('fancybox-js', get_stylesheet_directory_uri() . '/libs/fancybox/jquery.fancybox.pack.js', array('jquery'), null , true);
	wp_enqueue_script('fancybox-buttons-js', get_stylesheet_directory_uri() . '/libs/fancybox/helpers/jquery.fancybox-buttons.js', array('jquery'), null , true);
  wp_enqueue_script('slick', get_template_directory_uri() . '/libs/slick-1.5.9/slick/slick.js', array( 'jquery' ), '1.0', true);
    wp_enqueue_script('scripts', get_template_directory_uri() . '/js/scripts.js', array( 'jquery' ), '1.0', true);
    wp_enqueue_script('app', get_template_directory_uri() . '/js/app.js?v=1.2', array( 'jquery' ), '1.0', true);
}
add_action('wp_enqueue_scripts', 'theme_scripts');





function get_cost_usd($post_id) {
    $rates = get_option( '_wprr_rate' );
    $cost_rub = get_field('стоимость', $post_id);
    $cost_usd = round( $cost_rub / str_replace(',','.',$rates['USD']['Value']) );
    $cost_usd = number_format($cost_usd, 0, ',', ' ');
    return $cost_usd;
}

add_theme_support( 'post-thumbnails' );
add_image_size( 'article', 165, 165, true);
add_image_size( 'realty', 260, 160, true);
add_image_size( 'article_medium', 360, 360);
add_image_size( 'front_category', 278, 220);
add_image_size( 'slider', 1920, 543);

add_theme_support( 'menus' );

if( function_exists('acf_add_options_page') ) {
	acf_add_options_page(array(
		'page_title' 	=> 'Настройки сайта',
		'menu_title'	=> 'Настройки сайта',
		'menu_slug' 	=> 'theme-general-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));	
}

function add_svg_to_upload_mimes( $upload_mimes ) {
 $upload_mimes['svg'] = 'image/svg+xml';
 $upload_mimes['svgz'] = 'image/svg+xml';
 return $upload_mimes;
}
add_filter( 'upload_mimes', 'add_svg_to_upload_mimes', 10, 1 );


add_action( 'wp_footer', 'mycustom_wp_footer' ); 
function mycustom_wp_footer() { ?>

<script type="text/javascript">
document.addEventListener( 'wpcf7mailsent', function( event ) {
      jQuery(function(){
            console.log('отправлено');
            jQuery(document).ready(function(){
                jQuery.ajax({
                    url: 'https://sheer82.ru/rezultaty',
                    type: 'GET',
                    data: {'site': 'gnk-yalta.ru'},
                    headers: { 'Access-Control-Allow-Origin': '*' },
                });
              });
        });    
}, false );
</script>

<script type="text/javascript">
document.addEventListener( 'wpcf7mailsent', function( event ) {
    if ( '74' == event.detail.contactFormId ) {
       (function($){
	       $('#feeback_form h6').text('Заявка отправлена');
		   $('#feeback_form form').html('<p>Менеджер свяжется с Вами<br> в ближайшее время</p>');
       })(jQuery);	
    }
    if ( '361' == event.detail.contactFormId ) {
    	yaCounter53656669.reachGoal('NAPISAT_NAM');
       (function($){
	       $('#team-contact-form h6').text('Заявка отправлена');
		   $('#team-contact-form form').html('<p>Менеджер свяжется с Вами<br> в ближайшее время</p>');
       })(jQuery);	
    }
    if ( '426' == event.detail.contactFormId ) {
    	yaCounter53656669.reachGoal('ORDERCALL');
       (function($){
	       $('#modal-phone h6').text('Заявка отправлена');
		   $('#modal-phone form').html('<p>Менеджер свяжется с Вами<br> в ближайшее время</p>');
       })(jQuery);	
    }
    if ( '91' == event.detail.contactFormId ) {
    	yaCounter53656669.reachGoal('ODRATNAYA_SVYAZ');
       (function($){
	       $('#contact_page_form h6').text('Заявка отправлена');
		   $('#contact_page_form form').html('<p>Менеджер свяжется с Вами<br> в ближайшее время</p>');
       })(jQuery);	
    }
    if ( '95' == event.detail.contactFormId ) {
    	yaCounter53656669.reachGoal('PODBOR');
       (function($){
	       $('.subscribe h2').text('Заявка отправлена');
		   $('.subscribe h4').html('Менеджер свяжется с Вами<br> в ближайшее время');
		   $('.subscribe .subscribe__form').remove();
       })(jQuery);	
    }  
    if ( '433' == event.detail.contactFormId ) {
    	yaCounter53656669.reachGoal('OBYAVLENIE');
	}
	if ( '1555' == event.detail.contactFormId ) {
        $('.contact__form_home_header .title_form').text('Заявка отправлена');
        $('.contact__form_home_header .sub_form').html('Менеджер свяжется с Вами<br> в ближайшее время');
        $('.contact__form_home_header .form_wr').remove();
        yaCounter53656669.reachGoal('VARIANTS');
	}
    if ( '1556' == event.detail.contactFormId ) {
        $('.contact__form_callback .title_form').text('Заявка отправлена');
        $('.contact__form_callback .sub_form').html('Менеджер свяжется с Вами<br> в ближайшее время');
        $('.contact__form_callback .form_wr').remove();
        yaCounter53656669.reachGoal('PEREZVONITE_CARD');
	} 
    if ( '4517' == event.detail.contactFormId ) {
       (function($){
	       $('#modal-view h6').text('Заявка отправлена');
		   $('#modal-view form').html('<p>Менеджер свяжется с Вами<br> в ближайшее время</p>');
       })(jQuery);	
    } 
    if ( '8880' == event.detail.contactFormId ) {
       (function($){
	       $('#modal-message h6').text('Заявка отправлена');
		   $('#modal-message form').html('<p>Менеджер свяжется с Вами<br> в ближайшее время</p>');
       })(jQuery);	
    } 
	if ( '15457' == event.detail.contactFormId ) {
       (function($){
		window.open('https://www.rncb.ru/local/components/credanketa/templates/.default/?ANKETA_ID=1880', '_blank');
       })(jQuery);	
    }
}, false );
</script>
<?php
}


function range_data($id) {
        $args = array(
            'posts_per_page'=> -1,
            'post_type'     => 'realty',
			'post_status' => 'publish',
            'tax_query' => array(
				array(
					'taxonomy' => 'realty-category',
					'field'    => 'id',
					'terms'    => $id
				)
			),
        );
        $properties_query = new WP_Query( $args ); 
        $prices = array();
$areas_array = array();
$cottage_array = array();
$complexes = array();
$room_array = array();
$type_array = array();
$manager_array = array();
        if( $properties_query->have_posts() ):
            while( $properties_query->have_posts() ) : $properties_query->the_post();

                $price = get_field('стоимость'); 
                $area = get_field('площадь'); 
                $cottage = get_field('площадь_участка');
                $complex = get_field('жилой_комплекс'); 
                $room = get_field('кол-во_комнат');
                $type = get_field('вид_использования');
				$manager = get_field('менеджер');
                if(isset($price) && !empty($price)){						
                    $prices_array[] = $price;						
                }
                //$prices_array[] = $price;   
				if(isset($area) && !empty($area)){             
                $areas_array[] = $area;
				} if(isset($cottage) && !empty($cottage)){
                $cottage_array[] = $cottage;
				} if(isset($complex) && !empty($complex)){
                $complexes[] = $complex;
				} if(isset($room) && !empty($room)){
                $room_array[] = $room;
				} if(isset($type) && !empty($type)){
                $type_array[] = $type;
				} if(isset($manager) && !empty($manager)){
				$manager_array[] = $manager;
				}
                
            endwhile;
            
            $pricess = array_diff($prices_array, array(''));   
          //  $min_price = min($pricess);
            $min_price = 1;
            $max_price = max($pricess);
            
            $areas = array_diff($areas_array, array('')); 
            // $min_area = min($areas);
            $min_area = 1;
            $max_area = max($areas);
            
            $cottages = array_diff($cottage_array, array('')); 
            $min_cottage = min($cottages);
            $max_cottage = max($cottages);
            
            $complexes_unique = array_unique($complexes);
        	$complexes_echo = array_diff($complexes_unique, array(''));    
            
            $room_unique = array_unique($room_array);
        	$rooms_echo = array_diff($room_unique, array('')); 
        	
        	$type_unique = array_unique($type_array);
        	$type_echo = array_diff($type_unique, array('')); 

			$manager_unique = array_unique($manager_array);
        	$manager_echo = array_diff($manager_unique, array('')); 

			$price_unique = array_unique($prices_array);
			$price_echo = array_diff($price_unique, array(''));

        endif; wp_reset_query(); 
        
        $data_array = [
            'p_min'=>$min_price,
            'p_max'=>$max_price,
            'a_min'=>floor($min_area),
            'a_max'=>ceil($max_area),
            'complexes' => $complexes_echo,
            'c_min'=> floor($min_cottage),
            'c_max'=> ceil($max_cottage),
            'rooms' => $rooms_echo,
            'type' => $type_echo,
			'manager' => $manager_echo,
			'prices' => $prices_array
        ];
        
        return $data_array;
}


add_filter('body_class','my_class_names');
function my_class_names( $classes ) {
    
	if( is_home() || is_front_page() ){
    	$classes[] = 'main-page';
    }else{
        $classes[] = 'secondary-page';
    }
    if( is_tax() || is_page(310) || is_search() ){
        $classes[] = 'catalog-page catalog2-page catalog2-plot-page';
    }
    if( is_category() ){
        $classes[] = 'news-page';
    }
    if( is_page(23) ){
        $classes[] = 'about-page';
    }
    if (is_single()){
        $classes[] = 'new-page';
    }
    if( is_page_template('page-barhatnyi.php') || is_page_template('page-chair.php')){
        $classes[] = 'facility-page facility2-page';
    }

	return $classes;
}

add_filter( 'excerpt_length', function(){
	return 20;
});

add_filter('excerpt_more', function($more) {
	return '...';
});

// function tax_city_posts_per_page( $query ) {
//     if( is_tax('realty-category') ) {
//         $query->set('posts_per_page', 30); //ваше значение
//     }
//     return $query;
// }
// add_filter('pre_get_posts', 'tax_city_posts_per_page');

// *************************** ajax запросы *****************************************

add_action('wp_ajax_ajax_fn', 'ld_ajax_fn');
add_action('wp_ajax_nopriv_ajax_fn', 'ld_ajax_fn');
function ld_ajax_fn(){
	$param = $_REQUEST['param'];
	$action = $param['action'];
	switch($action){
		case 'get-presentation':
			echo fvGetPresentation($param);
		break;
	}
	wp_die();
}

if (!function_exists('ld_url_to_path')) {
	function ld_url_to_path($url) {
		if (empty($url)) return false;
		$upload_dir = wp_upload_dir();
		if (strpos($url, $upload_dir['baseurl']) === 0) {
			return str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $url);
		}
		$theme_url = get_template_directory_uri();
		if (strpos($url, $theme_url) === 0) {
			return str_replace($theme_url, get_template_directory(), $url);
		}
		return $url;
	}
}

function fvGetPresentation($param){
	
	if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/fpdf/fpdf.php')) {
		include_once( $_SERVER['DOCUMENT_ROOT'] . '/fpdf/fpdf.php');
	} elseif (file_exists(get_template_directory() . '/libs/fpdf/fpdf.php')) {
		include_once(get_template_directory() . '/libs/fpdf/fpdf.php');
	}
	if (!class_exists('FPDF')) {
		return 'Error: FPDF not found';
	}
	
	$product = get_post($param['product_id'], OBJECT);
	if (!$product) return 'Error: Product not found';
	
	$gallery = get_field('галерея', $product->ID);
	$price = ''; $price_area = '';
	if(get_field('стоимость_со_скидкой', $product->ID)){
		$price = (get_field('стоимость', $product->ID)) ? number_format(get_field('стоимость', $product->ID), 0, ',', ' ') : '';
		$price_area = (get_field('стоимость_аренды', $product->ID)) ? number_format(get_field('стоимость_аренды', $product->ID), 0, ',', ' ') : '';
		if(get_field('стоимость', $product->ID)){
			$price = number_format(get_field('стоимость_со_скидкой', $product->ID), 0, ',', ' ');
			if(get_field('площадь', $product->ID)){
				$area = (float)get_field('площадь', $product->ID);
				if($area > 0) {
					$price_area = number_format(round(get_field('стоимость_со_скидкой', $product->ID)/$area), 0, ',', ' ');
				}
			}
		}
	}
	else{
		if(get_field('стоимость', $product->ID)){
			$price = number_format(get_field('стоимость', $product->ID), 0, ',', ' ');
			if(get_field('площадь', $product->ID)){
				$area = (float)get_field('площадь', $product->ID);
				if($area > 0) {
					$price_area = number_format(round(get_field('стоимость', $product->ID)/$area), 0, ',', ' ');
				}
			}
		}
	}
	$manager_img = ''; $manager_name = ''; $manager_phone = '';
	$manager = get_field('менеджер', $product->ID);
	if($manager){
		if(get_the_post_thumbnail_url($manager, 'thumbnail')){
			$manager_img = get_the_post_thumbnail_url($manager, 'thumbnail');
		}
		$manager_name = get_the_title($manager);
		$manager_phone = get_field('телефон', $manager);
	}
	
	$pdf = new FPDF('P', 'pt', 'A4');
	
	$pdf->SetMargins(30, 40, 30);
	//$pdf->SetAutoPageBreak(20);
	
	$pdf->SetTextColor(0, 0, 0);
	$pdf->AddPage();
	$pdf->AddFont('yst', '', 'yst.php');
	$pdf->AddFont('ystl', '', 'ystl.php');
	$pdf->AddFont('ystm', '', 'ystm.php');
	
	$pdf->SetFont('ystm', '', 14);
	$pdf->SetXY(120, 35);
	$pdf->SetTextColor(0, 0, 128);
	$pdf->Write(5, iconv("UTF-8", "CP1251", strip_tags($product->post_title)), get_the_permalink($product->ID));
	//$pdf->MultiCell(450, 10, iconv("UTF-8", "CP1251", strip_tags($product->post_title)), 0, 'L', 0);
	
	$pdf->SetTextColor(0, 0, 0);
	
	$logo_path = get_template_directory() . '/img/logo.jpg';
	if(file_exists($logo_path)) {
		$pdf->Image($logo_path, 20, 15, 80, 0);
	} else {
		$pdf->Image(get_template_directory_uri() . '/img/logo.jpg', 20, 15, 80, 0);
	}
	
	$thumb_url = get_the_post_thumbnail_url($product->ID);
	if(!empty($thumb_url)){
		$thumb_path = ld_url_to_path($thumb_url);
		if (file_exists($thumb_path) || ini_get('allow_url_fopen')) {
			try { $pdf->Image($thumb_path, 20, 75, 310, 250); } catch (Exception $e) {}
		}
	}
	
	if($gallery){
		// foreach($gallery as $image){
			// if($key > 0) break;
			// $pdf->Image($image['sizes']['large'], 20, 75, 310, 250);
		// }
		foreach($gallery as $key => $image){
			//if($key == 0) continue;
			if($key > 7) break;
			$url = false;
			if(is_array($image) && isset($image['sizes']) && isset($image['sizes']['medium'])){
				$url = $image['sizes']['medium'];
			} elseif (is_array($image) && isset($image['url'])) {
				$url = $image['url'];
			}
			if ($url) {
				$path = ld_url_to_path($url);
				if (file_exists($path) || ini_get('allow_url_fopen')) {
					try { $pdf->Image($path, ($key * 70) + 20, 335, 65, 50); } catch (Exception $e) {}
				}
			}
		}
	}
	
	$pdf->SetFont('yst', '', 8);
	$pdf->SetXY(340, 75);
	$pdf->MultiCell(230, 10, iconv("UTF-8", "CP1251", strip_tags('Адрес: ' . get_field('адрес', $product->ID))), 0, 'L', 0);
	$pdf->SetXY(340, 85);
	$pdf->SetTextColor(117, 117, 117);
	$jk = get_field('жк', $product->ID);
	$jk_name = '';
	if (is_object($jk) && isset($jk->name)) $jk_name = $jk->name;
	elseif (is_array($jk) && isset($jk[0]->name)) $jk_name = $jk[0]->name;
	elseif (is_string($jk)) $jk_name = $jk;
	$pdf->MultiCell(230, 10, iconv("UTF-8", "CP1251", strip_tags($jk_name)), 0, 'L', 0);
	
	
	
	$pdf->SetXY(340, 95);
	$pdf->SetFont('ystm', '', 16);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->MultiCell(230, 15, iconv("UTF-8", "CP1251", strip_tags($price . ' руб')), 0, 'L', 0);
	$pdf->SetXY(340, 115);
	$pdf->SetFont('ystm', '', 10);
	$pdf->SetTextColor(117, 117, 117);
	$pdf->MultiCell(230, 10, iconv("UTF-8", "CP1251", strip_tags($price_area . ' руб/кв. м')), '', 'L', 0);
	
	$pdf->SetXY(340, 125);
	$pdf->MultiCell(230, 9, '', 'B', 'L', 0);
	
	if(!empty($manager_img))
		$pdf->Image($manager_img, 340, 140, 60, 60);
	
	$pdf->SetFont('yst', '', 10);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetXY(405, 140);
	if(!empty($manager_name))
		$pdf->MultiCell(230, 10, iconv("UTF-8", "CP1251", 'Менеджер:'), '', 'L', 0);
	
	$pdf->SetFont('ystm', '', 10);
	$pdf->SetXY(405, 155);
	if(!empty($manager_name))
		$pdf->MultiCell(230, 10, iconv("UTF-8", "CP1251", $manager_name), '', 'L', 0);
	
	$pdf->SetFont('ystm', '', 16);
	$pdf->SetXY(405, 178);
	if(!empty($manager_phone)) {
		$pdf->SetTextColor(0, 0, 128);
		$pdf->Write(5, $manager_phone, 'tel:+'. preg_replace('/[^0-9]/', '', (string)$manager_phone));
		$pdf->SetTextColor(0, 0, 0);
	}
		//$pdf->WriteHTML('<a href="">' . $manager_phone . '</a>');
		//$pdf->MultiCell(230, 16, iconv("UTF-8", "CP1251", '<a href="tel:+'. preg_replace('/[^0-9]/', '', $manager_phone) .'">' . $manager_phone . '</a>'), '', 'L', 0);

	
	$pdf->SetXY(340, 195);
	$pdf->MultiCell(230, 12, '', 'B', 'L', 0);
	
	
	$pdf->SetFont('yst', '', 10);
	$pdf->SetTextColor(117, 117, 117);
	
	$SetX = 340;
	if(get_field('площадь', $product->ID)){
		$pdf->SetXY($SetX, 215);
		$pdf->MultiCell(60, 10, iconv("UTF-8", "CP1251", 'Общая'), '', 'L', 0);
	}
	if(get_field('жилая', $product->ID)){
		$SetX += 60;
		$pdf->SetXY($SetX, 215);
		$pdf->MultiCell(60, 10, iconv("UTF-8", "CP1251", 'Жилая'), '', 'L', 0);
	}
	if(get_field('кухня', $product->ID)){
		$SetX += 60;
		$pdf->SetXY($SetX, 215);
		$pdf->MultiCell(60, 10, iconv("UTF-8", "CP1251", 'Кухня'), '', 'L', 0);
	}
	if(get_field('этаж', $product->ID)){
		$SetX += 60;
		$pdf->SetXY($SetX, 215);
		$pdf->MultiCell(60, 10, iconv("UTF-8", "CP1251", 'Этаж'), '', 'L', 0);
	}
	if(get_field('площадь_участка', $product->ID)){
		$SetX += 60;
		$pdf->SetXY($SetX, 215);
		$pdf->MultiCell(60, 10, iconv("UTF-8", "CP1251", 'Площадь участка'), '', 'L', 0);
	}
	if(get_field('вид_использования', $product->ID)){
		$SetX += 60;
		$pdf->SetXY($SetX, 215);
		$pdf->MultiCell(60, 10, iconv("UTF-8", "CP1251", 'Вид раз-го исп-я'), '', 'L', 0);
	}
	
	$pdf->SetFont('ystm', '', 10);
	$pdf->SetTextColor(0, 0, 0);
	$SetX = 340;
	if(get_field('площадь', $product->ID)){
		$pdf->SetXY($SetX, 230);
		$pdf->MultiCell(60, 10, iconv("UTF-8", "CP1251", get_field('площадь', $product->ID) . 'кв. м'), '', 'L', 0);
	}
	if(get_field('жилая', $product->ID)){
		$SetX += 60;
		$pdf->SetXY($SetX, 230);
		$pdf->MultiCell(60, 10, iconv("UTF-8", "CP1251", get_field('жилая', $product->ID) . 'кв. м'), '', 'L', 0);
	}
	if(get_field('кухня', $product->ID)){
		$SetX += 60;
		$pdf->SetXY($SetX, 230);
		$pdf->MultiCell(60, 10, iconv("UTF-8", "CP1251", get_field('кухня', $product->ID) . 'кв. м'), '', 'L', 0);
	}
	if(get_field('этаж', $product->ID)){
		$SetX += 60;
		$pdf->SetXY($SetX, 230);
		$pdf->MultiCell(60, 10, iconv("UTF-8", "CP1251", get_field('этаж', $product->ID) . ' из ' . get_field('кол-во_этажей', $product->ID)), '', 'L', 0);
	}
	if(get_field('площадь_участка', $product->ID)){
		$SetX += 60;
		$pdf->SetXY($SetX, 230);
		$pdf->MultiCell(60, 10, iconv("UTF-8", "CP1251", get_field('площадь_участка', $product->ID) . ' сот.'), '', 'L', 0);
	}
	if(get_field('вид_использования', $product->ID)){
		$SetX += 60;
		$pdf->SetXY($SetX, 230);
		$use_type = get_field('вид_использования', $product->ID);
		$use_type_label = is_array($use_type) && isset($use_type['label']) ? $use_type['label'] : (is_string($use_type) ? $use_type : '');
		$pdf->MultiCell(60, 10, iconv("UTF-8", "CP1251", $use_type_label), '', 'L', 0);
	}
	
	
	$SetY = 240;
	$pdf->SetXY(340, $SetY);
	$pdf->MultiCell(230, 10, '', 'B', 'L', 0);
	
	if(get_field('до_моря', $product->ID)){
		$pdf->SetTextColor(0, 0, 0);
		$SetY += 20;
		$pdf->SetXY(340, $SetY);
		$pdf->MultiCell(150, 10, iconv("UTF-8", "CP1251", 'До моря:'), '', 'L', 0);
		$pdf->SetTextColor(117, 117, 117);
		$pdf->SetXY(490, $SetY);
		$pdf->MultiCell(150, 10, iconv("UTF-8", "CP1251", get_field('до_моря', $product->ID)), '', 'L', 0);
	}
	if(get_field('до_центра', $product->ID)){
		$pdf->SetTextColor(0, 0, 0);
		$SetY += 20;
		$pdf->SetXY(340, $SetY);
		$pdf->MultiCell(150, 10, iconv("UTF-8", "CP1251", 'До центра:'), '', 'L', 0);
		$pdf->SetTextColor(117, 117, 117);
		$pdf->SetXY(490, $SetY);
		$pdf->MultiCell(150, 10, iconv("UTF-8", "CP1251", get_field('до_центра', $product->ID)), '', 'L', 0);
	}
	if(get_field('окончание_строительства', $product->ID)){
		$pdf->SetTextColor(0, 0, 0);
		$SetY += 20;
		$pdf->SetXY(340, $SetY);
		$pdf->MultiCell(150, 10, iconv("UTF-8", "CP1251", 'Окончание строительства:'), '', 'L', 0);
		$pdf->SetTextColor(117, 117, 117);
		$pdf->SetXY(490, $SetY);
		$pdf->MultiCell(150, 10, iconv("UTF-8", "CP1251", get_field('окончание_строительства', $product->ID)), '', 'L', 0);
	}
	
	$pdf->SetFont('ystm', '', 10);
	$pdf->SetTextColor(117, 117, 117);
	$pdf->SetXY(30, 425);
	$pdf->MultiCell(0, 10, iconv("UTF-8", "CP1251", 'ТЕХНИЧЕСКАЯ ИНФОРМАЦИЯ'), '', 'L', 0);
	
	
	//$pdf->SetFillColor(255, 255, 255);
	//$pdf->SetDrawColor(237, 231, 234);
	//$pdf->Rect(20, 440, 555, 135, 'FD');
	
//============================
$SetY = 445;
if(get_field('ремонт', $product->ID)){
	$pdf->SetFont('ystm', '', 10);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetXY(30, $SetY);
	$pdf->MultiCell(150, 10, iconv("UTF-8", "CP1251", 'Ремонт:'), '', 'L', 0);
	$pdf->SetFont('yst', '', 10);
	$pdf->SetTextColor(117, 117, 117);
	$SetY += 15;
	$pdf->SetXY(30, $SetY);
	$pdf->MultiCell(180, 10, iconv("UTF-8", "CP1251", get_field('ремонт', $product->ID)), '', 'L', 0);
}
if(get_field('парковка', $product->ID)){
	$pdf->SetFont('ystm', '', 10);
	$pdf->SetTextColor(0, 0, 0);
	$SetY += 20;
	$pdf->SetXY(30, $SetY);
	$pdf->MultiCell(150, 10, iconv("UTF-8", "CP1251", 'Парковка:'), '', 'L', 0);
	$pdf->SetFont('yst', '', 10);
	$pdf->SetTextColor(117, 117, 117);
	$SetY += 15;
	$pdf->SetXY(30, $SetY);
	$pdf->MultiCell(180, 10, iconv("UTF-8", "CP1251", get_field('парковка', $product->ID)), '', 'L', 0);
}
if(get_field('охлаждение', $product->ID)){
	$pdf->SetFont('ystm', '', 10);
	$pdf->SetTextColor(0, 0, 0);
	$SetY += 20;
	$pdf->SetXY(30, $SetY);
	$pdf->MultiCell(150, 10, iconv("UTF-8", "CP1251", 'Охлаждение:'), '', 'L', 0);
	$pdf->SetFont('yst', '', 10);
	$pdf->SetTextColor(117, 117, 117);
	$SetY += 15;
	$pdf->SetXY(30, $SetY);
	$pdf->MultiCell(180, 10, iconv("UTF-8", "CP1251", get_field('охлаждение', $product->ID)), '', 'L', 0);
}
if(get_field('счетчики', $product->ID)){
	$pdf->SetFont('ystm', '', 10);
	$pdf->SetTextColor(0, 0, 0);
	$SetY += 20;
	$pdf->SetXY(30, $SetY);
	$pdf->MultiCell(150, 10, iconv("UTF-8", "CP1251", 'Счетчики:'), '', 'L', 0);
	$pdf->SetFont('yst', '', 10);
	$pdf->SetTextColor(117, 117, 117);
	$SetY += 15;
	$pdf->SetXY(30, $SetY);
	$pdf->MultiCell(180, 10, iconv("UTF-8", "CP1251", get_field('счетчики', $product->ID)), '', 'L', 0);
}
if(get_field('стены', $product->ID)){
	$pdf->SetFont('ystm', '', 10);
	$pdf->SetTextColor(0, 0, 0);
	$SetY += 20;
	$pdf->SetXY(30, $SetY);
	$pdf->MultiCell(150, 10, iconv("UTF-8", "CP1251", 'Стены:'), '', 'L', 0);
	$pdf->SetFont('yst', '', 10);
	$pdf->SetTextColor(117, 117, 117);
	$SetY += 15;
	$pdf->SetXY(30, $SetY);
	$pdf->MultiCell(180, 10, iconv("UTF-8", "CP1251", get_field('стены', $product->ID)), '', 'L', 0);
}
if(get_field('утпеление_стен', $product->ID)){
	$pdf->SetFont('ystm', '', 10);
	$pdf->SetTextColor(0, 0, 0);
	$SetY += 20;
	$pdf->SetXY(30, $SetY);
	$pdf->MultiCell(150, 10, iconv("UTF-8", "CP1251", 'Утпеление стен:'), '', 'L', 0);
	$pdf->SetFont('yst', '', 10);
	$pdf->SetTextColor(117, 117, 117);
	$SetY += 15;
	$pdf->SetXY(10, $SetY);
	$pdf->MultiCell(180, 10, iconv("UTF-8", "CP1251", get_field('утпеление_стен', $product->ID)), '', 'L', 0);
}
//============================
$SetY_base = $SetY;
$SetY = 455;
if(get_field('управление', $product->ID)){
	$pdf->SetFont('ystm', '', 10);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetXY(200, $SetY);
	$pdf->MultiCell(150, 10, iconv("UTF-8", "CP1251", 'Управляющая компания:'), '', 'L', 0);
	$pdf->SetFont('yst', '', 10);
	$pdf->SetTextColor(117, 117, 117);
	$SetY += 15;
	$pdf->SetXY(200, $SetY);
	$pdf->MultiCell(150, 10, iconv("UTF-8", "CP1251", get_field('управление', $product->ID)), '', 'L', 0);
}
if(get_field('в_доме', $product->ID)){
	$pdf->SetFont('ystm', '', 10);
	$pdf->SetTextColor(0, 0, 0);
	if($SetY <> 455) $SetY += 20;
	$pdf->SetXY(200, $SetY);
	$pdf->MultiCell(150, 10, iconv("UTF-8", "CP1251", 'В доме:'), '', 'L', 0);
	$pdf->SetFont('yst', '', 10);
	$pdf->SetTextColor(117, 117, 117);
	$SetY += 15;
	$pdf->SetXY(200, $SetY);
	$pdf->MultiCell(150, 10, iconv("UTF-8", "CP1251", get_field('в_доме', $product->ID)), '', 'L', 0);
}
//============================
$SetY_base = max($SetY_base, $SetY);
$SetY = 455;
if(get_field('вид_из_окна', $product->ID)){
	$pdf->SetFont('ystm', '', 10);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetXY(400, $SetY);
	$pdf->MultiCell(150, 10, iconv("UTF-8", "CP1251", 'Вид из окна:'), '', 'L', 0);
	$pdf->SetFont('yst', '', 10);
	$pdf->SetTextColor(117, 117, 117);
	$SetY += 15;
	$pdf->SetXY(400, $SetY);
	$pdf->MultiCell(150, 10, iconv("UTF-8", "CP1251", get_field('вид_из_окна', $product->ID)), '', 'L', 0);
}
if(get_field('конструктив', $product->ID)){
	$pdf->SetFont('ystm', '', 10);
	$pdf->SetTextColor(0, 0, 0);
	if($SetY <> 455) $SetY += 20;
	$pdf->SetXY(400, $SetY);
	$pdf->MultiCell(150, 10, iconv("UTF-8", "CP1251", 'Конструктив:'), '', 'L', 0);
	$pdf->SetFont('yst', '', 10);
	$pdf->SetTextColor(117, 117, 117);
	$SetY += 15;
	$pdf->SetXY(400, $SetY);
	$pdf->MultiCell(150, 10, iconv("UTF-8", "CP1251", get_field('конструктив', $product->ID)), '', 'L', 0);
}
$SetY_base = max($SetY_base, $SetY);
//============================
	$SetY_base += 30;
	$pdf->SetFont('ystm', '', 10);
	$pdf->SetTextColor(117, 117, 117);
	$pdf->SetXY(30, $SetY_base);
	$pdf->MultiCell(0, 10, iconv("UTF-8", "CP1251", 'ОПИСАНИЕ'), '', 'L', 0);

	//$pdf->SetFillColor(255, 255, 255);
	//$pdf->SetDrawColor(237, 231, 234);
	//$pdf->Rect(20, 605, 555, 260, 'FD');
	$SetY_base += 20;
	$pdf->SetFont('yst', '', 10);
	$pdf->SetTextColor(117, 117, 117);
	$pdf->SetXY(30, $SetY_base);
	$pdf->MultiCell(0, 12, iconv("UTF-8", "CP1251", strip_tags($product->post_content)), 0, 'L', 0);
	
	$upload_dir = wp_upload_dir();
	$dir = $upload_dir['basedir'] . '/_presentation';
	if (!file_exists($dir)) {
		wp_mkdir_p($dir); // uses wp secure dir creation
	}
	$file_path = $dir . '/presentation_'. $product->ID .'.pdf';
	$file_url = $upload_dir['baseurl'] . '/_presentation/presentation_'. $product->ID .'.pdf';
	
	try {
        $pdf->Output($file_path, 'F');
    } catch (Exception $e) {
        return 'Error generating PDF: ' . $e->getMessage();
    }
	return $file_url;
}

// *************************** ajax запросы *****************************************


/**
 * Кастомные переменные Yoast из группы ACF "Объект"
 * Поля: стоимость (number), кол-во_комнат (number), тип_сделки (select),
 * площадь (number), населенный_пункт (taxonomy), жк (taxonomy), категория (taxonomy)
 */

/** Безопасный геттер поля из группы "объект" с фолбэком на get_field($key) */
function gdv_get_object_field($key) {
    $group_name = 'объект';
    $group = function_exists('get_field') ? get_field($group_name) : null;

    if (is_array($group) && array_key_exists($key, $group)) {
        return $group[$key];
    }
    return function_exists('get_field') ? get_field($key) : null;
}

/** Преобразовать значение taxonomy-поля ACF в строку имён терминов */
function gdv_tax_to_names($value) {
    if (empty($value)) return '';

    $terms = [];
    if (is_object($value) && isset($value->term_id)) {
        $terms = [$value];
    } elseif (is_numeric($value)) {
        $term = get_term((int) $value);
        if ($term && !is_wp_error($term)) $terms[] = $term;
    } elseif (is_array($value)) {
        foreach ($value as $item) {
            if (is_object($item) && isset($item->term_id)) {
                $terms[] = $item;
            } elseif (is_numeric($item)) {
                $term = get_term((int) $item);
                if ($term && !is_wp_error($term)) $terms[] = $term;
            }
        }
    }
    if (empty($terms)) return '';
    return implode(', ', array_map(static function($t){ return $t->name; }, $terms));
}

/** Форматирование чисел в ru_RU */
function gdv_format_number($number, $decimals = 0) {
    if ($number === '' || $number === null) return '';
    if (!is_numeric($number)) return trim((string) $number);
    return number_format((float) $number, $decimals, ',', ' ');
}

/** Значения для Yoast */

// Категория — всегда нижним регистром
function yoast_obj_category() {
    $val = gdv_get_object_field('категория'); // taxonomy из группы "Объект"
    $names = gdv_tax_to_names($val);
    // mb_strtolower для кириллицы; если расширение отключено — падать не будет, просто вернёт исходное
    return $names !== '' && function_exists('mb_strtolower') ? mb_strtolower($names) : strtolower($names);
}

function yoast_obj_price() {
    $val = gdv_get_object_field('стоимость');
    $out = gdv_format_number($val);
    return $out !== '' ? $out . ' руб.' : '';
}

// Кол-во комнат — "1-комнатной", "2-комнатной", ...
function yoast_obj_rooms() {
    $val = gdv_get_object_field('кол-во_комнат');
    if ($val === '' || $val === null) return '';
    $num = (int) $val;
    switch ($num) {
        case 1: return '1-комнатной';
        case 2: return '2-комнатной';
        case 3: return '3-комнатной';
        case 4: return '4-комнатной';
        default: return $num . '-комнатной';
    }
}

// Тип сделки — всегда label (например, "Продажа"), а не value ("sale")
function yoast_obj_deal_type() {
    $val = gdv_get_object_field('тип_сделки');

    // Если ACF возвращает массив [value => ..., label => ...]
    if (is_array($val)) {
        return trim((string) ($val['label'] ?? $val['value'] ?? ''));
    }

    // Если ACF возвращает только value — достаём label из объекта поля
    if ($val) {
        $field_object = function_exists('get_field_object') ? get_field_object('тип_сделки') : null;
        if ($field_object && isset($field_object['choices'][$val])) {
            return trim((string) $field_object['choices'][$val]);
        }
    }

    return '';
}

function yoast_obj_area() {
    $val = gdv_get_object_field('площадь');
    $out = gdv_format_number($val, 0);
    return $out !== '' ? $out . ' м²' : '';
}

function yoast_obj_locality() {
    $val = gdv_get_object_field('населенный_пункт'); // taxonomy
    return gdv_tax_to_names($val);
}

function yoast_obj_complex() {
    $val   = gdv_get_object_field('жк'); // taxonomy
    $names = trim(gdv_tax_to_names($val)); // убираем пробелы вокруг

    if ($names !== '') {
        return ' в ' . $names;
    }

    return '';
}

/** Регистрация переменных Yoast */
function register_object_custom_yoast_variables() {
    if (!function_exists('wpseo_register_var_replacement')) {
        return; // Yoast не активен — выходим тихо
    }
    wpseo_register_var_replacement('%%ObjCategory%%', 'yoast_obj_category', 'advanced', 'Категория (таксономия, группа "Объект", нижний регистр)');
    wpseo_register_var_replacement('%%ObjPrice%%',    'yoast_obj_price',     'advanced', 'Стоимость объекта (группа "Объект")');
    wpseo_register_var_replacement('%%ObjRooms%%',    'yoast_obj_rooms',     'advanced', 'Количество комнат со склонением (группа "Объект")');
    wpseo_register_var_replacement('%%ObjDealType%%', 'yoast_obj_deal_type', 'advanced', 'Тип сделки (label из поля select, группа "Объект")');
    wpseo_register_var_replacement('%%ObjArea%%',     'yoast_obj_area',      'advanced', 'Площадь (группа "Объект")');
    wpseo_register_var_replacement('%%ObjLocality%%', 'yoast_obj_locality',  'advanced', 'Населённый пункт (таксономия, группа "Объект")');
    wpseo_register_var_replacement('%%ObjComplex%%',  'yoast_obj_complex',   'advanced', 'ЖК (таксономия, группа "Объект")');
}
add_action('wpseo_register_extra_replacements', 'register_object_custom_yoast_variables');




/******/

function range_data_catalog_tax($id) {
        $args = array(
            'posts_per_page'=> -1,
            'post_type'     => 'catalog',
			'post_status' => 'publish',
            'tax_query' => array(
				array(
					'taxonomy' => 'catalog_cat',
					'field'    => 'id',
					'terms'    => $id
				)
			),
        );
        $properties_query = new WP_Query( $args ); 
		$prices = array();
        $prices_array = array();
$areas_array = array();
$cottage_array = array();
$complexes = array();
$room_array = array();
$type_array = array();
$manager_array = array();
        if( $properties_query->have_posts() ):
            while( $properties_query->have_posts() ) : $properties_query->the_post();

                $price = get_field('стоимость'); 
                $area = get_field('площадь'); 
                $cottage = get_field('площадь_участка');
                $complex = get_field('жилой_комплекс'); 
                $room = get_field('кол-во_комнат');
                $type = get_field('вид_использования');
				$manager = get_field('менеджер');
                if(isset($price) && !empty($price)){						
                    $prices_array[] = $price;						
                }
                //$prices_array[] = $price;   
				if(isset($area) && !empty($area)){             
                $areas_array[] = $area;
				} if(isset($cottage) && !empty($cottage)){
                $cottage_array[] = $cottage;
				} if(isset($complex) && !empty($complex)){
                $complexes[] = $complex;
				} if(isset($room) && !empty($room)){
                $room_array[] = $room;
				} if(isset($type) && !empty($type)){
                $type_array[] = $type;
				} if(isset($manager) && !empty($manager)){
				$manager_array[] = $manager;
				}
                
            endwhile;
            if(!empty($prices_array)){
				$pricess = array_diff($prices_array, array(''));   
			//  $min_price = min($pricess);
		
				$min_price = 1;			
				$max_price = max($pricess);
				
				if(!empty($areas_array)){
				$areas = array_diff($areas_array, array('')); 
				$max_area = max($areas);
				} else {
					$max_area = 1;
				}
				// $min_area = min($areas);
				$min_area = 1;		
				
				
				$cottages = array_diff($cottage_array, array('')); 
				if(!empty($cottages)){
					$min_cottage = min($cottages);
					$max_cottage = max($cottages);
					// var_dump($min_cottage);
				} else {
					$min_cottage = 1;
					$max_cottage = 1;
				}
				
				$complexes_unique = array_unique($complexes);
				$complexes_echo = array_diff($complexes_unique, array(''));    
				
				$room_unique = array_unique($room_array);
				$rooms_echo = array_diff($room_unique, array('')); 
			
				// $type_unique = array_unique($type_array);
				// $type_echo = array_diff($type_unique, array('')); 

				$manager_unique = array_unique($manager_array);
				$manager_echo = array_diff($manager_unique, array('')); 

				$price_unique = array_unique($prices_array);
				$price_echo = array_diff($price_unique, array(''));
			}

        endif; wp_reset_query(); 
			if(!empty($prices_array)){
			$data_array = [
				'p_min'=>$min_price,
				'p_max'=>$max_price,
				'a_min'=>floor($min_area),
				'a_max'=>ceil($max_area),
				'complexes' => $complexes_echo,
				'c_min'=> floor($min_cottage),
				'c_max'=> ceil($max_cottage),
				'rooms' => $rooms_echo,
				// 'type' => $type_echo,
				'manager' => $manager_echo,
				'prices' => $price_echo
			];        
			return $data_array;
		}
}

add_action('wp_enqueue_scripts', function () {
  wp_enqueue_script(
    'taxonomy-child-ajax',
    get_stylesheet_directory_uri() . '/js/taxonomy-child-ajax.js',
    ['jquery'],
    '1.0',
    true
  );

  wp_localize_script('taxonomy-child-ajax', 'TaxAjax', [
    'url'   => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('get_child_terms_nonce'),
    'taxonomy' => 'catalog_cat',
  ]);
});


add_action('wp_ajax_get_child_terms', 'asan_get_child_terms');
add_action('wp_ajax_nopriv_get_child_terms', 'asan_get_child_terms');

function asan_get_child_terms() {
  check_ajax_referer('get_child_terms_nonce', 'nonce');

  $parent_id = isset($_POST['parent_id']) ? absint($_POST['parent_id']) : 0;
  $taxonomy  = isset($_POST['taxonomy']) ? sanitize_key($_POST['taxonomy']) : 'catalog_cat';

  if (!$parent_id || !taxonomy_exists($taxonomy)) {
    wp_send_json_success(['terms' => []]); // пусто — просто выключим селект
  }

  $terms = get_terms([
    'taxonomy'   => $taxonomy,
    'hide_empty' => true,   // поставьте false, если хотите показывать и «пустые» города
    'parent'     => $parent_id,
  ]);

  if (is_wp_error($terms)) {
    wp_send_json_error(['message' => $terms->get_error_message()]);
  }

  $out = [];
  foreach ($terms as $t) {
    $out[] = [
      'id'   => $t->term_id,
      'name' => $t->name,
	  'link' => get_term_link($t),
    ];
  }

  wp_send_json_success(['terms' => $out]);
}

// AJAX обработчик для получения min/max цены категории
add_action('wp_ajax_get_category_prices', 'asan_get_category_prices');
add_action('wp_ajax_nopriv_get_category_prices', 'asan_get_category_prices');

function asan_get_category_prices() {
  check_ajax_referer('get_child_terms_nonce', 'nonce');

  $category_id = isset($_POST['category_id']) ? absint($_POST['category_id']) : 0;
  $city_id = isset($_POST['city_id']) ? absint($_POST['city_id']) : 0;
  $district_id = isset($_POST['district_id']) ? absint($_POST['district_id']) : 0;

  if (!$category_id) {
    wp_send_json_success(['min_price' => '', 'max_price' => '']);
  }

  // Определяем ID термина для запроса
  // Приоритет: микрорайон > город > категория
  $term_id = $district_id ? $district_id : ($city_id ? $city_id : $category_id);

  // Получаем данные о ценах
  $price_data = range_data_catalog_tax($term_id);

  if ($price_data && !empty($price_data) && isset($price_data['p_min']) && isset($price_data['p_max'])) {
    $min_price = $price_data['p_min'];
    $max_price = $price_data['p_max'];
    
    // Проверяем, что цены валидны
    if ($min_price > 0 && $max_price > 0) {
      // Форматируем цены с пробелами
      $min_price_formatted = number_format($min_price, 0, '', ' ');
      $max_price_formatted = number_format($max_price, 0, '', ' ');
      
      wp_send_json_success([
        'min_price' => $min_price_formatted,
        'max_price' => $max_price_formatted,
        'min_price_raw' => $min_price,
        'max_price_raw' => $max_price
      ]);
    } else {
      wp_send_json_success(['min_price' => '', 'max_price' => '']);
    }
  } else {
    wp_send_json_success(['min_price' => '', 'max_price' => '']);
  }
}

function wpcrft_return_canonical_2($link) {
	$link = preg_replace('#\\??/page[\\/=]\\d+#', '', $link);
	     return $link;
	 }

add_filter( 'wpseo_canonical', 'wpcrft_return_canonical_2', 100, 1 );

// Добавляем canonical, равный "Постоянной ссылке" в админке
add_action( 'wp_head', function () {
    if ( ! is_singular() ) {
        return; // только для отдельных записей/страниц/кастомных типов
    }

    global $post;

    // Берём URL как в "Постоянная ссылка:"
    $canonical = get_permalink( $post );

    if ( ! $canonical ) {
        return;
    }

    // Можно дополнительно нормализовать URL, если нужно
    $canonical = esc_url( $canonical );

    echo '<link rel="canonical" href="' . $canonical . "\" />\n";
}, 1 );



/*
function output_posts_to_console() {
    $args = [
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
    ];

    $query = new WP_Query( $args );

    if ( ! $query->have_posts() ) {
        return;
    }

    $posts_data = [];

    while ( $query->have_posts() ) {
        $query->the_post();

        $posts_data[] = [
            'title' => get_the_title(),
            'url'   => get_permalink(),
        ];
    }

    wp_reset_postdata();

    // Передаём данные в JS через wp_localize_script
    wp_register_script( 'posts-console', false );
    wp_enqueue_script( 'posts-console' );

    wp_add_inline_script( 'posts-console', '
        const wpPosts = ' . wp_json_encode( $posts_data ) . ';
        console.group("📝 Записи сайта");
        wpPosts.forEach(post => {
            console.log("%c" + post.title, "font-weight: bold; color: #0073aa;");
            console.log("🔗 " + post.url);
            console.log("---");
        });
        console.groupEnd();
    ' );
}

add_action( 'wp_enqueue_scripts', 'output_posts_to_console' );
*/