<?php
/*
Template Name: Каталог
*/
get_header();
?>

<section class="page-catalog">
			<div class="container">
				<ul class="breadcrumbs">
					<li><a href="#">Главная</a></li>
					<li>Каталог</li>
				</ul>
				<div class="row">
					<div class="col-lg-3">
						<h1 class="title-catalog">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14 18C14 17.4477 14.4477 17 15 17C15.5523 17 16 17.4477 16 18C16 18.5523 15.5523 19 15 19C14.4477 19 14 18.5523 14 18Z" stroke="#7A9C52" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /><path d="M8 18C8 17.4477 8.44772 17 9 17C9.55228 17 10 17.4477 10 18C10 18.5523 9.55228 19 9 19C8.44772 19 8 18.5523 8 18Z" stroke="#7A9C52" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /><path d="M14 12C14 11.4477 14.4477 11 15 11C15.5523 11 16 11.4477 16 12C16 12.5523 15.5523 13 15 13C14.4477 13 14 12.5523 14 12Z" stroke="#7A9C52" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /><path d="M8 12C8 11.4477 8.44772 11 9 11C9.55228 11 10 11.4477 10 12C10 12.5523 9.55228 13 9 13C8.44772 13 8 12.5523 8 12Z" stroke="#7A9C52" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /><path d="M14 6C14 5.44772 14.4477 5 15 5C15.5523 5 16 5.44772 16 6C16 6.55228 15.5523 7 15 7C14.4477 7 14 6.55228 14 6Z" stroke="#7A9C52" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /><path d="M8 6C8 5.44772 8.44772 5 9 5C9.55228 5 10 5.44772 10 6C10 6.55228 9.55228 7 9 7C8.44772 7 8 6.55228 8 6Z" stroke="#7A9C52" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
							каталог 
							<i class="far fa-filter"></i>
						</h1>
						<ul class="nav-catalog">
							<li><a href="#">РАСТЕНИЯ</a></li>
							<li><a href="#">ГОРШКИ И КАШПО</a></li>
							<li><a href="#">САДОВЫЙ ИНСТРУМЕНТ и СИСТЕМЫ ПОЛИВА</a></li>
							<li><a href="#">ОПОРЫ. РЕШЕТКИ. ДЕРЖАТЕЛИ. УКРЫВНОЙ МАТЕРИАЛ</a></li>
							<li><a href="#">ГРУНТЫ. СУБСТРАТЫ. ДРЕНАЖИ</a></li>
							<li><a href="#">СРЕДСТВА ЗАЩИТЫ РАСТЕНИЙ. ГЕРБИЦИДЫ</a></li>
							<li><a href="#">УДОБРЕНИЯ</a></li>
							<li><a href="#">ГОТОВЫЕ КОМПОЗИЦИИ</a></li>
						</ul>
					</div>
					<div class="col-lg-9">
						<div class="search-page">
							<form>
								<input type="text" placeholder="Поиск" required>
								<button class="search-page__btn"></button>
							</form>
						</div>
						<div class="row row--main">
							<div class="col-lg-4 col-md-6">
								<div class="item-card">
									<div class="card-action">
										<a href="#" class="btn-card btn-card--favorites">
											<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 20C12 20 3 14.9875 3 8.97234C3 2.95722 10 2.45597 12 7.16221C14 2.45597 21 2.95722 21 8.97234C21 14.9875 12 20 12 20Z" stroke="#3F5E29" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg>
										</a>
										<a href="#" class="btn-card btn-card--basket">
											<svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_2515_922)"><path d="M21.9994 21.2866L21.1815 6.87822C21.1614 6.52289 20.8677 6.24578 20.512 6.24578H17.6635V3.8899C17.6635 1.74767 15.9209 0.00500488 13.7786 0.00500488C11.6364 0.00500488 9.89375 1.74767 9.89375 3.8899V6.24578H7.18969C6.83392 6.24578 6.54027 6.52289 6.52016 6.87822L5.70224 21.2866C5.6807 21.6728 5.98811 21.995 6.37177 21.995H21.3299C21.7147 21.995 22.0209 21.6711 21.9994 21.2866ZM11.2346 3.8899C11.2346 2.48692 12.3761 1.34586 13.7786 1.34586C15.1812 1.34586 16.3227 2.48692 16.3227 3.8899V6.24578H11.2346V3.8899ZM7.08108 20.6541L7.82302 7.58664H9.89375V9.99257C9.89375 10.3626 10.1941 10.663 10.5642 10.663C10.9347 10.663 11.2346 10.3626 11.2346 9.99257V7.58664H16.3227V9.99257C16.3227 10.3626 16.6226 10.663 16.9931 10.663C17.3632 10.663 17.6635 10.3626 17.6635 9.99257V7.58664H19.8786L20.6201 20.6541H7.08108Z" fill="#3F5E29" /><path d="M4.21466 10.3296H0.670427C0.300172 10.3296 0 10.6298 0 11C0 11.3703 0.300172 11.6704 0.670427 11.6704H4.21466C4.58491 11.6704 4.88509 11.3703 4.88509 11C4.88509 10.6298 4.58491 10.3296 4.21466 10.3296Z" fill="#3F5E29" /><path d="M4.21533 13.6557H2.04543C1.67517 13.6557 1.375 13.9558 1.375 14.3261C1.375 14.6964 1.67517 14.9965 2.04543 14.9965H4.21533C4.58558 14.9965 4.88576 14.6964 4.88576 14.3261C4.88576 13.9558 4.58558 13.6557 4.21533 13.6557Z" fill="#3F5E29" /><path d="M4.21466 16.9818H0.670427C0.300172 16.9818 0 17.2819 0 17.6522C0 18.0224 0.300172 18.3226 0.670427 18.3226H4.21466C4.58491 18.3226 4.88509 18.0224 4.88509 17.6522C4.88509 17.2819 4.58491 16.9818 4.21466 16.9818Z" fill="#3F5E29" /></g><defs><clipPath id="clip0_2515_922"><rect width="22" height="22" fill="white" /></clipPath></defs></svg>
										</a>
									</div>
									<a href="#" class="item-card__image">
										<img src="<?php bloginfo('template_directory'); ?>/img/item_catalog1.jpg" alt="alt">
									</a>
									<div class="item-card__content">
										<div class="item-card__top">
											<div class="item-card__articul">Арт.: ПА-00001005</div>
											<div class="availability">В наличии</div>
										</div>
										<a href="#" class="item-card__title">Смолосемянник тонколистный «Tom Thumb»</a>
										<div class="item-card__prices">
											<div class="price-old">6 500 р</div>
											<div class="price-main">5 250 р</div>
										</div>
										<div class="item-card__bottom">
											<div class="quantity">
												<div class="quantity-button quantity-down"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 12H18" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg></div>
												<input type="number" min="1" step="1" value="1">
												<div class="quantity-button quantity-up"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 12H18" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /><path d="M12 18L12 6" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg></div>
											</div>
											<a href="#" class="btn-page">в корзину</a>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-4 col-md-6">
								<div class="item-card">
									<div class="card-action">
										<a href="#" class="btn-card btn-card--favorites">
											<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 20C12 20 3 14.9875 3 8.97234C3 2.95722 10 2.45597 12 7.16221C14 2.45597 21 2.95722 21 8.97234C21 14.9875 12 20 12 20Z" stroke="#3F5E29" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg>
										</a>
										<a href="#" class="btn-card btn-card--basket">
											<svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_2515_922)"><path d="M21.9994 21.2866L21.1815 6.87822C21.1614 6.52289 20.8677 6.24578 20.512 6.24578H17.6635V3.8899C17.6635 1.74767 15.9209 0.00500488 13.7786 0.00500488C11.6364 0.00500488 9.89375 1.74767 9.89375 3.8899V6.24578H7.18969C6.83392 6.24578 6.54027 6.52289 6.52016 6.87822L5.70224 21.2866C5.6807 21.6728 5.98811 21.995 6.37177 21.995H21.3299C21.7147 21.995 22.0209 21.6711 21.9994 21.2866ZM11.2346 3.8899C11.2346 2.48692 12.3761 1.34586 13.7786 1.34586C15.1812 1.34586 16.3227 2.48692 16.3227 3.8899V6.24578H11.2346V3.8899ZM7.08108 20.6541L7.82302 7.58664H9.89375V9.99257C9.89375 10.3626 10.1941 10.663 10.5642 10.663C10.9347 10.663 11.2346 10.3626 11.2346 9.99257V7.58664H16.3227V9.99257C16.3227 10.3626 16.6226 10.663 16.9931 10.663C17.3632 10.663 17.6635 10.3626 17.6635 9.99257V7.58664H19.8786L20.6201 20.6541H7.08108Z" fill="#3F5E29" /><path d="M4.21466 10.3296H0.670427C0.300172 10.3296 0 10.6298 0 11C0 11.3703 0.300172 11.6704 0.670427 11.6704H4.21466C4.58491 11.6704 4.88509 11.3703 4.88509 11C4.88509 10.6298 4.58491 10.3296 4.21466 10.3296Z" fill="#3F5E29" /><path d="M4.21533 13.6557H2.04543C1.67517 13.6557 1.375 13.9558 1.375 14.3261C1.375 14.6964 1.67517 14.9965 2.04543 14.9965H4.21533C4.58558 14.9965 4.88576 14.6964 4.88576 14.3261C4.88576 13.9558 4.58558 13.6557 4.21533 13.6557Z" fill="#3F5E29" /><path d="M4.21466 16.9818H0.670427C0.300172 16.9818 0 17.2819 0 17.6522C0 18.0224 0.300172 18.3226 0.670427 18.3226H4.21466C4.58491 18.3226 4.88509 18.0224 4.88509 17.6522C4.88509 17.2819 4.58491 16.9818 4.21466 16.9818Z" fill="#3F5E29" /></g><defs><clipPath id="clip0_2515_922"><rect width="22" height="22" fill="white" /></clipPath></defs></svg>
										</a>
									</div>
									<a href="#" class="item-card__image">
										<img src="<?php bloginfo('template_directory'); ?>/img/item_catalog2.jpg" alt="alt">
									</a>
									<div class="item-card__content">
										<div class="item-card__top">
											<div class="item-card__articul">Арт.: ПА-00001005</div>
											<div class="availability">В наличии</div>
										</div>
										<a href="#" class="item-card__title">Нандина домашняя «Lemon-Lime»</a>
										<div class="item-card__prices">
											<div class="price-old">6 500 р</div>
											<div class="price-main">5 250 р</div>
										</div>
										<div class="item-card__bottom">
											<div class="quantity">
												<div class="quantity-button quantity-down"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 12H18" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg></div>
												<input type="number" min="1" step="1" value="1">
												<div class="quantity-button quantity-up"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 12H18" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /><path d="M12 18L12 6" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg></div>
											</div>
											<a href="#" class="btn-page">в корзину</a>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-4 col-md-6">
								<div class="item-card">
									<div class="card-action">
										<a href="#" class="btn-card btn-card--favorites">
											<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 20C12 20 3 14.9875 3 8.97234C3 2.95722 10 2.45597 12 7.16221C14 2.45597 21 2.95722 21 8.97234C21 14.9875 12 20 12 20Z" stroke="#3F5E29" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg>
										</a>
										<a href="#" class="btn-card btn-card--basket">
											<svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_2515_922)"><path d="M21.9994 21.2866L21.1815 6.87822C21.1614 6.52289 20.8677 6.24578 20.512 6.24578H17.6635V3.8899C17.6635 1.74767 15.9209 0.00500488 13.7786 0.00500488C11.6364 0.00500488 9.89375 1.74767 9.89375 3.8899V6.24578H7.18969C6.83392 6.24578 6.54027 6.52289 6.52016 6.87822L5.70224 21.2866C5.6807 21.6728 5.98811 21.995 6.37177 21.995H21.3299C21.7147 21.995 22.0209 21.6711 21.9994 21.2866ZM11.2346 3.8899C11.2346 2.48692 12.3761 1.34586 13.7786 1.34586C15.1812 1.34586 16.3227 2.48692 16.3227 3.8899V6.24578H11.2346V3.8899ZM7.08108 20.6541L7.82302 7.58664H9.89375V9.99257C9.89375 10.3626 10.1941 10.663 10.5642 10.663C10.9347 10.663 11.2346 10.3626 11.2346 9.99257V7.58664H16.3227V9.99257C16.3227 10.3626 16.6226 10.663 16.9931 10.663C17.3632 10.663 17.6635 10.3626 17.6635 9.99257V7.58664H19.8786L20.6201 20.6541H7.08108Z" fill="#3F5E29" /><path d="M4.21466 10.3296H0.670427C0.300172 10.3296 0 10.6298 0 11C0 11.3703 0.300172 11.6704 0.670427 11.6704H4.21466C4.58491 11.6704 4.88509 11.3703 4.88509 11C4.88509 10.6298 4.58491 10.3296 4.21466 10.3296Z" fill="#3F5E29" /><path d="M4.21533 13.6557H2.04543C1.67517 13.6557 1.375 13.9558 1.375 14.3261C1.375 14.6964 1.67517 14.9965 2.04543 14.9965H4.21533C4.58558 14.9965 4.88576 14.6964 4.88576 14.3261C4.88576 13.9558 4.58558 13.6557 4.21533 13.6557Z" fill="#3F5E29" /><path d="M4.21466 16.9818H0.670427C0.300172 16.9818 0 17.2819 0 17.6522C0 18.0224 0.300172 18.3226 0.670427 18.3226H4.21466C4.58491 18.3226 4.88509 18.0224 4.88509 17.6522C4.88509 17.2819 4.58491 16.9818 4.21466 16.9818Z" fill="#3F5E29" /></g><defs><clipPath id="clip0_2515_922"><rect width="22" height="22" fill="white" /></clipPath></defs></svg>
										</a>
									</div>
									<a href="#" class="item-card__image">
										<img src="<?php bloginfo('template_directory'); ?>/img/item_catalog3.jpg" alt="alt">
									</a>
									<div class="item-card__content">
										<div class="item-card__top">
											<div class="item-card__articul">Арт.: ПА-00001005</div>
											<div class="availability">В наличии</div>
										</div>
										<a href="#" class="item-card__title">Абелия крупноцветковая «Compacta»</a>
										<div class="item-card__prices">
											<div class="price-old">6 500 р</div>
											<div class="price-main">5 250 р</div>
										</div>
										<div class="item-card__bottom">
											<div class="quantity">
												<div class="quantity-button quantity-down"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 12H18" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg></div>
												<input type="number" min="1" step="1" value="1">
												<div class="quantity-button quantity-up"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 12H18" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /><path d="M12 18L12 6" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg></div>
											</div>
											<a href="#" class="btn-page">в корзину</a>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-4 col-md-6">
								<div class="item-card">
									<div class="card-action">
										<a href="#" class="btn-card btn-card--favorites">
											<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 20C12 20 3 14.9875 3 8.97234C3 2.95722 10 2.45597 12 7.16221C14 2.45597 21 2.95722 21 8.97234C21 14.9875 12 20 12 20Z" stroke="#3F5E29" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg>
										</a>
										<a href="#" class="btn-card btn-card--basket">
											<svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_2515_922)"><path d="M21.9994 21.2866L21.1815 6.87822C21.1614 6.52289 20.8677 6.24578 20.512 6.24578H17.6635V3.8899C17.6635 1.74767 15.9209 0.00500488 13.7786 0.00500488C11.6364 0.00500488 9.89375 1.74767 9.89375 3.8899V6.24578H7.18969C6.83392 6.24578 6.54027 6.52289 6.52016 6.87822L5.70224 21.2866C5.6807 21.6728 5.98811 21.995 6.37177 21.995H21.3299C21.7147 21.995 22.0209 21.6711 21.9994 21.2866ZM11.2346 3.8899C11.2346 2.48692 12.3761 1.34586 13.7786 1.34586C15.1812 1.34586 16.3227 2.48692 16.3227 3.8899V6.24578H11.2346V3.8899ZM7.08108 20.6541L7.82302 7.58664H9.89375V9.99257C9.89375 10.3626 10.1941 10.663 10.5642 10.663C10.9347 10.663 11.2346 10.3626 11.2346 9.99257V7.58664H16.3227V9.99257C16.3227 10.3626 16.6226 10.663 16.9931 10.663C17.3632 10.663 17.6635 10.3626 17.6635 9.99257V7.58664H19.8786L20.6201 20.6541H7.08108Z" fill="#3F5E29" /><path d="M4.21466 10.3296H0.670427C0.300172 10.3296 0 10.6298 0 11C0 11.3703 0.300172 11.6704 0.670427 11.6704H4.21466C4.58491 11.6704 4.88509 11.3703 4.88509 11C4.88509 10.6298 4.58491 10.3296 4.21466 10.3296Z" fill="#3F5E29" /><path d="M4.21533 13.6557H2.04543C1.67517 13.6557 1.375 13.9558 1.375 14.3261C1.375 14.6964 1.67517 14.9965 2.04543 14.9965H4.21533C4.58558 14.9965 4.88576 14.6964 4.88576 14.3261C4.88576 13.9558 4.58558 13.6557 4.21533 13.6557Z" fill="#3F5E29" /><path d="M4.21466 16.9818H0.670427C0.300172 16.9818 0 17.2819 0 17.6522C0 18.0224 0.300172 18.3226 0.670427 18.3226H4.21466C4.58491 18.3226 4.88509 18.0224 4.88509 17.6522C4.88509 17.2819 4.58491 16.9818 4.21466 16.9818Z" fill="#3F5E29" /></g><defs><clipPath id="clip0_2515_922"><rect width="22" height="22" fill="white" /></clipPath></defs></svg>
										</a>
									</div>
									<a href="#" class="item-card__image">
										<img src="<?php bloginfo('template_directory'); ?>/img/item_catalog3.jpg" alt="alt">
									</a>
									<div class="item-card__content">
										<div class="item-card__top">
											<div class="item-card__articul">Арт.: ПА-00001005</div>
											<div class="availability">В наличии</div>
										</div>
										<a href="#" class="item-card__title">Абелия крупноцветковая «Compacta»</a>
										<div class="item-card__prices">
											<div class="price-old">6 500 р</div>
											<div class="price-main">5 250 р</div>
										</div>
										<div class="item-card__bottom">
											<div class="quantity">
												<div class="quantity-button quantity-down"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 12H18" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg></div>
												<input type="number" min="1" step="1" value="1">
												<div class="quantity-button quantity-up"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 12H18" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /><path d="M12 18L12 6" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg></div>
											</div>
											<a href="#" class="btn-page">в корзину</a>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-4 col-md-6">
								<div class="item-card">
									<div class="card-action">
										<a href="#" class="btn-card btn-card--favorites">
											<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 20C12 20 3 14.9875 3 8.97234C3 2.95722 10 2.45597 12 7.16221C14 2.45597 21 2.95722 21 8.97234C21 14.9875 12 20 12 20Z" stroke="#3F5E29" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg>
										</a>
										<a href="#" class="btn-card btn-card--basket">
											<svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_2515_922)"><path d="M21.9994 21.2866L21.1815 6.87822C21.1614 6.52289 20.8677 6.24578 20.512 6.24578H17.6635V3.8899C17.6635 1.74767 15.9209 0.00500488 13.7786 0.00500488C11.6364 0.00500488 9.89375 1.74767 9.89375 3.8899V6.24578H7.18969C6.83392 6.24578 6.54027 6.52289 6.52016 6.87822L5.70224 21.2866C5.6807 21.6728 5.98811 21.995 6.37177 21.995H21.3299C21.7147 21.995 22.0209 21.6711 21.9994 21.2866ZM11.2346 3.8899C11.2346 2.48692 12.3761 1.34586 13.7786 1.34586C15.1812 1.34586 16.3227 2.48692 16.3227 3.8899V6.24578H11.2346V3.8899ZM7.08108 20.6541L7.82302 7.58664H9.89375V9.99257C9.89375 10.3626 10.1941 10.663 10.5642 10.663C10.9347 10.663 11.2346 10.3626 11.2346 9.99257V7.58664H16.3227V9.99257C16.3227 10.3626 16.6226 10.663 16.9931 10.663C17.3632 10.663 17.6635 10.3626 17.6635 9.99257V7.58664H19.8786L20.6201 20.6541H7.08108Z" fill="#3F5E29" /><path d="M4.21466 10.3296H0.670427C0.300172 10.3296 0 10.6298 0 11C0 11.3703 0.300172 11.6704 0.670427 11.6704H4.21466C4.58491 11.6704 4.88509 11.3703 4.88509 11C4.88509 10.6298 4.58491 10.3296 4.21466 10.3296Z" fill="#3F5E29" /><path d="M4.21533 13.6557H2.04543C1.67517 13.6557 1.375 13.9558 1.375 14.3261C1.375 14.6964 1.67517 14.9965 2.04543 14.9965H4.21533C4.58558 14.9965 4.88576 14.6964 4.88576 14.3261C4.88576 13.9558 4.58558 13.6557 4.21533 13.6557Z" fill="#3F5E29" /><path d="M4.21466 16.9818H0.670427C0.300172 16.9818 0 17.2819 0 17.6522C0 18.0224 0.300172 18.3226 0.670427 18.3226H4.21466C4.58491 18.3226 4.88509 18.0224 4.88509 17.6522C4.88509 17.2819 4.58491 16.9818 4.21466 16.9818Z" fill="#3F5E29" /></g><defs><clipPath id="clip0_2515_922"><rect width="22" height="22" fill="white" /></clipPath></defs></svg>
										</a>
									</div>
									<a href="#" class="item-card__image">
										<img src="<?php bloginfo('template_directory'); ?>/img/item_catalog1.jpg" alt="alt">
									</a>
									<div class="item-card__content">
										<div class="item-card__top">
											<div class="item-card__articul">Арт.: ПА-00001005</div>
											<div class="availability">В наличии</div>
										</div>
										<a href="#" class="item-card__title">Смолосемянник тонколистный «Tom Thumb»</a>
										<div class="item-card__prices">
											<div class="price-old">6 500 р</div>
											<div class="price-main">5 250 р</div>
										</div>
										<div class="item-card__bottom">
											<div class="quantity">
												<div class="quantity-button quantity-down"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 12H18" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg></div>
												<input type="number" min="1" step="1" value="1">
												<div class="quantity-button quantity-up"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 12H18" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /><path d="M12 18L12 6" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg></div>
											</div>
											<a href="#" class="btn-page">в корзину</a>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-4 col-md-6">
								<div class="item-card">
									<div class="card-action">
										<a href="#" class="btn-card btn-card--favorites">
											<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 20C12 20 3 14.9875 3 8.97234C3 2.95722 10 2.45597 12 7.16221C14 2.45597 21 2.95722 21 8.97234C21 14.9875 12 20 12 20Z" stroke="#3F5E29" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg>
										</a>
										<a href="#" class="btn-card btn-card--basket">
											<svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_2515_922)"><path d="M21.9994 21.2866L21.1815 6.87822C21.1614 6.52289 20.8677 6.24578 20.512 6.24578H17.6635V3.8899C17.6635 1.74767 15.9209 0.00500488 13.7786 0.00500488C11.6364 0.00500488 9.89375 1.74767 9.89375 3.8899V6.24578H7.18969C6.83392 6.24578 6.54027 6.52289 6.52016 6.87822L5.70224 21.2866C5.6807 21.6728 5.98811 21.995 6.37177 21.995H21.3299C21.7147 21.995 22.0209 21.6711 21.9994 21.2866ZM11.2346 3.8899C11.2346 2.48692 12.3761 1.34586 13.7786 1.34586C15.1812 1.34586 16.3227 2.48692 16.3227 3.8899V6.24578H11.2346V3.8899ZM7.08108 20.6541L7.82302 7.58664H9.89375V9.99257C9.89375 10.3626 10.1941 10.663 10.5642 10.663C10.9347 10.663 11.2346 10.3626 11.2346 9.99257V7.58664H16.3227V9.99257C16.3227 10.3626 16.6226 10.663 16.9931 10.663C17.3632 10.663 17.6635 10.3626 17.6635 9.99257V7.58664H19.8786L20.6201 20.6541H7.08108Z" fill="#3F5E29" /><path d="M4.21466 10.3296H0.670427C0.300172 10.3296 0 10.6298 0 11C0 11.3703 0.300172 11.6704 0.670427 11.6704H4.21466C4.58491 11.6704 4.88509 11.3703 4.88509 11C4.88509 10.6298 4.58491 10.3296 4.21466 10.3296Z" fill="#3F5E29" /><path d="M4.21533 13.6557H2.04543C1.67517 13.6557 1.375 13.9558 1.375 14.3261C1.375 14.6964 1.67517 14.9965 2.04543 14.9965H4.21533C4.58558 14.9965 4.88576 14.6964 4.88576 14.3261C4.88576 13.9558 4.58558 13.6557 4.21533 13.6557Z" fill="#3F5E29" /><path d="M4.21466 16.9818H0.670427C0.300172 16.9818 0 17.2819 0 17.6522C0 18.0224 0.300172 18.3226 0.670427 18.3226H4.21466C4.58491 18.3226 4.88509 18.0224 4.88509 17.6522C4.88509 17.2819 4.58491 16.9818 4.21466 16.9818Z" fill="#3F5E29" /></g><defs><clipPath id="clip0_2515_922"><rect width="22" height="22" fill="white" /></clipPath></defs></svg>
										</a>
									</div>
									<a href="#" class="item-card__image">
										<img src="<?php bloginfo('template_directory'); ?>/img/item_catalog2.jpg" alt="alt">
									</a>
									<div class="item-card__content">
										<div class="item-card__top">
											<div class="item-card__articul">Арт.: ПА-00001005</div>
											<div class="availability">В наличии</div>
										</div>
										<a href="#" class="item-card__title">Нандина домашняя «Lemon-Lime»</a>
										<div class="item-card__prices">
											<div class="price-old">6 500 р</div>
											<div class="price-main">5 250 р</div>
										</div>
										<div class="item-card__bottom">
											<div class="quantity">
												<div class="quantity-button quantity-down"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 12H18" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg></div>
												<input type="number" min="1" step="1" value="1">
												<div class="quantity-button quantity-up"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 12H18" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /><path d="M12 18L12 6" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg></div>
											</div>
											<a href="#" class="btn-page">в корзину</a>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-4 col-md-6">
								<div class="item-card">
									<div class="card-action">
										<a href="#" class="btn-card btn-card--favorites">
											<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 20C12 20 3 14.9875 3 8.97234C3 2.95722 10 2.45597 12 7.16221C14 2.45597 21 2.95722 21 8.97234C21 14.9875 12 20 12 20Z" stroke="#3F5E29" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg>
										</a>
										<a href="#" class="btn-card btn-card--basket">
											<svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_2515_922)"><path d="M21.9994 21.2866L21.1815 6.87822C21.1614 6.52289 20.8677 6.24578 20.512 6.24578H17.6635V3.8899C17.6635 1.74767 15.9209 0.00500488 13.7786 0.00500488C11.6364 0.00500488 9.89375 1.74767 9.89375 3.8899V6.24578H7.18969C6.83392 6.24578 6.54027 6.52289 6.52016 6.87822L5.70224 21.2866C5.6807 21.6728 5.98811 21.995 6.37177 21.995H21.3299C21.7147 21.995 22.0209 21.6711 21.9994 21.2866ZM11.2346 3.8899C11.2346 2.48692 12.3761 1.34586 13.7786 1.34586C15.1812 1.34586 16.3227 2.48692 16.3227 3.8899V6.24578H11.2346V3.8899ZM7.08108 20.6541L7.82302 7.58664H9.89375V9.99257C9.89375 10.3626 10.1941 10.663 10.5642 10.663C10.9347 10.663 11.2346 10.3626 11.2346 9.99257V7.58664H16.3227V9.99257C16.3227 10.3626 16.6226 10.663 16.9931 10.663C17.3632 10.663 17.6635 10.3626 17.6635 9.99257V7.58664H19.8786L20.6201 20.6541H7.08108Z" fill="#3F5E29" /><path d="M4.21466 10.3296H0.670427C0.300172 10.3296 0 10.6298 0 11C0 11.3703 0.300172 11.6704 0.670427 11.6704H4.21466C4.58491 11.6704 4.88509 11.3703 4.88509 11C4.88509 10.6298 4.58491 10.3296 4.21466 10.3296Z" fill="#3F5E29" /><path d="M4.21533 13.6557H2.04543C1.67517 13.6557 1.375 13.9558 1.375 14.3261C1.375 14.6964 1.67517 14.9965 2.04543 14.9965H4.21533C4.58558 14.9965 4.88576 14.6964 4.88576 14.3261C4.88576 13.9558 4.58558 13.6557 4.21533 13.6557Z" fill="#3F5E29" /><path d="M4.21466 16.9818H0.670427C0.300172 16.9818 0 17.2819 0 17.6522C0 18.0224 0.300172 18.3226 0.670427 18.3226H4.21466C4.58491 18.3226 4.88509 18.0224 4.88509 17.6522C4.88509 17.2819 4.58491 16.9818 4.21466 16.9818Z" fill="#3F5E29" /></g><defs><clipPath id="clip0_2515_922"><rect width="22" height="22" fill="white" /></clipPath></defs></svg>
										</a>
									</div>
									<a href="#" class="item-card__image">
										<img src="<?php bloginfo('template_directory'); ?>/img/item_catalog1.jpg" alt="alt">
									</a>
									<div class="item-card__content">
										<div class="item-card__top">
											<div class="item-card__articul">Арт.: ПА-00001005</div>
											<div class="availability">В наличии</div>
										</div>
										<a href="#" class="item-card__title">Смолосемянник тонколистный «Tom Thumb»</a>
										<div class="item-card__prices">
											<div class="price-old">6 500 р</div>
											<div class="price-main">5 250 р</div>
										</div>
										<div class="item-card__bottom">
											<div class="quantity">
												<div class="quantity-button quantity-down"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 12H18" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg></div>
												<input type="number" min="1" step="1" value="1">
												<div class="quantity-button quantity-up"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 12H18" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /><path d="M12 18L12 6" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg></div>
											</div>
											<a href="#" class="btn-page">в корзину</a>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-4 col-md-6">
								<div class="item-card">
									<div class="card-action">
										<a href="#" class="btn-card btn-card--favorites">
											<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 20C12 20 3 14.9875 3 8.97234C3 2.95722 10 2.45597 12 7.16221C14 2.45597 21 2.95722 21 8.97234C21 14.9875 12 20 12 20Z" stroke="#3F5E29" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg>
										</a>
										<a href="#" class="btn-card btn-card--basket">
											<svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_2515_922)"><path d="M21.9994 21.2866L21.1815 6.87822C21.1614 6.52289 20.8677 6.24578 20.512 6.24578H17.6635V3.8899C17.6635 1.74767 15.9209 0.00500488 13.7786 0.00500488C11.6364 0.00500488 9.89375 1.74767 9.89375 3.8899V6.24578H7.18969C6.83392 6.24578 6.54027 6.52289 6.52016 6.87822L5.70224 21.2866C5.6807 21.6728 5.98811 21.995 6.37177 21.995H21.3299C21.7147 21.995 22.0209 21.6711 21.9994 21.2866ZM11.2346 3.8899C11.2346 2.48692 12.3761 1.34586 13.7786 1.34586C15.1812 1.34586 16.3227 2.48692 16.3227 3.8899V6.24578H11.2346V3.8899ZM7.08108 20.6541L7.82302 7.58664H9.89375V9.99257C9.89375 10.3626 10.1941 10.663 10.5642 10.663C10.9347 10.663 11.2346 10.3626 11.2346 9.99257V7.58664H16.3227V9.99257C16.3227 10.3626 16.6226 10.663 16.9931 10.663C17.3632 10.663 17.6635 10.3626 17.6635 9.99257V7.58664H19.8786L20.6201 20.6541H7.08108Z" fill="#3F5E29" /><path d="M4.21466 10.3296H0.670427C0.300172 10.3296 0 10.6298 0 11C0 11.3703 0.300172 11.6704 0.670427 11.6704H4.21466C4.58491 11.6704 4.88509 11.3703 4.88509 11C4.88509 10.6298 4.58491 10.3296 4.21466 10.3296Z" fill="#3F5E29" /><path d="M4.21533 13.6557H2.04543C1.67517 13.6557 1.375 13.9558 1.375 14.3261C1.375 14.6964 1.67517 14.9965 2.04543 14.9965H4.21533C4.58558 14.9965 4.88576 14.6964 4.88576 14.3261C4.88576 13.9558 4.58558 13.6557 4.21533 13.6557Z" fill="#3F5E29" /><path d="M4.21466 16.9818H0.670427C0.300172 16.9818 0 17.2819 0 17.6522C0 18.0224 0.300172 18.3226 0.670427 18.3226H4.21466C4.58491 18.3226 4.88509 18.0224 4.88509 17.6522C4.88509 17.2819 4.58491 16.9818 4.21466 16.9818Z" fill="#3F5E29" /></g><defs><clipPath id="clip0_2515_922"><rect width="22" height="22" fill="white" /></clipPath></defs></svg>
										</a>
									</div>
									<a href="#" class="item-card__image">
										<img src="<?php bloginfo('template_directory'); ?>/img/item_catalog2.jpg" alt="alt">
									</a>
									<div class="item-card__content">
										<div class="item-card__top">
											<div class="item-card__articul">Арт.: ПА-00001005</div>
											<div class="availability">В наличии</div>
										</div>
										<a href="#" class="item-card__title">Нандина домашняя «Lemon-Lime»</a>
										<div class="item-card__prices">
											<div class="price-old">6 500 р</div>
											<div class="price-main">5 250 р</div>
										</div>
										<div class="item-card__bottom">
											<div class="quantity">
												<div class="quantity-button quantity-down"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 12H18" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg></div>
												<input type="number" min="1" step="1" value="1">
												<div class="quantity-button quantity-up"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 12H18" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /><path d="M12 18L12 6" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg></div>
											</div>
											<a href="#" class="btn-page">в корзину</a>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-4 col-md-6">
								<div class="item-card">
									<div class="card-action">
										<a href="#" class="btn-card btn-card--favorites">
											<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 20C12 20 3 14.9875 3 8.97234C3 2.95722 10 2.45597 12 7.16221C14 2.45597 21 2.95722 21 8.97234C21 14.9875 12 20 12 20Z" stroke="#3F5E29" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg>
										</a>
										<a href="#" class="btn-card btn-card--basket">
											<svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_2515_922)"><path d="M21.9994 21.2866L21.1815 6.87822C21.1614 6.52289 20.8677 6.24578 20.512 6.24578H17.6635V3.8899C17.6635 1.74767 15.9209 0.00500488 13.7786 0.00500488C11.6364 0.00500488 9.89375 1.74767 9.89375 3.8899V6.24578H7.18969C6.83392 6.24578 6.54027 6.52289 6.52016 6.87822L5.70224 21.2866C5.6807 21.6728 5.98811 21.995 6.37177 21.995H21.3299C21.7147 21.995 22.0209 21.6711 21.9994 21.2866ZM11.2346 3.8899C11.2346 2.48692 12.3761 1.34586 13.7786 1.34586C15.1812 1.34586 16.3227 2.48692 16.3227 3.8899V6.24578H11.2346V3.8899ZM7.08108 20.6541L7.82302 7.58664H9.89375V9.99257C9.89375 10.3626 10.1941 10.663 10.5642 10.663C10.9347 10.663 11.2346 10.3626 11.2346 9.99257V7.58664H16.3227V9.99257C16.3227 10.3626 16.6226 10.663 16.9931 10.663C17.3632 10.663 17.6635 10.3626 17.6635 9.99257V7.58664H19.8786L20.6201 20.6541H7.08108Z" fill="#3F5E29" /><path d="M4.21466 10.3296H0.670427C0.300172 10.3296 0 10.6298 0 11C0 11.3703 0.300172 11.6704 0.670427 11.6704H4.21466C4.58491 11.6704 4.88509 11.3703 4.88509 11C4.88509 10.6298 4.58491 10.3296 4.21466 10.3296Z" fill="#3F5E29" /><path d="M4.21533 13.6557H2.04543C1.67517 13.6557 1.375 13.9558 1.375 14.3261C1.375 14.6964 1.67517 14.9965 2.04543 14.9965H4.21533C4.58558 14.9965 4.88576 14.6964 4.88576 14.3261C4.88576 13.9558 4.58558 13.6557 4.21533 13.6557Z" fill="#3F5E29" /><path d="M4.21466 16.9818H0.670427C0.300172 16.9818 0 17.2819 0 17.6522C0 18.0224 0.300172 18.3226 0.670427 18.3226H4.21466C4.58491 18.3226 4.88509 18.0224 4.88509 17.6522C4.88509 17.2819 4.58491 16.9818 4.21466 16.9818Z" fill="#3F5E29" /></g><defs><clipPath id="clip0_2515_922"><rect width="22" height="22" fill="white" /></clipPath></defs></svg>
										</a>
									</div>
									<a href="#" class="item-card__image">
										<img src="<?php bloginfo('template_directory'); ?>/img/item_catalog3.jpg" alt="alt">
									</a>
									<div class="item-card__content">
										<div class="item-card__top">
											<div class="item-card__articul">Арт.: ПА-00001005</div>
											<div class="availability">В наличии</div>
										</div>
										<a href="#" class="item-card__title">Абелия крупноцветковая «Compacta»</a>
										<div class="item-card__prices">
											<div class="price-old">6 500 р</div>
											<div class="price-main">5 250 р</div>
										</div>
										<div class="item-card__bottom">
											<div class="quantity">
												<div class="quantity-button quantity-down"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 12H18" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg></div>
												<input type="number" min="1" step="1" value="1">
												<div class="quantity-button quantity-up"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 12H18" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /><path d="M12 18L12 6" stroke="#3F5E29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg></div>
											</div>
											<a href="#" class="btn-page">в корзину</a>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="pager">
							<a class="prev page-numbers" href="#"><i class="far fa-long-arrow-left"></i></a> 
							<span aria-current="page" class="page-numbers current" href="#">1</span> 
							<a class="page-numbers" href="#">2</a> 
							<a class="page-numbers" href="#">3</a> 
							<a class="next page-numbers" href="#"><i class="far fa-long-arrow-right"></i></a>
						</div>
					</div>
				</div>
			</div>
		</section>

<?php
get_footer();