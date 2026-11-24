<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();?>

<a href="/followers/" class="btn-fixed btn-fixed_favorites">
	<span class="btn-fixed__value"><?=get_followers()?></span>
	<img src="<?=SITE_TEMPLATE_PATH?>/img/favorites.svg" alt="Избранное">
</a>
<a href="/personal/cart/" class="btn-fixed btn-fixed_basket">
	<span class="btn-fixed__value"><?=$arResult['NUM_PRODUCTS']?></span>
	<img src="<?=SITE_TEMPLATE_PATH?>/img/basket.svg" alt="Корзина">
</a>
<a target="_blank" href="<?echo \COption::GetOptionString("askaron.settings", "UF_WHATSAPP");?>" class="btn-fixed btn-fixed_whatsapp">
	<img src="<?=SITE_TEMPLATE_PATH?>/img/whatsapp.svg" alt="WhatsApp">
</a>
<a target="_blank" href="<?echo \COption::GetOptionString("askaron.settings", "UF_MAX");?>" class="btn-fixed btn-fixed_max">
	<img src="<?=SITE_TEMPLATE_PATH?>/img/max.svg" alt="MAX">
</a>
<a href="#" class="btn-fixed btn-fixed_search">
	<img src="<?=SITE_TEMPLATE_PATH?>/img/search2.svg" alt="Поиск">
</a>