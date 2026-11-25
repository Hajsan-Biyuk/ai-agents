<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $item
 * @var array $actualItem
 * @var array $minOffer
 * @var array $itemIds
 * @var array $price
 * @var array $measureRatio
 * @var bool $haveOffers
 * @var bool $showSubscribe
 * @var array $morePhoto
 * @var bool $showSlider
 * @var bool $itemHasDetailUrl
 * @var string $imgTitle
 * @var string $productTitle
 * @var string $buttonSizeClass
 * @var CatalogSectionComponent $component
 */

	$preview_picture = CFile::ResizeImageGet($item['PREVIEW_PICTURE']['ID'], ['width'=>1400, 'height'=>1400], BX_RESIZE_IMAGE_PROPORTIONAL);
	if(empty($preview_picture['src'])) $item['PREVIEW_PICTURE']['SRC'] = TMPL_URL . '/images/no_photo.png';
	else $item['PREVIEW_PICTURE']['SRC'] = $preview_picture['src'];
?>
<div class="inner_wrap TYPE_2">
	<div class="image_wrapper_block js-notice-block__image ">
		<div class="like_icons block">
			<div class="wish_item_button"><span title="Отложить" data-quantity="1" class="wish_item to rounded3 colored_theme_hover_bg" data-item="<?=$item['ID']?>" data-iblock="61"><i class="svg inline  svg-inline-wish ncolor colored" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="13" viewBox="0 0 16 13"><defs><style>.clsw-1{fill:#fff;fill-rule:evenodd;}</style></defs><path class="clsw-1" d="M506.755,141.6l0,0.019s-4.185,3.734-5.556,4.973a0.376,0.376,0,0,1-.076.056,1.838,1.838,0,0,1-1.126.357,1.794,1.794,0,0,1-1.166-.4,0.473,0.473,0,0,1-.1-0.076c-1.427-1.287-5.459-4.878-5.459-4.878l0-.019A4.494,4.494,0,1,1,500,135.7,4.492,4.492,0,1,1,506.755,141.6Zm-3.251-5.61A2.565,2.565,0,0,0,501,138h0a1,1,0,1,1-2,0h0a2.565,2.565,0,0,0-2.506-2,2.5,2.5,0,0,0-1.777,4.264l-0.013.019L500,145.1l5.179-4.749c0.042-.039.086-0.075,0.126-0.117l0.052-.047-0.006-.008A2.494,2.494,0,0,0,503.5,135.993Z" transform="translate(-492 -134)"></path></svg></i></span><span title="В отложенных" data-quantity="1" class="wish_item in added rounded3 colored_theme_bg" style="display: none;" data-item="<?=$item['ID']?>" data-iblock="61"><i class="svg inline  svg-inline-wish ncolor colored" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="13" viewBox="0 0 16 13"><defs><style>.clsw-1{fill:#fff;fill-rule:evenodd;}</style></defs><path class="clsw-1" d="M506.755,141.6l0,0.019s-4.185,3.734-5.556,4.973a0.376,0.376,0,0,1-.076.056,1.838,1.838,0,0,1-1.126.357,1.794,1.794,0,0,1-1.166-.4,0.473,0.473,0,0,1-.1-0.076c-1.427-1.287-5.459-4.878-5.459-4.878l0-.019A4.494,4.494,0,1,1,500,135.7,4.492,4.492,0,1,1,506.755,141.6Zm-3.251-5.61A2.565,2.565,0,0,0,501,138h0a1,1,0,1,1-2,0h0a2.565,2.565,0,0,0-2.506-2,2.5,2.5,0,0,0-1.777,4.264l-0.013.019L500,145.1l5.179-4.749c0.042-.039.086-0.075,0.126-0.117l0.052-.047-0.006-.008A2.494,2.494,0,0,0,503.5,135.993Z" transform="translate(-492 -134)"></path></svg></i></span></div>
		</div>
		<a href="<?=$item['DETAIL_PAGE_URL']?>" class="thumb">
			<span class="section-gallery-wrapper flexbox">
				<span class="section-gallery-wrapper__item _active">
					<img src="<?=$item['PREVIEW_PICTURE']['SRC']?>" class="img-responsive" alt="alt">
				</span>
			</span>
		</a>
	</div>
	<div class="item_info">
		<div class="item_info--top_block">
			<div class="item-title">
				<a href="<?=$item['DETAIL_PAGE_URL']?>" class="dark_link js-notice-block__title iq-catalog-item-title">
					<span><?=$productTitle?></span>
				</a>
			</div>
		</div>
		<div class="item_info--bottom_block">
			<div class="product_id_wrapper">
				<span><?=$item['PREVIEW_TEXT']?></span>
			</div>
			<div class="cost prices clearfix prices--with_icons_block<?if(!empty($item['PROPERTIES']['STARAYA_CENA']['VALUE'])):?> show_old_price__i<?endif;?>">
				<?// нет цены — не показываем цену, кнопка «Под заказ» ведёт в карточку товара
				$bOptNoPrice = empty($item['ATRIUM_NOT_FOR_SALE']) && (float)($price['RATIO_PRICE'] ?? 0) <= 0;?>
				<?if(!empty($item['ATRIUM_NOT_FOR_SALE'])):?>
				<span class="text-bold atrium-not-for-sale">Нет в продаже</span>
				<?elseif($bOptNoPrice):?>
				<?else:?>
				<?if(!empty($item['PROPERTIES']['STARAYA_CENA']['VALUE'])):?>
				<div class="old_price list_old_prices"><?=is_numeric(str_replace(array(' ', ','), array('', '.'), $item['PROPERTIES']['STARAYA_CENA']['VALUE'])) ? CurrencyFormat($item['PROPERTIES']['STARAYA_CENA']['VALUE'], 'RUB') : htmlspecialcharsbx($item['PROPERTIES']['STARAYA_CENA']['VALUE']).' ₽'?></div>
				<?elseif(!empty($price['RATIO_BASE_PRICE']) && $price['RATIO_PRICE'] < $price['RATIO_BASE_PRICE']):?>
				<div class="old_price list_old_prices"><?=$price['RATIO_BASE_PRICE']?></div>
				<?endif;?>
				<div class="price_matrix_wrapper">
					<div class="price font-bold font_mxs">
						<span class="values_wrapper">
							<span class="price_value"><?=$price['BASE_PRICE']?></span>
							<span class="price_currency"> ₽</span>
						</span>
					</div>
				</div>
				<?endif;?>
				<?if($bOptNoPrice):?>
				<div class="icons-basket-wrapper offer_buy_block">
					<div class="button_block">
						<a href="<?=$item['DETAIL_PAGE_URL']?>" class="btn-exlg btn btn-default transition_bg opt-btn-order">Под заказ</a>
					</div>
				</div>
				<?else:?>
				<div class="icons-basket-wrapper offer_buy_block ce_cmp_hidden">
					<div class="button_block">
						<span data-value="<?=$price['RATIO_PRICE']?>" data-currency="RUB" data-item="<?=$actualItem['ID']?>" data-float_ratio="1" data-ratio="1" data-bakset_div="bx_basket_div_<?=$actualItem['ID']?>" data-props="" data-part_props="Y" data-add_props="Y" data-empty_props="Y" data-offers="" data-iblockid="61" data-quantity="1" class="btn-exlg to-cart btn btn-default transition_bg animate-load has-ripple">
							<span>В корзину</span>
						</span>
					</div>
					<a rel="nofollow" href="/basket/" class="btn-exlg in-cart btn btn-default transition_bg" data-item="<?=$actualItem['ID']?>" style="display:none;"><i class="svg inline  svg-inline-fw ncolor colored" aria-hidden="true" title="В корзине"><svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18"><path data-name="Rounded Rectangle 906 copy 3" class="cls-1" d="M1005.97,4556.22l-1.01,4.02a0.031,0.031,0,0,0-.01.02,0.87,0.87,0,0,1-.14.29,0.423,0.423,0,0,1-.05.07,0.7,0.7,0,0,1-.2.18,0.359,0.359,0,0,1-.1.07,0.656,0.656,0,0,1-.21.08,1.127,1.127,0,0,1-.18.03,0.185,0.185,0,0,1-.07.02H993c-0.03,0-.056-0.02-0.086-0.02a1.137,1.137,0,0,1-.184-0.04,0.779,0.779,0,0,1-.207-0.08c-0.031-.02-0.059-0.04-0.088-0.06a0.879,0.879,0,0,1-.223-0.22s-0.007-.01-0.011-0.01a1,1,0,0,1-.172-0.43l-1.541-6.14H988a1,1,0,1,1,0-2h3.188a0.3,0.3,0,0,1,.092.02,0.964,0.964,0,0,1,.923.76l1.561,6.22h9.447l0.82-3.25a1,1,0,0,1,1.21-.73A0.982,0.982,0,0,1,1005.97,4556.22Zm-7.267.47c0,0.01,0,.01,0,0.01a1,1,0,0,1-1.414,0l-2.016-2.03a0.982,0.982,0,0,1,0-1.4,1,1,0,0,1,1.414,0l1.305,1.31,4.3-4.3a1,1,0,0,1,1.41,0,1.008,1.008,0,0,1,0,1.42ZM995,4562a3,3,0,1,1-3,3A3,3,0,0,1,995,4562Zm0,4a1,1,0,1,0-1-1A1,1,0,0,0,995,4566Zm7-4a3,3,0,1,1-3,3A3,3,0,0,1,1002,4562Zm0,4a1,1,0,1,0-1-1A1,1,0,0,0,1002,4566Z" transform="translate(-987 -4550)"></path></svg></i><span>В корзине</span></a>
				</div>
				<?endif;?>
			</div>
		</div>
	</div>
</div>