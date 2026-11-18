<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/** Плитки корневых разделов каталога (редизайн 2026). Картинки из макета по коду раздела, иначе картинка раздела. */
$pics = array(
	'krovelnye-materialy' => 1, 'stenovye-materialy' => 2, 'gidro-i-paroizolyatsiya' => 3, 'teploizolyatsiya' => 4,
	'cherdachnye-lestnitsy' => 5, 'mansardnye-okna' => 6, 'ograzhdeniya' => 7, 'vodostochnye-sistemy' => 8,
	'sistemy-vodootvedeniya' => 9, 'terrasnaya-doska' => 10,
);
$roots = array();
$cnt = array();
foreach ($arResult['SECTIONS'] as $s) {
	if ($s['DEPTH_LEVEL'] == 1) $roots[$s['ID']] = $s;
	elseif ($s['DEPTH_LEVEL'] == 2) $cnt[$s['IBLOCK_SECTION_ID']] = (isset($cnt[$s['IBLOCK_SECTION_ID']]) ? $cnt[$s['IBLOCK_SECTION_ID']] : 0) + 1;
}
$word = function ($n) {
	$m10 = $n % 10; $m100 = $n % 100;
	if ($m10 == 1 && $m100 != 11) return 'подкатегория';
	if ($m10 >= 2 && $m10 <= 4 && ($m100 < 12 || $m100 > 14)) return 'подкатегории';
	return 'подкатегорий';
};
?>
<ul class="sk-cats">
<?php foreach ($roots as $s):
	$this->AddEditAction($s['ID'], $s['EDIT_LINK'], CIBlock::GetArrayByID($s['IBLOCK_ID'], 'SECTION_EDIT'));
	$n = isset($cnt[$s['ID']]) ? $cnt[$s['ID']] : 0;
	$img = isset($pics[$s['CODE']]) ? SK_PATH . '/img/cat-' . $pics[$s['CODE']] . '.webp' : (!empty($s['PICTURE']['SRC']) ? $s['PICTURE']['SRC'] : '');
?>
	<li class="sk-cat" id="<?= $this->GetEditAreaId($s['ID']) ?>">
		<a class="sk-cat__img" href="<?= $s['SECTION_PAGE_URL'] ?>" tabindex="-1" aria-hidden="true"><?php if ($img): ?><img src="<?= $img ?>" alt="" width="302" height="249" loading="lazy"><?php endif; ?></a>
		<a class="sk-cat__name" href="<?= $s['SECTION_PAGE_URL'] ?>"><?= $s['NAME'] ?></a>
		<?php if ($n): ?><span class="sk-cat__count"><?= $n . ' ' . $word($n) ?></span><?php endif; ?>
	</li>
<?php endforeach; ?>
</ul>
