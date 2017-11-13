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

// 6 - hl блок Plots
$hlblock   = HL\HighloadBlockTable::getById(6)->fetch();
$Plots   = HL\HighloadBlockTable::compileEntity( $hlblock );
$PlotsDataClass = $Plots->getDataClass();

$rsData = $PlotsDataClass::getList(
  array(
    "select" => array('*'), //выбираем все поля
    "filter" => array(),
    "order" => array("ID"=>"ASC"), // сортировка по полю ID, будет работать только, если вы завели такое поле в hl'блоке
  )
);
//Формируем список участков
$plotsData = $rsData->FetchAll();
$data = array("{id : '', text: 'нет'}");
foreach($plotsData as $plot){
  if ($_POST['UF_B_PLOT_ID'] == $plot['ID']) $data[] ="{ id:".$plot['ID'].",text:'".trim ($plot['UF_PLOT_KAD_NUM']." ".$plot['UF_PLOT_ADDRESS'])."', selected: true}";
  else $data[] ="{ id:".$plot['ID'].",text:'".trim ($plot['UF_PLOT_KAD_NUM']." ".$plot['UF_PLOT_ADDRESS'])."'}";
}
$arResult['PLOTS'] = "[".implode(",",$data)."]";

// 23 - hl блок RealEstate - Жилые комплексы
$hlblock   = HL\HighloadBlockTable::getById(23)->fetch();
$ZHKs   = HL\HighloadBlockTable::compileEntity( $hlblock );
$ZHKDataClass = $ZHKs->getDataClass();

$rsData = $ZHKDataClass::getList(
  array(
    "select" => array('*'), //выбираем все поля
    "filter" => array(),
    "order" => array("ID"=>"ASC"), // сортировка по полю ID, будет работать только, если вы завели такое поле в hl'блоке
  )
);
//Формируем список ЖК
$ZHKData = $rsData->FetchAll();
$data = array("{id : '', text: 'нет'}");
foreach($ZHKData as $ZHK){
  if ($_POST['UF_RS'] == $ZHK['ID']) $data[] ="{ id:".$ZHK['ID'].",text:'".$ZHK['UF_NAMERS']."', selected: true}";
  else $data[] ="{ id:".$ZHK['ID'].",text:'".$ZHK['UF_NAMERS']."'}";
  $arResult['ZHKS'][$ZHK['ID']] = $ZHK['UF_NAMERS'];
}
$arResult['ZHKS'] = "[".implode(",",$data)."]";

$template = 'type';//По-умолчанию шаблон выбора типа здания
echo "<pre>";print_r($_POST);echo "</pre>";

$arResult['ID'] = $arParams['ELEMENT_ID'];
if ($_POST['AJAX_CALL'] == 'Y'){//вызов из формы
  foreach ($_POST as $key=>$value) $arResult[$key] = $value;
  if ($arResult['ACTION'] == 'save') {
    
  }
}else{//чистый вызов
  if ($arResult['ID']>0){
    $row = $BuildingDataClass::getById($arResult['ID'])->fetch();
    foreach ($row as $key=>$value) $arResult[$key] = $value;
  }else{
    echo "clean_new";
  }
}

switch ($arResult['UF_BUILDING_TYPE_ID']){//Выбор шаблона
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
/*
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
}
if ($_POST['ACTION'] == 'save') {
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
}
*/
$this->IncludeComponentTemplate($template);
?>