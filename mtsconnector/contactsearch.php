<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
include('functions.php');
$APPLICATION->SetTitle("Поиск контакта по номеру телефона");
$phone = "9877955786";
echo $phone."<br>";
echo  phonetype($phone, $DB)."<br>";
echo whose($phone, "C", $DB)."<br>";

$phone = "9877955693";
echo $phone."<br>";
echo  phonetype($phone, $DB)."<br>";
echo whose($phone, "O", $DB)."<br>";

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>