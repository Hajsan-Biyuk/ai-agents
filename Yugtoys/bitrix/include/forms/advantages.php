<section class="advantages">
	<div class="container">
		<div class="advantages-block">
			<div class="title-section">
				<?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/forms/advantages/b1.php"], false, ['HIDE_ICONS' => 'N']);?>
			</div>
			<div class="descr-section">
				<?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/forms/advantages/b2.php"], false, ['HIDE_ICONS' => 'N']);?>
			</div>
			<div class="row">
				<div class="col-lg-9">
					<div class="row">
						<div class="col-md-6">
							<div class="item-advantage">
								<div class="item-advantage__icon">
									<img alt="alt" src="/local/templates/ugtoiz/img/ic_avd1.svg">
								</div>
								<?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/forms/advantages/b3.php"], false, ['HIDE_ICONS' => 'N']);?>
							</div>
						</div>
						<div class="col-md-6">
							<div class="item-advantage">
								<div class="item-advantage__icon">
									<img alt="alt" src="/local/templates/ugtoiz/img/ic_avd2.svg">
								</div>
								<?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/forms/advantages/b4.php"], false, ['HIDE_ICONS' => 'N']);?>
							</div>
						</div>
						<div class="col-md-6">
							<div class="item-advantage">
								<div class="item-advantage__icon">
									<img alt="alt" src="/local/templates/ugtoiz/img/ic_avd3.svg">
								</div>
								<?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/forms/advantages/b5.php"], false, ['HIDE_ICONS' => 'N']);?>
							</div>
						</div>
						<div class="col-md-6">
							<div class="item-advantage">
								<div class="item-advantage__icon">
									<img alt="alt" src="/local/templates/ugtoiz/img/ic_avd4.svg">
								</div>
								<?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/forms/advantages/b6.php"], false, ['HIDE_ICONS' => 'N']);?>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-3">
					<div class="wrapper-sertificat">
						<a href="/about/#sertificats" class="sertificat-advantage">
							<?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/forms/advantages/b_img.php"], false, ['HIDE_ICONS' => 'N']);?>
						</a>
						<a href="/about/#sertificats" class="link-main">Смотреть сертификаты</a>
					</div>
				</div>
			</div>
		</div>
		<div class="partnership-form">
			<div class="partnership-form__wrapper">
				<div class="partnership-form__left">
					<div class="title-block">Стать партнёром легко</div>
					<div class="descr-block">
						<?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/forms/advantages/b7.php"], false, ['HIDE_ICONS' => 'N']);?>
					</div>
				</div>
				<div class="partnership-form__right">
					<form class="js-form">
						<input name="action" type="hidden" value="send-form"/>
						<input name="form-name" type="hidden" value="advantages"/>
						<input name="form-title" type="hidden" value="Стать партнёром"/>
						<div class="line-form">
							<div class="item-form">
								<input name="PHONE" type="text" placeholder="Телефон" class="input-phone" required="required">
							</div>
						</div>
						<div class="checkbox">
							<label><input type="checkbox" required="required"><span>Я даю <a href="/about/soglasie/">согласие на обработку моих персональных данных</a> ООО «Юг Тойз» (ИНН 2310142104) в целях обработки заявки и получения электронных писем на указанную почту. Политика конфиденциальности — <a href="/about/privacy-policy/">по ссылке</a></span></label><br>
							<label><input type="checkbox"><span>Я согласен получать рекламные и информационные материалы от ООО «Юг Тойз» (ИНН 2310142104) на указанный email.</span></label>
							<br><p>Вы можете отозвать своё согласие, написав на почту <a href="mailto:toys@yugtoys.ru">toys@yugtoys.ru</a></p>
						</div>
						<button class="btn-main btn-main_page">СТАТЬ ПАРТНЁРОМ</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>