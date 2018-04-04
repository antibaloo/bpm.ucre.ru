<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
$arResult['COMPONENT_PATH'] = $this->GetPath();
$arResult['CONTACT_ID'] = $arParams['ENTITY']['ID'];
$arResult['FIELDS'] = $arParams['FIELDS'];
foreach ($arResult['FIELDS'] as $field){
  $arResult[$field] = array();
  foreach ( $arParams['ENTITY'][$field]['VALUE'] as $key=>$fileId){
    $arResult[$field][$field."[".($key+1)."]"] = $fileId; //+1 необходим, что бы при добавления файла в поле он не перезатирал предыдущий
  }
}
$this->IncludeComponentTemplate();
?>