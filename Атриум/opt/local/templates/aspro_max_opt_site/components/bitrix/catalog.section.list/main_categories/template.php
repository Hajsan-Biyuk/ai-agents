<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** ТЗ п.3: плашки всех существующих категорий, по 7 в ряд (как в макете). */
$this->setFrameMode(true);
$strSectionEdit = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'SECTION_EDIT');
$strSectionDelete = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'SECTION_DELETE');
?>
<?if(!empty($arResult['SECTIONS'])):?>
<section class="opt-cats">
	<div class="maxwidth-theme">
		<div class="opt-cats__grid">
			<?// 2 ряда по 7: последнее место — плашка «Показать всё», поэтому разделов максимум 13
			foreach(array_slice($arResult['SECTIONS'], 0, 13) as $arSection):
				$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
				$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, ['CONFIRM' => 'Удалить раздел?']);
			?>
				<a href="<?=$arSection['SECTION_PAGE_URL']?>" class="opt-cat" id="<?=$this->GetEditAreaId($arSection['ID'])?>">
					<span class="opt-cat__title"><?=htmlspecialcharsbx(trim($arSection['~UF_OPT_MAIN_NAME'] ?? '')) ?: $arSection['NAME']?></span>
					<span class="opt-cat__image">
						<?if(!empty($arSection['OPT_IMAGE'])):?>
							<img class="lazy" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($arSection['OPT_IMAGE'])?>" data-src="<?=$arSection['OPT_IMAGE']?>" alt="<?=htmlspecialcharsbx($arSection['~NAME'])?>">
						<?endif;?>
					</span>
				</a>
			<?endforeach;?>
				<a href="<?=SITE_DIR?>catalog/" class="opt-cat opt-cat--all">
					<span class="opt-cat__title">Показать всё</span>
					<span class="opt-cat__image">
						<span class="opt-cat__all-text">Все категории</span>
						<svg width="40" height="40" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M13.172 12L8.222 7.05L9.636 5.636L16 12L9.636 18.364L8.222 16.95L13.172 12Z" fill="#fff"/></svg>
					</span>
				</a>
		</div>
	</div>
</section>
<?endif;?>
