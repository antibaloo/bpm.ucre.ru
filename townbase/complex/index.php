<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Жилые комплексы");
?>
<?$APPLICATION->IncludeComponent(
	"ucre:complex", 
	"", 
	array(
		"SEF_FOLDER" => "/townbase/complex/",// Каталог ЧПУ (относительно корня сайта)
		"SEF_URL_TEMPLATES" => array(
			"index" => "index.php",
			"list" => "list/",
			"edit" => "edit/#element_id#/",
      "show" => "show/#element_id#/"
		),
		"VARIABLE_ALIASES" => array(
			"index" => "",
			"list" => "",
			"edit" => "",
      "show" => "",
		)
	),
	false
);
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>