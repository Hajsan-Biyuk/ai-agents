<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?
global $arTheme, $arRegion, $bLongHeader3;
$arRegions = CMaxRegionality::getRegions();
if($arRegion)
	$bPhone = ($arRegion['PHONES'] ? true : false);
else
	$bPhone = ((int)$arTheme['HEADER_PHONES'] ? true : false);
$logoClass = ($arTheme['COLORED_LOGO']['VALUE'] !== 'Y' ? '' : ' colored');
$bLongHeader3 = true;

global $USER;
?>

<div class="fixed-buttons">
	<a href="/basket/" class="fixed-buttons__btn" title="Корзина">
		<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3.33336 5.34501L0.630859 2.64334L1.81003 1.46417L4.51169 4.16667H17.2134C17.3432 4.16666 17.4713 4.19702 17.5874 4.25531C17.7035 4.31361 17.8043 4.39823 17.8819 4.50242C17.9594 4.60661 18.0116 4.72749 18.0341 4.85541C18.0566 4.98333 18.049 5.11475 18.0117 5.23917L16.0117 11.9058C15.9602 12.0776 15.8548 12.2281 15.711 12.3352C15.5671 12.4422 15.3926 12.5 15.2134 12.5H5.00003V14.1667H14.1667V15.8333H4.16669C3.94568 15.8333 3.73372 15.7455 3.57744 15.5893C3.42116 15.433 3.33336 15.221 3.33336 15V5.34501ZM4.58336 19.1667C4.25184 19.1667 3.9339 19.035 3.69948 18.8006C3.46506 18.5661 3.33336 18.2482 3.33336 17.9167C3.33336 17.5852 3.46506 17.2672 3.69948 17.0328C3.9339 16.7984 4.25184 16.6667 4.58336 16.6667C4.91488 16.6667 5.23282 16.7984 5.46724 17.0328C5.70166 17.2672 5.83336 17.5852 5.83336 17.9167C5.83336 18.2482 5.70166 18.5661 5.46724 18.8006C5.23282 19.035 4.91488 19.1667 4.58336 19.1667ZM14.5834 19.1667C14.2518 19.1667 13.9339 19.035 13.6995 18.8006C13.4651 18.5661 13.3334 18.2482 13.3334 17.9167C13.3334 17.5852 13.4651 17.2672 13.6995 17.0328C13.9339 16.7984 14.2518 16.6667 14.5834 16.6667C14.9149 16.6667 15.2328 16.7984 15.4672 17.0328C15.7017 17.2672 15.8334 17.5852 15.8334 17.9167C15.8334 18.2482 15.7017 18.5661 15.4672 18.8006C15.2328 19.035 14.9149 19.1667 14.5834 19.1667Z" fill="#2B5840" /></svg>
	</a>
	<script>
	(function () {
		function atriumApplyFixedCount(link, count) {
			if (!link) {
				return;
			}
			count = parseInt(count, 10) || 0;
			var badge = link.querySelector('.fixed-buttons__count');
			if (count > 0) {
				link.classList.add('is-filled');
				if (!badge) {
					badge = document.createElement('span');
					badge.className = 'fixed-buttons__count';
					link.appendChild(badge);
				}
				badge.textContent = count > 99 ? '99+' : String(count);
			} else {
				link.classList.remove('is-filled');
				if (badge) {
					badge.remove();
				}
			}
		}
		function atriumApplyFixedBasketCount(count) {
			atriumApplyFixedCount(document.querySelector('.fixed-buttons a[title="Корзина"]'), count);
		}
		function atriumFixedGroupCount(group) {
			var counters = window.arBasketAsproCounters;
			if (counters && counters[group] && typeof counters[group].COUNT !== 'undefined') {
				return counters[group].COUNT;
			}
			var basket = window.arBasketAspro;
			if (basket && basket[group] && typeof basket[group] === 'object') {
				return Object.keys(basket[group]).length;
			}
			return null;
		}
		function atriumSyncFixedBasket() {
			var count = null;
			if (window.arBasketAsproCounters && arBasketAsproCounters.READY && typeof arBasketAsproCounters.READY.COUNT !== 'undefined') {
				count = arBasketAsproCounters.READY.COUNT;
			} else if (window.arBasketPrices && typeof arBasketPrices.BASKET_COUNT !== 'undefined' && arBasketPrices.BASKET_COUNT !== '') {
				count = arBasketPrices.BASKET_COUNT;
			}
			if (count !== null) {
				atriumApplyFixedBasketCount(count);
			}
			var delay = atriumFixedGroupCount('DELAY');
			if (delay !== null) {
				atriumApplyFixedCount(document.querySelector('.fixed-buttons a.delay'), delay);
			}
			var compare = atriumFixedGroupCount('COMPARE');
			if (compare !== null) {
				atriumApplyFixedCount(document.querySelector('.fixed-buttons a.compare, .fixed-buttons a[title="Сравнение"]'), compare);
			}
			// опт: arBasketAsproCounters может отставать (DELAY=0 при отложенных) — сверяемся с сервером
			clearTimeout(window.atriumFixedReload);
			window.atriumFixedReload = setTimeout(atriumLoadFixedBasket, 300);
		}
		function atriumLoadFixedBasket() {
			fetch('/ajax/show_basket_actual.php', {credentials: 'same-origin'})
				.then(function (response) { return response.text(); })
				.then(function (text) {
					var match = text.match(/["']BASKET_COUNT["']\s*:\s*["']?(\d+)/);
					if (match) {
						atriumApplyFixedBasketCount(match[1]);
					}
					var delayMatch = text.match(/["']DELAY_COUNT["']\s*:\s*["']?(\d+)/);
					if (delayMatch) {
						atriumApplyFixedCount(document.querySelector('.fixed-buttons a.delay'), delayMatch[1]);
					}
				})
				.catch(function () {});
		}
		function atriumBindFixedBasket() {
			if (window.BX && BX.addCustomEvent && !window.atriumFixedBasketBound) {
				window.atriumFixedBasketBound = true;
				BX.addCustomEvent('onCompleteAction', function (eventdata) {
					if (eventdata && eventdata.action === 'loadBasket') {
						atriumSyncFixedBasket();
					}
				});
			}
			if (typeof window.SetActualBasketFlyCounters === 'function' && !window.SetActualBasketFlyCounters.atriumWrapped) {
				var orig = window.SetActualBasketFlyCounters;
				window.SetActualBasketFlyCounters = function () {
					var result = orig.apply(this, arguments);
					atriumSyncFixedBasket();
					return result;
				};
				window.SetActualBasketFlyCounters.atriumWrapped = true;
			}
		}
		document.addEventListener('DOMContentLoaded', function () {
			atriumBindFixedBasket();
			atriumLoadFixedBasket();
		});
		window.addEventListener('load', atriumBindFixedBasket);
		window.atriumApplyFixedBasketCount = atriumApplyFixedBasketCount;
	})();
	</script>
	<button type="button" class="fixed-buttons__btn fixed-buttons__toggle" aria-expanded="false" aria-controls="fixed-buttons-extra" title="Ещё">
		<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M4.2 7.2L10 13L15.8 7.2" stroke="#2B5840" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
	</button>
	<div class="fixed-buttons__extra" id="fixed-buttons-extra" aria-hidden="true">
		<div class="fixed-buttons__extra-inner">
			<? if($USER->IsAuthorized()){ ?>
			<a href="/personal/" class="fixed-buttons__btn" title="Личный кабинет">
				<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_359_902)"><path d="M16.6666 18.3333H3.33325V16.6667C3.33325 15.5616 3.77224 14.5018 4.55364 13.7204C5.33504 12.939 6.39485 12.5 7.49992 12.5H12.4999C13.605 12.5 14.6648 12.939 15.4462 13.7204C16.2276 14.5018 16.6666 15.5616 16.6666 16.6667V18.3333ZM9.99992 10.8333C9.34331 10.8333 8.69313 10.704 8.0865 10.4527C7.47987 10.2015 6.92868 9.83315 6.46438 9.36886C6.00009 8.90457 5.63179 8.35337 5.38052 7.74675C5.12925 7.14012 4.99992 6.48994 4.99992 5.83333C4.99992 5.17672 5.12925 4.52654 5.38052 3.91991C5.63179 3.31328 6.00009 2.76209 6.46438 2.29779C6.92868 1.8335 7.47987 1.4652 8.0865 1.21393C8.69313 0.962657 9.34331 0.833328 9.99992 0.833328C11.326 0.833328 12.5978 1.36011 13.5355 2.29779C14.4731 3.23548 14.9999 4.50725 14.9999 5.83333C14.9999 7.15941 14.4731 8.43118 13.5355 9.36886C12.5978 10.3065 11.326 10.8333 9.99992 10.8333Z" fill="#2B5840" /></g><defs><clipPath id="clip0_359_902"><rect width="20" height="20" fill="white" /></clipPath></defs></svg>
			</a>
			<? }else{ ?>
			<a href="/personal/" data-event="jqm" data-param-type="auth" data-param-backurl="/" data-name="auth" class="fixed-buttons__btn" title="Войти">
				<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_359_902)"><path d="M16.6666 18.3333H3.33325V16.6667C3.33325 15.5616 3.77224 14.5018 4.55364 13.7204C5.33504 12.939 6.39485 12.5 7.49992 12.5H12.4999C13.605 12.5 14.6648 12.939 15.4462 13.7204C16.2276 14.5018 16.6666 15.5616 16.6666 16.6667V18.3333ZM9.99992 10.8333C9.34331 10.8333 8.69313 10.704 8.0865 10.4527C7.47987 10.2015 6.92868 9.83315 6.46438 9.36886C6.00009 8.90457 5.63179 8.35337 5.38052 7.74675C5.12925 7.14012 4.99992 6.48994 4.99992 5.83333C4.99992 5.17672 5.12925 4.52654 5.38052 3.91991C5.63179 3.31328 6.00009 2.76209 6.46438 2.29779C6.92868 1.8335 7.47987 1.4652 8.0865 1.21393C8.69313 0.962657 9.34331 0.833328 9.99992 0.833328C11.326 0.833328 12.5978 1.36011 13.5355 2.29779C14.4731 3.23548 14.9999 4.50725 14.9999 5.83333C14.9999 7.15941 14.4731 8.43118 13.5355 9.36886C12.5978 10.3065 11.326 10.8333 9.99992 10.8333Z" fill="#2B5840" /></g><defs><clipPath id="clip0_359_902"><rect width="20" height="20" fill="white" /></clipPath></defs></svg>
			</a>
			<? } ?>
			<a rel="nofollow" class="fixed-buttons__btn delay" href="/basket/#delayed" title="Отложенные">
				<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_359_905)"><path d="M13.7501 2.5C16.2817 2.5 18.3334 4.58333 18.3334 7.5C18.3334 13.3333 12.0834 16.6667 10.0001 17.9167C7.91675 16.6667 1.66675 13.3333 1.66675 7.5C1.66675 4.58333 3.75008 2.5 6.25008 2.5C7.80008 2.5 9.16675 3.33333 10.0001 4.16667C10.8334 3.33333 12.2001 2.5 13.7501 2.5Z" fill="#2B5840" /></g><defs><clipPath id="clip0_359_905"><rect width="20" height="20" fill="white" /></clipPath></defs></svg>
			</a>
			<div class="fixed-buttons__btn" data-event="jqm" data-param-form_id="ASK" data-name="ask" title="Написать">
				<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_359_908)"><path d="M2.50008 2.5H17.5001C17.7211 2.5 17.9331 2.5878 18.0893 2.74408C18.2456 2.90036 18.3334 3.11232 18.3334 3.33333V16.6667C18.3334 16.8877 18.2456 17.0996 18.0893 17.2559C17.9331 17.4122 17.7211 17.5 17.5001 17.5H2.50008C2.27907 17.5 2.06711 17.4122 1.91083 17.2559C1.75455 17.0996 1.66675 16.8877 1.66675 16.6667V3.33333C1.66675 3.11232 1.75455 2.90036 1.91083 2.74408C2.06711 2.5878 2.27907 2.5 2.50008 2.5ZM10.0501 9.73583L4.70675 5.19833L3.62758 6.46833L10.0609 11.9308L16.3784 6.46417L15.2884 5.20333L10.0509 9.73583H10.0501Z" fill="#2B5840" /></g><defs><clipPath id="clip0_359_908"><rect width="20" height="20" fill="white" /></clipPath></defs></svg>
			</div>
			<div class="fixed-buttons__btn" data-event="jqm" data-param-form_id="REVIEW" data-name="review" title="Отзыв">
				<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_359_911)"><path d="M8.33341 2.5H11.6667C13.4349 2.5 15.1306 3.20238 16.3808 4.45262C17.631 5.70286 18.3334 7.39856 18.3334 9.16667C18.3334 10.9348 17.631 12.6305 16.3808 13.8807C15.1306 15.131 13.4349 15.8333 11.6667 15.8333V18.75C7.50008 17.0833 1.66675 14.5833 1.66675 9.16667C1.66675 7.39856 2.36913 5.70286 3.61937 4.45262C4.86961 3.20238 6.5653 2.5 8.33341 2.5Z" fill="#2B5840" /></g><defs><clipPath id="clip0_359_911"><rect width="20" height="20" fill="white" /></clipPath></defs></svg>
			</div>
		</div>
	</div>
	<div class="fixed-buttons__social">
		<a class="fixed-buttons__btn fixed-buttons__soc" href="https://vk.com/atrium82" target="_blank" rel="nofollow" title="Вконтакте">
			<img src="/images/messengers/icon-vk.svg" alt="Вконтакте" width="28" height="28">
		</a>
		<a class="fixed-buttons__btn fixed-buttons__soc" href="https://t.me/atriumceramic" target="_blank" rel="nofollow" title="Telegram">
			<img src="/images/messengers/icon-tg.svg" alt="Telegram" width="28" height="28">
		</a>
		<a class="fixed-buttons__btn fixed-buttons__soc" href="https://max.ru/u/f9LHodD0cOLg1tfV845Ibyigoat1ltA7tNTSXwqiyzU2xJKspm43pTsqutk" target="_blank" rel="nofollow" title="MAX">
			<img src="/images/messengers/icon-max.svg" alt="MAX" width="28" height="28">
		</a>
	</div>
</div>

<?
// РРЦ / В2В: только для групп «Оптовые пользователи» (9) и «Оптовые пользователи+» (11)
$bOptSwitch = false;
if($USER->IsAuthorized()){
	$bOptSwitch = (bool)array_intersect([9, 11], CUser::GetUserGroup($USER->GetID()));
}
$bRetailPrice = (isset($_SESSION['switch_price']) && $_SESSION['switch_price'] == 'Y');
?>
<div class="header-wrapper header-v11 centered header-opt">
	<div class="logo_and_menu-row">
		<div class="logo-row">
			<div class="maxwidth-theme">
				<div class="line-header">
					<div class="logo-block text-center nopadding">
						<div class="logotype<?=$logoClass?>">
							<a href="/">
								<img src="/images/logo.svg" alt="АTRIUM" title="АTRIUM" data-src="">
							</a>
						</div>
					</div>

					<div class="line-header__right">
						<div class="wrap_icon inner-table-block">
							<div class="header-right-contacts">
								<div class="header-phone header-phone--row">
									<div><a rel="nofollow" class="no-decript" href="tel:+79787703530">+7 (978) 770-35-30</a></div>
									<div><a rel="nofollow" class="no-decript" href="tel:+79788005556">+7 (978) 800-55-56</a></div>
								</div>
								<?if($USER->IsAuthorized()):?>
									<a href="/personal/" class="header-login">
										<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M12 14v8H4a8 8 0 0 1 8-8Zm0-1c-3.315 0-6-2.685-6-6s2.685-6 6-6 6 2.685 6 6-2.685 6-6 6Zm6 4 2-2 2 2-2 2v3h-4v-1l1-1-1-1 1-1-1-1Z" fill="#2B5840"/></svg>
										<span>Кабинет</span>
									</a>
								<?else:?>
									<a href="/personal/" class="header-login" data-event="jqm" data-param-type="auth" data-param-backurl="<?=htmlspecialcharsbx($APPLICATION->GetCurPage())?>" data-name="auth">
										<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M12 14v8H4a8 8 0 0 1 8-8Zm0-1c-3.315 0-6-2.685-6-6s2.685-6 6-6 6 2.685 6 6-2.685 6-6 6Zm6 4 2-2 2 2-2 2v3h-4v-1l1-1-1-1 1-1-1-1Z" fill="#2B5840"/></svg>
										<span>Войти</span>
									</a>
								<?endif;?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div><?// class=logo-row?>
	</div>
	<div class="menu-row middle-block bglight">
		<hr class="left_border">
		<div class="maxwidth-theme">
			<div class="row">
				<div class="col-md-12">
					<div class="menu-only menu-wrapper">
						<nav class="mega-menu sliced">
							<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
								array(
									"COMPONENT_TEMPLATE" => ".default",
									"PATH" => SITE_DIR."include/menu/menu.".($arTheme["HEADER_TYPE"]["LIST"][$arTheme["HEADER_TYPE"]["VALUE"]]["ADDITIONAL_OPTIONS"]["MENU_HEADER_TYPE"]["VALUE"] == "Y" ? "top_catalog_wide" : "top").".php",
									"AREA_FILE_SHOW" => "file",
									"AREA_FILE_SUFFIX" => "",
									"AREA_FILE_RECURSIVE" => "Y",
									"EDIT_TEMPLATE" => "include_area.php"
								),
								false, array("HIDE_ICONS" => "Y")
							);?>
						</nav>

						<?if($bOptSwitch):?>
							<div class="price-switch">
								<a rel="user" href="#" class="price-switch__btn price-switch__btn--rrc js-switch-price<?=($bRetailPrice ? ' active' : '')?>" title="Показывать розничные цены (для работы с клиентами)">РРЦ</a>
								<a rel="admin" href="#" class="price-switch__btn price-switch__btn--b2b js-switch-price<?=($bRetailPrice ? '' : ' active')?>" title="Показывать оптовые цены">В2В</a>
							</div>
						<?endif;?>

						<div class="type-wrap type-wrap--search">
							<?$APPLICATION->IncludeComponent("arturgolubev:search.title", '', array(
									"CATEGORY_0" => array("iblock_1c_catalog"),
									"CATEGORY_0_TITLE" => "ALL",
									"CATEGORY_0_iblock_1c_catalog" => array("80"),
									"CATEGORY_OTHERS_TITLE" => "OTHER",
									"CHECK_DATES" => "Y",
									"COMPOSITE_FRAME_MODE" => "A",
									"COMPOSITE_FRAME_TYPE" => "AUTO",
									"CONTAINER_ID" => "title-search",
									"CONVERT_CURRENCY" => "N",
									"INPUT_ID" => "title-search-input",
									"NUM_CATEGORIES" => "1",
									"ORDER" => "date",
									"PAGE" => CMax::GetFrontParametrValue("CATALOG_PAGE_URL"),
									"PREVIEW_HEIGHT" => "38",
									"PREVIEW_TRUNCATE_LEN" => "50",
									"PREVIEW_WIDTH" => "38",
									"PRICE_CODE" => array(0=>"BASE",),
									"PRICE_VAT_INCLUDE" => "Y",
									"SHOW_ANOUNCE" => "N",
									"SHOW_INPUT" => "Y",
									"SHOW_OTHERS" => "N",
									"SHOW_PREVIEW" => "Y",
									"TOP_COUNT" => "10",
									"USE_LANGUAGE_GUESS" => "Y",
								),
								false, array("HIDE_ICONS" => "Y")
							);?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<hr class="right_border">
	</div>
	<div class="line-row visible-xs"></div>
</div>
<div id="header-fixed-margin"></div>
