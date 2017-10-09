<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
$arGroups = CUser::GetUserGroup($USER->GetID());
$arContact = CCrmContact::GetByID($arParams['CONTACT_ID']);
if(in_array(18,$arGroups)&&!$arContact['PHOTO']){//Пользователь состоит в группе Фотографирование
  $arResult['CONTACT_ID'] = $arParams['CONTACT_ID'];
  $arResult['COMPONENT_PATH'] = $this->GetPath();
  $this->IncludeComponentTemplate();
}
?>