<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Объекты недвижимости");
$APPLICATION->IncludeComponent(
	"baloo:crm.ro",
	".default",
	array(),
	false
);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>