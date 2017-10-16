<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
global $APPLICATION;
$APPLICATION->IncludeComponent(
	'bitrix:crm.control_panel',
	'',
	array(
		'ID' => 'LEAD_CONVERT',
		'ACTIVE_ITEM_ID' => 'LEAD_UCRE',
	),
	$component
);
$APPLICATION->IncludeComponent(
	'bitrix:crm.lead.menu', 
	'', 
	array(
		'PATH_TO_LEAD_LIST' => $arResult['PATH_TO_LEAD_LIST'],
		'PATH_TO_LEAD_SHOW' => $arResult['PATH_TO_LEAD_SHOW'],
		'PATH_TO_LEAD_EDIT' => $arResult['PATH_TO_LEAD_EDIT'],
		'PATH_TO_LEAD_CONVERT' => $arResult['PATH_TO_LEAD_CONVERT'],
		'PATH_TO_LEAD_IMPORT' => $arResult['PATH_TO_LEAD_IMPORT'],
		'ELEMENT_ID' => $arResult['VARIABLES']['lead_id'],    
		'TYPE' => 'convert'
	),
	$component
);?>

<?
$APPLICATION->IncludeComponent(
	'bitrix:crm.lead.convert', 
	'', 
	array(
		'PATH_TO_LEAD_LIST' => $arResult['PATH_TO_LEAD_LIST'],
		'PATH_TO_LEAD_CONVERT' => $arResult['PATH_TO_LEAD_CONVERT'],
		'ELEMENT_ID' => $arResult['VARIABLES']['lead_id'],
		'NAME_TEMPLATE' => $arParams['NAME_TEMPLATE'],
		'PATH_TO_PRODUCT_EDIT' => $arResult['PATH_TO_PRODUCT_EDIT'],
		'PATH_TO_PRODUCT_SHOW' => $arResult['PATH_TO_PRODUCT_SHOW'],
	),
	$component
);?>