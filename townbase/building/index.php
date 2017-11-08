<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Здания: многоквартирные, частные, нежилые.");
?>
<?$APPLICATION->IncludeComponent(
	"ucre:building", 
	"", 
	array(
		"SEF_FOLDER" => "/townbase/building/",// Каталог ЧПУ (относительно корня сайта)
		"SEF_URL_TEMPLATES" => array(
			"index" => "index.php",
			"list" => "list/",
			"edit" => "edit/#element_id#/",
      "show" => "show/#element_id#/",
      "chess" => "chess/#element_id#/"
		),
		"VARIABLE_ALIASES" => array(
			"index" => "",
			"list" => "",
			"edit" => "",
      "show" => "",
      "chess" => ""
		)
	),
	false
);
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>