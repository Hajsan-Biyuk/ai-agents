<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Контактная информация и адреса телефонов компании Юг Тойз");
?><section class="page-contacts">
<div class="container">
	<h1 class="title-section">Контакты</h1>
	<div class="unit-contacts">
		<div class="row">
			<div class="col-lg-5">
				<div class="info-contacts">
					<div class="title-block">
						 Оптовая база «Мир Игрушек»
					</div>
					<p>
						 Адрес: 350001, Краснодар, ул. Новороссийская, 98
					</p>
 <br>
					<p>
						 Телефон: <a href="tel:88003506691"><strong>8 (800) 350-66-91</strong></a>
					</p>
 <br>
					<p>
						Мессенджеры: 
						<a href="tel:+79189661101"><strong>+7 (918) 966-11-01</strong></a>
						<a href="https://api.whatsapp.com/send?phone=79189661101"><img alt="alt" src="/local/templates/ugtoiz/img/i_wp.svg"></a>
						<?/*?><a href="viber://chat?number=79189661101"><img alt="alt" src="/local/templates/ugtoiz/img/i_vb.svg"></a><?*/?>
						<a href="https://max.ru/u/f9LHodD0cOJjV2P7GuGWMf0FgQMYwBgmmkJTRS7ZV7mOUD4PSRIcGmgExP4"><img alt="alt" src="/local/templates/ugtoiz/img/max-bg.svg"></a>
					</p>
 <br>
					<p>
						 График работы:
					</p>
					<p>
						 ПН-ПТ с 8:30 до 17:30
					</p>
					<p>
						 СБ - ВС — выходной
					</p>
 <br>
					<p>
						 E-mail: <a href="mailto:sale1@yugtoys.ru">sale1@yugtoys.ru</a>
					</p>
				</div>
			</div>
			<div class="col-lg-7">
				<div id="map-contact1" class="map-contact">
				</div>
			</div>
		</div>
	</div>
	<div class="unit-contacts">
		<div class="row">
			<div class="col-lg-5">
				<div class="info-contacts">
					<div class="title-block">
						 Магазин-склад «Юг Тойз»
					</div>
					<p>
						 Адрес: 350001, Краснодар, ул. Уральская, 97
					</p>
 <br>
					<p>
						 Телефон: <a href="tel:+79892660516"><strong>+7 989 266-05-16</strong></a>
					</p>
<p>
						Городской телефон: <strong>2606363</strong></a>
					</p>
 <br>
					<p>
						Мессенджеры: 
						<a href="tel:+79892660516"><strong>+7 989 266-05-16</strong></a>
						<a href="https://api.whatsapp.com/send?phone=79892660516"><img alt="alt" src="/local/templates/ugtoiz/img/i_wp.svg"></a>
						<a href="https://max.ru/u/f9LHodD0cOI2FYaD4rN2mSCb9MrH90kVFonIlif6DM-N6GEzmZFDtl1gg3g"><img alt="alt" src="/local/templates/ugtoiz/img/max-bg.svg"></a>
					</p>
 <br>
					<p>
						 График работы:
					</p>
					<p>
						 ПН-ПТ с 8:30 до 17:30
					</p>
					<p>
						 СБ - ВС — выходной
					</p>
 <br>
					<p>
						 E-mail: <a href="mailto:ug_toys@bk.ru ">ug_toys@bk.ru</a>
					</p>
				</div>
			</div>
			<div class="col-lg-7">
				<div id="map-contact2" class="map-contact">
				</div>
			</div>
		</div>
	</div>
<div class="company-details">
        <div class="title-block">Реквизиты компании</div>
        <ul>
            <li>Наименование: ООО «Юг Тойз»</li>
            <li>ИНН: 2310142104</li>
            <li>КПП: 231201001</li>
            <li>ОГРН: 1092310004973</li>
        </ul>
    </div>
	<ul class="tabs">
		<li class="active"><a href="#tab_contacts1">заявка на рекламацию</a></li>
		<li><a href="#tab_contacts2">Заказать акт сверки</a></li>
		<li><a href="#tab_contacts3">условия работы</a></li>
	</ul>
	<div class="tab-container">
		<div class="tab-pane" id="tab_contacts1">
			<?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/forms/form_contacts1.php"], false, ['HIDE_ICONS' => 'Y']);?>
		</div>
		<div class="tab-pane" id="tab_contacts2">
			<?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/forms/form_contacts2.php"], false, ['HIDE_ICONS' => 'Y']);?>
		</div>
		<div class="tab-pane" id="tab_contacts3">
			<?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/forms/form_contacts3.php"], false, ['HIDE_ICONS' => 'Y']);?>
		</div>
	</div>
</div>
 </section><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php")?>