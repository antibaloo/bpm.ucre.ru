<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Тест нового грида");
$gridID = "newGridTest";
$headers = array(
    array("id"=>"ID", "name"=>"ID", "default"=>true, "width"=>60, "editable"=>false),
    array("id"=>"TITLE", "name"=>"Название заявки", "default"=>true, "editable"=>false),
    array("id"=>"UF_CRM_58958B5734602", "name"=>"Цена", "default"=>true, "editable"=>false),
    array("id"=>"NAME", "name"=>"Объект", "default"=>true, "editable"=>false),
    array("id"=>"PROPERTY_229", "name"=>"Комнат", "default"=>true, "editable"=>false),
    array("id"=>"PROPERTY_224", "name"=>"Общ. пл.", "default"=>true, "editable"=>false),
    array("id"=>"PROPERTY_226", "name"=>"Пл. кухни", "default"=>true, "editable"=>false),
    array("id"=>"PROPERTY_222", "name"=>"Этажность", "default"=>true, "editable"=>false),
    array("id"=>"ASSIGNED_BY_ID", "name"=>"Ответственный", "default"=>true, "editable"=>false),
  );

$rsQuery = "SELECT b_crm_deal.ID,b_crm_deal.CATEGORY_ID, b_crm_deal.TITLE, b_crm_deal.ASSIGNED_BY_ID, b_uts_crm_deal.UF_CRM_1469534140,b_uts_crm_deal.UF_CRM_58958B5734602, b_iblock_element.NAME, b_iblock_element_prop_s42.PROPERTY_210, b_iblock_element_prop_s42.PROPERTY_229, b_iblock_element_prop_s42.PROPERTY_224, b_iblock_element_prop_s42.PROPERTY_226, b_iblock_element_prop_s42.PROPERTY_221, b_iblock_element_prop_s42.PROPERTY_222 FROM b_crm_deal INNER JOIN b_uts_crm_deal ON b_crm_deal.ID=b_uts_crm_deal.VALUE_ID INNER JOIN b_iblock_element ON b_uts_crm_deal.UF_CRM_1469534140 = b_iblock_element.ID INNER JOIN b_iblock_element_prop_s42 ON b_iblock_element.ID = b_iblock_element_prop_s42.IBLOCK_ELEMENT_ID WHERE (b_crm_deal.STAGE_ID = 'PROPOSAL' or b_crm_deal.STAGE_ID = '8' or b_crm_deal.STAGE_ID = 'C4:1' or b_crm_deal.STAGE_ID = 'C4:PROPOSAL') ORDER BY ID desc";
$rsData = $DB->Query($rsQuery);
while($aRes = $rsData->Fetch()){
  $assigned_user = CUser::GetByID($aRes['ASSIGNED_BY_ID'])->Fetch();
  $aCols = array(
    "TITLE" => "<a href='/crm/deal/show/".$aRes['ID']."/' target='_blank'>".$aRes['TITLE']."</a>",
    "NAME" => "<a href='/crm/ro/?show&id=".$aRes['UF_CRM_1469534140']."' target='_blank'>".$aRes['NAME']."</a>",
    "PROPERTY_222" => $aRes['PROPERTY_221']."/".$aRes['PROPERTY_222'],
    "PROPERTY_229" => number_format($aRes['PROPERTY_229'],0),
    "PROPERTY_224" => number_format($aRes['PROPERTY_224'],2),
    "PROPERTY_226" => number_format($aRes['PROPERTY_226'],2),
    "ASSIGNED_BY_ID" => $assigned_user['LAST_NAME']." ".$assigned_user['NAME'],
  );
  $aActions = array();
  $aRows[] = array("data"=>$aRes, "actions"=>$aActions, "columns"=>$aCols, "editable"=>false);
}
$APPLICATION->IncludeComponent(
  'bitrix:main.ui.grid',
  '',
  array(
    'GRID_ID' => $gridID,
    'HEADERS' => $headers,
    'SORT' => array(),
		'SORT_VARS' => array(),
		'ROWS' => isset($aRows) ? $aRows : array(),
		'AJAX_MODE' => 'Y', //Strongly required
		"PAGE_SIZES" => array(
			array("NAME" => "5", "VALUE" => "5"),
			array("NAME" => "10", "VALUE" => "10"),
			array("NAME" => "20", "VALUE" => "20"),
			array("NAME" => "50", "VALUE" => "50"),
			array("NAME" => "100", "VALUE" => "100"),
			//Temporary limited by 100
			//array("NAME" => "200", "VALUE" => "200"),
		),
  ),
  false
);
?>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>