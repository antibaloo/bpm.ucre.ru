<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Список объектов недвижимости");
$APPLICATION->IncludeComponent(
	"baloo:crm.ro",
	".default", 
	array(
		"SEF_MODE" => "Y",
		"PATH_TO_LEAD_SHOW" => "/crm/lead/show/#lead_id#/",
		"PATH_TO_LEAD_EDIT" => "/crm/lead/edit/#lead_id#/",
		"PATH_TO_LEAD_CONVERT" => "/crm/lead/convert/#lead_id#/",
		"PATH_TO_BG_SHOW" => "/crm/bg/show/#bg_id#/",
		"PATH_TO_BG_EDIT" => "/crm/bg/edit/#bg_id#/",
		"PATH_TO_CONTACT_SHOW" => "/crm/contact/show/#contact_id#/",
		"PATH_TO_CONTACT_EDIT" => "/crm/contact/edit/#contact_id#/",
		"PATH_TO_COMPANY_SHOW" => "/crm/company/show/#company_id#/",
		"PATH_TO_COMPANY_EDIT" => "/crm/company/edit/#company_id#/",
		"PATH_TO_DEAL_SHOW" => "/crm/deal/show/#deal_id#/",
		"PATH_TO_DEAL_EDIT" => "/crm/deal/edit/#deal_id#/",
		"PATH_TO_INVOICE_SHOW" => "/crm/invoice/show/#invoice_id#/",
		"PATH_TO_INVOICE_EDIT" => "/crm/invoice/edit/#invoice_id#/",
		"PATH_TO_USER_PROFILE" => "/company/personal/user/#user_id#/",
		"ELEMENT_ID" => $_REQUEST["ro_id"],
		"SEF_FOLDER" => "/crm/ro/",
		"COMPONENT_TEMPLATE" => ".default",
		"NAME_TEMPLATE" => "#LAST_NAME# #NAME# #SECOND_NAME#",
		"SEF_URL_TEMPLATES" => array(
			"index" => "index.php",
			"list" => "list/",
			"edit" => "edit/#ro_id#/",
			"show" => "show/#ro_id#/",
		)
	),
	false
);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>