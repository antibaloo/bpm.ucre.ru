<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

//show the first crm type popup (with or without leads)
$CrmPerms = CCrmPerms::GetCurrentUserPermissions();
if (
	$CrmPerms->HavePerm('CONFIG', BX_CRM_PERM_CONFIG, 'WRITE')
	&& \Bitrix\Main\Config\Option::get('crm', 'crm_lead_enabled_show', "") == "Y"
	|| !\Bitrix\Crm\Settings\LeadSettings::isEnabled()
)
{
	CCrmComponentHelper::RegisterScriptLink('/bitrix/js/crm/common.js');
	?><script><?=\Bitrix\Crm\Settings\LeadSettings::showCrmTypePopup();?></script><?
}

/** @var CMain $APPLICATION */
$APPLICATION->IncludeComponent(
	'bitrix:crm.control_panel',
	'',
	array(
		'ID' => 'LEAD_LIST',
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
	$isBitrix24Template = SITE_TEMPLATE_ID === 'bitrix24';
	if($isBitrix24Template)
	{
		$this->SetViewTarget('below_pagetitle', 0);
	}

	?><div class="pagetitle-container" style="overflow: hidden;">Тут какбэ панелька со счетчиками<?
	/*$APPLICATION->IncludeComponent(
		'bitrix:crm.entity.counter.panel',
		'',
		array(
			'ENTITY_TYPE_NAME' => CCrmOwnerType::LeadName,
			'EXTRAS' => array(),
			'PATH_TO_ENTITY_LIST' => $arResult['PATH_TO_LEAD_LIST']
		)
	);*/
	?></div><?

	if($isBitrix24Template)
	{
		$this->EndViewTarget();
	}

	$APPLICATION->ShowViewContent('crm-grid-filter');
	?>Тут какбэ меню лидов<?
	/*$APPLICATION->IncludeComponent(
		'bitrix:crm.lead.menu',
		'',
		array(
			'PATH_TO_LEAD_LIST' => $arResult['PATH_TO_LEAD_LIST'],
			'PATH_TO_LEAD_SHOW' => $arResult['PATH_TO_LEAD_SHOW'],
			'PATH_TO_LEAD_EDIT' => $arResult['PATH_TO_LEAD_EDIT'],
			'PATH_TO_LEAD_CONVERT' => $arResult['PATH_TO_LEAD_CONVERT'],
			'PATH_TO_LEAD_IMPORT' => $arResult['PATH_TO_LEAD_IMPORT'],
			'PATH_TO_LEAD_DEDUPE' => $arResult['PATH_TO_LEAD_DEDUPE'],
			'ELEMENT_ID' => $arResult['VARIABLES']['lead_id'],
			'TYPE' => 'list'
		),
		$component
	);*/
	$APPLICATION->IncludeComponent(
		'bitrix:crm.lead.list',
		'',
		array(
			'LEAD_COUNT' => '20',
			'PATH_TO_LEAD_SHOW' => $arResult['PATH_TO_LEAD_SHOW'],
			'PATH_TO_LEAD_EDIT' => $arResult['PATH_TO_LEAD_EDIT'],
			'PATH_TO_LEAD_CONVERT' => $arResult['PATH_TO_LEAD_CONVERT'],
			'PATH_TO_LEAD_WIDGET' => $arResult['PATH_TO_LEAD_WIDGET'],
			'PATH_TO_LEAD_KANBAN' => $arResult['PATH_TO_LEAD_KANBAN'],
			'NAME_TEMPLATE' => $arParams['NAME_TEMPLATE'],
			'NAVIGATION_CONTEXT_ID' => $arResult['NAVIGATION_CONTEXT_ID']
		),
		$component
	);
}
?>