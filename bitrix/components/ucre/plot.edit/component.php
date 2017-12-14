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
  "UF_PLOT_TYPE_ID" => array('required' => true, 'regexp' => ''),
  "UF_PLOT_ADDRESS" => array('required' => true, 'regexp' => '/^[А-Я][а-яА-Я\s0-9.,][^a-zA-Z]+/'),
  "UF_LATITUDE" => array('required' => true, 'regexp' => '/^[1-9][0-9]+.[0-9]+/'),
  "UF_LONGITUDE" => array('required' => true, 'regexp' => '/^[1-9][0-9]+.[0-9]+/'),
  "UF_PLOT_KAD_NUM" => array('required' => true, 'regexp' => '/^[0-9]{2}:[0-9]{2}:[0-9]+:[0-9]+/', 'unique' => true),
  "UF_P_SQUARE" => array('required' => true, 'regexp' => '/^[1-9][0-9.]+[0-9]+/'),
);

// $hlblock - это массив, 6 - hl блок Plot
$hlblock   = HL\HighloadBlockTable::getById(6)->fetch();
$Plot   = HL\HighloadBlockTable::compileEntity($hlblock);
$PlotDataClass = $Plot->getDataClass();

// $hlblock - это массив, 7 - hl блок PlotType
$hlblock   = HL\HighloadBlockTable::getById(7)->fetch();
$PlotType   = HL\HighloadBlockTable::compileEntity( $hlblock );
$PlotTypeDataClass = $PlotType->getDataClass();
//Формируем список категорий
$rsData = $PlotTypeDataClass::getList(
      array(
        "select" => array('*'), //выбираем все поля
        "filter" => array(),
        "order" => array("ID"=>"ASC"), // сортировка по полю ID, будет работать только, если вы завели такое поле в hl'блоке
      )
    );
$PlotTypeListByID = array();//Массив категорий по ID, для вывода текстового значения из базы
$PlotTypeListByCode = array();//Массив категорий по коду, дял вывода текстового значения по API Росреестра
while ($arPlot = $rsData->Fetch()){
  //$PlotTypeListByID[$arPlot['ID']]=array("UF_CODE" => $arPlot['UF_CODE'], "UF_PLOT_TYPE_SHORT" => $arPlot['UF_PLOT_TYPE_SHORT'],"UF_PLOT_TYPE" => $arPlot['UF_PLOT_TYPE']);
  $PlotTypeListByCode[$arPlot['UF_CODE']]=array("ID" => $arPlot['ID'], "UF_PLOT_TYPE_SHORT" => $arPlot['UF_PLOT_TYPE_SHORT'],"UF_PLOT_TYPE" => $arPlot['UF_PLOT_TYPE']);
}

//echo "<pre>";print_r($_POST);echo "</pre>";
//echo "<pre>";print_r($PlotTypeListByID);/*print_r($PlotTypeListByCode);*/echo "</pre>";
$arResult['PlotTypeListByCode'] = json_encode($PlotTypeListByCode,JSON_UNESCAPED_UNICODE);
if ($_POST['AJAX_CALL'] == 'Y'){//вызов из формы
  foreach ($_POST as $key=>$value) $arResult[$key] = $value;
  if ($arResult['ACTION'] == 'save') {
    $dataToSave = array();
    foreach($checkFields as $key=>$rules){
      $dataToSave[$key] = $arResult[$key];
      if ($rules['required'] && $arResult[$key] == ""){//обязательное поле пустое
        $arResult['errors'][$key] = "поле обязательно к заполнению";
        continue;
      }
      if ($rules['regexp'] !="" && $arResult[$key]!=""){//на формат проверяются непустые поля
        if (preg_match($rules['regexp'],$arResult[$key]) == 0 || preg_match($rules['regexp'],$arResult[$key]) === false) $arResult['errors'][$key] = "поле не соответствует формату";
      }
      if ($rules['unique']){
        $filter = array($key => $arResult[$key]);
        if ($arResult['ID']>0)  $filter['!ID'] = $arResult['ID'];
        $testData = $PlotDataClass::getList(
          array(
            "select" => array('*'), //выбираем все поля
            "filter" => $filter,
            "order" => array("ID"=>"ASC"), // сортировка по полю ID, будет работать только, если вы завели такое поле в hl'блоке
          )
        );
        if ($testData->getSelectedRowsCount()) {
          $arResult['errors'][$key] = "Запись не уникальна по полю.";
          while($arTest = $testData->Fetch()){
            $arResult['duplicates'][] = $arTest['ID'];
          }
        }
      }
    }
    
    if (!isset($arResult['errors'])) {
      if ($arResult['ID']>0){
        $result = $PlotDataClass::update($arResult['ID'], $dataToSave);
      }else{
        $result = $PlotDataClass::add($dataToSave);
      }
      if ($result->isSuccess()) LocalRedirect('/townbase/plot/show/'.$result->getId().'/');
      else  $arResult['errors']['global'] =implode(', ', $result->getErrors());
    }
    
  }
}else{//Чистый вызов
   if ($arParams['ELEMENT_ID']>0){
     $row = $PlotDataClass::getRowById($arParams['ELEMENT_ID']);
    foreach ($row as $key=>$value) $arResult[$key] = $value;
   }
}

$this->IncludeComponentTemplate();
?>