<?php if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

//show the crm type popup (with or without leads)
if (!\Bitrix\Crm\Settings\LeadSettings::isEnabled())
{
	CCrmComponentHelper::RegisterScriptLink('/bitrix/js/crm/common.js');
	?><script><?=\Bitrix\Crm\Settings\LeadSettings::showCrmTypePopup();?></script><?
}

// js/css
$APPLICATION->SetAdditionalCSS('/bitrix/themes/.default/bitrix24/crm-entity-show.css');
$bodyClass = $APPLICATION->GetPageProperty('BodyClass');
$APPLICATION->SetPageProperty('BodyClass', ($bodyClass ? $bodyClass.' ' : '').'no-paddings grid-mode pagetitle-toolbar-field-view flexible-layout crm-toolbar');
$asset = Bitrix\Main\Page\Asset::getInstance();
$asset->addJs('/bitrix/js/crm/common.js');

// some common langs
use Bitrix\Main\Localization\Loc;
Loc::loadMessages($_SERVER['DOCUMENT_ROOT'].'/bitrix/components/bitrix/crm.lead.menu/component.php');
Loc::loadMessages($_SERVER['DOCUMENT_ROOT'].'/bitrix/components/bitrix/crm.lead.list/templates/.default/template.php');

// if not isset
$arResult['PATH_TO_LEAD_EDIT'] = isset($arResult['PATH_TO_LEAD_EDIT']) ? $arResult['PATH_TO_LEAD_EDIT'] : '';
$arResult['PATH_TO_LEAD_LIST'] = isset($arResult['PATH_TO_LEAD_LIST']) ? $arResult['PATH_TO_LEAD_LIST'] : '';
$arResult['PATH_TO_LEAD_WIDGET'] = isset($arResult['PATH_TO_LEAD_WIDGET']) ? $arResult['PATH_TO_LEAD_WIDGET'] : '';
$arResult['PATH_TO_LEAD_KANBAN'] = isset($arResult['PATH_TO_LEAD_KANBAN']) ? $arResult['PATH_TO_LEAD_KANBAN'] : '';
$arResult['PATH_TO_LEAD_DEDUPE'] = isset($arResult['PATH_TO_LEAD_DEDUPE']) ? $arResult['PATH_TO_LEAD_DEDUPE'] : '';
$arResult['PATH_TO_LEAD_IMPORT'] = isset($arResult['PATH_TO_LEAD_IMPORT']) ? $arResult['PATH_TO_LEAD_IMPORT'] : '';

// csv and excel delegate to list
$context = \Bitrix\Main\Application::getInstance()->getContext();
$request = $context->getRequest();
if (in_array($request->get('type'), array('csv', 'excel')))
{
	LocalRedirect(str_replace(
				$arResult['PATH_TO_LEAD_KANBAN'],
				$arResult['PATH_TO_LEAD_LIST'],
				$APPLICATION->getCurPageParam()
			), true);
}

// main menu
$APPLICATION->IncludeComponent(
	'bitrix:crm.control_panel',
	'',
	array(
		'ID' => 'LEAD_LIST',
		'ACTIVE_ITEM_ID' => 'LEAD_UCRE',
	),
	$component
);

// check rights
if (!\CCrmPerms::IsAccessEnabled())
{
	return false;
}

// check accessable
if (!Bitrix\Crm\Integration\Bitrix24Manager::isAccessEnabled(CCrmOwnerType::Lead))
{
	$APPLICATION->IncludeComponent('bitrix:bitrix24.business.tools.info', '', array());
}
else
{
	$entityType = \CCrmOwnerType::LeadName;

	// counters
	$this->SetViewTarget('below_pagetitle', 0);
	?><div class="pagetitle-container"><?
	$APPLICATION->IncludeComponent(
		'bitrix:crm.entity.counter.panel',
		'',
		array(
			'ENTITY_TYPE_NAME' => $entityType,
			'EXTRAS' => array(),
			'PATH_TO_ENTITY_LIST' => $arResult['PATH_TO_LEAD_KANBAN']
		)
	);
	?></div><?
	$this->EndViewTarget();


	// menu
	$APPLICATION->IncludeComponent(
		'bitrix:crm.lead.menu',
		'',
		array(
			'PATH_TO_LEAD_LIST' => $arResult['PATH_TO_LEAD_LIST'],
			'PATH_TO_LEAD_EDIT' => $arResult['PATH_TO_LEAD_EDIT'],
			'PATH_TO_LEAD_DEDUPE' => $arResult['PATH_TO_LEAD_DEDUPE'],
			'PATH_TO_LEAD_IMPORT' => $arResult['PATH_TO_LEAD_IMPORT'],
			'ELEMENT_ID' => 0,
			'TYPE' => 'list',
			'DISABLE_EXPORT' => 'Y'
		),
		$component
	);

	// filter
	$APPLICATION->IncludeComponent(
		'bitrix:crm.kanban.filter',
		'',
		array(
			'ENTITY_TYPE' => $entityType,
			'NAVIGATION_BAR' => array(
				'ITEMS' => array(
					array(
						//'icon' => 'table',
						'id' => 'list',
						'name' => Loc::getMessage('CRM_LEAD_LIST_FILTER_NAV_BUTTON_LIST'),
						'active' => 0,
						'url' => $arResult['PATH_TO_LEAD_LIST']
					),
					array(
						//'icon' => 'kanban',
						'id' => 'kanban',
						'name' => Loc::getMessage('CRM_LEAD_LIST_FILTER_NAV_BUTTON_KANBAN'),
						'active' => 1,
						'url' => $arResult['PATH_TO_LEAD_KANBAN']
					),
					array(
						//'icon' => 'chart',
						'id' => 'widget',
						'name' => Loc::getMessage('CRM_LEAD_LIST_FILTER_NAV_BUTTON_WIDGET'),
						'active' => 0,
						'url' => $arResult['PATH_TO_LEAD_WIDGET']
					)
				),
				'BINDING' => array(
					'category' => 'crm.navigation',
					'name' => 'index',
					'key' => strtolower($arResult['NAVIGATION_CONTEXT_ID'])
				)
			)
		),
		$component,
		array('HIDE_ICONS' => true)
	);

	/*
	$supervisorInv = \Bitrix\Crm\Kanban\SupervisorTable::isSupervisor($entityType) ? 'N' : 'Y';
	CCrmUrlUtil::AddUrlParams(
							CComponentEngine::MakePathFromTemplate(
								$arResult['PATH_TO_LEAD_KANBAN']
							),
							array('supervisor' => $supervisorInv, 'clear_filter' => 'Y')
						)
	 */

	$APPLICATION->IncludeComponent(
		'bitrix:crm.kanban',
		'',
		array(
			'ENTITY_TYPE' => $entityType,
			'SHOW_ACTIVITY' => 'Y'
		),
		$component
	);
}