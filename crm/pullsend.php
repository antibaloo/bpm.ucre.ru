<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Отправка сообщений");
?>
<?
$APPLICATION->IncludeComponent(
	"ucre:pull.send", 
	"", 
	array(),
  false
  );
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>