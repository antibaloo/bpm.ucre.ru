<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$arResult['CURRENT_USER_ID'] = CCrmSecurityHelper::GetCurrentUserID();
$arResult['GRID_ID'] = 'CRM_AVITOLOG_LIST_V12';
//инициализируем объект с настройками пользователя для нашего грида
$grid_options = new CGridOptions($arResult["GRID_ID"]);

//размер страницы в постраничке (передаем умолчания)
$aNav = $grid_options->GetNavParams(array("nPageSize"=>$arParams['AVITOLOG_COUNT']));
$arResult['HEADERS'] = array (
	array('id' => 'UF_AVITO_ID', 'name'=>'№', 'default'=>true),
  array('id' => 'UF_TIME', 'name' => 'Дата', 'default' => true),
	array('id' => 'UF_STATUS', 'name' => 'Статус обработки', 'default' => true),//в статус прячется ссылка на лог на сайте авито
  array('id' => 'UF_PROCESSED', 'name' => 'В', 'default' => true),
  array('id' => 'UF_SUCCESS', 'name' => 'У', 'default' => true),
  array('id' => 'UF_PROBLEMS', 'name' => 'П', 'default' => true),
  array('id' => 'UF_ERRORS', 'name' => 'О', 'default' => true)
);
//это собственно выборка данных с учетом сортировки и фильтра, указанных пользователем
//Список загруженных отчетов об автозагрузке
$query = 'SELECT * FROM ucre_avito_log ORDER BY UF_TIME DESC';
$rsData = $DB->Query($query);

//постраничка с учетом размера страницы
$rsData->NavStart($aNav["nPageSize"]);

//в этом цикле построчно заполняем данные для грида
$aRows = array();
$this->IncludeComponentTemplate(); 
?>