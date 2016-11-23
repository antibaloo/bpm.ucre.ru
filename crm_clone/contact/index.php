<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/intranet/public/crm/contact/index.php");
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
	"bitrix:crm.contact", 
	".default", 
	array(
		"SEF_MODE" => "Y",
		"PATH_TO_LEAD_SHOW" => "/crm/lead/show/#lead_id#/",
		"PATH_TO_LEAD_EDIT" => "/crm/lead/edit/#lead_id#/",
		"PATH_TO_LEAD_CONVERT" => "/crm/lead/convert/#lead_id#/",
		"PATH_TO_COMPANY_SHOW" => "/crm/company/show/#company_id#/",
		"PATH_TO_COMPANY_EDIT" => "/crm/company/edit/#company_id#/",
		"PATH_TO_DEAL_SHOW" => "/crm/deal/show/#deal_id#/",
		"PATH_TO_DEAL_EDIT" => "/crm/deal/edit/#deal_id#/",
		"PATH_TO_INVOICE_SHOW" => "/crm/invoice/show/#invoice_id#/",
		"PATH_TO_INVOICE_EDIT" => "/crm/invoice/edit/#invoice_id#/",
		"PATH_TO_USER_PROFILE" => "/company/personal/user/#user_id#/",
		"ELEMENT_ID" => $_REQUEST["contact_id"],
		"SEF_FOLDER" => "/crm/contact/",
		"COMPONENT_TEMPLATE" => ".default",
		"NAME_TEMPLATE" => "#LAST_NAME# #NAME# #SECOND_NAME#",
		"SEF_URL_TEMPLATES" => array(
			"index" => "index.php",
			"list" => "list/",
			"edit" => "edit/#contact_id#/",
			"show" => "show/#contact_id#/",
			"service" => "service/",
			"import" => "import/",
		)
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>