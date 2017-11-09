<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
// подключаем модули
CModule::IncludeModule('iblock');
CModule::IncludeModule('highloadblock');

// необходимые классы
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

// $hlblock - это массив, 2 - hl блок Building
$hlblock   = HL\HighloadBlockTable::getById(2)->fetch();
$Building   = HL\HighloadBlockTable::compileEntity( $hlblock );
$BuildingDataClass = $Building->getDataClass();
$template = 'type';//По-умолчанию шаблон выбора типа здания
//echo "<pre>";print_r($_POST);echo "</pre>";
if ($_POST['ACTION'] == 'type') {
  switch ($_POST['UF_BUILDING_TYPE_ID']){
    case 1:
      $template = 'nonresidental';
      break;
    case 2:
      $template = 'private';
      break;
    case 3:
      $template = 'multiflat';
      break;
  }
  foreach ($_POST as $key=>$value) $arResult[$key] = $value;
  //$arResult['UF_BUILDING_TYPE_ID'] = $_POST['UF_BUILDING_TYPE_ID'];
}


$this->IncludeComponentTemplate($template);
?>