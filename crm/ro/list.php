<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Переименование объектов недвижимости");
$APPLICATION->IncludeComponent(
  "bitrix:catalog.section",
  "",
  array(
  "IBLOCK_TYPE"=>"CRM_PRODUCT_CATALOG",
  "IBLOCK_ID"=> 42
),
  false
);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>