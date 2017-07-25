<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
CModule::IncludeModule("crm");
$arResult['ID'] = $arParams['ID'];
$arResult['COMPONENT_PATH'] = $this->GetPath();
$this->IncludeComponentTemplate();
?>