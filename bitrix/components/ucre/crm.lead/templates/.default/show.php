<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

/** @var CMain $APPLICATION */
$APPLICATION->SetAdditionalCSS('/bitrix/js/crm/css/crm.css');
$APPLICATION->IncludeComponent(
	'bitrix:crm.control_panel',
	'',
	array(
		'ID' => 'LEAD_SHOW',
		'ACTIVE_ITEM_ID' => 'LEAD_UCRE',
	),
	$component
);

if(!Bitrix\Crm\Integration\Bitrix24Manager::isAccessEnabled(CCrmOwnerType::Lead))
{
	$APPLICATION->IncludeComponent('bitrix:bitrix24.business.tools.info', '', array());
}
else
{
	?><div class="bx-crm-view"><?
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
			'TYPE' => 'show'
		),
		$component
	);
	?><div class="bx-crm-view-form"><?
	$APPLICATION->IncludeComponent(
		'bitrix:crm.lead.show',
		'',
		array(
			'PATH_TO_LEAD_SHOW' => $arResult['PATH_TO_LEAD_SHOW'],
			'PATH_TO_LEAD_LIST' => $arResult['PATH_TO_LEAD_LIST'],
			'PATH_TO_LEAD_CONVERT' => $arResult['PATH_TO_LEAD_CONVERT'],
			'PATH_TO_CONTACT_EDIT' => $arResult['PATH_TO_CONTACT_EDIT'],
			'PATH_TO_CONTACT_SHOW' => $arResult['PATH_TO_CONTACT_SHOW'],
			'PATH_TO_COMPANY_EDIT' => $arResult['PATH_TO_COMPANY_EDIT'],
			'PATH_TO_COMPANY_SHOW' => $arResult['PATH_TO_COMPANY_SHOW'],
			'PATH_TO_DEAL_EDIT' => $arResult['PATH_TO_DEAL_EDIT'],
			'PATH_TO_DEAL_SHOW' => $arResult['PATH_TO_DEAL_SHOW'],
			'PATH_TO_USER_PROFILE' => $arResult['PATH_TO_USER_PROFILE'],
			'PATH_TO_PRODUCT_EDIT' => $arResult['PATH_TO_PRODUCT_EDIT'],
			'PATH_TO_PRODUCT_SHOW' => $arResult['PATH_TO_PRODUCT_SHOW'],
			'ELEMENT_ID' => $arResult['VARIABLES']['lead_id'],
			'NAME_TEMPLATE' => $arParams['NAME_TEMPLATE']
		),
		$component
	);
	?></div><?
	?></div><?
}
?>

