<?/*if($USER->IsAuthorized()):?>
 <?endif;*/?>

<?php
$currentDate = new DateTime();
$startDate = new DateTime('2024-12-01');// Начальная дата
$endDate = strtotime('2025-01-31');   // Конечная дата
$currentDate = time();
// баннер по умолчанию линкуем на /catalog/rasprodazha/

if ($currentDate >= $startDate && $currentDate <= $endDate && $APPLICATION->GetCurPage(false) !== '/'): ?>
<div class="maxwidth-theme">
	<a href="/catalog/rasprodazha/" title="Новогодние скидки -70%"><img src="/images/new-year.png" class="img-responsive" alt="Новогодние скидки -70%" /></a>
</div>
<?php endif; ?>


