<ul class="menu-catalog">
<?
CModule::IncludeModule("iblock");
$UF_COL_LG = getUserFieldEnum(42);//UF_COL_LG
$UF_MENU_TYPE = getUserFieldEnum(43);//UF_MENU_TYPE

$section_list = CIBlockSection::GetList(array("sort" => "ASC"), array('IBLOCK_ID' => CATALOG, 'ACTIVE' => 'Y', 'SECTION_ID' => 0), false, array("ID", "NAME", "CODE", "SECTION_PAGE_URL", "PICTURE", "UF_*"));
$i = 0;
while($section = $section_list->GetNext()){ ?>
	<li <?=($i == 0 ? 'class="active"' : '')?>>
		<a href="<?=$section['SECTION_PAGE_URL']?>"><?=$section['NAME']?></a>
		<? if(CIBlockSection::GetCount(['IBLOCK_ID' => CATALOG, 'SECTION_ID' => $section['ID']])){ ?>
		<div class="content-dropdown" <?=($i == 0 ? 'style="display: block;"' : '')?>>
			<? if(!empty($section['UF_MENU_IMG'])){ ?>
			<div class="billbord-dropdown">
				<img src="<?=CFile::GetPath($section['UF_MENU_IMG'])?>" alt="alt">
			</div>
			<? } ?>
			<div class="row">
			<?
			if($UF_MENU_TYPE[$section['UF_MENU_TYPE']] == 1){
				$section_child_list = CIBlockSection::GetList(array("sort" => "ASC"), array('IBLOCK_ID' => CATALOG, 'ACTIVE' => 'Y', 'SECTION_ID' => $section['ID']), false, array("ID", "NAME", "CODE", "SECTION_PAGE_URL", "PICTURE", "UF_*"));
				while($section_child = $section_child_list->GetNext()){
			?>
				<div class="col-lg-6">
					<div class="title-dropdown"><a href="<?=$section_child['SECTION_PAGE_URL']?>"><?=$section_child['NAME']?></a></div>
					<ul>
			<?
					$section_child_child_list = CIBlockSection::GetList(array("sort" => "ASC"), array('IBLOCK_ID' => CATALOG, 'ACTIVE' => 'Y', 'SECTION_ID' => $section_child['ID']), false, array("ID", "NAME", "CODE", "SECTION_PAGE_URL", "PICTURE", "UF_*"));
					while($section_child_child = $section_child_child_list->GetNext()){
			?>
						<li><a href="<?=$section_child_child['SECTION_PAGE_URL']?>"><?=$section_child_child['NAME']?></a></li>
			<?
					}
			?>
					</ul>
				</div>
			<?
				}
			}
			else{
			?>
				<div class="col-lg-12">
					<ul>
			<?
					$section_child_list = CIBlockSection::GetList(array("sort" => "ASC"), array('IBLOCK_ID' => CATALOG, 'ACTIVE' => 'Y', 'SECTION_ID' => $section['ID']), false, array("ID", "NAME", "CODE", "SECTION_PAGE_URL", "PICTURE", "UF_*"));
					while($section_child = $section_child_list->GetNext()){
						$section_child_child_list = CIBlockSection::GetList(array("sort" => "ASC"), array('IBLOCK_ID' => CATALOG, 'ACTIVE' => 'Y', 'SECTION_ID' => $section_child['ID']), false, array("ID", "NAME", "CODE", "SECTION_PAGE_URL", "PICTURE", "UF_*"));
						$section_child_child_html = '';
						while($section_child_child = $section_child_child_list->GetNext()){
							$section_child_child_html .= '<li><a href="'. $section_child_child['SECTION_PAGE_URL'] .'">'. $section_child_child['NAME'] .'</a></li>';
						}
			?>
						<li>
						<!--	<a href="<?=$section_child['SECTION_PAGE_URL']?>"><?=$section_child['NAME']?></a> -->
							
						<?           
							if(!empty($section_child_child_html)){
								echo '<a href="'.$section_child['SECTION_PAGE_URL'].'" class="title-dropdown">' . $section_child['NAME'] . '</a>';
							} else {
							    echo '<a href="'.$section_child['SECTION_PAGE_URL'].'">' . $section_child['NAME'] . '</a>';
							}
			?>		
						
			<?           
							if(!empty($section_child_child_html)){
								echo '<ul>' . $section_child_child_html . '</ul>';
							}
			?>
						</li>
			<?
					}
			?>
					</ul>
				</div>
			<?
			}
			?>
			</div>
		</div>
		<? } ?>
	</li>
<? $i++; } ?>
</ul>