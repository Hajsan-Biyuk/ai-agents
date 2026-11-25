<?php
// Форма раздела каталога опта (инфоблок 80): «Изображение в правом меню» (UF_MENU_PICTURE) — сразу под «Изображение».
// Каталог открывает раздел через cat_section_edit.php, обычный инфоблок — iblock_section_edit.php.
// Не через «Настройку формы»: там нужно перечислить все поля всех вкладок, иначе пропадут.
AddEventHandler('main', 'OnProlog', function () {
	if (!defined('ADMIN_SECTION') || !in_array(basename((string)($_SERVER['SCRIPT_NAME'] ?? '')), ['iblock_section_edit.php', 'cat_section_edit.php'], true) || (int)($_REQUEST['IBLOCK_ID'] ?? 0) !== 80) {
		return;
	}
	\Bitrix\Main\Page\Asset::getInstance()->addString(
		"<script>document.addEventListener('DOMContentLoaded',function(){var p=document.getElementById('tr_PICTURE'),u=document.getElementById('tr_UF_MENU_PICTURE');if(p&&u)p.parentNode.insertBefore(u,p.nextSibling);});</script>"
	);
});
