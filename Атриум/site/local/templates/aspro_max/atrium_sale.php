<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

/**
 * Скидка карточки товара — одна на плашку, зачёркнутую цену и «Экономию».
 * Зачёркнутая = большая из STARAYA_CENA и базовой цены (до скидок Битрикса).
 * $final — цена, которую платит покупатель. null — скидки нет.
 */
if (!function_exists('atriumSaleInfo')) {
	function atriumSaleInfo($oldProp, $final, $base = 0)
	{
		$final = (float)$final;
		$old = is_scalar($oldProp) ? (float)str_replace(array(' ', "\xC2\xA0", ','), array('', '', '.'), (string)$oldProp) : 0;
		$old = max($old, (float)$base);
		if ($final <= 0 || $old <= $final) {
			return null;
		}
		$percent = (int)round(($old - $final) / $old * 100);
		if ($percent < 1) {
			return null;
		}

		return array('OLD' => $old, 'FINAL' => $final, 'PERCENT' => $percent, 'ECONOMY' => round($old - $final));
	}
}

if (!function_exists('atriumSaleEconomyHtml')) {
	function atriumSaleEconomyHtml($sale)
	{
		if (!$sale) {
			return '';
		}

		return '<div class="sale_block atr-sale-block"><div class="sale_wrapper font_xxs"><div class="sale-number rounded2">'
			. '<div class="value">-<span>' . (int)$sale['PERCENT'] . '</span>%</div>'
			. '<div class="inner-sale rounded1"><div class="text">Экономия <span class="values_wrapper"><span class="price_value">'
			. CurrencyFormat($sale['ECONOMY'], 'RUB') . '</span></span></div></div></div></div></div>';
	}
}
