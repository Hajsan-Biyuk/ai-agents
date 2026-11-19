<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Информация для оптовых покупателей");
?>

<section class="page-wholesalers">
	<div class="container">
		<h1 class="title-section">Оптовикам</h1>
		<div class="row">
			<div class="col-lg-5">
				<div class="item-wholesaler">
					<?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."about/wholesalers/b1.php"], false, ['HIDE_ICONS' => 'N']);?>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="item-wholesaler">
					<?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."about/wholesalers/b2.php"], false, ['HIDE_ICONS' => 'N']);?>
				</div>
			</div>
			<div class="col-lg-4">
				<div class="item-wholesaler">
					<?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."about/wholesalers/b3.php"], false, ['HIDE_ICONS' => 'N']);?>
				</div>
			</div>
		</div>
		<div class="email-block">
			<img src="<?=SITE_TEMPLATE_PATH?>/img/ic_message.svg" alt="alt">
			<div class="title-block">
				<?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."about/wholesalers/sub_t.php"], false, ['HIDE_ICONS' => 'N']);?>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-4">
				<?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/forms/wholesalers.php"], false, ['HIDE_ICONS' => 'Y']);?>
			</div>
			<div class="col-lg-8">
				<div class="text-wholesalers">
					<?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."about/wholesalers/desc.php"], false, ['HIDE_ICONS' => 'N']);?>
				</div>
			</div>
		</div>
	</div>
</section>

<?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/forms/partnership.php"], false, ['HIDE_ICONS' => 'Y']);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>