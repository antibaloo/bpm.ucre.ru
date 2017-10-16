<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
global $APPLICATION;
$APPLICATION->IncludeComponent(
	'bitrix:crm.control_panel',
	'',
	array(
		'ID' => 'LEAD_LIST',
		'ACTIVE_ITEM_ID' => 'LEAD_UCRE',
	),
	$component
);

$APPLICATION->IncludeComponent(
	'bitrix:crm.dedupe.list',
	'',
	array('ENTITY_TYPE' => 'LEAD'),
	$component
);
?>