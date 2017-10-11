<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Сделки");
?><?$APPLICATION->IncludeComponent(
	"ucre:crm.client.deal",
	"",
	array(
		"SEF_MODE" => "Y", // Включить поддержку ЧПУ
		"PATH_TO_CONTACT_SHOW" => "/crm/contact/show/#contact_id#/",
		"PATH_TO_CONTACT_EDIT" => "/crm/contact/edit/#contact_id#/",
		"PATH_TO_COMPANY_SHOW" => "/crm/company/show/#company_id#/",
		"PATH_TO_COMPANY_EDIT" => "/crm/company/edit/#company_id#/",
		"PATH_TO_DEAL_SHOW" => "/crm/deal/show/#deal_id#/",
		"PATH_TO_DEAL_EDIT" => "/crm/deal/edit/#deal_id#/",
		"PATH_TO_USER_PROFILE" => "/company/personal/user/#user_id#/",
		"PATH_TO_PRODUCT_EDIT" => "/crm/product/edit/#product_id#/",
		"PATH_TO_PRODUCT_SHOW" => "/crm/product/show/#product_id#/",
		"ELEMENT_ID" => $_REQUEST["cdeal_id"],	// ID лида
		"SEF_FOLDER" => "/crm/client_deal/",	// Каталог ЧПУ (относительно корня сайта)
		"SEF_URL_TEMPLATES" => array(
			"index" => "index.php",
			"list" => "list/",
			"edit" => "edit/#cdeal_id#/",
			"show" => "show/#cdeal_id#/",
			"import" => "import/",
			"service" => "service/",
			"dedupe" => "dedupe/",
		),
		"VARIABLE_ALIASES" => array(
			"index" => "",
			"list" => "",
			"edit" => "",
			"show" => "",
			"service" => "",
			"dedupe" => "",
		)
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>