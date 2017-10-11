<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Сделки");
?><?$APPLICATION->IncludeComponent(
	"ucre:crm.client.deal",
	"",
	array()
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>