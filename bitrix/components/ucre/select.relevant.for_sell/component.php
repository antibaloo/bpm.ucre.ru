<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
CModule::IncludeModule("crm");
//Читаем параметры поиска
$rsDeal = CCrmDeal::GetListEx(
	array(),
	array("ID" => $arParams['ID']),
	false,
	false,
	array("CATEGORY_ID", "ASSIGNED_BY_ID", "UF_CRM_58958B5734602", "UF_CRM_1469534140"),
	array()
);
$mainDeal = $rsDeal->Fetch();

if ($mainDeal["UF_CRM_1469534140"]){
  $rsObject = CIBlockElement::GetById($mainDeal["UF_CRM_1469534140"]);
  $mainObject = $rsObject->GetNextElement();
  $objectFields = $mainObject->GetFields();
  $objectProperties = $mainObject->GetProperties();
  $arResult['ID'] = $arParams['ID'];
  $arResult['COMPONENT_PATH'] = $this->GetPath(); //Пусть к папке компонента для вызова ajax скриптов
  $arResult['ASSIGNED_BY_ID'] = $mainDeal['ASSIGNED_BY_ID'];
  $arResult['SELECT_PARAMS']['PRICE'] = $mainDeal['UF_CRM_58958B5734602'];
  $arResult['SELECT_PARAMS']['MARKET'] = ($mainDeal['CATEGORY_ID'])?"первичка":"вторичка";
  $arResult['SELECT_PARAMS']['TYPE'] = $objectProperties['TYPE']['VALUE'];
  $arResult['SELECT_PARAMS']['ROOMS'] = $objectProperties['ROOMS']['VALUE'];
  $arResult['SELECT_PARAMS']['TOTAL_AREA'] = $objectProperties['TOTAL_AREA']['VALUE'];
  $arResult['SELECT_PARAMS']['KITCHEN_AREA'] = $objectProperties['KITCHEN_AREA']['VALUE'];
  $arResult['SELECT_PARAMS']['ROOM_AREA'] = $objectProperties['ROOM_AREA']['VALUE'];
  $arResult['SELECT_PARAMS']['PLOT_AREA'] = $objectProperties['PLOT_AREA']['VALUE'];
  $arResult['SELECT_PARAMS']['FLOOR'] = $objectProperties['FLOOR']['VALUE'];
  $arResult['SELECT_PARAMS']['FLOORALL'] = $objectProperties['FLOORALL']['VALUE'];
}else $arResult['NO_OBJECT'] = true;
$this->IncludeComponentTemplate();
?>