<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Поиск объектов через API Росреестра");
?>
<?$APPLICATION->IncludeComponent(
	"ucre:rosreestr.api", 
	"", 
	array(
		'AJAX_MODE' => 'Y',
		'AJAX_OPTION_SHADOW' => 'Y',
		'AJAX_OPTION_JUMP' => 'N'
	),
	false
);
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>