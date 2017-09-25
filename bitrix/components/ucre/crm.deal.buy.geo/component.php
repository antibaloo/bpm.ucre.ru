<?
CModule::IncludeModule('crm');
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();//Запрет вызова из адресной строки браузера
$rsDeal = CCrmDeal::GetListEx(
	array(),
	array("ID" => $arParams['DEAL_ID']),
	false,
	false,
	array("CATEGORY_ID"),
	array()
);
$mainDeal = $rsDeal->Fetch();
if ($mainDeal["CATEGORY_ID"] == 2){
  $this->IncludeComponentTemplate();
}
?>