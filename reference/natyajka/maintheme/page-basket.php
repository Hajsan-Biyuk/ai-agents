<?php
/*
Template Name: Корзина
*/
get_header();
?>

<section class="page-basket">
			<div class="container">
				<ul class="breadcrumbs">
					<li><a href="#">Главная</a></li>
					<li>Корзина</li>
				</ul>
				<div class="head-basket">
					<div class="row">
						<div class="col-xl-9 col-lg-8">
							<div class="page-basket__left">
								<h1 class="title-section">корзина</h1>
							</div>
						</div>
						<div class="col-xl-3 col-lg-4">
							<div class="page-basket__right">
								<div class="basket-buttons">
									<a href="#" class="btn-light">
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 12L12 18" stroke="#57594F" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /><path d="M15 16L12 18L9 16" stroke="#57594F" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /><path d="M18 21H6C5.44772 21 5 20.5523 5 20L5 4C5 3.44772 5.44772 3 6 3L13.5631 3C13.8416 3 14.1076 3.11619 14.2968 3.32059L18.7338 8.11246C18.9049 8.29731 19 8.53995 19 8.79187L19 20C19 20.5523 18.5523 21 18 21Z" stroke="#57594F" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /><path d="M19 9L14 9C13.4477 9 13 8.55228 13 8L13 3" stroke="#57594F" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg>
										скачать файлом
									</a>
									<a href="#" class="btn-light">
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 17H4C3.44772 17 3 16.5523 3 16L3 8C3 7.44772 3.44772 7 4 7L20 7C20.5523 7 21 7.44771 21 8V16C21 16.5523 20.5523 17 20 17H18" stroke="#57594F" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /><path d="M7 21L17 21C17.5523 21 18 20.5523 18 20V16C18 15.4477 17.5523 15 17 15L7 15C6.44772 15 6 15.4477 6 16L6 20C6 20.5523 6.44772 21 7 21Z" stroke="#57594F" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /><path d="M10 11H14" stroke="#57594F" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /><path d="M7 7L17 7C17.5523 7 18 6.55229 18 6V4C18 3.44772 17.5523 3 17 3L7 3C6.44772 3 6 3.44772 6 4L6 6C6 6.55229 6.44772 7 7 7Z" stroke="#57594F" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xl-9">
						<div class="page-basket__left">
							<div class="item-basket">
								<a href="#" class="item-basket__image">
									<img src="<?php bloginfo('template_directory'); ?>/img/basket1.jpg" alt="alt">
								</a>
								<div class="item-basket__content">
									<a href="#" class="item-basket__title">Туя западная Смарагд</a>
									<div class="item-basket__text">Полувечнозелёный раскидистый кустарник высотой, часто поникающими ветвями.</div>
								</div>
								<div class="item-basket__prices">
									<div class="price-main">15 900 р</div>
									<div class="descr-price">Цена за 1 шт</div>
								</div>
								<div class="quantity">
									<div class="quantity-button quantity-down"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 12H18" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg></div>
									<input type="number" min="1" step="1" value="1">
									<div class="quantity-button quantity-up"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 12H18" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /><path d="M12 18L12 6" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg></div>
								</div>
								<div class="item-basket__total">
									<div class="price-main">15 900 р</div>
								</div>
								<div class="item-basket__remove"><i class="far fa-trash"></i></div>
							</div>
							<div class="item-basket">
								<a href="#" class="item-basket__image">
									<img src="<?php bloginfo('template_directory'); ?>/img/basket1.jpg" alt="alt">
								</a>
								<div class="item-basket__content">
									<a href="#" class="item-basket__title">Туя западная Смарагд</a>
									<div class="item-basket__text">Полувечнозелёный раскидистый кустарник высотой, часто поникающими ветвями.</div>
								</div>
								<div class="item-basket__prices">
									<div class="price-main">15 900 р</div>
									<div class="descr-price">Цена за 1 шт</div>
								</div>
								<div class="quantity">
									<div class="quantity-button quantity-down"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 12H18" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg></div>
									<input type="number" min="1" step="1" value="1">
									<div class="quantity-button quantity-up"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 12H18" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /><path d="M12 18L12 6" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg></div>
								</div>
								<div class="item-basket__total">
									<div class="price-main">15 900 р</div>
								</div>
								<div class="item-basket__remove"><i class="far fa-trash"></i></div>
							</div>
							<div class="item-basket">
								<a href="#" class="item-basket__image">
									<img src="<?php bloginfo('template_directory'); ?>/img/basket1.jpg" alt="alt">
								</a>
								<div class="item-basket__content">
									<a href="#" class="item-basket__title">Туя западная Смарагд</a>
									<div class="item-basket__text">Полувечнозелёный раскидистый кустарник высотой, часто поникающими ветвями.</div>
								</div>
								<div class="item-basket__prices">
									<div class="price-main">15 900 р</div>
									<div class="descr-price">Цена за 1 шт</div>
								</div>
								<div class="quantity">
									<div class="quantity-button quantity-down"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 12H18" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg></div>
									<input type="number" min="1" step="1" value="1">
									<div class="quantity-button quantity-up"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 12H18" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /><path d="M12 18L12 6" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg></div>
								</div>
								<div class="item-basket__total">
									<div class="price-main">15 900 р</div>
								</div>
								<div class="item-basket__remove"><i class="far fa-trash"></i></div>
							</div>
							<div class="item-basket">
								<a href="#" class="item-basket__image">
									<img src="<?php bloginfo('template_directory'); ?>/img/basket1.jpg" alt="alt">
								</a>
								<div class="item-basket__content">
									<a href="#" class="item-basket__title">Туя западная Смарагд</a>
									<div class="item-basket__text">Полувечнозелёный раскидистый кустарник высотой, часто поникающими ветвями.</div>
								</div>
								<div class="item-basket__prices">
									<div class="price-main">15 900 р</div>
									<div class="descr-price">Цена за 1 шт</div>
								</div>
								<div class="quantity">
									<div class="quantity-button quantity-down"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 12H18" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg></div>
									<input type="number" min="1" step="1" value="1">
									<div class="quantity-button quantity-up"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 12H18" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /><path d="M12 18L12 6" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg></div>
								</div>
								<div class="item-basket__total">
									<div class="price-main">15 900 р</div>
								</div>
								<div class="item-basket__remove"><i class="far fa-trash"></i></div>
							</div>
							<div class="item-basket">
								<a href="#" class="item-basket__image">
									<img src="<?php bloginfo('template_directory'); ?>/img/basket1.jpg" alt="alt">
								</a>
								<div class="item-basket__content">
									<a href="#" class="item-basket__title">Туя западная Смарагд</a>
									<div class="item-basket__text">Полувечнозелёный раскидистый кустарник высотой, часто поникающими ветвями.</div>
								</div>
								<div class="item-basket__prices">
									<div class="price-main">15 900 р</div>
									<div class="descr-price">Цена за 1 шт</div>
								</div>
								<div class="quantity">
									<div class="quantity-button quantity-down"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 12H18" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg></div>
									<input type="number" min="1" step="1" value="1">
									<div class="quantity-button quantity-up"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 12H18" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /><path d="M12 18L12 6" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg></div>
								</div>
								<div class="item-basket__total">
									<div class="price-main">15 900 р</div>
								</div>
								<div class="item-basket__remove"><i class="far fa-trash"></i></div>
							</div>
						</div>
					</div>
					<div class="col-xl-3">
						<div class="page-basket__right">
							<div class="basket-sidebar">
								<div class="basket-sidebar__content">
									<div class="basket-sidebar__title">Итого:</div>
									<div class="price-main">79 500 р</div>
									<div class="basket-sidebar__descr">Сумма НДС: 302 р</div>
								</div>
								<div class="basket-sidebar__buttons">
									<a href="#" class="btn-page">оформить заказ</a>
									<a href="#" class="btn-page btn-page--main">быстрый заказ</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

<?php
get_footer();