<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
$arDefaultUrlTemplates404 = array(
	'index' => 'index.php',
	'list' => 'list/',
	'edit' => 'edit/#element_id#/',
  'show' => 'show/#element_id#/',
	'chess' => 'chess/#action#/#element_id#/'
);
$arDefaultVariableAliases404 = array(

);
$arDefaultVariableAliases = array();
$componentPage = '';
$arComponentVariables = array('element_id','action');

$arVariables = array();
$arUrlTemplates = CComponentEngine::MakeComponentUrlTemplates($arDefaultUrlTemplates404, $arParams['SEF_URL_TEMPLATES']);
$arVariableAliases = CComponentEngine::MakeComponentVariableAliases($arDefaultVariableAliases404, $arParams['VARIABLE_ALIASES']);
$componentPage = CComponentEngine::ParseComponentPath($arParams['SEF_FOLDER'], $arUrlTemplates, $arVariables);

if (empty($componentPage) || (!array_key_exists($componentPage, $arDefaultUrlTemplates404)))	$componentPage = 'index';
CComponentEngine::InitComponentVariables($componentPage, $arComponentVariables, $arVariableAliases, $arVariables);
$arResult = array_merge(
	array(
		'VARIABLES' => $arVariables,
		'ALIASES' => $arVariableAliases,
	),
	$arResult
);

if($componentPage === 'index')
{
	$componentPage = 'list';
}
/*
echo "<pre>";
print_r($arResult);
echo "</pre>";*/

$this->IncludeComponentTemplate($componentPage);
?>