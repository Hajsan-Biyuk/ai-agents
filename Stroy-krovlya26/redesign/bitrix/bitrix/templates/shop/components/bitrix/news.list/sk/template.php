<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/** Блоки главной (редизайн 2026). Вид задаёт параметр SK_VIEW: stats | stages | brands | portfolio | promo. */
use Bas\Pict;
$view = isset($arParams['SK_VIEW']) ? $arParams['SK_VIEW'] : '';
$items = $arResult['ITEMS'];
if (empty($items)) return;
$edit = function ($it) {
	$this->AddEditAction($it['ID'], $it['EDIT_LINK'], CIBlock::GetArrayByID($it['IBLOCK_ID'], 'ELEMENT_EDIT'));
	return $this->GetEditAreaId($it['ID']);
};
$img = function ($pic, $w, $h) {
	return is_array($pic) ? Pict::getResizeWebpSrc($pic, $w, $h) : '';
};

if ($view === 'stats'): ?>
<ul class="sk-stats">
	<?php foreach ($items as $it): ?>
	<li class="sk-stat" id="<?= $edit($it) ?>">
		<span class="sk-stat__num"><?= $it['NAME'] ?></span>
		<span class="sk-stat__bar" aria-hidden="true"></span>
		<span class="sk-stat__text"><?= strip_tags($it['PREVIEW_TEXT'], '<br><sup>') ?></span>
	</li>
	<?php endforeach; ?>
</ul>

<?php elseif ($view === 'stages'): ?>
<ol class="sk-steps__list">
	<?php foreach (array_values($items) as $i => $it): ?>
	<li class="sk-step" id="<?= $edit($it) ?>">
		<div class="sk-step__media"><img src="<?= SK_PATH ?>/img/step-<?= min($i + 1, 4) ?>.webp" alt="" width="375" height="375" loading="lazy"></div>
		<div class="sk-step__body">
			<span class="sk-step__num"><?= sprintf('%02d', $i + 1) ?></span>
			<h3 class="sk-step__title"><?= $it['NAME'] ?></h3>
			<span class="sk-step__bar" aria-hidden="true"></span>
			<p class="sk-step__text"><?= strip_tags($it['PREVIEW_TEXT'], '<br>') ?></p>
		</div>
	</li>
	<?php endforeach; ?>
</ol>

<?php elseif ($view === 'brands'): ?>
<ul class="sk-brands">
	<?php foreach ($items as $it): $logo = $img($it['PREVIEW_PICTURE'], 250, 176); ?>
	<li class="sk-brand" id="<?= $edit($it) ?>"><a class="sk-brand__link" href="<?= $it['DETAIL_PAGE_URL'] ?>">
		<span class="sk-brand__logo"><?php if ($logo): ?><img src="<?= $logo ?>" alt="<?= $it['NAME'] ?>" loading="lazy"><?php else: ?><?= $it['NAME'] ?><?php endif; ?></span>
		<span class="sk-brand__count"><?= $logo ? $it['NAME'] : '' ?></span>
	</a></li>
	<?php endforeach; ?>
	<li class="sk-brand sk-brand--all"><a class="sk-brand__link" href="/brends/"><span class="sk-brand__logo">Все бренды</span><span class="sk-brand__count">Полный список производителей</span></a></li>
</ul>

<?php elseif ($view === 'portfolio'): ?>
<ul class="sk-objects__grid">
	<?php foreach (array_values($items) as $i => $it): $src = $img($it['PREVIEW_PICTURE'], $i ? 600 : 1200, $i ? 380 : 760); ?>
	<li class="sk-object" id="<?= $edit($it) ?>">
		<a class="sk-object__link" href="<?= $it['DETAIL_PAGE_URL'] ?>">
			<?php if ($src): ?><img class="sk-object__img" src="<?= $src ?>" alt="<?= $it['NAME'] ?>" loading="lazy"><?php endif; ?>
			<span class="sk-object__body">
				<span class="sk-object__title"><?= $it['NAME'] ?></span>
				<span class="sk-object__meta">
					<?php if ($it['PROPERTIES']['ADRESS']['VALUE']): ?><span class="sk-object__tag sk-object__tag--city"><?= $it['PROPERTIES']['ADRESS']['VALUE'] ?></span><?php endif; ?>
					<?php if ($it['PROPERTIES']['OBJECT']['VALUE']): ?><span class="sk-object__tag sk-object__tag--type"><?= $it['PROPERTIES']['OBJECT']['VALUE'] ?></span><?php endif; ?>
				</span>
			</span>
		</a>
	</li>
	<?php endforeach; ?>
</ul>

<?php elseif ($view === 'promo'): $it = reset($items); $src = $img($it['PREVIEW_PICTURE'], 483, 362); ?>
<article class="sk-promo" id="<?= $edit($it) ?>">
	<a class="sk-promo__img" href="<?= $it['DETAIL_PAGE_URL'] ?>" tabindex="-1" aria-hidden="true"><img src="<?= $src ?: SK_PATH . '/img/promo.webp' ?>" alt="" width="483" height="362" loading="lazy"></a>
	<span class="sk-promo__label">Акция</span>
	<div class="sk-promo__body">
		<?php if ($it['ACTIVE_TO'] && MakeTimeStamp($it['ACTIVE_TO']) > time()): ?><span class="sk-promo__date">до <?= FormatDate('j F Y', MakeTimeStamp($it['ACTIVE_TO'])) ?></span><?php endif; ?>
		<h3 class="sk-promo__title"><a href="<?= $it['DETAIL_PAGE_URL'] ?>"><?= $it['NAME'] ?></a></h3>
		<?php if ($it['PREVIEW_TEXT']): ?><p class="sk-promo__text"><?= TruncateText(strip_tags($it['PREVIEW_TEXT']), 200) ?></p><?php endif; ?>
		<a class="sk-btn sk-btn--outline sk-promo__more" href="/aktsii/">Все акции</a>
	</div>
</article>
<?php endif;
