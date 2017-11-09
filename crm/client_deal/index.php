<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Сделки");
?><?$APPLICATION->IncludeComponent(
	"ucre:crm.client.deal",
	"",
	array(
		"SEF_MODE" => "Y", // Включить поддержку ЧПУ
		"ELEMENT_ID" => $_REQUEST["cdeal_id"],	// ID лида
		"SEF_FOLDER" => "/crm/client_deal/",	// Каталог ЧПУ (относительно корня сайта)
		"SEF_URL_TEMPLATES" => array(
			"index" => "index.php",
			"list" => "list/",
			"edit" => "edit/#cdeal_id#/",
			"show" => "show/#cdeal_id#/"
		),
		"VARIABLE_ALIASES" => array(
			"index" => "",
			"list" => "",
			"edit" => "",
			"show" => ""
		)
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>