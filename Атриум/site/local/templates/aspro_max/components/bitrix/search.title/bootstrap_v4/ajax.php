<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?
	/*if(empty($arResult["CATEGORIES"])) return;*/
	
?>
<div class="bx_searche scrollblock scrollblock--thick">
<div class="maxwidth-theme">
<div class="col-md-12">
	<?
	if($USER->IsAdmin())
	{
		//echo "<pre style='color:#02f702; background:#414354;'>";print_r($arResult);echo "</pre>";
	}
	?>
	<?
	if(CModule::IncludeModule('search'))
	{
		$dbStatistic = \CSearchStatistic::GetList(['COUNT'=>'DESC'], ['SITE_ID'=>['s1'], 'PHRASE'=>'%' . $arResult['query'] . '%'], ['PHRASE', 'RESULT_COUNT'], true);
		while($arStatistic = $dbStatistic->Fetch())
		{
			if($arStatistic['RESULT_COUNT'] > 0)
			{
				$arResult['POPULAR'][] = $arStatistic;
			}
		}
	}
	?>
	<? if(!empty($arResult['POPULAR']) && false):?>
		<div class="search-row-wrapper">
			<div class="search-row-title">Популярное</div>
			<?
				foreach($arResult['POPULAR'] as $k => $popular)
				{
					//if(count($arResult['POPULAR']) >= 4) break; // suck
					?>
					<div class="search-title-result-item py-2 d-flex align-items-center">
						<div class="search-title-result-item-info flex-grow-1">
							<a class="search-title-result-item-link" href="/catalog/?q=<?=$popular['PHRASE'];?>"><?=$popular['PHRASE'];?></a>
						</div>
					</div>
					<? if ($k == 4) break;
				}
				unset($k, $popular);
			?>
		</div>
	<? endif;?>
	<? if(!empty($arResult['SECTIONS']) && false):?>
		<div class="search-row-wrapper">
			<div class="search-row-title">Разделы</div>
			<?
				foreach($arResult['SECTIONS'] as $k => $section)
				{
					?>
					<div class="search-title-result-item py-2 d-flex align-items-center">
						<div class="search-title-result-item-info flex-grow-1">
							<a class="search-title-result-item-link" href="<?=$section["URL"];?>"><?=$section["NAME"];?></a>
						</div>
					</div>
					<?
				}
				unset($k, $section);
			?>
		</div>
	<? endif;?>
	
	<?if(!empty($arResult['SEARCH'])):?>
		<div class="search-row-wrapper">
			<div class="search-row-title">Товары</div>
			<?
				foreach($arResult['SEARCH'] as $k => $item)
				{
					$el = $arResult['ELEMENTS'][$item['ITEM_ID']];
					if(intval($item['ITEM_ID']))
					{
						?>
						<div class="search-title-result-item py-2 d-flex align-items-center">
							<?if (is_array($el["PICTURE"])):?>
								<div class="search-title-result-item-image-container pr-2">
									<div class="search-title-result-item-image"
										 style=" background-image: url('<?=$el["PICTURE"]["src"];?>'); width:<?=$el["PICTURE"]["width"];?>px; height:<?=$el["PICTURE"]["height"]?>px;">
									</div>
								</div>
							<?endif;?>
							<div class="search-title-result-item-info flex-grow-1">
								<a class="search-title-result-item-link" href="<?=$item['URL'];?>"><?=$item['NAME'];?></a>
								<?
								foreach($el['PRICES'] as $price)
								{
									if($price['MIN_PRICE'] != 'Y') continue;
									if($price['CAN_ACCESS'])
									{
										?>
										<?if($price['DISCOUNT_VALUE'] < $price['VALUE']):?>
											<div class="item-price">
												<span class="current-price text-primary"><?=$price['PRINT_DISCOUNT_VALUE'];?></span>
												<span class="old-price text-muted"><?=$price['PRINT_VALUE'];?></span>
											</div>
										<? else:?>
											<div class="item-price">
												<span class="current-price text-primary"><?=$price['PRINT_VALUE'];?></span>
											</div>
										<? endif;?>
										<?
									}
									if ($price['MIN_PRICE'] == 'Y') break;
								}
								?>
							</div>
						</div>
						<?
					}
				}
				unset($k, $item);
			?>
	</div>
	<? endif;?>
</div>
</div>
</div>