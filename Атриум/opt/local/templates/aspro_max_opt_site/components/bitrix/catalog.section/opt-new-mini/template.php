<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** ТЗ п.12: «Новинки» вверху раздела каталога — последние добавленные товары раздела (Figma 181-3639, мини-карточки 200×274). */
$this->setFrameMode(true);
if (empty($arResult['ITEMS'])) return;?>
<div class="opt-new">
	<div class="opt-new__promo">
		<span class="opt-new__pic" aria-hidden="true">NEW</span>
		<span class="opt-new__badge">НОВИНКИ</span>
	</div>
	<div class="opt-new__list">
		<?foreach ($arResult['ITEMS'] as $item):
			$this->AddEditAction($item['ID'], $item['EDIT_LINK'], CIBlock::GetArrayByID($item['IBLOCK_ID'], 'ELEMENT_EDIT'));
			$price = $item['ITEM_PRICES'][$item['ITEM_PRICE_SELECTED']] ?? null;
			$pic = $item['PREVIEW_PICTURE']['ID'] ?? ($item['DETAIL_PICTURE']['ID'] ?? 0);
			$img = $pic ? CFile::ResizeImageGet($pic, ['width' => 360, 'height' => 444], BX_RESIZE_IMAGE_PROPORTIONAL) : null;
			$unit = !empty($item['ITEM_MEASURE']['TITLE']) ? '/'.$item['ITEM_MEASURE']['TITLE'] : '';?>
			<a class="opt-new__item" href="<?=$item['DETAIL_PAGE_URL']?>" id="<?=$this->GetEditAreaId($item['ID'])?>" title="<?=htmlspecialcharsbx($item['~NAME'])?>">
				<span class="opt-new__img">
					<?if(!empty($img['src'])):?><img class="lazy" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($img['src'])?>" data-src="<?=$img['src']?>" alt="<?=htmlspecialcharsbx($item['~NAME'])?>"><?endif;?>
					<?if($price && $price['PERCENT'] > 0):?><span class="opt-new__sale">-<?=round($price['PERCENT'])?>%</span><?endif;?>
				</span>
				<?if($price && $price['RATIO_PRICE'] > 0):?>
					<span class="opt-new__prices">
						<span class="opt-new__price"><?=$price['PRINT_RATIO_PRICE'].$unit?></span>
						<?if($price['RATIO_BASE_PRICE'] > $price['RATIO_PRICE']):?><span class="opt-new__old"><?=$price['PRINT_RATIO_BASE_PRICE'].$unit?></span><?endif;?>
					</span>
				<?endif;?>
			</a>
		<?endforeach;?>
	</div>
</div>
