<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$arResult['CURRENT_USER_ID'] = CCrmSecurityHelper::GetCurrentUserID();
$arResult['GRID_ID'] = 'CRM_AVITOELEMENTLOG_LIST_V12';
$arResult['FILTER'] = array(
  array('id' => 'UF_CRM_ID', 'name' => 'ID или код объекта в системе'),
  //array('id' => 'UF_AVITO_LOG_ID', 'name' => 'ID лога на avito.ru', 'value' => $_GET['AVITO_ID']),
);
//инициализируем объект с настройками пользователя для нашего грида
$grid_options = new CGridOptions($arResult["GRID_ID"]);
//размер страницы в постраничке (передаем умолчания)
$aNav = $grid_options->GetNavParams(array("nPageSize"=>$arParams['AVITOELEMENT_COUNT']));
$arResult['HEADERS'] = array (
	array('id' => 'UF_AVITO_LOG_ID', 'name'=>'ID лога на avito.ru', 'default'=>true),
  array('id' => 'UF_CRM_ID', 'name' => 'ID или код объекта в системе', 'default' => true),
  array('id' => 'UF_AVITO_LINK', 'name' => 'Ссылка на объявление', 'default'=>true),
  array('id' => 'UF_STATUS', 'name' => 'Статус загрузки', 'default'=>true),
  array('id' => 'UF_STATUS_MORE', 'name' => 'Доп статус', 'default'=>true),
  array('id' => 'UF_TILL', 'name' => 'Срок публикации', 'default'=>true),
  array('id' => 'UF_MESSAGE', 'name' => 'Сообщения и ошибки', 'default'=>true),
);

//это собственно выборка данных с учетом сортировки и фильтра, указанных пользователем
//Список загруженных отчетов об автозагрузке

if (isset($arParams['AVITO_ID']) && $arParams['AVITO_ID']!="")
  if (isset($_REQUEST['UF_CRM_ID']) && $_REQUEST['UF_CRM_ID']!=""){
    $query = 'SELECT * FROM ucre_avito_log_element WHERE UF_AVITO_LOG_ID = '.$arParams['AVITO_ID'].' AND UF_CRM_ID = '.$_REQUEST['UF_CRM_ID'];
  }else{
    $query = 'SELECT * FROM ucre_avito_log_element WHERE UF_AVITO_LOG_ID = '.$arParams['AVITO_ID'].' ORDER BY UF_CRM_ID DESC';
  }
else
  if (isset($_REQUEST['UF_CRM_ID']) && $_REQUEST['UF_CRM_ID']!=""){
    $query = 'SELECT * FROM ucre_avito_log_element WHERE UF_CRM_ID = '.$_REQUEST['UF_CRM_ID'].' ORDER BY UF_AVITO_LOG_ID DESC';
  }else{
    $query = 'SELECT * FROM ucre_avito_log_element ORDER BY UF_AVITO_LOG_ID DESC';
  }
$rsData = $DB->Query($query);

//постраничка с учетом размера страницы
$rsData->NavStart($aNav["nPageSize"]);

//в этом цикле построчно заполняем данные для грида
$aRows = array();
while($arItem = $rsData->Fetch()){
  $aCols = array(
    'UF_AVITO_LINK' =>  '<a href="'.$arItem['UF_AVITO_LINK'].'" target="_blank" alt="Ссылка на объявление">'.$arItem['UF_AVITO_LINK'].'</a>',
  );
  //это определения для меню действий над строкой
   $aActions = array();
  //запомнили данные. "data" - вся выборка,  "editable" - можно редактировать строку или нет
   $aRows[] = array("data"=>$arItem, "actions"=>$aActions, "columns"=>$aCols, "editable"=>false);
}
//наши накопленные данные
$arResult["ROWS"] = $aRows;

//информация для футера списка
$arResult["AVITOELEMENT_COUNT"] = $rsData->SelectedRowsCount();//Так считают кол-во записей для HL блоков

//объект постранички - нужен гриду. Убираем ссылку "все".
$rsData->bShowAll = false;
$arResult["NAV_OBJECT"] = $rsData;
$this->IncludeComponentTemplate(); 
?>