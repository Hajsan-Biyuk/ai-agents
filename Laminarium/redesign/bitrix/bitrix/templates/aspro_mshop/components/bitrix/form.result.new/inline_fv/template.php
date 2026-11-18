<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<form class="<?=$arResult["arForm"]["SID"]?>" name="<?=$arResult["arForm"]["SID"]?>">
	<div class="row-main">
		<?if($arResult["isFormErrors"] == "Y" || strlen($arResult["FORM_NOTE"])):?>
			<div class="form_result <?=($arResult["isFormErrors"] == "Y" ? 'error' : 'success')?>">
				<?if($arResult["isFormErrors"] == "Y"):?>
					<?=$arResult["FORM_ERRORS_TEXT"]?>
				<?else:?>
					<script>window.location.replace("/thanks/");</script>
<?$successNoteFile = SITE_DIR."include/form/success_{$arResult["arForm"]["SID"]}.php";?>
					<?if(file_exists($_SERVER["DOCUMENT_ROOT"].$successNoteFile)):?>
					<?$APPLICATION->IncludeFile($successNoteFile, array(), array("MODE" => "html", "NAME" => "Form success note"));?>
					<?else:?>
						Ваше сообщение успешно отправлено
					<?endif;?>
				<?endif;?>
			</div>
		<?endif;?>
		<?=$arResult["FORM_HEADER"]?>
		<?=bitrix_sessid_post();?>
			<? if(is_array($arResult["QUESTIONS"])){ ?>
				<? foreach($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion){ ?>
					<? if($arQuestion['STRUCTURE'][0]['ACTIVE'] == 'Y'){ ?>
					<?
						$type = 'text'; $class = ''; $inputmode = '';
						if($FIELD_SID == 'USR_PHONE'){
							$type = 'tel';
							$arQuestion['CAPTION'] = '+7 (___) ___-__-__';
							$class = 'class="phone_input"';
							$inputmode = 'inputmode="numeric"';
						}
						if($FIELD_SID == 'USR_EMAIL') $type = 'email';
						
					?>
					<div class="col-main">
						<div class="item-form">
							<input data-sid="<?=$FIELD_SID?>" name="form_<?=$arQuestion['STRUCTURE'][0]['FIELD_TYPE']?>_<?=$arQuestion['STRUCTURE'][0]['ID']?>" type="<?=$type?>" placeholder="<?=$arQuestion['CAPTION']?>" <?=($arQuestion['REQUIRED'] == 'Y' ? 'required' : '')?> <?=$class?> <?=$inputmode?>>
						</div>
					</div>
					<? } ?>
				<? } ?>
			<? } ?>
		<?=$arResult["FORM_FOOTER"]?>
		<div class="col-big">
			<div class="contacts-unit">
				<div class="contacts-unit__line">
					<a href="tel:+79180227999" class="phone-main">+7 (918) 022-79-99</a>
					<ul class="socials-list">
						<li><a href="#" target="_blank"><img src="<?php echo SITE_TEMPLATE_PATH ?>/images/soc_wp.svg" alt="alt"></a></li>
						<li><a href="#" target="_blank"><img src="<?php echo SITE_TEMPLATE_PATH ?>/images/soc_tg.svg" alt="alt"></a></li>
					</ul>
				</div>
				<a href="mailto:mail@laminarium.com" class="email-main">mail@laminarium.com</a>
			</div>
		</div>
		<div class="col-main">
			<input class="btn-main" type="submit" class="button medium" value="<?=$arResult["arForm"]["BUTTON"]?>" name="web_form_submit" >
		</div>
	</div>
	<div class="checkbox">
		<label>
			<input type="checkbox" required checked />
			<span>Я даю согласие на обработку моих персональных данных ИП Ипполитова Ольга Олеговна (ИНН 344811221964) в целях обработки заявки и обратной связи. Политика конфиденциальности — <a href="/info/zakon/" target="_blank">по ссылке</a></span>
		</label>
	</div>
</form>
<script type="text/javascript">
$(document).ready(function() {
	try{
		$('form').filter('[name="<?=$arResult["arForm"]["SID"]?>"]').submit(function(e){
			e.preventDefault();
			var $form = $(this);
			var data = new FormData($form[0]);
			$.ajax({
				method: 'POST',
				type: "POST",
				url: "/ajax/form.php?form_id=<?=$arResult["arForm"]["SID"]?>",
				data: data,
				processData: false,
				contentType: false,
				cache: false,
				success: function(reply){
					var html = (typeof reply === 'string') ? reply : '';
					if (/form_result\s+success/.test(html)) {
						window.location.replace('/thanks/');
						return;
					}
					var $parsed = $('<div>').html(html).find('.form_result').first();
					var $box = $form.find('.form_result');
					if ($parsed.length) {
						if ($box.length) { $box.replaceWith($parsed); }
						else { $form.prepend($parsed); }
					}
				},
				error: function(e){
					console.log(e);
				}
			});
		});
	}
	catch(e){
	}
});
</script>