<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php';

global $USER, $APPLICATION;

if (!$USER->IsAdmin()) {
    $APPLICATION->AuthForm('Доступ запрещён');
}

if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && check_bitrix_sessid()
    && isset($_POST['toggle_price'])
) {
    $next = atikaPriceNegotiable() ? 'N' : 'Y';
    COption::SetOptionString('main', 'atika_negotiable_price', $next);
    atikaClearPublicCache();
    LocalRedirect($APPLICATION->GetCurPage() . '?lang=' . LANGUAGE_ID . '&saved=Y');
}

$enabled = atikaPriceNegotiable();
$APPLICATION->SetTitle('Цена договорная');

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php';

if (isset($_GET['saved']) && $_GET['saved'] === 'Y') {
    CAdminMessage::ShowNote(
        $enabled
            ? 'На сайте вместо цен показывается «Цена договорная».'
            : 'На сайте снова показываются обычные цены.'
    );
}
?>
<form method="post">
    <?= bitrix_sessid_post(); ?>
    <p>
        <?php if ($enabled) { ?>
            Сейчас на сайте вместо цен написано «Цена договорная». Суммы в каталоге не удалены.
        <?php } else { ?>
            Сейчас на сайте показываются обычные цены.
        <?php } ?>
    </p>
    <input type="submit" name="toggle_price" class="adm-btn-save" value="<?= $enabled ? 'Вернуть цены' : 'Включить «Цена договорная»' ?>">
</form>
<?php
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php';
