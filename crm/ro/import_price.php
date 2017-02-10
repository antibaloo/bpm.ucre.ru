<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Импорт объектов недвижимости");
$APPLICATION->IncludeComponent(
	"bitrix:main.interface.toolbar",
	"",
	array(
		"BUTTONS"=>array(
			array(
				"TEXT"=>"Добавить новый объект",
				"TITLE"=>"Добавить новый объект недвижимости",
				"LINK"=>"../ro/?edit&id=0",
				"ICON"=>"btn-new",
			),
			array(
				"TEXT"=>"Объекты недвижимости",
				"TITLE"=>"Список объектов недвижимости",
				"LINK"=>".",
				"ICON"=>"btn-list",
			),
			array("SEPARATOR"=>true), 
			array(
				"TEXT"=>"База города",
				"TITLE"=>"Список объектов базы города",
				"LINK"=>"../bg/",
				"ICON"=>"btn-list",
			),
      array(
				"TEXT"=>"Импорт объектов недвижимости",
				"TITLE"=>"Импорт объектов недвижимости",
				"LINK"=>"import.php",
				"ICON"=>"btn-list",
			),
		),
	),
	$component
);
?>
<?

if ($USER->GetID() == 24) {
}else{
	die("Вы не тот, кому это позволено!");
}
$ecrplus_ro = new DOMDocument();
$ecrplus_ro->load('https://bpm.ucre.ru/orenburg_bpm.xml');

$objects = $ecrplus_ro->getElementsByTagName('Object');
$num = 0;
$none = 0;
$est = 0;
foreach ($objects as $object) {
  $num++;
  $arFields = array();
  $PROP = array();
  foreach($object->childNodes as $nodename){
    switch ($nodename->nodeName){
      case "Id":
        $arSelect = Array("ID", "IBLOCK_ID", "CODE","ACTIVE", "NAME","CREATED_BY","MODIFIED_BY","DATE_CREATE","TIMESTAMP_X", "DATE_ACTIVE_FROM","PROPERTY_*");
        $db_res = CIBlockElement::GetList(Array(), array("IBLOCK_ID" => 42, "CODE" => $nodename->nodeValue ), false, Array(), $arSelect);
				$code = $nodename->nodeValue;
        if ($aRes = $db_res->GetNext()){
          $ID = $aRes['ID'];
          $est++;
        }else {
          $ID = 0;
          $none++;
        }
        break;
			case "Price":
				$price = $nodename->nodeValue;
				break;
    }
	}
  
	$ro =  new CIBlockElement;
	if ($ID) {
		echo "Обновляем цену в объекте ".$ID.", код в старой базе: ".$code."<br>";
		CIBlockElement::SetPropertyValuesEx($ID, 42, array('PRICE' => $price));
	}
	echo "<hr>";
}

echo "Всего объектов ".$num.", из них ".$none." нет в bpm.ucre.ru, а ".$est." есть.";
?>

<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>
