<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Тест нового грида");
?>
<h2>
  bitrix:crm.interface.grid with template titleflex
</h2>
<?
$APPLICATION->IncludeComponent(
  'bitrix:crm.interface.grid',
  'titleflex',
  array(),
  flase
);
?>
<h2>
  bitrix:main.ui.grid with template .default
</h2>

<?
$APPLICATION->IncludeComponent(
  'bitrix:main.ui.grid',
  'titleflex',
  array(),
  flase
);
?>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>