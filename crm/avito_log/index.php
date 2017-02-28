<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
global $USER;
$APPLICATION->SetTitle("Лог загрузки объявлений на avito.ru");
$APPLICATION->IncludeComponent(
  'baloo:crm.avitolog.list',
  '',
  array('AVITOLOG_COUNT' => '10')
);
if (isset($_GET['AVITO_ID']) && $_GET['AVITO_ID']!="") {
  echo "<br>Расшифровки логов: <br>";
  $APPLICATION->IncludeComponent(
    'baloo:crm.avitologelement.list',
    '',
    array('AVITOELEMENT_COUNT' => '50',
          'AVITO_ID' => $_GET['AVITO_ID']
         )
  );
}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>