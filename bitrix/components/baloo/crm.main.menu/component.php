<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
$arResult['position'] = $arParams['position'];
$arResult['portalLink'] = "https://bpm.ucre.ru";
$arResult['crmLink'] = $arResult['portalLink'] .'/crm_ucre';
$arResult['streamLink'] = $arResult['crmLink'];
$arResult['leadsLink'] = $arResult['crmLink']."/leads";
$arResult['clientsLink'] = $arResult['crmLink']."/clients";
$arResult['requestsLink'] = $arResult['crmLink']."/requests";
$arResult['mortgageLink'] = $arResult['crmLink']."/mortgage";
$arResult['marketingLink'] = $arResult['crmLink']."/marketing";
$arResult['contractsLink'] = $arResult['crmLink']."/contracts";
$arResult['dealsLink'] = $arResult['crmLink']."/deals";
$arResult['realtyLink'] = $arResult['crmLink']."/realty";
$this->IncludeComponentTemplate();
?>