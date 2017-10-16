<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();

if (!CModule::IncludeModule('crm'))
{
	ShowError(GetMessage('CRM_MODULE_NOT_INSTALLED'));
	return;
}

if(!CAllCrmInvoice::installExternalEntities())
	return;
if(!CCrmQuote::LocalComponentCausedUpdater())
	return;

if (!CModule::IncludeModule('currency'))
{
	ShowError(GetMessage('CRM_MODULE_NOT_INSTALLED_CURRENCY'));
	return;
}
if (!CModule::IncludeModule('catalog'))
{
	ShowError(GetMessage('CRM_MODULE_NOT_INSTALLED_CATALOG'));
	return;
}
if (!CModule::IncludeModule('sale'))
{
	ShowError(GetMessage('CRM_MODULE_NOT_INSTALLED_SALE'));
	return;
}

$arDefaultUrlTemplates404 = array(
	'index' => 'index.php',
	'list' => 'list/',
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

$arParams['NAME_TEMPLATE'] = empty($arParams['NAME_TEMPLATE']) ? CSite::GetNameFormat(false) : str_replace(array("#NOBR#","#/NOBR#"), array("",""), $arParams["NAME_TEMPLATE"]);
//Поддержка ЧПУ включена всегда
$arVariables = array();
$arUrlTemplates = CComponentEngine::MakeComponentUrlTemplates($arDefaultUrlTemplates404, $arParams['SEF_URL_TEMPLATES']);
$arVariableAliases = CComponentEngine::MakeComponentVariableAliases($arDefaultVariableAliases404, $arParams['VARIABLE_ALIASES']);
$componentPage = CComponentEngine::ParseComponentPath($arParams['SEF_FOLDER'], $arUrlTemplates, $arVariables);

if (empty($componentPage) || (!array_key_exists($componentPage, $arDefaultUrlTemplates404)))	$componentPage = 'index';
CComponentEngine::InitComponentVariables($componentPage, $arComponentVariables, $arVariableAliases, $arVariables);
/*
foreach ($arUrlTemplates as $url => $value){
	if(strlen($arParams['PATH_TO_LEAD_'.strToUpper($url)]) <= 0) $arResult['PATH_TO_LEAD_'.strToUpper($url)] = $arParams['SEF_FOLDER'].$value;
	else $arResult['PATH_TO_LEAD_'.strToUpper($url)] = $arParams['PATH_TO_'.strToUpper($url)];
}
*/
$arResult = array_merge(
	array(
		'VARIABLES' => $arVariables,
		'ALIASES' => $arVariableAliases,
		'ELEMENT_ID' => $arParams['ELEMENT_ID'],
	),
	$arResult
);

$arResult['NAVIGATION_CONTEXT_ID'] = 'LEAD';
if($componentPage === 'index')
{
	$componentPage = 'list';
}
echo "<pre>";
print_r($arResult);
echo "</pre>";
$this->IncludeComponentTemplate($componentPage);
?>