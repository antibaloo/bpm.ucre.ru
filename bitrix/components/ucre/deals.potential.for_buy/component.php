<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
CModule::IncludeModule("crm");
$arResult['ID'] = $arParams['ID'];
$arResult['COMPONENT_PATH'] = $this->GetPath();
$rsDeal = CCrmDeal::GetListEx(
  array(), 
  array("ID" => $arParams['ID']), 
  false, 
  false, 
  array("ASSIGNED_BY_ID"),
  array()
);
$mainDeal = $rsDeal->Fetch();
$arResult['ASSIGNED_BY_ID'] = $mainDeal['ASSIGNED_BY_ID'];
$this->IncludeComponentTemplate();
?>