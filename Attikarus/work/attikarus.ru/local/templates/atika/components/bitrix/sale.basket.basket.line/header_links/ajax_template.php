<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
$this->IncludeLangFile('template.php');
$cartId = $arParams['cartId'];
require(realpath(dirname(__FILE__)).'/top_template.php');

if ($arParams["SHOW_PRODUCTS"] == "Y" && ($arResult['NUM_PRODUCTS'] > 0 || !empty($arResult['CATEGORIES']['DELAY']))){

	$HL_TSVET = getHLList(5);
	$HL_RAZMER = getHLList(6);

?>
<div class="dropdown-header dropdown-header_basket">
	<div class="dropdown-header__close dropdown-header_basket__close"><img src="<?=TMPL_URL?>/img/remove.svg" alt="alt"></div>
	<div class="dropdown-header__title">КОРЗИНА</div>
	<div class="unit-basket">
		<div id="<?=$cartId?>products" class="bx-basket-item-list-container">
			<?foreach ($arResult["CATEGORIES"] as $category => $items):
				if(empty($items)) continue;
			?>
			<table>
				<thead>
					<tr>
						<th colspan="3">Наименование:</th>
					</tr>
				</thead>
				<tbody>
				<?foreach ($items as $v):?>
					<?
						//print_r($v);
						if(empty($v["PREVIEW_PICTURE"])){
							if(!empty($v["DETAIL_PICTURE"])) $v["PREVIEW_PICTURE"] = $v["DETAIL_PICTURE"];
						}
					?>
					<tr>
						<td>
							<div class="image-td">
								<img src="<?=CFile::GetPath($v["PREVIEW_PICTURE"])?>" alt="<?=$v["NAME"]?>">
							</div>
						</td>
						<td>
						<?
							$PRODUCT_ID = $v["PRODUCT_ID"];
							$mxResult = CCatalogSku::GetProductInfo($v["PRODUCT_ID"]);
							if(is_array($mxResult)) $PRODUCT_ID = $mxResult['ID'];
							
							
							$PRODUCT_INFO = CIBlockElement::GetList([], ['ID' => $v["PRODUCT_ID"], 'IBLOCK_ID' => (is_array($mxResult) ? $mxResult['OFFER_IBLOCK_ID'] : CATALOG)], false, false, ['*', 'CATALOG_QUANTITY'])->GetNext();
							
							
							$ITEM = $v;
							$PARENT = CCatalogSku::GetProductInfo($ITEM['PRODUCT_ID']);
							if(empty($PARENT)) $PARENT = ['ID' => $ITEM['PRODUCT_ID'], 'IBLOCK_ID' => CATALOG, 'OFFER_IBLOCK_ID' => CATALOG];
							$db_res = CIBlockElement::GetList([], ['ID' => $PARENT['ID'], 'IBLOCK_ID' => $PARENT['IBLOCK_ID']], false, false, ['*', 'PROPERTY_NAIMENOVANIE', 'PROPERTY_ANGLNAIMENOVANIE', 'PROPERTY_BREND', 'PROPERTY_CML2_ARTICLE']);
							if($PARENT_INFO = $db_res->GetNext()){
								$NAME = $PARENT_INFO['PROPERTY_NAIMENOVANIE_VALUE'] .' '. $PARENT_INFO['PROPERTY_ANGLNAIMENOVANIE_VALUE'];
								$CML2_ARTICLE = $PARENT_INFO['PROPERTY_CML2_ARTICLE_VALUE'];
								$BREND = $PARENT_INFO['PROPERTY_BREND_VALUE'];
								$PICTURE = (!empty($PARENT_INFO['PREVIEW_PICTURE']) ? CFile::GetPath($PARENT_INFO['PREVIEW_PICTURE']) : TMPL_URL . '/images/no_photo.jpg');
							}
							else{
								$NAME = $ITEM['NAME'];
								$CML2_ARTICLE = '';
								$BREND = '';
								$PICTURE = TMPL_URL . '/images/no_photo.jpg';
							}
							$db_props = CIBlockElement::GetProperty($PARENT['OFFER_IBLOCK_ID'], $ITEM['PRODUCT_ID'], [], ['CODE' => 'TSVET']);
							$TSVET = ''; if($ar_props = $db_props->Fetch()) $TSVET = $HL_TSVET[$ar_props["VALUE"]]['UF_NAME'];
							$db_props = CIBlockElement::GetProperty($PARENT['OFFER_IBLOCK_ID'], $ITEM['PRODUCT_ID'], [], ['CODE' => 'RAZMER']);
							$RAZMER = ''; if($ar_props = $db_props->Fetch()) $RAZMER = $HL_RAZMER[$ar_props["VALUE"]]['UF_NAME'];
								
						?>
							<div class="info-td">
								<div class="title-td"><?=$BREND?></div>
								<div class="descr-td"><?=$CML2_ARTICLE?></div>
								<div class="name-td"><a href="<?=$v["DETAIL_PAGE_URL"]?>"><?=$NAME?></a></div>
								<div class="descr-td">Цвет: <?=$TSVET?></div>
								<div class="descr-td">Размер: <?=$RAZMER?></div>
								<div class="quantity">
									<div class="quantity-button quantity-down">–</div>
									<input cart_id="<?=$v['ID']?>" type="number" min="1" max="<?=$PRODUCT_INFO['CATALOG_QUANTITY']?>" value="<?=$v["QUANTITY"]?>">
									<div class="quantity-button quantity-up">+</div>
								</div>
								<div class="descr-td">Стоимость:</div>
							</div>
						</td>
						<td class="td-price">
							<?if (!atikaPriceNegotiable() && $v["FULL_PRICE"] != $v["PRICE_FMT"]):?>
								<div class="price-old"><?=$v["FULL_PRICE"]?></div>
							<?endif?>
							<div class="price-main"><?=(atikaPriceNegotiable() ? 'Цена договорная' : $v["PRICE_FMT"])?></div>
							<div class="remove-tr" onclick="<?=$cartId?>.removeItemFromCart(<?=$v['ID']?>)"><img src="<?=TMPL_URL?>/img/remove.svg" alt="alt"></div>
						</td>
					</tr>
				<?endforeach?><?//=$v["SUM"]?>
				</tbody>
				<tfoot>
					<tr>
						<td></td>
						<td>Сумма заказа:</td>
						<td><?=(atikaPriceNegotiable() ? 'Цена договорная' : CurrencyFormat($arResult["TOTAL_PRICE"], 'RUB'));?></td>
					</tr>
				</tfoot>
		</table>
			<?endforeach?>
		</div>
	</div>
	<div class="block-buttons">
		<div class="row">
			<div class="col-md-6">
				<a href="<?=$arParams["PATH_TO_ORDER"]?>" class="btn-main">ОФОРМИТЬ ЗАКАЗ</a>
			</div>
			<div class="col-md-6">
				<a href="#quick-modal" class="btn-main btn-main_gray fancybox">БЫСТРАЯ ПОКУПКА</a>
			</div>
			<div class="col-12">
				<a href="/personal/cart/" class="btn-main">ПЕРЕЙТИ В КОРЗИНУ</a>
			</div>
		</div>
	</div>
</div>
<script>
	BX.ready(function(){
		<?=$cartId?>.fixCart();
		jQuery('.quantity').each(function() {
			var spinner = jQuery(this),
			input = spinner.find('input[type="number"]'),
			btnUp = spinner.find('.quantity-up'),
			btnDown = spinner.find('.quantity-down'),
			min = input.attr('min'),
			max = input.attr('max');

			btnUp.off('click').click(function() {
				var oldValue = parseFloat(input.val());
				if (oldValue >= max) {
					var newVal = oldValue;
				} else {
					var newVal = oldValue + 1;
				}
				spinner.find("input").val(newVal);
				spinner.find("input").trigger("change");
			});

			btnDown.off('click').click(function() {
				var oldValue = parseFloat(input.val());
				if (oldValue <= min) {
					var newVal = oldValue;
				} else {
					var newVal = oldValue - 1;
				}
				spinner.find("input").val(newVal);
				spinner.find("input").trigger("change");
			});
		});
	});
</script>
<?
}
?>