<?php
// ИНН обязателен при регистрации на опте: поле пользователя UF_INN, форма main.register/main.
// Подключается из header.php, т.к. шаблон компонента выполняется уже после регистрации.
\Bitrix\Main\EventManager::getInstance()->addEventHandler('main', 'OnBeforeUserRegister', function (&$arFields) {
	$inn = preg_replace('/\D/', '', (string)($arFields['UF_INN'] ?? $_POST['UF_INN'] ?? ''));
	if (!preg_match('/^(\d{10}|\d{12})$/', $inn)) {
		$GLOBALS['APPLICATION']->ThrowException('Укажите ИНН: 10 или 12 цифр.');
		return false;
	}
	$arFields['UF_INN'] = $inn;
});
