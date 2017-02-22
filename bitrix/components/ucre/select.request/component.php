<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
require_once $_SERVER["DOCUMENT_ROOT"].'/include/dompdf-0.7.0/autoload.inc.php';
use \Bitrix\Crm\Category\DealCategory;
CModule::IncludeModule("crm");
if ($arParams['CATEGORY'] == '2'){ //Поиск встречных заявок для заявок на покупку
  //Читаем параметры поиска
  $rsDeal = CCrmDeal::GetListEx(
    array(), 
    array("ID" => $arParams['ID']), 
    false, 
    false, 
    array("TITLE","UF_CRM_5895BC940ED3F","UF_CRM_58958B5724514","UF_CRM_58958B529E628","UF_CRM_58958B52BA439","UF_CRM_58958B52F2BAC","UF_CRM_58958B51B667E","UF_CRM_58958B576448C","UF_CRM_58958B5751841"),
    array()
  );
  $mainDeal = $rsDeal->Fetch();
  //Фильтр по рынку поиска (Вторичка и /или Новостройки)
  if (count($mainDeal['UF_CRM_5895BC940ED3F']) == 0 || count($mainDeal['UF_CRM_5895BC940ED3F']) == 2) {
    $market = " AND (b_crm_deal.CATEGORY_ID = 0 or b_crm_deal.CATEGORY_ID = 4)";
    $arResult['SELECT_PARAMS']['MARKET'] = "Первичка, Вторичка";
  } elseif ($mainDeal['UF_CRM_5895BC940ED3F'][0] == '827') {
    $market = " AND b_crm_deal.CATEGORY_ID = 0";
    $arResult['SELECT_PARAMS']['MARKET'] = "Вторичка";
  } elseif ($mainDeal['UF_CRM_5895BC940ED3F'][0] == '828') {
    $market = " AND b_crm_deal.CATEGORY_ID = 4";
    $arResult['SELECT_PARAMS']['MARKET'] = "Первичка";
  }
  //Фильтр по типу обьъекта (комната, квартира и т.д.)
  $arResult['SELECT_PARAMS']['TYPE'] = "нет данных";
  switch ($mainDeal['UF_CRM_58958B5724514']){
    case 813:
      $type = " AND b_iblock_element_prop_s42.PROPERTY_210 = 381";
      $arResult['SELECT_PARAMS']['TYPE'] = "Комната";
      break;
    case 814:
      $type = " AND (b_iblock_element_prop_s42.PROPERTY_210 = 382 or b_iblock_element_prop_s42.PROPERTY_210 = 384)"; //Дополнительно ищутся тауны
      $arResult['SELECT_PARAMS']['TYPE'] = "Квартира";
      break;
    case 815:
      $type = " AND b_iblock_element_prop_s42.PROPERTY_210 = 383";
      $arResult['SELECT_PARAMS']['TYPE'] = "Дом";
      break;
    case 816:
      $type = " AND (b_iblock_element_prop_s42.PROPERTY_210 = 384 or b_iblock_element_prop_s42.PROPERTY_210 = 382)"; //Дополнительно ищутся квартиры
      $arResult['SELECT_PARAMS']['TYPE'] = "Таунхаус";
      break;
    case 817:
      $type = " AND b_iblock_element_prop_s42.PROPERTY_210 = 385";
      $arResult['SELECT_PARAMS']['TYPE'] = "Дача";
      break;
    case 818:
      $type = " AND b_iblock_element_prop_s42.PROPERTY_210 = 386";
      $arResult['SELECT_PARAMS']['TYPE'] = "Участок";
      break;
    case 819:
      $type = " AND b_iblock_element_prop_s42.PROPERTY_210 = 387";
      $arResult['SELECT_PARAMS']['TYPE'] = "Коммерческая";
      break;
  }
  //Фильтр по количеству комнат
  $arResult['SELECT_PARAMS']['ROOMS'] = "нет данных";
  if ($mainDeal['UF_CRM_58958B529E628']) {
    $rooms = " AND b_iblock_element_prop_s42.PROPERTY_229 >=".$mainDeal['UF_CRM_58958B529E628'];
    $arResult['SELECT_PARAMS']['ROOMS'] = $mainDeal['UF_CRM_58958B529E628'];
  }
  //Фильтр по общей площади
  $arResult['SELECT_PARAMS']['TOTAL_AREA'] = "нет данных";
  if ($mainDeal['UF_CRM_58958B52BA439']) {
    $totalArea = " AND b_iblock_element_prop_s42.PROPERTY_224 >=".$mainDeal['UF_CRM_58958B52BA439'];
    $arResult['SELECT_PARAMS']['TOTAL_AREA'] = $mainDeal['UF_CRM_58958B52BA439'];
  }
  //Фильтр по площади кухни
  $arResult['SELECT_PARAMS']['KITCHEN_AREA'] = "нет данных";
  if ($mainDeal['UF_CRM_58958B52F2BAC']) {
    $kitchenArea = " AND b_iblock_element_prop_s42.PROPERTY_226 >=".$mainDeal['UF_CRM_58958B52F2BAC'];
    $arResult['SELECT_PARAMS']['KITCHEN_AREA'] = $mainDeal['UF_CRM_58958B52F2BAC'];
  }
  //Исключить этажи
  $arResult['SELECT_PARAMS']['EX_FLOORS'] = "";
  foreach ($mainDeal['UF_CRM_58958B51B667E'] as $ex_floor){
    if ($ex_floor == 754) {
      $nFirst=" AND b_iblock_element_prop_s42.PROPERTY_221 >1";
      $arResult['SELECT_PARAMS']['EX_FLOORS'] .= "[не первый]";
    }
    if ($ex_floor == 755) {
      $nLast=" AND b_iblock_element_prop_s42.PROPERTY_221 < b_iblock_element_prop_s42.PROPERTY_222";
      $arResult['SELECT_PARAMS']['EX_FLOORS'] .= "[не последний]";
    }
  }
  //Фильтр по минимальной цене
  $arResult['SELECT_PARAMS']['MINPRICE'] = "нет данных";
  if ($mainDeal['UF_CRM_58958B576448C']) {
    $minprice = " AND b_uts_crm_deal.UF_CRM_58958B5734602 >=".$mainDeal['UF_CRM_58958B576448C'];
    $arResult['SELECT_PARAMS']['MINPRICE'] = $mainDeal['UF_CRM_58958B576448C'];
  }
  //Фильтр по максимальной цене
  $arResult['SELECT_PARAMS']['MAXPRICE'] = "нет данных";
  if ($mainDeal['UF_CRM_58958B5751841']) {
    $maxprice = " AND b_uts_crm_deal.UF_CRM_58958B5734602 <=".$mainDeal['UF_CRM_58958B5751841'];
    $arResult['SELECT_PARAMS']['MAXPRICE'] = $mainDeal['UF_CRM_58958B5751841'];
  }
  //Выборка всех нужных полей и объединения таблицы Заявки+Польз_поля_заявки_Инфоблок_ОН+Польз_поля_инфоблок_ОН    
  $rsQuery = "SELECT b_crm_deal.ID,b_crm_deal.CATEGORY_ID, b_crm_deal.TITLE, b_crm_deal.ASSIGNED_BY_ID, b_uts_crm_deal.UF_CRM_1469534140,b_uts_crm_deal.UF_CRM_58958B5734602, b_iblock_element.NAME, b_iblock_element_prop_s42.PROPERTY_210, b_iblock_element_prop_s42.PROPERTY_229, b_iblock_element_prop_s42.PROPERTY_224, b_iblock_element_prop_s42.PROPERTY_226, b_iblock_element_prop_s42.PROPERTY_221, b_iblock_element_prop_s42.PROPERTY_222 FROM b_crm_deal INNER JOIN b_uts_crm_deal ON b_crm_deal.ID=b_uts_crm_deal.VALUE_ID INNER JOIN b_iblock_element ON b_uts_crm_deal.UF_CRM_1469534140 = b_iblock_element.ID INNER JOIN b_iblock_element_prop_s42 ON b_iblock_element.ID = b_iblock_element_prop_s42.IBLOCK_ELEMENT_ID WHERE (b_crm_deal.STAGE_ID = 'PROPOSAL' or b_crm_deal.STAGE_ID = '8' or b_crm_deal.STAGE_ID = 'C4:1' or b_crm_deal.STAGE_ID = 'C4:PROPOSAL')";
  $rsQuery .= $market;
  $rsQuery .= $type;
  $rsQuery .= $rooms;
  $rsQuery .= $totalArea;
  $rsQuery .= $kitchenArea;
  $rsQuery .= $nFirst;
  $rsQuery .= $nLast;
  $rsQuery .= $minprice;
  $rsQuery .= $maxprice;
  $rsQuery .= " ORDER BY b_crm_deal.ID DESC";
  $rsData = $DB->Query($rsQuery);
  
  $arResult["GRID_ID"] = $arParams['ID']."_".$arParams['CATEGORY'];
  $grid_options = new CGridOptions($arResult["GRID_ID"]);
  $aSort = $grid_options->GetSorting(array("sort"=>array("ID"=>"desc"), "vars"=>array("by"=>"by", "order"=>"order")));
  $aNav = $grid_options->GetNavParams(array("nPageSize"=>10));
  $aSortArg = each($aSort["sort"]);
  $rsData->NavStart($aNav["nPageSize"]);
  
  $arResult['HEADERS'] = array(
    array("id"=>"ID", "name"=>"id заявки", "default"=>true, "editable"=>false),
    array("id"=>"TITLE", "name"=>"Название заявки", "default"=>true, "editable"=>false),
    array("id"=>"UF_CRM_58958B5734602", "name"=>"Цена, руб.", "default"=>true, "editable"=>false),
    array("id"=>"NAME", "name"=>"Наименование  объекта", "default"=>true, "editable"=>false),
    array("id"=>"PROPERTY_229", "name"=>"N<sub>комнат</sub>", "default"=>true, "editable"=>false),
    array("id"=>"PROPERTY_224", "name"=>"S<sub>общ.</sub>", "default"=>true, "editable"=>false),
    array("id"=>"PROPERTY_226", "name"=>"S<sub>кухни</sub>", "default"=>true, "editable"=>false),
    array("id"=>"PROPERTY_222", "name"=>"Этажность", "default"=>true, "editable"=>false),
    array("id"=>"ASSIGNED_BY_ID", "name"=>"Ответственный", "default"=>true, "editable"=>false),
  );
  
  while($aRes = $rsData->Fetch()){
    $assigned_user = CUser::GetByID($aRes['ASSIGNED_BY_ID'])->Fetch();
    $aCols = array(
      "TITLE" => "<a href='/crm/deal/show/".$aRes['ID']."/' target='_blank'>".DealCategory::getName($aRes['CATEGORY_ID']).": ".$aRes['TITLE']."</a>",
      "NAME" => "<a href='/crm/ro/?show&id=".$aRes['UF_CRM_1469534140']."' target='_blank'>".$aRes['NAME']."</a>",
      "PROPERTY_222" => $aRes['PROPERTY_221']."/".$aRes['PROPERTY_222'],
      "PROPERTY_229" => number_format($aRes['PROPERTY_229'],0),
      "PROPERTY_224" => number_format($aRes['PROPERTY_224'],2),
      "PROPERTY_226" => number_format($aRes['PROPERTY_226'],2),
      "ASSIGNED_BY_ID" => $assigned_user['LAST_NAME']." ".$assigned_user['NAME'],
    );
    $aActions = array();
    $aRows[] = array("data"=>$aRes, "actions"=>$aActions, "columns"=>$aCols, "editable"=>false);
  }
  $arResult["ROWS"] = $aRows;
  $arResult["ROWS_COUNT"] = $rsData->SelectedRowsCount();
  $arResult["SORT"] = $aSort["sort"];
  $arResult["SORT_VARS"] = $aSort["vars"];
  $rsData->bShowAll = false;
  $arResult["NAV_OBJECT"] = $rsData;
}elseif (in_array($arParams['CATEGORY'], array(0,4))){ //Поиск встречных заявок для заявок на продажу/новостройки
  //Читаем параметры поиска
  $rsQuery = "SELECT b_crm_deal.ID, b_uts_crm_deal.UF_CRM_1469534140,b_uts_crm_deal.UF_CRM_58958B5734602, b_iblock_element_prop_s42.PROPERTY_210, b_iblock_element_prop_s42.PROPERTY_229, b_iblock_element_prop_s42.PROPERTY_224, b_iblock_element_prop_s42.PROPERTY_226, b_iblock_element_prop_s42.PROPERTY_221, b_iblock_element_prop_s42.PROPERTY_222 FROM b_crm_deal INNER JOIN b_uts_crm_deal ON b_crm_deal.ID=b_uts_crm_deal.VALUE_ID INNER JOIN b_iblock_element ON b_uts_crm_deal.UF_CRM_1469534140 = b_iblock_element.ID INNER JOIN b_iblock_element_prop_s42 ON b_iblock_element.ID = b_iblock_element_prop_s42.IBLOCK_ELEMENT_ID WHERE b_crm_deal.ID =".$arParams['ID'];
  $rsData = $DB->Query($rsQuery);
  $mainDeal = $rsData->Fetch();
  
  //Фильтр по параметрам поиска
  if ($arParams['CATEGORY'] == 0){
    $arResult['SELECT_PARAMS']['MARKET'] = "Вторичка";
    $market = "AND (LOCATE('827',b_uts_crm_deal.UF_CRM_5895BC940ED3F) OR b_uts_crm_deal.UF_CRM_5895BC940ED3F = 'a:0:{}')";
  }else{
    $arResult['SELECT_PARAMS']['MARKET'] = "Первичка";
    $market = "AND (LOCATE('828',b_uts_crm_deal.UF_CRM_5895BC940ED3F) OR b_uts_crm_deal.UF_CRM_5895BC940ED3F = 'a:0:{}')";
  }
  
  //Фильтр по этажности
  if ($mainDeal['PROPERTY_221'] == 1 ){
    $nFirst = " AND LOCATE('754',b_uts_crm_deal.UF_CRM_58958B51B667E)=0";
  }
  if ($mainDeal['PROPERTY_221'] == $mainDeal['PROPERTY_222']){
    $nLast = " AND LOCATE('755',b_uts_crm_deal.UF_CRM_58958B51B667E)=0";
  }
  $arResult['SELECT_PARAMS']['FLOORS'] = (($mainDeal['PROPERTY_221'])?$mainDeal['PROPERTY_221']:"нет данных")."/".(($mainDeal['PROPERTY_222'])?$mainDeal['PROPERTY_222']:"нет данных");
  //Фильтр по типу недвижимости
  switch ($mainDeal['PROPERTY_210']){
    case 381:
      $arResult['SELECT_PARAMS']['TYPE'] = "комната";
      $type = " AND b_uts_crm_deal.UF_CRM_58958B5724514=813";
      break;
    case 382:
      $arResult['SELECT_PARAMS']['TYPE'] = "квартира";
      $type = " AND (b_uts_crm_deal.UF_CRM_58958B5724514=814 OR b_uts_crm_deal.UF_CRM_58958B5724514=816)";
      break;
    case 383:
      $arResult['SELECT_PARAMS']['TYPE'] = "дом";
      $type = " AND b_uts_crm_deal.UF_CRM_58958B5724514=815";
      break;
    case 384:
      $arResult['SELECT_PARAMS']['TYPE'] = "таунхаус";
      $type = " AND (b_uts_crm_deal.UF_CRM_58958B5724514=816 OR b_uts_crm_deal.UF_CRM_58958B5724514=814)";
      break;
    case 385:
      $arResult['SELECT_PARAMS']['TYPE'] = "дача";
      $type = " AND b_uts_crm_deal.UF_CRM_58958B5724514=817";
      break;
    case 386:
      $arResult['SELECT_PARAMS']['TYPE'] = "участок";
      $type = " AND b_uts_crm_deal.UF_CRM_58958B5724514=818";
      break;
    case 387:
      $arResult['SELECT_PARAMS']['TYPE'] = "коммерческий";
      $type = " AND b_uts_crm_deal.UF_CRM_58958B5724514=819";
      break;
  }
  if(intval($mainDeal['PROPERTY_229'])){
    $arResult['SELECT_PARAMS']['ROOMS'] = intval($mainDeal['PROPERTY_229']);
    $rooms = " AND (b_uts_crm_deal.UF_CRM_58958B529E628<=".intval($mainDeal['PROPERTY_229'])." OR b_uts_crm_deal.UF_CRM_58958B529E628 IS NULL)";
  }else{
    $arResult['SELECT_PARAMS']['ROOMS'] = "нет данных";
  }
  if(floatval($mainDeal['PROPERTY_224'])){
    $arResult['SELECT_PARAMS']['TOTAL_AREA'] = floatval($mainDeal['PROPERTY_224']);
    $totalArea = " AND (b_uts_crm_deal.UF_CRM_58958B52BA439<=".floatval($mainDeal['PROPERTY_224'])." OR b_uts_crm_deal.UF_CRM_58958B52BA439 IS NULL)";
  }else{
    $arResult['SELECT_PARAMS']['TOTAL_AREA'] = "нет данных";
  }
  if(floatval($mainDeal['PROPERTY_226'])){
    $arResult['SELECT_PARAMS']['KITCHEN_AREA'] = floatval($mainDeal['PROPERTY_226']);
    $kitchenArea = " AND (b_uts_crm_deal.UF_CRM_58958B52F2BAC<=".floatval($mainDeal['PROPERTY_226'])." OR b_uts_crm_deal.UF_CRM_58958B52F2BAC IS NULL)";
  }else{
    $arResult['SELECT_PARAMS']['KITCHEN_AREA'] = "нет данных";
  }
  if (intval($mainDeal['UF_CRM_58958B5734602'])){
    $arResult['SELECT_PARAMS']['PRICE'] = intval($mainDeal['UF_CRM_58958B5734602']);
    $minprice = " AND (b_uts_crm_deal.UF_CRM_58958B576448C<=".intval($mainDeal['UF_CRM_58958B5734602'])." OR b_uts_crm_deal.UF_CRM_58958B576448C IS NULL)";
    $maxprice = " AND (b_uts_crm_deal.UF_CRM_58958B5751841>=".intval($mainDeal['UF_CRM_58958B5734602'])." OR b_uts_crm_deal.UF_CRM_58958B5751841 IS NULL OR b_uts_crm_deal.UF_CRM_58958B5751841=0)";
  } else {
    $arResult['SELECT_PARAMS']['PRICE'] = "не задана";
  }

  $rsQuery = "SELECT b_crm_deal.ID, b_crm_deal.TITLE, b_crm_deal.ASSIGNED_BY_ID, b_uts_crm_deal.UF_CRM_5895BC940ED3F,b_uts_crm_deal.UF_CRM_58958B5724514,b_uts_crm_deal.UF_CRM_58958B529E628,b_uts_crm_deal.UF_CRM_58958B52BA439,b_uts_crm_deal.UF_CRM_58958B52F2BAC,b_uts_crm_deal.UF_CRM_58958B51B667E, b_uts_crm_deal.UF_CRM_58958B576448C, b_uts_crm_deal.UF_CRM_58958B5751841 FROM b_crm_deal INNER JOIN b_uts_crm_deal ON b_crm_deal.ID=b_uts_crm_deal.VALUE_ID WHERE b_crm_deal.CATEGORY_ID = 2 AND b_crm_deal.STAGE_ID = 'C2:PROPOSAL'";
  $rsQuery .= $market;
  $rsQuery .= $nFirst;
  $rsQuery .= $nLast;
  $rsQuery .= $type;
  $rsQuery .= $rooms;
  $rsQuery .= $totalArea;
  $rsQuery .= $kitchenArea;
  $rsQuery .= $minprice;
  $rsQuery .= $maxprice;

  $rsQuery .= " ORDER BY b_crm_deal.ID DESC";
  $rsData = $DB->Query($rsQuery);
  
  $arResult["GRID_ID"] = $arParams['ID']."_".$arParams['CATEGORY'];
  $grid_options = new CGridOptions($arResult["GRID_ID"]);
  $aSort = $grid_options->GetSorting(array("sort"=>array("ID"=>"desc"), "vars"=>array("by"=>"by", "order"=>"order")));
  $aNav = $grid_options->GetNavParams(array("nPageSize"=>10));
  $aSortArg = each($aSort["sort"]);
  $rsData->NavStart($aNav["nPageSize"]);
  
  $arResult['HEADERS'] = array(
    array("id"=>"ID", "name"=>"id заявки", "default"=>true, "editable"=>false),
    array("id"=>"TITLE", "name"=>"Название заявки", "default"=>true, "editable"=>false),
    array("id"=>"UF_CRM_5895BC940ED3F", "name"=>"Рынок", "default"=>true, "editable"=>false),
    array("id"=>"UF_CRM_58958B5724514", "name"=>"Тип  объекта", "default"=>true, "editable"=>false),
    array("id"=>"UF_CRM_58958B529E628", "name"=>"N<sub>комнат</sub> от", "default"=>true, "editable"=>false),
    array("id"=>"UF_CRM_58958B52BA439", "name"=>"S<sub>общ.</sub> от", "default"=>true, "editable"=>false),
    array("id"=>"UF_CRM_58958B52F2BAC", "name"=>"S<sub>кухни</sub> от", "default"=>true, "editable"=>false),
    array("id"=>"UF_CRM_58958B51B667E", "name"=>"Этажи", "default"=>true, "editable"=>false),
    array("id"=>"UF_CRM_58958B576448C", "name"=>"Цена <sub>min</sub>", "default"=>true, "editable"=>false),
    array("id"=>"UF_CRM_58958B5751841", "name"=>"Цена <sub>max</sub>", "default"=>true, "editable"=>false),
    array("id"=>"ASSIGNED_BY_ID", "name"=>"Ответственный", "default"=>true, "editable"=>false),
  );
  while($aRes = $rsData->Fetch()){
    $assigned_user = CUser::GetByID($aRes['ASSIGNED_BY_ID'])->Fetch();
    switch ($aRes['UF_CRM_5895BC940ED3F']){
      case "a:2:{i:0;i:827;i:1;i:828;}":
        $market = "любой";
        break;
      case "a:0:{}":
        $market = "нет данных";
        break;
      case "a:1:{i:0;i:827;}":
        $market = "вторичка";
        break;
      case "a:1:{i:0;i:828;}":
        $market = "первичка";
        break;
    }
    switch ($aRes['UF_CRM_58958B51B667E']){
      case "a:2:{i:0;i:754;i:1;i:755;}":
        $floors = "не первый,<br>не последний";
        break;
      case "a:0:{}":
        $floors = "любой";
        break;
      case "a:1:{i:0;i:754;}":
        $floors = "не первый";
        break;
      case "a:1:{i:0;i:755;}":
        $floors = "не последний";
        break;
    }
    switch ($aRes['UF_CRM_58958B5724514']){
      case 813:
        $type = "комната";
        break;
      case 814:
        $type = "квартира";
        break;
      case 815:
        $type = "дом";
        break;
      case 816:
        $type = "таунхаус";
        break;
      case 817:
        $type = "дача";
        break;
      case 818:
        $type = "участок";
        break;
      case 819:
        $type = "коммерческий";
        break;
    }
    $aCols = array(
      "TITLE" => "<a href='/crm/deal/show/".$aRes['ID']."/' target='_blank'>".$aRes['TITLE']."</a>",
      "UF_CRM_5895BC940ED3F" => $market,
      "UF_CRM_58958B51B667E" => $floors,
      "UF_CRM_58958B5724514" => $type,
      "UF_CRM_58958B529E628" => (intval($aRes['UF_CRM_58958B529E628']))?intval($aRes['UF_CRM_58958B529E628']):"нет данных",
      "UF_CRM_58958B52BA439" => (floatval($aRes['UF_CRM_58958B52BA439']))?floatval($aRes['UF_CRM_58958B52BA439']):"нет данных",
      "UF_CRM_58958B52F2BAC" => (floatval($aRes['UF_CRM_58958B52F2BAC']))?floatval($aRes['UF_CRM_58958B52F2BAC']):"нет данных",
      "UF_CRM_58958B576448C" => (intval($aRes['UF_CRM_58958B576448C']))?intval($aRes['UF_CRM_58958B576448C']):"не задана",
      "UF_CRM_58958B5751841" => (intval($aRes['UF_CRM_58958B5751841']))?intval($aRes['UF_CRM_58958B5751841']):"не задана",
      "ASSIGNED_BY_ID" => $assigned_user['LAST_NAME']." ".$assigned_user['NAME'],
    );
    $aActions = array();
    $aRows[] = array("data"=>$aRes, "actions"=>$aActions, "columns"=>$aCols, "editable"=>false);
  }
  
  $arResult["ROWS"] = $aRows;
  $arResult["ROWS_COUNT"] = $rsData->SelectedRowsCount();
  $arResult["SORT"] = $aSort["sort"];
  $arResult["SORT_VARS"] = $aSort["vars"];
  $rsData->bShowAll = false;
  $arResult["NAV_OBJECT"] = $rsData;


}
$this->IncludeComponentTemplate();
?>
