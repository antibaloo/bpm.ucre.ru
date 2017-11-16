<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
?>
<?$APPLICATION->IncludeComponent(
	"ucre:chess", 
	"", 
	array(
		"SEF_FOLDER" => "/townbase/chess/",// Каталог ЧПУ (относительно корня сайта)
		"SEF_URL_TEMPLATES" => array(
			"index" => "index.php",
			"edit" => "edit/#element_id#/",
      "show" => "show/#element_id#/",
		),
		"VARIABLE_ALIASES" => array(
			"index" => "",
			"edit" => "",
      "show" => ""
		)
	),
	false
);
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>