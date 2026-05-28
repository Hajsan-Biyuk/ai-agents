<?php
/*
Template Name: Вход
*/
get_header();
?>

<section class="page-authorization">
			<div class="container">
				<ul class="tabs tabs--card">
					<li class="active"><a href="#tab_authorization1">вход</a></li>
					<li><a href="#tab_authorization2">регистрация</a></li>
				</ul>
				<div class="tab-container">
					<div class="tab-pane" id="tab_authorization1">
						<div class="form-page">
							<div class="title-middle">личный кабинет</div>
							<form>
								<div class="item-form">
									<input type="text" placeholder="Логин*" required>
								</div>
								<div class="item-form">
									<input type="password" placeholder="Пароль*" required>
									<div class="show-password"><i class="far fa-eye"></i></div>
								</div>
								<div class="recover-link">
									<a href="#">Забыли пароль?</a>
								</div>
								<input type="submit" class="btn-page" value="войти">
								<div class="checkbox">
									<label>
										<input type="checkbox" required="required" checked="checked" />
										<span>Я даю согласие на обработку моих персональных данных ООО “____” (ИНН: 9102050864) в целях обработки заявки и обратной связи. Политика конфиденциальности — <a href="#">по ссылке.</a></span>
									</label>
								</div>
							</form>
						</div>
					</div>
					<div class="tab-pane" id="tab_authorization2">
						<div class="form-page">
							<div class="title-middle">регистрация</div>
							<div class="descr-form">Зарегистрируйтесь, чтобы использовать все возможности личного кабинета: отслеживание заказов, настройку подписки, связи с социальными сетями</div>
							<form>
								<div class="item-form">
									<input type="text" placeholder="Фамиллия Имя Отчество*" required>
								</div>
								<div class="item-form">
									<input type="email" placeholder="Почта*" inputmode="email" required>
								</div>
								<div class="item-form">
									<input type="tel" placeholder="Телефон*" class="input-phone" inputmode="tel" required>
								</div>
								<div class="item-form">
									<input type="text" placeholder="Вид деятельности" required>
								</div>
								<div class="item-form">
									<input type="password" placeholder="Пароль*" required>
									<div class="show-password"><i class="far fa-eye"></i></div>
								</div>
								<div class="item-form">
									<input type="password" placeholder="Подтверждение пароля*" required>
									<div class="show-password"><i class="far fa-eye"></i></div>
								</div>
								<input type="submit" class="btn-page" value="зарегистрироваться">
								<div class="checkbox">
									<label>
										<input type="checkbox" required="required" checked="checked" />
										<span>Я даю согласие на обработку моих персональных данных ООО “____” (ИНН: 9102050864) в целях обработки заявки и обратной связи. Политика конфиденциальности — <a href="#">по ссылке.</a></span>
									</label>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</section>

<?php
get_footer();