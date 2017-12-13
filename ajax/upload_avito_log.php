<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("crm");

if (isset($_POST['id'])){
  header("Content-type: text/txt; charset=UTF-8");
  $tempDeal = new CCrmDeal;
  $tempob = $tempDeal->GetListEx(array(), array("ID" => $_POST['id']), false, false, array("UF_CRM_1469534140"),array());
  $tempFields = $tempob->Fetch();
  if ($tempFields['UF_CRM_1469534140']){
		$res = CIBlockElement::GetByID(intval($tempFields['UF_CRM_1469534140']));
		$ar_res = $res->GetNext();
		
		if (strlen($ar_res['CODE'])) $rsData = $DB->Query('SELECT ucre_avito_log_element.*, ucre_avito_log.UF_TIME FROM ucre_avito_log_element LEFT JOIN ucre_avito_log ON ucre_avito_log_element.UF_AVITO_LOG_ID = ucre_avito_log.UF_AVITO_ID WHERE UF_CRM_ID in ('.$ar_res['ID'].',"'.$ar_res['CODE'].'") ORDER BY UF_TIME DESC');
		else $rsData = $DB->Query('SELECT ucre_avito_log_element.*, ucre_avito_log.UF_TIME FROM ucre_avito_log_element LEFT JOIN ucre_avito_log ON ucre_avito_log_element.UF_AVITO_LOG_ID = ucre_avito_log.UF_AVITO_ID WHERE UF_CRM_ID = '.$ar_res['ID'].' ORDER BY UF_TIME DESC');
    /*
		if (intval($ar_res['CODE']) > 0){
			$rsData = $DB->Query('SELECT ucre_avito_log_element.*, ucre_avito_log.UF_TIME FROM ucre_avito_log_element LEFT JOIN ucre_avito_log ON ucre_avito_log_element.UF_AVITO_LOG_ID = ucre_avito_log.UF_AVITO_ID WHERE UF_CRM_ID = '.intval($ar_res['CODE']).' ORDER BY UF_TIME DESC');
		}else {
      $rsData = $DB->Query('SELECT ucre_avito_log_element.*, ucre_avito_log.UF_TIME FROM ucre_avito_log_element LEFT JOIN ucre_avito_log ON ucre_avito_log_element.UF_AVITO_LOG_ID = ucre_avito_log.UF_AVITO_ID WHERE UF_CRM_ID = '.$tempFields['UF_CRM_1469534140'].' ORDER BY UF_TIME DESC');
		}*/
    $gridId = $_POST['id']."_upload_avito_grid";
    $grid_options = new CGridOptions($gridId);
    //$aNav = $grid_options->GetNavParams(array("nPageSize"=>10));
    //sData->NavStart($aNav["nPageSize"]);
    //$rsData->bShowAll = false;
    $aRows = array();
    $count = 0;
    while($aRes = $rsData->GetNext()){
      $count++;
      $upload_date = ParseDateTime($aRes['UF_TIME'], "YYYY.MM.DD HH:MI:SS");
			$till = ParseDateTime($aRes['UF_TILL'], "YYYY.MM.DD HH:MI:SS");
      $aCols = array(
        "UF_TIME" => $upload_date['HH'].":".$upload_date['MI'].":".$upload_date['SS']." ".$upload_date['DD'].".".$upload_date['MM'].".".$upload_date['YYYY']." г.",
        "UF_AVITO_LINK" => "<a href=".$aRes['UF_AVITO_LINK']." target='_blank'>Объявление на Авито</a>",
        "UF_TILL" => $till['HH'].":".$till['MI'].":".$till['SS']." ".$till['DD'].".".$till['MM'].".".$till['YYYY']." г."
      );
      $aActions = array();
      $aRows[] = array("data"=>$aRes, "actions"=>$aActions, "columns"=>$aCols, "editable"=>false);
    }
    if ($count > 0){
    ob_start();
    $APPLICATION->IncludeComponent(
      "bitrix:main.interface.grid",
      "",
      array(
        //уникальный идентификатор грида
        "GRID_ID"=> $gridId,
        //описание колонок грида, поля типизированы
        "HEADERS"=>array(
          array("id"=>"UF_TIME", "name"=>"Дата загрузки", "default"=>true, "editable"=>false),
          array("id"=>"UF_AVITO_LINK", "name"=>"Ссылка на объявление", "default"=>true, "editable"=>false),
          array("id"=>"UF_STATUS", "name"=>"Статус", "default"=>true, "editable"=>false),
          array("id"=>"UF_STATUS_MORE", "name"=>"Дополнительно", "default"=>true, "editable"=>false),
          array("id"=>"UF_TILL", "name"=>"Срок размещения", "default"=>true, "editable"=>false),
          array("id"=>"UF_MESSAGE", "name"=>"Сообщение", "default"=>true, "editable"=>false),
        ),
      //данные
      "ROWS"=>$aRows,
      //футер списка, можно задать несколько секций
      "FOOTER"=>array(array("title"=>"Всего", "value"=>$rsData->SelectedRowsCount())),
      //групповые действия
      "ACTIONS"=>array(),
      //разрешить действия над всеми элементами
      "ACTION_ALL_ROWS"=>false,
      //разрешено редактирование в списке
      "EDITABLE"=>false,
      //объект постранички
      "NAV_OBJECT"=>"",
      //можно использовать в режиме ajax
      "AJAX_MODE"=>"Y",
      "AJAX_OPTION_JUMP"=>"N",
      "AJAX_OPTION_STYLE"=>"Y",
      ),
      false
    );
      $avitoVal = ob_get_contents();
      ob_end_clean(); 
    }else{
      echo "<br><h2>Выгрузка объекта ".$tempFields['UF_CRM_1469534140']." не производилась!<h2>";
    }
  }else{
    echo "<br><h2>Нет связанного объекта!<h2>";
  }
}
?>
