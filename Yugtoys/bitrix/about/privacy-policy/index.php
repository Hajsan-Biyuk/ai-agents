<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Политика конфиденциальности и правила обработки персональных данных");
?>

<section class="page-contacts">
	<div class="container">
		<h1 class="title-section"><?$APPLICATION->ShowTitle()?></h1>
		<div class="row">
			<div class="col-lg-12">
				<?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."about/privacy-policy/html.php"], false, ['HIDE_ICONS' => 'N']);?>
			</div>
		</div>
	</div>
</section>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php")?>