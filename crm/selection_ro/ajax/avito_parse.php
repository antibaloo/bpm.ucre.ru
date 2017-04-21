<?
require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule('crm');
require($_SERVER["DOCUMENT_ROOT"]."/include/ocr/ocr.php");
echo "Парсер подключен!";
?>