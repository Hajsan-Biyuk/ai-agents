<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** ТЗ п.5: «Товар дня» — товар с флажком «Товар дня (главная опта)» в админке, с переходом в карточку. */
$this->setFrameMode(true);
$item = $arResult['ITEMS'][0] ?? null;
if (!$item) return;
$price = $item['ITEM_PRICES'][$item['ITEM_PRICE_SELECTED']] ?? null;
$pic = $item['PREVIEW_PICTURE']['ID'] ?? ($item['DETAIL_PICTURE']['ID'] ?? 0);
$img = $pic ? CFile::ResizeImageGet($pic, ['width' => 640, 'height' => 640], BX_RESIZE_IMAGE_PROPORTIONAL) : null;
$this->AddEditAction($item['ID'], $item['EDIT_LINK'], CIBlock::GetArrayByID($item['IBLOCK_ID'], 'ELEMENT_EDIT'));
?>
<a href="<?=$item['DETAIL_PAGE_URL']?>" class="opt-day" id="<?=$this->GetEditAreaId($item['ID'])?>">
	<span class="opt-day__info">
		<span class="opt-day__badge">Товар дня</span>
		<span class="opt-day__bottom">
			<span class="opt-day__name"><?=$item['NAME']?></span>
			<?if($price && $price['RATIO_PRICE'] > 0):?>
				<span class="opt-day__prices">
					<span class="opt-day__price"><?=$price['PRINT_RATIO_PRICE']?></span>
					<?if($price['RATIO_BASE_PRICE'] > $price['RATIO_PRICE']):?>
						<span class="opt-day__old"><?=$price['PRINT_RATIO_BASE_PRICE']?></span>
					<?endif;?>
				</span>
				<span class="opt-day__tag">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 3h18a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1Zm1 2v14h16V5H4Zm4.5 9H14a.5.5 0 1 0 0-1h-4a2.5 2.5 0 1 1 0-5h1V6h2v2h2.5v2H10a.5.5 0 0 0 0 1h4a2.5 2.5 0 1 1 0 5h-1v2h-2v-2H8.5v-2Z" fill="#2B5840"/></svg>
					Минимальная цена
				</span>
			<?endif;?>
		</span>
	</span>
	<span class="opt-day__image">
		<?if(!empty($img['src'])):?><img src="<?=$img['src']?>" alt="<?=htmlspecialcharsbx($item['~NAME'])?>"><?endif;?>
	</span>
</a>
