<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
// подключаем модули
CModule::IncludeModule('iblock');
CModule::IncludeModule('highloadblock');

// необходимые классы
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;
//Структура для проверки формы
$checkFields = array(
  "1" => array(             //Нежилое
  ),
  "2" => array(             //Жилое
    "1" => array(           //Комната
    ),
    "2" => array(           //Квартира
    )
  )
);
// $hlblock - это массив, 1 - hl блок Placement
$hlblock   = HL\HighloadBlockTable::getById(1)->fetch();
$Placement   = HL\HighloadBlockTable::compileEntity( $hlblock );
$PlacementDataClass = $Placement->getDataClass();

// $hlblock - это массив, 2 - hl блок Building
$hlblock   = HL\HighloadBlockTable::getById(2)->fetch();
$Building   = HL\HighloadBlockTable::compileEntity( $hlblock );
$BuildingDataClass = $Building->getDataClass();

$template = 'type';//По-умолчанию шаблон выбора типа здания
if ($_POST['AJAX_CALL'] == 'Y'){//вызов из формы
  foreach ($_POST as $key=>$value) $arResult[$key] = $value;
  
}else{//чистый вызов
  if ($arParams['ELEMENT_ID']>0){
    $row = $PlacementDataClass::getRowById($arParams['ELEMENT_ID']);
    foreach ($row as $key=>$value) $arResult[$key] = $value;
  }elseif (isset($_GET)){
    foreach ($_GET as $key=>$value) $arResult[$key] = $value;
  }  
}
if (!isset($arResult['UF_BUILDING_ID'])) die("Создание помещения без привязки к зданию запрещено");
//Получаем адрес здания
if($arResult['UF_BUILDING_ID']>0){
  $temp = $BuildingDataClass::getRowById($arResult['UF_BUILDING_ID']);
  $arResult['UF_BUILDING_ADDRESS'] = $temp['UF_BUILDING_ADDRESS'];
  $arResult['UF_LATITUDE'] = $temp['UF_LATITUDE'];
  $arResult['UF_LONGITUDE'] = $temp['UF_LONGITUDE'];
}


if ($arResult['UF_PLACEMENT_TYPE_ID'] == 1) {
  $template = 'nonresidental';
}elseif ($arResult['UF_PLACEMENT_TYPE_ID'] == 2){
  if ($arResult['UF_LIVING_TYPE_ID'] == 1) $template = 'room';
  if ($arResult['UF_LIVING_TYPE_ID'] == 2) $template = 'flat';
}
$this->IncludeComponentTemplate($template);
?>