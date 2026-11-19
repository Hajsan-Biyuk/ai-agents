<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/** Новый подвал (редизайн 2026). Подключается из footer.php при SK_NEW. */
global $APPLICATION;
?>
<footer class="sk-scope sk-footer">
	<div class="sk-container sk-footer__inner">
		<div class="sk-footer__col">
			<p class="sk-footer__title">Компания</p>
			<?php sk_menu('bottom', 'sk-footer'); ?>
		</div>
		<div class="sk-footer__col">
			<p class="sk-footer__title">Каталог</p>
			<?php sk_menu('catalog-link', 'sk-footer', 'wide'); ?>
		</div>
		<div class="sk-footer__col sk-footer__col--brands">
			<p class="sk-footer__title">Производители</p>
			<?php sk_menu('brends', 'sk-footer', 'wide'); ?>
		</div>
		<div class="sk-footer__col sk-footer__col--contacts">
			<p class="sk-footer__title">Контакты</p>
			<ul class="sk-footer__list sk-footer__list--contacts">
				<li class="sk-footer__item"><a class="sk-footer__link" href="tel:+78652334041"><?php $APPLICATION->IncludeFile(SITE_DIR . 'include/tel.php', array(), array('MODE' => 'text')); ?></a></li>
				<li class="sk-footer__item"><a class="sk-footer__link" href="mailto:info@stroy-krovlya26.ru"><?php $APPLICATION->IncludeFile(SITE_DIR . 'include/mail.php', array(), array('MODE' => 'text')); ?></a></li>
				<li class="sk-footer__item"><?php $APPLICATION->IncludeFile(SITE_DIR . 'include/adress.php', array(), array('MODE' => 'text')); ?></li>
				<li class="sk-footer__item"><a class="sk-footer__link sk-footer__link--u sk-footer__link--big" href="/kontakty/">Как проехать</a></li>
			</ul>
			<p class="sk-footer__note"><?= sk_home('FOOTER_NOTE', 'Обращаем ваше внимание на то, что данный интернет-сайт, а также вся информация о товарах и ценах, предоставленная на нём, носит исключительно информационный характер и ни при каких условиях не является публичной офертой, определяемой положениями Статьи 437 Гражданского кодекса Российской Федерации.') ?></p>
		</div>
	</div>
	<div class="sk-footer__bottom">
		<div class="sk-container sk-footer__bottom-inner">
			<div class="sk-footer__docs">
				<a class="sk-footer__doc" href="/politika-konfidentsialnosti/">Политика конфиденциальности</a>
				<a class="sk-footer__doc" href="/soglashenie-na-obrabotku-personalnykh-dannykh/">Согласие на обработку персональных данных</a>
			</div>
			<span class="sk-footer__copy">© 2010—<?= date('Y') ?> ООО «СТРОЙ КРОВЛЯ»<?= SK_INN ? ', ИНН ' . SK_INN : '' ?></span>
			<a class="sk-footer__dev" href="https://sheer.ru/" target="_blank" rel="noopener">Сделано в <img src="<?= SK_PATH ?>/img/sheer.svg" alt="Sheer" width="57" height="13"></a>
		</div>
	</div>
</footer>
