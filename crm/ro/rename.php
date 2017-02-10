<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Указание активности объектов недвижимости");
$arSelect = Array("ID", "IBLOCK_ID", "ACTIVE", "NAME","CREATED_BY","MODIFIED_BY","DATE_CREATE","TIMESTAMP_X", "DATE_ACTIVE_FROM","PROPERTY_*" );
$iblock_filter = array (
	"IBLOCK_ID" => 42,
);
$db_res = CIBlockElement::GetList(array(), $iblock_filter, false, false, $arSelect);
$num = 0;
$num_act = 0;
$num_dis = 0;
while($aRes = $db_res->GetNext()){
	if ($aRes['PROPERTY_266']=='Активный' || $aRes['PROPERTY_266']=='Свободный'){
		$active="Y";
		$num_act++;
	}else{
		$active="N";
		$num_dis++;
	}
  $el = new CIBlockElement;
  $arFields = array(
        "ACTIVE" => $active,
        "MODIFIED_BY" => $USER->GetID(),
      );
  $el->Update($aRes['ID'], $arFields);
  $num++;
}
echo "Всего ".$num.", из них активных - ".$num_act.", неактивных - ".$num_dis;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>