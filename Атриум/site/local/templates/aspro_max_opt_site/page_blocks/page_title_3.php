<div class="top-block-wrapper">
	<section class="navigation-item page-top maxwidth-theme <?CMax::ShowPageProps('TITLE_CLASS');?>">
		<div id="navigation">
			<?$APPLICATION->IncludeComponent("bitrix:breadcrumb", "main", array(
				"START_FROM" => "0",
				"PATH" => "",
				"SITE_ID" => SITE_ID,
				"SHOW_SUBSECTIONS" => "N"
				),
				false
			);?>
		</div>		
	</section>

	<section class=" page-top maxwidth-theme <?CMax::ShowPageProps('TITLE_CLASS');?>">

		<?php if(false) { ?>
		<div class="topic">
			<div class="topic__inner ">
				<?//=$APPLICATION->ShowViewContent('product_share')?>
				<div class="topic__heading iq-catalog-title">
					<h1 id="pagetitle iq-catalog-title__text"><?$APPLICATION->ShowTitle(false)?></h1>
					<?$APPLICATION->ShowViewContent('more_text_title');?>
				</div>
			</div>
		</div>
		<?php } ?>
		<?$APPLICATION->ShowViewContent('section_bnr_h1_content');?>
	</section>
</div>