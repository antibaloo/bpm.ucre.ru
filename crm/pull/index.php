<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Сообщения от ВАТС Мегафон");
?>
<?
$APPLICATION->IncludeComponent("ucre:pull.megapbx", '');
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>