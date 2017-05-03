<?
CModule::IncludeModule('crm');
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();//Запрет вызова из адресной строки браузера
if ($arParams['CATEGORY'] == 2){
   $rsQuery = "SELECT * from b_crm_potential_deals WHERE BUY_DEAL_ID=".$arParams['ID'];
}elseif(in_array($arParams['CATEGORY'],array(0,4))){
   $rsQuery = "SELECT * from b_crm_potential_deals WHERE SELL_DEAL_ID=".$arParams['ID'];
}
$rsData = $DB->Query($rsQuery);
$arResult['ID'] = $arParams['ID'];
$arResult['CATEGORY'] = $arParams['CATEGORY'];
$arResult['TABID'] = $arParams['TABID'];
$arResult['FILTER'] = $arParams['FILTER'];
$arResult['DATA'] = array();

while ($aRes = $rsData->Fetch()){
  $arResult['DATA'][] = $aRes;
}
$this->IncludeComponentTemplate();
?>