<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Согласие на обработку персональных данных");
?>
<section class="page-contacts">
	<div class="container">
		<h1 class="title-section">Согласие на обработку персональных данных</h1>
		<div class="row">
			<div class="col-lg-12">
				<?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."about/soglasie/html.php"], false, ['HIDE_ICONS' => 'N']);?>
			</div>
		</div>
	</div>
</section>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php")?>
