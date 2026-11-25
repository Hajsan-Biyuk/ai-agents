<?
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

\Bitrix\Main\Loader::includeModule('highloadblock');

$hlblockId = 9; // замените на актуальный ID

$arResult = CMax::getChilds($arResult);
global $arRegion, $arTheme;

// // Функция генерации элемента меню в нужном формате
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

// // Рекурсивная функция построения дерева
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
			$hlblock = HL\HighloadBlockTable::getById($hlblockId)->fetch();

			$entity = HL\HighloadBlockTable::compileEntity($hlblock);
			$entityClass = $entity->getDataClass();
			
			// Получаем записи
			$result = $entityClass::getList([
				'select' => ['*'], // какие поля выбирать
				'order' => ['ID' => 'ASC'], // сортировка
				'filter' => [] // фильтр, если нужен
			]);
			
			while ($row = $result->fetch()) {
				$elements[$row['ID']] = $row;
				$elements[$row['ID']]['CHILDREN'] = []; // заготовка под детей
			}		

			// Сначала собираем связи
			$tree = [];
			foreach ($elements as $id => &$element) {
				if (!empty($element['UF_CONNECT']) && isset($elements[$element['UF_CONNECT']])) {
					$elements[$element['UF_CONNECT']]['CHILD'][] = &$element;
				} else {
					$tree[] = &$element;
				}
			}
			unset($element);			

			$finalMenu = buildMenuTree($tree);

			// Функция для красивого вывода дерева
			// function printTree($items, $level = 0) {
			// 	foreach ($items as $item) {
			// 		echo str_repeat('--', $level) . ' ' . $item['UF_NAME'] . ' (ID: ' . $item['ID'] . ')' . PHP_EOL;
			// 		if (!empty($item['CHILDREN'])) {
			// 			printTree($item['CHILDREN'], $level + 1);
			// 		}
			// 	}
			// }

			// Пример вывода

			
			$arItem['CHILD'] = $finalMenu;

		}		
	}
}
// echo '<pre>'; print_r($arResult);echo '</pre>'; 
			// echo '<pre>';
			// print_r($finalMenu);
			// echo '</pre>';	
$save_cat = $arResult[0]['CHILD'];
$save_cat_childs_1 = $save_cat[0];
$save_cat_childs_2 = $save_cat[1];
$save_cat_childs_5 = $save_cat[2];
$save_cat_childs_6 = $save_cat[3]; 
$save_cat_childs_4 = $save_cat[4];
$save_cat_childs_7 = $save_cat[5];
$save_cat_childs_8 = $save_cat[6];
$save_cat_childs_9 = $save_cat[7];
$save_cat_childs_10 = $save_cat[8];
$save_cat_childs_11 = $save_cat[9];
$save_cat_childs_12 = $save_cat[10];
//pre($arResult[0]['CHILD']);
$build_new_sorts = [
	$save_cat_childs_1,
	$save_cat_childs_10,
	$save_cat_childs_12,
	$save_cat_childs_5,
	$save_cat_childs_6,
	$save_cat_childs_11,
	$save_cat_childs_2, 
	$save_cat_childs_7,
	$save_cat_childs_8,
	$save_cat_childs_4, 
	$save_cat_childs_9
];

$arResult[0]['CHILD'] = $build_new_sorts;
foreach ($arResult as $key => $menu) {
	if($menu['LINK'] == '/catalog/plitka/') {
		$arResult[$key]['LINK'] = '/collections/plitka/';
	}
	if(!empty($menu['CHILD'])) {
		foreach($menu['CHILD'] as $c => $child) {
			if($child['LINK'] == '/catalog/plitka/') {
				$arResult[$key]['CHILD'][$c]['LINK'] = '/collections/plitka/';
			}
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