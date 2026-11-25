<?php
/**
 * Товары опта, заведённые на сайте мимо 1С (импорт RGW-уголков 19.11.2026), помечены флажком
 * «Не выключать при обмене с 1С» (свойство OPT_LOCK_1C, инфоблок 80).
 * Обмен с 1С (1C_ELEMENT_ACTION=A) выключает всё, чего нет в выгрузке, — для помеченных товаров
 * снимаем ACTIVE=N. Вне обмена (руками в админке) выключение работает как обычно.
 */
AddEventHandler('iblock', 'OnBeforeIBlockElementUpdate', 'atriumOptLock1C');

function atriumOptLock1C(&$arFields)
{
	if (($arFields['ACTIVE'] ?? '') !== 'N' || empty($arFields['ID'])) {
		return;
	}
	if (strpos((string)($_SERVER['SCRIPT_NAME'] ?? ''), '1c_exchange') === false) {
		return;
	}
	$locked = CIBlockElement::GetList([], ['IBLOCK_ID' => 80, 'ID' => (int)$arFields['ID'], '!PROPERTY_OPT_LOCK_1C' => false], false, ['nTopCount' => 1], ['ID'])->Fetch();
	if ($locked) {
		unset($arFields['ACTIVE']);
	}
}

/**
 * «Коллекция» из дополнительных сведений 1С (KOLLEKTSIYA_1) — обмен 19.11 выключил свойство,
 * коллекция пропала с карточек и из фильтра мебели и сантехники. При обмене не даём выключать.
 */
AddEventHandler('iblock', 'OnBeforeIBlockPropertyUpdate', 'atriumOptKeepProps1C');

function atriumOptKeepProps1C(&$arFields)
{
	if (($arFields['ACTIVE'] ?? '') === 'N' && in_array((int)($arFields['ID'] ?? 0), [2152], true)
		&& strpos((string)($_SERVER['SCRIPT_NAME'] ?? ''), '1c_exchange') !== false) {
		unset($arFields['ACTIVE']);
	}
}
