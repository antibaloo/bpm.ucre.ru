<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
?>
<?$APPLICATION->IncludeComponent(
	"ucre:crm.lead", 
	"", 
	array(
		"SEF_MODE" => "Y", // Включить поддержку ЧПУ
		"ELEMENT_ID" => $_REQUEST["lead_id"],// ID лида
		"SEF_FOLDER" => "/crm/lead_ucre/",// Каталог ЧПУ (относительно корня сайта), ЧПУ включено по-умолчанию
		"SEF_URL_TEMPLATES" => array(
			"index" => "index.php",
			"list" => "list/",
			"edit" => "edit/#lead_id#/",
			"show" => "show/#lead_id#/",
			"convert" => "convert/#lead_id#/",
			"dedupe" => "dedupe/",
		),
		"VARIABLE_ALIASES" => array(
			"index" => "",
			"list" => "",
			"edit" => "",
			"show" => "",
			"convert" => "",
			"dedupe" => "",
		)
	),
	false
);
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>