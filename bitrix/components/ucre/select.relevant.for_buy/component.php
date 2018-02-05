<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
CModule::IncludeModule("crm");
/*Справочник типов домов*/
  $housetype = array(
    '1890' => '426|Блочный',
    '1891' => '427|Деревянный',
    '1892' => '428|Кирпичный',
    '1893' => '429|Монолитно-Кирпичный',
    '1894' => '430|Монолитный',
    '1895' => '431|Панельный',
    '1896' => '432|Сталинский',
    '1897' => '433|Элитный'
  );
  /*Справочник материалов стен*/
  $wallstype = array(
    '1898' => '413|блок',
    '1899' => '414|бревно',
    '1900' => '415|брус',
    '1901' => '416|иное',
    '1902' => '417|каркасно-щитовой',
    '1903' => '418|кирпич',
    '1904' => '419|монолит',
    '1906' => '421|оцилиндрованное бревно',
    '1907' => '422|панели"',
    '1908' => '423|пеноблок',
    '1909' => '424|сэндвич',
    '1910' => '425|шлакоблок'
  );
//Читаем параметры поиска
$rsDeal = CCrmDeal::GetListEx(
  array(), 
  array("ID" => $arParams['ID']), 
  false, 
  false, 
  array("TITLE","ASSIGNED_BY_ID","UF_CRM_5895BC940ED3F","UF_CRM_58CFC7CDAAB96","UF_CRM_58958B529E628","UF_CRM_58958B52BA439","UF_CRM_58958B52F2BAC","UF_CRM_58958B576448C","UF_CRM_58958B5751841","UF_CRM_1502433005","UF_CRM_1505802775","UF_CRM_1505802786","UF_CRM_58958B532A119","UF_CRM_1505805281","UF_CRM_1505805394","UF_CRM_1505965059","UF_CRM_1506501917","UF_CRM_1506501950"),
  array()
);
$arResult['ID'] = $arParams['ID'];
$arResult['COMPONENT_PATH'] = $this->GetPath(); //Пусть к папке компонента для вызова ajax скриптов
$mainDeal = $rsDeal->Fetch();
$arResult['ASSIGNED_BY_ID'] = $mainDeal['ASSIGNED_BY_ID'];
//Базовая SQL строка
$arResult["SQL_STRING"] = "SELECT b_crm_deal.ID,b_crm_deal.CATEGORY_ID, b_crm_deal.TITLE, b_crm_deal.ASSIGNED_BY_ID, b_crm_deal.STAGE_ID, b_uts_crm_deal.UF_CRM_1469534140,b_uts_crm_deal.UF_CRM_58958B5734602, b_uts_crm_deal.UF_CRM_1512621495, b_iblock_element.NAME, b_iblock_element_prop_s42.PROPERTY_209, b_iblock_element_prop_s42.PROPERTY_210, b_iblock_element_prop_s42.PROPERTY_215, b_iblock_element_prop_s42.PROPERTY_216, b_iblock_element_prop_s42.PROPERTY_217, b_iblock_element_prop_s42.PROPERTY_218, b_iblock_element_prop_s42.PROPERTY_229, b_iblock_element_prop_s42.PROPERTY_224, b_iblock_element_prop_s42.PROPERTY_226, b_iblock_element_prop_s42.PROPERTY_221, b_iblock_element_prop_s42.PROPERTY_222, b_iblock_element_prop_s42.PROPERTY_241, b_iblock_element_prop_s42.PROPERTY_242, b_iblock_element_prop_s42.PROPERTY_243, b_iblock_element_prop_s42.PROPERTY_298, b_iblock_element_prop_s42.PROPERTY_299 FROM b_crm_deal INNER JOIN b_uts_crm_deal ON b_crm_deal.ID=b_uts_crm_deal.VALUE_ID INNER JOIN b_iblock_element ON b_uts_crm_deal.UF_CRM_1469534140 = b_iblock_element.ID INNER JOIN b_iblock_element_prop_s42 ON b_iblock_element.ID = b_iblock_element_prop_s42.IBLOCK_ELEMENT_ID WHERE (b_crm_deal.STAGE_ID = '10' or b_crm_deal.STAGE_ID = 'PROPOSAL' or b_crm_deal.STAGE_ID = '1' or b_crm_deal.STAGE_ID = '13' or b_crm_deal.STAGE_ID = 'C4:1' or b_crm_deal.STAGE_ID = 'C4:PROPOSAL')";
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
//Диапазон этажей
//от
$arResult['SELECT_PARAMS']['FLOOR_FROM'] = "любого";
if ($mainDeal['UF_CRM_1506501917']>1){
  $arResult["SQL_STRING"] .=" AND b_iblock_element_prop_s42.PROPERTY_221 >=".$mainDeal['UF_CRM_1506501917'];
  $arResult['SELECT_PARAMS']['FLOOR_FROM'] = $mainDeal['UF_CRM_1506501917'];
}
//до
$arResult['SELECT_PARAMS']['FLOOR_TO'] = "любой";
if ($mainDeal['UF_CRM_1506501950']>1){
  $arResult["SQL_STRING"] .=" AND b_iblock_element_prop_s42.PROPERTY_221 <=".$mainDeal['UF_CRM_1506501950'];
  $arResult['SELECT_PARAMS']['FLOOR_TO'] = $mainDeal['UF_CRM_1506501950'];
}
//Исключить последний
$arResult['SELECT_PARAMS']['LAST'] = "";
if ($mainDeal['UF_CRM_1502433005']){
  $arResult["SQL_STRING"] .=" AND b_iblock_element_prop_s42.PROPERTY_221 < b_iblock_element_prop_s42.PROPERTY_222";
  $arResult['SELECT_PARAMS']['LAST'] = "[не последний]";
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
//Фильтр по типу балкона
$arResult['SELECT_PARAMS']['TYPEBALKON'] = "нет данных";
if ($mainDeal['UF_CRM_58958B532A119']) {
  $arResult["SQL_STRING"] .= " AND b_iblock_element_prop_s42.PROPERTY_241 <> '' AND b_iblock_element_prop_s42.PROPERTY_241 <> 410";
  $arResult['SELECT_PARAMS']['TYPEBALKON'] = "Да";
}
//Фильтр по типу дома
$arResult['SELECT_PARAMS']['TYPEHOUSE'] = "нет данных";
if ($mainDeal['UF_CRM_1505805281']) {
  $temp = explode("|", $housetype[$mainDeal['UF_CRM_1505805281']]);
  $arResult["SQL_STRING"] .= " AND b_iblock_element_prop_s42.PROPERTY_243=".$temp[0];
  $arResult['SELECT_PARAMS']['TYPEHOUSE'] = $temp[1];
}
//Фильтр по материалам стен
$arResult['SELECT_PARAMS']['TYPEWALLS'] = "нет данных";
if ($mainDeal['UF_CRM_1505805394']) {
  $temp = explode("|", $wallstype[$mainDeal['UF_CRM_1505805394']]);
  $arResult["SQL_STRING"] .= " AND b_iblock_element_prop_s42.PROPERTY_242=".$temp[0];
  $arResult['SELECT_PARAMS']['TYPEWALLS'] = $temp[1];
}
//Фильтр по населенному пункту
$arResult['SELECT_PARAMS']['CITY'] = "нет данных";
if ($mainDeal['UF_CRM_1505802775']) {
  $arResult["SQL_STRING"] .= " AND LOWER(b_iblock_element_prop_s42.PROPERTY_215) LIKE '%".trim(strtolower($mainDeal['UF_CRM_1505802775']))."%'";
  $arResult['SELECT_PARAMS']['CITY'] = $mainDeal['UF_CRM_1505802775'];
}
//Фильтр по району
$arResult['SELECT_PARAMS']['LOCALITY'] = "нет данных";
if ($mainDeal['UF_CRM_1505802786']) {
  $arResult["SQL_STRING"] .= " AND LOWER(b_iblock_element_prop_s42.PROPERTY_216) LIKE '%".trim(strtolower($mainDeal['UF_CRM_1505802786']))."%'";
  $arResult['SELECT_PARAMS']['LOCALITY'] = $mainDeal['UF_CRM_1505802786'];
}
//Фильтр по улицам
$arResult['SELECT_PARAMS']['STREETS'] = "нет данных";
if ($mainDeal['UF_CRM_1505965059']) {
  $arResult['SELECT_PARAMS']['STREETS'] = $mainDeal['UF_CRM_1505965059'];
  $arResult["SQL_STRING"] .= " AND (";
  foreach (explode(",",$mainDeal['UF_CRM_1505965059']) as $key=>$street){
    if ($key) $arResult["SQL_STRING"] .=" OR LOWER(b_iblock_element_prop_s42.PROPERTY_217) LIKE '%".trim(strtolower($street))."%'";
    else $arResult["SQL_STRING"] .="LOWER(b_iblock_element_prop_s42.PROPERTY_217) LIKE '%".trim(strtolower($street))."%'";
  }
  $arResult["SQL_STRING"] .= ")";
}
//Область поиска
$rsData = $DB->Query("select polygonArray from b_crm_search_polygon where dealId=".$arParams['ID']);
if ($aData = $rsData->Fetch()) $arResult['SELECT_PARAMS']['SEARCHGEO'] = $aData['polygonArray'];
else $arResult['SELECT_PARAMS']['SEARCHGEO'] = "";

$this->IncludeComponentTemplate();
?>