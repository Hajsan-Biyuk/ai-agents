<?
	global $USER;
	$arParams['IBLOCK_ID'] = 68;
	$arParams['SHOW_ALL_LINK'] = 'Y';
	$arParams['ALL_LINK'] = '/info/brands/';
	$arParams['SHORT_RU'] = 'Y'; // выводить короткой записью Y (А-Я) | N (А Б В ... Я)
	$arParams['SHORT_EN'] = 'N'; // выводить короткой записью Y (A-Z) | N (A B C ... Z)
	
	$arNumbers = ['0-9'];
	$arLettersRu = ['А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Э', 'Ю', 'Я'];
	$arLettersEn = range('A', 'Z');
	
	$arResult['ALPHABET'] = array();
	
	if($USER->IsAdmin())
	{
		//echo "<pre style='color:#02f702; background:#414354;'>";print_r($arNumbers);echo "</pre>";
	}
	
	if(\Bitrix\Main\Loader::includeModule('iblock'))
	{
		$params = array(
			'filter' => ['=IBLOCK_ID'=>intval($arParams['IBLOCK_ID']), '=ACTIVE'=>'Y'],
			'select' => ['IBLOCK_ID', 'ID', 'NAME', 'CODE'],
			'group'  => ['NAME'],
		);
		$q = \Bitrix\Iblock\ElementTable::getList($params);
		
		$arTmp = array();
		while($arItem = $q->fetch())
		{
			$arItem['FIRST_LETTER'] = strtoupper(mb_substr($arItem['NAME'], 0, 1, 'UTF-8'));
			//$arItem['ACTIVE'] = (isset($_REQUEST['lid']) && intval($_REQUEST['lid']) == intval($arItem['ID']))?'Y':'';
			$arTmp[$arItem['FIRST_LETTER']][] = $arItem;
		}
		unset($params, $q, $arItem);
	}
	
	foreach($arLettersEn as $letter)
	{
		if($arParams['SHORT_EN'] === 'Y')
		{
			if(array_key_exists($letter, $arTmp))
			{
				$arResult['ALPHABET']['A-Z'][] = $arTmp[$letter][0];
			}
		} else {
			foreach($arLettersEn as $letter)
			{
				$arResult['ALPHABET'][$letter] = $arTmp[$letter];
			}
		}
	}
	unset($letter);
	
	foreach($arLettersRu as $letter)
	{
		if($arParams['SHORT_RU'] === 'Y')
		{
			if(array_key_exists($letter, $arTmp))
			{
				$arResult['ALPHABET']['А-Я'][] = $arTmp[$letter][0];
			}
		} else {
			foreach($arLettersRu as $letter)
			{
				$arResult['ALPHABET'][$letter] = $arTmp[$letter];
			}
		}
	}
	unset($letter);
?>
<div class="iq-container">
<? if($arResult['ALPHABET']):?>
	<div class="letter-dir">
	
		<? if($arParams['SHOW_ALL_LINK'] === 'Y'):?>
			<a class="letter-dir__btn" href="<?=($arParams['ALL_LINK'])?$arParams['ALL_LINK']:'javascript:void(0);';?>">Все бренды</a>
		<? endif;?>
		
		<div class="letter-dir__grid">
			<? foreach($arResult['ALPHABET'] as $key => $letter):?>
				<div class="letter-dir__grid__item">
					<a class="iq-letter<?=(empty($letter))?' disable':' active';?>" href="javascript:void(0);"><?=$key;?></a>
					
					<? if(!empty($letter) && is_array($letter)):?>
						<div class="iq-letter-menu">
							<? foreach($letter as $sub):?>
								<a class="iq-letter-menu__link" href="<?=$arParams['ALL_LINK'].$sub['CODE'].'/';?>"><?=$sub['NAME'];?></a>
							<? endforeach;?>
						</div>
					<? endif?>
					
				</div>
			<? endforeach;?>
		</div>
		
	</div>
<? endif;?>
</div>