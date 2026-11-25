<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?
// Футер опта по макету Figma (главная, узел 1270:2647)
global $USER;
$bOptSwitch = $USER->IsAuthorized() && array_intersect([9, 11], CUser::GetUserGroup($USER->GetID()));
$bRetailPrice = (isset($_SESSION['switch_price']) && $_SESSION['switch_price'] == 'Y');

// Колонка «Каталог»: те же разделы, что плашками на главной (флажок UF_OPT_MAIN, любой уровень; не отмечен ни один — все 2-го уровня)
$arFooterSections = [];
$obCache = new CPHPCache();
if($obCache->InitCache(3600, 'opt_footer_sections_main', '/opt_footer')){
	$arFooterSections = $obCache->GetVars();
}elseif(\Bitrix\Main\Loader::includeModule('iblock') && $obCache->StartDataCache()){
	global $CACHE_MANAGER;
	$CACHE_MANAGER->StartTagCache('/opt_footer');
	$CACHE_MANAGER->RegisterTag('iblock_id_80'); // сброс при правке разделов/флажка
	$arFilter = ['IBLOCK_ID' => 80, 'DEPTH_LEVEL' => 2, 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y'];
	if(CIBlockSection::GetList([], ['IBLOCK_ID' => 80, 'ACTIVE' => 'Y', 'UF_OPT_MAIN' => 1], false, ['ID'], ['nTopCount' => 1])->Fetch()){
		unset($arFilter['DEPTH_LEVEL']);
		$arFilter['UF_OPT_MAIN'] = 1;
	}
	$rs = CIBlockSection::GetList(['SORT' => 'ASC', 'NAME' => 'ASC'], $arFilter, false, ['ID', 'NAME', 'SECTION_PAGE_URL']);
	while($ar = $rs->GetNext()) $arFooterSections[] = ['NAME' => $ar['NAME'], 'URL' => $ar['SECTION_PAGE_URL']];
	$CACHE_MANAGER->EndTagCache();
	$obCache->EndDataCache($arFooterSections);
}
?>
<div class="opt-footer footer-inner"><?/* footer-inner нужен скрипту Аспро (кнопка «наверх») */?>
	<div class="opt-footer__top">
		<div class="maxwidth-theme">
			<div class="opt-footer__grid">
				<div class="opt-footer__info">
					<?if($bOptSwitch):?>
						<div class="opt-footer__switch">
							<a rel="user" href="#" class="opt-footer__btn opt-footer__btn--rrc js-switch-price<?=($bRetailPrice ? ' active' : '')?>">РРЦ</a>
							<a rel="admin" href="#" class="opt-footer__btn opt-footer__btn--b2b js-switch-price<?=($bRetailPrice ? '' : ' active')?>">В2В</a>
						</div>
					<?endif;?>
					<div class="opt-footer__legal">
						<a href="<?=SITE_DIR?>politika-konfidencialnosti/">Политика конфиденциальности</a>
						<a href="<?=SITE_DIR?>personal-data/">Согласие на обработку персональных данных</a>
					</div>
					<div class="opt-footer__req">ОГРН: 1149102108616<br>ИНН: 9102052660<br>КПП: 910201001</div>
				</div>
				<div class="opt-footer__menus">
					<div class="opt-footer__col opt-footer__col--catalog">
						<a href="/catalog/" class="opt-footer__title">Каталог</a>
						<ul class="opt-footer__list">
							<?foreach($arFooterSections as $arSection):?>
								<li><a href="<?=$arSection['URL']?>"><?=$arSection['NAME']?></a></li>
							<?endforeach;?>
							<li><a href="/sale/">Акции</a></li>
						</ul>
					</div>
					<div class="opt-footer__col opt-footer__col--about">
						<a href="/about/" class="opt-footer__title">О компании</a>
						<ul class="opt-footer__list">
							<li><a href="/info/brands/">Бренды</a></li>
							<li><a href="/news/">Новости</a></li>
							<li><a href="/contacts/">Контакты</a></li>
							<li><a href="/upload/katalog-atrium-opt.pdf" target="_blank" rel="noopener">Скачать каталог</a></li>
						</ul>
					</div>
				</div>
				<div class="opt-footer__contacts">
					<div class="opt-footer__phones">
						<a href="tel:+79787703530">+7 (978) 770-35-30</a>
						<a href="tel:+79788005556">+7 (978) 800-55-56</a>
					</div>
					<div class="opt-footer__socials">
						<a href="https://max.ru/u/f9LHodD0cOLg1tfV845Ibyigoat1ltA7tNTSXwqiyzU2xJKspm43pTsqutk" target="_blank" rel="nofollow noopener" title="MAX"><img src="/images/messengers/icon-max.svg" alt="MAX" width="32" height="32"></a>
						<a href="https://t.me/atriumceramic" target="_blank" rel="nofollow noopener" title="Telegram"><img src="/images/messengers/icon-tg.svg" alt="Telegram" width="32" height="32"></a>
					</div>
					<div class="opt-footer__text">
						<p><b>Адрес:</b><br>Республика Крым, г. Симферополь, ул. Вилара, 47</p>
						<p><b>Почта:</b><br><a href="mailto:aqada.da@yandex.ru">aqada.da@yandex.ru</a><br><a href="mailto:optplitka-crimea@yandex.ru">optplitka-crimea@yandex.ru</a></p>
						<p><b>Режим работы:</b><br>Пн–Пт 9:00–19:00</p>
						<p><b>Дежурный менеджер:</b><br>Сб - Вс 9:00–17:00</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="opt-footer__bottom">
		<div class="maxwidth-theme">
			<div class="opt-footer__line">
				<div class="opt-footer__bottom-legal">
					<a href="<?=SITE_DIR?>politika-konfidencialnosti/">Политика конфиденциальности</a>
					<a href="<?=SITE_DIR?>personal-data/">Согласие на обработку персональных данных</a>
				</div>
				<div class="opt-footer__copy">© Copyright <?=date('Y')?> г.</div>
				
			</div>
		</div>
	</div>
</div>
