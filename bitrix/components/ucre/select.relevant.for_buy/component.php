<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
CModule::IncludeModule("crm");
//Читаем параметры поиска
$rsDeal = CCrmDeal::GetListEx(
  array(), 
  array("ID" => $arParams['ID']), 
  false, 
  false, 
  array("TITLE","ASSIGNED_BY_ID","UF_CRM_5895BC940ED3F","UF_CRM_58CFC7CDAAB96","UF_CRM_58958B529E628","UF_CRM_58958B52BA439","UF_CRM_58958B52F2BAC","UF_CRM_58958B51B667E","UF_CRM_58958B576448C","UF_CRM_58958B5751841"),
  array()
);
$arResult['ID'] = $arParams['ID'];
$mainDeal = $rsDeal->Fetch();
$arResult['ASSIGNED_BY_ID'] = $mainDeal['ASSIGNED_BY_ID'];
//Базовая SQL строка
$arResult["SQL_STRING"] = "SELECT b_crm_deal.ID,b_crm_deal.CATEGORY_ID, b_crm_deal.TITLE, b_crm_deal.ASSIGNED_BY_ID, b_uts_crm_deal.UF_CRM_1469534140,b_uts_crm_deal.UF_CRM_58958B5734602, b_iblock_element.NAME, b_iblock_element_prop_s42.PROPERTY_209, b_iblock_element_prop_s42.PROPERTY_210, b_iblock_element_prop_s42.PROPERTY_229, b_iblock_element_prop_s42.PROPERTY_224, b_iblock_element_prop_s42.PROPERTY_226, b_iblock_element_prop_s42.PROPERTY_221, b_iblock_element_prop_s42.PROPERTY_222 FROM b_crm_deal INNER JOIN b_uts_crm_deal ON b_crm_deal.ID=b_uts_crm_deal.VALUE_ID INNER JOIN b_iblock_element ON b_uts_crm_deal.UF_CRM_1469534140 = b_iblock_element.ID INNER JOIN b_iblock_element_prop_s42 ON b_iblock_element.ID = b_iblock_element_prop_s42.IBLOCK_ELEMENT_ID WHERE (b_crm_deal.STAGE_ID = 'PROPOSAL' or b_crm_deal.STAGE_ID = '8' or b_crm_deal.STAGE_ID = 'C4:1' or b_crm_deal.STAGE_ID = 'C4:PROPOSAL')";
//Фильтр по рынку поиска (Вторичка и /или Новостройки)
$arResult['SELECT_PARAMS']['MARKET'] = "нет данных";
if (count($mainDeal['UF_CRM_5895BC940ED3F']) == 2) {
  $arResult['SELECT_PARAMS']['MARKET'] = "Первичка, Вторичка";
  $arResult["SQL_STRING"] .= " AND (b_crm_deal.CATEGORY_ID = 0 or b_crm_deal.CATEGORY_ID = 4)";
}elseif ($mainDeal['UF_CRM_5895BC940ED3F'][0] == '827') {
  $arResult['SELECT_PARAMS']['MARKET'] = "Вторичка";
  $arResult["SQL_STRING"] .= " AND b_crm_deal.CATEGORY_ID = 0";
}elseif ($mainDeal['UF_CRM_5895BC940ED3F'][0] == '828') {
  $arResult['SELECT_PARAMS']['MARKET'] = "Первичка";
  $arResult["SQL_STRING"] .= " AND b_crm_deal.CATEGORY_ID = 4";
}
//Фильтр по типу обьъекта (комната, квартира и т.д.)
$arResult['SELECT_PARAMS']['TYPE'] = "нет данных";
switch ($mainDeal['UF_CRM_58CFC7CDAAB96']){
  case 1:
    $arResult["SQL_STRING"] .= " AND b_iblock_element_prop_s42.PROPERTY_210 = 381";
    $arResult['SELECT_PARAMS']['TYPE'] = "Комната";
    break;
  case 2:
    $arResult["SQL_STRING"] .= " AND (b_iblock_element_prop_s42.PROPERTY_210 = 382 or b_iblock_element_prop_s42.PROPERTY_210 = 384)"; //Дополнительно ищутся тауны
    $arResult['SELECT_PARAMS']['TYPE'] = "Квартира";
    break;
  case 3:
    $arResult["SQL_STRING"] .= " AND b_iblock_element_prop_s42.PROPERTY_210 = 383";
    $arResult['SELECT_PARAMS']['TYPE'] = "Дом";
    break;
  case 4:
    $arResult["SQL_STRING"] .= " AND (b_iblock_element_prop_s42.PROPERTY_210 = 384 or b_iblock_element_prop_s42.PROPERTY_210 = 382)"; //Дополнительно ищутся квартиры
    $arResult['SELECT_PARAMS']['TYPE'] = "Таунхаус";
    break;
  case 5:
    $arResult["SQL_STRING"] .= " AND b_iblock_element_prop_s42.PROPERTY_210 = 385";
    $arResult['SELECT_PARAMS']['TYPE'] = "Дача";
    break;
  case 6:
    $arResult["SQL_STRING"] .= " AND b_iblock_element_prop_s42.PROPERTY_210 = 386";
    $arResult['SELECT_PARAMS']['TYPE'] = "Участок";
    break;
  case 7:
    $arResult["SQL_STRING"] .= " AND b_iblock_element_prop_s42.PROPERTY_210 = 387";
    $arResult['SELECT_PARAMS']['TYPE'] = "Коммерческая";
    break;
}
//Фильтр по количеству комнат
$arResult['SELECT_PARAMS']['ROOMS'] = "нет данных";
if ($mainDeal['UF_CRM_58958B529E628']) {
  $arResult["SQL_STRING"] .= " AND b_iblock_element_prop_s42.PROPERTY_229 >=".$mainDeal['UF_CRM_58958B529E628'];
  $arResult['SELECT_PARAMS']['ROOMS'] = $mainDeal['UF_CRM_58958B529E628'];
}
//Фильтр по общей площади
$arResult['SELECT_PARAMS']['TOTAL_AREA'] = "нет данных";
if ($mainDeal['UF_CRM_58958B52BA439']) {
  $arResult["SQL_STRING"] .= " AND b_iblock_element_prop_s42.PROPERTY_224 >=".$mainDeal['UF_CRM_58958B52BA439'];
  $arResult['SELECT_PARAMS']['TOTAL_AREA'] = $mainDeal['UF_CRM_58958B52BA439'];
}
//Фильтр по площади кухни
$arResult['SELECT_PARAMS']['KITCHEN_AREA'] = "нет данных";
if ($mainDeal['UF_CRM_58958B52F2BAC']) {
  $arResult["SQL_STRING"] .= " AND b_iblock_element_prop_s42.PROPERTY_226 >=".$mainDeal['UF_CRM_58958B52F2BAC'];
  $arResult['SELECT_PARAMS']['KITCHEN_AREA'] = $mainDeal['UF_CRM_58958B52F2BAC'];
}
//Исключить этажи
$arResult['SELECT_PARAMS']['FIRST'] = "";
$arResult['SELECT_PARAMS']['LAST'] = "";
foreach ($mainDeal['UF_CRM_58958B51B667E'] as $ex_floor){
  if ($ex_floor == 754) {
    $arResult["SQL_STRING"] .=" AND b_iblock_element_prop_s42.PROPERTY_221 >1";
    $arResult['SELECT_PARAMS']['FIRST'] = "[не первый]";
  }
  if ($ex_floor == 755) {
    $arResult["SQL_STRING"] .=" AND b_iblock_element_prop_s42.PROPERTY_221 < b_iblock_element_prop_s42.PROPERTY_222";
    $arResult['SELECT_PARAMS']['LAST'] = "[не последний]";
  }
}
//Фильтр по минимальной цене
$arResult['SELECT_PARAMS']['MINPRICE'] = "нет данных";
if ($mainDeal['UF_CRM_58958B576448C']) {
  $arResult["SQL_STRING"] .= " AND b_uts_crm_deal.UF_CRM_58958B5734602 >=".$mainDeal['UF_CRM_58958B576448C'];
  $arResult['SELECT_PARAMS']['MINPRICE'] = $mainDeal['UF_CRM_58958B576448C'];
}
//Фильтр по максимальной цене
$arResult['SELECT_PARAMS']['MAXPRICE'] = "нет данных";
if ($mainDeal['UF_CRM_58958B5751841']) {
  $arResult["SQL_STRING"] .= " AND b_uts_crm_deal.UF_CRM_58958B5734602 <=".$mainDeal['UF_CRM_58958B5751841'];
  $arResult['SELECT_PARAMS']['MAXPRICE'] = $mainDeal['UF_CRM_58958B5751841'];
}
$arResult["SQL_STRING"] .= " ORDER BY b_crm_deal.ID DESC";
$this->IncludeComponentTemplate();
?>