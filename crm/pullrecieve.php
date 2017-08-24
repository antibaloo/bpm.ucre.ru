<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Прием сообщений");
?>
<?
$APPLICATION->IncludeComponent(
	"ucre:pull.recieve", 
	"", 
	array(
		"USER" => $USER->GetID()
	),
  false
  );
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>