<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
CModule::IncludeModule("crm");
//Читаем параметры поиска
$rsDeal = $DB->Query("select b_crm_deal.CATEGORY_ID, b_crm_deal.ASSIGNED_BY_ID, b_uts_crm_deal.UF_CRM_58958B5734602, b_iblock_element_prop_s42.PROPERTY_210, b_iblock_element_prop_s42.PROPERTY_221, b_iblock_element_prop_s42.PROPERTY_222, b_iblock_element_prop_s42.PROPERTY_229, b_iblock_element_prop_s42.PROPERTY_224, b_iblock_element_prop_s42.PROPERTY_226, b_iblock_element_prop_s42.PROPERTY_238, b_iblock_element_prop_s42.PROPERTY_242, b_iblock_element_prop_s42.PROPERTY_243, b_iblock_element_prop_s42.PROPERTY_292 FROM b_crm_deal INNER JOIN b_uts_crm_deal ON b_crm_deal.ID=b_uts_crm_deal.VALUE_ID  INNER JOIN b_iblock_element ON b_uts_crm_deal.UF_CRM_1469534140 = b_iblock_element.ID INNER JOIN b_iblock_element_prop_s42 ON b_iblock_element.ID = b_iblock_element_prop_s42.IBLOCK_ELEMENT_ID WHERE b_crm_deal.ID=".$arParams['ID']);
$mainDeal=$rsDeal->Fetch();
$arResult['ID'] = $arParams['ID'];
$arResult['COMPONENT_PATH'] = $this->GetPath(); //Пусть к папке компонента для вызова ajax скриптов
$arResult['ASSIGNED_BY_ID'] = $mainDeal['ASSIGNED_BY_ID'];

//Базовая SQL строка
$arResult["SQL_STRING"] = "SELECT b_crm_deal.ID FROM b_crm_deal.ID INNER JOIN b_uts_crm_deal ON b_crm_deal.ID = b_uts_crm_deal.VALUE_ID WHERE b_crm_deal.STAGE_ID = 'C2:PROPOSAL'";

//Фильтр по рынку поиска (Вторичка и /или Новостройки)
$arResult['SELECT_PARAMS']['MARKET'] = "нет данных";
if ($mainDeal['CATEGORY_ID'] == 0) {
  $arResult['SELECT_PARAMS']['MARKET'] = "Вторичка";
  $arResult["SQL_STRING"] .= " AND (b_uts_crm_deal.UF_CRM_5895BC940ED3F = 'a:1:{i:0;i:827;}' OR b_uts_crm_deal.UF_CRM_5895BC940ED3F = 'a:2:{i:0;i:827;i:1;i:828;}')";
}
elseif ($mainDeal['CATEGORY_ID'] == 4) {
  $arResult['SELECT_PARAMS']['MARKET'] = "Новостройка";
  $arResult["SQL_STRING"] .= " AND (b_uts_crm_deal.UF_CRM_5895BC940ED3F = 'a:1:{i:0;i:828;}' OR b_uts_crm_deal.UF_CRM_5895BC940ED3F = 'a:2:{i:0;i:827;i:1;i:828;}')";
}
//Фильтр по типу обьъекта (комната, квартира и т.д.)
$arResult['SELECT_PARAMS']['TYPE'] = "нет данных";
switch ($mainDeal['PROPERTY_210']){
  case 381:
    $arResult['SELECT_PARAMS']['TYPE'] = "Комната";
    $arResult["SQL_STRING"] .= " AND b_uts_crm_deal.UF_CRM_58CFC7CDAAB96 = 1";
    break;
  case 382:
    $arResult['SELECT_PARAMS']['TYPE'] = "Квартира";
    $arResult["SQL_STRING"] .= " AND b_uts_crm_deal.UF_CRM_58CFC7CDAAB96 = 2";
    break;
  case 383:
    $arResult['SELECT_PARAMS']['TYPE'] = "Дом";
    $arResult["SQL_STRING"] .= " AND b_uts_crm_deal.UF_CRM_58CFC7CDAAB96 = 3";
    break;
  case 384:
    $arResult['SELECT_PARAMS']['TYPE'] = "Таунхаус";
    $arResult["SQL_STRING"] .= " AND b_uts_crm_deal.UF_CRM_58CFC7CDAAB96 = 4";
    break;
  case 385:
    $arResult['SELECT_PARAMS']['TYPE'] = "Дача";
    $arResult["SQL_STRING"] .= " AND b_uts_crm_deal.UF_CRM_58CFC7CDAAB96 = 5";
    break;
  case 386:
    $arResult['SELECT_PARAMS']['TYPE'] = "Участок";
    $arResult["SQL_STRING"] .= " AND b_uts_crm_deal.UF_CRM_58CFC7CDAAB96 = 6";
    break;
  case 387:
    $arResult['SELECT_PARAMS']['TYPE'] = "Коммерческая";
    $arResult["SQL_STRING"] .= " AND b_uts_crm_deal.UF_CRM_58CFC7CDAAB96 = 7";
    break;
}
echo "<pre>";
print_r($mainDeal);
echo "</pre>";
$this->IncludeComponentTemplate();
?>