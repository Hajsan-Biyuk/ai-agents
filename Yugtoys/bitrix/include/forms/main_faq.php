<section class="questions">
	<div class="container">
		<div class="questions-block">
			<?if ($_SERVER['REQUEST_URI']=='/'){?><div class="title-section">Часто задаваемые вопросы</div><?}else{?><h1 class="title-section">Часто задаваемые вопросы</h1><?}?>
			
			<div class="row">
				<div class="col-lg-8">
					<?$APPLICATION->IncludeComponent(
						"bitrix:news.list", 
						"faq",
						array(
							"IBLOCK_TYPE" => "sliders",
							"IBLOCK_ID" => "8",
							"NEWS_COUNT" => "9999",
							"SORT_BY1" => "SORT",
							"SORT_ORDER1" => "ASC",
							"PROPERTY_CODE" => array(),
							"SET_TITLE" => "N",
							"SET_BROWSER_TITLE" => "N",
						),
						false,
						array('HIDE_ICONS' => 'Y')
					);?>
				</div>
				<div class="col-lg-4">
					<div class="block-form">
						<div class="title-block">Остались вопросы или есть предложения?</div>
						<form class="js-form">
							<input name="action" type="hidden" value="send-form"/>
							<input name="form-name" type="hidden" value="faq"/>
							<input name="form-title" type="hidden" value="Остались вопросы или есть предложения?"/>
							<div class="item-form">
								<input name="NAME" type="text" placeholder="Имя" required="required">
							</div>
							<div class="item-form">
								<input name="PHONE" type="text" placeholder="Телефон" class="input-phone" required="required">
							</div>
							<div class="item-form">
								<textarea name="MSG" placeholder="Комментарий"></textarea>
							</div>
							<div class="checkbox">
								<label><input type="checkbox" required="required"><span>Я даю <a href="/about/soglasie/">согласие на обработку моих персональных данных</a> ООО «Юг Тойз» (ИНН 2310142104) в целях обработки заявки и получения электронных писем на указанную почту. Политика конфиденциальности — <a href="/about/privacy-policy/">по ссылке</a></span></label><br>
								<label><input type="checkbox"><span>Я согласен получать рекламные и информационные материалы от ООО «Юг Тойз» (ИНН 2310142104) на указанный email.</span></label>
								<br><p>Вы можете отозвать своё согласие, написав на почту <a href="mailto:toys@yugtoys.ru">toys@yugtoys.ru</a></p>
							</div>
							<button class="btn-main btn-main_page">отправить</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
<script type="application/ld+json">
	{
		"@context": "http://schema.org/",
		"@type": "FAQPage",
		"mainEntity": [
			{
				"@type": "Question",
				"name": "Зарегистрировался на сайте, но не могу увидеть цены на товары, почему?",
				"acceptedAnswer": {
					"@type": "Answer",
					"text": "Для того, чтобы увидеть цены на сайте вам необходимо зарегистрироваться как “оптовому покупателю”."
				}
			},
			{
				"@type": "Question",
				"name": "Работаете с самозанятыми?",
				"acceptedAnswer": {
					"@type": "Answer",
					"text": "Мы работаем исключительно с ИП и ООО."
				}
			},
			{
				"@type": "Question",
				"name": "Какая минимальная сумма закупки?",
				"acceptedAnswer": {
					"@type": "Answer",
					"text": "Минимальная сумма закупки от 10.000 рублей."
				}
			},
			{
				"@type": "Question",
				"name": "Есть ли у вас сертификаты на товары?",
				"acceptedAnswer": {
					"@type": "Answer",
					"text": "Вся продукция проходит обязательную сертификацию в соответствии с законодательством. Вы можете получить копию сертификата, выбрав интересующую вас продукцию."
				}
			}
		]
	}
	</script>
</section>