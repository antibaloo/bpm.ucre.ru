<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();

if (!CModule::IncludeModule('crm'))
{
	ShowError(GetMessage('CRM_MODULE_NOT_INSTALLED'));
	return;
}

$arDefaultUrlTemplates404 = array(
	'index' => 'index.php',
	'list' => 'list/',
	'service' => 'service/',
	'import' => 'import/',
	'widget' => 'widget/',
	'kanban' => 'kanban/',
	'edit' => 'edit/#lead_id#/',
	'show' => 'show/#lead_id#/',
	'convert' => 'convert/#lead_id#/',
	'dedupe' => 'dedupe/'
);

$arDefaultVariableAliases404 = array(

);
$arDefaultVariableAliases = array();
$componentPage = '';
$arComponentVariables = array('lead_id');



$arResult['NAVIGATION_CONTEXT_ID'] = 'LEAD';
$this->IncludeComponentTemplate($componentPage);
?>