<?
CModule::IncludeModule('crm');
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();//Запрет вызова из адресной строки браузера
$arResult['COMPONENT_PATH'] = $this->GetPath();
$rsData = $DB->Query("select polygonArray from b_crm_search_polygon where dealId=".$arParams['DEAL_ID']);
if ($aData = $rsData->Fetch()) $arResult['POLYGON'] = $aData['polygonArray'];
else $arResult['POLYGON'] = "";
$arResult['DEAL_ID'] = $arParams['DEAL_ID'];
$this->IncludeComponentTemplate();
?>