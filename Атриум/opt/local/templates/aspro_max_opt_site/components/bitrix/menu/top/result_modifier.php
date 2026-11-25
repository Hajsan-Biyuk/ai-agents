<?
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

\Bitrix\Main\Loader::includeModule('highloadblock');

$hlblockId = 9; // замените на актуальный ID

if (!function_exists('atriumOptCatalogMenu')) {
	/**
	 * Дерево разделов каталога опта (инфоблок 80) в формате пунктов меню: уровни 1–3 каталога → DEPTH_LEVEL 2–4 меню.
	 * Иконки верхних разделов — из HL «Menu» розницы (по названию), картинки подразделов — PICTURE раздела.
	 */
	function atriumOptCatalogMenu($rootLink)
	{
		$cache = \Bitrix\Main\Data\Cache::createInstance();
		// на опте управляемый кеш выключен — тег iblock_id_80 не сбрасывается; ключ меняется при любом сохранении раздела
		$changed = (string)\Bitrix\Main\Application::getConnection()->queryScalar('SELECT MAX(TIMESTAMP_X) FROM b_iblock_section WHERE IBLOCK_ID = 80');
		if ($cache->initCache(3600, 'atrium_opt_catalog_menu_v4_' . md5($changed), '/atrium_opt_menu')) {
			return $cache->getVars();
		}
		$cache->startDataCache();
		\Bitrix\Main\Loader::includeModule('iblock');
		$taggedCache = \Bitrix\Main\Application::getInstance()->getTaggedCache();
		$taggedCache->startTagCache('/atrium_opt_menu');
		$taggedCache->registerTag('iblock_id_80');

		$icons = [];
		$hl = HL\HighloadBlockTable::getList(['filter' => ['=NAME' => 'Menu']])->fetch();
		if ($hl) {
			$cls = HL\HighloadBlockTable::compileEntity($hl)->getDataClass();
			$rs = $cls::getList(['select' => ['UF_NAME', 'UF_IMG'], 'filter' => ['>UF_IMG' => 0]]);
			while ($r = $rs->fetch()) {
				$icons[mb_strtolower(trim($r['UF_NAME']))] = (int)$r['UF_IMG'];
			}
		}
		$iconAlias = ['плитка и керамогранит' => 'плитка', 'мебель для ванных комнат' => 'мебель для ванной'];

		$byId = [];
		$tree = [];
		$rs = CIBlockSection::GetList(
			['LEFT_MARGIN' => 'ASC'],
			['IBLOCK_ID' => 80, 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y', '<=DEPTH_LEVEL' => 3],
			false,
			['ID', 'NAME', 'PICTURE', 'UF_MENU_PICTURE', 'DEPTH_LEVEL', 'IBLOCK_SECTION_ID', 'SECTION_PAGE_URL']
		);
		while ($sec = $rs->GetNext()) {
			// «Изображение в правом меню» важнее «Изображения» (оно же плитка на главной)
			$picture = (int)$sec['UF_MENU_PICTURE'] ?: (int)$sec['PICTURE'];
			if ($sec['DEPTH_LEVEL'] == 1) { // слева — иконка из HL «Menu»: PICTURE раздела занят плиткой на главной
				$key = mb_strtolower(trim($sec['~NAME']));
				$picture = $icons[$key] ?? ($icons[$iconAlias[$key] ?? ''] ?? $picture);
			}
			$depth = (int)$sec['DEPTH_LEVEL'] + 1;
			$byId[$sec['ID']] = [
				'TEXT' => $sec['~NAME'],
				'LINK' => $sec['SECTION_PAGE_URL'],
				'SELECTED' => false,
				'PERMISSION' => 'R',
				'ADDITIONAL_LINKS' => [],
				'ITEM_TYPE' => 'D',
				'PARAMS' => ['FROM_IBLOCK' => 1, 'DEPTH_LEVEL' => $depth, 'IBLOCK_ID' => 80, 'SECTION_ID' => (int)$sec['ID'], 'PICTURE' => $picture ?: false],
				'DEPTH_LEVEL' => $depth,
				'IS_PARENT' => 0,
				'CHILD' => [],
			];
			$parent = (int)$sec['IBLOCK_SECTION_ID'];
			if ($parent && isset($byId[$parent])) {
				$byId[$parent]['CHILD'][] = &$byId[$sec['ID']];
				$byId[$parent]['IS_PARENT'] = 1;
				$byId[$parent]['PARAMS']['IS_PARENT'] = 1;
			} elseif ($sec['DEPTH_LEVEL'] == 1) {
				$tree[] = &$byId[$sec['ID']];
			}
		}
		// раздел без подразделов («Акции»): справа вместо пустоты — его товары плитками
		foreach ($tree as &$top) {
			if ($top['CHILD']) {
				continue;
			}
			$top['PARAMS']['ITEMS'] = [];
			$rsEl = CIBlockElement::GetList(['SORT' => 'ASC', 'ID' => 'DESC'],
				['IBLOCK_ID' => 80, 'ACTIVE' => 'Y', 'SECTION_ID' => $top['PARAMS']['SECTION_ID'], 'INCLUDE_SUBSECTIONS' => 'Y'],
				false, ['nTopCount' => 12], ['ID', 'NAME', 'DETAIL_PAGE_URL', 'PREVIEW_PICTURE', 'DETAIL_PICTURE']);
			while ($el = $rsEl->GetNext()) {
				$top['PARAMS']['ITEMS'][] = ['NAME' => $el['~NAME'], 'LINK' => $el['DETAIL_PAGE_URL'], 'PICTURE' => (int)$el['PREVIEW_PICTURE'] ?: (int)$el['DETAIL_PICTURE']];
			}
		}
		unset($top);
		$result = json_decode(json_encode($tree), true); // снять ссылки PHP
		$taggedCache->endTagCache();
		$cache->endDataCache($result);
		return $result;
	}
}

$arResult = CMax::getChilds($arResult);
global $arRegion, $arTheme;

// Функция генерации элемента меню в нужном формате
function formatMenuItem($element, $depthLevel, $chain) {
    $slug = strtolower(str_replace(' ', '-', $element['UF_NAME']));
    $link = $element['UF_LINK'];/*!empty($element['UF_LINK']) ? $element['UF_LINK'] : '/catalog/' . implode('/', array_map(function($item) {
        return strtolower(str_replace(' ', '-', $item));
    }, $chain)) . '/';*/

    return [
        'TEXT' => $element['UF_NAME'],
        'LINK' => $link,
        'SELECTED' => '',
        'PERMISSION' => 'X',
        'ADDITIONAL_LINKS' => [],
        'ITEM_TYPE' => 'D',
        'ITEM_INDEX' => 0, // можно добавить счётчик
        'PARAMS' => [
			'UFBUTTON' => $element['UF_DPBUTTON'],
            'FROM_IBLOCK' => 1,
            'DEPTH_LEVEL' => $depthLevel,
            'IBLOCK_ID' => 61,
            'IS_PARENT' => 0, // обновится, если будут дети
            'PICTURE' => $element['UF_IMG'], // фиктивный ID картинки
        ],
        'CHAIN' => $chain,
        'DEPTH_LEVEL' => $depthLevel,
        'IS_PARENT' => 0, // обновится
        'CHILD' => [],
    ];
}

// Рекурсивная функция построения дерева
function buildMenuTree($elements, $depth = 2, $chain = ['Каталог']) {
    $result = [];

    foreach ($elements as $element) {
        $currentChain = array_merge($chain, [$element['UF_NAME']]);
        $menuItem = formatMenuItem($element, $depth, $currentChain);

        if (!empty($element['CHILD'])) {
            $menuItem['CHILD'] = buildMenuTree($element['CHILD'], $depth + 1, $currentChain);
            $menuItem['IS_PARENT'] = 1;
            $menuItem['PARAMS']['IS_PARENT'] = 1;
        }

        $result[] = $menuItem;
    }

    return $result;
}

if($arResult){
	$MENU_TYPE = $arTheme['MEGA_MENU_TYPE']['VALUE'];
	$bRightSide = $arTheme['SHOW_RIGHT_SIDE']['VALUE'] == 'Y';
	$bManyItemsMenu = ($MENU_TYPE == '4');

	$bRightBanner = $bRightSide && $arTheme['SHOW_RIGHT_SIDE']['DEPENDENT_PARAMS']['RIGHT_CONTENT']['VALUE'] == 'BANNER';
	$bRightBrand = $bRightSide && $arTheme['SHOW_RIGHT_SIDE']['DEPENDENT_PARAMS']['RIGHT_CONTENT']['VALUE'] == 'BRANDS';

	if($bRightBanner) {
		$bannerIblockId = CMaxCache::$arIBlocks[SITE_ID]["aspro_max_adv"]["aspro_max_banners_inner"][0];
		$arBannerFilter = array('!PROPERTY_SHOW_MENU' => false, 'ACTIVE' => 'Y', 'IBLOCK_ID' => $bannerIblockId);
		$arBannerSelect = array('ID', 'PROPERTY_SHOW_MENU');
		$arBanners = CMaxCache::CIblockElement_GetList(array("NAME" => "ASC", "CACHE" => array("TAG" => CMaxCache::GetIBlockCacheTag($bannerIblockId))), $arBannerFilter, $arBannerSelect);
	}

	if($bRightBrand) {
		$brandIblockId = CMaxCache::$arIBlocks[SITE_ID]["aspro_max_content"]["aspro_max_brands"][0];
		$arBrandFilter = array('PROPERTY_SHOW_TOP_MENU_VALUE' => 'Y', 'ACTIVE' => 'Y', 'IBLOCK_ID' => $brandIblockId);
		$arBrandSelect = array('ID', 'PROPERTY_SHOW_TOP_MENU', 'PREVIEW_PICTURE', 'NAME', 'DETAIL_PAGE_URL', 'IBLOCK_ID');
		$arResult['BRANDS'] = CMaxCache::CIblockElement_GetList(array("NAME" => "ASC", "CACHE" => array("TAG" => CMaxCache::GetIBlockCacheTag($brandIblockId))), $arBrandFilter, false, false, $arBrandSelect);
	}

	if($MENU_TYPE == 3) {
		CMax::replaceMenuChilds($arResult, $arParams);
	}

	foreach($arResult as $key=>&$arItem)
	{

		// echo '<pre>';
		// print_r($arItem['PARAMS']);
		// echo '</pre>';			
		
		$bWideMenu = (isset($arItem['PARAMS']['CLASS']) && strpos($arItem['PARAMS']['CLASS'], 'wide_menu') !== false);

		if($arBanners && $bWideMenu) {
			foreach ($arBanners as $banner) {
				if(is_array($banner['PROPERTY_SHOW_MENU_VALUE'])) {
					foreach ($banner['PROPERTY_SHOW_MENU_VALUE'] as $link) {
						if($link == $arItem['LINK']) {
							$arResult[$key]["BANNERS"][] = $banner['ID'];
						}
					}
				} else {
					if($banner['PROPERTY_SHOW_MENU_VALUE'] == $arItem['LINK']) {
						$arResult[$key]["BANNERS"][] = $banner['ID'];
					}
				}
			}
		}

		if(isset($arItem['CHILD']))
		{
			foreach($arItem['CHILD'] as $key2=>$arItemChild)
			{
				if($bManyItemsMenu) {
					if($bRightBrand && $arItemChild['PARAMS']['BRANDS']) {
						$brandIblockId = CMaxCache::$arIBlocks[SITE_ID]["aspro_max_content"]["aspro_max_brands"][0];
						$arBrandFilter = array('ID' => $arItemChild['PARAMS']['BRANDS'], 'ACTIVE' => 'Y', 'IBLOCK_ID' => $brandIblockId);
						$arBrandSelect = array('ID', 'PREVIEW_PICTURE', 'NAME', 'DETAIL_PAGE_URL', 'IBLOCK_ID');
						$arResult[$key]['CHILD'][$key2]['BRANDS'] = CMaxCache::CIblockElement_GetList(array("SORT" => "ASC", "NAME" => "ASC", "CACHE" => array("TAG" => CMaxCache::GetIBlockCacheTag($brandIblockId))), $arBrandFilter, false, false, $arBrandSelect);
					}
				}
				if(isset($arItemChild['PARAMS']) && $arRegion && $arTheme['USE_REGIONALITY']['VALUE'] === 'Y' && $arTheme['USE_REGIONALITY']['DEPENDENT_PARAMS']['REGIONALITY_FILTER_ITEM']['VALUE'] === 'Y')
				{
					// filter items by region
					if(isset($arItemChild['PARAMS']['LINK_REGION']))
					{
						if($arItemChild['PARAMS']['LINK_REGION'])
						{
							if(!in_array($arRegion['ID'], $arItemChild['PARAMS']['LINK_REGION']))
								unset($arResult[$key]['CHILD'][$key2]);
						}
						else
							unset($arResult[$key]['CHILD'][$key2]);
					}
				}
			}
		}


		if ($arItem['PARAMS']['USE_CUSTOM'] == "Y") {
			// Опт: меню каталога строится из разделов инфоблока каталога (все существующие активные разделы),
			// а не из HL-блока MenuOpt — новые разделы попадают в меню сами.
			$arItem['CHILD'] = atriumOptCatalogMenu($arItem['LINK']);
		}		
	}
}
// echo '<pre>'; print_r($arResult);echo '</pre>'; 
			// echo '<pre>';
			// print_r($finalMenu);
			// echo '</pre>';	
// (старая пересортировка разделов по жёстким индексам удалена — порядок как в инфоблоке)
foreach ($arResult as $key => $menu) {
	// if($menu['LINK'] == '/catalog/plitka/') {
	// 	$arResult[$key]['LINK'] = '/collections/plitka/';
	// }
	if(!empty($menu['CHILD'])) {
		foreach($menu['CHILD'] as $c => $child) {
			// if($child['LINK'] == '/catalog/plitka/') {
			// 	$arResult[$key]['CHILD'][$c]['LINK'] = '/collections/plitka/';
			// }
		}
	}
}

	
 	foreach ($arResult[0]['CHILD'] as $key => $value) {
		if($key == 1){
 		foreach ($value['CHILD'] as $k => $val) {
			$arR[] = $val;
			
		} 
		
	}
}

//$res = insertValueAtPosition($arResult[0]['CHILD'], $arR, $position=2);
//$arResult[0]['CHILD'] = array_merge($arResult[0]['CHILD'], $res);
//$arResult[0]['CHILD'] = array_slice($arResult[0]['CHILD'], 0, 1, true) + $arR + array_slice($arResult[0]['CHILD'], 1, count($arResult[0]['CHILD']) - 1,true);

// $arResult[0]['CHILD'] = array_slice($arResult[0]['CHILD'], 0, 1) + array_slice($arResult[0]['CHILD'], 1, count($arResult[0]['CHILD']) - 1);

// $arResult[0]['CHILD'] = array_merge($arResult[0]['CHILD'],$arR);
//pre($res); 

// $cc = $arResult[0]['CHILD'];
// //pre($cc);
// $save_cat_childs_0 = $cc[0];
// $save_cat_childs_1 = $cc[1];
// $save_cat_childs_2 = $cc[2];
// $save_cat_childs_3 = $cc[3]; 
// $save_cat_childs_4 = $cc[4];
// $save_cat_childs_5 = $cc[5];
// $save_cat_childs_6 = $cc[6];
// $save_cat_childs_7 = $cc[7]; 
// $save_cat_childs_8 = $cc[8];
// $save_cat_childs_9 = $cc[9];
// $save_cat_childs_10 = $cc[10];
// $save_cat_childs_11 = $cc[11]; 
// $save_cat_childs_12 = $cc[12];
// $save_cat_childs_13 = $cc[13];
// $save_cat_childs_14 = $cc[14];
// $save_cat_childs_15 = $cc[15];
// $save_cat_childs_16 = $cc[16];
// $save_cat_childs_17 = $cc[17];
// $save_cat_childs_18 = $cc[18];
 

// $sortt = [
// 	$save_cat_childs_0,
// 	$save_cat_childs_3, 
// 	$save_cat_childs_4,
// 	$save_cat_childs_5,
// 	$save_cat_childs_6,
// 	$save_cat_childs_7, 
// 	$save_cat_childs_8,
// 	$save_cat_childs_9,
// 	$save_cat_childs_10,
// 	$save_cat_childs_11, 
// 	$save_cat_childs_12,
// 	$save_cat_childs_13,
// 	$save_cat_childs_14,
// 	$save_cat_childs_15,
// 	$save_cat_childs_16,
// 	$save_cat_childs_17,
// 	$save_cat_childs_18,
// 	$save_cat_childs_1,
// 	$save_cat_childs_2,
// ];
// $arResult[0]['CHILD'] = $sortt;
/* foreach ($sortt as $key => $value) {
	pre($key." - ".$value['TEXT']);
}  */
//pre($cc);
?>