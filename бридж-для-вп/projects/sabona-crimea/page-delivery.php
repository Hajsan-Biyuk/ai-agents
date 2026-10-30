<?php
/*
Template Name: Доставка
*/
add_action( 'wp_enqueue_scripts', function () {
	$uri = get_template_directory_uri();
	wp_enqueue_style( 'sabona-delivery', $uri . '/delivery.css', array(), '1.0' );
	wp_enqueue_style( 'sabona-delivery-media', $uri . '/delivery-media.css', array( 'sabona-delivery' ), '1.0' );
}, 20 );

if ( ! function_exists( 'sabona_del_str' ) ) {
	function sabona_del_str( $value ) {
		return is_string( $value ) ? trim( $value ) : '';
	}
}

if ( ! function_exists( 'sabona_del_field' ) ) {
	function sabona_del_field( $name ) {
		if ( ! function_exists( 'get_field' ) ) {
			return '';
		}
		return sabona_del_str( get_field( $name ) );
	}
}

if ( ! function_exists( 'sabona_del_html' ) ) {
	function sabona_del_html( $value ) {
		$value = sabona_del_str( $value );
		if ( $value === '' ) {
			return '';
		}
		return nl2br( esc_html( $value ), false );
	}
}

if ( ! function_exists( 'sabona_del_rows' ) ) {
	function sabona_del_rows( $name ) {
		if ( ! function_exists( 'get_field' ) ) {
			return array();
		}
		$rows = get_field( $name );
		return is_array( $rows ) ? $rows : array();
	}
}

if ( ! function_exists( 'sabona_del_icon_file' ) ) {
	function sabona_del_icon_file( $key ) {
		$icons = array(
			'cash'     => 'del-icon-cash.svg',
			'exchange' => 'del-icon-exchange.svg',
			'wallet'   => 'del-icon-wallet.svg',
			'card'     => 'del-icon-card.svg',
			'invoice'  => 'del-icon-invoice.svg',
			'qr'       => 'del-icon-qr.svg',
			'transfer' => 'del-icon-transfer.svg',
		);
		return isset( $icons[ $key ] ) ? $icons[ $key ] : '';
	}
}

get_header();

$del_img   = get_template_directory_uri() . '/img/';
$hours_title = sabona_del_field( 'del_hours_title' );
$hours_text  = sabona_del_field( 'del_hours_text' );
$ship_title  = sabona_del_field( 'del_ship_title' );
$free_title  = sabona_del_field( 'del_free_title' );
$free_text   = sabona_del_field( 'del_free_text' );
$prices      = sabona_del_rows( 'del_prices' );
$days_title  = sabona_del_field( 'del_days_title' );
$days        = sabona_del_rows( 'del_days' );
$pay_title   = sabona_del_field( 'del_pay_title' );
$pays        = sabona_del_rows( 'del_pays' );
$note_new_title = sabona_del_field( 'del_note_new_title' );
$note_new_text  = sabona_del_field( 'del_note_new_text' );
$note_big_title = sabona_del_field( 'del_note_big_title' );
$note_big_text  = sabona_del_field( 'del_note_big_text' );
$steps_title = sabona_del_field( 'del_steps_title' );
$steps_lead  = sabona_del_field( 'del_steps_lead' );
$steps       = sabona_del_rows( 'del_steps' );
$form_title  = sabona_del_field( 'del_form_title' );
$form_lead   = sabona_del_field( 'del_form_lead' );
$form_phone  = sabona_del_field( 'del_form_phone' );
$form_company = sabona_del_field( 'del_form_company' );
$form_message = sabona_del_field( 'del_form_message' );
$form_agree  = sabona_del_field( 'del_form_agree' );
$form_btn    = sabona_del_field( 'del_form_btn' );

$pay_left  = array();
$pay_right = array();
$pay_full  = array();
foreach ( $pays as $pay ) {
	if ( ! is_array( $pay ) ) {
		continue;
	}
	$column = isset( $pay['column'] ) ? (string) $pay['column'] : 'left';
	if ( $column === 'right' ) {
		$pay_right[] = $pay;
	} elseif ( $column === 'full' ) {
		$pay_full[] = $pay;
	} else {
		$pay_left[] = $pay;
	}
}

$show_hours = ( $hours_title !== '' || $hours_text !== '' );
$show_ship  = ( $ship_title !== '' || $show_hours || $free_title !== '' || $free_text !== '' || $prices || $days_title !== '' || $days );
$show_pay   = ( $pay_title !== '' || $show_hours || $pays || $note_new_title !== '' || $note_new_text !== '' || $note_big_title !== '' || $note_big_text !== '' );
$show_steps = ( $steps_title !== '' || $steps_lead !== '' || $steps );
$show_form  = ( $form_title !== '' || $form_lead !== '' || $form_phone !== '' || $form_company !== '' || $form_message !== '' || $form_agree !== '' || $form_btn !== '' );

if ( ! function_exists( 'sabona_del_hours' ) ) {
	function sabona_del_hours( $img, $title, $text ) {
		if ( $title === '' && $text === '' ) {
			return;
		}
		?>
		<div class="page-del__hours">
			<img class="page-del__hours-ico" src="<?php echo esc_url( $img . 'del-icon-time.svg' ); ?>" alt="" width="36" height="36">
			<div class="page-del__hours-copy">
				<?php if ( $title !== '' ) { ?>
					<h3 class="page-del__hours-title"><?php echo esc_html( $title ); ?></h3>
				<?php } ?>
				<?php if ( $text !== '' ) { ?>
					<p class="page-del__hours-text"><?php echo esc_html( $text ); ?></p>
				<?php } ?>
			</div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'sabona_del_pay_item' ) ) {
	function sabona_del_pay_item( $pay, $img ) {
		$text = sabona_del_html( isset( $pay['text'] ) ? $pay['text'] : '' );
		$icon = sabona_del_icon_file( isset( $pay['icon'] ) ? (string) $pay['icon'] : '' );
		if ( $text === '' && $icon === '' ) {
			return;
		}
		$cash = ( isset( $pay['icon'] ) && $pay['icon'] === 'cash' );
		?>
		<div class="page-del__pay">
			<?php if ( $icon !== '' ) { ?>
				<span class="page-del__pay-ico<?php echo $cash ? ' page-del__pay-ico_cash' : ''; ?>">
					<img src="<?php echo esc_url( $img . $icon ); ?>" alt="" width="<?php echo $cash ? '46' : '24'; ?>" height="<?php echo $cash ? '46' : '24'; ?>">
				</span>
			<?php } ?>
			<?php if ( $text !== '' ) { ?>
				<p class="page-del__pay-text"><?php echo $text; ?></p>
			<?php } ?>
		</div>
		<?php
	}
}
?>
<section class="page-del">
	<div class="container">
		<div class="page-del__body">
			<nav class="page-del__crumbs" aria-label="Хлебные крошки">
				<a href="/">Главная</a><span class="page-del__crumbs-sep" aria-hidden="true"> › </span><span aria-current="page"><?php the_title(); ?></span>
			</nav>

			<h1 class="page-del__title"><?php the_title(); ?></h1>

			<?php if ( $show_ship || $show_pay ) { ?>
			<div class="page-del__cards">
				<?php if ( $show_ship ) { ?>
				<article class="page-del__card">
					<div class="page-del__card-top">
						<?php if ( $ship_title !== '' ) { ?>
							<h2 class="page-del__card-title"><?php echo esc_html( $ship_title ); ?></h2>
						<?php } ?>
						<?php sabona_del_hours( $del_img, $hours_title, $hours_text ); ?>
					</div>

					<?php if ( $free_title !== '' || $free_text !== '' ) { ?>
					<div class="page-del__free">
						<?php if ( $free_title !== '' ) { ?>
							<h3 class="page-del__sub"><?php echo esc_html( $free_title ); ?></h3>
						<?php } ?>
						<?php if ( $free_text !== '' ) { ?>
							<p class="page-del__text"><?php echo sabona_del_html( $free_text ); ?></p>
						<?php } ?>
					</div>
					<?php } ?>

					<?php if ( $prices ) { ?>
					<div class="page-del__prices">
						<?php foreach ( $prices as $index => $price ) {
							if ( ! is_array( $price ) ) {
								continue;
							}
							$tone = isset( $price['tone'] ) ? (string) $price['tone'] : '';
							if ( $tone !== 'crimea' && $tone !== 'new' ) {
								$tone = ( $index === 0 ) ? 'crimea' : 'new';
							}
							$name = sabona_del_str( isset( $price['name'] ) ? $price['name'] : '' );
							$sum  = sabona_del_str( isset( $price['sum'] ) ? $price['sum'] : '' );
							$note = sabona_del_str( isset( $price['note'] ) ? $price['note'] : '' );
							if ( $name === '' && $sum === '' && $note === '' ) {
								continue;
							}
							?>
						<div class="page-del__price page-del__price_<?php echo esc_attr( $tone ); ?>">
							<?php if ( $name !== '' ) { ?>
								<p class="page-del__price-name"><?php echo esc_html( $name ); ?></p>
							<?php } ?>
							<?php if ( $sum !== '' ) { ?>
								<p class="page-del__price-sum"><?php echo esc_html( $sum ); ?></p>
							<?php } ?>
							<?php if ( $note !== '' ) { ?>
								<p class="page-del__price-note"><?php echo esc_html( $note ); ?></p>
							<?php } ?>
						</div>
						<?php } ?>
					</div>
					<?php } ?>

					<?php if ( $days_title !== '' || $days ) { ?>
					<div class="page-del__days-block">
						<?php if ( $days_title !== '' ) { ?>
							<h3 class="page-del__sub"><?php echo esc_html( $days_title ); ?></h3>
						<?php } ?>
						<?php if ( $days ) { ?>
						<ul class="page-del__days">
							<?php foreach ( $days as $day ) {
								if ( ! is_array( $day ) ) {
									continue;
								}
								$city = sabona_del_str( isset( $day['city'] ) ? $day['city'] : '' );
								$when = sabona_del_str( isset( $day['days'] ) ? $day['days'] : '' );
								if ( $city === '' && $when === '' ) {
									continue;
								}
								?>
							<li class="page-del__day"><?php if ( $city !== '' ) { ?><span class="page-del__day-city"><?php echo esc_html( $city ); ?></span><?php } ?><?php echo esc_html( $when ); ?></li>
							<?php } ?>
						</ul>
						<?php } ?>
					</div>
					<?php } ?>
				</article>
				<?php } ?>

				<?php if ( $show_pay ) { ?>
				<article class="page-del__card page-del__card_pay">
					<div class="page-del__card-top">
						<?php if ( $pay_title !== '' ) { ?>
							<h2 class="page-del__card-title"><?php echo esc_html( $pay_title ); ?></h2>
						<?php } ?>
						<?php sabona_del_hours( $del_img, $hours_title, $hours_text ); ?>
					</div>

					<?php if ( $pay_left || $pay_right || $pay_full ) { ?>
					<div class="page-del__pays">
						<?php if ( $pay_left || $pay_right ) { ?>
						<div class="page-del__pays-row">
							<?php if ( $pay_left ) { ?>
							<div class="page-del__pays-col">
								<?php foreach ( $pay_left as $pay ) {
									sabona_del_pay_item( $pay, $del_img );
								} ?>
							</div>
							<?php } ?>
							<?php if ( $pay_right ) { ?>
							<div class="page-del__pays-col">
								<?php foreach ( $pay_right as $pay ) {
									sabona_del_pay_item( $pay, $del_img );
								} ?>
							</div>
							<?php } ?>
						</div>
						<?php } ?>
						<?php foreach ( $pay_full as $pay ) {
							sabona_del_pay_item( $pay, $del_img );
						} ?>
					</div>
					<?php } ?>

					<?php if ( $note_new_title !== '' || $note_new_text !== '' || $note_big_title !== '' || $note_big_text !== '' ) { ?>
					<div class="page-del__notes">
						<?php if ( $note_new_title !== '' ) { ?>
							<h3 class="page-del__sub"><?php echo esc_html( $note_new_title ); ?></h3>
						<?php } ?>
						<?php if ( $note_new_text !== '' ) { ?>
							<p class="page-del__text"><?php echo sabona_del_html( $note_new_text ); ?></p>
						<?php } ?>
						<?php if ( $note_big_title !== '' || $note_big_text !== '' ) { ?>
						<div class="page-del__note">
							<?php if ( $note_big_title !== '' ) { ?>
								<h3 class="page-del__sub"><?php echo esc_html( $note_big_title ); ?></h3>
							<?php } ?>
							<?php if ( $note_big_text !== '' ) { ?>
								<p class="page-del__text"><?php echo sabona_del_html( $note_big_text ); ?></p>
							<?php } ?>
						</div>
						<?php } ?>
					</div>
					<?php } ?>
				</article>
				<?php } ?>
			</div>
			<?php } ?>

			<?php if ( $show_steps ) { ?>
			<div class="page-del__steps-head">
				<?php if ( $steps_title !== '' ) { ?>
					<h2 class="page-del__h2"><?php echo esc_html( $steps_title ); ?></h2>
				<?php } ?>
				<?php if ( $steps_lead !== '' ) { ?>
					<p class="page-del__lead"><?php echo esc_html( $steps_lead ); ?></p>
				<?php } ?>
			</div>

			<?php if ( $steps ) { ?>
			<div class="page-del__steps">
				<?php foreach ( $steps as $index => $step ) {
					if ( ! is_array( $step ) ) {
						continue;
					}
					$title = sabona_del_str( isset( $step['title'] ) ? $step['title'] : '' );
					$text  = sabona_del_str( isset( $step['text'] ) ? $step['text'] : '' );
					if ( $title === '' && $text === '' ) {
						continue;
					}
					$alt = ( $index % 2 === 1 ) ? ' page-del__step_alt' : '';
					?>
				<article class="page-del__step<?php echo esc_attr( $alt ); ?>">
					<div class="page-del__step-top">
						<span class="page-del__step-num"><?php echo (int) $index + 1; ?></span>
						<?php if ( $title !== '' ) { ?>
							<h3 class="page-del__step-title"><?php echo esc_html( $title ); ?></h3>
						<?php } ?>
					</div>
					<?php if ( $text !== '' ) { ?>
						<p class="page-del__step-text"><?php echo sabona_del_html( $text ); ?></p>
					<?php } ?>
				</article>
				<?php } ?>
			</div>
			<?php } ?>
			<?php } ?>

			<?php if ( $show_form ) { ?>
			<form class="page-del__form" action="#" method="post" onsubmit="return false;">
				<div class="page-del__form-head">
					<?php if ( $form_title !== '' ) { ?>
						<h2 class="page-del__form-title"><?php echo esc_html( $form_title ); ?></h2>
					<?php } ?>
					<?php if ( $form_lead !== '' ) { ?>
						<p class="page-del__form-lead"><?php echo sabona_del_html( $form_lead ); ?></p>
					<?php } ?>
				</div>
				<div class="page-del__form-fields">
					<div class="page-del__form-col">
						<input class="page-del__field" type="tel" inputmode="numeric" name="phone" autocomplete="tel" placeholder="<?php echo esc_attr( $form_phone ); ?>" aria-label="<?php echo esc_attr( $form_phone ); ?>">
						<input class="page-del__field" type="text" name="company" autocomplete="organization" placeholder="<?php echo esc_attr( $form_company ); ?>" aria-label="<?php echo esc_attr( $form_company ); ?>">
					</div>
					<div class="page-del__form-col page-del__form-col_area">
						<textarea class="page-del__field page-del__field_area" name="message" placeholder="<?php echo esc_attr( $form_message ); ?>" aria-label="<?php echo esc_attr( $form_message ); ?>"></textarea>
					</div>
				</div>
				<div class="page-del__form-bar">
					<label class="page-del__agree">
						<span class="page-del__agree-box">
							<input class="page-del__agree-input" type="checkbox" checked>
							<img class="page-del__agree-mark" src="<?php echo esc_url( $del_img . 'del-icon-check.svg' ); ?>" alt="" width="20" height="20">
							<span class="page-del__agree-empty" aria-hidden="true"></span>
						</span>
						<?php if ( $form_agree !== '' ) { ?>
							<span class="page-del__agree-text"><?php echo esc_html( $form_agree ); ?></span>
						<?php } ?>
					</label>
					<?php if ( $form_btn !== '' ) { ?>
					<div class="page-del__form-submit">
						<button class="page-del__btn" type="button"><?php echo esc_html( $form_btn ); ?></button>
					</div>
					<?php } ?>
				</div>
			</form>
			<?php } ?>
		</div>
	</div>
</section>
<?php
get_footer();
