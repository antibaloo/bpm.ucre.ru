<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
$arResult['FIELDS'] = $arParams['FIELDS'];
foreach ($arResult['FIELDS'] as $field){$arResult[$field] = $arParams['ENTITY'][$field];}
$template = 'show';
$this->IncludeComponentTemplate($template);
?>