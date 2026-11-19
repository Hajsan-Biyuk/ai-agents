<section class="partnership">
	<div class="container">
		<div class="partnership-block">
			<div class="title-partnership">Хочешь стать партнёром?</div>
			<ul class="list-partnership">
				<li>
					<?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/forms/partnership/b1.php"], false, ['HIDE_ICONS' => 'N']);?>
				</li>
				<li>
					<?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/forms/partnership/b2.php"], false, ['HIDE_ICONS' => 'N']);?>
				</li>
				<li>
					<?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/forms/partnership/b3.php"], false, ['HIDE_ICONS' => 'N']);?>
				</li>
				<li>
					<?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/forms/partnership/b4.php"], false, ['HIDE_ICONS' => 'N']);?>
				</li>
			</ul>
		</div>
		<div class="partnership-form">
			<div class="title-block">Стать партнёром легко</div>
			<div class="descr-block">Оставьте свои контакты и менеджер свяжется с вами в ближайшее время</div>
			<form class="js-form">
				<div class="partnership-form__bottom">
					<div class="file-upload"></div>
					<a href="/registration/" class="btn-main btn-main_page">СТАТЬ ПАРТНЁРОМ</a>
					<div class="checkbox"></div>
				</div>
			</form>
			<!--<form class="js-form">
				<input name="action" type="hidden" value="send-form"/>
				<input name="form-name" type="hidden" value="partnership"/>
				<input name="form-title" type="hidden" value="Стать партнёром"/>
				<div class="row">
					<div class="col-lg-3 col-md-6">
						<div class="item-form">
							<input name="NAME" type="text" placeholder="Имя" required="required">
						</div>
					</div>
					<div class="col-lg-3 col-md-6">
						<div class="item-form">
							<input name="PHONE" type="text" placeholder="Телефон" class="input-phone" required="required">
						</div>
					</div>
					<div class="col-lg-3 col-md-6">
						<div class="item-form">
							<input name="EMAIL" type="email" placeholder="E-mail" required="required">
						</div>
					</div>
					<div class="col-lg-3 col-md-6">
						<div class="item-form">
							<input name="INN" type="text" placeholder="ИНН/ОГРН" required="required">
						</div>
					</div>
				</div>
				<div class="partnership-form__bottom">
					<div class="file-upload">
						<label><input name="file" type="file" name="file"><img alt="alt" src="/local/templates/ugtoiz/img/clip.svg"> Загрузить карточку предприятия</label>
						<div class="name-upload"></div>
					</div>
					<button class="btn-main btn-main_page">СТАТЬ ПАРТНЁРОМ</button>
					<div class="checkbox">
						<label>
							<input type="checkbox" required="required" checked="checked">
							<span>Согласие на обработку персональных данных</span>
						</label>
					</div>
				</div>
			</form>-->
			<div class="partnership-toy">
				<img alt="alt" src="/local/templates/ugtoiz/img/toy1.png">
			</div>
		</div>
	</div>
</section>