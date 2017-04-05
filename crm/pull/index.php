<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Push & Pull");
?>
<h1>Push & Pull</h1>

<?
$APPLICATION->IncludeComponent("ucre:pull.test", '');
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>