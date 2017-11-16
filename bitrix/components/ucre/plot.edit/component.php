<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
// подключаем модули
CModule::IncludeModule('iblock');
CModule::IncludeModule('highloadblock');

// необходимые классы
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

// $hlblock - это массив, 6 - hl блок Plot
$hlblock   = HL\HighloadBlockTable::getById(6)->fetch();
$Plot   = HL\HighloadBlockTable::compileEntity($hlblock);
$PlotDataClass = $Plot->getDataClass();


//echo "<pre>";print_r($_POST);echo "</pre>";

if ($_POST['AJAX_CALL'] == 'Y'){//вызов из формы
  foreach ($_POST as $key=>$value) $arResult[$key] = $value;
  if ($arResult['ACTION'] == 'save') {
    
  }
}else{//Чисты вызов
   if ($arParams['ELEMENT_ID']>0){
   }
}

$this->IncludeComponentTemplate();
?>