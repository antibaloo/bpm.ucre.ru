<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
if ($_GET['category'] == 0 || $_GET['category'] == 4) {
  $APPLICATION->IncludeComponent(
    "ucre:select.relevant.for_sell",
    "",
    array('ID' => $_GET['id']),
    false
  );
}elseif ($_GET['category'] == 2){
  $APPLICATION->IncludeComponent(
    "ucre:select.relevant.for_buy",
    "",
    array('ID' => $_GET['id']),
    false
  );
}else {
  echo "<h2>Для это категории заявок поиск встречных не производится!</h2>";
}
?>