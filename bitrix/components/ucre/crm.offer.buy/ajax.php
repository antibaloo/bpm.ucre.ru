<?require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");?>
<?
$APPLICATION->IncludeComponent(
  "ucre:crm.offer.buy",
  "",
  array(
    'OFFER_AJAX_ID' => $_POST['OFFER_AJAX_ID'],
    'PARAMS' => array(
      'UF_CRM_58CFC7CDAAB96' => $_POST['UF_CRM_58CFC7CDAAB96'],
    )
  ),
  false
);
?>