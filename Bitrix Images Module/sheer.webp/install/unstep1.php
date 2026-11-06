<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

global $APPLICATION;

?>
<form action="<?= $APPLICATION->GetCurPage(); ?>" method="post">
    <?= bitrix_sessid_post(); ?>
    <input type="hidden" name="lang" value="<?= LANG; ?>">
    <input type="hidden" name="id" value="sheer.webp">
    <input type="hidden" name="uninstall" value="Y">
    <input type="hidden" name="step" value="2">
    <?php echo CAdminMessage::ShowMessage('Будет удалена регистрация модуля, агент и правила Apache в /upload/.htaccess. Оригиналы изображений не затрагиваются.'); ?>
    <p>
        <label>
            <input type="checkbox" name="remove_webp" value="Y" checked>
            Удалить созданные WebP-копии (откат к оригиналам)
        </label>
    </p>
    <input type="submit" name="inst" value="Удалить модуль" class="adm-btn-save">
</form>
