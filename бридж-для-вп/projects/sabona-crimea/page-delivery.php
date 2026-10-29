<?php
/*
Template Name: Доставка
*/
add_action( 'wp_enqueue_scripts', function () {
	$uri = get_template_directory_uri();
	wp_enqueue_style( 'sabona-delivery', $uri . '/delivery.css', array(), '1.0' );
	wp_enqueue_style( 'sabona-delivery-media', $uri . '/delivery-media.css', array( 'sabona-delivery' ), '1.0' );
}, 20 );

get_header();

$del_img = get_template_directory_uri() . '/img/';
?>
<section class="page-del">
	<div class="container">
		<div class="page-del__body">
			<nav class="page-del__crumbs" aria-label="Хлебные крошки">
				<a href="/">Главная</a><span class="page-del__crumbs-sep" aria-hidden="true"> › </span><span aria-current="page">Доставка и оплата</span>
			</nav>

			<h1 class="page-del__title">Доставка и оплата</h1>

			<div class="page-del__cards">
				<article class="page-del__card">
					<div class="page-del__card-top">
						<h2 class="page-del__card-title">Условия доставки</h2>
						<div class="page-del__hours">
							<img class="page-del__hours-ico" src="<?php echo esc_url( $del_img . 'del-icon-time.svg' ); ?>" alt="" width="36" height="36">
							<div class="page-del__hours-copy">
								<h3 class="page-del__hours-title">Прием заказов</h3>
								<p class="page-del__hours-text">Понедельник — Пятница с 8:00 до 17:00</p>
							</div>
						</div>
					</div>

					<div class="page-del__free">
						<h3 class="page-del__sub">Доставка бесплатная</h3>
						<p class="page-del__text">осуществляется нашим собственным транспортом со склада в г. Симферополь.</p>
					</div>

					<div class="page-del__prices">
						<div class="page-del__price page-del__price_crimea">
							<p class="page-del__price-name">По Крыму</p>
							<p class="page-del__price-sum">от 3&nbsp;000&nbsp;₽</p>
							<p class="page-del__price-note">Минимальная сумма для бесплатной доставки</p>
						</div>
						<div class="page-del__price page-del__price_new">
							<p class="page-del__price-name">Новые территории</p>
							<p class="page-del__price-sum">от 20&nbsp;000&nbsp;₽</p>
							<p class="page-del__price-note">Минимальная сумма для бесплатной доставки</p>
						</div>
					</div>

					<div class="page-del__days-block">
						<h3 class="page-del__sub">Дни доставки</h3>
						<ul class="page-del__days">
							<li class="page-del__day"><span class="page-del__day-city">г. Симферополь – </span>каждый день</li>
							<li class="page-del__day"><span class="page-del__day-city">г. Севастополь – </span>вторник, среда, пятница</li>
							<li class="page-del__day"><span class="page-del__day-city">г. Керчь, г. Феодосия и г. Судак – </span>среда, четверг</li>
							<li class="page-del__day"><span class="page-del__day-city">г. Ялта и г. Алушта – </span>четверг</li>
							<li class="page-del__day"><span class="page-del__day-city">г. Евпатория и г. Саки – </span>пятница</li>
							<li class="page-del__day"><span class="page-del__day-city">Новые территории – </span>по согласованию</li>
						</ul>
					</div>
				</article>

				<article class="page-del__card page-del__card_pay">
					<div class="page-del__card-top">
						<h2 class="page-del__card-title">Способы оплаты</h2>
						<div class="page-del__hours">
							<img class="page-del__hours-ico" src="<?php echo esc_url( $del_img . 'del-icon-time.svg' ); ?>" alt="" width="36" height="36">
							<div class="page-del__hours-copy">
								<h3 class="page-del__hours-title">Прием заказов</h3>
								<p class="page-del__hours-text">Понедельник — Пятница с 8:00 до 17:00</p>
							</div>
						</div>
					</div>

					<div class="page-del__pays">
						<div class="page-del__pays-row">
							<div class="page-del__pays-col">
								<div class="page-del__pay">
									<span class="page-del__pay-ico page-del__pay-ico_cash">
										<img src="<?php echo esc_url( $del_img . 'del-icon-cash.svg' ); ?>" alt="" width="46" height="46">
									</span>
									<p class="page-del__pay-text">Наличный<br>расчет</p>
								</div>
								<div class="page-del__pay">
									<span class="page-del__pay-ico">
										<img src="<?php echo esc_url( $del_img . 'del-icon-exchange.svg' ); ?>" alt="" width="24" height="24">
									</span>
									<p class="page-del__pay-text">Безналичный расчет с НДС и без НДС</p>
								</div>
								<div class="page-del__pay">
									<span class="page-del__pay-ico">
										<img src="<?php echo esc_url( $del_img . 'del-icon-wallet.svg' ); ?>" alt="" width="24" height="24">
									</span>
									<p class="page-del__pay-text">Предоплата<br>за заказ</p>
								</div>
							</div>
							<div class="page-del__pays-col">
								<div class="page-del__pay">
									<span class="page-del__pay-ico">
										<img src="<?php echo esc_url( $del_img . 'del-icon-card.svg' ); ?>" alt="" width="24" height="24">
									</span>
									<p class="page-del__pay-text">Оплата банковской картой,</p>
								</div>
								<div class="page-del__pay">
									<span class="page-del__pay-ico">
										<img src="<?php echo esc_url( $del_img . 'del-icon-invoice.svg' ); ?>" alt="" width="24" height="24">
									</span>
									<p class="page-del__pay-text">Оплата по счёту для юридических лиц и ИП</p>
								</div>
								<div class="page-del__pay">
									<span class="page-del__pay-ico">
										<img src="<?php echo esc_url( $del_img . 'del-icon-qr.svg' ); ?>" alt="" width="24" height="24">
									</span>
									<p class="page-del__pay-text">Оплата по QR-коду через СБП</p>
								</div>
							</div>
						</div>
						<div class="page-del__pay">
							<span class="page-del__pay-ico">
								<img src="<?php echo esc_url( $del_img . 'del-icon-transfer.svg' ); ?>" alt="" width="24" height="24">
							</span>
							<p class="page-del__pay-text">Безналичный перевод по реквизитам</p>
						</div>
					</div>

					<div class="page-del__notes">
						<h3 class="page-del__sub">Для новых клиентов и товаров</h3>
						<p class="page-del__text">изготовленных или зарезервированных специально под заказ — частичная предоплата.</p>
						<div class="page-del__note">
							<h3 class="page-del__sub">Для крупных партий:</h3>
							<p class="page-del__text">часть суммы оплачивается при оформлении, остаток — перед отгрузкой или после согласования условий, индивидуальные условия для дилеров и крупных заказов</p>
						</div>
					</div>
				</article>
			</div>

			<div class="page-del__steps-head">
				<h2 class="page-del__h2">Как мы работаем:</h2>
				<p class="page-del__lead">5 простых шагов от заказа до получения</p>
			</div>

			<div class="page-del__steps">
				<article class="page-del__step">
					<div class="page-del__step-top">
						<span class="page-del__step-num">1</span>
						<h3 class="page-del__step-title">Оформление заказа и резерв товара</h3>
					</div>
					<p class="page-del__step-text">Соберите корзину на сайте или отправьте заявку с реквизитами персональному менеджеру по почте или в мессенджер. Мы проверим наличие и забронируем товар.</p>
				</article>
				<article class="page-del__step page-del__step_alt">
					<div class="page-del__step-top">
						<span class="page-del__step-num">2</span>
						<h3 class="page-del__step-title">Согласование условий и счёт</h3>
					</div>
					<p class="page-del__step-text">Подтвердим объемы, обсудим индивидуальные скидки и условия доставки. При первой закупке оформим договор и выставим счет. Цены фиксируются в момент выставления счета.</p>
				</article>
				<article class="page-del__step">
					<div class="page-del__step-top">
						<span class="page-del__step-num">3</span>
						<h3 class="page-del__step-title">Оплата заказа</h3>
					</div>
					<p class="page-del__step-text">Оплатите по безналичному расчету для юрлиц и ИП или другим согласованным способом. После поступления средств заказ передается на сборку.</p>
				</article>
				<article class="page-del__step page-del__step_alt">
					<div class="page-del__step-top">
						<span class="page-del__step-num">4</span>
						<h3 class="page-del__step-title">Комплектация и отгрузка</h3>
					</div>
					<p class="page-del__step-text">Бережно упакуем товар по стандартам транспортировки и подготовим закрывающие документы. Отгрузка — в течение 24–48 часов после оплаты.</p>
				</article>
				<article class="page-del__step">
					<div class="page-del__step-top">
						<span class="page-del__step-num">5</span>
						<h3 class="page-del__step-title">Доставка и приемка груза</h3>
					</div>
					<p class="page-del__step-text">Доставим заказ на ваш склад, в магазин или другое согласованное место. При получении проверьте упаковку, подпишите документы и заберите заказ.</p>
				</article>
			</div>

			<form class="page-del__form" action="#" method="post" onsubmit="return false;">
				<div class="page-del__form-head">
					<h2 class="page-del__form-title">Соберите стартовую заявку по своему меню</h2>
					<p class="page-del__form-lead">Соберите стартовую заявку по своему меню в полном каталоге товаров - или запросите подбор ассортимента у менеджера.</p>
				</div>
				<div class="page-del__form-fields">
					<div class="page-del__form-col">
						<input class="page-del__field" type="tel" inputmode="numeric" name="phone" autocomplete="tel" placeholder="Ваш телефон" aria-label="Ваш телефон">
						<input class="page-del__field" type="text" name="company" autocomplete="organization" placeholder="Название компании" aria-label="Название компании">
					</div>
					<div class="page-del__form-col page-del__form-col_area">
						<textarea class="page-del__field page-del__field_area" name="message" placeholder="Что хотите упаковать: бургеры, выпечка, готовые обеды, напитки, суши" aria-label="Что хотите упаковать: бургеры, выпечка, готовые обеды, напитки, суши"></textarea>
					</div>
				</div>
				<div class="page-del__form-bar">
					<label class="page-del__agree">
						<span class="page-del__agree-box">
							<input class="page-del__agree-input" type="checkbox" checked>
							<img class="page-del__agree-mark" src="<?php echo esc_url( $del_img . 'del-icon-check.svg' ); ?>" alt="" width="20" height="20">
							<span class="page-del__agree-empty" aria-hidden="true"></span>
						</span>
						<span class="page-del__agree-text">Нажимая кнопку, вы соглашаетесь на обработку персональных данных и получение обратной связи по заявке.</span>
					</label>
					<div class="page-del__form-submit">
						<button class="page-del__btn" type="button">Запросить подбор ассортимента</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</section>
<?php
get_footer();
