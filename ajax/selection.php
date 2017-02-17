<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
$APPLICATION->IncludeComponent(
  "ucre:select.request",
  "",
  array('ID' => $_GET['id'], 'CATEGORY' => $_GET['category']),
  false
);
?>