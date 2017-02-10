<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();//Запрет вызова из адресной строки браузера

$arSelect = Array("ID", "IBLOCK_ID", "CODE","ACTIVE", "NAME","CREATED_BY","MODIFIED_BY","DATE_CREATE","TIMESTAMP_X", "DATE_ACTIVE_FROM","DETAIL_TEXT","PROPERTY_*");
$db_res = CIBlockElement::GetList(Array(), array("IBLOCK_ID" => 42, "ID" => $arParams['ID']), false, Array(), $arSelect);
$aRes = $db_res->GetNext();
$arResult['FORM_ID'] = 'ro_form';
$arResult['DATA'] = $aRes;
$arResult['USERS'] = $arParams['USERS'];

switch ($arResult['DATA']['PROPERTY_210']) {
  case '381':
    $componentPage = 'room';
    break;
  case '382':
    $componentPage = 'flat';
    break;
  case '383':
    $componentPage = 'house';
    break;
  case '384':
    $componentPage = 'townhouse';
    break;
  case '385':
    $componentPage = 'dacha';
    break;
  case '386':
    $componentPage = 'plot';
    break;
   case '387':
    $componentPage = 'comm';
    break;
}
$this->IncludeComponentTemplate($componentPage);
?>