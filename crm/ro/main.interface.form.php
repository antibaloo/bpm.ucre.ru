<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Объекты недвижимости(тестируем компоненты с использованием гридов)");
$APPLICATION->IncludeComponent(
	"baloo:users.form",
	".default",
	array(),
	false
);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>