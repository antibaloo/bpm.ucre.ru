<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
$APPLICATION->IncludeComponent(
  "ucre:crm.deal.potentials",
  "",
  array('ID' => $_GET['id'], 'CATEGORY' => $_GET['category']),
  false
);
?>