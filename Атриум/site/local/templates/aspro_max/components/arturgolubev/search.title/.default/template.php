<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
\Bitrix\Main\UI\Extension::load('ui.bootstrap4');
/* hints */
$arResult["HINTS"] = [];
if(is_array($arParams["ANIMATE_HINTS"])){
	foreach($arParams["ANIMATE_HINTS"] as $k=>$v){
		if(trim($v)){
			$arResult["HINTS"][] = trim($v);
		}
	}
}

if(count($arResult["HINTS"])){
	CJSCore::Init(["ag_smartsearch_type"]);
	$arParams["INPUT_PLACEHOLDER"] = '';
	$arParams["ANIMATE_HINTS_SPEED"] = (intval($arParams["ANIMATE_HINTS_SPEED"]) ? intval($arParams["ANIMATE_HINTS_SPEED"]) : 1);
}

/* quick variants */
$arResult["QUICK_VARIANTS"] = [];
if($arParams["QUICK_VARIANTS_SHOW"] == "Y" && is_array($arParams["QUICK_VARIANTS_VALUE"])){
	foreach($arParams["QUICK_VARIANTS_VALUE"] as $k=>$v){
		if(trim($v)){
			$arResult["QUICK_VARIANTS"][] = trim($v);
		}
	}
}

$INPUT_ID = ($arParams["~INPUT_ID"]) ? trim($arParams["~INPUT_ID"]) : "smart-title-search-input";
$INPUT_ID = CUtil::JSEscape($INPUT_ID);

$CONTAINER_ID = ($arParams["~CONTAINER_ID"]) ? trim($arParams["~CONTAINER_ID"]) : "smart-title-search";
$CONTAINER_ID = CUtil::JSEscape($CONTAINER_ID);

$PRELOADER_ID = $CONTAINER_ID."_preloader_item";
$CLEAR_ID = $CONTAINER_ID."_clear_item";
$VOICE_ID = $CONTAINER_ID."_voice_item";

if($arParams["SHOW_INPUT"] !== "N"):?>
	<div id="<?echo $CONTAINER_ID?>" class="bx-searchtitle <?=$arResult["VISUAL_PARAMS"]["THEME_CLASS"]?>">
		<form action="<?echo $arResult["FORM_ACTION"]?>">
			<div class="bx-input-group">
				<input id="<?echo $INPUT_ID?>" placeholder="Найти" type="text" name="q" value="<?=htmlspecialcharsbx($_REQUEST["q"])?>" autocomplete="off" class="bx-form-control"/>
				<span class="bx-input-group-btn">
					<span class="bx-searchtitle-preloader <?if($arParams["SHOW_LOADING_ANIMATE"] == 'Y') echo 'view';?>" id="<?echo $PRELOADER_ID?>"></span>
					<span class="bx-searchtitle-clear" id="<?echo $CLEAR_ID?>"></span>
					<?if($arParams['VOICE_INPUT'] == 'Y'):?>
						<span class="bx-searchtitle-voice" id="<?echo $VOICE_ID?>">
							<svg height="100px" width="100px" version="1.1" id="_x32_" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512"  xml:space="preserve">
								<path fill="#222" d="M383.788,206.98v51.113c-0.013,35.266-14.301,67.108-37.475,90.318
									c-23.212,23.176-55.042,37.464-90.307,37.464c-35.267,0-67.108-14.288-90.32-37.464c-23.174-23.211-37.462-55.052-37.474-90.318
									V206.98H90.503v51.113c0.036,84.93,64.21,154.935,146.649,164.337V512h37.709v-89.57c82.426-9.402,146.599-79.407,146.636-164.337
									V206.98H383.788z"/>
								<path fill="#222" d="M256.006,344.41c47.589,0,86.305-38.728,86.305-86.318V86.318C342.311,38.728,303.596,0,256.006,0
									c-47.59,0-86.318,38.728-86.318,86.318v171.775C169.688,305.682,208.416,344.41,256.006,344.41z"/>
							</svg>
						</span>
					<?endif;?>
					<button class="" type="submit" name="s"><i class="svg inline  svg-inline-search" aria-hidden="true"><svg class="" width="17" height="17" viewBox="0 0 17 17" aria-hidden="true"><path class="cls-1" d="M16.709,16.719a1,1,0,0,1-1.412,0l-3.256-3.287A7.475,7.475,0,1,1,15,7.5a7.433,7.433,0,0,1-1.549,4.518l3.258,3.289A1,1,0,0,1,16.709,16.719ZM7.5,2A5.5,5.5,0,1,0,13,7.5,5.5,5.5,0,0,0,7.5,2Z"></path></svg></i></button>
				</span>
			</div>
			<?/*?>
			<div id="typed-strings">									
				<?
				$arSelect = Array("ID", "NAME", "DATE_ACTIVE_FROM");
				$arFilter = Array("IBLOCK_ID"=>74, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
				$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
				while($ob = $res->GetNextElement())
				{
					$arFields = $ob->GetFields();
					// print_r($arFields);
					echo '<p>'.$arFields['NAME'].'</p>';
				}
				?> 			
				
			</div>
			<label id="typed" class="label-for-search" style="white-space:pre;" for="title-search-input"></label>
			<?*/?>
		</form>
	</div>
<?endif?>

<script>
	BX.ready(function(){
		new JCTitleSearchAG({
			// 'AJAX_PAGE' : '/your-path/fast_search.php',
			'AJAX_PAGE' : '<?echo CUtil::JSEscape(POST_FORM_ACTION_URI)?>',
			'CONTAINER_ID': '<?echo $CONTAINER_ID?>',
			'INPUT_ID': '<?echo $INPUT_ID?>',
			'PRELODER_ID': '<?echo $PRELOADER_ID?>',
			'CLEAR_ID': '<?echo $CLEAR_ID?>',
			'VOICE_ID': '<?=($arParams['VOICE_INPUT'] == 'Y') ? $VOICE_ID : ''?>',
			'POPUP_HISTORY': '<?=($arParams['SHOW_HISTORY'] == 'Y') ? 'Y' : 'N'?>',
			'POPUP_HISTORY_TITLE': '<?=GetMessage("CT_BST_SEARCH_HISTORY")?>',
			'POPUP_HISTORY_CLEAR': '<?=GetMessage("CT_BST_SEARCH_HISTORY_CLEAR")?>',
			'BX_COOKIE_PREFIX': '<?=\Bitrix\Main\Config\Option::get('main', 'cookie_name')?>',
			'PAGE': '<?=$arParams["PAGE"]?>',
			'QUICK_VARIANTS': <?=\Bitrix\Main\Web\Json::encode($arResult["QUICK_VARIANTS"])?>,
			'QUICK_VARIANTS_TITLE': '<?=GetMessage("CT_BST_QUICK_VARIANTS_TITLE")?>',
			'MIN_QUERY_LEN': 2,
		});
		
		<?if(count($arResult["HINTS"])):?>
			new Typed('#<?echo $INPUT_ID?>', {
				strings: <?=CUtil::PhpToJSObject($arResult["HINTS"]);?>,
				typeSpeed: <?=$arParams["ANIMATE_HINTS_SPEED"]*20?>,
				backSpeed: <?=$arParams["ANIMATE_HINTS_SPEED"]*10?>,
				backDelay: 500,
				startDelay: 1000,
				// smartBackspace: true,
				bindInputFocusEvents: true,
				attr: 'placeholder',
				loop: true
			});
		<?endif;?>
	});
</script>

<?if($arResult["VISUAL_PARAMS"]["THEME_COLOR"]):?>
	<style>
		.bx-searchtitle .bx-input-group .bx-form-control, .bx-searchtitle .bx-input-group-btn button {
			border-color: <?=$arResult["VISUAL_PARAMS"]["THEME_COLOR"]?> !important;
		}
		.bx-searchtitle .bx-input-group-btn button {
			background-color: <?=$arResult["VISUAL_PARAMS"]["THEME_COLOR"]?>  !important;
		}
		.bx-searchtitle .bx-searchtitle-clear, .bx_smart_searche .bx_item_block.all_result .all_result_button {
			color: <?=$arResult["VISUAL_PARAMS"]["THEME_COLOR"]?>  !important;
		}
		.bx-searchtitle-voice svg path {
			fill: <?=$arResult["VISUAL_PARAMS"]["THEME_COLOR"]?>  !important;
		}
	</style>
<?endif;?>