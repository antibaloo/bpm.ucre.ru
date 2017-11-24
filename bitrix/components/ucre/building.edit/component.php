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
  "1" => array(
    "UF_BUILDING_TYPE_ID" => array('required' => true, 'regexp' => ''),
    "UF_BUILDING_ADDRESS" => array('required' => true, 'regexp' => '/^[А-Я][а-яА-Я\s0-9.,][^a-zA-Z]+/'),
    "UF_COUNTRY" => array('required' => true, 'regexp' => '/^[А-Я][а-яА-Я\s][^a-zA-Z]+/'),
    "UF_FED_DISTRICT" => array('required' => true, 'regexp' => '/^[А-Я][а-яА-Я\s][^a-zA-Z]+/'),
    "UF_PROVINCE" => array('required' => true, 'regexp' => '/^[А-Я][а-яА-Я\s][^a-zA-Z]+/'),
    "UF_AREA" => array('required' => true, 'regexp' => '/^[А-Я][а-яА-Я\s][^a-zA-Z]+/'),
    "UF_LOCALITY" => array('required' => true, 'regexp' => '/^[А-Я][а-яА-Я\s][^a-zA-Z]+/'),
    "UF_DISTRICT" => array('required' => false, 'regexp' => '/^[а-яА-Я\s][^a-zA-Z]+/'),
    "UF_STREET" => array('required' => true, 'regexp' => '/^[а-яА-Я0-9\s-][^a-zA-Z]+/'),
    "UF_HOUSE" => array('required' => true, 'regexp' => ''),
    "UF_POSTAL" => array('required' => true, 'regexp' => '/[0-9]{6}/'),
    "UF_LATITUDE" => array('required' => true, 'regexp' => '/^[1-9][0-9]+.[0-9]+/'),
    "UF_LONGITUDE" => array('required' => true, 'regexp' => '/^[1-9][0-9]+.[0-9]+/'),
    "UF_BUILDING_KAD_NUM" => array('required' => true, 'regexp' => '/^[0-9]{2}:[0-9]{2}:[0-9]+:[0-9]+/'),
    "UF_B_SQUARE" => array('required' => true, 'regexp' => '/^[1-9][0-9.]+[0-9]+/'),
    "UF_PLACEMENT_COUNT" =>array('required' => true, 'regexp' => '/^[0-9]+$/'),
    "UF_FLOORS_MIN"  =>array('required' => true, 'regexp' => '/^[0-9]+$/'),
    "UF_FLOORS_MAX"  =>array('required' => true, 'regexp' => '/^[0-9]+$/'),
    "UF_UNDER_FLOORS" =>array('required' => true, 'regexp' => '/^[0-9]+$/'),
    "UF_MATERIAL" =>array('required' => true, 'regexp' => ''),
    "UF_SECTIONS" => array('required' => true, 'regexp' => '/^[0-9]+$/'),
    "UF_ELEVATORS" => array('required' => true, 'regexp' => '/^[0-9]+$/'),
    "UF_B_PLOT_ID" => array('required' => true, 'regexp' => ''),
    "UF_YEAR_BUILT" => array('required' => false, 'regexp' => ''),
  ),
  "2" => array(
    "UF_BUILDING_TYPE_ID" => array('required' => true, 'regexp' => ''),
    "UF_BUILDING_ADDRESS" => array('required' => true, 'regexp' => '/^[А-Я][а-яА-Я\s0-9.,][^a-zA-Z]+/'),
    "UF_COUNTRY" => array('required' => true, 'regexp' => '/^[А-Я][а-яА-Я\s][^a-zA-Z]+/'),
    "UF_FED_DISTRICT" => array('required' => true, 'regexp' => '/^[А-Я][а-яА-Я\s][^a-zA-Z]+/'),
    "UF_PROVINCE" => array('required' => true, 'regexp' => '/^[А-Я][а-яА-Я\s][^a-zA-Z]+/'),
    "UF_AREA" => array('required' => true, 'regexp' => '/^[А-Я][а-яА-Я\s][^a-zA-Z]+/'),
    "UF_LOCALITY" => array('required' => true, 'regexp' => '/^[А-Я][а-яА-Я\s][^a-zA-Z]+/'),
    "UF_DISTRICT" => array('required' => false, 'regexp' => '/^[а-яА-Я\s][^a-zA-Z]+/'),
    "UF_STREET" => array('required' => true, 'regexp' => '/^[а-яА-Я0-9\s-][^a-zA-Z]+/'),
    "UF_HOUSE" => array('required' => true, 'regexp' => ''),
    "UF_POSTAL" => array('required' => true, 'regexp' => '/[0-9]{6}/'),
    "UF_LATITUDE" => array('required' => true, 'regexp' => '/^[1-9][0-9]+.[0-9]+/'),
    "UF_LONGITUDE" => array('required' => true, 'regexp' => '/^[1-9][0-9]+.[0-9]+/'),
    "UF_BUILDING_KAD_NUM" => array('required' => true, 'regexp' => '/^[0-9]{2}:[0-9]{2}:[0-9]+:[0-9]+/'),
    "UF_B_SQUARE" => array('required' => true, 'regexp' => '/^[1-9][0-9.]+[0-9]+/'),
    "UF_PLACEMENT_COUNT" =>array('required' => true, 'regexp' => '/^[0-9]+$/'),
    "UF_FLOORS_MAX"  =>array('required' => true, 'regexp' => '/^[0-9]+$/'),
    "UF_MATERIAL" =>array('required' => true, 'regexp' => ''),
    "UF_B_PLOT_ID" => array('required' => true, 'regexp' => ''),
    "UF_YEAR_BUILT" => array('required' => false, 'regexp' => ''),
  ),
  "3" => array(
    "UF_BUILDING_TYPE_ID" => array('required' => true, 'regexp' => ''),
    "UF_BUILDING_ADDRESS" => array('required' => true, 'regexp' => '/^[А-Я][а-яА-Я\s0-9.,][^a-zA-Z]+/'),
    "UF_COUNTRY" => array('required' => true, 'regexp' => '/^[А-Я][а-яА-Я\s][^a-zA-Z]+/'),
    "UF_FED_DISTRICT" => array('required' => true, 'regexp' => '/^[А-Я][а-яА-Я\s][^a-zA-Z]+/'),
    "UF_PROVINCE" => array('required' => true, 'regexp' => '/^[А-Я][а-яА-Я\s][^a-zA-Z]+/'),
    "UF_AREA" => array('required' => true, 'regexp' => '/^[А-Я][а-яА-Я\s][^a-zA-Z]+/'),
    "UF_LOCALITY" => array('required' => true, 'regexp' => '/^[А-Я][а-яА-Я\s][^a-zA-Z]+/'),
    "UF_DISTRICT" => array('required' => false, 'regexp' => '/^[а-яА-Я\s][^a-zA-Z]+/'),
    "UF_STREET" => array('required' => true, 'regexp' => '/^[а-яА-Я0-9\s-][^a-zA-Z]+/'),
    "UF_HOUSE" => array('required' => true, 'regexp' => ''),
    "UF_POSTAL" => array('required' => true, 'regexp' => '/[0-9]{6}/'),
    "UF_LATITUDE" => array('required' => true, 'regexp' => '/^[1-9][0-9]+.[0-9]+/'),
    "UF_LONGITUDE" => array('required' => true, 'regexp' => '/^[1-9][0-9]+.[0-9]+/'),
    "UF_BUILDING_KAD_NUM" => array('required' => true, 'regexp' => '/^[0-9]{2}:[0-9]{2}:[0-9]+:[0-9]+/'),
    "UF_B_SQUARE" => array('required' => true, 'regexp' => '/^[1-9][0-9.]+[0-9]+/'),
    "UF_PLACEMENT_COUNT" =>array('required' => true, 'regexp' => '/^[0-9]+$/'),
    "UF_LIVE_COUNT" =>array('required' => true, 'regexp' => '/^[0-9]+$/'),
    "UF_FLOORS_MIN"  =>array('required' => true, 'regexp' => '/^[0-9]+$/'),
    "UF_FLOORS_MAX"  =>array('required' => true, 'regexp' => '/^[0-9]+$/'),
    "UF_UNDER_FLOORS" =>array('required' => true, 'regexp' => '/^[0-9]+$/'),
    "UF_MATERIAL" =>array('required' => true, 'regexp' => ''),
    "UF_SECTIONS" => array('required' => true, 'regexp' => '/^[0-9]+$/'),
    "UF_ELEVATORS" => array('required' => true, 'regexp' => '/^[0-9]+$/'),
    "UF_B_PLOT_ID" => array('required' => true, 'regexp' => ''),
    "UF_RS" => array('required' => false, 'regexp' => ''),
    "UF_YEAR_BUILT" => array('required' => false, 'regexp' => ''),
  )
);

// $hlblock - это массив, 2 - hl блок Building
$hlblock   = HL\HighloadBlockTable::getById(2)->fetch();
$Building   = HL\HighloadBlockTable::compileEntity( $hlblock );
$BuildingDataClass = $Building->getDataClass();

// 6 - hl блок Plots
$hlblock   = HL\HighloadBlockTable::getById(6)->fetch();
$Plots   = HL\HighloadBlockTable::compileEntity( $hlblock );
$PlotsDataClass = $Plots->getDataClass();

// 23 - hl блок RealEstate - Жилые комплексы
$hlblock   = HL\HighloadBlockTable::getById(23)->fetch();
$ZHKs   = HL\HighloadBlockTable::compileEntity( $hlblock );
$ZHKDataClass = $ZHKs->getDataClass();

//24 - hl блок Materials - материалы здания
$hlblock   = HL\HighloadBlockTable::getById(24)->fetch();
$Materials   = HL\HighloadBlockTable::compileEntity( $hlblock );
$MaterialsDataClass = $Materials->getDataClass();

$template = 'type';//По-умолчанию шаблон выбора типа здания

//echo "<pre>";print_r($_POST);echo "</pre>";

if ($_POST['AJAX_CALL'] == 'Y'){//вызов из формы
  foreach ($_POST as $key=>$value) $arResult[$key] = $value;
  if ($arResult['ACTION'] == 'save') {
    $dataToSave = array();
    foreach($checkFields[$arResult['UF_BUILDING_TYPE_ID']] as $key=>$rules){
      $dataToSave[$key] = $arResult[$key];
      if ($rules['required'] && $arResult[$key] == ""){//обязательное поле пустое
        $arResult['errors'][$key] = "поле обязательно к заполнению";
        continue;
      }
      if ($rules['regexp'] !="" && $arResult[$key]!=""){//на формат проверяются непустые поля
        if (preg_match($rules['regexp'],$arResult[$key]) == 0 || preg_match($rules['regexp'],$arResult[$key]) === false) $arResult['errors'][$key] = "поле не соответствует формату";
      }
    }
    if (!isset($arResult['errors'])) {
      if ($arResult['ID']>0){
        $result = $BuildingDataClass::update($arResult['ID'], $dataToSave);
      }else{
         $result = $BuildingDataClass::add($dataToSave);
      }
      if ($result->isSuccess()) LocalRedirect('/townbase/building/show/'.$result->getId().'/');
      else  $arResult['errors']['global'] =implode(', ', $result->getErrors());
    }
  }
}else{//чистый вызов
  if ($arParams['ELEMENT_ID']>0){
    $row = $BuildingDataClass::getRowById($arParams['ELEMENT_ID']);
    foreach ($row as $key=>$value) $arResult[$key] = $value;
  }elseif (isset($_GET)){
    foreach ($_GET as $key=>$value) $arResult[$key] = $value;
  }
}

switch ($arResult['UF_BUILDING_TYPE_ID']){//Выбор шаблона
  case 1:
    $template = 'nonresidental';
    $materialFilter =  array("UF_NONRESIDENTAL" => 1);
    break;
  case 2:
    $template = 'private';
    $materialFilter = array("UF_PRIVATE" => 1);
    break;
  case 3:
    $template = 'multiflat';
    $materialFilter = array("UF_MULTIFLAT" => 1);
    break;
}

//Формируем список материалов
$rsData = $MaterialsDataClass::getList(
      array(
        "select" => array('*'), //выбираем все поля
        "filter" => $materialFilter,
        "order" => array("ID"=>"ASC"), // сортировка по полю ID, будет работать только, если вы завели такое поле в hl'блоке
      )
    );
$materialsData = $rsData->FetchAll();
$data = array("{id : '', text: 'нет'}");

foreach($materialsData as $material){

  if ($arResult['UF_MATERIAL'] == $material['ID']) $data[] = "{id: ".$material['ID'].", text: '".$material['UF_MATERIAL_NAME']."', selected: true}";
  else  $data[] = "{id: ".$material['ID'].", text: '".$material['UF_MATERIAL_NAME']."'}";
}
$arResult['MATERIALS'] = "[".implode(",",$data)."]";

//Формируем список участков
$rsData = $PlotsDataClass::getList(
  array(
    "select" => array('*'), //выбираем все поля
    "filter" => array(),
    "order" => array("ID"=>"ASC"), // сортировка по полю ID, будет работать только, если вы завели такое поле в hl'блоке
  )
);

$plotsData = $rsData->FetchAll();
$data = array("{id : '', text: 'нет'}");
foreach($plotsData as $plot){
  if ($arResult['UF_B_PLOT_ID'] == $plot['ID']) $data[] ="{ id:".$plot['ID'].",text:'".trim ($plot['UF_PLOT_KAD_NUM']." ".$plot['UF_PLOT_ADDRESS'])."', selected: true}";
  else $data[] ="{ id:".$plot['ID'].",text:'".trim ($plot['UF_PLOT_KAD_NUM']." ".$plot['UF_PLOT_ADDRESS'])."'}";
}
$arResult['PLOTS'] = "[".implode(",",$data)."]";

//Формируем список ЖК
$rsData = $ZHKDataClass::getList(
  array(
    "select" => array('*'), //выбираем все поля
    "filter" => array(),
    "order" => array("ID"=>"ASC"), // сортировка по полю ID, будет работать только, если вы завели такое поле в hl'блоке
  )
);
$ZHKData = $rsData->FetchAll();
$data = array("{id : '', text: 'нет'}");
foreach($ZHKData as $ZHK){
  if ($arResult['UF_RS'] == $ZHK['ID']) $data[] ="{ id:".$ZHK['ID'].",text:'".$ZHK['UF_NAMERS']."', selected: true}";
  else $data[] ="{ id:".$ZHK['ID'].",text:'".$ZHK['UF_NAMERS']."'}";
  $arResult['ZHKS'][$ZHK['ID']] = $ZHK['UF_NAMERS'];
}
$arResult['ZHKS'] = "[".implode(",",$data)."]";
$this->IncludeComponentTemplate($template);
?>