<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/intranet/public/crm/company/index.php");
$APPLICATION->SetTitle(GetMessage("CRM_TITLE"));
$APPLICATION->IncludeComponent("shum:crm.maskphone", "",array(
	"PARAMETERS" => array(
		"MASK" => array(
			"NAME" => GetMessage("CP_PMASK_MASK"),
			"TYPE" => "STRING",
			"DEFAULT" => '+7(999)999-99-99',
		),
		"IN_JQ" => array(
			"NAME" => GetMessage("CP_PMASK_JQ"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),
	),
));
?><?$APPLICATION->IncludeComponent(
	"bitrix:crm.company",
	"",
	Array(
		"SEF_MODE" => "Y",	// Включить поддержку ЧПУ
		"PATH_TO_LEAD_SHOW" => "/crm/lead/show/#lead_id#/",
		"PATH_TO_LEAD_EDIT" => "/crm/lead/edit/#lead_id#/",
		"PATH_TO_LEAD_CONVERT" => "/crm/lead/convert/#lead_id#/",		
		"PATH_TO_CONTACT_SHOW" => "/crm/contact/show/#contact_id#/",
		"PATH_TO_CONTACT_EDIT" => "/crm/contact/edit/#contact_id#/",
		"PATH_TO_DEAL_SHOW" => "/crm/deal/show/#deal_id#/",
		"PATH_TO_DEAL_EDIT" => "/crm/deal/edit/#deal_id#/",
		"PATH_TO_INVOICE_SHOW" => "/crm/invoice/show/#invoice_id#/",
		"PATH_TO_INVOICE_EDIT" => "/crm/invoice/edit/#invoice_id#/",
		"PATH_TO_USER_PROFILE" => "/company/personal/user/#user_id#/",
		"ELEMENT_ID" => $_REQUEST["company_id"],
		"SEF_FOLDER" => "/crm/company/",
		"SEF_URL_TEMPLATES" => Array(
			"index" => "index.php",
			"list" => "list/",
			"import" => "import/",
			"edit" => "edit/#company_id#/",
			"show" => "show/#company_id#/",
			"dedupe" => "dedupe/"
		),
		"VARIABLE_ALIASES" => Array(
			"index" => Array(),
			"list" => Array(),
			"import" => Array(),
			"edit" => Array(),
			"show" => Array(),
			"dedupe" => Array()
		)
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>