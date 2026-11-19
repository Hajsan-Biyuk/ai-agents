<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

CModule::IncludeModule("iblock");

$strSectionEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT");
$strSectionDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE");
$arSectionDeleteParams = array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'));

$UF_COL_LG = getUserFieldEnum(42);//UF_COL_LG
$UF_MENU_TYPE = getUserFieldEnum(43);//UF_MENU_TYPE

?>

<section class="page-categories">
	<div class="container">
		<?$APPLICATION->IncludeComponent("bitrix:breadcrumb", "breadcrumb", ["PATH" => "", "SITE_ID" => "s1", "START_FROM" => "0"]);?>
		<h1 class="title-section">Каталог товаров</h1>
		<div class="row row_cateogories">
		<? foreach ($arResult['SECTIONS'] as &$arSection){ ?>
			<div class="<?=$UF_COL_LG[$arSection['UF_COL_LG']]?>">
				<div class="item-category item-category_<?=$UF_MENU_TYPE[$arSection['UF_MENU_TYPE']]?>">
					<div class="item-category__wrapper">
						<div class="item-category__left">
							<div class="item-category__title"><a href="<?=$arSection['SECTION_PAGE_URL']?>"><?=$arSection['NAME']?></a></div>
							<div class="item-category__image">
								<img src="<? echo $arSection['PICTURE']['SRC']; ?>">
							</div>
						</div>
						<div class="item-category__content">
						<? if($UF_MENU_TYPE[$arSection['UF_MENU_TYPE']] == 1){ ?>
							<?
								$section_list = CIBlockSection::GetList(array("sort" => "ASC"), array('IBLOCK_ID' => $arParams["IBLOCK_ID"], 'ACTIVE' => 'Y', 'SECTION_ID' => $arSection['ID']), false, array("ID", "NAME", "CODE", "SECTION_PAGE_URL", "PICTURE"));
							?>
							<div class="row">
							<? while($section = $section_list->GetNext()){ ?>
								<div class="col-lg-6">
									<div class="item-category__subtitle"><a href="<?=$section['SECTION_PAGE_URL']?>"><?=$section['NAME']?></a></div>
									<ul>
									<?
										$section_list_childs = CIBlockSection::GetList(array("sort" => "ASC"), array('IBLOCK_ID' => $arParams["IBLOCK_ID"], 'ACTIVE' => 'Y', 'SECTION_ID' => $section['ID']), false, array("ID", "NAME", "CODE", "SECTION_PAGE_URL", "PICTURE"));
									?>
										<? while($section_child = $section_list_childs->GetNext()){ ?>
										
											<li><a href="<?=$section_child['SECTION_PAGE_URL']?>"><?=$section_child['NAME']?></a></li>
										<? } ?>
									</ul>
								</div>
							<? } ?>
							</div>
							<?
								}
								else{
									$section_list = CIBlockSection::GetList(array("sort" => "ASC"), array('IBLOCK_ID' => $arParams["IBLOCK_ID"], 'ACTIVE' => 'Y', 'SECTION_ID' => $arSection['ID']), false, array("ID", "NAME", "CODE", "SECTION_PAGE_URL", "PICTURE"));
							?>
							<ul>
							<? while($section = $section_list->GetNext()){ ?>
							
								<li><a href="<?=$section['SECTION_PAGE_URL']?>"><?=$section['NAME']?></a></li>
							<? } ?>
							</ul>
						<? } ?>
						</div>
					</div>
				</div>
			</div>
		<? } ?>
		</div>
	</div>
</section>
<section class="section-text">
	<div class="container">
		<?echo CIBlock::GetArrayByID($arParams['IBLOCK_ID'], "DESCRIPTION");?>
	</div>
</section>