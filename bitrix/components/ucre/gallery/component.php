<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
$arResult['COMPONENT_PATH'] = $this->GetPath();
$arResult['DEAL_ID'] = $arParams['ENTITY']['ID'];
$arResult['FIELDS'] = $arParams['FIELDS'];
foreach ($arResult['FIELDS'] as $field){
  foreach ( $arParams['ENTITY'][$field]['VALUE'] as $fileId){
    $arResult[$field][$fileId] = CFile::GetPath($fileId);
  }
}
$this->IncludeComponentTemplate();
?>