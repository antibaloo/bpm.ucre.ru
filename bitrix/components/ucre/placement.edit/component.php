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

if

$this->IncludeComponentTemplate($template);
?>