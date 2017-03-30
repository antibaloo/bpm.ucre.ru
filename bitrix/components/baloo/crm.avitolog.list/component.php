<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$arResult['CURRENT_USER_ID'] = CCrmSecurityHelper::GetCurrentUserID();
$arResult['GRID_ID'] = 'CRM_AVITOLOG_LIST_V12';
//инициализируем объект с настройками пользователя для нашего грида
$grid_options = new CGridOptions($arResult["GRID_ID"]);
//размер страницы в постраничке (передаем умолчания)
$aNav = $grid_options->GetNavParams(array("nPageSize"=>$arParams['AVITOLOG_COUNT']));
$arResult['HEADERS'] = array (
	array('id' => 'UF_AVITO_ID', 'name'=>'ID лога на avito.ru', 'default'=>true),
  array('id' => 'UF_TIME', 'name' => 'Время загрузки', 'default' => true),
  array('id' => 'UF_STATUS', 'name' => 'Статус автозагрузки', 'default' => true),
  array('id' => 'UF_PROCESSED', 'name' => 'Обработано', 'default' => true),
  array('id' => 'UF_SUCCESS', 'name' => 'Успешно', 'default' => true),
  array('id' => 'UF_PROBLEMS', 'name' => 'С проблемами', 'default' => true),
  array('id' => 'UF_ERRORS', 'name' => 'С ошибками', 'default' => true),
  array('id' => 'UF_DELETED', 'name' => 'Удалено', 'default' => true),
);

//это собственно выборка данных с учетом сортировки и фильтра, указанных пользователем
//Список загруженных отчетов об автозагрузке
$query = 'SELECT * FROM ucre_avito_log ORDER BY UF_TIME DESC';
$rsData = $DB->Query($query);

//постраничка с учетом размера страницы
$rsData->NavStart($aNav["nPageSize"]);

//в этом цикле построчно заполняем данные для грида
$aRows = array();
while($arItem = $rsData->Fetch()){
	//$arItem['UF_TIME'] = strtotime($arItem['UF_TIME'])->modify('+1 hour');
  $aCols = array(
    'UF_AVITO_ID' =>  '<a href="https://bpm.ucre.ru/crm/avito_log/?AVITO_ID='.$arItem['UF_AVITO_ID'].'" alt="Фильтр объявлений по номеру лога">'.$arItem['UF_AVITO_ID'].'</a>',
    'UF_STATUS' =>  '<a href="'.$arItem['UF_LOG_LINK'].'" target="_blank" alt="Ссылка на лог автозагрузки">'.$arItem['UF_STATUS'].'</a>',
    'UF_PROCESSED' =>  '<a href="'.$arItem['UF_LINK'].'" target="_blank" alt="Ссылка на xml фид автозагрузки">'.$arItem['UF_PROCESSED'].'</a>',
		//'UF_TIME'	=> date("H:m:s d.m.Y",strtotime($arItem['UF_TIME']."+1 hour")),
  );
  //это определения для меню действий над строкой
   $aActions = array();
  //запомнили данные. "data" - вся выборка,  "editable" - можно редактировать строку или нет
   $aRows[] = array("data"=>$arItem, "actions"=>$aActions, "columns"=>$aCols, "editable"=>false);
}
//наши накопленные данные
$arResult["ROWS"] = $aRows;

//информация для футера списка
$arResult["AVITOLOG_COUNT"] = $rsData->SelectedRowsCount();//Так считают кол-во записей для HL блоков

//объект постранички - нужен гриду. Убираем ссылку "все".
$rsData->bShowAll = false;
$arResult["NAV_OBJECT"] = $rsData;
$this->IncludeComponentTemplate(); 
?>