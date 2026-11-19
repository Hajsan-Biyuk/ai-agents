<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/** Новая шапка (редизайн 2026). Подключается из header.php при SK_NEW. */
global $APPLICATION;
$skHome = $APPLICATION->GetCurPage() === '/';
ob_start();
include $_SERVER['DOCUMENT_ROOT'] . '/include/tel-open.php'; // там же запуск заявок в Telegram — не убирать
$skTel = trim(strip_tags(ob_get_clean()));
$skTelText = '+7 (8652) 33-40-41';
?>
<header class="sk-scope sk-header<?= $skHome ? '' : ' sk-header--solid' ?>">
	<div class="sk-container sk-header__inner">
		<a class="sk-header__logo" href="/"><img src="<?= SK_PATH ?>/img/logo.png" alt="СтройКровля" width="129" height="68"></a>

		<nav class="sk-header__nav" aria-label="Основное меню">
			<button class="sk-header__burger sk-js-menu-toggle" type="button" aria-expanded="false" aria-controls="sk-menu" aria-label="Открыть меню каталога">
				<img src="<?= SK_PATH ?>/img/ic-burger.svg" alt="" width="24" height="24">
			</button>
			<ul class="sk-header__list">
<?php $APPLICATION->IncludeFile(SITE_DIR . 'include/sk_topmenu.php', array(), array('MODE' => 'php')); ?>			</ul>

			<div class="sk-menu" id="sk-menu" aria-hidden="true">
				<div class="sk-menu__panel">
					<div class="sk-menu__head">
						<a class="sk-menu__logo" href="/"><img src="<?= SK_PATH ?>/img/logo.png" alt="СтройКровля" width="129" height="68"></a>
						<button class="sk-menu__close sk-js-menu-close" type="button" aria-label="Закрыть меню"></button>
					</div>
					<div class="sk-menu__group sk-menu__group--catalog">
						<a class="sk-menu__title" href="/catalog/">Каталог</a>
						<?php sk_menu('catalog-link', 'sk-menu'); ?>
					</div>
					<div class="sk-menu__group sk-menu__group--company">
						<span class="sk-menu__title">Компания</span>
						<?php sk_menu('bottom', 'sk-menu'); ?>
					</div>
					<div class="sk-menu__contacts">
						<a class="sk-menu__phone" href="tel:<?= htmlspecialcharsbx($skTel) ?>"><?= $skTelText ?></a>
						<a class="sk-btn sk-menu__callback" data-fancybox data-src="#OrderCall" href="javascript:;">Заказать звонок</a>
					</div>
				</div>
			</div>
		</nav>

		<div class="sk-header__actions">
			<a class="sk-header__icon SearchPopup" href="javascript:;" aria-label="Поиск по сайту"><img src="<?= SK_PATH ?>/img/ic-search.svg" alt="" width="20" height="20"></a>
			<a class="sk-header__icon sk-header__icon--phone" href="tel:<?= htmlspecialcharsbx($skTel) ?>" aria-label="Позвонить <?= $skTelText ?>">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M21 16.42v3.54a1 1 0 0 1-.93 1A15.6 15.6 0 0 1 3.04 3.93a1 1 0 0 1 1-.93h3.54a.5.5 0 0 1 .5.45c.02.23.04.46.08.69.2 1.4.6 2.72 1.18 3.95a.46.46 0 0 1-.15.57L7.03 9.98a13.05 13.05 0 0 0 6.99 6.99l1.32-2.16a.46.46 0 0 1 .58-.15c1.23.58 2.56.99 3.95 1.18.23.04.46.06.69.08a.5.5 0 0 1 .45.5Z" fill="#fff"/></svg>
			</a>
			<a class="sk-header__phone" href="tel:<?= htmlspecialcharsbx($skTel) ?>" onclick="if(window.ym)ym(38770565,'reachGoal','Perehod_po_nomeru_telefona');"><?= $skTelText ?></a>
			<a class="sk-btn sk-btn--sm sk-header__callback" data-fancybox data-src="#OrderCall" href="javascript:;">Заказать звонок</a>
		</div>
	</div>
</header>
