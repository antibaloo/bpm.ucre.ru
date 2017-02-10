<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Удаление картинок из объектов, таблиц и с диска");

if ($USER->GetID() == 24) {
	echo "Welcome, magister!<br>";
}else{
	die("Вы не тот, кому это позволено!");
}

$arSelect = Array("ID", "IBLOCK_ID", "CODE", "NAME","CREATED_BY","MODIFIED_BY","DATE_CREATE","TIMESTAMP_X", "DATE_ACTIVE_FROM","PROPERTY_*");
$db_res = CIBlockElement::GetList(Array(), array("IBLOCK_ID" => 42), false,false, $arSelect);
while($aRes = $db_res->GetNext())
{
  if (!empty($aRes['PROPERTY_237'])){
    echo "ID элемента - ".$aRes['ID']." image ids: ";
    foreach ($aRes['PROPERTY_237'] as $imageID){
      echo $imageID." ";
      CFile::Delete($imageID);
    }
    CIBlockElement::SetPropertyValuesEx($aRes['ID'], 42, array('PHOTOS' => Array ("VALUE" => array("del" => "Y"))));
		echo "<br>";
	}
	if (!empty($aRes['PROPERTY_293'])){
		echo "ID элемента - ".$aRes['ID']." image ids: ";
		foreach ($aRes['PROPERTY_293'] as $imageID){
      echo $imageID." ";
      CFile::Delete($imageID);
    }
    CIBlockElement::SetPropertyValuesEx($aRes['ID'], 42, array('RO_DOCS' => Array ("VALUE" => array("del" => "Y"))));
	}
	if (!empty($aRes['PROPERTY_294'])){
		echo "ID элемента - ".$aRes['ID']." image ids: ";
		foreach ($aRes['PROPERTY_294'] as $imageID){
      echo $imageID." ";
      CFile::Delete($imageID);
    }
    CIBlockElement::SetPropertyValuesEx($aRes['ID'], 42, array('CLI_DOCS' => Array ("VALUE" => array("del" => "Y"))));
  
    echo "<br>";
	}
}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>