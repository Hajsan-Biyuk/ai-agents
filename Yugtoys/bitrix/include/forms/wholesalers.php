<div class="wrapper-form">
	<!--<ul class="tabs-form">
		<li><a href="#tab_form2">Войти в личный кабинет</a></li>
		<li class="active"><a href="#tab_form1">Стать партнером</a></li>
	</ul>-->
	<div class="tab-container-form">
		<div class="tab-pane-form" id="tab_form1">
			<div class="block-form">
				<div class="title-block">Войти <br>в личный кабинет</div>
				<?if ($USER->IsAuthorized()):?>
				<form class="js-form">
					<a href="/registration/" class="btn-main btn-main_page">войти</a>
					
				</form>
				<?else:?>
				<form class="js-form">
					<input name="action" type="hidden" value="send-form"/>
					<input name="form-name" type="hidden" value="login_lk"/>
					<input name="form-title" type="hidden" value="Войти в личный кабинет"/>
					<div class="item-form">
						<input name="U_LOGIN" type="text" placeholder="Логин" required="required">
					</div>
					<div class="item-form">
						<input name="U_PASSWORD" type="password" placeholder="***********"required="required">
					</div>
					<div class="checkbox">
						<label><input type="checkbox" required="required"><span>Я даю <a href="/about/soglasie/">согласие на обработку моих персональных данных</a> ООО «Юг Тойз» (ИНН 2310142104) в целях обработки заявки и получения электронных писем на указанную почту. Политика конфиденциальности — <a href="/about/privacy-policy/">по ссылке</a></span></label><br>
						<label><input type="checkbox"><span>Я согласен получать рекламные и информационные материалы от ООО «Юг Тойз» (ИНН 2310142104) на указанный email.</span></label>
						<br><p>Вы можете отозвать своё согласие, написав на почту <a href="mailto:toys@yugtoys.ru">toys@yugtoys.ru</a></p>
					</div>
					<button class="btn-main btn-main_page">войти</button>
				</form>
				<?endif?>
			</div>
		</div>
		<div class="tab-pane-form" id="tab_form2">
			<div class="block-form">
				<div class="title-block">Зарегистрироваться</div>
				<form class="nojs-form">
					<input name="action" type="hidden" value="send-form"/>
					<input name="form-name" type="hidden" value="reg_lk"/>
					<input name="form-title" type="hidden" value="Зарегистрироваться"/>
					<div class="item-form">
						<input name="U_LOGIN" type="text" placeholder="Логин" required="required">
					</div>
					<div class="item-form">
						<input name="U_PASSWORD" type="password" placeholder="***********"required="required">
					</div>
					<div class="checkbox">
						<label><input type="checkbox" required="required"><span>Я даю <a href="/about/soglasie/">согласие на обработку моих персональных данных</a> ООО «Юг Тойз» (ИНН 2310142104) в целях обработки заявки и получения электронных писем на указанную почту. Политика конфиденциальности — <a href="/about/privacy-policy/">по ссылке</a></span></label><br>
						<label><input type="checkbox"><span>Я согласен получать рекламные и информационные материалы от ООО «Юг Тойз» (ИНН 2310142104) на указанный email.</span></label>
						<br><p>Вы можете отозвать своё согласие, написав на почту <a href="mailto:toys@yugtoys.ru">toys@yugtoys.ru</a></p>
					</div>
					<button class="btn-main btn-main_page">Зарегистрироваться</button>
				</form>
			</div>
		</div>
	</div>
</div>