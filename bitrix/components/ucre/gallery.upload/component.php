<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
$arResult['FIELDS'] = $arParams['FIELDS'];
foreach ($arResult['FIELDS'] as $field){
  $arResult[$field] = array();
  foreach ( $arParams['ENTITY'][$field]['VALUE'] as $fileId){
    $arResult[$field][] = $fileId;
  }
}
$this->IncludeComponentTemplate();
?>