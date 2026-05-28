<?php
/**
 * Wholesale page — заявка
 *
 * @package maintheme
 */
$t        = get_template_directory_uri();
$sect     = get_field( 'zayavka' );
$pochta   = maintheme_get_option( 'pochta' );
$telefon  = maintheme_get_option( 'telefon' );
$adres    = maintheme_get_option( 'polnyj_adres' );
$politika = maintheme_get_option( 'politika' );
$soglasie = maintheme_get_option( 'soglasie' );

if ( ! maintheme_section_show( $sect ) ) {
	return;
}
?>
<section class="wholesale-page-request">
	<div class="container-fluid container-main">
		<div class="row wholesale-page-request__row">
			<div class="col-lg-5 wholesale-page-request__info">
				<?php if ( maintheme_is_filled( $sect['zagolovok'] ?? '' ) || maintheme_is_filled( $sect['opisanie'] ?? '' ) ) { ?>
					<div class="head-section">
						<?php if ( maintheme_is_filled( $sect['zagolovok'] ?? '' ) ) { ?>
							<h2 class="title-section"><?php echo esc_html( $sect['zagolovok'] ); ?></h2>
						<?php } ?>
						<?php if ( maintheme_is_filled( $sect['opisanie'] ?? '' ) ) { ?>
							<p class="descr-section"><?php echo esc_html( $sect['opisanie'] ); ?></p>
						<?php } ?>
					</div>
				<?php } ?>
				<?php if ( $telefon || $pochta || $adres ) { ?>
					<div class="wholesale-page-request__contacts">
						<?php if ( $telefon ) { ?>
							<div class="wholesale-page-request__contact">
								<span class="wholesale-page-request__contact-icon">
									<img src="<?php echo esc_url( $t ); ?>/icons/Group%204595.svg" width="48" height="48" alt="">
								</span>
								<div class="wholesale-page-request__contact-body">
									<span class="wholesale-page-request__contact-label">Телефон:</span>
									<a href="tel:<?php echo esc_attr( maintheme_phone_href( $telefon ) ); ?>" class="wholesale-page-request__contact-value"><?php echo esc_html( $telefon ); ?></a>
								</div>
							</div>
						<?php } ?>
						<?php if ( $pochta ) { ?>
							<div class="wholesale-page-request__contact">
								<span class="wholesale-page-request__contact-icon">
									<img src="<?php echo esc_url( $t ); ?>/icons/Group%204596.svg" width="48" height="48" alt="">
								</span>
								<div class="wholesale-page-request__contact-body">
									<span class="wholesale-page-request__contact-label">Почта:</span>
									<a href="mailto:<?php echo esc_attr( $pochta ); ?>" class="wholesale-page-request__contact-value"><?php echo esc_html( $pochta ); ?></a>
								</div>
							</div>
						<?php } ?>
						<?php if ( $adres ) { ?>
							<div class="wholesale-page-request__contact">
								<span class="wholesale-page-request__contact-icon">
									<img src="<?php echo esc_url( $t ); ?>/icons/Group%204597.svg" width="48" height="48" alt="">
								</span>
								<div class="wholesale-page-request__contact-body">
									<span class="wholesale-page-request__contact-label">Адрес:</span>
									<span class="wholesale-page-request__contact-value"><?php echo esc_html( $adres ); ?></span>
								</div>
							</div>
						<?php } ?>
					</div>
				<?php } ?>
			</div>
			<div class="col-lg-7 wholesale-page-request__form-col">
				<div class="wholesale-page-request__form">
					<?php if ( maintheme_is_filled( $sect['zagolovok_formy'] ?? '' ) ) { ?>
						<div class="wholesale-page-request__form-title"><?php echo esc_html( $sect['zagolovok_formy'] ); ?></div>
					<?php } ?>
					<form action="#" method="post">
						<div class="row">
							<div class="col-md-6">
								<div class="item-form">
									<label>Имя*</label>
									<input type="text" name="name" placeholder="Иван Иванов" required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="item-form">
									<label>Компания</label>
									<input type="text" name="company" placeholder="ООО Агро">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="item-form">
									<label>Телефон*</label>
									<input type="tel" name="phone" class="input-phone" inputmode="numeric" placeholder="+7 (978) 000-00-00" required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="item-form">
									<label>Почта</label>
									<input type="email" name="email" placeholder="mail@mail.ru">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="item-form">
									<label>Интересующие товары</label>
									<input type="text" name="products" placeholder="Удобрения, СЗР...">
								</div>
							</div>
							<div class="col-md-6">
								<div class="item-form">
									<label>Примерный объём</label>
									<input type="text" name="volume" placeholder="1000 кг">
								</div>
							</div>
						</div>
						<div class="item-form">
							<label>Комментарий</label>
							<textarea name="comment" placeholder="Уточните детали или задайте вопрос..."></textarea>
						</div>
						<div class="row align-items-center">
							<div class="col-lg">
								<div class="checkbox">
									<label>
										<input type="checkbox" required checked>
										<span>
											Подтверждаю, что ознакомлен(а) с условиями
											<?php if ( $politika ) { ?>
												<a href="<?php echo esc_url( $politika ); ?>">Политики конфиденциальности</a>
											<?php } else { ?>
												Политики конфиденциальности
											<?php } ?>
											<?php if ( $politika && $soglasie ) { ?>
												и
											<?php } ?>
											<?php if ( $soglasie ) { ?>
												<a href="<?php echo esc_url( $soglasie ); ?>">Политики в области обработки персональных данных</a>, даю свое <a href="<?php echo esc_url( $soglasie ); ?>">согласие на обработку персональных данных</a>
											<?php } ?>
										</span>
									</label>
								</div>
							</div>
							<div class="col-lg-auto">
								<button type="submit" class="btn-main">Отправить заявку <img src="<?php echo esc_url( $t ); ?>/img/icon-arrow.svg" alt=""></button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>
